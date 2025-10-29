# 画像命名・配置ガイドライン

## 目的
実運用画像の命名・配置・代替テキストを統一し、保守性と再利用性を高めます。UI/レイアウトは変更しません。

## 配置ルール
- ルート: `public_html/assets/images/`
- 文脈別ディレクトリ例:
  - `jobs/` 求人・店舗関連
  - `articles/` 記事・特集
  - `ui/` 装飾・バナー・汎用パターン

## 命名規則
- 形式: `context-keywords[-variant][-size].ext`
- 英小文字・数字・ハイフンのみ、スペース禁止
- 例:
  - `jobs/no-image-1280w.jpg`
  - `articles/feature-default-600w.jpg`
  - `ui/ad-banner-1-640x200.jpg`

## 拡張子選択
- 写真: JPEG（品質 80–90 目安）
- ロゴ/アイコン・フラット: PNG または SVG
- 余白や透過が必要な場合は PNG/SVG を優先

## サイズ・バリアント
- サフィックスで明示: `-640w`, `-1280w`, `-1920w` など
- アスペクト比は現行UIに合わせる（トリミングで崩さない）

## alt テキスト方針
- 意味のある画像: 内容を簡潔に説明（名詞句でOK）
- 装飾目的: `alt=""`（状況により `role="presentation"`）
- バナー: 目的（例: 広告バナー）を簡潔に

## 版管理・キャッシュ
- ファイル名固定 + クエリ `?v=yyyymmdd` でキャッシュ更新、またはファイル名に短いハッシュ/日付を付与
- `.htaccess` で画像種別に長期キャッシュを設定

## 参照記録
- 旧→新の対応は `docs/assets/image-mapping.csv` に記録
- 未使用になった画像は `docs/assets/deleted-placeholders.csv` に記録
