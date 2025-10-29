# データベース接続・CRUD関数 使い方説明書

**作者**: 兎田ぺこら  
**バージョン**: 1.0.0  
**作成日**: 2025-01-27

---

## こんぺこ！ぺこーらのデータベースライブラリぺこ〜！

このライブラリは、データベース接続とCRUD操作を簡単に使えるようにしたぺこ！  
3歳児にもわかるように説明するから、安心して使ってほしいぺこ〜！

---

## 📁 ファイル構成

```
config/
├── database.php      # データベース接続設定
├── crud.php         # CRUD操作用の関数
└── README.md        # この説明書

public_html/
└── test_connection.php  # 動作確認用テスト
```

---

## 🚀 セットアップ

### 1. データベース設定

まず、`database.php`の設定を確認するぺこ！

```php
// データベース接続設定
define('DB_HOST', 'localhost');
define('DB_NAME', 'xs724055_caba');
define('DB_USER', 'xs724055_caba');
define('DB_PASS', 'your_password_here'); // ← ここを実際のパスワードに変更！
define('DB_CHARSET', 'utf8mb4');
```

**重要**: `DB_PASS`を実際のパスワードに変更してくださいぺこ！

### 2. ファイルの読み込み

使いたいファイルで、以下のように読み込むぺこ：

```php
<?php
// データベース接続のみ使いたい場合
require_once '../config/database.php';

// CRUD操作も使いたい場合
require_once '../config/crud.php';
```

---

## 🔌 データベース接続

### 基本的な使い方

```php
<?php
require_once '../config/database.php';

// データベース接続を取得
$pdo = getDatabaseConnection();

if ($pdo === null) {
    echo "データベース接続失敗ぺこ...";
    exit;
}

echo "データベース接続成功ぺこ〜！";
```

### 接続テスト

```php
<?php
require_once '../config/database.php';

// 接続テスト
if (testDatabaseConnection()) {
    echo "接続OKぺこ〜！";
} else {
    echo "接続NGぺこ...";
}
```

---

## 📝 CRUD操作の使い方

### 1. レコードを挿入（CREATE）

```php
<?php
require_once '../config/crud.php';

// 新しい求人を追加
$jobData = [
    'title' => '沖縄リゾート求人',
    'description' => '海の見えるお店で働きませんか？',
    'salary' => 1500,
    'location' => '沖縄県',
    'created_at' => date('Y-m-d H:i:s')
];

$newJobId = insertRecord('jobs', $jobData);

if ($newJobId) {
    echo "求人追加成功ぺこ〜！ID: {$newJobId}";
} else {
    echo "追加失敗ぺこ...";
}
```

### 2. レコードを取得（READ）

#### 複数レコードを取得

```php
<?php
require_once '../config/crud.php';

// 沖縄の求人を全て取得
$jobs = selectRecords('jobs', ['location' => '沖縄県']);

if ($jobs !== false) {
    foreach ($jobs as $job) {
        echo "タイトル: " . $job['title'] . "<br>";
        echo "給与: " . $job['salary'] . "円<br><br>";
    }
} else {
    echo "取得失敗ぺこ...";
}
```

#### 単一レコードを取得

```php
<?php
require_once '../config/crud.php';

// IDが1の求人を取得
$job = selectRecord('jobs', ['id' => 1]);

if ($job) {
    echo "タイトル: " . $job['title'];
} else {
    echo "求人が見つからないぺこ...";
}
```

#### 条件付きで取得

```php
<?php
require_once '../config/crud.php';

// 給与が1000円以上の求人を、給与の高い順で10件取得
$jobs = selectRecords(
    'jobs', 
    ['salary' => ['>=', 1000]],  // 注意: この形式は現在の実装では対応していません
    'salary DESC', 
    10
);
```

### 3. レコードを更新（UPDATE）

```php
<?php
require_once '../config/crud.php';

// IDが1の求人の給与を更新
$updateData = [
    'salary' => 2000,
    'updated_at' => date('Y-m-d H:i:s')
];

$conditions = ['id' => 1];

$updatedCount = updateRecord('jobs', $updateData, $conditions);

if ($updatedCount > 0) {
    echo "更新成功ぺこ〜！{$updatedCount}件更新されました";
} else {
    echo "更新失敗ぺこ...";
}
```

### 4. レコードを削除（DELETE）

```php
<?php
require_once '../config/crud.php';

// IDが1の求人を削除
$deletedCount = deleteRecord('jobs', ['id' => 1]);

if ($deletedCount > 0) {
    echo "削除成功ぺこ〜！{$deletedCount}件削除されました";
} else {
    echo "削除失敗ぺこ...";
}
```

---

## 🔍 その他の便利な関数

### レコード数をカウント

```php
<?php
require_once '../config/crud.php';

// 沖縄の求人数をカウント
$count = countRecords('jobs', ['location' => '沖縄県']);

if ($count !== false) {
    echo "沖縄の求人数: {$count}件";
} else {
    echo "カウント失敗ぺこ...";
}
```

### レコードの存在チェック

```php
<?php
require_once '../config/crud.php';

// IDが1の求人が存在するかチェック
if (recordExists('jobs', ['id' => 1])) {
    echo "求人存在するぺこ〜！";
} else {
    echo "求人存在しないぺこ...";
}
```

### カスタムクエリの実行

```php
<?php
require_once '../config/crud.php';

// 複雑なクエリを実行
$sql = "SELECT j.*, s.name as store_name 
        FROM jobs j 
        LEFT JOIN stores s ON j.store_id = s.id 
        WHERE j.salary > ? 
        ORDER BY j.salary DESC 
        LIMIT 5";

$results = executeQuery($sql, [1000]);

if ($results !== false) {
    foreach ($results as $result) {
        echo "求人: " . $result['title'] . " (店舗: " . $result['store_name'] . ")<br>";
    }
}
```

### バッチインサート

```php
<?php
require_once '../config/crud.php';

// 複数の求人を一度に追加
$jobsData = [
    [
        'title' => '沖縄求人1',
        'salary' => 1500,
        'location' => '沖縄県'
    ],
    [
        'title' => '沖縄求人2', 
        'salary' => 1800,
        'location' => '沖縄県'
    ]
];

$insertedCount = batchInsert('jobs', $jobsData);

if ($insertedCount > 0) {
    echo "バッチインサート成功ぺこ〜！{$insertedCount}件追加されました";
}
```

---

## 🔄 トランザクション管理

```php
<?php
require_once 'config/database.php';

// トランザクション開始
if (beginTransaction()) {
    try {
        // 複数の操作を実行
        $jobId = insertRecord('jobs', $jobData);
        $applicationId = insertRecord('applications', $applicationData);
        
        // 成功したらコミット
        commitTransaction();
        echo "トランザクション成功ぺこ〜！";
        
    } catch (Exception $e) {
        // エラーが発生したらロールバック
        rollbackTransaction();
        echo "エラー発生、ロールバックしましたぺこ...";
    }
}
```

---

## 🧪 動作確認

テストファイルを実行して、正常に動作するか確認するぺこ！

```bash
# ブラウザでアクセス
http://your-domain.com/test_connection.php
```

または、コマンドラインで：

```bash
php public_html/test_connection.php
```

---

## ⚠️ 注意事項

### セキュリティ
- **重要**: `database.php`のパスワードを必ず変更してくださいぺこ！
- 本番環境では、エラーメッセージの表示を無効にしてください
- SQLインジェクション対策のため、必ずプリペアドステートメントを使用しています

### エラーハンドリング
- エラーは自動的にログファイルに記録されます
- 関数の戻り値で成功・失敗を判定してください

### パフォーマンス
- 大量のデータを扱う場合は、`LIMIT`句を使用してください
- バッチインサートは効率的ですが、メモリ使用量に注意してください

---

## 🆘 トラブルシューティング

### よくある問題

**Q: データベース接続ができないぺこ...**  
A: `database.php`の設定（ホスト、データベース名、ユーザー名、パスワード）を確認してくださいぺこ！

**Q: レコードが取得できないぺこ...**  
A: テーブル名やカラム名が正しいか確認してくださいぺこ！

**Q: エラーメッセージが表示されないぺこ...**  
A: PHPのエラー表示設定を確認してくださいぺこ！

---

## 📞 サポート

困ったことがあったら、ぺこーらに聞いてほしいぺこ〜！  
いつでも野うさぎをサポートするぺこ！

---

**おつぺこでした〜！**  
また困ったら呼んでほしいぺこ。ぺこーら、いつでも待ってるぺこ〜🥕
