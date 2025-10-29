<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';

renderLayout('求人一覧', function () {
    $pdo = db();
    $perPage = 20;

    // Read query params (GET)
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $status = isset($_GET['status']) ? (string)$_GET['status'] : '';
    $jobType = isset($_GET['job_type']) ? (string)$_GET['job_type'] : '';
    $sort = isset($_GET['sort']) ? (string)$_GET['sort'] : 'updated_desc';

    $allowedSort = [
        'updated_desc' => 'j.updated_at DESC',
        'updated_asc' => 'j.updated_at ASC',
        'created_desc' => 'j.created_at DESC',
        'created_asc' => 'j.created_at ASC',
        'title_asc' => 'j.title ASC',
        'title_desc' => 'j.title DESC',
    ];
    $orderBy = $allowedSort[$sort] ?? $allowedSort['updated_desc'];

    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = '(j.title LIKE :q OR s.name LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($status !== '') {
        $where[] = 'j.status = :status';
        $params[':status'] = $status;
    }
    if ($jobType !== '') {
        $where[] = 'j.job_type = :job_type';
        $params[':job_type'] = $jobType;
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $total = 0;
    $rows = [];
    if ($pdo) {
        try {
            // Count
            $sqlCount = "SELECT COUNT(*) FROM jobs j LEFT JOIN stores s ON s.id = j.store_id $whereSql";
            $stmt = $pdo->prepare($sqlCount);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            $total = (int)$stmt->fetchColumn();

            // List
            $offset = ($page - 1) * $perPage;
            $sql = "SELECT j.id, j.title, j.status, j.job_type, j.updated_at, j.created_at, j.store_id, COALESCE(s.name, '') AS store_name
                    FROM jobs j
                    LEFT JOIN stores s ON s.id = j.store_id
                    $whereSql
                    ORDER BY $orderBy
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] jobs/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));

    // Filters + Table (HTML template with minimal PHP)
    ?>
    <h1>求人一覧</h1>
    <form method="get" class="filters" style="margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap;">
      <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="キーワード（タイトル/店舗名）" />
      <?php $statusOptions = ['', 'published', 'draft', 'archived']; ?>
      <select name="status">
        <?php foreach ($statusOptions as $opt): $label = $opt === '' ? 'すべてのステータス' : $opt; ?>
          <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $status === $opt ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="job_type" value="<?= htmlspecialchars($jobType, ENT_QUOTES, 'UTF-8') ?>" placeholder="募集職種" />
      <?php $sortLabels = [
        'updated_desc' => '更新日(新しい順)',
        'updated_asc' => '更新日(古い順)',
        'created_desc' => '作成日(新しい順)',
        'created_asc' => '作成日(古い順)',
        'title_asc' => 'タイトル(A→Z)',
        'title_desc' => 'タイトル(Z→A)',
      ]; ?>
      <select name="sort">
        <?php foreach ($sortLabels as $k => $label): ?>
          <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"<?= $sort === $k ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">検索</button>
      <a href="/admin/jobs/edit.php" class="button" style="margin-left:8px;">新規作成</a>
    </form>

    <div class="table-wrap">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead>
          <tr>
            <th>ID</th><th>タイトル</th><th>ステータス</th><th>店舗</th><th>職種</th><th>更新</th><th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="7" style="text-align:center; color:#64748b;">該当する求人がありません</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): $id = (int)$r['id']; ?>
              <tr>
                <td><?= $id ?></td>
                <td><?= htmlspecialchars((string)$r['title'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)$r['status'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)$r['store_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($r['job_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($r['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <a href="/admin/jobs/edit.php?id=<?= $id ?>">編集</a> |
                  <a href="/admin/jobs/images.php?job_id=<?= $id ?>">画像</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="pagination" style="margin-top:12px; display:flex; gap:6px; flex-wrap:wrap;">
        <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/jobs/?' . http_build_query($baseParams); ?>
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
