# 実装状況 監査メモ（ヘッダー/フッター, リンク, 動的化）

最終更新: 2025-10-23 17:57 JST

## 対象
- `public_html/` 配下の主要ページ
- `config/` 配下のユーティリティ・DBアクセス

## 概要
- ヘッダー/フッターは各ページに直書きされており、共通コンポーネント化は未実施。
- リンク先の拡張子・パス表記が混在（`.html` 固定、`/resocaba/` プレフィックス、拡張子なしディレクトリ風、相対/絶対混在）。
- 動的化（PHP+DB）は一部で進行中。トップはサーバ側生成に寄せつつあり、`config/functions.php` にDB取得関数が実装済み。ただし各一覧/詳細ページには静的モックが残存。

## 詳細

### 1) ヘッダー/フッターの実装状況（重複）
- 代表例（ヘッダー/フッター断片重複）：
  - `public_html/index.php` 全面にヘッダー/フッター直書き
  - `public_html/announcements.php`, `features.php`, `jobs.php`, `partners.php`, `faq.php`, `feature.php`, `announcement.php`, `job.php` でも同様の構造（クラス名・ナビ項目は微差）
- 差異の例：
  - ナビのリンク形式がページごとに異なる（`/path.html`, `/resocaba/path.html`, `/path/` 等）
  - `job.php` は一部リンクが拡張子なしディレクトリ表記（例: `/jobs/`）

【結論】`includes/header.php` と `includes/footer.php`（仮）として共通化を推奨。アクティブメニュー判定はパスで条件分岐。

### 2) リンク拡張子（.html）とパスの混在
- `.html` 固定リンクの主な残存例：
  - `index.php` 内ヘッダー/パンくず/ボタン: `/for-beginners.html`, `/job-list.html`, `/partners.html`, `/announcements.html`, `/features.html`, `/faq.html`
  - 一覧→詳細: `/partner/{id}.html`, `/job/{id}.html`, `/feature/{id}.html`, `/announcement/{id}.html`
  - `jobs.php` は `/resocaba/` プレフィックス付きで `.html` 使用多数（ページネーション含む）
  - `faq.php` は `/resocaba/` プレフィックスで `.html` 使用
  - `features.php`, `announcements.php`, `partners.php` も `.html` 参照
- 一部は拡張子なし・ディレクトリ形式に移行済み（例：`job.php` のヘッダーは `/jobs/`, `/features/` など）。

【結論】URL規約（拡張子なし + ディレクトリスラッグ）へ統一する方針なら、以下のリダイレクト・置換設計が必要：
- 旧 → 新マッピングテーブル作成
- `.htaccess` またはフロントコントローラで 301 リダイレクト
- テンプレート内のリンク一括置換（コンポーネント化後に1箇所で管理）

### 3) 動的化（PHP + DB）の状況
- 実装済みのDB取得関数（`config/functions.php`）
  - `get_job_list_with_images()`：求人と画像の一括取得
  - `get_announcement_list($limit)`：お知らせ一覧（publishedのみ）
  - `get_article_list($limit)`：記事一覧（publishedのみ）
  - `get_store_list_with_images()`：店舗+画像の一括取得
  - `get_faq_list()`：FAQ一覧
- 実ページでの利用状況：
  - `index.php` が `require_once __DIR__ . '/../config/functions.php';` で読み込み、`get_job_list_with_images()` を呼び出し（行312付近）
  - ただしUI表示は静的HTMLのまま残存部分が多く、生成済みデータの差し込みは限定的
  - `jobs.php` はUIとJSロジックを「DB反映済み前提」の形に寄せているが、実データ埋め込みは未統合箇所あり
  - `announcements.php`, `features.php`, `partners.php`, `faq.php` はリスト部が静的モック/ダミーを含む
  - 詳細ページ（`announcement.php`, `feature.php`, `job.php`）もモック/固定OGなどが残る

【結論】
- サーバサイドでのループ差し込み（PHP）の全面適用が必要。
- スラッグ/IDで単一詳細を取得する`get_*_by_slug_or_id()`系関数を追加し、詳細ページを動的化。
- JSでのモック生成コードは削除/最小化し、レスポンスはPHPで描画。

## 推奨対応（優先度順）
1. 共通化の基盤
   - `includes/header.php`, `includes/footer.php` を新設
   - 全ページで `require` に置換（アクティブメニューは `$current_path` などで制御）
2. URL規約の確定と一括切替
- 規約: 拡張子なし, 末尾スラッシュ, 英小文字スラッグ, かつ `/resocaba/` は廃止しルート直下に統一（例: トップは `https://resocaba-info.com/`）
   - `.htaccess` で `.html` → ディレクトリ体裁へ 301（一覧/詳細とも）
   - `/resocaba/xxx.html` 系は `/xxx/` に 301 統一
   - コンポーネント内リンクを規約に統一
3. 一覧ページの完全動的化
   - `index.php`：求人/特集/お知らせカードをPHPループで生成
   - `jobs.php`, `features.php`, `announcements.php`, `partners.php`, `faq.php`：DBデータに置換
4. 詳細ページAPIの整備と適用
   - `get_job_by_id($id)`, `get_article_by_slug($slug)`, `get_announcement_by_slug($slug)`, `get_store_by_id($id)` などを追加
   - OG/JSON-LDも動的生成
5. 検索/フィルタのURL仕様整理
   - `?area=...` 等のクエリ→内部的に条件ビルダ、ページタイトル/メタをPHPで更新
6. 残タスク
   - パンくず、ボタン、ページネーションのリンク表記統一
   - 画像URLのCDN/最適化検討

## 既存の重複・競合の有無（重複実装防止チェック）
- 共通化部品：現時点で `includes/` 相当は未構築 → 新設してOK
- 関数名競合：`config/functions.php` に一覧取得系は存在、詳細取得系は未定義 → 追加可
- APIエンドポイント：外部APIは未使用、DB直アクセスのみ → 衝突なし

## リスク・注意点
- URL切替はSEO影響が大きい → 301 ルールとサイトマップ更新必須
- テンプレートの差異（`/resocaba/` 有無）を吸収するため、ベースURL定数（例: `BASE_PATH`）導入推奨
- JSのモック生成が残ると重複描画になる → コンポーネント化と同時に整理

## 参考スニペット（出典）

ヘッダー直書き例（`public_html/index.php` 抜粋）

```123:170:/home/xs724055/resocaba-info.com/public_html/announcement.php
        <header id="header" class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 border-b border-[var(--border-color)] transition-all duration-300">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center gap-3">
```

DB関数読み込み（`public_html/index.php`）

```312:314:/home/xs724055/resocaba-info.com/public_html/index.php
<?php
    require_once __DIR__ . '/../config/functions.php';
    $job_list = get_job_list_with_images();
```

`.html`リンクの残存（`public_html/index.php`）

```138:145:/home/xs724055/resocaba-info.com/public_html/announcement.php
                        <a href="/for-beginners.html" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">初めての方</a>
                        <a href="/job-list.html" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">求人検索</a>
                        <a href="/partners.html" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
                        <a href="/announcements.html" class="text-sm font-medium text-[var(--brand-primary)] font-bold">お知らせ</a>
                        <a href="/features.html" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">特集・コラム</a>
                        <a href="/faq.html" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">よくある質問</a>
```

## 次アクション提案（実装計画）
- ステップA: `includes/` 追加、`header.php`/`footer.php` 作成、全ページ置換（1PR）✅ 完了
  - `public_html/includes/header.php` と `public_html/includes/footer.php` を全ページで `require_once __DIR__ . '/includes/*.php'` に統一
  - 対象ページ: `index.php`, `jobs.php`, `job.php`, `features.php`, `feature.php`, `partners.php`, `partner.php`, `faq.php`, `announcements.php`, `announcement.php`
  - 備考: OGP/タイトルは各ページで `$title`/`$description`/`$og_*` を設定してから include する構成に統一
- ステップB: URL規約（拡張子なし）に統一する設定・置換（1PR）✅ 完了　stepb_url_unification_plan.md
- ステップC: 一覧ページのPHP化（`jobs.php` から）→ テンプレ/ループ導入（1PR） ✅ 完了 stepc_list_pages_php_plan.md
- ステップC-2: 不要なJSの削除（ダミーデータ生成処理の除去）（1PR） ✅ 完了
  - `announcement.php` 内の `generateJobs()`, `generateFeatures()`, `sampleTags` 関連処理削除
  - `index.php` 内の `sampleTags`, `getRandomTags()` 処理削除
  - テスト用プレースホルダー画像（`placehold.co`）の参照削除
  - テストデータ（`test1002` 等）の残存をクリーンアップ
- ステップD: 詳細ページのDB化 + OGP/JSON-LD動的化（1～2PR）

## 付記（その他気づき）
- `job.php` は既に拡張子なしパスへ一部移行済み。全体規約をこれに合わせると移行が簡易。
- `config/README.md` と `docs/database_specification.md` の整合性は概ね良好。詳細取得系のSQLは未掲載のため追記が必要。


