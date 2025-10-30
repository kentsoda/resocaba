<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';

$pdo = db();

function redirectWithNotice(string $notice): void {
    header('Location: /admin/ads/index.php?notice=' . rawurlencode($notice));
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    requireValidCsrfOrAbort();
    $action = isset($_POST['action']) ? (string)$_POST['action'] : '';

    if (!$pdo || $action === '') {
        redirectWithNotice('error');
    }

    try {
        if ($action === 'toggle_active') {
            $id = getPostInt('id', 0);
            if ($id <= 0) {
                redirectWithNotice('error');
            }
            $stmt = $pdo->prepare('UPDATE ad_banners SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END, updated_at = NOW() WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirectWithNotice($stmt->rowCount() > 0 ? 'toggled' : 'error');
        } elseif ($action === 'delete') {
            $id = getPostInt('id', 0);
            if ($id <= 0) {
                redirectWithNotice('error');
            }
            $stmt = $pdo->prepare('DELETE FROM ad_banners WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            redirectWithNotice($stmt->rowCount() > 0 ? 'deleted' : 'error');
        } elseif ($action === 'sort') {
            $orders = isset($_POST['orders']) && is_array($_POST['orders']) ? $_POST['orders'] : [];
            if ($orders) {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('UPDATE ad_banners SET sort_order = :sort_order, updated_at = NOW() WHERE id = :id');
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
                redirectWithNotice('reordered');
            } else {
                redirectWithNotice('error');
            }
        } else {
            redirectWithNotice('error');
        }
    } catch (Throwable $e) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[admin] ads/index action error: ' . $e->getMessage());
        redirectWithNotice('error');
    }
}

renderLayout('広告バナー一覧', function () use ($pdo) {
    $ads = [];
    if ($pdo) {
        try {
            $stmt = $pdo->query('SELECT id, image_url, link_url, target_blank, is_active, sort_order, updated_at FROM ad_banners ORDER BY sort_order ASC, id ASC');
            $ads = $stmt ? ($stmt->fetchAll(PDO::FETCH_ASSOC) ?: []) : [];
        } catch (Throwable $e) {
            error_log('[admin] ads/index fetch error: ' . $e->getMessage());
        }
    }

    $noticeKey = isset($_GET['notice']) ? (string)$_GET['notice'] : '';
    $messages = [
        'saved' => '保存しました。',
        'created' => '広告を作成しました。',
        'deleted' => '広告を削除しました。',
        'toggled' => '表示状態を更新しました。',
        'reordered' => '並び順を更新しました。',
        'boundary' => 'これ以上移動できません。',
        'error' => '操作に失敗しました。',
    ];
    $noticeClasses = [
        'saved' => 'alert-success',
        'created' => 'alert-success',
        'deleted' => 'alert-success',
        'toggled' => 'alert-success',
        'reordered' => 'alert-success',
        'boundary' => 'alert-warning',
        'error' => 'alert-danger',
    ];
    $currentMessage = $messages[$noticeKey] ?? '';
    $currentClass = $noticeClasses[$noticeKey] ?? 'alert-info';
    ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
      <h1 class="mb-0">広告バナー一覧</h1>
      <a href="/admin/ads/edit.php" class="btn btn-success">新規作成</a>
    </div>

    <?php if ($currentMessage): ?>
      <div class="alert <?= htmlspecialchars($currentClass, ENT_QUOTES, 'UTF-8') ?>" role="alert">
        <?= htmlspecialchars($currentMessage, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

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
              <th scope="col" style="width:90px;">表示順</th>
              <th scope="col" style="width:60px;">ID</th>
              <th scope="col">画像</th>
              <th scope="col">リンクURL</th>
              <th scope="col" style="width:90px;">target</th>
              <th scope="col" style="width:90px;">表示</th>
              <th scope="col" class="text-end" style="width:220px;">操作</th>
            </tr>
          </thead>
          <tbody id="sortable-tbody">
            <?php if (!$ads): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">広告バナーが登録されていません。</td>
              </tr>
            <?php else: ?>
              <?php foreach ($ads as $index => $ad): $id = (int)$ad['id']; ?>
                <tr data-id="<?= $id ?>" data-sort-order="<?= (int)$ad['sort_order'] ?>">
                  <td class="drag-handle" style="cursor:grab; text-align:center; user-select:none; background:#f8f9fa;" title="ドラッグして並び替え">
                    <span style="display:inline-block; font-size:18px; color:#6c757d;">⋮⋮</span>
                  </td>
                  <td>
                    <input type="number" name="orders[<?= $id ?>]" value="<?= (int)$ad['sort_order'] ?>" style="width:70px;" form="sort-form" class="sort-order-input">
                  </td>
                  <td><?= $id ?></td>
                  <td>
                    <?php if (!empty($ad['image_url'])): ?>
                      <img src="<?= htmlspecialchars($ad['image_url'], ENT_QUOTES, 'UTF-8') ?>" alt="広告画像" class="img-fluid" style="max-width:180px; max-height:100px; object-fit:contain;">
                    <?php endif; ?>
                  </td>
                  <td class="text-break">
                    <?php if (!empty($ad['link_url'])): ?>
                      <a href="<?= htmlspecialchars($ad['link_url'], ENT_QUOTES, 'UTF-8') ?>" target="<?= ((int)$ad['target_blank'] === 1) ? '_blank' : '_self' ?>" rel="noopener"><?= htmlspecialchars($ad['link_url'], ENT_QUOTES, 'UTF-8') ?></a>
                    <?php endif; ?>
                  </td>
                  <td><?= ((int)$ad['target_blank'] === 1) ? '_blank' : '_self' ?></td>
                  <td>
                    <span class="badge bg-<?= ((int)$ad['is_active'] === 1) ? 'success' : 'danger' ?>">
                      <?= ((int)$ad['is_active'] === 1) ? '表示' : '非表示' ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <div class="btn-group btn-group-sm" role="group">
                      <form method="post" class="d-inline">
                        <?php csrf_field(); ?>
                        <input type="hidden" name="action" value="toggle_active">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-outline-primary">
                          <?= ((int)$ad['is_active'] === 1) ? '非表示にする' : '表示にする' ?>
                        </button>
                      </form>
                      <a href="/admin/ads/edit.php?id=<?= $id ?>" class="btn btn-outline-success">編集</a>
                      <form method="post" class="d-inline" onsubmit="return confirm('広告を削除しますか？');">
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

    <?php if ($ads): ?>
      <div style="margin-top:12px;">
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
          placeholder.innerHTML = '<td colspan="8" style="border: 2px dashed #0d6efd; background: #f0f8ff;"></td>';
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
          // 位置決めはtbody側で実施
          return false;
        });

        row.addEventListener('dragenter', function(e) {
          if (this !== draggedRow) {
            e.preventDefault();
          }
        });

        row.addEventListener('drop', function(e) {
          if (e.stopPropagation) e.stopPropagation();
          // dropはtbody側で処理
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
          background: #e9ecef !important;
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
