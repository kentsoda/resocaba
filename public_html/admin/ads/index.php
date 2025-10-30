<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

$pdo = db();

function redirectWithNotice(string $notice): void {
    header('Location: /admin/ads/index.php?notice=' . rawurlencode($notice));
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = getPostEnum('action', ['toggle_active', 'move_up', 'move_down', 'delete'], '');
    $id = getPostInt('id', 0);

    if (!$pdo || $id <= 0 || $action === '') {
        redirectWithNotice('error');
    }

    try {
        if ($action === 'toggle_active') {
            $stmt = $pdo->prepare('UPDATE ad_banners SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END, updated_at = NOW() WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirectWithNotice($stmt->rowCount() > 0 ? 'toggled' : 'error');
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM ad_banners WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirectWithNotice($stmt->rowCount() > 0 ? 'deleted' : 'error');
        } else {
            $pdo->beginTransaction();
            $stmtCurrent = $pdo->prepare('SELECT id, sort_order FROM ad_banners WHERE id = :id LIMIT 1 FOR UPDATE');
            $stmtCurrent->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtCurrent->execute();
            $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                $pdo->rollBack();
                redirectWithNotice('error');
            }
            $sortOrder = (int)$current['sort_order'];
            if ($action === 'move_up') {
                $stmtNeighbor = $pdo->prepare('SELECT id, sort_order FROM ad_banners WHERE (sort_order < :sort_order) OR (sort_order = :sort_order AND id < :id) ORDER BY sort_order DESC, id DESC LIMIT 1 FOR UPDATE');
            } else {
                $stmtNeighbor = $pdo->prepare('SELECT id, sort_order FROM ad_banners WHERE (sort_order > :sort_order) OR (sort_order = :sort_order AND id > :id) ORDER BY sort_order ASC, id ASC LIMIT 1 FOR UPDATE');
            }
            $stmtNeighbor->bindValue(':sort_order', $sortOrder, PDO::PARAM_INT);
            $stmtNeighbor->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtNeighbor->execute();
            $neighbor = $stmtNeighbor->fetch(PDO::FETCH_ASSOC);
            if (!$neighbor) {
                $pdo->commit();
                redirectWithNotice('boundary');
            }
            $neighborId = (int)$neighbor['id'];
            $neighborSort = (int)$neighbor['sort_order'];

            $stmtUpdateCurrent = $pdo->prepare('UPDATE ad_banners SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id');
            $stmtUpdateCurrent->bindValue(':sort_order', $neighborSort, PDO::PARAM_INT);
            $stmtUpdateCurrent->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtUpdateCurrent->execute();

            $stmtUpdateNeighbor = $pdo->prepare('UPDATE ad_banners SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id');
            $stmtUpdateNeighbor->bindValue(':sort_order', $sortOrder, PDO::PARAM_INT);
            $stmtUpdateNeighbor->bindValue(':id', $neighborId, PDO::PARAM_INT);
            $stmtUpdateNeighbor->execute();
            $pdo->commit();
            redirectWithNotice('reordered');
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[admin] ads/index action error: ' . $e->getMessage());
        redirectWithNotice('error');
    }
}

renderLayout('広告バナー一覧', function () use ($pdo) {
    $ads = [];
    if ($pdo) {
        try {
            $stmt = $pdo->query('SELECT id, image_url, link_url, target_blank, is_active, sort_order, updated_at FROM ad_banners ORDER BY sort_order ASC, id ASC');
            $ads = $stmt ? ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) : [];
        } catch (Throwable $e) {
            error_log('[admin] ads/index fetch error: ' . $e->getMessage());
        }
    }

    $noticeKey = isset($_GET['notice']) ? (string)$_GET['notice'] : '';
    $messages = [
        'saved' => '保存しました。',
        'created' => '広告を作成しました。',
        'deleted' => '広告を削除しました。',
        'toggled' => '表示状態を更新しました。',
        'reordered' => '並び順を更新しました。',
        'boundary' => 'これ以上移動できません。',
        'error' => '操作に失敗しました。',
    ];
    $noticeClasses = [
        'saved' => 'alert-success',
        'created' => 'alert-success',
        'deleted' => 'alert-success',
        'toggled' => 'alert-success',
        'reordered' => 'alert-success',
        'boundary' => 'alert-warning',
        'error' => 'alert-danger',
    ];
    $currentMessage = $messages[$noticeKey] ?? '';
    $currentClass = $noticeClasses[$noticeKey] ?? 'alert-info';
    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <h1 class="mb-0">広告バナー一覧</h1>
      <a href="/admin/ads/edit.php" class="btn btn-success">新規作成</a>
    </div>

    <?php if ($currentMessage): ?>
      <div class="alert <?= htmlspecialchars($currentClass, ENT_QUOTES, 'UTF-8') ?>" role="alert">
        <?= htmlspecialchars($currentMessage, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width:90px;">表示順</th>
              <th scope="col" style="width:60px;">ID</th>
              <th scope="col">画像</th>
              <th scope="col">リンクURL</th>
              <th scope="col" style="width:90px;">target</th>
              <th scope="col" style="width:90px;">表示</th>
              <th scope="col" class="text-end" style="width:220px;">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$ads): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">広告バナーが登録されていません。</td>
              </tr>
            <?php else: ?>
              <?php foreach ($ads as $index => $ad): $id = (int)$ad['id']; ?>
                <tr>
                  <td><?= (int)$ad['sort_order'] ?></td>
                  <td><?= $id ?></td>
                  <td>
                    <?php if (!empty($ad['image_url'])): ?>
                      <img src="<?= htmlspecialchars($ad['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="広告画像" class="img-fluid" style="max-width:180px; max-height:100px; object-fit:contain;">
                    <?php endif; ?>
                  </td>
                  <td class="text-break">
                    <?php if (!empty($ad['link_url'])): ?>
                      <a href="<?= htmlspecialchars($ad['link_url'], ENT_QUOTES, 'UTF-8') ?>" target="<?= ((int)$ad['target_blank'] === 1) ? '_blank' : '_self' ?>" rel="noopener"><?= htmlspecialchars($ad['link_url'], ENT_QUOTES, 'UTF-8') ?></a>
                    <?php endif; ?>
                  </td>
                  <td><?= ((int)$ad['target_blank'] === 1) ? '_blank' : '_self' ?></td>
                  <td>
                    <span class="badge bg-<?= ((int)$ad['is_active'] === 1) ? 'success' : 'danger' ?>">
                      <?= ((int)$ad['is_active'] === 1) ? '表示' : '非表示' ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm" role="group">
                      <form method="post" class="d-inline">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="move_up">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-secondary" title="上へ">↑</button>
                      </form>
                      <form method="post" class="d-inline">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="move_down">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-secondary" title="下へ">↓</button>
                      </form>
                      <form method="post" class="d-inline">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="toggle_active">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-primary">
                          <?= ((int)$ad['is_active'] === 1) ? '非表示にする' : '表示にする' ?>
                        </button>
                      </form>
                      <a href="/admin/ads/edit.php?id=<?= $id ?>" class="btn btn-outline-success">編集</a>
                      <form method="post" class="d-inline" onsubmit="return confirm('広告を削除しますか？');">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-danger">削除</button>
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
    <?php
});
