# コーディング規約・スタイルガイド

**作成日**: 2025-11-12  
**対象**: 海外リゾキャバ求人.COM プロジェクト

## 概要

このドキュメントは、プロジェクトのコーディング規約とスタイルガイドを定義します。既存のコードベースの慣習に基づいて作成されています。

---

## PHPコーディング規約

### 1. ファイル構造

#### ファイルの先頭

```php
<?php
/**
 * ファイルの説明
 * 
 * @author 作者名
 * @version バージョン
 * @created 作成日
 */
```

#### ファイルの終わり

- ファイル末尾には空行を1行入れる
- `?>` タグは使用しない（PHPのみのファイルの場合）

### 2. 命名規則

#### 変数名

- **スネークケース（snake_case）** を使用
- 意味のある名前を付ける
- 略語は避ける（ただし、一般的な略語は可）

```php
// ✅ 良い例
$job_list = [];
$user_name = '';
$is_active = true;

// ❌ 悪い例
$jl = [];
$un = '';
$ia = true;
```

#### 関数名

- **スネークケース（snake_case）** を使用
- 動詞で始める（get, set, create, update, delete など）

```php
// ✅ 良い例
function get_job_list() { }
function update_record() { }
function is_active() { }

// ❌ 悪い例
function JobList() { }
function updateRecord() { }
function active() { }
```

#### 定数名

- **大文字のスネークケース（UPPER_SNAKE_CASE）** を使用

```php
// ✅ 良い例
define('DB_HOST', 'localhost');
define('MAX_RETRY_COUNT', 3);

// ❌ 悪い例
define('dbHost', 'localhost');
define('maxRetryCount', 3);
```

#### クラス名（使用する場合）

- **パスカルケース（PascalCase）** を使用

```php
// ✅ 良い例
class DatabaseConnection { }
class JobManager { }

// ❌ 悪い例
class database_connection { }
class job_manager { }
```

### 3. インデント・スペース

- **インデント**: スペース4つ（タブは使用しない）
- **行の長さ**: 120文字以内を推奨（可読性を優先）

```php
// ✅ 良い例（インデント4スペース）
function example_function() {
    if ($condition) {
        $result = process_data($data);
        return $result;
    }
}

// ❌ 悪い例（タブ使用）
function example_function() {
	if ($condition) {
		$result = process_data($data);
		return $result;
	}
}
```

### 4. 制御構造

#### if文

- 開始ブレースは同じ行に記述
- 終了ブレースは独立した行に記述
- `elseif` を使用（`else if` は使用しない）

```php
// ✅ 良い例
if ($condition) {
    // 処理
} elseif ($other_condition) {
    // 処理
} else {
    // 処理
}

// ❌ 悪い例
if ($condition)
{
    // 処理
}
else if ($other_condition)
{
    // 処理
}
```

#### foreach文

- 配列の参照渡しは必要な場合のみ使用
- 使用後は `unset()` で参照を解除

```php
// ✅ 良い例
foreach ($items as $item) {
    process_item($item);
}

// 参照渡しが必要な場合
foreach ($items as &$item) {
    $item['processed'] = true;
}
unset($item); // 参照を解除

// ❌ 悪い例
foreach ($items as &$item) {
    process_item($item);
}
// unset() がないと予期しない動作の原因になる
```

### 5. 関数定義

#### 関数の書き方

- 関数名の後にスペース1つを入れる
- パラメータの後にスペース1つを入れる
- デフォルト値を持つパラメータは後ろに配置

```php
// ✅ 良い例
function get_job_list($filters = [], $offset = 0, $limit = 20) {
    // 処理
}

// ❌ 悪い例
function get_job_list($filters=[],$offset=0,$limit=20){
    // 処理
}
```

#### 関数のドキュメント

- PHPDoc形式でコメントを記述
- パラメータと戻り値を明記

```php
/**
 * 求人リストを取得する関数
 * 
 * @param array $filters フィルタ条件（例: ['area' => '沖縄県']）
 * @param int $offset 取得開始オフセット
 * @param int $limit 取得件数
 * @return array|false 求人配列、失敗時はfalse
 */
function get_jobs($filters = [], $offset = 0, $limit = 20) {
    // 処理
}
```

### 6. データベース操作

#### SQLクエリ

- **プリペアドステートメントを必ず使用**
- SQLインジェクション対策のため、直接文字列結合は禁止

```php
// ✅ 良い例
$sql = "SELECT * FROM jobs WHERE id = ?";
$result = executeQuery($sql, [$id]);

// ❌ 悪い例（SQLインジェクションの危険性）
$sql = "SELECT * FROM jobs WHERE id = " . $id;
$result = executeQuery($sql);
```

#### テーブル名・カラム名

- バッククォート（`）で囲む

```php
// ✅ 良い例
$sql = "SELECT * FROM `jobs` WHERE `status` = ?";

// ❌ 悪い例
$sql = "SELECT * FROM jobs WHERE status = ?";
```

### 7. エラーハンドリング

#### エラーログ

- `error_log()` を使用してエラーを記録
- ユーザーには詳細なエラーメッセージを表示しない

```php
// ✅ 良い例
try {
    $result = executeQuery($sql, $params);
    if ($result === false) {
        error_log("クエリ実行エラー: " . $e->getMessage());
        return false;
    }
    return $result;
} catch (PDOException $e) {
    error_log("データベースエラー: " . $e->getMessage());
    return false;
}

// ❌ 悪い例
try {
    $result = executeQuery($sql, $params);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage(); // ユーザーに詳細を表示しない
}
```

### 8. 出力・エスケープ

#### HTML出力

- **必ず `htmlspecialchars()` でエスケープ**
- `ENT_QUOTES` と `UTF-8` を指定

```php
// ✅ 良い例
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// ❌ 悪い例
echo $user_input; // XSSの危険性
```

#### 短縮タグ

- `<?= ?>` は使用可（PHP 5.4以降）
- エスケープ処理を忘れない

```php
// ✅ 良い例
<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>

// ❌ 悪い例
<?= $title ?> // エスケープなし
```

### 9. ファイルの読み込み

#### require_once の使用

- 相対パスは `__DIR__` を使用
- 絶対パスは避ける

```php
// ✅ 良い例
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

// ❌ 悪い例
require_once '/home/user/config/database.php'; // 絶対パス
require_once '../config/database.php'; // __DIR__ なし
```

### 10. 配列操作

#### 配列の書き方

- 短縮構文 `[]` を使用（PHP 5.4以降）
- 連想配列のキーはクォートで囲む

```php
// ✅ 良い例
$array = [];
$array['key'] = 'value';
$array = ['key' => 'value'];

// ❌ 悪い例
$array = array(); // 古い構文
$array[key] = 'value'; // クォートなし
```

---

## HTML・CSS規約

### 1. HTML構造

#### DOCTYPE宣言

- HTML5の標準的なDOCTYPEを使用

```html
<!DOCTYPE html>
<html lang="ja">
```

#### メタタグ

- 文字コードはUTF-8
- viewport設定を必ず含める

```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

### 2. Tailwind CSSの使用

- CDN経由で読み込む
- インラインクラスでスタイリング
- カスタムCSSは最小限に

```html
<!-- ✅ 良い例 -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800">タイトル</h1>
</div>
```

### 3. セマンティックHTML

- 適切なHTMLタグを使用
- `div` の多用を避ける

```html
<!-- ✅ 良い例 -->
<header>
    <nav>
        <ul>
            <li><a href="/">ホーム</a></li>
        </ul>
    </nav>
</header>
<main>
    <article>
        <h1>記事タイトル</h1>
        <p>本文</p>
    </article>
</main>
<footer>
    <p>フッター</p>
</footer>

<!-- ❌ 悪い例 -->
<div class="header">
    <div class="nav">
        <div class="list">
            <div><a href="/">ホーム</a></div>
        </div>
    </div>
</div>
```

---

## JavaScript規約

### 1. 外部ライブラリの使用

- CDN経由で読み込む
- 必要最小限のライブラリのみ使用

```html
<!-- ✅ 良い例 -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
```

### 2. インラインJavaScript

- 最小限に抑える
- 外部ファイルに分離することを推奨

```html
<!-- ✅ 良い例 -->
<script src="/assets/js/main.js"></script>

<!-- ❌ 悪い例 -->
<script>
    // 長いJavaScriptコードがインラインに書かれている
</script>
```

---

## コメント規約

### 1. ファイルヘッダーコメント

```php
<?php
/**
 * ファイルの説明
 * 
 * @author 作者名
 * @version バージョン
 * @created 作成日
 */
```

### 2. 関数コメント

```php
/**
 * 関数の説明
 * 
 * @param string $param1 パラメータ1の説明
 * @param int $param2 パラメータ2の説明
 * @return array|false 戻り値の説明
 */
function example_function($param1, $param2) {
    // 処理
}
```

### 3. インラインコメント

- 複雑なロジックにはコメントを追加
- 自明な処理にはコメント不要

```php
// ✅ 良い例
// タグIDを一意にするため、重複を除去
$tag_ids = array_unique($tag_ids);

// ❌ 悪い例
// 変数に値を代入
$value = 10;
```

---

## Git運用規約

### 1. コミットメッセージ

- 日本語で記述可
- 簡潔で明確に
- 変更内容を説明

```
例:
- 求人一覧ページにページネーション機能を追加
- 管理画面のBasic認証設定を追加
- フッターのリンクをURL統一規約に合わせて修正
```

### 2. ブランチ命名

- 機能追加: `feature/機能名`
- バグ修正: `fix/バグ名`
- リファクタリング: `refactor/対象`

### 3. .gitignore

- 機密情報を含むファイルは必ず除外
- `config/database.php` は除外設定済み
- ログファイル、一時ファイルも除外

---

## コードレビューのポイント

### チェックリスト

- [ ] SQLインジェクション対策（プリペアドステートメント使用）
- [ ] XSS対策（htmlspecialchars使用）
- [ ] エラーハンドリング（try-catch、エラーログ）
- [ ] 関数のドキュメント（PHPDoc）
- [ ] 命名規則の遵守（スネークケース）
- [ ] コードの重複がないか
- [ ] パフォーマンス（N+1問題など）

---

## 関連ドキュメント

- `docs/architecture_overview.md` - アーキテクチャ概要
- `config/README.md` - データベース接続・CRUD関数の使い方
- `docs/database_specification.md` - データベース仕様

