<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';

renderLayout('求人画像', function () {
    $pdo = db();
    $jobId = isset($_GET['job_id']) ? max(1, (int)$_GET['job_id']) : 0;
    if ($jobId <= 0) {
        echo '<p>不正な求人IDです。</p>';
        return;
    }

    // Handle delete
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        requireValidCsrfOrAbort();
        $imgId = isset($_POST['image_id']) ? (int)$_POST['image_id'] : 0;
        if ($imgId > 0 && $pdo) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('SELECT path FROM job_images WHERE id=:id AND job_id=:job_id');
                $stmt->bindValue(':id', $imgId, PDO::PARAM_INT);
                $stmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
                $stmt->execute();
                $path = $stmt->fetchColumn();
                if ($path) {
                    $stmt = $pdo->prepare('DELETE FROM job_images WHERE id=:id AND job_id=:job_id');
                    $stmt->bindValue(':id', $imgId, PDO::PARAM_INT);
                    $stmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
                    $stmt->execute();
                    $full = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($path, '/');
                    if (strpos(realpath(dirname($full)) ?: '', realpath($_SERVER['DOCUMENT_ROOT'] . '/uploads/jobs') ?: '') === 0 && file_exists($full)) {
                        @unlink($full);
                    }
                }
                $pdo->commit();
                header('Location: /admin/jobs/images.php?job_id=' . $jobId);
                exit;
            } catch (Throwable $e) {
                if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
                error_log('[admin] jobs/images delete error: ' . $e->getMessage());
            }
        }
    }

    // Handle reorder
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reorder') {
        requireValidCsrfOrAbort();
        $orders = isset($_POST['order']) && is_array($_POST['order']) ? $_POST['order'] : [];
        if ($orders && $pdo) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('UPDATE job_images SET sort_order=:sort_order WHERE id=:id AND job_id=:job_id');
                foreach ($orders as $imgId => $sortStr) {
                    $imgId = (int)$imgId;
                    $so = max(0, (int)$sortStr);
                    $stmt->bindValue(':sort_order', $so, PDO::PARAM_INT);
                    $stmt->bindValue(':id', $imgId, PDO::PARAM_INT);
                    $stmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
                    $stmt->execute();
                }
                $pdo->commit();
                header('Location: /admin/jobs/images.php?job_id=' . $jobId);
                exit;
            } catch (Throwable $e) {
                if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
                error_log('[admin] jobs/images reorder error: ' . $e->getMessage());
            }
        }
    }

    // List
    $images = [];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT id, path, sort_order, created_at FROM job_images WHERE job_id=:job_id ORDER BY sort_order ASC, id ASC');
            $stmt->bindValue(':job_id', $jobId, PDO::PARAM_INT);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] jobs/images list error: ' . $e->getMessage());
        }
    }

    ?>
    <h1>求人画像</h1>
    <p><a href="/admin/jobs/edit.php?id=<?= (int)$jobId ?>">求人編集に戻る</a></p>

    <form method="post" action="" style="margin:12px 0;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="reorder">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead><tr><th>ID</th><th>画像</th><th>パス</th><th>並び順</th><th>作成</th><th>操作</th></tr></thead>
        <tbody>
          <?php if (!$images): ?>
            <tr><td colspan="6" style="text-align:center; color:#64748b;">画像がありません</td></tr>
          <?php else: ?>
            <?php foreach ($images as $im): $id=(int)$im['id']; $path=(string)$im['path']; $so=(int)$im['sort_order']; ?>
              <tr>
                <td><?= $id ?></td>
                <td><img src="<?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-height:80px"></td>
                <td><?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?></td>
                <td><input type="number" name="order[<?= $id ?>]" value="<?= $so ?>" min="0" style="width:80px"></td>
                <td><?= htmlspecialchars((string)($im['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <form method="post" action="" onsubmit="return confirm('削除しますか？');" style="display:inline">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="image_id" value="<?= $id ?>">
                    <button type="submit">削除</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div style="margin-top:8px;"><button type="submit">並び順を保存</button></div>
    </form>
    <?php
});


