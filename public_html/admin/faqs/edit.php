<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';
require __DIR__ . '/../inc/editor.php';

renderLayout('FAQ 編集/新規', function () {
    $pdo = db();
    $id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
    $isEdit = $id > 0;

    $values = [
        'question' => '',
        'answer_html' => '',
        'sort_order' => 0,
        'status' => 'published',
    ];
    $meta = [
        'created_at' => '',
        'updated_at' => '',
    ];
    $errors = [];

    if ($isEdit && $pdo) {
        try {
            $stmt = $pdo->prepare('SELECT id, question, answer_html, sort_order, status, created_at, updated_at FROM faqs WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $values['question'] = (string)($row['question'] ?? '');
                $values['answer_html'] = (string)($row['answer_html'] ?? '');
                $values['sort_order'] = (int)($row['sort_order'] ?? 0);
                $values['status'] = (string)($row['status'] ?? 'published');
                $meta['created_at'] = (string)($row['created_at'] ?? '');
                $meta['updated_at'] = (string)($row['updated_at'] ?? '');
            } else {
                $isEdit = false;
            }
        } catch (Throwable $e) {
            error_log('[admin] faqs/edit load error: ' . $e->getMessage());
        }
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        requireValidCsrfOrAbort();
        $action = getPostEnum('action', ['save', 'delete'], 'save');

        if ($action === 'delete') {
            if ($isEdit && $pdo) {
                try {
                    $stmt = $pdo->prepare('DELETE FROM faqs WHERE id = :id');
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    header('Location: /admin/faqs/?notice=deleted');
                    exit;
                } catch (Throwable $e) {
                    error_log('[admin] faqs/edit delete error: ' . $e->getMessage());
                    $errors[] = '削除に失敗しました';
                }
            }
        } else {
            $values['question'] = trim(getPostString('question', 255));
            $values['answer_html'] = sanitizeAllowedHtml(getPostString('answer_html'));
            $values['sort_order'] = getPostInt('sort_order', 0);
            $values['status'] = getPostEnum('status', ['draft', 'published'], 'draft');

            if ($values['question'] === '') {
                $errors[] = '質問は必須です';
            }
            if (!in_array($values['status'], ['draft', 'published'], true)) {
                $errors[] = 'ステータスが不正です';
            }

            if (!$errors && $pdo) {
                try {
                    if ($isEdit) {
                        $sql = 'UPDATE faqs SET question = :question, answer_html = :answer_html, sort_order = :sort_order, status = :status, updated_at = NOW() WHERE id = :id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    } else {
                        $sql = 'INSERT INTO faqs (question, answer_html, sort_order, status, created_at, updated_at) VALUES (:question, :answer_html, :sort_order, :status, NOW(), NOW())';
                        $stmt = $pdo->prepare($sql);
                    }
                    $stmt->bindValue(':question', $values['question']);
                    $stmt->bindValue(':answer_html', $values['answer_html']);
                    $stmt->bindValue(':sort_order', $values['sort_order'], PDO::PARAM_INT);
                    $stmt->bindValue(':status', $values['status']);
                    $stmt->execute();
                    if (!$isEdit) {
                        $id = (int)$pdo->lastInsertId();
                        $isEdit = $id > 0;
                    }
                    header('Location: /admin/faqs/?notice=saved');
                    exit;
                } catch (Throwable $e) {
                    error_log('[admin] faqs/edit save error: ' . $e->getMessage());
                    $errors[] = '保存に失敗しました';
                }
            }
        }
    }
    ?>
    <h1 class="mb-2">FAQ <?= $isEdit ? '編集' : '新規作成' ?></h1>
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
          <input type="hidden" name="action" value="save">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label" for="question">質問<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="question" name="question" value="<?= htmlspecialchars($values['question'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="status">ステータス</label>
              <select class="form-select" id="status" name="status">
                <?php foreach (['published' => '公開', 'draft' => '下書き'] as $key => $label): ?>
                  <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label" for="sort_order">表示順</label>
              <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= (int)$values['sort_order'] ?>">
              <div class="form-text">小さいほど上に表示されます</div>
            </div>
            <div class="col-12">
              <label class="form-label" for="answer_html">回答（HTML）</label>
              <textarea class="form-control js-wysiwyg" id="answer_html" name="answer_html" rows="12"><?= htmlspecialchars($values['answer_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="/admin/faqs/" class="btn btn-outline-secondary">一覧に戻る</a>
            <?php if ($isEdit): ?>
              <form method="post" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                <?php csrf_field(); ?>
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-outline-danger">削除</button>
              </form>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
    <?php
    enableWysiwyg('.js-wysiwyg');
});
