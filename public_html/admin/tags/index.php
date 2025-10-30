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
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <h1 class="mb-0">タグ一覧</h1>
      <a href="/admin/tags/edit.php" class="btn btn-success">新規作成</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        タグを削除しました
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php elseif (isset($_GET['sort_updated'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        並び順を更新しました
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        処理に失敗しました
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <form method="get" class="row g-2 mb-4">
      <div class="col-md-3">
        <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="名前・スラッグ検索" />
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select">
          <option value="">すべてのカテゴリ</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?>"<?= $categoryFilter === (string)$cat ? ' selected' : '' ?>><?= htmlspecialchars((string)$cat, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php $sortLabels = [
        'sort_order' => '並び順',
        'name_asc' => '名前(昇順)',
        'name_desc' => '名前(降順)',
        'created_desc' => '作成日(新しい順)',
        'created_asc' => '作成日(古い順)',
      ]; ?>
      <div class="col-md-3">
        <select name="sort" class="form-select">
          <?php foreach ($sortLabels as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $sortKey === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">検索</button>
      </div>
    </form>

    <form id="sort-form" method="post" action="" style="margin:0;">
      <?php csrf_field(); ?>
      <input type="hidden" name="action" value="sort">
    </form>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width:40px;"></th>
              <th scope="col" style="width:80px;">ID</th>
              <th scope="col">名前</th>
              <th scope="col">カテゴリ</th>
              <th scope="col">スラッグ</th>
              <th scope="col" style="width:120px;">並び順</th>
              <th scope="col" class="text-end" style="width:180px;">操作</th>
            </tr>
          </thead>
          <tbody id="sortable-tbody">
            <?php if (!$rows): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">タグがありません</td>
              </tr>
            <?php else: ?>
              <?php foreach ($rows as $row): $id = (int)$row['id']; ?>
                <tr data-id="<?= $id ?>" data-sort-order="<?= (int)($row['sort_order'] ?? 0) ?>">
                  <td class="drag-handle" style="cursor:grab; text-align:center; user-select:none; background:#f8f9fa;" title="ドラッグして並び替え">
                    <span style="display:inline-block; font-size:18px; color:#6c757d;">⋮⋮</span>
                  </td>
                  <td><?= $id ?></td>
                  <td><?= htmlspecialchars((string)$row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($row['category'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars((string)($row['slug'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><input type="number" name="orders[<?= $id ?>]" value="<?= (int)($row['sort_order'] ?? 0) ?>" style="width:80px;" form="sort-form" class="sort-order-input form-control form-control-sm"></td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="/admin/tags/edit.php?id=<?= $id ?>" class="btn btn-outline-success">編集</a>
                      <form method="post" class="d-inline" onsubmit="return confirm('削除してよろしいですか？');">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-danger">削除</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php if ($rows): ?>
      <div class="mt-3">
        <button type="submit" form="sort-form" class="btn btn-primary">並び順を保存</button>
      </div>
    <?php endif; ?>

    <script>
    (function() {
      const tbody = document.getElementById('sortable-tbody');
      if (!tbody) return;

      let draggedRow = null;
      let placeholder = null;

      function createPlaceholder() {
        if (!placeholder) {
          placeholder = document.createElement('tr');
          placeholder.style.height = '40px';
          placeholder.innerHTML = '<td colspan="7" style="border: 2px dashed #0d6efd; background: #f0f8ff;"></td>';
        }
        return placeholder;
      }

      function getDragAfterElement(container, y) {
        const draggableElements = Array.from(container.querySelectorAll('tr[data-id]')).filter(el => el !== draggedRow);
        
        return draggableElements.reduce((closest, child) => {
          const box = child.getBoundingClientRect();
          const offset = y - box.top - box.height / 2;
          
          if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
          } else {
            return closest;
          }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
      }

      function updateSortOrders() {
        const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
        rows.forEach((row, index) => {
          const input = row.querySelector('.sort-order-input');
          if (input) {
            input.value = index + 1;
          }
        });
      }

      const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
      
      rows.forEach((row) => {
        const dragHandle = row.querySelector('.drag-handle');
        if (!dragHandle) return;

        row.draggable = false;
        // ハンドル操作時のみドラッグ可能にする
        dragHandle.addEventListener('mousedown', function() {
          row.draggable = true;
        });
        dragHandle.addEventListener('mouseup', function() {
          // ドロップで解除
        });
        row.classList.add('sortable-row');

        row.addEventListener('dragstart', function(e) {
          draggedRow = this;
          this.classList.add('dragging');
          this.style.opacity = '0.5';
          e.dataTransfer.effectAllowed = 'move';
          e.dataTransfer.setData('text/html', '');
        });

        row.addEventListener('dragend', function(e) {
          this.style.opacity = '';
          this.classList.remove('dragging');
          if (placeholder && placeholder.parentNode) {
            placeholder.parentNode.removeChild(placeholder);
          }
          updateSortOrders();
          draggedRow = null;
        });

        row.addEventListener('dragover', function(e) {
          if (e.preventDefault) e.preventDefault();
          // 位置決めはtbodyで実施
          return false;
        });

        row.addEventListener('dragenter', function(e) {
          if (this !== draggedRow) {
            e.preventDefault();
          }
        });

        row.addEventListener('drop', function(e) {
          if (e.stopPropagation) e.stopPropagation();
          // dropはtbodyで処理
          return false;
        });
      });

      // tbody全体で広い当たり判定
      tbody.addEventListener('dragover', function(e) {
        if (e.preventDefault) e.preventDefault();
        if (!draggedRow) return false;
        e.dataTransfer.dropEffect = 'move';
        const afterElement = getDragAfterElement(tbody, e.clientY);
        createPlaceholder();
        if (afterElement == null) {
          if (tbody.lastElementChild !== placeholder) tbody.appendChild(placeholder);
        } else {
          if (afterElement !== placeholder) tbody.insertBefore(placeholder, afterElement);
        }
        return false;
      });

      tbody.addEventListener('drop', function(e) {
        if (e.preventDefault) e.preventDefault();
        if (!draggedRow || !placeholder || !placeholder.parentNode) return false;
        tbody.insertBefore(draggedRow, placeholder);
        tbody.removeChild(placeholder);
        updateSortOrders();
        const form = document.getElementById('sort-form');
        if (form) setTimeout(function(){ form.submit(); }, 50);
        // ドラッグ終了: ハンドル時のみドラッグ可能に戻す
        draggedRow.draggable = false;
        draggedRow = null;
        return false;
      });

      // ページ全体での広域ドロップ対応（table外でもドロップ可能）
      document.addEventListener('dragover', function(e) {
        if (!draggedRow) return;
        // テーブル内はtbody側で処理
        if (e.target && typeof e.target.closest === 'function' && e.target.closest('#sortable-tbody')) return;
        e.preventDefault();
        const rect = tbody.getBoundingClientRect();
        const y = e.clientY;
        createPlaceholder();
        const first = tbody.querySelector('tr[data-id]');
        if (!first) return;
        if (y < rect.top) {
          // テーブル上側にドロップ → 先頭
          if (placeholder !== first) tbody.insertBefore(placeholder, first);
        } else if (y > rect.bottom) {
          // テーブル下側にドロップ → 末尾
          if (tbody.lastElementChild !== placeholder) tbody.appendChild(placeholder);
        } else {
          // テーブル領域近辺 → 近傍位置
          const afterElement = getDragAfterElement(tbody, y);
          if (afterElement == null) {
            if (tbody.lastElementChild !== placeholder) tbody.appendChild(placeholder);
          } else if (afterElement !== placeholder) {
            tbody.insertBefore(placeholder, afterElement);
          }
        }
      });

      document.addEventListener('drop', function(e) {
        if (!draggedRow) return;
        // テーブル内はtbodyで処理するためスキップ
        if (e.target && typeof e.target.closest === 'function' && e.target.closest('#sortable-tbody')) return;
        e.preventDefault();
        if (placeholder && placeholder.parentNode === tbody) {
          tbody.insertBefore(draggedRow, placeholder);
          tbody.removeChild(placeholder);
          updateSortOrders();
          const form = document.getElementById('sort-form');
          if (form) setTimeout(function(){ form.submit(); }, 50);
        }
        draggedRow.draggable = false;
        draggedRow = null;
      });

      // ドラッグ中のスタイル
      const style = document.createElement('style');
      style.textContent = `
        .sortable-row.dragging {
          opacity: 0.5;
        }
        .sortable-row:hover .drag-handle {
          background: #e9ecef;
        }
        .sortable-row .drag-handle:active {
          cursor: grabbing;
        }
        .sortable-row .drag-handle {
          cursor: grab;
        }
      `;
      document.head.appendChild(style);
    })();
    </script>
    <?php
});
