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
    <h1>店舗一覧</h1>
    <form method="get" class="filters" style="margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;">
      <label style="display:flex; flex-direction:column; gap:4px;">
        <span>キーワード</span>
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="店舗名・国・地域で検索">
      </label>
      <label style="display:flex; flex-direction:column; gap:4px;">
        <span>カテゴリ</span>
        <select name="category">
          <?php foreach ($categoryOptions as $opt): $label = $opt === '' ? 'すべて' : $opt; ?>
            <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $category === $opt ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <div style="display:flex; gap:8px;">
        <button type="submit">検索</button>
        <a href="/admin/stores/edit.php" class="button" style="align-self:flex-end;">新規登録</a>
      </div>
    </form>

    <div class="table-wrap">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead>
          <tr>
            <th style="width:60px;">ID</th>
            <th>店舗名</th>
            <th style="width:120px;">カテゴリ</th>
            <th style="width:220px;">所在地</th>
            <th style="width:160px;">営業時間</th>
            <th style="width:160px;">店休日</th>
            <th style="width:180px;">更新日時</th>
            <th style="width:140px;">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="8" style="text-align:center; color:#64748b;">該当する店舗がありません</td></tr>
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
                  <div style="display:flex; flex-direction:column;">
                    <strong><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php if (!empty($row['site_url'])): ?>
                      <a href="<?= htmlspecialchars((string)$row['site_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" style="font-size:12px; color:#2563eb;">サイトを開く</a>
                    <?php endif; ?>
                  </div>
                </td>
                <td><?= htmlspecialchars((string)$row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(implode(' / ', $locationParts), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($hours, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($holiday, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($row['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <a href="/admin/stores/edit.php?id=<?= $id ?>">編集</a> |
                  <a href="/admin/stores/images.php?store_id=<?= $id ?>">画像</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="pagination" style="margin-top:12px; display:flex; gap:6px; flex-wrap:wrap;">
        <?php $baseParams = $_GET; for ($p = 1; $p <= $totalPages; $p++): $baseParams['page'] = $p; $href = '/admin/stores/?' . http_build_query($baseParams); ?>
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
