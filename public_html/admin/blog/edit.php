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
    <h1 class="mb-2"><?= $isEdit ? 'ブログ記事編集' : 'ブログ記事新規作成' ?></h1>
    <?php if ($isEdit): ?>
      <p class="text-muted">ID: <?= (int)$id ?></p>
    <?php endif; ?>
    <?php if ($meta['updated_at']): ?>
      <p class="text-muted small">最終更新: <?= htmlspecialchars((string)$meta['updated_at'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="alert alert-danger" role="alert">
        <?= nl2br(htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8')) ?>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="" class="needs-validation" novalidate>
          <?php csrf_field(); ?>
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label" for="title">タイトル<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8') ?>" required maxlength="255" />
            </div>
            <div class="col-md-4">
              <label class="form-label" for="status">ステータス</label>
              <select class="form-select" id="status" name="status">
                <?php foreach (['draft' => '下書き', 'published' => '公開', 'archived' => 'アーカイブ'] as $key => $label): ?>
                  <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="slug">スラッグ</label>
              <input type="text" class="form-control" id="slug" name="slug" value="<?= htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') ?>" maxlength="191" placeholder="例: summer-campaign" />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="category">カテゴリ</label>
              <input type="text" class="form-control" id="category" name="category" list="article-categories" value="<?= htmlspecialchars($values['category'], ENT_QUOTES, 'UTF-8') ?>" maxlength="100" placeholder="カテゴリを入力" />
              <?php if ($existingCategories): ?>
                <datalist id="article-categories">
                  <?php foreach ($existingCategories as $cat): ?>
                    <option value="<?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?>"></option>
                  <?php endforeach; ?>
                </datalist>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="published_at">公開日時</label>
              <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="<?= htmlspecialchars($values['published_at'], ENT_QUOTES, 'UTF-8') ?>" />
              <div class="form-text">公開日時を設定すると公開予約できます。</div>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="og_image_url">OGP画像URL</label>
              <input type="url" class="form-control" id="og_image_url" name="og_image_url" value="<?= htmlspecialchars($values['og_image_url'], ENT_QUOTES, 'UTF-8') ?>" maxlength="512" placeholder="https://example.com/ogp.jpg" />
            </div>
            <div class="col-12">
              <div id="og-image-preview" class="border rounded p-2 d-flex align-items-center justify-content-center" style="min-height:80px; background:#f8fafc;"></div>
            </div>
            <div class="col-12">
              <label class="form-label" for="body_html">本文 HTML</label>
              <textarea class="form-control js-wysiwyg" id="body_html" name="body_html" rows="16"><?= htmlspecialchars($values['body_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="/admin/blog/index.php" class="btn btn-outline-secondary">一覧へ戻る</a>
          </div>
        </form>
      </div>
    </div>

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


