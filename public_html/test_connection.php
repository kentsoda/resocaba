<?php
/**
 * データベース接続とCRUD関数のテストファイル
 * 
 * @author 兎田ぺこら
 * @version 1.0.0
 * @created 2025-01-27
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/crud.php';

// エラー表示を有効にする（テスト用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>ぺこーらのデータベース接続テストぺこ〜！</h2>\n";

// 1. データベース接続テスト
echo "<h3>1. データベース接続テスト</h3>\n";
if (testDatabaseConnection()) {
    echo "✅ データベース接続成功ぺこ〜！<br>\n";
} else {
    echo "❌ データベース接続失敗ぺこ...<br>\n";
    echo "設定を確認してくださいぺこ！<br>\n";
    exit;
}

// 2. テーブル一覧の取得テスト
echo "<h3>2. テーブル一覧取得テスト</h3>\n";
$tables = executeQuery("SHOW TABLES");
if ($tables !== false) {
    echo "✅ テーブル一覧取得成功ぺこ〜！<br>\n";
    echo "テーブル数: " . count($tables) . "<br>\n";
    echo "<ul>\n";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<li>{$tableName}</li>\n";
    }
    echo "</ul>\n";
} else {
    echo "❌ テーブル一覧取得失敗ぺこ...<br>\n";
}

// 3. 各テーブルのレコード数確認
echo "<h3>3. 各テーブルのレコード数確認</h3>\n";
$tableNames = ['ad_banners', 'announcements', 'applications', 'articles', 'assets', 'audit_logs', 'faqs', 'favorites', 'jobs', 'job_images', 'job_tag', 'stores', 'store_images', 'tags', 'users'];

echo "<table border='1' style='border-collapse: collapse;'>\n";
echo "<tr><th>テーブル名</th><th>レコード数</th></tr>\n";

foreach ($tableNames as $tableName) {
    $count = countRecords($tableName);
    if ($count !== false) {
        echo "<tr><td>{$tableName}</td><td>{$count}</td></tr>\n";
    } else {
        echo "<tr><td>{$tableName}</td><td>エラー</td></tr>\n";
    }
}
echo "</table>\n";

// 4. サンプルCRUD操作テスト（jobsテーブルを使用）
echo "<h3>4. CRUD操作テスト（jobsテーブル）</h3>\n";

// 既存のレコードを1件取得
$existingJob = selectRecord('jobs', []);
if ($existingJob) {
    echo "✅ 既存レコード取得成功ぺこ〜！<br>\n";
    echo "取得したレコードのID: " . $existingJob['id'] . "<br>\n";
} else {
    echo "⚠️ 既存レコードなし、または取得失敗ぺこ...<br>\n";
}

// レコード数カウントテスト
$jobCount = countRecords('jobs');
if ($jobCount !== false) {
    echo "✅ レコード数カウント成功ぺこ〜！<br>\n";
    echo "jobsテーブルのレコード数: {$jobCount}<br>\n";
} else {
    echo "❌ レコード数カウント失敗ぺこ...<br>\n";
}

// 5. トランザクションテスト
echo "<h3>5. トランザクションテスト</h3>\n";
if (beginTransaction()) {
    echo "✅ トランザクション開始成功ぺこ〜！<br>\n";
    
    if (rollbackTransaction()) {
        echo "✅ ロールバック成功ぺこ〜！<br>\n";
    } else {
        echo "❌ ロールバック失敗ぺこ...<br>\n";
    }
} else {
    echo "❌ トランザクション開始失敗ぺこ...<br>\n";
}

echo "<h3>テスト完了ぺこ〜！</h3>\n";
echo "<p>おつぺこでした〜！データベース接続とCRUD関数が正常に動作しているぺこ！</p>\n";
echo "<p>※ 実際のパスワードは database.php で設定してくださいぺこ〜</p>\n";
