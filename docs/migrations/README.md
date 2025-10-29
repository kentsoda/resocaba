# DBマイグレーション手順（Phase 2）

このディレクトリには管理画面 Phase 2 で使用する SQL をまとめます。

## 実行順序（推奨）
1. `sql/add_jobs_currency_jobtype_card_message_2025-10-27.sql`
2. `sql/add_stores_category_hours_split_2025-10-27.sql`
3. `sql/add_store_images_table_2025-10-27.sql`
4. `sql/add_tags_category_2025-10-27.sql`
5. `sql/add_job_images_sort_order_2025-10-27.sql`
   - MySQL 5.7 などでウィンドウ関数が使えない場合は代替: `sql/add_job_images_sort_order_fallback_mysql57_2025-10-28.sql`

## 注意事項
- 本番適用前に必ずバックアップを取得してください。
- 既存データに依存するアプリがあるため、オフピーク時間帯での実行を推奨します。
- MySQL バージョンによっては `ROW_NUMBER()` が使えない場合があります。その場合、`add_job_images_sort_order_2025-10-27.sql` の初期化は一時テーブルや変数で代替してください。


