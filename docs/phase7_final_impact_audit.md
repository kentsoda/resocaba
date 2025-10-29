# フェーズ7: 影響範囲 最終調整 監査メモ

最終更新: 2025-10-27 19:12 JST

## 対象
- `public_html/jobs.php`
- `public_html/features.php`
- `public_html/announcements.php`
- `public_html/partners.php`
- `public_html/faq.php`
- 共通ヘッダー: `public_html/includes/header.php`
- トップ: `public_html/index.php`

## チェック観点（計画との差分確認）
- パンくず/ナビ/サイドバーのリンクが StepB 規約（拡張子なし・末尾スラッシュ）に統一されているか
- ダミーJSやモック生成コードが残って重複描画・干渉を起こさないか

## 結果サマリ
- ナビ/パンくず/ページ内リンクは一覧・詳細ともに概ね StepB 規約（例: `/jobs/`, `/job/{id}/`）に準拠。
- 一部ページに「将来用のダミーJS（配列初期化やレンダ関数の雛形）」がコメントアウト状態（実行なし）で残存。現状は干渉なし。
- トップページ（`index.php`）の旧式リンク（`/job.php?id=...`, `/article.php?id=...`, `announcement.php?id=...`）は、すべて新URL（`/job/{id}/`, `/feature/{id}/`, `/announcement/{id}/`）へ置換完了。

## 詳細指摘

### 1) URL規約の遵守状況
- 共通ヘッダー（`includes/header.php`）
  - メインナビ: `/jobs/`, `/partners/`, `/announcements/`, `/features/`, `/faq/` → 問題なし。
- `jobs.php`
  - 一覧: `/jobs/?page=N` → 仕様どおり。
  - カード/ボタン: `/job/{id}/` → 問題なし。
- `features.php`
  - 一覧: `/features/?page=N[&category=...]` → 問題なし。
  - カード/ボタン: `/feature/{id}/` → 問題なし。
- `announcements.php`
  - 一覧: `/announcements/?page=N` → 問題なし。
  - 行リンク: `/announcement/{id}/` → 問題なし。
- `partners.php`
  - 一覧: `/partners/?page=N[&area=...]` → 問題なし。
  - カード/ボタン: `/partner/{id}/` → 問題なし。
- `faq.php`
  - 一覧: `/faq/` 固定 → 問題なし。
- トップ（`index.php`）
  - 旧リンクは全て置換済み（`.php?id=` は残存なし）。

### 2) ダミーJS/スタブの残存と影響
- `jobs.php`
  - 旧モック関連関数は削除済みコメント。実行ロジックは実データ前提に更新済み。
  - レイアウト切替（Swiper/グリッド）初期化のみ動作。重複描画なし。
- `features.php`
  - DOM 操作はヘッダーのモバイルメニュー程度。ダミーデータ生成無し。干渉なし。
- `announcements.php`
  - `const announcements = [];` 等の雛形・`renderFilters()`/`renderAnnouncements()` の呼び出しはコメントアウトで無効化。
  - 現状影響なしだが、不要コードとして C-2 で削除候補。
- `partners.php`
  - `const partners = [];` 等の雛形・`render*` 関数は定義のみで呼び出しコメントアウト。干渉なし。
- `faq.php`
  - アコーディオン開閉のみ。ダミーデータなし。

【対応方針（提案、今回変更は未実施）】
- ステップC-2で `announcements.php` と `partners.php` の未使用JS雛形を削除し、読みやすさと将来の誤用リスクを低減。

## 影響リスクと優先度
- 残存高リスク項目はなし（トップリンク統一済み）。
- 低: 未使用JS雛形 → 現状は未実行のため副作用なし。ただし保守性低下。

## 推奨修正（次アクション案）
1. ステップC-2 にて未使用JS雛形の削除
   - 対象: `announcements.php`, `partners.php`

## 検証観点（修正後）
- トップからのリンク遷移で 404/301 が発生しない
- JSON-LD/Breadcrumb が意図通り（URL表記の整合）
- 一覧各ページのページネーションに影響がない

---
この文書は差分記録です。今回は `index.php` のリンク統一のみを実施し、C-2想定のJS削除は未実施です。
