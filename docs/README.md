# ドキュメント一覧

このディレクトリには、プロジェクトの開発・運用に関する各種ドキュメントが格納されています。各ドキュメントの役割を以下に記載します。

## プロジェクト管理・要件定義

### requirements.md
プロジェクト全体の要件定義書。CMSとLP間の求人・店舗管理における不具合問題点と解決目標を定義。

## 管理画面関連

### admin_panel_plan.md
管理画面の実装計画書。Basic認証を使用した左サイドメニュー型ダッシュボードの構築計画と詳細なフェーズ分け。

### 管理画面CMS_仕様まとめ_2025-10-28.md
管理画面（CMS）で扱う各機能（求人管理、店舗管理、お知らせ管理など）の設定項目詳細仕様。

### admin_frontend_mapping_audit.md
管理画面のフロントエンド実装監査レポート。

## データベース関連

### database_specification.md
データベース構造の詳細仕様書。各テーブル（jobs, stores, usersなど）のカラム定義、外部キー制約、仕様の詳細説明。

## 実装・移行関連

### implementation_audit.md
実装状況の監査メモ。ヘッダー/フッター共通化、リンク統一、動的化の現状と改善計画。

### contact_migration_2025-11-05.md
お問い合わせページのPHP化とリンク統一の移行作業記録。

### stepb_url_unification_plan.md
URL規約（拡張子なし）統一の実装計画。拡張子削除とディレクトリ形式への移行手順。

### stepc_list_pages_php_plan.md
一覧ページのPHP化実装計画。求人・特集・お知らせ・店舗・FAQページのDB連携化手順。

### stepc2_remove_dummy_js_plan.md
不要なJavaScript処理とテストデータの除去実装計画。ダミーJSとプレースホルダー画像のクリーンアップ。

## 画像・資産関連

### phase6_image_replacement.md
画像置換フェーズの検証レポート。画像リンク切れチェックと置換結果の記録。

### feature_dynamic_spec.md
動的機能仕様書。

## 監査・検証関連

### implementation_status_check_2025-11-05.md
実装状況調査結果レポート。サイト内各機能の実装状況確認と報告された問題点の調査結果。

### for-beginners_implementation_audit_2025-11-03.md
初めての方ページの実装監査レポート。仕様準拠チェックと改善提案。

## 開発・引き継ぎ関連

### architecture_overview.md
システム全体のアーキテクチャ概要。レイヤー構成、データフロー、主要な設計判断、依存関係を説明。

### coding_standards.md
コーディング規約・スタイルガイド。PHP、HTML、CSS、JavaScriptのコーディング規約とベストプラクティス。

## ディレクトリ

### assets/
画像・メディア資産の管理に関するドキュメント。

### migrations/
データベース移行・マイグレーションに関するドキュメント。
