# 管理画面→フロント反映 実装確認レポート

更新日時: 2025-11-03 07:16:44 JST

## 対象範囲
- 管理画面: `/public_html/admin/` 配下（jobs, stores, faqs, notices(announcements), blog(articles), ads, tags）
- フロント: `/public_html/` 配下の各ページ（jobs.php / job.php / partners.php / partner.php / faq.php / announcements.php / announcement.php / features.php / feature.php ほか）
- 共通ヘルパ: `config/functions.php`

## 概要
管理画面の入力項目とフロント表示の対応関係を確認し、以下を整理しました。
- 管理に項目があるがフロントに未反映のもの
- フロントに表示があるが管理に登録箇所が無い/未接続のもの
- 実装上の不整合・改善提案

---

## 結果サマリ（重要ポイント）
- FAQs・お知らせ・特集（記事）・広告バナーは概ね整合（公開フラグや並び順含む）。
- 求人（jobs）と掲載店舗（stores）に未反映／未接続点が多い。
  - job詳細の構造化データで `valid_through` を参照するが、バックエンドで展開していない。
  - jobの「メリット/タグ」セクションは、管理の `qualifications`/`benefits`/`tag_ids` と未接続。
  - jobカードでの `card_message`/`salary_text` は未使用。
  - 画像が無い求人は一覧で非表示（運用注意）。
  - 掲載店舗の詳細ページ（`partner.php`）が静的で、管理 `stores` と未連動。

---

## モジュール別 詳細

### 1) FAQs（よくある質問）
- 管理:
  - 項目: `question`, `answer_html`, `sort_order`, `status`（公開/下書き）
  - 保存/表示: `/admin/faqs/edit.php`, `/admin/faqs/index.php`
- フロント:
  - 取得: `config/functions.php#get_faq_list()`（`status='published'` かつ `sort_order` 昇順）
  - 表示: `/public_html/faq.php` 質問と回答HTMLを表示、JSON-LDも生成
- 判定:
  - 管理にあるが未反映: なし（statusと並び順は利用済み）
  - フロントにあるが管理に無い: なし

### 2) お知らせ（announcements）
- 管理:
  - 項目: `title`, `status`（draft/published/archived）, `body_html`, `published_at`
  - 保存/表示: `/admin/notices/*`
- フロント:
  - 一覧/詳細: `/public_html/announcements.php`, `/public_html/announcement.php`
  - 取得関数: `get_announcement_list`, `get_announcements`, `get_announcement_by_id`, `get_announcement_by_slug`
- 判定:
  - 管理にあるが未反映: なし（公開・日付・本文とも反映）
  - フロントにあるが管理に無い: なし

### 3) 特集・コラム（articles）
- 管理:
  - 項目: `title`, `slug`, `category`, `og_image_url`, `status`, `published_at`, `body_html`
  - 保存/表示: `/admin/blog/*`
- フロント:
  - 一覧/詳細: `/public_html/features.php`, `/public_html/feature.php`
  - 取得関数: `count_articles`, `get_articles`
- 判定:
  - 管理にあるが未反映: なし（OGP含め反映）
  - フロントにあるが管理に無い: なし

### 4) 広告バナー（ad_banners）
- 管理:
  - 項目: `image_url`, `link_url`, `target_blank`, `is_active`, `sort_order`
  - 保存/表示: `/admin/ads/*`
- フロント:
  - 利用箇所: `jobs.php`, `job.php`, `feature.php` などで `get_ad_banners(limit)` を表示
  - 取得関数: `get_ad_banners`（`is_active = 1` のみ、`sort_order` 昇順）
- 判定:
  - 管理にあるが未反映: なし
  - フロントにあるが管理に無い: なし

### 5) タグ（tags）
- 管理:
  - 項目: `name`, `category`, `sort_order`（`/admin/tags/*`）
  - 求人管理（`/admin/jobs/edit.php`）で `tag_ids[]` をメタに保存
- フロント:
  - jobs/job ページでタグを描画する実装は形だけあり（`job.php` のメリット枠で `$job['merits']`/`$job['tags']` を参照）が、バックエンドから付与しておらず表示されない。
- 判定:
  - 管理にあるが未反映: `tag_ids`（メタJSONに保存するが、フロントに投影していない）
  - フロントにあるが管理に無い: なし（管理は存在、連携未実装）

### 6) 掲載店舗（stores）
- 管理:
  - 項目: `name`, `slug`, `category`, `logo_url`, `country`, `region_prefecture`, `address`, `phone_*`, `business_hours_*`, `holiday`, `site_url`, `description_html` ほか
  - 保存/表示: `/admin/stores/*`
- フロント:
  - 一覧: `/public_html/partners.php`（`get_stores` でDB連携、画像は `store_images`）
  - 詳細: `/public_html/partner.php` は静的ダミーで、ID・DBと未連動
- 判定:
  - 管理にあるが未反映: 店舗詳細ページ側の全般（電話・営業時間・休日・説明・サイトURL 等）。一覧には一部（名称/エリア/画像）のみ反映。
  - フロントにあるが管理に無い: なし（ただし詳細ページは静的実装）

### 7) 求人（jobs）
- 管理:
  - 主要項目: `title`, `status`, `store_id`, `description_html`, `message_html`, `work_content_html`, `employment_type`, `salary_min/max`, `salary_unit`, `region_prefecture`,
  - 追加（メタJSON）: `job_code`, `min_term`, `business_hours`, `regular_holiday`, `valid_through`, `qualifications[]`, `benefits{カテゴリ別}`, `home_sections[]`, `tag_ids[]`, `salary_text`, `card_message`, `currency`, `job_type` ほか
- フロント（一覧: `jobs.php`）:
  - `get_jobs()` 結果＋ `job_images` を使用。画像が無い求人は一覧カードをスキップ（非表示）。
  - 表示項目: タイトル、エリア（city/region/countryいずれか）、職種、給与（min+unit）など。
  - 未使用: `card_message`, `salary_text`, `currency`（表示に使っていない）
- フロント（詳細: `job.php`）:
  - 使用: `title`, `store.name`, `region_prefecture/country`, `salary_min/salary_unit`, `employment_type`, `images`, `message_html`, `description_html` 等
  - 構造化データ: `valid_through` を `$job['valid_through']` から参照しているが、`get_job_by_id()` がメタを展開していないため常に空
  - 「この求人のメリット」: `$job['merits']` → 未設定。代替で `$job['tags']` を参照するが、これも未設定
- 判定（jobs）:
  - 管理にあるが未反映:
    - メタ系: `valid_through`, `qualifications[]`, `benefits`, `tag_ids[]`（→メリット/タグ表示未連携）
    - 表示用: `card_message`, `salary_text`, `currency`（現状未使用）
  - フロントにあるが管理に無い:
    - なし（ただし `country` は求人では未入力。ストア由来を利用するなどの連携検討余地あり）
  - 実装上の注意:
    - 画像未登録の求人は `/public_html/jobs.php` でカード生成を `continue` によりスキップ → 一覧に出ない運用リスク

---

## 不整合・改善提案（アクションプラン）
1. `config/functions.php#get_job_by_id` でメタJSONを展開し、必要項目を `$job` に反映
   - 少なくとも: `valid_through`, `qualifications`, `benefits`, `tag_ids`（→ `tags` 名称配列化）
   - 可能なら: `card_message`, `salary_text`, `currency`, `job_type` も返却
2. `job.php` のメリット/タグ描画を実データに接続
   - `qualifications` と `benefits`（カテゴリ配下の配列）をバッジ表示
   - `tag_ids` → `tags` 名称解決（`tags` テーブル）し表示
3. 求人カード（一覧/関連カード）で `salary_text`/`card_message` の表示方針を決定
   - 例: `salary_text` があれば給与ラベルの代替に使用、`card_message` はサブテキストに表示
4. 画像未登録時の求人カード表示
   - 現状は一覧で除外。ダミー画像で表示に切替（運用リスク低減）
5. 掲載店舗詳細ページ（`partner.php`）をDB連携化
   - `?id=` または `/partner/{id}/` から `stores` を取得・表示（電話/営業時間/休日/URL/説明/画像）
   - 同店舗の求人一覧も `store_id` で紐付け表示
6. （任意）求人の `country` の扱い統一
   - 求人に無い場合は関連店舗の `country` を表示用に併用するなどの整合処理

---

## 参考（主な該当ファイル）
- 管理: `public_html/admin/faqs/*`, `admin/notices/*`, `admin/blog/*`, `admin/ads/*`, `admin/tags/*`, `admin/stores/*`, `admin/jobs/*`
- フロント: `public_html/faq.php`, `announcements.php`, `announcement.php`, `features.php`, `feature.php`, `partners.php`, `partner.php`, `jobs.php`, `job.php`
- 関数: `config/functions.php`

---

## 結論
- 記事/FAQ/お知らせ/広告はOK。求人・店舗は表示強化の余地が大きいです。
- 上記アクション（特に `get_job_by_id` メタ展開と `partner.php` DB連携化）を行えば、管理入力→フロント反映のギャップが解消されます。


