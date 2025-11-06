---
title: 初めての方ページ 実装監査レポート（for-beginners）
date: 2025-11-03 07:27:01 JST
url: /for-beginners/
---

## 概要
- 対象: `https://resocaba-info.com/for-beginners/`
- 状態: 構成・文言・目次・CTA・Q&A といった情報設計は仕様通り。主要リンク動作も正常。
- 主要な要修正点: Flow セクションのUIが仕様の「アイコン＋点線タイムライン」と異なる。
- 補足: 画像は `.ph` クラスでCSS背景として実装済み（WebP）。視覚上は問題なし。アクセシビリティ/パフォーマンスの改善余地はあり。

## 仕様準拠チェック（抜粋）

### 1. ヒーロー／イントロ
- 文言: 仕様通り。
- CTA: 「求人を探す」→ `/jobs` に遷移（OK）。
- 画像: `.ph` 背景で実装（表示OK）。

### 2. 目次（1〜6）
- 表示・アンカー動作: 問題なし。

### 3. 各セクションの本文
- 1. 魅力: 見出し・本文 OK。画像は`.ph` 背景で表示。
- 2. どんな人が…: 見出し・本文 OK。画像は`.ph` 背景で表示。
- 3. エリア例: 見出し・本文 OK。画像は`.ph` 背景で表示。
- 4. 応募〜勤務開始までの流れ: 文言 OK。UI が仕様と相違（後述）。
- 5. 現地生活のイメージ: 箇条書き OK。画像は`.ph` 背景で表示。
- 6. Q&A: アコーディオン開閉・回答内容 OK。

## 相違点・修正が必要な点

1) Flow（応募〜勤務開始までの流れ）のUI相違
- 仕様: 丸いアイコン＋縦の点線でつながるタイムライン、各 Step の見出しと説明がボックス化。
- 現状: 通常のテキストリスト表示。
- 対応: 専用CSSとマークアップ微調整でタイムライン化（アクセシビリティ配慮）。

2) 画像まわりの改善余地（任意）
- 実装方式がCSS背景のため、`alt` 相当は `figcaption` で補われている。より厳密なアクセシビリティ要件がある場合は `<img>` 化も検討。
- LCP改善: ヒーロー画像のプリロード、適切な解像度の供給（必要に応じて `srcset` を用いた `<img>` 化）。
- CLS抑制: `.ph` の `aspect-ratio:16/9` は良好。`height` を明示しない現実装でもレイアウトシフトは小。

## 推奨修正内容（実装方針）

### A. 画像のアクセシビリティ/パフォーマンス改善（任意）
- 現状の `.ph` 背景方式を継続する場合:
  - `figcaption` 文言を短く的確に、情報が重複する場合は簡潔に。
  - ヒーローのみプリロード（`<link rel="preload" as="image">`）の検討。
- `<img>` 方式へ切り替える場合:
  - 適切な `alt`、`width/height`、`loading="lazy"`/`decoding="async"` を明示。
  - `srcset`/`sizes` を設定して帯域を最適化。

### B. FlowタイムラインUI
- マークアップ:
  - `ol.flow-steps > li.flow-step` に構造化。
  - 各`li`の先頭に`span.flow-step__icon`（背景〇+アイコン）を配置。
  - 擬似要素で縦点線（`::before`）を連結。最終要素は非表示。
- CSSの要点:
  - `display: grid; grid-template-columns: auto 1fr; gap: 12px;`でアイコンと本文を整列。
  - モバイルは1カラムで読みやすく、デスクトップは余白を最適化。
  - コントラスト・フォーカスリングを担保。

### C. パフォーマンス/SEOの小改善（任意）
- 画像の`alt`最適化、`figcaption`の活用。
- 画像の適切な圧縮（WebP/JPEG）、`sizes`でCLS抑制。

## 受け入れ基準（Acceptance Criteria）
- Flowセクションが「丸アイコン＋縦の点線」のタイムラインスタイルで表示されること。
- モバイル/デスクトップで視認性と可読性が担保され、水平スクロールが発生しないこと。
- 画像表示が現在と同等以上に鮮明で、レイアウトシフトが発生しないこと。
- （任意）画像のアクセスビリティ/パフォーマンス改善を実施した場合、その効果が確認できること。

## 必要アセット／確認事項
- 掲載画像一式（上記8点）とライセンス確認。
- タイムライン用のアイコン（PNG/SVG）。
- カラー（アクセント/点線/アイコン背景）の指定があれば共有。

## 検証計画（抜粋）
- デバイス幅: 390px / 768px / 1280px。
- 動作: 目次アンカー、CTAリンク、Q&Aアコーディオン。
- パフォーマンス: 画像遅延読み込み確認、CLS目視チェック。

## 備考
- 現状の情報構成・文章は仕様と整合。UI（Flow）を調整すれば添付イメージに近づく。

### 補足: 画像の実装根拠
以下のとおり、`public_html/for-beginners.php` にて `.ph` 系クラスで背景画像が指定されています。

```174:189:/home/xs724055/resocaba-info.com/public_html/for-beginners.php
            .ph {
                display: block;
                width: 100%;
                aspect-ratio: 16/9;
                background: no-repeat center center;
                background-size: cover;
                background-image: url("../assets/images/for-beginners/シンガポール.webp");
            }
            .ph--1 { background-image: url("../assets/images/for-beginners/シンガポール.webp"); }
            .ph--2 { background-image: url("../assets/images/for-beginners/5つの魅力.webp"); }
            .ph--3 { background-image: url("../assets/images/for-beginners/5つの魅力2.webp"); }
            .ph--4 { background-image: url("../assets/images/for-beginners/インタビュー例.webp"); }
            .ph--5 { background-image: url("../assets/images/for-beginners/インタビュー例2.webp"); }
            .ph--6 { background-image: url("../assets/images/for-beginners/シンガポール.webp"); }
            .ph--7 { background-image: url("../assets/images/for-beginners/ベトナム.webp"); }
            .ph--8 { background-image: url("../assets/images/for-beginners/寮.webp"); }
```

参考URL: https://resocaba-info.com/for-beginners/

