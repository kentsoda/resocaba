# 実装状況調査結果

調査日: 2025-11-05
最終更新: 2025-11-06

## 概要

サイト内の各機能の実装状況を確認し、報告された問題点を調査しました。

---

## 1. フッター：運営者が無い

### 状況
- **ファイル**: `public_html/includes/footer.php`
- **該当行**: 22行目
- **現状**: 「運営者：海外リゾキャバ求人.COM運営」というテキストがコード内に存在

### 問題点
- コード上では存在しているが、実際に表示されていない可能性がある
- または、より具体的な運営者情報が必要な可能性がある

### 確認事項
```22:22:public_html/includes/footer.php
                <p class="text-xs text-slate-400 mt-2">運営者：海外リゾキャバ求人.COM運営</p>
```

### 対応方針
- ブラウザでの実際の表示確認が必要
- CSSで非表示になっていないか確認
- 必要に応じて、より詳細な運営者情報の追加を検討

---

## 2. フッター：規約・サポート情報が全部404

### 状況
- **ファイル**: `public_html/includes/footer.php`
- **該当行**: 38-43行目
- **リンク先**:
  - `/contact/` → `contact.php`（存在確認済み）
  - `/terms/` → `terms.php`（存在確認済み）
  - `/privacy/` → `privacy.php`（存在確認済み）

### 問題点
- `terms.php`と`privacy.php`は存在するが、`.htaccess`にURLルーティング設定がない
- `.htaccess`には`/terms/`や`/privacy/`へのルーティングルールが存在しない

### 確認事項
```38:43:public_html/includes/footer.php
                <div>
                    <h4 class="font-semibold text-sm text-white mb-4 tracking-wider">規約・サポート情報</h4>
                    <ul class="space-y-3 text-xs">
                        <li><a href="/contact/" class="hover:text-white transition-colors">お問い合わせ</a></li>
                        <li><a href="/terms/" class="hover:text-white transition-colors">利用規約</a></li>
                        <li><a href="/privacy/" class="hover:text-white transition-colors">プライバシーポリシー</a></li>
                    </ul>
                </div>
```

### 対応方針
- `.htaccess`に`/terms/` → `terms.php`、`/privacy/` → `privacy.php`へのルーティングルールを追加する必要がある

---

## 3. 求人カード：詳細の高さが無制限になってる

### 状況
- **ファイル**: `public_html/jobs.php`
- **該当行**: 324行目
- **問題**: `description-truncate`クラスが適用されているが、コンテンツが空

### 問題点
- 324行目の`<p>`タグが空で、`description_text`が出力されていない
- CSSの`description-truncate`クラスは3行に制限する設定になっているが、空のため効果なし

### 確認事項
```121:127:public_html/jobs.php
        .description-truncate {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
```

```324:324:public_html/jobs.php
                                    <p class="text-xs text-slate-500 flex-grow description-truncate"></p>
```

### 対応方針
- 求人データから`description_text`を取得して出力する必要がある
- `index.php`では正しく実装されている（547行目など）ので、同様の実装を`jobs.php`に追加

---

## 4. 求人検索：並び替えが機能してない

### 状況
- **ファイル**: `public_html/jobs.php`
- **該当行**: 245-249行目
- **問題**: `select`要素に`id="sort"`があるが、イベントリスナーが設定されていない

### 問題点
- 並び替えの`select`要素は存在するが、JavaScriptでイベントリスナーが設定されていない
- 選択肢の値も設定されていない（「新着順」「給与の高い順」「人気順」のみ）

### 確認事項
```245:249:public_html/jobs.php
                                <select id="sort" class="border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                    <option>新着順</option>
                                    <option>給与の高い順</option>
                                    <option>人気順</option>
                                </select>
```

### 対応方針
- `select`要素に`value`属性を追加
- JavaScriptで`change`イベントリスナーを追加し、URLパラメータを更新してリロードする処理を実装

---

## 5. 求人検索：ページ数切替下部の「条件を変更して再検索する」が404

### 状況
- **ファイル**: `public_html/jobs.php`
- **該当行**: 375行目、394行目
- **リンク先**: `archive-job/`

### 問題点
- `archive-job/`というパスへのリンクがあるが、該当ファイルが存在しない
- 検索条件変更機能は`filters-accordion`で実装されているため、リンク先を修正する必要がある

### 確認事項
```375:377:public_html/jobs.php
                        <a href="archive-job/" class="inline-flex items-center justify-center gap-x-2 w-full sm:w-auto px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">
                            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                            <span>条件を変更して再検索する</span>
                        </a>
```

### 対応方針
- `href="archive-job/"`を削除し、JavaScriptで`filters-accordion`を開く処理に変更
- または、`#`に変更してJavaScriptで処理する

---

## 6. 同じエリアの求人/ピックアップ求人：「もっと見る」が404

### 状況
- **ファイル**: `public_html/jobs.php`
- **該当行**: 520行目、596行目
- **リンク先**: 
  - `/jobs/pickup/`
  - `/jobs/new/`

### 問題点
- `/jobs/pickup/`や`/jobs/new/`というパスへのリンクがあるが、該当ファイルが存在しない
- `index.php`では`/jobs/?section=pickup`のようなクエリパラメータ形式を使用している

### 確認事項
```520:520:public_html/jobs.php
                            <a href="/jobs/pickup/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
```

```596:596:public_html/jobs.php
                            <a href="/jobs/new/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
```

### 対応方針
- `/jobs/pickup/` → `/jobs/?section=pickup`に変更
- `/jobs/new/` → `/jobs/?section=new`に変更
- または、`jobs.php`でこれらのセクションに対応する処理を追加

---

## 7. 掲載店舗：登録している業種が検索条件に出てこない

### 状況
- **ファイル**: `public_html/partners.php`
- **該当行**: 183-185行目
- **問題**: 業種フィルターのUIは存在するが、データベース検索に反映されていない

### 問題点
- 業種フィルターのUIはJavaScriptで実装されているが、実際のデータベース検索には`category`フィルターが実装されていない
- `get_stores()`関数（`config/functions.php`）には業種（`category`）フィルターの処理がない
- `partners.php`でも`$filters`に`type`や`category`が含まれていない

### 確認事項
```183:185:public_html/partners.php
                                <div id="type-filters" class="flex flex-wrap gap-2">
                                    <!-- Type filter buttons will be populated by JS -->
                                </div>
```

```560:609:config/functions.php
function get_stores($filters = [], $offset = 0, $limit = 20) {
    $sql = "SELECT * FROM stores WHERE deleted_at IS NULL";
    $conditions = [];
    $params = [];

    // エリア絞り込み（country / region_prefecture のいずれか一致）
    if (isset($filters['area']) && $filters['area'] !== '' && $filters['area'] !== 'all') {
        $conditions[] = "(country = ? OR region_prefecture = ?)";
        $params[] = $filters['area'];
        $params[] = $filters['area'];
    }

    if (!empty($conditions)) {
        $sql .= ' AND ' . implode(' AND ', $conditions);
    }

    $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;
    // ... 以下省略
}
```

### 対応方針
- `get_stores()`関数と`count_stores()`関数に`category`フィルターの処理を追加
- `partners.php`で`$_GET['type']`または`$_GET['category']`を取得して`$filters`に追加
- データベースの`stores`テーブルに`category`カラムが存在することを確認

---

## 8. 掲載店舗：掲載店舗一覧から店舗ページ飛ぶと全部404

### 状況
- **ファイル**: `public_html/partners.php`
- **該当行**: 231行目、235行目
- **リンク先**: `/partner/{id}/`
- **ルーティング**: `.htaccess`に設定あり

### 問題点
- `.htaccess`には`/partner/{id}/` → `partner.php?id={id}`のルーティングが設定されている
- `partner.php`ファイルも存在する
- 404になる原因は、URLパラメータの取得方法やデータベースクエリの問題の可能性がある

### 確認事項
```28:29:public_html/.htaccess
# Partner detail routing: /partner/{id}/ -> partner.php?id={id}
RewriteRule ^partner/([0-9]+)/?$ partner.php?id=$1 [L,QSA]
```

```231:231:public_html/partners.php
                                    <h3 class="font-bold text-lg mb-1 leading-tight"><a href="/partner/<?php echo $storeId; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo h($name); ?></a></h3>
```

### 対応方針
- `partner.php`のURLパラメータ取得処理を確認
- データベースクエリが正しく動作しているか確認
- 404エラーの詳細を確認（サーバーログなど）

---

## 9. お知らせ：詳しく見る→の先が全部404

### 状況
- **ファイル**: `public_html/announcements.php`
- **該当行**: 126行目、138行目
- **リンク先**: `/announcement/{id}/`
- **ルーティング**: `.htaccess`に設定あり

### 問題点
- `.htaccess`には`/announcement/{id}/` → `announcement.php?id={id}`のルーティングが設定されている
- `announcement.php`ファイルも存在する
- 404になる原因は、URLパラメータの取得方法やデータベースクエリの問題の可能性がある

### 確認事項
```25:26:public_html/.htaccess
# Announcement detail routing: /announcement/{id}/ -> announcement.php?id={id}
RewriteRule ^announcement/([0-9]+)/?$ announcement.php?id=$1 [L,QSA]
```

```126:126:public_html/announcements.php
    $url = '/announcement/' . (int)$n['id'] . '/';
```

### 対応方針
- `announcement.php`のURLパラメータ取得処理を確認
- データベースクエリが正しく動作しているか確認
- 404エラーの詳細を確認（サーバーログなど）

---

## まとめ

### 優先度：高
1. **求人検索：並び替えが機能してない** - ユーザー体験に直接影響
2. **求人カード：詳細の高さが無制限になってる** - レイアウトの問題
3. **フッター：規約・サポート情報が全部404** - 法的文書へのアクセス不可

### 優先度：中
4. **求人検索：ページ数切替下部の「条件を変更して再検索する」が404** - 機能不全
5. **同じエリアの求人/ピックアップ求人：「もっと見る」が404** - ナビゲーションの問題
6. **掲載店舗：登録している業種が検索条件に出てこない** - 機能不完全

### 優先度：低（調査が必要）
7. **フッター：運営者が無い** - 表示確認が必要
8. **掲載店舗：掲載店舗一覧から店舗ページ飛ぶと全部404** - サーバー側の確認が必要
9. **お知らせ：詳しく見る→の先が全部404** - サーバー側の確認が必要

---

## 推奨対応順序

1. ~~`.htaccess`に`/terms/`と`/privacy/`のルーティングを追加~~ → **不要（ブラウザで動作確認済み）**
2. ~~`jobs.php`の求人カードに`description_text`を出力／`jobs.php`のピックアップ求人にも出力~~ → **出力完了**／~~`job.php`のピックアップ求人の説明に高さ制限を適用~~ → **適用済み（index.php準拠）**
3. ~~`jobs.php`の並び替え機能にJavaScriptを実装~~ → **実装完了**
   - `get_jobs()`関数にsortパラメータ対応を追加（ORDER BY句の動的変更）
   - jobs.phpのPHP部分でsortパラメータ処理を追加
   - JavaScriptでselectのchangeイベントを監視し、URLパラメータ変更でページ遷移
   - 選択状態の保持（selected属性）とページリセット機能を実装
4. ~~`jobs.php`の「条件を変更して再検索する」リンクを修正~~ → **実装完了**
   - UI的に冗長なため、2つのボタンを削除
   - 下部ページャー部の「条件を変更して再検索する」ボタン削除
   - 空状態部の「検索条件を変更する」ボタン削除
5. ~~「もっと見る」リンクのURLを修正~~ → **実装完了**
   - 全ページの「同じエリアの求人」「ピックアップ求人」リンクを`/jobs/`に統一
   - 対象ファイル: jobs.php, job.php, announcement.php, index.php
   - 新着求人等の他のリンクは変更せず
6. ~~各ページの「職種で探す」選択肢を動的に生成~~ → **実装完了**
   - `get_employment_types()`関数を新規作成（jobsテーブルのemployment_typeからDISTINCT取得）
   - jobs.phpで職種選択肢を動的に生成（ハードコーディングから変更）
   - `get_jobs()`/`count_jobs()`関数にemploymentフィルターを追加（IN句で複数選択対応）
   - 検索条件表示に職種チップを追加
7. ~~404エラーの詳細調査（サーバーログ確認）~~ → **修正済み**

---

## 注意事項

- 404エラーについては、サーバーの設定や.htaccessの動作確認が必要な場合があります
- データベースの構造（特に`stores`テーブルの`category`カラムの有無）を確認する必要があります
- 一部の問題は、実際のブラウザでの動作確認が必要です

