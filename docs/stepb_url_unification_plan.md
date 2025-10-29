# ステップB: URL規約（拡張子なし）統一 実装計画

最終更新: 2025-10-23 17:57 JST

## 概要
URL規約を拡張子なし・ディレクトリ形式に統一する。`.html`拡張子を削除し、末尾スラッシュ形式に変更。`/resocaba/`プレフィックスを廃止しルート直下に統一。
実装フェーズの順に実装し、**実装が完了したフェーズにはマーク✅を付ける**。


## 対象ファイルと修正内容

### URLマッピングテーブル

#### ナビゲーションリンク変換
| 旧URL | 新URL | 説明 |
|-------|--------|------|
| `/for-beginners.html` | `/for-beginners/` | 初めての方ページ |
| `/job-list.html` | `/jobs/` | 求人一覧ページ |
| `/partners.html` | `/partners/` | 掲載店舗一覧ページ |
| `/announcements.html` | `/announcements/` | お知らせ一覧ページ |
| `/features.html` | `/features/` | 特集・コラム一覧ページ |
| `/faq.html` | `/faq/` | よくある質問ページ |
| `/contact-ad.html` | `/contact-ad/` | 広告掲載ページ |
| `/login.html` | `/login/` | ログインページ |
| `/register.html` | `/register/` | 登録ページ |

#### 詳細ページリンク変換
| パターン | 例 | 説明 |
|----------|-----|------|
| `/partner/{id}.html` | `/partner/123/` | 店舗詳細ページ |
| `/job/{id}.html` | `/job/456/` | 求人詳細ページ |
| `/feature/{id}.html` | `/feature/789/` | 特集詳細ページ |
| `/announcement/{id}.html` | `/announcement/101/` | お知らせ詳細ページ |

#### /resocaba/プレフィックス廃止
| 旧URL | 新URL | 説明 |
|-------|--------|------|
| `/resocaba/` | `/` | トップページ |
| `/resocaba/for-beginners.html` | `/for-beginners/` | 初めての方ページ |
| `/resocaba/job-list.html` | `/jobs/` | 求人一覧ページ |
| `/resocaba/partners.html` | `/partners/` | 掲載店舗一覧ページ |
| `/resocaba/announcements.html` | `/announcements/` | お知らせ一覧ページ |
| `/resocaba/features.html` | `/features/` | 特集・コラム一覧ページ |
| `/resocaba/faq.html` | `/faq/` | よくある質問ページ |

## 実装フェーズ

### フェーズ1: 基盤設定（.htaccess）✅ 完了
**対象ファイル**: `public_html/.htaccess`

1. **リダイレクト規則追加**
   - `.html`拡張子付きURLを拡張子なし・末尾スラッシュ形式に301リダイレクト
   - `/resocaba/`プレフィックス付きURLをルート直下に301リダイレクト
   - クエリパラメータを保持したままリダイレクト

2. **実装内容**
   ```apache
   # .html から ディレクトリ形式へのリダイレクト
   RewriteRule ^(.+)\.html$ /$1/ [R=301,L]

   # /resocaba/ プレフィックスからのリダイレクト
   RewriteRule ^resocaba/(.*)$ /$1 [R=301,L]
   ```

3. **バックアップ**
   - 既存.htaccessのバックアップ取得

### フェーズ2: メインページ修正（index.php）✅ 完了
**対象ファイル**: `public_html/index.php`

1. **修正箇所**
   - ナビゲーションリンク（ヘッダー）
   - 「もっと見る」ボタンリンク（7箇所）
   - サイドバーリンク（エリア別、期間別、職種別、タグ別）

2. **具体的な変更**
   - `/job-list.html` → `/jobs/`
   - `/for-beginners.html` → `/for-beginners/`
   - `/partners.html` → `/partners/`
   - `/announcements.html` → `/announcements/`
   - `/features.html` → `/features/`
   - `/faq.html` → `/faq/`

3. **クエリパラメータ対応**
   - `?section=pickup` → `?section=pickup`
   - `?area=東京` → `?area=東京`
   - など、既存クエリパラメータは保持

### フェーズ3: 求人関連ページ修正（jobs.php, job.php）✅ 完了
**対象ファイル**: `public_html/jobs.php`, `public_html/job.php`

1. **jobs.phpの修正箇所**
   - ナビゲーションリンク（全項目）
   - ページネーションリンク
   - 詳細ページへのリンク
   - サイドバーリンク

2. **job.phpの修正箇所**
   - ナビゲーションリンク
   - パンくずリスト
   - 関連リンク

3. **/resocaba/プレフィックス除去**
   - `/resocaba/` → `/`
   - `/resocaba/for-beginners.html` → `/for-beginners/`
   - `/resocaba/job-list.html` → `/jobs/`
   - など

### フェーズ4: 特集関連ページ修正（features.php, feature.php）✅ 完了
**対象ファイル**: `public_html/features.php`, `public_html/feature.php`

1. **features.phpの修正箇所**
   - ナビゲーションリンク
   - 特集カードの詳細リンク
   - ページネーションリンク

2. **feature.phpの修正箇所**
   - ナビゲーションリンク
   - パンくずリスト
   - 関連特集リンク

3. **リンク変換**
   - `/features.html` → `/features/`
   - `/feature/{id}.html` → `/feature/{id}/`

### フェーズ5: 店舗関連ページ修正（partners.php, partner.php）✅ 完了
**対象ファイル**: `public_html/partners.php`, `public_html/partner.php`

1. **partners.phpの修正箇所**
   - ナビゲーションリンク
   - 店舗カードの詳細リンク
   - ページネーションリンク

2. **partner.phpの修正箇所**
   - ナビゲーションリンク
   - パンくずリスト
   - 関連店舗リンク

3. **リンク変換**
   - `/partners.html` → `/partners/`
   - `/partner/{id}.html` → `/partner/{id}/`

### フェーズ6: お知らせ関連ページ修正（announcements.php, announcement.php）✅ 完了
**対象ファイル**: `public_html/announcements.php`, `public_html/announcement.php`

1. **announcements.phpの修正箇所**
   - ナビゲーションリンク
   - お知らせカードの詳細リンク
   - ページネーションリンク

2. **announcement.phpの修正箇所**
   - ナビゲーションリンク
   - パンくずリスト
   - 関連お知らせリンク

3. **リンク変換**
   - `/announcements.html` → `/announcements/`
   - `/announcement/{id}.html` → `/announcement/{id}/`

### フェーズ7: その他ページ修正（faq.php）✅ 完了
**対象ファイル**: `public_html/faq.php`

1. **修正箇所**
   - ナビゲーションリンク（全項目）
   - 内部リンク（該当する場合）

2. **/resocaba/プレフィックス除去**
   - `/resocaba/` → `/`
   - `/resocaba/for-beginners.html` → `/for-beginners/`
   - `/resocaba/job-list.html` → `/jobs/`
   - など

## 検証フェーズ

### 動作確認項目
1. **リダイレクト動作確認**
   - `.html`リンクからの301リダイレクト
   - `/resocaba/`プレフィックスからの301リダイレクト
   - クエリパラメータ保持確認

2. **リンク整合性確認**
   - すべてのナビゲーションリンクが機能
   - 詳細ページリンクが正常動作
   - ページネーションリンクが機能
   - パンくずリストが正しいURLを指す

3. **SEO影響確認**
   - 301リダイレクトが正しく設定されている
   - サイトマップ更新が必要な場合の特定

### テスト手順
1. 各ページの主要リンクをクリックして動作確認
2. ブラウザの開発者ツールでリダイレクト先確認
3. 外部ツール（Google Search Console等）でのインデックス状況確認

## リスク対応

### バックアップ戦略
- 各ファイルの修正前にGitコミットまたはバックアップ
- .htaccess変更前の完全バックアップ

### ロールバック計画
- 問題発生時は.htaccessのリダイレクト規則をコメントアウト
- 各ファイルのリンクを段階的に元に戻す

### SEO影響最小化
- 301リダイレクトを使用（302ではなく）
- サイトマップ更新（別途実施）
- 検索エンジンへの変更通知

## 完了基準
- [x] すべての.htmlリンクが拡張子なし形式に変換
- [x] すべての/resocaba/プレフィックスが除去
- [x] .htaccessのリダイレクト規則が動作
- [x] すべてのページ間リンクが正常動作
- [x] 301リダイレクトが正しく設定されている

## 実装順序推奨
1. .htaccess（基盤設定）
2. index.php（メインページ）
3. jobs.php, job.php（求人関連）
4. features.php, feature.php（特集関連）
5. partners.php, partner.php（店舗関連）
6. announcements.php, announcement.php（お知らせ関連）
7. faq.php（その他ページ）
8. 動作確認・調整

---

**注意**: URL変更はSEOに大きな影響を与えるため、各フェーズ完了ごとに十分なテストを実施してください。問題発生時は即座にロールバックできる体制を整えてください。
