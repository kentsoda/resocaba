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
    <h1 class="mb-4">求人一覧</h1>
    <form method="get" class="row gy-2 gx-3 align-items-end mb-4">
      <div class="col-sm-6 col-lg-3">
        <label for="filter-q" class="form-label">キーワード（タイトル/店舗名）</label>
        <input type="text" class="form-control" id="filter-q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="キーワードを入力">
      </div>
      <?php $statusOptions = ['', 'published', 'draft', 'archived']; ?>
      <div class="col-sm-6 col-lg-2">
        <label for="filter-status" class="form-label">ステータス</label>
        <select class="form-select" id="filter-status" name="status">
          <?php foreach ($statusOptions as $opt): $label = $opt === '' ? 'すべて' : $opt; ?>
            <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $status === $opt ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-sm-6 col-lg-2">
        <label for="filter-job-type" class="form-label">募集職種</label>
        <input type="text" class="form-control" id="filter-job-type" name="job_type" value="<?= htmlspecialchars($jobType, ENT_QUOTES, 'UTF-8') ?>" placeholder="職種を入力">
      </div>
      <?php $sortLabels = [
        'updated_desc' => '更新日(新しい順)',
        'updated_asc' => '更新日(古い順)',
        'created_desc' => '作成日(新しい順)',
        'created_asc' => '作成日(古い順)',
        'title_asc' => 'タイトル(A→Z)',
        'title_desc' => 'タイトル(Z→A)',
      ]; ?>
      <div class="col-sm-6 col-lg-2">
        <label for="filter-sort" class="form-label">ソート</label>
        <select class="form-select" id="filter-sort" name="sort">
          <?php foreach ($sortLabels as $k => $label): ?>
            <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"<?= $sort === $k ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">検索</button>
        <a href="/admin/jobs/" class="btn btn-outline-secondary ms-2">リセット</a>
      </div>
      <div class="col-auto ms-auto">
        <a href="/admin/jobs/edit.php" class="btn btn-success">新規作成</a>
      </div>
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">タイトル</th>
              <th scope="col">ステータス</th>
              <th scope="col">店舗</th>
              <th scope="col">職種</th>
              <th scope="col">更新</th>
              <th scope="col">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">該当する求人がありません</td>
              </tr>
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
                    <div class="btn-group btn-group-sm" role="group">
                      <a class="btn btn-outline-primary" href="/admin/jobs/edit.php?id=<?= $id ?>">編集</a>
                      <a class="btn btn-outline-secondary" href="/admin/jobs/images.php?job_id=<?= $id ?>">画像</a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav aria-label="求人ページネーション" class="mt-4">
        <ul class="pagination flex-wrap">
          <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/jobs/?' . http_build_query($baseParams); ?>
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
