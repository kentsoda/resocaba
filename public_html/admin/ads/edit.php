<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

function redirectToAdsIndex(string $notice): void {
    header('Location: /admin/ads/index.php?notice=' . rawurlencode($notice));
    exit;
}

$pdo = db();
$id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
$isEdit = $id > 0;

$values = [
    'image_url' => '',
    'link_url' => '',
    'target_blank' => 1,
    'is_active' => 1,
    'sort_order' => 0,
];
$errors = [];
$loadError = '';

if ($isEdit) {
    if (!$pdo) {
        $loadError = 'db';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id, image_url, link_url, target_blank, is_active, sort_order FROM ad_banners WHERE id = :id LIMIT 1');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $ad = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ad) {
                foreach ($values as $key => $_) {
                    if (isset($ad[$key])) {
                        if ($key === 'target_blank' || $key === 'is_active' || $key === 'sort_order') {
                            $values[$key] = (int)$ad[$key];
                        } else {
                            $values[$key] = (string)$ad[$key];
                        }
                    }
                }
            } else {
                $loadError = 'not_found';
            }
        } catch (Throwable $e) {
            error_log('[admin] ads/edit fetch error: ' . $e->getMessage());
            $loadError = 'db';
        }
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = getPostEnum('action', ['save', 'delete'], '');

    if ($action === 'delete') {
        if (!$isEdit || !$pdo) {
            redirectToAdsIndex('error');
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM ad_banners WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirectToAdsIndex($stmt->rowCount() > 0 ? 'deleted' : 'error');
        } catch (Throwable $e) {
            error_log('[admin] ads/edit delete error: ' . $e->getMessage());
            redirectToAdsIndex('error');
        }
    }

    $values['image_url'] = trim(getPostString('image_url', 1024));
    $values['link_url'] = trim(getPostString('link_url', 1024));
    $values['target_blank'] = isset($_POST['target_blank']) ? 1 : 0;
    $values['is_active'] = isset($_POST['is_active']) ? 1 : 0;
    $values['sort_order'] = max(0, getPostInt('sort_order', 0));

    if ($values['image_url'] === '' || !filter_var($values['image_url'], FILTER_VALIDATE_URL)) {
        $errors[] = '画像URLを正しく入力してください。';
    }
    if ($values['link_url'] !== '' && !filter_var($values['link_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'リンクURLの形式が正しくありません。';
    }
    if (empty($errors) && $pdo) {
        try {
            if ($isEdit) {
                $stmt = $pdo->prepare('UPDATE ad_banners SET image_url = :image_url, link_url = :link_url, target_blank = :target_blank, is_active = :is_active, sort_order = :sort_order, updated_at = NOW() WHERE id = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare('INSERT INTO ad_banners (image_url, link_url, target_blank, is_active, sort_order, created_at, updated_at) VALUES (:image_url, :link_url, :target_blank, :is_active, :sort_order, NOW(), NOW())');
            }
            $stmt->bindValue(':image_url', $values['image_url']);
            $stmt->bindValue(':link_url', $values['link_url']);
            $stmt->bindValue(':target_blank', $values['target_blank'], PDO::PARAM_INT);
            $stmt->bindValue(':is_active', $values['is_active'], PDO::PARAM_INT);
            $stmt->bindValue(':sort_order', $values['sort_order'], PDO::PARAM_INT);
            $stmt->execute();

            redirectToAdsIndex($isEdit ? 'saved' : 'created');
        } catch (Throwable $e) {
            error_log('[admin] ads/edit save error: ' . $e->getMessage());
            $errors[] = '保存中にエラーが発生しました。';
        }
    }
}

renderLayout('広告バナー編集', function () use ($isEdit, $values, $errors, $loadError) {
    if ($loadError === 'db') {
        echo '<p>データベースに接続できませんでした。</p>';
        return;
    }
    if ($loadError === 'not_found') {
        echo '<p>指定された広告が見つかりません。</p>';
        return;
    }
    ?>
    <h1 class="mb-4"><?= $isEdit ? '広告バナー編集' : '広告バナー新規作成' ?></h1>

    <?php if ($errors): ?>
      <div class="alert alert-danger" role="alert">
        <ul class="mb-0 ps-3">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm">
      <div class="card-body">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="save">
        <div class="row g-3">
          <div class="col-12">
            <label for="image_url" class="form-label">画像URL<span class="text-danger ms-1">*</span></label>
            <input type="url" class="form-control" id="image_url" name="image_url" value="<?= htmlspecialchars((string)$values['image_url'], ENT_QUOTES, 'UTF-8') ?>" required>
          </div>
          <div class="col-12">
            <label for="link_url" class="form-label">リンクURL</label>
            <input type="url" class="form-control" id="link_url" name="link_url" value="<?= htmlspecialchars((string)$values['link_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://example.com">
            <div class="form-text">空欄の場合はリンクなしで表示されます。</div>
          </div>
          <div class="col-12">
            <div class="d-flex flex-wrap gap-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="target_blank" name="target_blank" value="1" <?= ((int)$values['target_blank'] === 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="target_blank">新しいタブで開く（target="_blank"）</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= ((int)$values['is_active'] === 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">公開（表示）</label>
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-md-3 col-lg-2">
            <label for="sort_order" class="form-label">表示順</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= (int)$values['sort_order'] ?>" step="1" min="0">
            <div class="form-text">数値が小さいほど上に表示されます。</div>
          </div>
        </div>
      </div>
      <div class="card-footer d-flex gap-2 flex-wrap justify-content-between">
        <div>
          <button type="submit" class="btn btn-primary">保存する</button>
          <a href="/admin/ads/index.php" class="btn btn-outline-secondary ms-2">一覧に戻る</a>
        </div>
      </div>
    </form>

    <?php if ($isEdit): ?>
      <form method="post" class="mt-4" onsubmit="return confirm('この広告を削除しますか？');">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="btn btn-outline-danger">削除する</button>
      </form>
    <?php endif; ?>
    <?php
});
