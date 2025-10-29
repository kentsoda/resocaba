<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

$pdo = db();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = getPostEnum('action', ['delete', 'archive', 'publish', 'draft'], '');
    $id = getPostInt('id', 0);
    $returnTo = trim(getPostString('return_to', 1024));
    $redirect = '/admin/notices/';
    if ($returnTo !== '' && strpos($returnTo, '/admin/notices') === 0) {
        $redirect = $returnTo;
    }

    $noticeKey = 'error';
    if ($id > 0 && $action !== '' && $pdo) {
        try {
            switch ($action) {
                case 'delete':
                    $stmt = $pdo->prepare('UPDATE announcements SET deleted_at = NOW(), updated_at = NOW() WHERE id = :id AND deleted_at IS NULL');
                    break;
                case 'archive':
                    $stmt = $pdo->prepare("UPDATE announcements SET status = 'archived', updated_at = NOW() WHERE id = :id AND deleted_at IS NULL");
                    break;
                case 'publish':
                    $stmt = $pdo->prepare("UPDATE announcements SET status = 'published', published_at = COALESCE(published_at, NOW()), updated_at = NOW() WHERE id = :id AND deleted_at IS NULL");
                    break;
                case 'draft':
                    $stmt = $pdo->prepare("UPDATE announcements SET status = 'draft', updated_at = NOW() WHERE id = :id AND deleted_at IS NULL");
                    break;
                default:
                    $stmt = null;
                    break;
            }
            if (isset($stmt) && $stmt) {
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $noticeKey = $action;
                }
            }
        } catch (Throwable $e) {
            error_log('[admin] notices/index action error: ' . $e->getMessage());
            $noticeKey = 'error';
        }
    }

    $separator = strpos($redirect, '?') === false ? '?' : '&';
    header('Location: ' . $redirect . $separator . 'notice=' . rawurlencode($noticeKey));
    exit;
}

renderLayout('お知らせ 一覧', function () use ($pdo) {
    $perPage = 20;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $status = isset($_GET['status']) ? (string)$_GET['status'] : '';
    $sort = isset($_GET['sort']) ? (string)$_GET['sort'] : 'published_desc';

    $allowedSort = [
        'published_desc' => '(a.published_at IS NULL), a.published_at DESC, a.id DESC',
        'published_asc' => '(a.published_at IS NULL) DESC, a.published_at ASC, a.id DESC',
        'created_desc' => 'a.created_at DESC',
        'created_asc' => 'a.created_at ASC',
        'updated_desc' => 'a.updated_at DESC',
        'updated_asc' => 'a.updated_at ASC',
        'title_asc' => 'a.title ASC',
        'title_desc' => 'a.title DESC',
    ];
    $orderBy = $allowedSort[$sort] ?? $allowedSort['published_desc'];

    $where = ['a.deleted_at IS NULL'];
    $params = [];
    if ($q !== '') {
        $where[] = '(a.title LIKE :q OR a.body_html LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($status !== '' && in_array($status, ['draft', 'published', 'archived'], true)) {
        $where[] = 'a.status = :status';
        $params[':status'] = $status;
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $total = 0;
    $rows = [];
    if ($pdo) {
        try {
            $sqlCount = "SELECT COUNT(*) FROM announcements a $whereSql";
            $stmt = $pdo->prepare($sqlCount);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            $total = (int)$stmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $sql = "SELECT a.id, a.title, a.status, a.published_at, a.updated_at, a.created_at
                    FROM announcements a
                    $whereSql
                    ORDER BY $orderBy
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] notices/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));
    $statusLabels = [
        'draft' => '下書き',
        'published' => '公開',
        'archived' => 'アーカイブ',
    ];
    $noticeKey = isset($_GET['notice']) ? (string)$_GET['notice'] : '';
    $messages = [
        'delete' => '削除しました',
        'archive' => 'アーカイブに移動しました',
        'publish' => '公開状態を更新しました',
        'draft' => '下書きに変更しました',
        'error' => '操作に失敗しました',
        'saved' => '保存しました',
    ];
    $currentMessage = $messages[$noticeKey] ?? '';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/admin/notices/';
    $parsed = parse_url($requestUri);
    $path = $parsed['path'] ?? '/admin/notices/';
    $queryArray = [];
    if (!empty($parsed['query'])) {
        parse_str($parsed['query'], $queryArray);
        unset($queryArray['notice']);
    }
    $currentUrl = $path . ($queryArray ? ('?' . http_build_query($queryArray)) : '');
    $returnToHidden = htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8');
    ?>
    <h1>お知らせ一覧</h1>
    <div style="margin-bottom:16px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px;">
      <form method="get" class="filters" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="タイトル・本文検索">
        <select name="status">
          <option value="">すべてのステータス</option>
          <?php foreach ($statusLabels as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $status === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
        <?php $sortLabels = [
            'published_desc' => '公開日が新しい順',
            'published_asc' => '公開日が古い順',
            'created_desc' => '作成日が新しい順',
            'created_asc' => '作成日が古い順',
            'updated_desc' => '更新日が新しい順',
            'updated_asc' => '更新日が古い順',
            'title_asc' => 'タイトル（A→Z）',
            'title_desc' => 'タイトル（Z→A）',
        ]; ?>
        <select name="sort">
          <?php foreach ($sortLabels as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $sort === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit">検索</button>
        <a href="/admin/notices/" style="margin-left:8px;">条件リセット</a>
      </form>
      <a href="/admin/notices/edit.php" class="button" style="align-self:flex-start;">新規作成</a>
    </div>

    <?php if ($currentMessage): ?>
      <div class="card" style="border-color:#3b82f6; margin-bottom:16px;"><?= htmlspecialchars($currentMessage, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="table-wrap">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead>
          <tr>
            <th style="width:60px;">ID</th>
            <th>タイトル</th>
            <th style="width:110px;">ステータス</th>
            <th style="width:170px;">公開日時</th>
            <th style="width:170px;">更新日時</th>
            <th style="width:160px;">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="6" style="text-align:center; color:#64748b;">該当するお知らせがありません</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): $id = (int)$r['id']; $statusKey = (string)$r['status']; ?>
              <tr>
                <td><?= $id ?></td>
                <td>
                  <a href="/admin/notices/edit.php?id=<?= $id ?>" style="font-weight:600; text-decoration:none; color:#1e40af;">
                    <?= htmlspecialchars((string)$r['title'], ENT_QUOTES, 'UTF-8') ?>
                  </a>
                  <div style="font-size:12px; color:#6b7280;">作成: <?= htmlspecialchars((string)($r['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                </td>
                <td><?= htmlspecialchars($statusLabels[$statusKey] ?? $statusKey, ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <?php if (!empty($r['published_at'])): ?>
                    <?= htmlspecialchars((string)$r['published_at'], ENT_QUOTES, 'UTF-8') ?>
                  <?php else: ?>
                    <span style="color:#94a3b8;">未設定</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars((string)($r['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <div style="display:flex; flex-direction:column; gap:4px;">
                    <a href="/admin/notices/edit.php?id=<?= $id ?>">編集</a>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                      <?php if ($statusKey !== 'published'): ?>
                        <form method="post" class="inline-form" data-confirm="公開状態を変更しますか？" style="display:inline;">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="id" value="<?= $id ?>">
                          <input type="hidden" name="action" value="publish">
                          <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                          <button type="submit" style="background:none; border:none; color:#16a34a; cursor:pointer;">公開</button>
                        </form>
                      <?php endif; ?>
                      <?php if ($statusKey !== 'draft'): ?>
                        <form method="post" class="inline-form" data-confirm="下書きに戻しますか？" style="display:inline;">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="id" value="<?= $id ?>">
                          <input type="hidden" name="action" value="draft">
                          <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                          <button type="submit" style="background:none; border:none; color:#d97706; cursor:pointer;">下書き</button>
                        </form>
                      <?php endif; ?>
                      <?php if ($statusKey !== 'archived'): ?>
                        <form method="post" class="inline-form" data-confirm="アーカイブに移動しますか？" style="display:inline;">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="id" value="<?= $id ?>">
                          <input type="hidden" name="action" value="archive">
                          <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                          <button type="submit" style="background:none; border:none; color:#2563eb; cursor:pointer;">アーカイブ</button>
                        </form>
                      <?php endif; ?>
                      <form method="post" class="inline-form" data-confirm="本当に削除しますか？" style="display:inline;">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                        <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer;">削除</button>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="pagination" style="margin-top:12px; display:flex; gap:6px; flex-wrap:wrap;">
        <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/notices/?' . http_build_query($baseParams); ?>
          <?php if ($p === $page): ?>
            <span style="padding:4px 8px; background:#1e293b; color:#fff; border-radius:4px;"><?= $p ?></span>
          <?php else: ?>
            <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" style="padding:4px 8px; background:#e2e8f0; color:#111; border-radius:4px; text-decoration:none;"><?= $p ?></a>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

    <script>
      document.querySelectorAll('form.inline-form[data-confirm]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
          var msg = form.getAttribute('data-confirm') || '実行しますか？';
          if (!window.confirm(msg)) {
            e.preventDefault();
          }
        });
      });
    </script>
    <?php
});
