<?php
declare(strict_types=1);

require_once __DIR__ . '/../inc/layout.php';
require_once __DIR__ . '/../inc/csrf.php';
require_once __DIR__ . '/../inc/db.php';

const ASSET_UPLOAD_DIR = __DIR__ . '/../../assets/uploads';
const ASSET_UPLOAD_URL = '/assets/uploads';
const ASSET_ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
const ASSET_ALLOWED_MIME = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
];
const ASSET_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
const ASSET_MAX_DIMENSION = 4000;

ensureSessionStarted();

$errors = [];
$notices = [];

if (!is_dir(ASSET_UPLOAD_DIR)) {
    if (!mkdir(ASSET_UPLOAD_DIR, 0775, true) && !is_dir(ASSET_UPLOAD_DIR)) {
        $errors[] = 'アップロード用ディレクトリを作成できませんでした。権限を確認してください。';
    }
}

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isValidCsrfFromPost()) {
        $errors[] = 'フォームの有効期限が切れています。再度お試しください。';
    } else {
        $action = isset($_POST['action']) ? (string)$_POST['action'] : '';
        if ($action === 'upload') {
            $result = handleAssetUpload($pdo);
            $errors = array_merge($errors, $result['errors']);
            if ($result['message'] !== null) {
                $notices[] = $result['message'];
            }
        } elseif ($action === 'delete') {
            $result = handleAssetDeletion($pdo);
            $errors = array_merge($errors, $result['errors']);
            if ($result['message'] !== null) {
                $notices[] = $result['message'];
            }
        } else {
            $errors[] = '不明な操作がリクエストされました。';
        }
    }
}

$dbAssets = fetchAssetsFromDatabase($pdo);

$filterType = isset($_GET['type']) ? strtolower((string)$_GET['type']) : 'all';
if ($filterType !== 'all' && !in_array($filterType, ['jpg', 'png', 'webp'], true)) {
    $filterType = 'all';
}
$searchKeyword = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$sortKey = isset($_GET['sort']) ? (string)$_GET['sort'] : 'date_desc';

$assetEntries = collectAssetEntries($dbAssets, $filterType, $searchKeyword);
$assetEntries = sortAssetEntries($assetEntries, $sortKey);

renderLayout('アセット管理', function () use ($errors, $notices, $assetEntries, $filterType, $searchKeyword, $sortKey, $pdo) {
    $hasDb = $pdo instanceof PDO;
    ?>
    <h1>アセット管理</h1>
    <p>画像ファイルのアップロードと管理を行います。対応形式: JPG / PNG / WEBP（最大 5MB、最長辺 4000px 以内）。</p>

    <?php if (!empty($notices)): ?>
        <div class="alert success">
            <ul>
                <?php foreach ($notices as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="panel">
        <h2>画像をアップロード</h2>
        <form method="post" enctype="multipart/form-data" class="upload-form">
            <?php csrf_field(); ?>
            <input type="hidden" name="action" value="upload">
            <div class="form-row">
                <label for="asset-file">ファイル</label>
                <input type="file" name="asset_file" id="asset-file" accept=".jpg,.jpeg,.png,.webp" required>
            </div>
            <div class="form-actions">
                <button type="submit">アップロード</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>アップロード済みファイル一覧</h2>
            <p class="panel-meta">合計 <?= count($assetEntries) ?> 件<?= $hasDb ? '（DB接続済）' : '（DB未接続）' ?></p>
        </div>

        <form method="get" class="filter-form">
            <div class="filter-group">
                <label for="filter-type">種別</label>
                <select name="type" id="filter-type">
                    <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>すべて</option>
                    <option value="jpg" <?= $filterType === 'jpg' ? 'selected' : '' ?>>JPG/JPEG</option>
                    <option value="png" <?= $filterType === 'png' ? 'selected' : '' ?>>PNG</option>
                    <option value="webp" <?= $filterType === 'webp' ? 'selected' : '' ?>>WEBP</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-q">キーワード</label>
                <input type="search" id="filter-q" name="q" value="<?= htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8') ?>" placeholder="ファイル名やパスを検索">
            </div>
            <div class="filter-group">
                <label for="filter-sort">並び順</label>
                <select name="sort" id="filter-sort">
                    <option value="date_desc" <?= $sortKey === 'date_desc' ? 'selected' : '' ?>>新しい順</option>
                    <option value="date_asc" <?= $sortKey === 'date_asc' ? 'selected' : '' ?>>古い順</option>
                    <option value="name_asc" <?= $sortKey === 'name_asc' ? 'selected' : '' ?>>ファイル名 (A→Z)</option>
                    <option value="name_desc" <?= $sortKey === 'name_desc' ? 'selected' : '' ?>>ファイル名 (Z→A)</option>
                    <option value="size_desc" <?= $sortKey === 'size_desc' ? 'selected' : '' ?>>サイズ (大→小)</option>
                    <option value="size_asc" <?= $sortKey === 'size_asc' ? 'selected' : '' ?>>サイズ (小→大)</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit">適用</button>
            </div>
        </form>

        <div class="table-wrap">
            <table class="asset-table">
                <thead>
                    <tr>
                        <th>プレビュー</th>
                        <th>ファイル</th>
                        <th>サイズ</th>
                        <th>寸法</th>
                        <th>更新日時</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($assetEntries)): ?>
                    <tr>
                        <td colspan="6" class="empty">該当するファイルがありません。</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($assetEntries as $entry): ?>
                        <tr class="<?= $entry['has_file'] ? '' : 'missing' ?>">
                            <td class="preview-cell">
                                <?php if ($entry['has_file']): ?>
                                    <img src="<?= htmlspecialchars($entry['web_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
                                <?php else: ?>
                                    <span class="no-preview">ファイルなし</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="file-name"><?= htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="file-path"><?= htmlspecialchars($entry['relative_path'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php if ($entry['db_record'] !== null): ?>
                                    <div class="db-info">DB ID: <?= (int)$entry['db_record']['id'] ?> / 登録: <?= htmlspecialchars($entry['db_record']['created_at'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php else: ?>
                                    <div class="db-info warning">DB未登録</div>
                                <?php endif; ?>
                            </td>
                            <td><?= $entry['size_label'] ?></td>
                            <td><?= $entry['dimension_label'] ?></td>
                            <td><?= htmlspecialchars($entry['updated_label'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form method="post" onsubmit="return confirm('ファイルを削除します。よろしいですか？');">
                                    <?php csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="target" value="<?= htmlspecialchars($entry['relative_path'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?php if ($entry['db_record'] !== null): ?>
                                        <input type="hidden" name="asset_id" value="<?= (int)$entry['db_record']['id'] ?>">
                                    <?php endif; ?>
                                    <button type="submit" class="danger">削除</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <style>
        .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
        .panel-header { display: flex; flex-wrap: wrap; align-items: baseline; gap: 12px; justify-content: space-between; }
        .panel-header h2 { margin: 0; }
        .panel-meta { color: #64748b; font-size: 14px; }
        .upload-form .form-row { margin-bottom: 12px; display: flex; flex-direction: column; gap: 8px; }
        .upload-form label { font-weight: 600; }
        .form-actions button, .filter-actions button, .table-wrap button { background: #2563eb; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; cursor: pointer; }
        .table-wrap button.danger { background: #dc2626; }
        .alert { border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; }
        .alert.success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .alert.error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .filter-form { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; margin-bottom: 16px; }
        .filter-group { display: flex; flex-direction: column; gap: 6px; }
        .filter-group label { font-size: 14px; font-weight: 600; color: #334155; }
        .filter-group select, .filter-group input { padding: 6px 10px; border: 1px solid #cbd5f5; border-radius: 6px; min-width: 160px; }
        .asset-table { width: 100%; border-collapse: collapse; }
        .asset-table th, .asset-table td { border-bottom: 1px solid #e2e8f0; padding: 12px; vertical-align: top; text-align: left; }
        .asset-table th { background: #f8fafc; font-size: 14px; color: #475569; }
        .asset-table tbody tr.missing { background: #fff7ed; }
        .preview-cell { width: 120px; }
        .preview-cell img { width: 96px; height: 96px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
        .no-preview { display: inline-block; padding: 8px 12px; border-radius: 6px; background: #e2e8f0; color: #475569; font-size: 12px; }
        .file-name { font-weight: 600; color: #0f172a; }
        .file-path { font-size: 13px; color: #64748b; }
        .db-info { font-size: 12px; margin-top: 4px; color: #0369a1; }
        .db-info.warning { color: #d97706; }
        .asset-table td { font-size: 14px; }
        .asset-table td.empty { text-align: center; color: #94a3b8; padding: 32px 12px; }
        .table-wrap { overflow-x: auto; }
        @media (max-width: 768px) {
            .filter-form { flex-direction: column; align-items: stretch; }
            .filter-group select, .filter-group input { width: 100%; }
        }
    </style>
    <?php
});

/**
 * 画像ファイルのアップロード処理
 *
 * @param PDO|null $pdo
 * @return array{errors: array<int, string>, message: ?string}
 */
function handleAssetUpload($pdo): array
{
    $errors = [];
    $message = null;

    if (!isset($_FILES['asset_file'])) {
        return ['errors' => ['ファイルが選択されていません。'], 'message' => null];
    }

    $file = $_FILES['asset_file'];

    if (!is_array($file) || !isset($file['error'])) {
        return ['errors' => ['アップロード情報が不正です。'], 'message' => null];
    }

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['errors' => ['ファイルが選択されていません。'], 'message' => null];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = translateUploadError((int)$file['error']);
        return ['errors' => $errors, 'message' => null];
    }

    $size = isset($file['size']) ? (int)$file['size'] : 0;
    if ($size <= 0) {
        $errors[] = 'ファイルサイズを取得できませんでした。';
    } elseif ($size > ASSET_MAX_FILE_SIZE) {
        $errors[] = 'ファイルサイズは 5MB 以内にしてください。';
    }

    $originalName = isset($file['name']) ? (string)$file['name'] : 'upload';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension === 'jpeg') {
        $extension = 'jpg';
    }

    if (!in_array($extension, ASSET_ALLOWED_EXTENSIONS, true)) {
        $errors[] = '対応していないファイル形式です。JPG/PNG/WEBP のみアップロード可能です。';
    }

    $tmpPath = $file['tmp_name'] ?? '';
    if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
        $errors[] = 'アップロードされた一時ファイルを確認できませんでした。';
    }

    if (!empty($errors)) {
        return ['errors' => $errors, 'message' => null];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpPath);
    if ($mime === false) {
        $errors[] = 'MIMEタイプを判定できませんでした。';
    } else {
        $expectedMime = ASSET_ALLOWED_MIME[$extension] ?? null;
        if ($expectedMime !== null && $mime !== $expectedMime) {
            $errors[] = 'ファイルの種類が内容と一致しません。';
        }
    }

    $imageInfo = @getimagesize($tmpPath);
    if ($imageInfo === false) {
        $errors[] = '画像情報を読み取れませんでした。破損している可能性があります。';
    } else {
        $width = isset($imageInfo[0]) ? (int)$imageInfo[0] : 0;
        $height = isset($imageInfo[1]) ? (int)$imageInfo[1] : 0;
        if ($width > ASSET_MAX_DIMENSION || $height > ASSET_MAX_DIMENSION) {
            $errors[] = '画像の一辺は最大 4000px 以内にしてください。';
        }
    }

    if (!empty($errors)) {
        return ['errors' => $errors, 'message' => null];
    }

    $subDir = date('Y/m');
    $targetDir = rtrim(ASSET_UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subDir);
    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
        return ['errors' => ['アップロード先ディレクトリを作成できませんでした。'], 'message' => null];
    }

    try {
        $random = bin2hex(random_bytes(6));
    } catch (Throwable $e) {
        $random = bin2hex(openssl_random_pseudo_bytes(6));
    }

    $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
    if ($safeName === '' || $safeName === null) {
        $safeName = 'image';
    }
    $finalName = date('YmdHis') . '-' . $safeName . '-' . $random . '.' . $extension;
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $finalName;

    if (!move_uploaded_file($tmpPath, $targetPath)) {
        return ['errors' => ['ファイルの保存に失敗しました。'], 'message' => null];
    }

    $relativePath = $subDir . '/' . $finalName;
    $webPath = rtrim(ASSET_UPLOAD_URL, '/') . '/' . str_replace('\\', '/', $relativePath);

    $imageInfo = $imageInfo ?: @getimagesize($targetPath);
    $width = isset($imageInfo[0]) ? (int)$imageInfo[0] : null;
    $height = isset($imageInfo[1]) ? (int)$imageInfo[1] : null;

    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare('INSERT INTO assets (file_name, file_path, mime, size, width, height, created_by) VALUES (:file_name, :file_path, :mime, :size, :width, :height, :created_by)');
            $createdBy = isset($_SESSION['admin_user_id']) ? (int)$_SESSION['admin_user_id'] : null;
            $stmt->execute([
                ':file_name' => $finalName,
                ':file_path' => $webPath,
                ':mime' => $mime,
                ':size' => $size,
                ':width' => $width,
                ':height' => $height,
                ':created_by' => $createdBy > 0 ? $createdBy : null,
            ]);
        } catch (Throwable $e) {
            error_log('[admin/assets_manager] failed to insert asset record: ' . $e->getMessage());
        }
    }

    $message = 'ファイル「' . $finalName . '」をアップロードしました。';
    return ['errors' => [], 'message' => $message];
}

/**
 * ファイル削除処理
 *
 * @param PDO|null $pdo
 * @return array{errors: array<int, string>, message: ?string}
 */
function handleAssetDeletion($pdo): array
{
    $errors = [];
    $message = null;

    $relativePath = isset($_POST['target']) ? trim((string)$_POST['target']) : '';
    if ($relativePath === '') {
        return ['errors' => ['削除対象のファイルが指定されていません。'], 'message' => null];
    }

    $relativePath = str_replace('\\', '/', $relativePath);
    $relativePath = ltrim($relativePath, '/');

    if (stringContains($relativePath, '..')) {
        return ['errors' => ['不正なパスが指定されました。'], 'message' => null];
    }

    $fullPath = rtrim(ASSET_UPLOAD_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    $fileRemoved = false;

    if (is_file($fullPath)) {
        if (!@unlink($fullPath)) {
            $errors[] = 'ファイルの削除に失敗しました。権限を確認してください。';
        } else {
            $fileRemoved = true;
            removeEmptyParentDirectories($fullPath, ASSET_UPLOAD_DIR);
        }
    }

    $assetId = isset($_POST['asset_id']) ? (int)$_POST['asset_id'] : 0;
    if ($pdo instanceof PDO) {
        try {
            if ($assetId > 0) {
                $stmt = $pdo->prepare('DELETE FROM assets WHERE id = :id');
                $stmt->execute([':id' => $assetId]);
                if ($stmt->rowCount() > 0 && !$fileRemoved) {
                    $message = 'DBレコード (ID: ' . $assetId . ') を削除しました。';
                }
            } else {
                $webPath = rtrim(ASSET_UPLOAD_URL, '/') . '/' . $relativePath;
                $stmt = $pdo->prepare('DELETE FROM assets WHERE file_path = :file_path');
                $stmt->execute([':file_path' => $webPath]);
            }
        } catch (Throwable $e) {
            $errors[] = 'DBレコードの削除中にエラーが発生しました。';
            error_log('[admin/assets_manager] failed to delete asset record: ' . $e->getMessage());
        }
    }

    if ($fileRemoved) {
        $message = 'ファイルを削除しました。';
    } elseif ($message === null && empty($errors)) {
        $message = '対象のファイルは存在しませんでしたが、関連レコードを整理しました。';
    }

    return ['errors' => $errors, 'message' => $message];
}

/**
 * DBに登録されたアセット情報を取得
 *
 * @param PDO|null $pdo
 * @return array{list: array<int, array<string, mixed>>, by_relative: array<string, array<string, mixed>>}
 */
function fetchAssetsFromDatabase($pdo): array
{
    $result = ['list' => [], 'by_relative' => []];
    if (!($pdo instanceof PDO)) {
        return $result;
    }

    try {
        $stmt = $pdo->query('SELECT id, file_name, file_path, mime, size, width, height, created_at FROM assets ORDER BY created_at DESC');
        if ($stmt !== false) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $result['list'][] = $row;
                $relative = normalizeRelativePathFromUrl($row['file_path'] ?? '');
                if ($relative !== null) {
                    $result['by_relative'][$relative] = $row;
                }
            }
        }
    } catch (Throwable $e) {
        error_log('[admin/assets_manager] failed to fetch assets: ' . $e->getMessage());
    }

    return $result;
}

/**
 * ファイルとDBレコードを統合した一覧を生成
 *
 * @param array{list: array<int, array<string, mixed>>, by_relative: array<string, array<string, mixed>>} $dbAssets
 * @param string $filterType
 * @param string $searchKeyword
 * @return array<int, array<string, mixed>>
 */
function collectAssetEntries(array $dbAssets, string $filterType, string $searchKeyword): array
{
    $entries = [];
    $matchedRelative = [];

    if (is_dir(ASSET_UPLOAD_DIR)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(ASSET_UPLOAD_DIR, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }
            $extension = strtolower($fileInfo->getExtension());
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }
            if (!in_array($extension, ASSET_ALLOWED_EXTENSIONS, true)) {
                continue;
            }

            $relativePath = trim(str_replace('\\', '/', substr($fileInfo->getPathname(), strlen(ASSET_UPLOAD_DIR))), '/');
            $dbRecord = $dbAssets['by_relative'][$relativePath] ?? null;
            $entry = buildAssetEntry(
                $fileInfo->getFilename(),
                $relativePath,
                true,
                $fileInfo->getSize(),
                $extension,
                $fileInfo->getPathname(),
                $dbRecord
            );

            if (!passesFilters($entry, $filterType, $searchKeyword)) {
                continue;
            }

            $entries[] = $entry;
            $matchedRelative[$relativePath] = true;
        }
    }

    foreach ($dbAssets['list'] as $row) {
        $relativePath = normalizeRelativePathFromUrl($row['file_path'] ?? '');
        if ($relativePath === null || isset($matchedRelative[$relativePath])) {
            continue;
        }
        $extension = strtolower(pathinfo($row['file_name'] ?? '', PATHINFO_EXTENSION));
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }
        $entry = buildAssetEntry(
            $row['file_name'] ?? basename((string)$row['file_path']),
            $relativePath,
            false,
            isset($row['size']) ? (int)$row['size'] : 0,
            $extension,
            null,
            $row
        );

        if (!passesFilters($entry, $filterType, $searchKeyword)) {
            continue;
        }

        $entries[] = $entry;
    }

    return $entries;
}

/**
 * 一覧用の行データを組み立て
 *
 * @param string $fileName
 * @param string $relativePath
 * @param bool $hasFile
 * @param int $size
 * @param string $extension
 * @param string|null $fullPath
 * @param array<string, mixed>|null $dbRecord
 * @return array<string, mixed>
 */
function buildAssetEntry(string $fileName, string $relativePath, bool $hasFile, int $size, string $extension, ?string $fullPath, ?array $dbRecord): array
{
    $webPath = rtrim(ASSET_UPLOAD_URL, '/') . '/' . str_replace('\\', '/', $relativePath);
    $sizeBytes = $hasFile ? $size : (int)($dbRecord['size'] ?? 0);
    $dimensions = $hasFile && $fullPath !== null ? @getimagesize($fullPath) : null;
    $width = $dimensions !== false && $dimensions !== null ? (int)($dimensions[0] ?? 0) : (int)($dbRecord['width'] ?? 0);
    $height = $dimensions !== false && $dimensions !== null ? (int)($dimensions[1] ?? 0) : (int)($dbRecord['height'] ?? 0);

    $updatedTs = null;
    if ($hasFile && $fullPath !== null) {
        $fileTime = @filemtime($fullPath);
        if ($fileTime !== false) {
            $updatedTs = (int)$fileTime;
        }
    }
    if ($updatedTs === null || $updatedTs <= 0) {
        $dbTimestamp = isset($dbRecord['created_at']) ? strtotime((string)$dbRecord['created_at']) : false;
        if ($dbTimestamp !== false) {
            $updatedTs = (int)$dbTimestamp;
        } else {
            $updatedTs = null;
        }
    }

    $dbCreated = isset($dbRecord['created_at']) ? (string)$dbRecord['created_at'] : '';

    return [
        'name' => $fileName,
        'relative_path' => $relativePath,
        'web_path' => $webPath,
        'has_file' => $hasFile,
        'extension' => $extension,
        'size' => $sizeBytes,
        'size_label' => formatBytes($sizeBytes),
        'width' => $width,
        'height' => $height,
        'dimension_label' => $width > 0 && $height > 0 ? $width . '×' . $height : '—',
        'updated_ts' => $updatedTs,
        'updated_label' => $updatedTs ? date('Y-m-d H:i', $updatedTs) : ($dbCreated ?: '—'),
        'db_record' => $dbRecord,
    ];
}

/**
 * フィルタ条件をチェック
 *
 * @param array<string, mixed> $entry
 * @param string $filterType
 * @param string $searchKeyword
 * @return bool
 */
function passesFilters(array $entry, string $filterType, string $searchKeyword): bool
{
    if ($filterType !== 'all' && $entry['extension'] !== $filterType) {
        return false;
    }
    if ($searchKeyword !== '') {
        if (function_exists('mb_strtolower')) {
            $keyword = mb_strtolower($searchKeyword, 'UTF-8');
            $haystack = mb_strtolower($entry['name'] . ' ' . $entry['relative_path'], 'UTF-8');
            if (mb_strpos($haystack, $keyword, 0, 'UTF-8') === false) {
                return false;
            }
        } else {
            $keyword = strtolower($searchKeyword);
            $haystack = strtolower($entry['name'] . ' ' . $entry['relative_path']);
            if (strpos($haystack, $keyword) === false) {
                return false;
            }
        }
    }
    return true;
}

/**
 * 一覧をソート
 *
 * @param array<int, array<string, mixed>> $entries
 * @param string $sortKey
 * @return array<int, array<string, mixed>>
 */
function sortAssetEntries(array $entries, string $sortKey): array
{
    $sortKey = in_array($sortKey, ['date_desc', 'date_asc', 'name_asc', 'name_desc', 'size_desc', 'size_asc'], true) ? $sortKey : 'date_desc';

    usort($entries, function (array $a, array $b) use ($sortKey): int {
        switch ($sortKey) {
            case 'name_asc':
                return strcasecmp($a['name'], $b['name']);
            case 'name_desc':
                return strcasecmp($b['name'], $a['name']);
            case 'size_asc':
                return ($a['size'] ?? 0) <=> ($b['size'] ?? 0);
            case 'size_desc':
                return ($b['size'] ?? 0) <=> ($a['size'] ?? 0);
            case 'date_asc':
                return ($a['updated_ts'] ?? 0) <=> ($b['updated_ts'] ?? 0);
            case 'date_desc':
            default:
                return ($b['updated_ts'] ?? 0) <=> ($a['updated_ts'] ?? 0);
        }
    });

    return $entries;
}

/**
 * バイト数を人間が読みやすい形式に整形
 */
function formatBytes(int $bytes): string
{
    if ($bytes <= 0) {
        return '—';
    }
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $value = (float)$bytes;
    while ($value >= 1024 && $i < count($units) - 1) {
        $value /= 1024;
        $i++;
    }
    return sprintf($value >= 10 || $i === 0 ? '%.0f %s' : '%.1f %s', $value, $units[$i]);
}

/**
 * アップロードエラーコードを日本語メッセージに変換
 */
function translateUploadError(int $errorCode): string
{
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'ファイルサイズが制限を超えています。';
        case UPLOAD_ERR_PARTIAL:
            return 'ファイルが途中までしかアップロードされませんでした。';
        case UPLOAD_ERR_NO_TMP_DIR:
            return '一時フォルダが見つかりません。';
        case UPLOAD_ERR_CANT_WRITE:
            return 'ディスクへの書き込みに失敗しました。';
        case UPLOAD_ERR_EXTENSION:
            return 'PHP拡張によってアップロードが中断されました。';
        default:
            return 'ファイルのアップロード中にエラーが発生しました。';
    }
}

/**
 * URLまたはパスから相対パスを抽出
 */
function normalizeRelativePathFromUrl($path): ?string
{
    if (!is_string($path) || $path === '') {
        return null;
    }
    $normalized = str_replace(['\\', '\r', '\n'], '/', trim($path));
    if (stringContains($normalized, '://')) {
        $parts = parse_url($normalized, PHP_URL_PATH);
        $normalized = is_string($parts) ? $parts : $normalized;
    }
    $normalized = ltrim($normalized, '/');
    $base = ltrim(str_replace(['\\'], '/', ASSET_UPLOAD_URL), '/');
    if ($base !== '' && stringStartsWith($normalized, $base)) {
        $normalized = ltrim(substr($normalized, strlen($base)), '/');
    }
    if ($normalized === '' || stringContains($normalized, '..')) {
        return null;
    }
    return $normalized;
}

/**
 * 削除後に空になった年/月ディレクトリを片付ける
 */
function removeEmptyParentDirectories(string $filePath, string $baseDir): void
{
    $dir = dirname($filePath);
    $baseDir = rtrim(realpath($baseDir) ?: $baseDir, DIRECTORY_SEPARATOR);
    while ($dir !== $baseDir && stringStartsWith($dir, $baseDir)) {
        if (@count(scandir($dir)) > 2) {
            break;
        }
        @rmdir($dir);
        $dir = dirname($dir);
    }
}

/**
 * 文字列に指定した値が含まれるかを判定
 */
function stringContains(string $haystack, string $needle): bool
{
    if ($needle === '') {
        return true;
    }
    return strpos($haystack, $needle) !== false;
}

/**
 * 文字列が指定した値で始まるかを判定
 */
function stringStartsWith(string $haystack, string $needle): bool
{
    if ($needle === '') {
        return true;
    }
    return strncmp($haystack, $needle, strlen($needle)) === 0;
}
