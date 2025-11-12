# アーキテクチャ概要

**作成日**: 2025-11-12  
**対象**: 海外リゾキャバ求人.COM プロジェクト

## 概要

このドキュメントは、プロジェクトのシステム全体の構造、データフロー、主要な設計判断について説明します。

---

## システム全体の構造

### レイヤー構成

```
┌─────────────────────────────────────┐
│  フロントエンド（公開ページ）        │
│  - index.php（トップページ）        │
│  - jobs.php（求人一覧）             │
│  - job.php（求人詳細）              │
│  - partners.php（店舗一覧）        │
│  - features.php（特集一覧）         │
│  - announcements.php（お知らせ）   │
│  - faq.php（FAQ）                  │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  共通コンポーネント層               │
│  - includes/header.php              │
│  - includes/footer.php              │
│  - includes/menu.php                │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  ビジネスロジック層                 │
│  - config/functions.php             │
│    （求人・店舗・お知らせ取得など） │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  データアクセス層                   │
│  - config/crud.php                 │
│    （CRUD操作関数）                 │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  データベース接続層                 │
│  - config/database.php              │
│    （PDO接続管理）                  │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│  データベース（MySQL）              │
│  - jobs, stores, tags,              │
│    announcements, articles, etc.     │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│  管理画面（CMS）                    │
│  - admin/index.php（ダッシュボード）│
│  - admin/jobs/（求人管理）          │
│  - admin/stores/（店舗管理）        │
│  - admin/applications/（応募管理）  │
│  - admin/inc/layout.php（共通レイア）│
└─────────────────────────────────────┘
```

---

## ディレクトリ構造の詳細

### プロジェクトルート

```
resocaba-info.com/
├── config/              # 設定・共通関数
│   ├── database.php    # DB接続設定（PDO）
│   ├── crud.php        # CRUD操作関数
│   └── functions.php   # ビジネスロジック関数
│
├── public_html/         # 公開ディレクトリ（Webルート）
│   ├── admin/          # 管理画面
│   │   ├── inc/        # 管理画面共通ファイル
│   │   │   └── layout.php  # 共通レイアウト
│   │   ├── jobs/       # 求人管理
│   │   ├── stores/     # 店舗管理
│   │   ├── applications/ # 応募管理
│   │   └── ...
│   │
│   ├── includes/        # フロントエンド共通ファイル
│   │   ├── header.php  # ヘッダー（メタタグ、OGP）
│   │   ├── footer.php  # フッター
│   │   └── menu.php    # ナビゲーションメニュー
│   │
│   ├── assets/         # 静的ファイル
│   │   ├── css/        # CSSファイル
│   │   ├── js/         # JavaScriptファイル
│   │   └── images/     # 画像ファイル
│   │
│   ├── index.php       # トップページ
│   ├── jobs.php        # 求人一覧ページ
│   ├── job.php         # 求人詳細ページ
│   ├── partners.php    # 店舗一覧ページ
│   ├── partner.php     # 店舗詳細ページ
│   ├── features.php    # 特集一覧ページ
│   ├── feature.php     # 特集詳細ページ
│   ├── announcements.php # お知らせ一覧ページ
│   ├── announcement.php # お知らせ詳細ページ
│   ├── faq.php         # FAQページ
│   ├── contact.php     # お問い合わせページ
│   └── .htaccess       # URLルーティング設定
│
├── docs/               # ドキュメント
├── sql/                # SQLファイル（マイグレーション）
├── log/                # ログファイル
├── mail/               # メール関連
└── htpasswd/           # Basic認証設定
```

---

## データフロー

### 1. フロントエンドページの表示フロー

```
ユーザーリクエスト
    ↓
.htaccess（URLルーティング）
    ↓
PHPファイル（例: jobs.php）
    ↓
require_once config/functions.php
    ↓
get_jobs() などの関数呼び出し
    ↓
config/crud.php の selectRecords() など
    ↓
config/database.php の getDatabaseConnection()
    ↓
PDO経由でMySQLにクエリ実行
    ↓
データ取得・整形
    ↓
includes/header.php でHTMLヘッダー出力
    ↓
PHPでデータをループしてHTML生成
    ↓
includes/footer.php でHTMLフッター出力
    ↓
レスポンス返却
```

### 2. 管理画面のデータ更新フロー

```
管理画面フォーム送信
    ↓
admin/jobs/edit.php など
    ↓
POSTデータの検証・サニタイズ
    ↓
config/crud.php の updateRecord() または insertRecord()
    ↓
config/database.php の getDatabaseConnection()
    ↓
PDO経由でMySQLにUPDATE/INSERT実行
    ↓
トランザクション管理（必要に応じて）
    ↓
リダイレクトまたは成功メッセージ表示
```

---

## 主要な設計判断とその理由

### 1. レイヤー分離アーキテクチャ

**設計**: データベース接続 → CRUD操作 → ビジネスロジック → プレゼンテーション層の4層構造

**理由**:
- 各層の責務を明確化し、保守性を向上
- テスト容易性の向上
- 将来的な変更への柔軟性

### 2. 共通コンポーネントの分離

**設計**: `includes/header.php`, `includes/footer.php`, `includes/menu.php` で共通部分を分離

**理由**:
- コードの重複を排除
- 一箇所の修正で全ページに反映
- メンテナンス性の向上

### 3. 関数ベースのビジネスロジック

**設計**: `config/functions.php` にビジネスロジック関数を集約

**理由**:
- オブジェクト指向よりもシンプルで理解しやすい
- PHPの特性に合致
- 小規模プロジェクトに適した設計

### 4. 一括取得によるパフォーマンス最適化

**設計**: `get_job_list_with_images()` などで画像やタグを一括取得

**理由**:
- N+1問題の回避
- データベースクエリ数の削減
- ページ表示速度の向上

### 5. URLルーティングの統一

**設計**: `.htaccess` で拡張子なし・ディレクトリ形式のURLに統一

**理由**:
- SEO対策（URLの統一）
- ユーザビリティの向上
- 旧URLからのリダイレクト対応

### 6. Basic認証による管理画面保護

**設計**: Apache Basic認証で管理画面を保護

**理由**:
- シンプルで確実な認証
- 追加の認証システム不要
- 将来的にアカウント認証へ移行可能な設計

---

## 依存関係

### 外部ライブラリ・フレームワーク

- **Tailwind CSS**: CDN経由で読み込み（フロントエンドスタイリング）
- **Bootstrap 5**: 管理画面のスタイリング
- **Swiper**: スライダー機能
- **Lucide Icons**: アイコン表示

### PHP拡張機能

- **PDO**: データベース接続
- **PDO_MySQL**: MySQL接続ドライバ
- **JSON**: JSONデータの処理
- **mbstring**: マルチバイト文字列処理

### Apacheモジュール

- **mod_rewrite**: URLルーティング
- **mod_auth_basic**: Basic認証
- **mod_headers**: HTTPヘッダー制御
- **mod_expires**: キャッシュ制御

---

## データベース設計の特徴

### 正規化と非正規化のバランス

- **正規化**: 基本的なテーブル構造は正規化されている
- **非正規化**: `meta_json` カラムで柔軟なメタデータを保存
- **理由**: 将来の拡張性とパフォーマンスのバランス

### 画像管理

- **job_images**, **store_images** テーブルで画像を別管理
- `sort_order` で表示順を制御
- 一つのエンティティに複数画像を紐付け可能

### タグシステム

- **tags** テーブルでタグマスタを管理
- **job_tag** テーブルで求人とタグの多対多関係を管理
- `meta_json` にも `tag_ids` 配列を保存（高速化のため）

---

## セキュリティ設計

### 1. SQLインジェクション対策

- **プリペアドステートメント**: すべてのSQLクエリでPDOのプリペアドステートメントを使用
- **パラメータバインディング**: ユーザー入力は必ずパラメータとしてバインド

### 2. XSS対策

- **htmlspecialchars()**: すべての出力でエスケープ処理
- **ENT_QUOTES**: シングルクォート・ダブルクォートの両方をエスケープ

### 3. CSRF対策

- 管理画面では将来的にCSRFトークンを実装予定
- 現状はBasic認証による保護

### 4. 認証・認可

- **Basic認証**: 管理画面へのアクセス制御
- **robots.txt**: 管理画面を検索エンジンから除外
- **X-Robots-Tag**: HTTPヘッダーでnoindex設定

---

## パフォーマンス最適化

### 1. データベースクエリ最適化

- **一括取得**: 関連データをJOINやIN句で一括取得
- **インデックス**: 主要な検索条件にインデックスを設定
- **LIMIT句**: ページネーションで取得件数を制限

### 2. キャッシュ戦略

- **画像キャッシュ**: `.htaccess` で画像の長期キャッシュ設定
- **Expiresヘッダー**: 静的ファイルのキャッシュ期間設定

### 3. フロントエンド最適化

- **CDN利用**: Tailwind CSS、Bootstrap、SwiperをCDNから読み込み
- **遅延読み込み**: 必要に応じてJavaScriptの遅延読み込み

---

## 今後の拡張性

### 1. 認証システムの移行

- Basic認証からアカウント認証への移行を想定
- `admin/inc/auth.php` で認証ロジックを分離可能な設計

### 2. API化

- 将来的にRESTful APIを追加する場合、`admin/api/` ディレクトリを活用
- 既存の `config/functions.php` の関数をAPIからも呼び出し可能

### 3. キャッシュ層の追加

- 必要に応じてRedisやMemcachedなどのキャッシュ層を追加可能
- `config/functions.php` の関数にキャッシュロジックを追加

---

## 関連ドキュメント

- `docs/database_specification.md` - データベース構造の詳細仕様
- `docs/admin_panel_plan.md` - 管理画面の実装計画
- `config/README.md` - データベース接続・CRUD関数の使い方
- `docs/implementation_audit.md` - 実装状況の監査メモ

