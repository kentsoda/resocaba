<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';
require __DIR__ . '/../inc/editor.php';

$pdo = db();
$id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
$isEdit = $id > 0;

$errors = [];
$values = [
    'title' => '',
    'slug' => '',
    'category' => '',
    'og_image_url' => '',
    'status' => 'draft',
    'published_at' => '',
    'body_html' => '',
];
$meta = [
    'created_at' => null,
    'updated_at' => null,
];

$existingCategories = [];
if ($pdo) {
    try {
        $stmtCat = $pdo->query("SELECT DISTINCT category FROM articles WHERE category IS NOT NULL AND category <> '' AND deleted_at IS NULL ORDER BY category ASC");
        if ($stmtCat) {
            $existingCategories = $stmtCat->fetchAll(PDO::FETCH_COLUMN) ?: [];
        }
    } catch (Throwable $e) {
        error_log('[admin] blog/edit categories error: ' . $e->getMessage());
    }
}

if ($isEdit && $pdo) {
    try {
        $stmt = $pdo->prepare('SELECT id, title, slug, category, og_image_url, status, published_at, body_html, created_at, updated_at FROM articles WHERE id = :id AND deleted_at IS NULL');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $values['title'] = (string)($row['title'] ?? '');
            $values['slug'] = (string)($row['slug'] ?? '');
            $values['category'] = (string)($row['category'] ?? '');
            $values['og_image_url'] = (string)($row['og_image_url'] ?? '');
            $values['status'] = (string)($row['status'] ?? 'draft');
            $values['body_html'] = (string)($row['body_html'] ?? '');
            if (!empty($row['published_at'])) {
                try {
                    $dt = new DateTimeImmutable((string)$row['published_at']);
                    $values['published_at'] = $dt->format('Y-m-d\TH:i');
                } catch (Throwable $e) {
                    $values['published_at'] = '';
                }
            }
            $meta['created_at'] = $row['created_at'] ?? null;
            $meta['updated_at'] = $row['updated_at'] ?? null;
        } else {
            $errors[] = '指定された記事が見つかりません。';
        }
    } catch (Throwable $e) {
        error_log('[admin] blog/edit load error: ' . $e->getMessage());
        $errors[] = '記事情報の取得に失敗しました。';
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();

    $values['title'] = trim(getPostString('title', 255));
    $values['slug'] = trim(getPostString('slug', 191));
    $values['category'] = trim(getPostString('category', 100));
    $values['og_image_url'] = trim(getPostString('og_image_url', 512));
    $values['status'] = getPostEnum('status', ['draft', 'published', 'archived'], 'draft');
    $bodyRaw = getPostString('body_html', 200000);
    $values['body_html'] = sanitizeAllowedHtml($bodyRaw);

    $publishedInput = isset($_POST['published_at']) ? trim((string)$_POST['published_at']) : '';
    $values['published_at'] = $publishedInput;
    $publishedForDb = null;
    if ($publishedInput !== '') {
        $dt = DateTime::createFromFormat('Y-m-d\TH:i', $publishedInput);
        if (!$dt) {
            $dt = DateTime::createFromFormat('Y-m-d\TH:i:s', $publishedInput);
        }
        if ($dt instanceof DateTime) {
            $publishedForDb = $dt->format('Y-m-d H:i:s');
            $values['published_at'] = $dt->format('Y-m-d\TH:i');
        } else {
            $errors[] = '公開日時の形式が正しくありません。';
        }
    }

    if ($values['title'] === '') {
        $errors[] = 'タイトルは必須です。';
    }
    if (mb_strlen($values['slug'], 'UTF-8') > 191) {
        $errors[] = 'スラッグは191文字以内で入力してください。';
    }
    if ($values['og_image_url'] !== '' && !filter_var($values['og_image_url'], FILTER_VALIDATE_URL)) {
        $errors[] = 'OGP画像URLの形式が不正です。';
    }

    if (!$pdo) {
        $errors[] = 'データベース接続に失敗しました。';
    }

    if (!$errors && $pdo) {
        try {
            if ($isEdit) {
                $sql = 'UPDATE articles SET title = :title, slug = :slug, category = :category, og_image_url = :og_image_url, status = :status, published_at = :published_at, body_html = :body_html, updated_at = NOW() WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            } else {
                $sql = 'INSERT INTO articles (title, slug, category, og_image_url, status, published_at, body_html, created_at, updated_at) VALUES (:title, :slug, :category, :og_image_url, :status, :published_at, :body_html, NOW(), NOW())';
                $stmt = $pdo->prepare($sql);
            }
            $stmt->bindValue(':title', $values['title']);
            $stmt->bindValue(':slug', $values['slug'] !== '' ? $values['slug'] : null, $values['slug'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':category', $values['category'] !== '' ? $values['category'] : null, $values['category'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':og_image_url', $values['og_image_url'] !== '' ? $values['og_image_url'] : null, $values['og_image_url'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':status', $values['status']);
            if ($publishedForDb !== null) {
                $stmt->bindValue(':published_at', $publishedForDb);
            } else {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            }
            $stmt->bindValue(':body_html', $values['body_html']);
            $stmt->execute();

            if (!$isEdit) {
                $id = (int)$pdo->lastInsertId();
            }

            header('Location: /admin/blog/index.php?saved=1');
            exit;
        } catch (Throwable $e) {
            error_log('[admin] blog/edit save error: ' . $e->getMessage());
            $errors[] = '保存に失敗しました。';
        }
    }
}

renderLayout($isEdit ? 'ブログ記事編集' : 'ブログ記事新規作成', function () use ($isEdit, $id, $errors, $values, $meta, $existingCategories) {
    ?>
    <h1><?= $isEdit ? 'ブログ記事編集' : 'ブログ記事新規作成' ?></h1>
    <?php if ($isEdit): ?>
      <p style="color:#64748b;">ID: <?= (int)$id ?></p>
    <?php endif; ?>
    <?php if ($meta['updated_at']): ?>
      <p style="color:#94a3b8; font-size:12px;">最終更新: <?= htmlspecialchars((string)$meta['updated_at'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444; color:#ef4444;"><?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="" style="display:grid; gap:16px; max-width:960px;">
      <?php csrf_field(); ?>
      <div>
        <label class="form-label">タイトル <span style="color:#ef4444;">*</span></label>
        <input type="text" name="title" value="<?= htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8') ?>" required maxlength="255" />
      </div>
      <div>
        <label class="form-label">スラッグ</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') ?>" maxlength="191" placeholder="例: summer-campaign" />
      </div>
      <div>
        <label class="form-label">カテゴリ</label>
        <input type="text" name="category" list="article-categories" value="<?= htmlspecialchars($values['category'], ENT_QUOTES, 'UTF-8') ?>" maxlength="100" placeholder="カテゴリを入力" />
        <?php if ($existingCategories): ?>
          <datalist id="article-categories">
            <?php foreach ($existingCategories as $cat): ?>
              <option value="<?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?>"></option>
            <?php endforeach; ?>
          </datalist>
        <?php endif; ?>
      </div>
      <div>
        <label class="form-label">OGP画像URL</label>
        <input type="url" name="og_image_url" value="<?= htmlspecialchars($values['og_image_url'], ENT_QUOTES, 'UTF-8') ?>" maxlength="512" placeholder="https://example.com/ogp.jpg" />
        <div id="og-image-preview" style="margin-top:8px; padding:8px; border:1px solid #e2e8f0; border-radius:6px; min-height:80px; display:flex; align-items:center; justify-content:center; background:#f8fafc;"></div>
      </div>
      <div style="display:flex; gap:16px; flex-wrap:wrap;">
        <div style="flex:1 1 200px;">
          <label class="form-label">ステータス</label>
          <select name="status">
            <?php foreach (['draft' => '下書き', 'published' => '公開', 'archived' => 'アーカイブ'] as $key => $label): ?>
              <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="flex:1 1 200px;">
          <label class="form-label">公開日時</label>
          <input type="datetime-local" name="published_at" value="<?= htmlspecialchars($values['published_at'], ENT_QUOTES, 'UTF-8') ?>" />
          <small style="display:block; color:#64748b;">公開日時を設定すると公開予約できます。</small>
        </div>
      </div>
      <div>
        <label class="form-label">本文 HTML</label>
        <textarea name="body_html" class="js-wysiwyg" rows="16"><?= htmlspecialchars($values['body_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div style="display:flex; gap:12px;">
        <button type="submit" class="button" style="background:#2563eb; color:#fff;">保存する</button>
        <a href="/admin/blog/index.php" class="button" style="background:#e2e8f0; color:#111827;">一覧へ戻る</a>
      </div>
    </form>

    <script>
      (function () {
        var input = document.querySelector('input[name="og_image_url"]');
        var preview = document.getElementById('og-image-preview');
        if (!input || !preview) return;
        function updatePreview() {
          var url = input.value.trim();
          preview.innerHTML = '';
          if (url) {
            var img = document.createElement('img');
            img.src = url;
            img.alt = 'OGPプレビュー';
            img.style.maxWidth = '100%';
            img.style.maxHeight = '160px';
            img.style.objectFit = 'cover';
            preview.appendChild(img);
          } else {
            var span = document.createElement('span');
            span.textContent = 'URLを入力するとプレビューが表示されます。';
            span.style.color = '#64748b';
            preview.appendChild(span);
          }
        }
        input.addEventListener('input', updatePreview);
        updatePreview();
      })();
    </script>
    <?php
    enableWysiwyg('.js-wysiwyg');
});


