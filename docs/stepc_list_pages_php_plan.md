# ステップC: 一覧ページのPHP化 実装計画

最終更新: 2025-10-24 20:33 JST

## 概要
一覧系ページ（求人/特集・コラム/お知らせ/掲載店舗/FAQ）の表示を、静的モックやフロント側のダミーJSではなく、PHP + DB のループ描画に統一する。UI/UX（見た目・クラス・構造）は変更しない。まずは `jobs.php` から着手し、同じ設計を他の一覧に横展開する。

本ステップの目的は「テンプレ/ループ導入とページネーションのサーバサイド化」。不要JSの削除はステップC-2で別PRとする（本書では影響点を明記するに留める）。

実装が完了したフェーズにはマーク✅を付ける。

## 対象ファイルと修正方針

- `public_html/jobs.php`
  - 取得: `config/functions.php` を読み込み、求人 + 画像を DB から取得（既存 `get_job_list_with_images()` を活用）。
  - ループ: 求人カードを PHP ループで描画。
  - ページネーション: `?page=N` 形式で実装（デフォルト `page=1`、1ページあたり件数は暫定 20）。
  - リンク: StepB の新URL規約に準拠（例: `/job/{id}/`）。
  - JS: 既存のダミー/スタブは挙動を阻害しない範囲で温存（削除はC-2）。

- `public_html/features.php`
  - 取得: 記事一覧を DB から取得。既存 `get_article_list($limit)` はトップ向けの限定版のため、ページング汎用関数を追加して対応（後述）。
  - ループ/ページネーション: `jobs.php` と同一設計。カテゴリ（`category`）での絞り込みはクエリ（`?category=...`）を受け取り可能とするが、UIは変更しない。
  - リンク: `/feature/{id}/`。

- `public_html/announcements.php`
  - 取得: お知らせ一覧を DB から取得。`get_announcement_list($limit)` は限定版のため、ページング汎用関数を追加（後述）。
  - ループ/ページネーション: 同一設計。カテゴリは必要に応じて拡張できる形に。
  - リンク: `/announcement/{id}/`。

- `public_html/partners.php`
  - 取得: 店舗 + 画像を DB から取得（既存 `get_store_list_with_images()` を活用）。
  - ループ/ページネーション: 同一設計。エリア等のフィルタは将来拡張前提の受け口のみ（UI変更なし）。
  - リンク: `/partner/{id}/`。

- `public_html/faq.php`
  - 取得: `get_faq_list()` を使用。現状は全件でも問題ない前提。必要に応じて将来ページネーションへ拡張可。
  - ループ: Q/A の繰り返しを PHP で描画。

【非対象（本ステップ）】
- トップ（`index.php`）の完全動的化は別ステップで扱う。
- 詳細ページのDB化 + OGP/JSON-LD動的化はステップD。
- 不要JSの削除はステップC-2。

## 追加/変更が必要なサーバサイド関数（`config/functions.php`）

既存の「トップ用限定件数」関数だけでなく、一覧ページ用に汎用のページング関数を追加する。

- 求人（jobs）
  - `get_jobs(array $filters, int $offset, int $limit): array`
  - `count_jobs(array $filters): int`
  - 備考: 画像は既存の一括取得ロジックを再利用（IN 句 + 並び）。

- 記事（articles = 特集・コラム）
  - `get_articles(array $filters, int $offset, int $limit): array`
  - `count_articles(array $filters): int`
  - フィルタ例: `category`（UIは既存ボタンを使用、内部は任意）

- お知らせ（announcements）
  - `get_announcements(array $filters, int $offset, int $limit): array`
  - `count_announcements(array $filters): int`

- 店舗（stores）
  - `get_stores(array $filters, int $offset, int $limit): array`
  - `count_stores(array $filters): int`
  - 画像は既存の一括取得ロジックを再利用。

実装要点:
- すべて Prepared Statement（`executeQuery`）で実装。
- `deleted_at IS NULL`、`status='published'` 等の既存規約を継承。
- `ORDER BY` は既存の一覧に合わせる（新着順: `created_at DESC`/`published_at DESC` など）。

## ページネーション仕様（共通）

- パラメータ: `?page=N`（1始まり）。
- 1ページ件数: 20（暫定。将来 `.env` や定数化可能）。
- URL: 既存の末尾スラッシュ形式を維持（例: `/jobs/?page=2`）。
- 前へ/次へ: `?page=N±1` を生成。クエリに他のパラメータがある場合は引き継ぐ。
- SEO: 本ステップでは UI/UX変更なしのため、`rel="prev"/"next"` は任意（追加する場合はテンプレ内で `<link>` を出力）。

## 実装フェーズ

### フェーズ1: データアクセス層の拡張（汎用ページング関数の追加） ✅ 完了
- `config/functions.php` に各一覧用の `get_*`/`count_*` を追加。
- 既存の画像一括取得ロジック（jobs/stores）を流用し、N+1 を回避。
- SQLの WHERE はフィルタが無い場合でも安全に生成（ベース WHERE に AND で連結）。

### フェーズ2: `jobs.php` をPHPループ化（基準実装） ✅ 完了
- `require_once __DIR__ . '/../config/functions.php';` を先頭で読み込み。
- `$page`, `$limit`, `$offset` を計算し、`get_jobs()`/`count_jobs()` を呼び出し。
- 既存HTML構造を保持したまま、求人カードを `foreach` で出力。
- ページネーションUI（前/次とページ番号）を生成。
- リンクは StepB 規約の新URLを使用。

### フェーズ3: `features.php` をPHPループ化 ✅ 完了
- `get_articles()`/`count_articles()` を使用（カテゴリクエリは任意）。
- 既存カードレイアウトをそのままループへ置換。

### フェーズ4: `announcements.php` をPHPループ化 ✅ 完了
- `get_announcements()`/`count_announcements()` を使用。
- 既存のJSON-LD/Breadcrumb等の構造は維持。

### フェーズ5: `partners.php` をPHPループ化 ✅ 完了
- `get_stores()`/`count_stores()` を使用し、カードと画像を描画。

### フェーズ6: `faq.php` をPHPループ化 ✅ 完了
- `get_faq_list()` の出力をループで描画（現状は全件でOK）。

### フェーズ7: 影響範囲の最終調整 ✅ 完了
- パンくず/ナビ/サイドバーのリンクが StepB 規約に統一されているか再確認。
- ダミーJSが重複描画や動作干渉を引き起こさないか確認（削除はC-2）。
- 調査結果 -> /docs/phase7_final_impact_audit.md

## 検証フェーズ

### 動作確認項目
1. 一覧が DB データで描画される（静的モックが残っていない）。
2. ページネーションが期待通りに動作（件数・端ページ・クエリ引継ぎ）。
3. 詳細リンクが新URLで遷移（404/301 なし）。
4. 既存のスタイル/クラス/レイアウトが維持（UI変更なし）。
5. 既存の JSON-LD/OG などが壊れていない。

### テスト手順
1. 各一覧ページで `?page=1/2/大値` を試験。
2. フィルタ（`features.php` の `?category=`）を指定し、件数・ページネーションの整合性を確認。
3. ブラウザDevToolsのネットワーク/コンソールでエラーが出ていないことを確認。

## リスク対応

### バックアップ戦略
- 変更前に Git コミット、作業ブランチを切る。
- 大きめのテンプレ改修前は対象ファイルのバックアップを取得。

### ロールバック計画
- ループ導入前の静的版へ戻せるよう、コミット粒度を小さく保つ。
- 影響が大きいページはファイル単位で切り戻し可能に。

### パフォーマンス
- 画像は一括取得を原則とし、N+1 を回避。
- 必要に応じてテーブルにインデックス（`published_at`, `created_at`, 外部キー）を確認/追加（別途PR）。

## 完了基準
- [ ] `jobs.php` が DB ループ + ページネーションで描画
- [ ] `features.php` が DB ループ + ページネーションで描画
- [ ] `announcements.php` が DB ループ + ページネーションで描画
- [ ] `partners.php` が DB ループ + ページネーションで描画
- [ ] `faq.php` が DB ループで描画
- [ ] すべてのリンクが StepB 規約（拡張子なし/末尾スラッシュ）に準拠
- [ ] UI/UX の変更なし（見た目・クラス・構造を維持）

## 実装順序推奨
1. データアクセス層（汎用 `get_*`/`count_*`）
2. `jobs.php`（基準実装）
3. `features.php`
4. `announcements.php`
5. `partners.php`
6. `faq.php`
7. 総合テスト・微調整

---

注意: 本ステップは UI/UX を変更しない。不要なダミーJSの削除やプレースホルダー画像の整理はステップC-2で実施する。


