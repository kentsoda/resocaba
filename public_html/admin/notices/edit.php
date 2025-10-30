<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';
require __DIR__ . '/../inc/editor.php';

renderLayout('お知らせ 編集/新規', function () {
    $pdo = db();
    $id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
    $isEdit = $id > 0;

    $values = [
        'title' => '',
        'status' => 'draft',
        'body_html' => '',
        'published_at' => '',
    ];
    $meta = [
        'created_at' => '',
        'updated_at' => '',
    ];
    $errors = [];

    if ($isEdit && $pdo) {
        try {
            $stmt = $pdo->prepare('SELECT id, title, status, body_html, published_at, created_at, updated_at FROM announcements WHERE id = :id AND deleted_at IS NULL');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $values['title'] = (string)$row['title'];
                $values['status'] = (string)$row['status'];
                $values['body_html'] = (string)($row['body_html'] ?? '');
                if (!empty($row['published_at'])) {
                    $dt = new \DateTime($row['published_at']);
                    $values['published_at'] = $dt->format('Y-m-d\TH:i');
                }
                $meta['created_at'] = (string)($row['created_at'] ?? '');
                $meta['updated_at'] = (string)($row['updated_at'] ?? '');
            } else {
                $isEdit = false;
            }
        } catch (Throwable $e) {
            error_log('[admin] notices/edit load error: ' . $e->getMessage());
        }
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        requireValidCsrfOrAbort();
        $values['title'] = trim(getPostString('title', 255));
        $values['status'] = getPostEnum('status', ['draft', 'published', 'archived'], 'draft');
        $values['body_html'] = sanitizeAllowedHtml(getPostString('body_html'));
        $values['published_at'] = trim(getPostString('published_at', 25));

        $publishedAtDb = null;
        if ($values['published_at'] !== '') {
            $dt = \DateTime::createFromFormat('Y-m-d\TH:i', $values['published_at']);
            if (!$dt) {
                $errors[] = '公開日時の形式が正しくありません';
            } else {
                $publishedAtDb = $dt->format('Y-m-d H:i:00');
            }
        }

        if ($values['title'] === '') {
            $errors[] = 'タイトルは必須です';
        }
        if (!in_array($values['status'], ['draft', 'published', 'archived'], true)) {
            $errors[] = 'ステータスが不正です';
        }
        if ($values['status'] === 'published' && $publishedAtDb === null) {
            $errors[] = '公開ステータスでは公開日時が必要です';
        }

        if (!$errors && $pdo) {
            try {
                if ($isEdit) {
                    $sql = 'UPDATE announcements SET title = :title, status = :status, body_html = :body_html, published_at = :published_at, updated_at = NOW() WHERE id = :id AND deleted_at IS NULL';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = 'INSERT INTO announcements (title, status, body_html, published_at, created_at, updated_at) VALUES (:title, :status, :body_html, :published_at, NOW(), NOW())';
                    $stmt = $pdo->prepare($sql);
                }
                $stmt->bindValue(':title', $values['title']);
                $stmt->bindValue(':status', $values['status']);
                $stmt->bindValue(':body_html', $values['body_html']);
                if ($publishedAtDb === null) {
                    $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
                } else {
                    $stmt->bindValue(':published_at', $publishedAtDb);
                }
                $stmt->execute();
                if (!$isEdit) {
                    $id = (int)$pdo->lastInsertId();
                }
                header('Location: /admin/notices/?notice=saved');
                exit;
            } catch (Throwable $e) {
                error_log('[admin] notices/edit save error: ' . $e->getMessage());
                $errors[] = '保存に失敗しました';
            }
        }
    }

    ?>
    <h1 class="mb-2">お知らせ <?= $isEdit ? '編集' : '新規作成' ?></h1>
    <?php if ($isEdit): ?>
      <p class="text-muted">ID: <?= (int)$id ?></p>
      <p class="text-muted small">作成日時: <?= htmlspecialchars($meta['created_at'], ENT_QUOTES, 'UTF-8') ?> / 更新日時: <?= htmlspecialchars($meta['updated_at'], ENT_QUOTES, 'UTF-8') ?></p>
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
              <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="status">公開状態</label>
              <select class="form-select" id="status" name="status">
                <?php foreach (['draft' => '下書き', 'published' => '公開', 'archived' => 'アーカイブ'] as $key => $label): ?>
                  <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="published_at">公開日時</label>
              <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="<?= htmlspecialchars($values['published_at'], ENT_QUOTES, 'UTF-8') ?>">
              <div class="form-text">公開ステータスの場合は必須です</div>
            </div>
            <div class="col-12">
              <label class="form-label" for="body_html">本文（HTML）</label>
              <textarea class="form-control js-wysiwyg" id="body_html" name="body_html" rows="12"><?= htmlspecialchars($values['body_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="/admin/notices/" class="btn btn-outline-secondary">一覧に戻る</a>
          </div>
        </form>
      </div>
    </div>
    <?php
    enableWysiwyg('.js-wysiwyg');
});
