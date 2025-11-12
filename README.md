# 海外リゾキャバ求人.COM

国内外のリゾートバイト、ワーキングホリデーの求人情報を提供するWebサイト。

## 概要

このプロジェクトは、キャバクラ・ラウンジなどの求人情報を管理・表示するためのPHPベースのWebアプリケーションです。
- 求人情報の一覧・詳細表示
- 店舗情報の管理
- 管理画面（CMS）によるコンテンツ管理
- FAQ、お知らせなどのコンテンツページ

## 技術スタック

- **言語**: PHP
- **データベース**: MySQL
- **Webサーバー**: Apache
- **ホスティング**: Xserver

## ディレクトリ構成

```
resocaba-info.com/
├── config/              # データベース接続・CRUD関数など
│   ├── database.php    # DB接続設定
│   ├── crud.php        # CRUD操作関数
│   └── functions.php   # その他ユーティリティ関数
├── docs/               # ドキュメント
├── public_html/        # 公開ディレクトリ
│   ├── admin/         # 管理画面
│   ├── includes/      # 共通インクルードファイル
│   ├── assets/        # 静的ファイル（CSS/JS/画像）
│   ├── index.php      # トップページ
│   ├── jobs.php       # 求人一覧
│   ├── job.php        # 求人詳細
│   └── ...
├── sql/               # SQLファイル
├── log/               # ログファイル
├── mail/              # メール関連
├── htpasswd/          # Basic認証設定
├── script/            # スクリプト類
└── .gitignore         # Git除外設定
```

## セットアップ

### 1. データベース設定

`config/database.php` にデータベース接続情報を設定してください。

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');
```

### 2. ファイル配置

Xserver環境に合わせてファイルを配置します。
- `public_html/` 配下がWebルートになります

### 3. 管理画面アクセス

管理画面は `/admin/` でアクセス可能です（Basic認証設定が必要）。

## 主要機能

### フロントエンド
- 求人情報の一覧・詳細表示
- 店舗情報の表示
- FAQページ
- お知らせページ
- 特徴・サービス紹介ページ

### 管理画面（CMS）
- ダッシュボード
- 求人管理（CRUD）
- 店舗管理（CRUD）
- 応募管理
- その他コンテンツ管理

## ドキュメント

詳細な仕様・実装ドキュメントは `docs/` ディレクトリを参照してください。

- `docs/architecture_overview.md` - システム全体のアーキテクチャ概要
- `docs/coding_standards.md` - コーディング規約・スタイルガイド
- `docs/管理画面CMS_仕様まとめ_2025-10-28.md` - 管理画面の仕様
- `docs/database_specification.md` - データベース仕様
- `docs/admin_panel_plan.md` - 管理画面実装計画
- `config/README.md` - データベース接続・CRUD関数の使い方

## 注意事項

- `config/database.php` には機密情報が含まれているため、`.gitignore` で除外されています
- 本番環境では適切なセキュリティ設定を行ってください
- 管理画面は Basic認証を使用しています（将来、アカウント認証へ移行予定）

## ライセンス

（未設定）

## 更新履歴

- 2025-01-27: データベース接続・CRUD関数の実装
- 2025-10-28: 管理画面（CMS）仕様まとめ作成

