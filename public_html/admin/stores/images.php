<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

renderLayout('店舗画像', function () {
    $pdo = db();
    $storeId = isset($_GET['store_id']) ? max(1, (int)$_GET['store_id']) : 0;
    if ($storeId <= 0) {
        echo '<p>不正な店舗IDです。</p>';
        return;
    }

    $errors = [];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        requireValidCsrfOrAbort();
        $action = isset($_POST['action']) ? (string)$_POST['action'] : '';

        if ($action === 'delete') {
            $imageId = isset($_POST['image_id']) ? (int)$_POST['image_id'] : 0;
            if ($imageId > 0 && $pdo) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM store_images WHERE id = :id AND store_id = :store_id');
                    $stmt->bindValue(':id', $imageId, PDO::PARAM_INT);
                    $stmt->bindValue(':store_id', $storeId, PDO::PARAM_INT);
                    $stmt->execute();
                    header('Location: /admin/stores/images.php?store_id=' . $storeId . '&deleted=1');
                    exit;
                } catch (Throwable $e) {
                    error_log('[admin] stores/images delete error: ' . $e->getMessage());
                    $errors[] = '削除に失敗しました。';
                }
            }
        } elseif ($action === 'create') {
            $imageUrl = trim(getPostString('new_image_url', 2048));
            $sortOrder = isset($_POST['new_sort_order']) ? (int)$_POST['new_sort_order'] : 0;
            if ($imageUrl === '') {
                $errors[] = '画像URLを入力してください。';
            } elseif (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $errors[] = '画像URLの形式が正しくありません。';
            } elseif ($pdo) {
                try {
                    $stmt = $pdo->prepare('INSERT INTO store_images (store_id, image_url, sort_order) VALUES (:store_id, :image_url, :sort_order)');
                    $stmt->bindValue(':store_id', $storeId, PDO::PARAM_INT);
                    $stmt->bindValue(':image_url', $imageUrl, PDO::PARAM_STR);
                    $stmt->bindValue(':sort_order', max(0, $sortOrder), PDO::PARAM_INT);
                    $stmt->execute();
                    header('Location: /admin/stores/images.php?store_id=' . $storeId . '&created=1');
                    exit;
                } catch (Throwable $e) {
                    error_log('[admin] stores/images create error: ' . $e->getMessage());
                    $errors[] = '画像の追加に失敗しました。';
                }
            }
        } else {
            $items = isset($_POST['images']) && is_array($_POST['images']) ? $_POST['images'] : [];
            if ($items && $pdo) {
                try {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare('UPDATE store_images SET image_url = :image_url, sort_order = :sort_order WHERE id = :id AND store_id = :store_id');
                    foreach ($items as $imageId => $item) {
                        $imageId = (int)$imageId;
                        if ($imageId <= 0) {
                            continue;
                        }
                        $imageUrl = isset($item['image_url']) ? trim((string)$item['image_url']) : '';
                        $sortOrder = isset($item['sort_order']) ? (int)$item['sort_order'] : 0;
                        if ($imageUrl === '') {
                            $errors[] = '画像URLは必須です。';
                            break;
                        }
                        if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                            $errors[] = '画像URLの形式が正しくありません。';
                            break;
                        }
                        $stmt->bindValue(':image_url', $imageUrl, PDO::PARAM_STR);
                        $stmt->bindValue(':sort_order', max(0, $sortOrder), PDO::PARAM_INT);
                        $stmt->bindValue(':id', $imageId, PDO::PARAM_INT);
                        $stmt->bindValue(':store_id', $storeId, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    if (empty($errors)) {
                        $pdo->commit();
                        header('Location: /admin/stores/images.php?store_id=' . $storeId . '&saved=1');
                        exit;
                    }
                    $pdo->rollBack();
                } catch (Throwable $e) {
                    if ($pdo && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    error_log('[admin] stores/images update error: ' . $e->getMessage());
                    $errors[] = '保存に失敗しました。';
                }
            }
        }
    }

    $storeName = '';
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT name FROM stores WHERE id = :id LIMIT 1');
            $stmt->bindValue(':id', $storeId, PDO::PARAM_INT);
            $stmt->execute();
            $storeName = (string)$stmt->fetchColumn();
        } catch (Throwable $e) {
            error_log('[admin] stores/images store fetch error: ' . $e->getMessage());
        }
    }
    if ($storeName === '') {
        echo '<p>店舗が見つかりません。</p>';
        return;
    }

    $images = [];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT id, image_url, sort_order, created_at FROM store_images WHERE store_id = :store_id AND deleted_at IS NULL ORDER BY sort_order ASC, id ASC');
            $stmt->bindValue(':store_id', $storeId, PDO::PARAM_INT);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] stores/images list error: ' . $e->getMessage());
        }
    }

    $saved = isset($_GET['saved']);
    $created = isset($_GET['created']);
    $deleted = isset($_GET['deleted']);

    ?>
    <h1>店舗画像</h1>
    <p><a href="/admin/stores/edit.php?id=<?= (int)$storeId ?>">店舗編集に戻る</a></p>
    <h2><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if ($saved): ?>
      <div class="card" style="border-color:#22c55e;">並び順を保存しました。</div>
    <?php endif; ?>
    <?php if ($created): ?>
      <div class="card" style="border-color:#22c55e;">画像を追加しました。</div>
    <?php endif; ?>
    <?php if ($deleted): ?>
      <div class="card" style="border-color:#22c55e;">画像を削除しました。</div>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444;">
        <?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="post" action="" id="update-form" style="margin:12px 0;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="save">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead>
          <tr>
            <th style="width:60px;">ID</th>
            <th style="width:220px;">プレビュー</th>
            <th>画像URL</th>
            <th style="width:120px;">並び順</th>
            <th style="width:160px;">作成日時</th>
            <th style="width:120px;">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$images): ?>
            <tr><td colspan="6" style="text-align:center; color:#64748b;">画像が登録されていません</td></tr>
          <?php else: ?>
            <?php foreach ($images as $img): $imgId = (int)$img['id']; ?>
              <tr>
                <td><?= $imgId ?></td>
                <td>
                  <?php if (!empty($img['image_url'])): ?>
                    <img src="<?= htmlspecialchars((string)$img['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-width:200px; max-height:120px; object-fit:cover;">
                  <?php endif; ?>
                </td>
                <td>
                  <input type="url" name="images[<?= $imgId ?>][image_url]" value="<?= htmlspecialchars((string)$img['image_url'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;">
                </td>
                <td>
                  <input type="number" name="images[<?= $imgId ?>][sort_order]" value="<?= (int)$img['sort_order'] ?>" min="0" style="width:100px;">
                </td>
                <td><?= htmlspecialchars((string)($img['created_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <button type="submit" form="delete-form-<?= $imgId ?>" onclick="return confirm('削除しますか？');">削除</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div style="margin-top:8px;"><button type="submit">変更を保存</button></div>
    </form>

    <?php foreach ($images as $img): $imgId = (int)$img['id']; ?>
      <form method="post" action="" id="delete-form-<?= $imgId ?>" style="display:none;">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="image_id" value="<?= $imgId ?>">
      </form>
    <?php endforeach; ?>

    <form method="post" action="" style="margin-top:20px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="create">
      <label style="flex:1 1 320px;">新規画像URL<br>
        <input type="url" name="new_image_url" value="" style="width:100%;">
      </label>
      <label style="width:160px;">並び順<br>
        <input type="number" name="new_sort_order" value="0" min="0" style="width:100%;">
      </label>
      <button type="submit" class="button">画像を追加</button>
    </form>
    <?php
});
