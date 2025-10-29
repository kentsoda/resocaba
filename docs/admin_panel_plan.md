# 管理画面 実装計画（Admin Panel Implementation Plan）

- 作成日時（JST）: 2025-10-28 07:40:41 JST
- 対象: `public_html/` 配下で稼働する既存 PHP サイト
- 方針: 当面は Basic 認証、左サイドメニュー型ダッシュボード、既存資産の最大活用

## 1. 概要 / 目的
サイトの運用効率化のため、管理者向けの管理画面（ダッシュボード）を新設する。まずは最低限のアクセス制御として Basic 認証を採用し、左サイドメニュー型のレイアウトで主要機能（ダッシュボード、求人管理、応募管理など）の骨組みを提供する。

## 2. 前提・制約
- 既存構成（Apache + PHP、`public_html/`、`.htaccess`）を踏襲。バージョン変更は行わない。
- 認証は当面 Basic 認証のみ（将来アカウント認証へ移行可能に構造化）。
- 管理画面は検索エンジンにインデックスさせない（`X-Robots-Tag`、`robots.txt`）。
- UI/UX は最小限の骨組みのみ（不要な変更は行わない）。

## 3. 重要要件
- 左サイドメニューを持つレイアウト（共通レイアウト化）。
- ダッシュボード初期ページに主要 KPI の簡易サマリを表示。
- 求人・応募に関する一覧/詳細（雛形）を用意。
- 既存 DB 接続・ユーティリティがあれば流用し、重複実装を避ける。

## 4. ディレクトリ / URL 設計（案）
- `public_html/admin/.htaccess`（Basic 認証 + noindex ヘッダ）
- `public_html/admin/index.php`（ダッシュボード）
- `public_html/admin/inc/`（共通: `layout.php`, `header.php`, `footer.php`, `auth.php`, `db.php`, `config.php`）
- `public_html/admin/jobs/index.php`（求人一覧）
- `public_html/admin/jobs/edit.php`（求人編集/新規）
- `public_html/admin/applications/index.php`（応募一覧）
- `public_html/admin/applications/show.php`（応募詳細）
- `public_html/admin/assets/`（管理専用 CSS/JS）

ルーティングはまずフラット構成（各ページ `.php`）。将来的に `index.php?page=...` へ切替可能なように `layout.php` でナビを集中管理。

## 5. 認証（Basic 認証）
- `.htaccess`（例: `public_html/admin/.htaccess`）

```apacheconf
AuthType Basic
AuthName "Admin"
AuthUserFile /home/USER/private/.htpasswd
Require valid-user

# noindex 対策
Header set X-Robots-Tag "noindex, nofollow"
```

- `.htpasswd` は Web ルート外に配置（例: `/home/xs724055/private/.htpasswd` を想定。要最終決定）。
- 必要モジュール: `mod_authn_file`, `mod_auth_basic`, `mod_headers`。

## 6. レイアウト共通化
- `admin/inc/layout.php` に左サイドバー + コンテンツ領域の枠組みを定義。
- `header.php` / `footer.php` 分離、メニューは配列定義でアクティブ判定。
- CSS/JS は最小限（当面はベースのみ）。

## 7. 画面一覧（初期スコープ）
- ダッシュボード（`admin/index.php`）
  - KPI: 総求人件数 / 公開求人 / 非公開、直近応募数、最近更新 5 件
- 求人管理（`admin/jobs/`）
  - 一覧（ページネーション雛形）/ 編集（新規兼用）
- 応募管理（`admin/applications/`）
  - 一覧 / 詳細（個人情報の取り扱いに配慮）
- 設定（将来用）/ ログ・監査（将来用）

## 8. データ・既存資産の流用
- 既存の DB 接続・HTML テンプレ・ユーティリティ（サニタイズ等）があれば `admin/inc/` から include して流用。
- 無い場合は `admin/inc/db.php` を新設（PDO 推奨、接続情報は `config.php` へ）。

## 9. セキュリティ / SEO
- `X-Robots-Tag: noindex, nofollow`（`admin/.htaccess` で Header 付与）。
- `robots.txt` に `Disallow: /admin/` を追加（既存記述との整合確認）。
- フォーム導入時は CSRF トークン、Referer チェック、`SameSite` 属性の検討。
- 出力エスケープの徹底、SQL はプリペアドステートメント使用。

## 10. ログ / 監査（当面の方針）
- 初期は PHP エラーログ/アクセスログの既存仕組みを活用。
- 操作ログは将来要件で DB 化 or ファイル出力を選択。

## 11. テスト計画（抜粋）
- 認証: 成功/失敗、未認証時に全管理ページが 401 になること。
- SEO: `X-Robots-Tag` 付与確認、`robots.txt` 反映。
- UI: サイドバーのアクティブ状態、主要ブラウザ表示崩れの有無。
- パフォーマンス: 初期ダッシュボードの集計は軽量に（必要なら遅延）。

## 12. 実装手順（推奨順）
1. `public_html/admin/` 作成、`.htaccess` と `.htpasswd` 設定
2. 共通レイアウト（`inc/layout.php`、`header.php`、`footer.php`）
3. ダッシュボード骨組み（KPI は軽量集計）
4. メニュー定義と空ページ雛形（求人/応募）
5. 既存 DB 接続/ユーティリティの取り込み
6. `X-Robots-Tag` と `robots.txt` の noindex 対策
7. 動作確認・調整

## 13. 見積り（目安）
- 骨組み（認証/レイアウト/ダッシュボード雛形）: 0.5〜1.0 日
- 求人/応募の一覧雛形: 0.5 日
- 既存資産取り込みと調整: 0.5 日
- 検証・微調整: 0.5 日
- 合計: 1.5〜2.5 日（既存流用状況により変動）

## 14. 承認・決定事項
- `.htpasswd` の保存先フルパス（例: `/home/xs724055/private/.htpasswd`）。
- ディレクトリ/ページ構成（本計画案の通りで可か）。
- 既存 DB/ユーティリティの流用対象（有無の確認）。

---

## 15. 具体的な実装の流れ（全体像）

### Phase 1: 管理画面の骨組み構築
1. **認証・セキュリティ設定**
   - `public_html/admin/.htaccess` 作成（Basic認証 + noindex）
   - `.htpasswd` ファイル作成・配置
   - `robots.txt` に `/admin/` 追加

2. **共通レイアウト構築**
   - `admin/inc/layout.php`（左サイドバー + コンテンツ領域）
   - `admin/inc/header.php` / `admin/inc/footer.php`
   - `admin/inc/config.php` / `admin/inc/db.php`
   - メニュー定義とアクティブ判定ロジック

3. **ダッシュボード骨組み**
   - `admin/index.php`（KPI表示の雛形）
   - 軽量な集計処理（総求人数、応募数等）

4. **各ページの雛形作成**
   - `admin/jobs/index.php`（求人一覧の空ページ）
   - `admin/jobs/edit.php`（求人編集の空ページ）
   - `admin/applications/index.php`（応募一覧の空ページ）
   - `admin/applications/show.php`（応募詳細の空ページ）

### Phase 2: データベース構造の拡張
1. **既存DB構造の確認**
   - 既存テーブル・カラムの調査
   - 既存のDB接続・ユーティリティの確認（`config/database.php` 等）
   - 関連ドキュメント：`管理画面CMS_仕様まとめ_2025-10-28.md` 参照

2. **必要なカラム追加**
   - **`jobs` テーブル**
     - `currency`（通貨）
     - `job_type`（募集職種）
     - `card_message`（求人カード用メッセージ）
     - `meta_json`（応募資格、待遇・福利厚生、最低勤務期間等をJSON形式で格納）
     - SQL: `sql/add_jobs_currency_jobtype_card_message_2025-10-27.sql`
     - SQL: `sql/add_jobs_meta_extensions_2025-10-27.sql`（仕様ドキュメント）
   
   - **`job_images` テーブル**
     - `sort_order`（画像の並び順）
     - SQL: `sql/add_job_images_sort_order_2025-10-27.sql`
   
   - **`stores` テーブル**
     - `category`（店舗カテゴリ）
     - `business_hours_start`（営業時間開始）
     - `business_hours_end`（営業時間終了）
     - SQL: `sql/add_stores_category_hours_split_2025-10-27.sql`
   
   - **`store_images` テーブル（新規作成）**
     - 店舗画像管理用テーブル
     - SQL: `sql/add_store_images_table_2025-10-27.sql`
   
   - **`tags` テーブル**
     - `category`（タグのグループ化用）
     - SQL: `sql/add_tags_category_2025-10-27.sql`

3. **既存データの移行・調整**
   - `job_images.sort_order` の初期化（既存画像を`id`順で初期設定）
   - データ整合性の確認
   - 全SQLファイルは `/sql/` ディレクトリに配置済み

### Phase 3: 管理画面の詳細機能実装
1. **求人管理機能**
   - 一覧表示（ページネーション、検索、フィルタ）: 実装済（`admin/jobs/index.php`）
   - 編集・新規作成（全項目対応、WYSIWYG対応）: 実装済（`admin/jobs/edit.php`）
   - 画像並べ替え・削除（初期化SQL適用前提）: 実装済（`admin/jobs/images.php`）

2. **応募管理機能**
   - 応募一覧・詳細表示: 実装済（`admin/applications/index.php`, `admin/applications/show.php`）
   - 個人情報の適切な取り扱い: 一覧はマスク表示、詳細はトグルで表示制御

3. **その他管理機能**
   - 店舗/お知らせ/ブログ: 雛形ページ配置（`admin/stores/*`, `admin/notices/*`, `admin/blog/*`）
   - 将来、本文/説明にWYSIWYG適用予定

4. **注意事項**
   - メンテナンスしやすいように作る
   - html部分はhtmlで書いて必要な部分だけphp等で挿入する
   - $html = '<div>hogehoge</div>';みたいなやつ禁止
   - <div><?php echo $hogehoge; ?></div>みたいにする
   - 全フォームにCSRFトークン（`admin/inc/csrf.php`）、入力ヘルパー（`admin/inc/form.php`）適用
   - WYSIWYGはTinyMCE（CDN）、画像アップロード/挿入は無効化

### Phase 4: フロントエンドへの反映
1. **フロント側の修正**
   - 新カラムの表示対応
   - 検索・フィルタ機能の拡張
   - レイアウト調整

2. **既存機能の動作確認**
   - 既存ページの表示確認
   - 検索機能の動作確認
   - レスポンシブ対応の確認

### Phase 5: 最終調整・テスト
1. **動作確認**
   - 管理画面の全機能テスト
   - フロント・バック連携テスト
   - セキュリティテスト

2. **パフォーマンス調整**
   - クエリ最適化
   - キャッシュ設定
   - 画像最適化

3. **ドキュメント整備**
   - 操作マニュアル作成
   - 技術仕様書の更新

### 重要な注意事項
- **各Phase開始前に必ずバックアップを取得**
- **DB変更時は既存データへの影響を事前確認**
- **フロント修正時は既存機能の動作確認を徹底**
- **セキュリティ対策（CSRF、XSS、SQLインジェクション）の徹底**

---

この計画に承認後、上記順序で実装を開始します。必要に応じて詳細設計（KPI 定義、権限設計）を追補します。


