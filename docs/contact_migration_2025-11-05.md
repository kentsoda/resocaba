# お問い合わせページのPHP化とリンク統一

## 実施日
2025-11-05

## 概要
`9.お問い合わせ.html` を `contact.php` に移行し、共通ヘッダー・フッターの読み込み方式に統一。ページ内リンクをディレクトリ形式（末尾スラッシュ）に変更。

## 変更内容

### 1. 新規ファイル作成
- `public_html/contact.php` を作成
  - `includes/header.php` と `includes/footer.php` を使用
  - `terms.php` と `privacy.php` と同じ構造に統一
  - モバイルメニュー対応

### 2. リンクURLの変更
以下のリンクを `.html` 形式からディレクトリ形式（末尾スラッシュ）に変更：
- `/for-beginners.html` → `/for-beginners/`
- `/job-list.html` → `/jobs/`
- `/partners.html` → `/partners/`
- `/announcements.html` → `/announcements/`
- `/features.html` → `/features/`
- `/faq.html` → `/faq/`
- `/contact-ad.html` → `/contact-ad/`
- `/privacy.html` → `/privacy/`
- `/terms.html` → `/terms/`
- `/contact.html` → `/contact/`

### 3. OGP設定の更新
- `og:url`: `https://resocaba-info.com/contact/`
- JSON-LD構造化データを追加（BreadcrumbList, WebPage）

### 4. フォーム設定
- `action` 属性を `/contact/` に変更

### 5. 旧ファイル削除
- `public_html/9.お問い合わせ.html` を削除
- リダイレクト設定は実施しない（ユーザー要望）

## 公開URL
- 新URL: `/contact/`（`.htaccess` のルールにより `contact.php` が解決される）

## 技術的な変更点
- PHP変数設定によるメタ情報管理
- 共通ヘッダー・フッターの読み込み方式に統一
- モバイルメニューの追加（他ページと同構造）
- ログイン・登録ボタンの追加（ヘッダーナビに）

## 影響範囲
- お問い合わせページのみ（他ページへの影響なし）

