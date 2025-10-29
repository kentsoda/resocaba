<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';

renderLayout('応募一覧', function () {
    $pdo = db();
    $perPage = 20;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $from = isset($_GET['from']) ? (string)$_GET['from'] : '';
    $to = isset($_GET['to']) ? (string)$_GET['to'] : '';

    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = '(a.name LIKE :q OR a.email LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($from !== '') {
        $where[] = 'a.created_at >= :from';
        $params[':from'] = $from . ' 00:00:00';
    }
    if ($to !== '') {
        $where[] = 'a.created_at <= :to';
        $params[':to'] = $to . ' 23:59:59';
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $total = 0;
    $rows = [];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications a $whereSql");
            foreach ($params as $k => $v) $stmt->bindValue($k, $v);
            $stmt->execute();
            $total = (int)$stmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $sql = "SELECT a.id, a.name, a.email, a.created_at, a.job_id, j.title AS job_title
                    FROM applications a
                    LEFT JOIN jobs j ON j.id = a.job_id
                    $whereSql
                    ORDER BY a.created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $k => $v) $stmt->bindValue($k, $v);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] applications/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));

    ?>
    <h1>応募一覧</h1>
    <form method="get" class="filters" style="margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap;">
      <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="氏名/メール">
      <input type="date" name="from" value="<?= htmlspecialchars($from, ENT_QUOTES, 'UTF-8') ?>"> 〜
      <input type="date" name="to" value="<?= htmlspecialchars($to, ENT_QUOTES, 'UTF-8') ?>">
      <button type="submit">検索</button>
    </form>

    <div class="table-wrap">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead><tr><th>ID</th><th>氏名</th><th>メール</th><th>求人</th><th>作成</th><th>操作</th></tr></thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="6" style="text-align:center; color:#64748b;">応募がありません</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): $id = (int)$r['id']; $name = (string)$r['name']; $email=(string)$r['email'];
              $nameMask = mb_substr($name, 0, 1, 'UTF-8') . str_repeat('＊', max(0, mb_strlen($name, 'UTF-8') - 1));
              $emailMask = preg_replace('/(^.).*(@.*$)/', '$1***$2', $email);
            ?>
              <tr>
                <td><?= $id ?></td>
                <td><?= htmlspecialchars($nameMask, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($emailMask, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($r['job_title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)$r['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><a href="/admin/applications/show.php?id=<?= $id ?>">詳細</a></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="pagination" style="margin-top:12px; display:flex; gap:6px; flex-wrap:wrap;">
        <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/applications/?' . http_build_query($baseParams); ?>
          <?php if ($p === $page): ?>
            <span style="padding:4px 8px; background:#1e293b; color:#fff; border-radius:4px;"><?= $p ?></span>
          <?php else: ?>
            <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" style="padding:4px 8px; background:#e2e8f0; color:#111; border-radius:4px; text-decoration:none;"><?= $p ?></a>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
    <?php
});
