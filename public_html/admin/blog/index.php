<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';

renderLayout('ブログ記事一覧', function () {
    $pdo = db();
    $perPage = 20;

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $status = isset($_GET['status']) ? (string)$_GET['status'] : '';
    $category = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
    $state = isset($_GET['state']) ? (string)$_GET['state'] : '';
    $sortKey = isset($_GET['sort']) ? (string)$_GET['sort'] : 'published_desc';

    $allowedSort = [
        'published_desc' => 'a.published_at DESC',
        'published_asc' => 'a.published_at ASC',
        'updated_desc' => 'a.updated_at DESC',
        'updated_asc' => 'a.updated_at ASC',
        'created_desc' => 'a.created_at DESC',
        'title_asc' => 'a.title ASC',
        'title_desc' => 'a.title DESC',
    ];
    $orderBy = $allowedSort[$sortKey] ?? $allowedSort['published_desc'];

    $where = ['a.deleted_at IS NULL'];
    $params = [];
    if ($q !== '') {
        $where[] = '(a.title LIKE :q OR a.body_html LIKE :q OR a.slug LIKE :q)';
        $params[':q'] = '%' . $q . '%';
    }
    if ($status !== '') {
        $where[] = 'a.status = :status';
        $params[':status'] = $status;
    }
    if ($category !== '') {
        $where[] = 'a.category = :category';
        $params[':category'] = $category;
    }
    if ($state === 'published_now') {
        $where[] = "(a.status = 'published' AND a.published_at IS NOT NULL AND a.published_at <= NOW())";
    } elseif ($state === 'scheduled') {
        $where[] = "(a.status = 'published' AND a.published_at IS NOT NULL AND a.published_at > NOW())";
    } elseif ($state === 'no_date') {
        $where[] = '(a.published_at IS NULL)';
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $total = 0;
    $rows = [];
    $categories = [];

    if ($pdo) {
        try {
            $stmtCat = $pdo->query("SELECT DISTINCT category FROM articles WHERE category IS NOT NULL AND category <> '' AND deleted_at IS NULL ORDER BY category ASC");
            if ($stmtCat) {
                $categories = $stmtCat->fetchAll(PDO::FETCH_COLUMN) ?: [];
            }

            $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM articles a $whereSql");
            foreach ($params as $key => $value) {
                $stmtCount->bindValue($key, $value);
            }
            $stmtCount->execute();
            $total = (int)$stmtCount->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $sql = "SELECT a.id, a.title, a.slug, a.category, a.status, a.published_at, a.updated_at, a.created_at, a.og_image_url
                    FROM articles a
                    $whereSql
                    ORDER BY $orderBy
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
            error_log('[admin] blog/index query error: ' . $e->getMessage());
        }
    }

    $totalPages = max(1, (int)ceil($total / $perPage));
    ?>
    <h1>ブログ記事一覧</h1>
    <div class="card" style="margin-bottom:16px;">
      <form method="get" class="filters" style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-end;">
        <div style="display:flex; flex-direction:column;">
          <label for="blog-filter-q" style="font-size:12px; color:#475569;">キーワード</label>
          <input id="blog-filter-q" type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="タイトル・本文・スラッグ" />
        </div>
        <div style="display:flex; flex-direction:column;">
          <label for="blog-filter-category" style="font-size:12px; color:#475569;">カテゴリ</label>
          <input id="blog-filter-category" type="text" name="category" list="blog-categories" value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>" placeholder="カテゴリ名" />
          <?php if ($categories): ?>
            <datalist id="blog-categories">
              <?php foreach ($categories as $catOption): ?>
                <option value="<?= htmlspecialchars((string)$catOption, ENT_QUOTES, 'UTF-8') ?>"></option>
              <?php endforeach; ?>
            </datalist>
          <?php endif; ?>
        </div>
        <div style="display:flex; flex-direction:column;">
          <label for="blog-filter-status" style="font-size:12px; color:#475569;">ステータス</label>
          <?php $statusOptions = ['', 'published', 'draft', 'archived']; ?>
          <select id="blog-filter-status" name="status">
            <?php foreach ($statusOptions as $opt): $label = $opt === '' ? 'すべて' : $opt; ?>
              <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $status === $opt ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="display:flex; flex-direction:column;">
          <label for="blog-filter-state" style="font-size:12px; color:#475569;">公開状態</label>
          <select id="blog-filter-state" name="state">
            <?php
            $stateOptions = [
                '' => 'すべて',
                'published_now' => '公開中（現在時刻まで）',
                'scheduled' => '公開予約（未来日時）',
                'no_date' => '公開日時未設定',
            ];
            foreach ($stateOptions as $val => $label): ?>
              <option value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8') ?>"<?= $state === $val ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="display:flex; flex-direction:column;">
          <label for="blog-filter-sort" style="font-size:12px; color:#475569;">並び替え</label>
          <?php $sortLabels = [
              'published_desc' => '公開日時(新しい順)',
              'published_asc' => '公開日時(古い順)',
              'updated_desc' => '更新日時(新しい順)',
              'updated_asc' => '更新日時(古い順)',
              'created_desc' => '作成日時(新しい順)',
              'title_asc' => 'タイトル(A→Z)',
              'title_desc' => 'タイトル(Z→A)',
          ]; ?>
          <select id="blog-filter-sort" name="sort">
            <?php foreach ($sortLabels as $key => $label): ?>
              <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $sortKey === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
          <button type="submit" class="button">検索</button>
          <a href="/admin/blog/index.php" class="button" style="background:#e2e8f0; color:#1f2937;">リセット</a>
        </div>
        <div style="margin-left:auto;">
          <a href="/admin/blog/edit.php" class="button" style="background:#2563eb; color:#fff;">新規作成</a>
        </div>
      </form>
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
      <div>該当件数: <?= number_format($total) ?> 件</div>
      <?php if ($totalPages > 1): ?>
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
          <?php $base = $_GET; for ($p = 1; $p <= $totalPages; $p++): $base['page'] = $p; $href = '/admin/blog/index.php?' . http_build_query($base); ?>
            <?php if ($p === $page): ?>
              <span style="padding:4px 8px; background:#1e293b; color:#fff; border-radius:4px; font-weight:600;"><?= $p ?></span>
            <?php else: ?>
              <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" style="padding:4px 8px; background:#e2e8f0; color:#111827; border-radius:4px; text-decoration:none;"><?= $p ?></a>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-wrap">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
        <thead>
          <tr>
            <th style="width:60px;">ID</th>
            <th>タイトル</th>
            <th style="width:120px;">カテゴリ</th>
            <th style="width:120px;">ステータス</th>
            <th style="width:160px;">公開日時</th>
            <th style="width:160px;">更新日時</th>
            <th style="width:80px;">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr>
              <td colspan="7" style="text-align:center; color:#64748b;">条件に一致する記事がありません</td>
            </tr>
          <?php else: ?>
            <?php foreach ($rows as $row): $id = (int)$row['id']; ?>
              <tr>
                <td><?= $id ?></td>
                <td>
                  <div style="font-weight:600; color:#111827;">
                    <?= htmlspecialchars((string)$row['title'], ENT_QUOTES, 'UTF-8') ?>
                  </div>
                  <div style="font-size:12px; color:#64748b;">スラッグ: <?= htmlspecialchars((string)($row['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                </td>
                <td><?= $row['category'] !== null && $row['category'] !== '' ? htmlspecialchars((string)$row['category'], ENT_QUOTES, 'UTF-8') : '<span style="color:#94a3b8;">(未設定)</span>' ?></td>
                <td>
                  <?php
                  $statusLabel = (string)($row['status'] ?? '');
                  $statusColor = '#cbd5f5';
                  if ($statusLabel === 'published') {
                      $statusColor = '#22c55e';
                  } elseif ($statusLabel === 'draft') {
                      $statusColor = '#f97316';
                  } elseif ($statusLabel === 'archived') {
                      $statusColor = '#64748b';
                  }
                  ?>
                  <span style="display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(148, 163, 184, 0.15); color:<?= htmlspecialchars($statusColor, ENT_QUOTES, 'UTF-8') ?>; font-size:12px; font-weight:600;">
                    <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($row['published_at'] ? (string)$row['published_at'] : '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['updated_at'] ? (string)$row['updated_at'] : '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                  <a href="/admin/blog/edit.php?id=<?= $id ?>">編集</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php
});


