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
    <h1>FAQ <?= $isEdit ? '編集' : '新規作成' ?></h1>
    <?php if ($isEdit): ?>
      <p>ID: <?= (int)$id ?></p>
      <p style="color:#6b7280;">作成日時: <?= htmlspecialchars($meta['created_at'], ENT_QUOTES, 'UTF-8') ?> / 更新日時: <?= htmlspecialchars($meta['updated_at'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444; margin-bottom:16px;">
        <?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="post" action="" style="display:grid; gap:16px; max-width:960px;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="save">
      <label>質問<br>
        <input type="text" name="question" value="<?= htmlspecialchars($values['question'], ENT_QUOTES, 'UTF-8') ?>" required>
      </label>
      <label>回答（HTML）<br>
        <textarea class="js-wysiwyg" name="answer_html" rows="12"><?= htmlspecialchars($values['answer_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </label>
      <label>表示順<br>
        <input type="number" name="sort_order" value="<?= (int)$values['sort_order'] ?>">
        <span style="font-size:12px; color:#64748b;">小さいほど上に表示されます</span>
      </label>
      <label>ステータス<br>
        <select name="status">
          <?php foreach (['published' => '公開', 'draft' => '下書き'] as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
        <button type="submit" class="button">保存する</button>
        <a href="/admin/faqs/" class="button" style="background:#e2e8f0; color:#111;">一覧に戻る</a>
      </div>
    </form>

    <?php if ($isEdit): ?>
      <form method="post" action="" onsubmit="return confirm('本当に削除しますか？');" style="margin-top:16px;">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="delete">
        <button type="submit" class="button" style="background:#dc2626; color:#fff;">削除する</button>
      </form>
    <?php endif; ?>
    <?php
    enableWysiwyg('.js-wysiwyg');
});
