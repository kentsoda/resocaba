<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

$pdo = db();
$id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
$isEdit = $id > 0;

$values = [
    'name' => '',
    'category' => '',
    'sort_order' => 0,
];
$slug = '';
$errors = [];
$notFound = false;

if ($isEdit && $pdo) {
    try {
        $stmt = $pdo->prepare('SELECT id, name, category, sort_order, slug FROM tags WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $values['name'] = (string)$row['name'];
            $values['category'] = (string)($row['category'] ?? '');
            $values['sort_order'] = (int)($row['sort_order'] ?? 0);
            $slug = (string)($row['slug'] ?? '');
        } else {
            $notFound = true;
        }
    } catch (Throwable $e) {
        error_log('[admin] tags/edit load error: ' . $e->getMessage());
        $notFound = true;
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !$notFound) {
    requireValidCsrfOrAbort();
    $values['name'] = trim(getPostString('name', 100));
    $values['category'] = trim(getPostString('category', 64));
    $values['sort_order'] = getPostInt('sort_order', 0);

    if ($values['name'] === '') {
        $errors[] = '名前は必須です';
    }
    if (mb_strlen($values['category'], 'UTF-8') > 64) {
        $errors[] = 'カテゴリは64文字以内で入力してください';
    }

    if (!$errors && $pdo) {
        try {
            if ($isEdit) {
                $stmt = $pdo->prepare('UPDATE tags SET name = :name, category = :category, sort_order = :sort_order WHERE id = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare('INSERT INTO tags (name, category, sort_order, created_at) VALUES (:name, :category, :sort_order, NOW())');
            }
            $stmt->bindValue(':name', $values['name']);
            if ($values['category'] === '') {
                $stmt->bindValue(':category', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':category', $values['category']);
            }
            $stmt->bindValue(':sort_order', (int)$values['sort_order'], PDO::PARAM_INT);
            $stmt->execute();

            if (!$isEdit) {
                $id = (int)$pdo->lastInsertId();
            }

            header('Location: /admin/tags/edit.php?id=' . $id . '&saved=1');
            exit;
        } catch (Throwable $e) {
            error_log('[admin] tags/edit save error: ' . $e->getMessage());
            $errors[] = '保存に失敗しました';
        }
    }
}

$categoryOptions = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT DISTINCT category FROM tags WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC");
        $categoryOptions = $stmt ? ($stmt->fetchAll(PDO::FETCH_COLUMN) ?: []) : [];
    } catch (Throwable $e) {
        error_log('[admin] tags/edit category options error: ' . $e->getMessage());
    }
}

renderLayout($isEdit ? 'タグ編集' : 'タグ作成', function () use ($values, $errors, $isEdit, $id, $slug, $categoryOptions, $notFound) {
    ?>
    <h1><?= $isEdit ? 'タグ編集' : 'タグ作成' ?></h1>
    <?php if ($notFound): ?>
      <div class="card" style="border-color:#ef4444;">タグが見つかりません</div>
      <p><a href="/admin/tags/">一覧に戻る</a></p>
      <?php return; ?>
    <?php endif; ?>

    <?php if ($isEdit): ?>
      <p>ID: <?= (int)$id ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['saved'])): ?>
      <div class="card" style="border-color:#22c55e;">保存しました</div>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444;">
        <?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <?php csrf_field(); ?>
      <div style="display:grid; gap:12px; max-width:480px;">
        <label>名前<span style="color:#ef4444;">*</span><br>
          <input type="text" name="name" value="<?= htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') ?>" required>
        </label>
        <label>カテゴリ<br>
          <input type="text" name="category" list="tag-category-list" value="<?= htmlspecialchars($values['category'], ENT_QUOTES, 'UTF-8') ?>" placeholder="例: 渡航, 給与, 住居">
        </label>
        <datalist id="tag-category-list">
          <?php foreach ($categoryOptions as $cat): ?>
            <option value="<?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?>"></option>
          <?php endforeach; ?>
        </datalist>
        <label>並び順<br>
          <input type="number" name="sort_order" value="<?= (int)$values['sort_order'] ?>">
        </label>
        <?php if ($isEdit && $slug !== ''): ?>
          <p>スラッグ: <code><?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?></code></p>
        <?php endif; ?>
        <div style="display:flex; gap:8px;">
          <button type="submit">保存</button>
          <a href="/admin/tags/" class="button">一覧に戻る</a>
        </div>
      </div>
    </form>
    <?php
});
