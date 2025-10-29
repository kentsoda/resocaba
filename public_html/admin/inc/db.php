<?php
// 既存の DB 接続ユーティリティを流用する
require_once __DIR__ . '/../../../config/database.php';

/**
 * 管理画面向けの PDO インスタンスを取得
 *
 * @return PDO|null
 */
function db() {
    return function_exists('getDatabaseConnection') ? getDatabaseConnection() : null;
}

/**
 * 単一の整数値を返す軽量集計クエリ実行
 * エラー時は null を返す
 *
 * @param PDO $pdo
 * @param string $sql
 * @return int|null
 */
function fetchCountOrNull($pdo, $sql) {
    if ($pdo === null) {
        return null;
    }
    try {
        $stmt = $pdo->query($sql);
        $value = $stmt !== false ? $stmt->fetchColumn() : null;
        if ($value === false || $value === null) {
            return null;
        }
        return (int)$value;
    } catch (Throwable $e) {
        error_log('[admin] fetchCountOrNull error: ' . $e->getMessage());
        return null;
    }
}


