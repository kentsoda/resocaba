<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';

renderLayout('店舗一覧', function () {
    $pdo = db();
    $perPage = 20;

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $category = isset($_GET['category']) ? (string)$_GET['category'] : '';

    $categoryOptions = ['', 'キャバクラ', 'ラウンジ', 'クラブ', 'スナック'];
    if (!in_array($category, $categoryOptions, true)) {
        $category = '';
    }

    $where = ['s.deleted_at IS NULL'];
    $params = [];

    if ($q !== '') {
        $where[] = '(s.name LIKE :q OR s.country LIKE :q OR s.region_prefecture LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($category !== '') {
        $where[] = 's.category = :category';
        $params[':category'] = $category;
    }

    $whereSql = 'WHERE ' . implode(' AND ', $where);

    $total = 0;
    $rows = [];
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM stores s $whereSql");
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $total = (int)$stmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $sql = "SELECT s.id, s.name, s.category, s.country, s.region_prefecture, s.business_hours_start, s.business_hours_end, s.holiday, s.site_url, s.updated_at
                    FROM stores s
                    $whereSql
                    ORDER BY s.updated_at DESC, s.id DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            error_log('[admin] stores/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));

    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <h1 class="mb-0">店舗一覧</h1>
      <a href="/admin/stores/edit.php" class="btn btn-success">新規登録</a>
    </div>

    <form method="get" class="row g-2 mb-4">
      <div class="col-md-4">
        <label for="filter-q" class="form-label">キーワード</label>
        <input type="text" class="form-control" id="filter-q" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="店舗名・国・地域で検索">
      </div>
      <div class="col-md-3">
        <label for="filter-category" class="form-label">カテゴリ</label>
        <select name="category" class="form-select" id="filter-category">
          <?php foreach ($categoryOptions as $opt): $label = $opt === '' ? 'すべて' : $opt; ?>
            <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $category === $opt ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">&nbsp;</label>
        <button type="submit" class="btn btn-primary w-100">検索</button>
      </div>
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width:60px;">ID</th>
              <th scope="col">店舗名</th>
              <th scope="col" style="width:120px;">カテゴリ</th>
              <th scope="col" style="width:220px;">所在地</th>
              <th scope="col" style="width:160px;">営業時間</th>
              <th scope="col" style="width:160px;">店休日</th>
              <th scope="col" style="width:180px;">更新日時</th>
              <th scope="col" class="text-end" style="width:160px;">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">該当する店舗がありません</td>
              </tr>
            <?php else: ?>
              <?php foreach ($rows as $row):
                $id = (int)$row['id'];
                $locationParts = [];
                if (!empty($row['country'])) { $locationParts[] = (string)$row['country']; }
                if (!empty($row['region_prefecture'])) { $locationParts[] = (string)$row['region_prefecture']; }
                $hours = '';
                $start = $row['business_hours_start'];
                $end = $row['business_hours_end'];
                if ($start !== null && $start !== '') {
                    $start = max(0, min(23, (int)$start));
                    $hours = sprintf('%02d:00', $start);
                }
                if ($end !== null && $end !== '') {
                    $endStr = (string)$end;
                    if (strcasecmp($endStr, 'LAST') === 0) {
                        $endLabel = 'LAST';
                    } elseif (preg_match('/^\d{2}:\d{2}$/', $endStr)) {
                        $endLabel = $endStr;
                    } elseif (preg_match('/^\d{1,2}$/', $endStr)) {
                        $endLabel = sprintf('%02d:00', (int)$endStr);
                    } else {
                        $endLabel = '';
                    }
                    if ($endLabel !== '') {
                        $hours = $hours !== '' ? ($hours . '〜' . $endLabel) : $endLabel;
                    }
                }
                $holiday = '';
                if (!empty($row['holiday'])) {
                    $holiday = implode('／', array_filter(array_map('trim', explode(',', (string)$row['holiday']))));
                }
            ?>
              <tr>
                <td><?= $id ?></td>
                <td>
                  <div>
                    <strong><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php if (!empty($row['site_url'])): ?>
                      <div><a href="<?= htmlspecialchars((string)$row['site_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" class="small text-primary text-decoration-none">サイトを開く</a></div>
                    <?php endif; ?>
                  </div>
                </td>
                <td><?= htmlspecialchars((string)$row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(implode(' / ', $locationParts), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($hours, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($holiday, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($row['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="/admin/stores/edit.php?id=<?= $id ?>" class="btn btn-outline-primary">編集</a>
                    <a href="/admin/stores/images.php?store_id=<?= $id ?>" class="btn btn-outline-secondary">画像</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <nav aria-label="店舗ページネーション" class="mt-4">
        <ul class="pagination flex-wrap">
          <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/stores/?' . http_build_query($baseParams); ?>
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
