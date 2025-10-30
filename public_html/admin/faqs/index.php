<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

$pdo = db();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = getPostEnum('action', ['delete', 'publish', 'draft', 'update_order'], '');
    $id = getPostInt('id', 0);
    $returnTo = trim(getPostString('return_to', 1024));
    $redirect = '/admin/faqs/';
    if ($returnTo !== '' && strpos($returnTo, '/admin/faqs') === 0) {
        $redirect = $returnTo;
    }

    $noticeKey = 'error';
    if ($pdo && $action !== '') {
        try {
            switch ($action) {
                case 'delete':
                    if ($id > 0) {
                        $stmt = $pdo->prepare('DELETE FROM faqs WHERE id = :id');
                        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $noticeKey = 'deleted';
                        }
                    }
                    break;
                case 'publish':
                case 'draft':
                    if ($id > 0) {
                        $status = $action === 'publish' ? 'published' : 'draft';
                        $stmt = $pdo->prepare("UPDATE faqs SET status = :status, updated_at = NOW() WHERE id = :id");
                        $stmt->bindValue(':status', $status);
                        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        if ($stmt->rowCount() > 0) {
                            $noticeKey = $status === 'published' ? 'published' : 'draft';
                        }
                    }
                    break;
                case 'update_order':
                    $orderRaw = isset($_POST['order']) && is_array($_POST['order']) ? $_POST['order'] : [];
                    $updates = [];
                    foreach ($orderRaw as $faqId => $orderValue) {
                        if (!preg_match('/^\d+$/', (string)$faqId)) {
                            continue;
                        }
                        $faqIdInt = (int)$faqId;
                        $orderInt = filter_var($orderValue, FILTER_VALIDATE_INT);
                        if ($orderInt === false) {
                            $orderInt = 0;
                        }
                        $updates[$faqIdInt] = $orderInt;
                    }
                    if ($updates) {
                        $pdo->beginTransaction();
                        $stmt = $pdo->prepare('UPDATE faqs SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id');
                        foreach ($updates as $faqIdInt => $orderInt) {
                            $stmt->bindValue(':sort_order', $orderInt, PDO::PARAM_INT);
                            $stmt->bindValue(':id', $faqIdInt, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                        $pdo->commit();
                        $noticeKey = 'order_updated';
                    }
                    break;
            }
        } catch (Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('[admin] faqs/index action error: ' . $e->getMessage());
            $noticeKey = 'error';
        }
    }

    $separator = strpos($redirect, '?') === false ? '?' : '&';
    header('Location: ' . $redirect . $separator . 'notice=' . rawurlencode($noticeKey));
    exit;
}

renderLayout('FAQ一覧', function () use ($pdo) {
    $perPage = 20;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $status = isset($_GET['status']) ? (string)$_GET['status'] : '';

    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = '(question LIKE :q OR answer_html LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($status !== '' && in_array($status, ['published', 'draft'], true)) {
        $where[] = 'status = :status';
        $params[':status'] = $status;
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $total = 0;
    $rows = [];
    if ($pdo) {
        try {
            $sqlCount = "SELECT COUNT(*) FROM faqs $whereSql";
            $stmt = $pdo->prepare($sqlCount);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $total = (int)$stmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $sql = "SELECT id, question, status, sort_order, updated_at, created_at
                    FROM faqs
                    $whereSql
                    ORDER BY sort_order ASC, id ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] faqs/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }

    $statusLabels = [
        'published' => '公開',
        'draft' => '下書き',
    ];

    $noticeKey = isset($_GET['notice']) ? (string)$_GET['notice'] : '';
    $messages = [
        'saved' => '保存しました',
        'deleted' => '削除しました',
        'published' => '公開状態に更新しました',
        'draft' => '下書きに変更しました',
        'order_updated' => '表示順を更新しました',
        'error' => '操作に失敗しました',
    ];
    $noticeClasses = [
        'saved' => 'alert-success',
        'deleted' => 'alert-success',
        'published' => 'alert-success',
        'draft' => 'alert-warning',
        'order_updated' => 'alert-info',
        'error' => 'alert-danger',
    ];
    $currentMessage = $messages[$noticeKey] ?? '';
    $currentClass = $noticeClasses[$noticeKey] ?? 'alert-info';

    $requestUri = $_SERVER['REQUEST_URI'] ?? '/admin/faqs/';
    $parsed = parse_url($requestUri);
    $path = $parsed['path'] ?? '/admin/faqs/';
    $queryArray = [];
    if (!empty($parsed['query'])) {
        parse_str($parsed['query'], $queryArray);
        unset($queryArray['notice']);
    }
    $currentUrl = $path . ($queryArray ? ('?' . http_build_query($queryArray)) : '');
    $returnToHidden = htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8');
    ?>
    <h1 class="mb-4">FAQ一覧</h1>
    <div class="d-flex flex-wrap gap-3 align-items-end justify-content-between mb-3">
      <form method="get" class="row gy-2 gx-2 align-items-end flex-grow-1">
        <div class="col-sm-6 col-md-4">
          <label for="filter-q" class="form-label">質問・回答検索</label>
          <input type="text" class="form-control" id="filter-q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="キーワードを入力">
        </div>
        <div class="col-sm-6 col-md-3 col-lg-2">
          <label for="filter-status" class="form-label">ステータス</label>
          <select class="form-select" id="filter-status" name="status">
            <option value="">すべて</option>
            <?php foreach ($statusLabels as $key => $label): ?>
              <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $status === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">検索</button>
          <a href="/admin/faqs/" class="btn btn-outline-secondary ms-2">条件リセット</a>
        </div>
      </form>
      <a href="/admin/faqs/edit.php" class="btn btn-success">新規作成</a>
    </div>

    <?php if ($currentMessage): ?>
      <div class="alert <?= htmlspecialchars($currentClass, ENT_QUOTES, 'UTF-8') ?>" role="alert">
        <?= htmlspecialchars($currentMessage, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form id="order-form" method="post" class="d-none">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="update_order">
      <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col" >ID</th>
              <th scope="col">質問</th>
              <th scope="col" >ステータス</th>
              <th scope="col" >表示順</th>
              <th scope="col" >更新日時</th>
              <th scope="col"  class="text-end">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">該当するFAQがありません</td>
              </tr>
            <?php else: ?>
              <?php foreach ($rows as $r): $id = (int)$r['id']; $statusKey = (string)$r['status']; ?>
                <tr>
                  <td><?= $id ?></td>
                  <td>
                    <a href="/admin/faqs/edit.php?id=<?= $id ?>" class="fw-semibold text-decoration-none"><?= htmlspecialchars((string)$r['question'], ENT_QUOTES, 'UTF-8') ?></a>
                    <div class="text-muted small">作成: <?= htmlspecialchars((string)($r['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                  </td>
                  <td><?= htmlspecialchars($statusLabels[$statusKey] ?? $statusKey, ENT_QUOTES, 'UTF-8') ?></td>
                  <td >
                    <input type="number" class="form-control form-control-sm" name="order[<?= $id ?>]" value="<?= (int)($r['sort_order'] ?? 0) ?>" form="order-form">
                  </td>
                  <td><?= htmlspecialchars((string)($r['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td class="text-end">
                    <div class="d-flex flex-wrap justify-content-end gap-2">
                      <a href="/admin/faqs/edit.php?id=<?= $id ?>" class="btn btn-outline-primary btn-sm">編集</a>
                      <?php if ($statusKey !== 'published'): ?>
                        <form method="post" class="d-inline" data-confirm="公開状態にしますか？">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="id" value="<?= $id ?>">
                          <input type="hidden" name="action" value="publish">
                          <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                          <button type="submit" class="btn btn-outline-success btn-sm">公開</button>
                        </form>
                      <?php endif; ?>
                      <?php if ($statusKey !== 'draft'): ?>
                        <form method="post" class="d-inline" data-confirm="下書きにしますか？">
                          <?php csrf_field(); ?>
                          <input type="hidden" name="id" value="<?= $id ?>">
                          <input type="hidden" name="action" value="draft">
                          <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                          <button type="submit" class="btn btn-outline-warning btn-sm">下書き</button>
                        </form>
                      <?php endif; ?>
                      <form method="post" class="d-inline" data-confirm="本当に削除しますか？">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="return_to" value="<?= $returnToHidden ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">削除</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav aria-label="FAQページネーション" class="mt-4">
        <ul class="pagination flex-wrap">
          <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/faqs/?' . http_build_query($baseParams); ?>
            <li class="page-item<?= $p === $page ? ' active' : '' ?>">
              <?php if ($p === $page): ?>
                <span class="page-link"><?= $p ?></span>
              <?php else: ?>
                <a class="page-link" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><?= $p ?></a>
              <?php endif; ?>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
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
