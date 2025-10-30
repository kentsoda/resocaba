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
    <h1 class="mb-4">応募一覧</h1>
    <form method="get" class="row gy-2 gx-3 align-items-end mb-4">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <label for="filter-q" class="form-label">氏名/メール</label>
        <input type="text" class="form-control" id="filter-q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="検索キーワード">
      </div>
      <div class="col-sm-6 col-md-3 col-lg-2">
        <label for="filter-from" class="form-label">応募日（開始）</label>
        <input type="date" class="form-control" id="filter-from" name="from" value="<?= htmlspecialchars($from, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="col-sm-6 col-md-3 col-lg-2">
        <label for="filter-to" class="form-label">応募日（終了）</label>
        <input type="date" class="form-control" id="filter-to" name="to" value="<?= htmlspecialchars($to, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">検索</button>
        <a href="/admin/applications/" class="btn btn-outline-secondary ms-2">リセット</a>
      </div>
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">氏名</th>
              <th scope="col">メール</th>
              <th scope="col">求人</th>
              <th scope="col">作成</th>
              <th scope="col" class="text-end">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">応募がありません</td>
              </tr>
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
                  <td class="text-end">
                    <a href="/admin/applications/show.php?id=<?= $id ?>" class="btn btn-outline-primary btn-sm">詳細</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav aria-label="応募ページネーション" class="mt-4">
        <ul class="pagination flex-wrap">
          <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/applications/?' . http_build_query($baseParams); ?>
            <li class="page-item<?= $p === $page ? ' active' : '' ?>">
              <?php if ($p === $page): ?>
                <span class="page-link"><?= $p ?></span>
              <?php else: ?>
                <a class="page-link" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><?= $p ?></a>
              <?php endif; ?>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
    <?php
});
