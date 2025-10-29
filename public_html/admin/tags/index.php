<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';

$pdo = db();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = isset($_POST['action']) ? (string)$_POST['action'] : '';

    if ($pdo) {
        try {
            if ($action === 'delete') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                if ($id > 0) {
                    $stmt = $pdo->prepare('DELETE FROM tags WHERE id = :id');
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    header('Location: /admin/tags/?deleted=1');
                    exit;
                }
            } elseif ($action === 'sort') {
                $orders = isset($_POST['orders']) && is_array($_POST['orders']) ? $_POST['orders'] : [];
                if ($orders) {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare('UPDATE tags SET sort_order = :sort_order WHERE id = :id');
                    foreach ($orders as $id => $value) {
                        $id = filter_var($id, FILTER_VALIDATE_INT);
                        $sortOrder = filter_var($value, FILTER_VALIDATE_INT);
                        if ($id === false || $sortOrder === false) {
                            continue;
                        }
                        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
                        $stmt->bindValue(':sort_order', (int)$sortOrder, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    $pdo->commit();
                    header('Location: /admin/tags/?sort_updated=1');
                    exit;
                }
            }
        } catch (Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('[admin] tags/index action error: ' . $e->getMessage());
        }
    }

    header('Location: /admin/tags/?error=1');
    exit;
}

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$categoryFilter = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
$sortKey = isset($_GET['sort']) ? (string)$_GET['sort'] : 'sort_order';

$sortMap = [
    'sort_order' => 'sort_order ASC, id ASC',
    'name_asc' => 'name ASC',
    'name_desc' => 'name DESC',
    'created_desc' => 'created_at DESC',
    'created_asc' => 'created_at ASC',
];
$orderBy = $sortMap[$sortKey] ?? $sortMap['sort_order'];

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(name LIKE :q OR slug LIKE :q)';
    $params[':q'] = '%' . $q . '%';
}
if ($categoryFilter !== '') {
    $where[] = 'category = :category';
    $params[':category'] = $categoryFilter;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$rows = [];
$categories = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT id, name, slug, category, sort_order, type, created_at FROM tags $whereSql ORDER BY $orderBy");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $catStmt = $pdo->query("SELECT DISTINCT category FROM tags WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC");
        $categories = $catStmt ? ($catStmt->fetchAll(PDO::FETCH_COLUMN) ?: []) : [];
    } catch (Throwable $e) {
        error_log('[admin] tags/index query error: ' . $e->getMessage());
        $rows = [];
    }
}

renderLayout('タグ一覧', function () use ($rows, $q, $categoryFilter, $sortKey, $categories) {
    ?>
    <h1>タグ一覧</h1>
    <?php if (isset($_GET['deleted'])): ?>
      <div class="card" style="border-color:#f97316;">タグを削除しました</div>
    <?php elseif (isset($_GET['sort_updated'])): ?>
      <div class="card" style="border-color:#22c55e;">並び順を更新しました</div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="card" style="border-color:#ef4444;">処理に失敗しました</div>
    <?php endif; ?>

    <form method="get" class="filters" style="margin-bottom:16px; display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
      <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="名前・スラッグ検索" />
      <select name="category">
        <option value="">すべてのカテゴリ</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?>"<?= $categoryFilter === (string)$cat ? ' selected' : '' ?>><?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <?php $sortLabels = [
        'sort_order' => '並び順',
        'name_asc' => '名前(昇順)',
        'name_desc' => '名前(降順)',
        'created_desc' => '作成日(新しい順)',
        'created_asc' => '作成日(古い順)',
      ]; ?>
      <select name="sort">
        <?php foreach ($sortLabels as $key => $label): ?>
          <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $sortKey === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">検索</button>
      <a href="/admin/tags/edit.php" class="button" style="margin-left:auto;">新規作成</a>
    </form>

    <form id="sort-form" method="post" action="" style="margin:0;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="sort">
    </form>

    <div class="table-wrap" style="margin-bottom:16px;">
      <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">
          <thead>
            <tr>
              <th style="width:80px;">ID</th>
              <th>名前</th>
              <th>カテゴリ</th>
              <th>スラッグ</th>
              <th style="width:120px;">並び順</th>
              <th style="width:160px;">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr><td colspan="6" style="text-align:center; color:#64748b;">タグがありません</td></tr>
            <?php else: ?>
              <?php foreach ($rows as $row): $id = (int)$row['id']; ?>
                <tr>
                  <td><?= $id ?></td>
                  <td><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($row['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($row['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><input type="number" name="orders[<?= $id ?>]" value="<?= (int)($row['sort_order'] ?? 0) ?>" style="width:80px;" form="sort-form"></td>
                  <td>
                    <a href="/admin/tags/edit.php?id=<?= $id ?>">編集</a>
                    <form method="post" action="" style="display:inline; margin-left:8px;" onsubmit="return confirm('削除してよろしいですか？');">
                      <?php csrf_field(); ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $id ?>">
                      <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer;">削除</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
    </div>
    <?php if ($rows): ?>
      <div style="margin-top:12px;">
        <button type="submit" form="sort-form">並び順を保存</button>
      </div>
    <?php endif; ?>
    <?php
});
