# データベース仕様書

## テーブル一覧

### ad_banners
- id
  - 主キー、自動増分、UNSIGNED INT(10)
- image_url
  - 画像URL、VARCHAR(1024)、NOT NULL
- link_url
  - リンク先URL、VARCHAR(1024)、NOT NULL、デフォルト値: ''
- target_blank
  - 新しいタブで開くかどうか、TINYINT(1)、NOT NULL、デフォルト値: 1
- is_active
  - アクティブ状態、TINYINT(1)、NOT NULL、デフォルト値: 1
- sort_order
  - 表示順序、INT(11)、NOT NULL、デフォルト値: 0
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### announcements
- id
  - 主キー、自動増分、INT(11)
- title
  - タイトル、VARCHAR(255)、NOT NULL
- slug
  - URL用スラッグ、VARCHAR(191)、UNIQUE
- body_html
  - HTML本文、MEDIUMTEXT
- status
  - ステータス、ENUM('draft','published','archived')、デフォルト値: 'draft'
- published_at
  - 公開日時、DATETIME
- author_user_id
  - 作成者ID、INT(11)、外部キー（users.id）
- deleted_at
  - 削除日時、DATETIME（ソフトデリート用）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### applications
- id
  - 主キー、自動増分、INT(11)
- job_id
  - 求人ID、INT(11)、NOT NULL、外部キー（jobs.id）
- user_id
  - ユーザーID、INT(11)、外部キー（users.id）
- name
  - 応募者名、VARCHAR(100)、NOT NULL
- email
  - メールアドレス、VARCHAR(191)、NOT NULL
- message
  - メッセージ、TEXT
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### articles
- id
  - 主キー、自動増分、INT(11)
- title
  - タイトル、VARCHAR(255)、NOT NULL
- slug
  - URL用スラッグ、VARCHAR(191)、UNIQUE
- body_html
  - HTML本文、MEDIUMTEXT
- category
  - カテゴリ、VARCHAR(100)
- og_image_url
  - OGP画像URL、VARCHAR(512)
- status
  - ステータス、ENUM('draft','published','archived')、デフォルト値: 'draft'
- published_at
  - 公開日時、DATETIME
- author_user_id
  - 作成者ID、INT(11)、外部キー（users.id）
- deleted_at
  - 削除日時、DATETIME（ソフトデリート用）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### assets
- id
  - 主キー、自動増分、INT(11)
- file_name
  - ファイル名、VARCHAR(255)、NOT NULL
- file_path
  - ファイルパス、VARCHAR(512)、NOT NULL
- mime
  - MIMEタイプ、VARCHAR(100)、NOT NULL
- size
  - ファイルサイズ、INT(11)、NOT NULL
- width
  - 画像幅、INT(11)
- height
  - 画像高さ、INT(11)
- created_by
  - 作成者ID、INT(11)、外部キー（users.id）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### audit_logs
- id
  - 主キー、自動増分、INT(11)
- user_id
  - ユーザーID、INT(11)、外部キー（users.id）
- action
  - アクション、VARCHAR(64)、NOT NULL
- entity_type
  - エンティティタイプ、VARCHAR(64)、NOT NULL
- entity_id
  - エンティティID、INT(11)
- before_json
  - 変更前データ、MEDIUMTEXT（JSON形式）
- after_json
  - 変更後データ、MEDIUMTEXT（JSON形式）
- ip
  - IPアドレス、VARCHAR(64)
- ua
  - ユーザーエージェント、VARCHAR(255)
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### faqs
- id
  - 主キー、自動増分、INT(11)
- question
  - 質問、VARCHAR(255)、NOT NULL
- answer_html
  - 回答（HTML）、MEDIUMTEXT
- sort_order
  - 表示順序、INT(11)、NOT NULL、デフォルト値: 0
- status
  - ステータス、ENUM('draft','published')、デフォルト値: 'published'
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### favorites
- id
  - 主キー、自動増分、INT(11)
- user_id
  - ユーザーID、INT(11)、NOT NULL、外部キー（users.id）
- job_id
  - 求人ID、INT(11)、NOT NULL、外部キー（jobs.id）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### jobs
- id
  - 主キー、自動増分、INT(11)
- title
  - 求人タイトル、VARCHAR(255)、NOT NULL
- slug
  - URL用スラッグ、VARCHAR(191)、UNIQUE
- description_html
  - 説明（HTML）、MEDIUMTEXT
- description_text
  - 説明（テキスト）、MEDIUMTEXT
- message_text
  - メッセージ（テキスト）、MEDIUMTEXT
- message_html
  - メッセージ（HTML）、MEDIUMTEXT
- work_content_html
  - 仕事内容（HTML）、MEDIUMTEXT
- store_id
  - 店舗ID、INT(11)、外部キー（stores.id）
- country
  - 国、VARCHAR(64)
- region_prefecture
  - 都道府県・地域、VARCHAR(64)
- city
  - 都市、VARCHAR(64)
- employment_type
  - 雇用形態、VARCHAR(32)
- category
  - カテゴリ、ENUM('domestic','overseas')
- salary_min
  - 最低給与、INT(11)
- salary_max
  - 最高給与、INT(11)
- salary_unit
  - 給与単位、ENUM('HOUR','DAY','MONTH')、デフォルト値: 'HOUR'
- benefits_json
  - 福利厚生、TEXT（JSON形式）
- status
  - ステータス、ENUM('draft','published','archived')、デフォルト値: 'draft'
- published_at
  - 公開日時、DATETIME
- author_user_id
  - 作成者ID、INT(11)、外部キー（users.id）
- is_pinned
  - ピン留め、TINYINT(1)、NOT NULL、デフォルト値: 0
- meta_json
  - メタデータ、TEXT（JSON形式）
- deleted_at
  - 削除日時、DATETIME（ソフトデリート用）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### job_images
- id
  - 主キー、自動増分、INT(11)
- job_id
  - 求人ID、INT(11)、NOT NULL、外部キー（jobs.id）
- image_url
  - 画像URL、VARCHAR(512)、NOT NULL
- sort_order
  - 表示順序、INT(11)、NOT NULL、デフォルト値: 0
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### job_tag
- job_id
  - 求人ID、INT(11)、NOT NULL、外部キー（jobs.id）
- tag_id
  - タグID、INT(11)、NOT NULL、外部キー（tags.id）

### stores
- id
  - 主キー、自動増分、INT(11)
- name
  - 店舗名、VARCHAR(255)、NOT NULL
- slug
  - URL用スラッグ、VARCHAR(191)、UNIQUE
- logo_url
  - ロゴURL、VARCHAR(512)
- description_html
  - 説明（HTML）、MEDIUMTEXT
- country
  - 国、VARCHAR(64)
- region_prefecture
  - 都道府県・地域、VARCHAR(64)
- area_tag_id
  - エリアタグID、INT(11)、外部キー（tags.id）
- address
  - 住所、VARCHAR(255)
- phone
  - 電話番号、VARCHAR(50)
- phone_domestic
  - 国内電話番号、VARCHAR(32)
- phone_international
  - 国際電話番号、VARCHAR(32)
- business_hours
  - 営業時間、VARCHAR(255)
- holiday
  - 休日、VARCHAR(255)
- site_url
  - サイトURL、VARCHAR(255)
- deleted_at
  - 削除日時、DATETIME（ソフトデリート用）
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

### store_images
- id
  - 主キー、自動増分、INT(11)
- store_id
  - 店舗ID、INT(11)、NOT NULL、外部キー（stores.id）
- image_url
  - 画像URL、VARCHAR(512)、NOT NULL
- sort_order
  - 表示順序、INT(11)、NOT NULL、デフォルト値: 0
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### tags
- id
  - 主キー、自動増分、INT(11)
- name
  - タグ名、VARCHAR(100)、NOT NULL
- slug
  - URL用スラッグ、VARCHAR(191)、UNIQUE
- type
  - タグタイプ、ENUM('job_feature','area','custom')、NOT NULL、デフォルト値: 'job_feature'
- sort_order
  - 表示順序、INT(11)、NOT NULL、デフォルト値: 0
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()

### users
- id
  - 主キー、自動増分、INT(11)
- name
  - ユーザー名、VARCHAR(100)、NOT NULL
- email
  - メールアドレス、VARCHAR(191)、NOT NULL、UNIQUE
- password_hash
  - パスワードハッシュ、VARCHAR(255)、NOT NULL
- role
  - ロール、ENUM('admin','editor','author','viewer')、NOT NULL、デフォルト値: 'author'
- last_login_at
  - 最終ログイン日時、DATETIME
- created_at
  - 作成日時、DATETIME、NOT NULL、デフォルト値: current_timestamp()
- updated_at
  - 更新日時、DATETIME、NOT NULL、デフォルト値: current_timestamp() ON UPDATE current_timestamp()

## 仕様がわからないorあってるか怪しいやつ

### 外部キー制約の詳細
- 一部の外部キー制約の詳細な動作（CASCADE、SET NULL等）は確認済みですが、実際の運用での動作は要検証

### JSON形式のカラム
- benefits_json（jobsテーブル）
  - 福利厚生の詳細な構造は要確認
- meta_json（jobsテーブル）
  - メタデータの詳細な構造は要確認
- before_json、after_json（audit_logsテーブル）
  - ログデータの詳細な構造は要確認

### インデックスの詳細
- 各テーブルのインデックスは設定済みですが、パフォーマンスチューニングの観点での最適性は要検証

### 文字セット・照合順序
- 全体的にutf8mb4を使用していますが、一部テーブルで照合順序が異なる（utf8mb4_unicode_ci vs utf8mb4_general_ci）


### 詳細とサンプルデータ
/home/xs724055/resocaba-info.com/sql/xs724055_caba.sql