<?php
require_once 'database.php';
require_once 'crud.php';

/**
 * 広告バナーを取得（表示中のみ／並び順昇順／件数制限）
 *
 * @param int $limit 取得件数（デフォルト4件）
 * @return array|false バナー配列、失敗時はfalse
 */
function get_ad_banners(int $limit = 4) {
    $sql = "SELECT id, image_url, link_url, target_blank FROM ad_banners WHERE is_active = 1 ORDER BY sort_order ASC, id ASC LIMIT ?";
    return executeQuery($sql, [(int)$limit]);
}

function get_job_list() {
    $jobs = selectRecords('jobs');
    return $jobs;
}

/**
 * 求人リストと画像を効率的に一括取得する関数
 * 
 * @return array|false 求人データ（各求人にimages配列が追加される）、失敗時はfalse
 */
function get_job_list_with_images() {
    // 1. 求人データを取得
    $jobs = get_job_list();
    if ($jobs === false || empty($jobs)) {
        return $jobs;
    }
    
    // 2. 求人IDの配列を作成
    $job_ids = array_column($jobs, 'id');
    if (empty($job_ids)) {
        return $jobs;
    }
    
    // 3. 画像を一括取得（WHERE job_id IN (...)
    $placeholders = str_repeat('?,', count($job_ids) - 1) . '?';
    $sql = "SELECT job_id, image_url, sort_order FROM job_images WHERE job_id IN ({$placeholders}) ORDER BY job_id, sort_order";
    
    $images = executeQuery($sql, $job_ids);
    if ($images === false) {
        // 画像取得に失敗した場合は、画像なしで求人データを返す
        foreach ($jobs as &$job) {
            $job['images'] = [];
        }
        return $jobs;
    }
    
    // 4. 画像をjob_idでグルーピング
    $images_by_job = [];
    foreach ($images as $image) {
        $images_by_job[$image['job_id']][] = $image;
    }
    
    // 5. 各求人データに画像情報を追加
    foreach ($jobs as &$job) {
        $job['images'] = isset($images_by_job[$job['id']]) ? $images_by_job[$job['id']] : [];
    }
    
    return $jobs;
}

/**
 * お知らせリストを取得する関数
 * 
 * @param int $limit 取得件数（デフォルト5件）
 * @return array|false お知らせデータ、失敗時はfalse
 */
function get_announcement_list($limit = 5) {
    $sql = "SELECT id, title, slug, published_at, created_at
            FROM announcements
            WHERE status = 'published'
            AND deleted_at IS NULL
            ORDER BY created_at DESC
            LIMIT ?";
    return executeQuery($sql, [$limit]);
}

/**
 * 単一のお知らせをIDで取得する
 *
 * @param int $id お知らせID
 * @return array|null 見つかった場合はお知らせ配列、存在しない場合はnull
 */
function get_announcement_by_id($id) {
    $id = (int)$id;
    if ($id <= 0) {
        return null;
    }

    $sql = <<<SQL
SELECT id, title, slug, body_html, status, published_at, created_at, updated_at
FROM announcements
WHERE id = ?
  AND status = 'published'
  AND deleted_at IS NULL
LIMIT 1
SQL;
    $row = executeQuerySingle($sql, [$id]);

    if ($row === false || empty($row)) {
        return null;
    }

    return $row;
}

/**
 * 単一のお知らせをスラッグで取得する
 *
 * @param string $slug スラッグ
 * @return array|null 見つかった場合はお知らせ配列、存在しない場合はnull
 */
function get_announcement_by_slug($slug) {
    $slug = trim((string)$slug);
    if ($slug === '') {
        return null;
    }

    $sql = <<<SQL
SELECT id, title, slug, body_html, status, published_at, created_at, updated_at
FROM announcements
WHERE slug = ?
  AND status = 'published'
  AND deleted_at IS NULL
LIMIT 1
SQL;
    $row = executeQuerySingle($sql, [$slug]);

    if ($row === false || empty($row)) {
        return null;
    }

    return $row;
}

/**
 * 記事リストを取得する関数
 * 
 * @param int $limit 取得件数（デフォルト4件）
 * @return array|false 記事データ、失敗時はfalse
 */
function get_article_list($limit = 4) {
    $sql = "SELECT id, title, slug, category, og_image_url, published_at, updated_at 
            FROM articles 
            WHERE status = 'published' 
            AND deleted_at IS NULL 
            ORDER BY published_at DESC 
            LIMIT ?";
    return executeQuery($sql, [$limit]);
}

/**
 * 店舗の営業時間を表示用文字列にまとめるヘルパー
 *
 * @param int|null $startHour
 * @param string|null $endHour
 * @return string
 */
function build_store_hours_label($startHour, $endHour): string {
    $hasStart = $startHour !== null && $startHour !== '' && $startHour !== false;
    $hasEnd = $endHour !== null && $endHour !== '' && $endHour !== false;
    if (!$hasStart && !$hasEnd) {
        return '';
    }
    $labelStart = '';
    if ($hasStart) {
        $startInt = (int)$startHour;
        if ($startInt < 0) { $startInt = 0; }
        if ($startInt > 23) { $startInt = 23; }
        $labelStart = sprintf('%02d:00', $startInt);
    }
    $labelEnd = '';
    if ($hasEnd) {
        $endStr = is_string($endHour) ? trim($endHour) : (string)$endHour;
        if ($endStr === '') {
            $labelEnd = '';
        } elseif (strcasecmp($endStr, 'LAST') === 0) {
            $labelEnd = 'LAST';
        } elseif (preg_match('/^\d{2}:\d{2}$/', $endStr)) {
            $labelEnd = $endStr;
        } elseif (preg_match('/^\d{1,2}$/', $endStr)) {
            $endInt = (int)$endStr;
            if ($endInt < 0) { $endInt = 0; }
            if ($endInt > 23) { $endInt = 23; }
            $labelEnd = sprintf('%02d:00', $endInt);
        }
    }
    if ($labelStart !== '' && $labelEnd !== '') {
        return $labelStart . '〜' . $labelEnd;
    }
    return $labelStart ?: $labelEnd;
}

/**
 * 店舗レコードに共通の整形処理を適用する
 *
 * @param array $store
 * @return array
 */
function normalize_store_record(array $store): array {
    $store['category'] = isset($store['category']) && $store['category'] !== null ? (string)$store['category'] : '';

    $startRaw = $store['business_hours_start'] ?? null;
    if ($startRaw === '' || $startRaw === null) {
        $store['business_hours_start'] = null;
    } else {
        $startInt = (int)$startRaw;
        if ($startInt < 0) { $startInt = 0; }
        if ($startInt > 23) { $startInt = 23; }
        $store['business_hours_start'] = $startInt;
    }

    $endRaw = $store['business_hours_end'] ?? '';
    if ($endRaw === null) {
        $endRaw = '';
    }
    $endRaw = is_string($endRaw) ? trim($endRaw) : (string)$endRaw;
    if ($endRaw === '') {
        $store['business_hours_end'] = '';
    } elseif (strcasecmp($endRaw, 'LAST') === 0) {
        $store['business_hours_end'] = 'LAST';
    } elseif (preg_match('/^\d{1,2}$/', $endRaw)) {
        $endInt = (int)$endRaw;
        if ($endInt < 0) { $endInt = 0; }
        if ($endInt > 23) { $endInt = 23; }
        $store['business_hours_end'] = sprintf('%02d:00', $endInt);
    } elseif (preg_match('/^\d{2}:\d{2}$/', $endRaw)) {
        $store['business_hours_end'] = $endRaw;
    } else {
        $store['business_hours_end'] = '';
    }

    $holidayRaw = isset($store['holiday']) && $store['holiday'] !== null ? (string)$store['holiday'] : '';
    $holidayList = [];
    if ($holidayRaw !== '') {
        foreach (explode(',', $holidayRaw) as $holiday) {
            $holiday = trim((string)$holiday);
            if ($holiday !== '') {
                $holidayList[] = $holiday;
            }
        }
    }
    $store['holiday'] = $holidayRaw;
    $store['holiday_list'] = $holidayList;

    $label = build_store_hours_label($store['business_hours_start'], $store['business_hours_end']);
    $store['business_hours_label'] = $label;
    if ((!isset($store['business_hours']) || $store['business_hours'] === null || $store['business_hours'] === '') && $label !== '') {
        $store['business_hours'] = $label;
    }

    return $store;
}

/**
 * 店舗レコード配列に整形処理を適用する
 *
 * @param array $stores
 * @return array
 */
function normalize_store_records(array $stores): array {
    foreach ($stores as $idx => $store) {
        if (is_array($store)) {
            $stores[$idx] = normalize_store_record($store);
        }
    }

    return $stores;
}

/**
 * 店舗リストと画像を効率的に一括取得する関数
 * 
 * @return array|false 店舗データ（各店舗にimages配列が追加される）、失敗時はfalse
 */
function get_store_list_with_images() {
    // 1. 店舗データを取得（削除済み除外、新しい順）
    $sql = "SELECT * FROM stores WHERE deleted_at IS NULL ORDER BY created_at DESC";
    $stores = executeQuery($sql);
    
    if ($stores === false || empty($stores)) {
        return $stores;
    }
    
    // 2. 店舗IDの配列を作成
    $store_ids = array_column($stores, 'id');
    
    // 3. 画像を一括取得（WHERE store_id IN (...)）
    $placeholders = str_repeat('?,', count($store_ids) - 1) . '?';
    $sql = "SELECT store_id, image_url, sort_order FROM store_images WHERE store_id IN ({$placeholders}) ORDER BY store_id, sort_order";
    
    $images = executeQuery($sql, $store_ids);
    
    // 4. 画像をstore_idでグルーピング
    $images_by_store = [];
    if ($images !== false) {
        foreach ($images as $image) {
            $images_by_store[$image['store_id']][] = $image;
        }
    }
    
    // 5. 各店舗データに画像情報を追加
    foreach ($stores as &$store) {
        $store['images'] = isset($images_by_store[$store['id']]) ? $images_by_store[$store['id']] : [];
    }
    
    unset($store);

    $stores = normalize_store_records($stores);

    return $stores;
}

/**
 * FAQ一覧を取得する関数
 * 
 * @return array|false FAQデータ、失敗時はfalse
 */
function get_faq_list() {
    $sql = "SELECT id, question, answer_html, sort_order 
            FROM faqs 
            WHERE status = 'published' 
            ORDER BY sort_order ASC";
    return executeQuery($sql);
}

/**
 * 一覧用: 求人の件数を取得（ページネーション用）
 * 
 * @param array $filters フィルタ条件（将来拡張用）
 * @return int 件数（失敗時は0）
 */
function count_jobs($filters = []) {
    $sql = "SELECT COUNT(*) AS cnt FROM jobs WHERE status = 'published' AND deleted_at IS NULL";
    $conditions = [];
    $params = [];

    // フィルタ（必要に応じて拡張）
    if (isset($filters['keyword']) && $filters['keyword'] !== '') {
        $conditions[] = "(title LIKE ? OR description LIKE ?)";
        $kw = '%' . $filters['keyword'] . '%';
        $params[] = $kw;
        $params[] = $kw;
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $rows = executeQuery($sql, $params);
    if ($rows === false || empty($rows)) {
        return 0;
    }
    return (int)$rows[0]['cnt'];
}

/**
 * 一覧用: 求人をページング取得し、画像を一括付与する
 * 
 * @param array $filters フィルタ条件（将来拡張用）
 * @param int $offset 取得開始オフセット
 * @param int $limit 取得件数
 * @return array|false 求人配列（各要素に images 配列付き）、失敗時はfalse
 */
function get_jobs($filters = [], $offset = 0, $limit = 20) {
    $sql = "SELECT * FROM jobs WHERE status = 'published' AND deleted_at IS NULL";
    $conditions = [];
    $params = [];

    // フィルタ（必要に応じて拡張）
    if (isset($filters['keyword']) && $filters['keyword'] !== '') {
        $conditions[] = "(title LIKE ? OR description LIKE ?)";
        $kw = '%' . $filters['keyword'] . '%';
        $params[] = $kw;
        $params[] = $kw;
    }

    // エリア絞り込み（country / region_prefecture のいずれか一致）
    if (isset($filters['area']) && $filters['area'] !== '' && $filters['area'] !== 'all') {
        $conditions[] = "(country = ? OR region_prefecture = ?)";
        $params[] = $filters['area'];
        $params[] = $filters['area'];
    }

    // 指定IDを除外
    if (isset($filters['exclude_id']) && (int)$filters['exclude_id'] > 0) {
        $conditions[] = "id <> ?";
        $params[] = (int)$filters['exclude_id'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    // 新着順（created_at が無い環境では id の降順で近似）
    $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;

    $jobs = executeQuery($sql, $params);
    if ($jobs === false || empty($jobs)) {
        return $jobs;
    }

    $job_ids = array_column($jobs, 'id');
    if (empty($job_ids)) {
        return $jobs;
    }

    $placeholders = str_repeat('?,', count($job_ids) - 1) . '?';
    $imgSql = "SELECT job_id, image_url, sort_order FROM job_images WHERE job_id IN ({$placeholders}) ORDER BY job_id, sort_order";
    $images = executeQuery($imgSql, $job_ids);

    $images_by_job = [];
    if ($images !== false) {
        foreach ($images as $image) {
            $images_by_job[$image['job_id']][] = $image;
        }
    }

    foreach ($jobs as &$job) {
        $job['images'] = isset($images_by_job[$job['id']]) ? $images_by_job[$job['id']] : [];
    }
    unset($job);

    return $jobs;
}

/**
 * 一覧用: 記事（特集・コラム）の件数を取得
 * 
 * @param array $filters 例: ['category' => 'エリア紹介']
 * @return int 件数（失敗時は0）
 */
function count_articles($filters = []) {
    $sql = "SELECT COUNT(*) AS cnt FROM articles WHERE status = 'published' AND deleted_at IS NULL";
    $conditions = [];
    $params = [];

    if (isset($filters['category']) && $filters['category'] !== '' && $filters['category'] !== 'all') {
        $conditions[] = "category = ?";
        $params[] = $filters['category'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $rows = executeQuery($sql, $params);
    if ($rows === false || empty($rows)) {
        return 0;
    }
    return (int)$rows[0]['cnt'];
}

/**
 * 一覧用: 記事（特集・コラム）をページング取得
 * 
 * @param array $filters 例: ['category' => 'エリア紹介']
 * @param int $offset 取得開始オフセット
 * @param int $limit 取得件数
 * @return array|false 記事配列、失敗時はfalse
 */
function get_articles($filters = [], $offset = 0, $limit = 20) {
    $sql = "SELECT id, title, slug, category, og_image_url, published_at, updated_at 
            FROM articles 
            WHERE status = 'published' AND deleted_at IS NULL";
    $conditions = [];
    $params = [];

    if (isset($filters['category']) && $filters['category'] !== '' && $filters['category'] !== 'all') {
        $conditions[] = "category = ?";
        $params[] = $filters['category'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY published_at DESC, id DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;

    return executeQuery($sql, $params);
}

/**
 * 一覧用: お知らせの件数を取得
 * 
 * @param array $filters 予備（現状未使用）
 * @return int 件数（失敗時は0）
 */
function count_announcements($filters = []) {
    $sql = "SELECT COUNT(*) AS cnt FROM announcements WHERE status = 'published' AND deleted_at IS NULL";
    $rows = executeQuery($sql);
    if ($rows === false || empty($rows)) {
        return 0;
    }
    return (int)$rows[0]['cnt'];
}

/**
 * 一覧用: お知らせをページング取得
 * 
 * @param array $filters 予備（現状未使用）
 * @param int $offset 取得開始オフセット
 * @param int $limit 取得件数
 * @return array|false お知らせ配列、失敗時はfalse
 */
function get_announcements($filters = [], $offset = 0, $limit = 20) {
    $sql = "SELECT id, title, slug, published_at, created_at 
            FROM announcements 
            WHERE status = 'published' AND deleted_at IS NULL 
            ORDER BY created_at DESC, id DESC 
            LIMIT ? OFFSET ?";
    return executeQuery($sql, [(int)$limit, (int)$offset]);
}

/**
 * 一覧用: 店舗の件数を取得
 * 
 * @param array $filters 予備（現状未使用）
 * @return int 件数（失敗時は0）
 */
function count_stores($filters = []) {
    $sql = "SELECT COUNT(*) AS cnt FROM stores WHERE deleted_at IS NULL";
    $conditions = [];
    $params = [];

    // エリア絞り込み（country / region_prefecture のいずれか一致）
    if (isset($filters['area']) && $filters['area'] !== '' && $filters['area'] !== 'all') {
        $conditions[] = "(country = ? OR region_prefecture = ?)";
        $params[] = $filters['area'];
        $params[] = $filters['area'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $rows = executeQuery($sql, $params);
    if ($rows === false || empty($rows)) {
        return 0;
    }
    return (int)$rows[0]['cnt'];
}

/**
 * 一覧用: 店舗をページング取得し、画像を一括付与する
 * 
 * @param array $filters 予備（現状未使用）
 * @param int $offset 取得開始オフセット
 * @param int $limit 取得件数
 * @return array|false 店舗配列（各要素に images 配列付き）、失敗時はfalse
 */
function get_stores($filters = [], $offset = 0, $limit = 20) {
    $sql = "SELECT * FROM stores WHERE deleted_at IS NULL";
    $conditions = [];
    $params = [];

    // エリア絞り込み（country / region_prefecture のいずれか一致）
    if (isset($filters['area']) && $filters['area'] !== '' && $filters['area'] !== 'all') {
        $conditions[] = "(country = ? OR region_prefecture = ?)";
        $params[] = $filters['area'];
        $params[] = $filters['area'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;

    $stores = executeQuery($sql, $params);
    if ($stores === false || empty($stores)) {
        return $stores;
    }

    $store_ids = array_column($stores, 'id');
    if (empty($store_ids)) {
        return $stores;
    }

    $placeholders = str_repeat('?,', count($store_ids) - 1) . '?';
    $imgSql = "SELECT store_id, image_url, sort_order FROM store_images WHERE store_id IN ({$placeholders}) ORDER BY store_id, sort_order";
    $images = executeQuery($imgSql, $store_ids);

    $images_by_store = [];
    if ($images !== false) {
        foreach ($images as $image) {
            $images_by_store[$image['store_id']][] = $image;
        }
    }

    foreach ($stores as &$store) {
        $store['images'] = isset($images_by_store[$store['id']]) ? $images_by_store[$store['id']] : [];
    }
    unset($store);

    $stores = normalize_store_records($stores);

    return $stores;
}

/**
 * 詳細用: 単一の求人を取得し、画像や店舗情報を付与する
 *
 * @param int $id 求人ID
 * @return array|null 取得できた場合は求人配列（images/stores含む場合あり）、存在しない場合はnull
 */
function get_job_by_id($id) {
    $id = (int)$id;
    if ($id <= 0) {
        return null;
    }

    // 求人本体を取得（削除済みは除外）
    $jobSql = "SELECT * FROM jobs WHERE id = ? AND (deleted_at IS NULL) LIMIT 1";
    $job = executeQuerySingle($jobSql, [$id]);
    if ($job === false || empty($job)) {
        return null;
    }

    // 画像を取得（ソート順）
    $imgSql = "SELECT image_url, sort_order FROM job_images WHERE job_id = ? ORDER BY sort_order";
    $images = executeQuery($imgSql, [$id]);
    $job['images'] = $images !== false ? $images : [];

    // 店舗情報があれば付与
    if (isset($job['store_id']) && (int)$job['store_id'] > 0) {
        $store = executeQuerySingle("SELECT * FROM stores WHERE id = ? AND (deleted_at IS NULL) LIMIT 1", [(int)$job['store_id']]);
        if ($store !== false && !empty($store)) {
            $job['store'] = normalize_store_record($store);
        }
    }

    return $job;
}