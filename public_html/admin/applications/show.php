<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';

renderLayout('応募詳細', function () {
    $pdo = db();
    $id = isset($_GET['id']) ? max(1, (int)$_GET['id']) : 0;
    if ($id <= 0) {
        echo '<p>不正なIDです。</p>';
        return;
    }
    $row = null;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT a.*, j.title AS job_title FROM applications a LEFT JOIN jobs j ON j.id = a.job_id WHERE a.id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            error_log('[admin] applications/show query error: ' . $e->getMessage());
        }
    }
    if (!$row) {
        echo '<p>応募が見つかりません。</p>';
        return;
    }

    ?>
    <h1>応募詳細</h1>
    <p><a href="/admin/applications/">一覧に戻る</a></p>

    <?php $fields = [
        'ID' => $row['id'] ?? '',
        '求人' => $row['job_title'] ?? '',
        '氏名' => $row['name'] ?? '',
        'メール' => $row['email'] ?? '',
        '電話' => $row['tel'] ?? '',
        'メッセージ' => $row['message'] ?? '',
        '作成日時' => $row['created_at'] ?? '',
    ]; ?>

    <div class="card">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <?php foreach ($fields as $label => $value): $safe = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); ?>
          <?php if (in_array($label, ['氏名','メール','電話'], true)):
            $masked = $safe;
            if ($label === '氏名') $masked = htmlspecialchars(mb_substr((string)$value, 0, 1, 'UTF-8') . '＊', ENT_QUOTES, 'UTF-8');
            if ($label === 'メール') $masked = htmlspecialchars(preg_replace('/(^.).*(@.*$)/', '$1***$2', (string)$value), ENT_QUOTES, 'UTF-8');
            if ($label === '電話') $masked = htmlspecialchars(preg_replace('/(\d{3}).*(\d{4})/', '$1****$2', (string)$value), ENT_QUOTES, 'UTF-8');
          ?>
            <tr>
              <th style="width:160px;"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></th>
              <td>
                <span class="pii" data-masked="1" data-raw="<?= $safe ?>"><?= $masked ?></span>
                <button type="button" class="toggle-mask">表示/非表示</button>
              </td>
            </tr>
          <?php else: ?>
            <tr><th style="width:160px;"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></th><td><?= $safe ?></td></tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </table>
    </div>

    <script>
    document.addEventListener('click', function(e){
      if (e.target && e.target.classList.contains('toggle-mask')) {
        var span = e.target.closest('td').querySelector('.pii');
        if (!span) return;
        var masked = span.getAttribute('data-masked') === '1';
        if (masked) { span.textContent = span.getAttribute('data-raw'); span.setAttribute('data-masked', '0'); }
        else {
          var raw = span.getAttribute('data-raw');
          span.textContent = raw.replace(/^(.)(.*)(@.*)$/,'$1***$3');
          span.setAttribute('data-masked', '1');
        }
      }
    });
    </script>
    <?php
});
