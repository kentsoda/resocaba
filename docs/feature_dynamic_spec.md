# 特集詳細ページの動的化 実装仕様

- 対象: `public_html/feature.php`
- 目的: `articles` テーブルのレコードを用いて `/feature/{id}/` を動的配信
- 更新日時 (JST): 2025-10-29 19:25:40 +0900

## ルーティング
- `public_html/.htaccess`
  - `/feature/{id}.html` → `/feature/{id}/` を301（既存の汎用ルールで担保）
  - `/feature/{id}/` → `feature.php?id={id}` へ内部転送

## データ取得
- 取得条件: `id = :id AND status = 'published' AND deleted_at IS NULL`
- API: `executeQuerySingle()`（`config/functions.php` 由来）
- 未検出/未公開: `http_response_code(404)`、簡易案内を本文に表示

## メタ/OG/JSON-LD
- タイトル: `{title}｜特集・コラム｜海外リゾキャバ求人.COM`
- ディスクリプション: `body_html` のテキスト化→先頭約160字
- OGP画像: `og_image_url` なければ `https://placehold.co/1200x630/0ABAB5/ffffff?text=FEATURE`
- URL: `https://{host}/feature/{id}/`（404時は `/features/`）
- JSON-LD: `BreadcrumbList` と `Article`（日時はJSTのISO8601）

## 表示の動的差し込み
- パンくず末尾、H1、日付（`Y.m.d`）、カテゴリ、本文（`body_html`）
- 既存のレイアウト/スタイルは変更しない

## 前後記事ナビ
- `published_at` があればそれ基準、無ければ `id` 基準
- Prev: 直前（降順）、Next: 直後（昇順）
- いずれも `status='published' AND deleted_at IS NULL`

## エラーハンドリング
- 404: 見つからない/未公開
- 500系はサーバーログで確認（画面は一般向け文言）

## テスト観点
- 正常: 既存IDで本文/メタ/OG/JSON-LDが反映
- 404: 不在ID/未公開で404・誘導
- リダイレクト: `/feature/123.html` → `/feature/123/`
- Prev/Next: 先頭/末尾/中間
- バリデーション: 非数値ID、大きすぎるID
