-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8.remi
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2025 年 10 月 22 日 18:04
-- サーバのバージョン： 10.5.27-MariaDB-log
-- PHP のバージョン: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `xs724055_caba`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `ad_banners`
--

CREATE TABLE `ad_banners` (
  `id` int(10) UNSIGNED NOT NULL,
  `image_url` varchar(1024) NOT NULL,
  `link_url` varchar(1024) NOT NULL DEFAULT '',
  `target_blank` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `body_html` mediumtext DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `author_user_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `slug`, `body_html`, `status`, `published_at`, `author_user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '【ベトナム】ハノイの新店舗「CRAZY CAT\'S」の求人情報を掲載しました。ああ', 'detail-1', '<p>ハノイに新規オープンした日本人オーナー店「CRAZY CAT\'S」の求人情報を掲載aaしました。</p><p>未経験歓迎・寮費無料などの高待遇です。</p>', 'published', '2025-09-22 17:52:40', NULL, NULL, '2025-09-22 17:52:40', '2025-09-22 19:14:04'),
(2, '【沖縄】リゾート地で働こう！新着求人を追加しました。', 'okinawa-2025-09', '<p>沖縄エリアでの新着求人を複数追加しました。週1〜OKや短期募集もあります。</p>', 'published', '2025-09-22 17:52:40', NULL, NULL, '2025-09-22 17:52:40', '2025-09-22 17:52:40'),
(3, 'メンテナンス完了のお知らせ', 'maintenance-2025-09', '<p>本日未明にサイトのメンテナンスを実施し、無事完了しました。</p>', 'published', '2025-09-22 17:52:40', NULL, NULL, '2025-09-22 17:52:40', '2025-09-22 17:52:40');

-- --------------------------------------------------------

--
-- テーブルの構造 `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `body_html` mediumtext DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `og_image_url` varchar(512) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `author_user_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `body_html`, `category`, `og_image_url`, `status`, `published_at`, `author_user_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '【エリア紹介】初めての海外リゾバ！ベトナム・ハノイの魅力とは？', 'vietnam-hanoi-intro', '<p>ハノイの魅力を紹介します。</p>', 'エリア紹介', NULL, 'published', '2025-09-22 19:35:12', NULL, NULL, '2025-09-22 19:35:12', '2025-09-22 19:35:12'),
(2, '【ノウハウ】海外リゾバ準備チェックリスト', 'howto-prep-checklist', '<p>準備物のチェックリスト。</p>', 'ノウハウ', NULL, 'published', '2025-09-22 19:35:12', NULL, NULL, '2025-09-22 19:35:12', '2025-09-22 19:35:12'),
(3, '【体験談】沖縄で働いてみた', 'experience-okinawa', '<p>沖縄での体験談。</p>', '体験談', NULL, 'published', '2025-09-22 19:35:12', NULL, NULL, '2025-09-22 19:35:12', '2025-09-22 19:35:12'),
(4, '海外キャバクラで初めて働きたい方向けの完全ガイド', NULL, '<!DOCTYPE html>\r\n<html lang=\"ja\">\r\n<head>\r\n  <meta charset=\"utf-8\" />\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />\r\n  <title>初めての方｜海外リゾキャバ求人.COM</title>\r\n  <meta name=\"description\" content=\"海外キャバクラで初めて働きたい方向けの完全ガイド\" />\r\n  <style>\r\n    :root{ --brand:#00bfa6; --ink:#222; --muted:#667085; --bg:#ffffff; --card:#f7f7f8; --border:#e6e6ea; }\r\n    html,body{margin:0;padding:0;background:var(--bg);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,\"Hiragino Kaku Gothic ProN\",\"Noto Sans JP\",\"Yu Gothic\",\"Helvetica Neue\",Arial,sans-serif;line-height:1.75}\r\n    .container{max-width:1080px;margin-inline:auto;padding:24px}\r\n    .hero{display:grid;gap:20px;padding:28px;border-radius:16px;background:linear-gradient(180deg,#f0fffc 0%,#ffffff 60%);border:1px solid var(--border)}\r\n    .eyebrow{color:var(--brand);font-weight:700;letter-spacing:.06em;font-size:.9rem}\r\n    h1{font-size:clamp(1.6rem,2.2vw,2.2rem);line-height:1.3;margin:0;color:#0f172a}\r\n    .lead{color:#374151;margin:8px 0 0}\r\n    .hero .cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:8px;justify-content:center} /* 中央寄せ */\r\n    .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:12px;font-weight:700;text-decoration:none;border:1px solid transparent}\r\n    .btn-primary{background:var(--brand);color:#fff}\r\n\r\n    nav.toc{margin:28px 0;border:1px solid var(--border);border-radius:14px;background:#fff}\r\n    nav.toc h2{font-size:1.05rem;margin:0;padding:14px 18px;border-bottom:1px solid var(--border)}\r\n    nav.toc ol{margin:0;padding:12px 24px 16px 32px}\r\n    nav.toc a{color:#0f172a;text-decoration:none}\r\n\r\n    section{margin:40px 0}\r\n    section h2{font-size:1.4rem;margin:0 0 10px}\r\n    .subtitle{color:var(--muted);margin:0 0 16px}\r\n\r\n    .grid{display:grid;gap:20px}\r\n    @media(min-width:768px){.grid-2{grid-template-columns:1.1fr .9fr}}\r\n\r\n    .card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:20px}\r\n    ul.clean{margin:0;padding-left:1.1em}\r\n    ul.clean li{margin:10px 0}\r\n\r\n    /* 画像プレースホルダー */\r\n    figure{margin:0;border:1px dashed var(--border);border-radius:12px;overflow:hidden;background:#fff}\r\n    .ph{display:block;width:100%;aspect-ratio:16/9;background:repeating-linear-gradient(45deg,#f8fafc,#f8fafc 10px,#eef2f7 10px,#eef2f7 20px)}\r\n    figcaption{font-size:.9rem;color:#475569;padding:10px 12px;background:#f9fafb;border-top:1px dashed var(--border)}\r\n\r\n    /* メリット：画像→文面→画像→文面… */\r\n    .merits{display:grid;gap:24px}\r\n    .merit-row{display:grid;gap:14px}\r\n    @media(min-width:900px){.merit-row{grid-template-columns:1.05fr 1fr;align-items:center}}\r\n    .merit-row.reverse{direction:rtl}.merit-row.reverse>*{direction:ltr}\r\n    .merit-text{background:#fff;border:1px solid var(--border);border-radius:14px;padding:16px}\r\n    .merit-text h3{font-size:1.06rem;margin:0 0 6px}\r\n\r\n    /* インタビュー */\r\n    .stack{display:grid;gap:20px}\r\n    .stack .block{display:grid;gap:10px}\r\n    @media(min-width:840px){.stack .block{grid-template-columns:1fr 1.2fr;align-items:start}}\r\n    .stack .card h3{margin-top:0}\r\n\r\n    /* 国エリア */\r\n    .country{display:grid;gap:16px}\r\n    @media(min-width:840px){.country{grid-template-columns:1fr 1.1fr}}\r\n\r\n    /* CTA */\r\n    .cta-band{display:grid;gap:12px;align-items:center;grid-template-columns:1fr; padding:16px;border:1px solid var(--border);border-radius:16px;background:linear-gradient(180deg,#f0fffc 0%,#ffffff 70%)}\r\n    @media(min-width:720px){.cta-band{grid-template-columns:1fr auto}}\r\n    .cta-band h3{margin:0;font-size:1.2rem}\r\n\r\n    /* FAQ 表示調整 */\r\n    details{background:#fff;border:1px solid var(--border);border-radius:12px;margin:10px 0}\r\n    summary{cursor:pointer;padding:12px 14px;font-weight:700}\r\n    details[open] summary{border-bottom:1px solid var(--border)}\r\n    .a{padding:12px 14px;color:#374151}\r\n\r\n    footer{margin:52px 0 24px;color:#6b7280;font-size:.9rem}\r\n  </style>\r\n</head>\r\n<body>\r\n  <header class=\"container hero\">\r\n    <span class=\"eyebrow\">For Beginner</span>\r\n    <h1>海外キャバクラで“はじめて”働くあなたへ｜魅力・応募の流れ・Q&Aガイド</h1>\r\n    <figure style=\"margin:8px 0 4px\">\r\n      <div class=\"ph\" aria-hidden=\"true\"></div>\r\n      <figcaption>ヒーロー画像：海辺・夜景・街歩きなど「旅×しごと」をイメージできる横長写真（16:9）</figcaption>\r\n    </figure>\r\n    <p class=\"lead\">「海外でちょっと働いて、ついでに旅行も楽しみたい」「できれば楽に、サクッと稼ぎたい」——そんなあなた向けのページです。まずは“海外キャバクラのいいところ”をぎゅっと紹介。“応募〜お仕事スタート”までの流れとQ&Aを、カンタンにまとめました。エントリーも相談も無料です👌</p>\r\n    <p class=\"lead\" style=\"margin-top:-4px;color:#475569\"><small>※ご案内のサポート内容・待遇・働き方・期間などは<b>求人・時期・エリアによって異なります</b>。最新の条件は各求人ページや担当からのご連絡でご確認ください。</small></p>\r\n    <div class=\"cta\">\r\n      <a class=\"btn btn-primary\" href=\"/jobs\">求人を探す</a>\r\n    </div>\r\n  </header>\r\n\r\n  <main class=\"container\">\r\n    <nav class=\"toc\" aria-label=\"目次\">\r\n      <h2>この記事の目次</h2>\r\n      <ol>\r\n        <li><a href=\"#merit\">海外キャバクラで働く“5つの魅力”</a></li>\r\n        <li><a href=\"#voice\">どんな人がどれくらい働いてる？</a></li>\r\n        <li><a href=\"#area\">働ける主な国・エリアの例</a></li>\r\n        <li><a href=\"#flow\">応募〜勤務開始までの流れ</a></li>\r\n        <li><a href=\"#life\">現地生活のイメージ</a></li>\r\n        <li><a href=\"#faq\">よくある質問（Q&A）</a></li>\r\n      </ol>\r\n    </nav>\r\n\r\n    <section id=\"merit\">\r\n      <h2>1. 海外キャバクラで働く“5つの魅力”</h2>\r\n      <p class=\"subtitle\">旅行×お仕事のいいとこ取り。未経験でもはじめやすく、ムリなく続けやすい環境が選べます。</p>\r\n      <div class=\"merits\">\r\n        <div class=\"merit-row\">\r\n          <figure><div class=\"ph\" aria-hidden=\"true\"></div><figcaption>旅×しごと：海・街・夜景・グルメのコラージュ（16:9）</figcaption></figure>\r\n          <div class=\"merit-text\">\r\n            <h3>旅行とお仕事を両立できる</h3>\r\n            <p>昼は観光、夜はお仕事——“稼ぎながら旅する”スタイルが叶います。海・街・夜景・グルメ…気分でエリアを選べるのも楽しい。</p>\r\n            <p>休日は人気スポットのハシゴや、映える写真スポット巡りも◎。思い出づくりと貯金が同時に進みます。</p>\r\n          </div>\r\n        </div>\r\n        <div class=\"merit-row reverse\">\r\n          <figure><div class=\"ph\" aria-hidden=\"true\"></div><figcaption>はじめてでもOK：笑顔の接客シーン（16:9）</figcaption></figure>\r\n          <div class=\"merit-text\">\r\n            <h3>未経験からはじめやすい</h3>\r\n            <p>“笑顔でおしゃべり”がいちばんの武器。初めての人でも入り口は広く、先輩のコツを真似しながら少しずつ慣れていけます。</p>\r\n            <p>まずは短期で雰囲気をつかんで、気に入ったら延長や再渡航へ…というステップも定番です。</p>\r\n          </div>\r\n        </div>\r\n        <div class=\"merit-row\">\r\n          <figure><div class=\"ph\" aria-hidden=\"true\"></div><figcaption>働きやすさ：私服OK・ノンアル対応イメージ（16:9）</figcaption></figure>\r\n          <div class=\"merit-text\">\r\n            <h3>ムリのない働き方</h3>\r\n            <p>私服OKやノンアル対応など“働きやすさ重視”の雰囲気。がっつり営業よりも「楽しくおしゃべり」を大切にしているお店が多めです。</p>\r\n            <p>シフトは事前に組まれるから、遊ぶ日・休む日のバランスを取りやすく、生活リズムも整えやすい。</p>\r\n          </div>\r\n        </div>\r\n        <div class=\"merit-row reverse\">\r\n          <figure><div class=\"ph\" aria-hidden=\"true\"></div><figcaption>寮・食事サポート：部屋・まかない・送迎のイメージ（16:9）</figcaption></figure>\r\n          <div class=\"merit-text\">\r\n            <h3>生活コストをおさえやすい</h3>\r\n            <p>“寮・食事サポート・空港送迎”などがセットの募集もあるので、初期費用をグッと抑えやすいのがうれしい。</p>\r\n            <p>家賃や光熱費の負担が軽いぶん、手元にお金が残りやすい実感が持てます。</p>\r\n          </div>\r\n        </div>\r\n        <div class=\"merit-row\">\r\n          <figure><div class=\"ph\" aria-hidden=\"true\"></div><figcaption>プライバシー配慮：海外×リゾートの距離感が伝わる写真（16:9）</figcaption></figure>\r\n          <div class=\"merit-text\">\r\n            <h3>身バレの心配をしにくい環境</h3>\r\n            <p>海外でのお仕事だから、地元の知り合いに会う心配はほぼナシ。プライベートとお仕事をすっきり分けたい人にも向いています。</p>\r\n            <p>SNSの見せ方や距離感は、先輩の小ワザを聞きながら上手にコントロールできます。</p>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </section>\r\n\r\n    <section id=\"voice\">\r\n      <h2>2. どんな人がどれくらい働いてる？</h2>\r\n      <div class=\"stack\">\r\n        <div class=\"block\">\r\n          <figure><div class=\"ph\"></div><figcaption>例①イメージ：学生2人の女子旅スナップ（カフェ・街歩き）</figcaption></figure>\r\n          <article class=\"card\">\r\n            <h3>例①：21歳／学生（春休み2週間）</h3>\r\n            <p>「友だちと2人で挑戦しました。昼はカフェ巡り、夜はお仕事でメリハリがついて、短期でも“思ったよりちゃんと貯金できた”のが嬉しかったです。担当さんが相談に乗ってくれたので不安もすぐ解消できました。次は夏休みにもう少し長めで行きたい！」</p>\r\n          </article>\r\n        </div>\r\n        <div class=\"block\">\r\n          <figure><div class=\"ph\"></div><figcaption>例②イメージ：海沿いでリラックスするシーン</figcaption></figure>\r\n          <article class=\"card\">\r\n            <h3>例②：24歳／フリーター（1か月）</h3>\r\n            <p>「“海外で住む”を一回やってみたくて来ました。生活まわりのサポートが整った求人を選んだので、着いた日からスムーズに動けました。オフは海沿いでのんびりして、気持ちもリフレッシュ。帰国後に再渡航を相談中です！」</p>\r\n          </article>\r\n        </div>\r\n        <div class=\"block\">\r\n          <figure><div class=\"ph\"></div><figcaption>例③イメージ：夜景バックに移動（通勤イメージ）</figcaption></figure>\r\n          <article class=\"card\">\r\n            <h3>例③：27歳／転職前の有休消化（3週間）</h3>\r\n            <p>「次の仕事が始まるまでの間は、夜だけ働くスタイルにしました。体調も整えやすくて、短期でも常連さんができました。最終日に“また来てね”って言われたのが一番うれしかったです。短期間でもちゃんと手応えがありました。」</p>\r\n          </article>\r\n        </div>\r\n        <div class=\"block\">\r\n          <figure><div class=\"ph\"></div><figcaption>例④イメージ：ショップ袋を持った街歩き（接客経験活用の雰囲気）</figcaption></figure>\r\n          <article class=\"card\">\r\n            <h3>例④：29歳／アパレル販売経験あり（6週間）</h3>\r\n            <p>「接客の経験がそのまま活きました。英語は挨拶レベルからでしたが、先輩のフレーズを真似してるうちに後半は指名も増えて、自信がつきました。観光も仕事も充実して、“もう一度来たい”って素直に思えました。」</p>\r\n          </article>\r\n        </div>\r\n      </div>\r\n    </section>\r\n\r\n    <section id=\"area\">\r\n      <h2>3. 働ける主な国・エリアの例</h2>\r\n      <div class=\"country\">\r\n        <figure><div class=\"ph\"></div><figcaption>エリア写真：シンガポールの夜景（マリーナ周辺など）</figcaption></figure>\r\n        <div>\r\n          <h3>シンガポール（都会×夜景）</h3>\r\n          <p>夜景やショッピング、清潔感のある街並みが魅力。きれいめ私服や落ち着いた接客スタイルの募集が見つかりやすいです。</p>\r\n          <p>街歩きだけでも楽しく、カフェや屋上バーなど写真が映えるスポットも豊富。短期でも満足度が高いエリアです。</p>\r\n          <p>交通の便がよく移動がラクなので、限られたオフでも予定が組みやすいのがうれしい。夜のライトアップは思わず写真を撮りたくなるはず。</p>\r\n          <p>ごはんも多国籍で選び放題。気分に合わせて毎日違う味を楽しめます。</p>\r\n        </div>\r\n      </div>\r\n      <div class=\"country\">\r\n        <figure><div class=\"ph\"></div><figcaption>エリア写真：ベトナムのカフェ・市場・街角アート</figcaption></figure>\r\n        <div>\r\n          <h3>ベトナム（ホーチミン／ハノイ）</h3>\r\n          <p>カフェやごはんが豊富で“毎日ちょっと楽しい”。コンドミニアム系の寮や送迎がセットになった求人も探しやすい印象です。</p>\r\n          <p>物価も比較的やさしめで、オフのカフェ巡りや雑貨屋さん探しがはかどります。街の活気に元気をもらえるはず。</p>\r\n          <p>写真が映える壁アートやローカル市場も多く、休日の散策がほんとうに充実。気づいたら常連のカフェができてます。</p>\r\n          <p>屋台のバインミーや生春巻きなど、手軽でおいしいローカル飯も楽しみのひとつ。</p>\r\n        </div>\r\n      </div>\r\n      <div class=\"country\">\r\n        <figure><div class=\"ph\"></div><figcaption>エリア写真：タイの夜市・寺院・ビーチ</figcaption></figure>\r\n        <div>\r\n          <h3>タイ（バンコク／リゾート）</h3>\r\n          <p>休日の観光ネタが尽きない人気エリア。衣装はドレス系〜私服系まで幅広く、写真映えスポットも多くて遊び場に困りません。</p>\r\n          <p>屋台グルメやナイトマーケットも楽しく、昼夜どちらも充実。リピーターが多いのも納得のロケーションです。</p>\r\n          <p>マッサージやスパも手頃で、オフのリフレッシュにぴったり。気軽に“自分メンテ”ができるのが最高。</p>\r\n          <p>雨でも楽しめる大型モールや水上マーケットなど、気分に合わせて遊び方を選べます。</p>\r\n        </div>\r\n      </div>\r\n      <div class=\"country\">\r\n        <figure><div class=\"ph\"></div><figcaption>エリア写真：マレーシアの高層ビル群・屋台・カフェ</figcaption></figure>\r\n        <div>\r\n          <h3>マレーシア（クアラルンプールなど）</h3>\r\n          <p>大型モールから屋台まで“便利さ×多国籍感”が魅力。日本語の生活情報も集めやすく、初めてでも動きやすいです。</p>\r\n          <p>雨でも遊べる屋内スポットが多いので、オフの選択肢が広め。暮らしと遊びのバランスが取りやすいエリアです。</p>\r\n          <p>広くて快適なカフェが多く、仕事前のひと息や休日のパソコン作業にも◎。街の夜景もキレイで、気分転換にちょうどいい。</p>\r\n          <p>配車アプリで移動しやすく、はじめての場所でも迷いにくいのが助かります。</p>\r\n        </div>\r\n      </div>\r\n    </section>\r\n\r\n    <section id=\"flow\">\r\n      <h2>4. 応募〜勤務開始までの流れ</h2>\r\n      <div class=\"grid grid-2\">\r\n        <figure><div class=\"ph\"></div><figcaption>図版スペース：応募〜到着までのフローチャート（アイコン×矢印の簡易図）</figcaption></figure>\r\n        <ol class=\"card\" style=\"margin:0\">\r\n        <li><b>条件に合った求人情報を探す</b><br>当サイトの求人情報から自分が行きたい国、期間とマッチした求人情報をチェック！</li>\r\n        <li><b>プロフィール登録</b><br>簡単な情報を入力してエントリーを行います。新規登録すると次回以降の情報入力が全て自動化されます。</li>\r\n        <li><b>オンライン面談</b><br>不安や質問をまとめて解消。働き方・生活面・準備物をわかりやすく説明。</li>\r\n        <li><b>お店のご紹介・比較</b><br>寮・送迎・給与内訳・衣装ルールを比べて、あなたに合うお店を一緒に決定。</li>\r\n        <li><b>決定＆準備</b><br>スケジュールが合えばスピード決定も。持ち物リストと到着日の動き方を共有。</li>\r\n        <li><b>現地到着→スタート</b><br>空港お迎えや入寮案内、初日の説明がセットの求人も。初日で生活とお仕事の流れをつかめます。</li>\r\n        </ol>\r\n      </div>\r\n    </section>\r\n\r\n    <section id=\"life\">\r\n      <h2>5. 現地生活のイメージ</h2>\r\n      <div class=\"grid\" style=\"grid-template-columns:1fr;gap:18px\">\r\n        <figure><div class=\"ph\"></div><figcaption>寮イメージ：清潔な室内・Wi-Fi・エアコン・立地感が伝わる写真</figcaption></figure>\r\n      </div>\r\n      <div class=\"card\" style=\"margin-top:18px\">\r\n        <ul class=\"clean\">\r\n          <li><b>寮</b>：清潔なお部屋にWi-Fi・エアコン完備の物件が人気。スーパーやカフェが近い立地だと毎日がさらにラク。</li>\r\n          <li><b>送迎</b>：通勤は専用車でサクッと移動の求人もあり。夜道の移動も安心感があって続けやすい。</li>\r\n          <li><b>サポート</b>：到着日から担当がチャットでフォローの体制がある求人も。美容院・ネイル・両替スポットなど生活情報も共有。</li>\r\n          <li><b>働き方</b>：私服OK／ドレス支給／ヘアメイクあり等、スタイルはいろいろ。自分に合う“ムリのないスタンス”で続けやすい。</li>\r\n        </ul>\r\n      </div>\r\n    </section>\r\n\r\n    <section class=\"cta-band\">\r\n      <h3>まずは相談だけでもOK。あなたに合う“ラクに続けやすい”働き方を一緒に。</h3>\r\n      <div><a class=\"btn btn-primary\" href=\"/jobs\">求人を探す</a></div>\r\n    </section>\r\n\r\n    <section id=\"faq\">\r\n      <h2>6. よくある質問（Q&A）</h2>\r\n      <details>\r\n        <summary>未経験でも大丈夫？</summary>\r\n        <div class=\"a\">大丈夫。まずは笑顔でおしゃべりできればOK。接客のコツは面談でしっかり共有します。</div>\r\n      </details>\r\n      <details>\r\n        <summary>お酒が弱いんですが…</summary>\r\n        <div class=\"a\">ノンアルや軽めのドリンクで働いている方も多いです。自分のペースで無理なく続けられます。</div>\r\n      </details>\r\n      <details>\r\n        <summary>英語がほとんど話せません</summary>\r\n        <div class=\"a\">挨拶レベルからスタートした先輩もたくさん。よく使うフレーズは面談でお渡しします。</div>\r\n      </details>\r\n      <details>\r\n        <summary>どのくらいの期間から行ける？</summary>\r\n        <div class=\"a\">まずは1〜2週間の“お試し”が人気。気に入ったら延長や再渡航も相談できます。</div>\r\n      </details>\r\n      <details>\r\n        <summary>到着してからの流れが不安</summary>\r\n        <div class=\"a\">空港お迎え→入寮→店舗案内→シフト説明までがセットになった求人もあります。初日で生活のリズムをつかめるので安心です。</div>\r\n      </details>\r\n\r\n      <script type=\"application/ld+json\">\r\n      {\r\n        \"@context\":\"https://schema.org\",\r\n        \"@type\":\"FAQPage\",\r\n        \"mainEntity\":[\r\n          {\"@type\":\"Question\",\"name\":\"未経験でも大丈夫？\",\"acceptedAnswer\":{\"@type\":\"Answer\",\"text\":\"未経験でもOK。まずは笑顔でおしゃべりできればOK。接客のコツは面談で共有します。\"}},\r\n          {\"@type\":\"Question\",\"name\":\"お酒が弱いんですが…\",\"acceptedAnswer\":{\"@type\":\"Answer\",\"text\":\"ノンアルや軽めのドリンクで働いている方も多いです。自分のペースで無理なく続けられます。\"}},\r\n          {\"@type\":\"Question\",\"name\":\"英語がほとんど話せません\",\"acceptedAnswer\":{\"@type\":\"Answer\",\"text\":\"挨拶レベルからスタートした先輩もたくさん。よく使うフレーズは面談でお渡しします。\"}},\r\n          {\"@type\":\"Question\",\"name\":\"どのくらいの期間から行ける？\",\"acceptedAnswer\":{\"@type\":\"Answer\",\"text\":\"まずは1〜2週間の“お試し”が人気。気に入ったら延長や再渡航も相談できます。\"}},\r\n          {\"@type\":\"Question\",\"name\":\"到着してからの流れが不安\",\"acceptedAnswer\":{\"@type\":\"Answer\",\"text\":\"空港お迎え→入寮→店舗案内→シフト説明までがセットになった求人もあります。初日で生活のリズムをつかめます。\"}}\r\n        ]\r\n      }\r\n      </script>\r\n    </section>\r\n  </main>\r\n\r\n  <footer class=\"container\"><p>© 海外リゾキャバ求人.COM</p></footer>\r\n</body>\r\n</html>\r\n', '初めての方', '', 'published', NULL, NULL, NULL, '2025-10-10 14:45:41', '2025-10-14 10:45:17');

-- --------------------------------------------------------

--
-- テーブルの構造 `assets`
--

CREATE TABLE `assets` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(512) NOT NULL,
  `mime` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `assets`
--

INSERT INTO `assets` (`id`, `file_name`, `file_path`, `mime`, `size`, `width`, `height`, `created_by`, `created_at`) VALUES
(2, 'a.png', '/resocaba/storage/uploads/2025/09/20250924202640-d5aecffe43fb.png', 'image/png', 10667, 1920, 1080, 1, '2025-09-24 20:26:40'),
(3, '0926.png', '/resocaba/storage/uploads/2025/09/20250926175938-941b4ea82fb5.png', 'image/png', 750726, 1795, 930, 1, '2025-09-26 17:59:38'),
(4, 'a.png', '/resocaba/storage/uploads/2025/10/20251006215815-4d1cfdea9758.png', 'image/png', 10667, 1920, 1080, 1, '2025-10-06 21:58:15');

-- --------------------------------------------------------

--
-- テーブルの構造 `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(64) NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `before_json` mediumtext DEFAULT NULL,
  `after_json` mediumtext DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `ua` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer_html` mediumtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('draft','published') DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer_html`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, '未経験でも応募できますか？', '<p>はい、もちろんご応募いただけます。多くの求人が未経験者歓迎です。</p>', 1, 'published', '2025-09-22 19:48:45', '2025-09-22 19:48:45'),
(2, '応募から採用までの流れは？', '<p>応募→書類選考→面接→採用→渡航準備→勤務開始の流れが一般的です。</p>', 2, 'published', '2025-09-22 19:48:45', '2025-09-22 19:48:45'),
(3, '語学力（英語など）は必要ですか？', '<p>必須ではありません。働きながら学べる環境もあります。</p>', 3, 'published', '2025-09-22 19:48:45', '2025-09-22 19:48:45'),
(4, '海外生活のサポートはありますか？', '<p>寮完備、現地サポート、ビザ申請支援などを提供する求人があります。</p>', 4, 'published', '2025-09-22 19:48:45', '2025-09-22 19:48:45'),
(5, 'お給料の受け取り方法は？', '<p>店舗により異なりますが、日払いや週払いに対応している場合があります。</p>', 5, 'published', '2025-09-22 19:48:45', '2025-09-22 19:48:45');

-- --------------------------------------------------------

--
-- テーブルの構造 `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `description_html` mediumtext DEFAULT NULL,
  `description_text` mediumtext DEFAULT NULL,
  `message_text` mediumtext DEFAULT NULL,
  `message_html` mediumtext DEFAULT NULL,
  `work_content_html` mediumtext DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `region_prefecture` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `employment_type` varchar(32) DEFAULT NULL,
  `category` enum('domestic','overseas') DEFAULT NULL,
  `salary_min` int(11) DEFAULT NULL,
  `salary_max` int(11) DEFAULT NULL,
  `salary_unit` enum('HOUR','DAY','MONTH') DEFAULT 'HOUR',
  `benefits_json` text DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `author_user_id` int(11) DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `meta_json` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `slug`, `description_html`, `description_text`, `message_text`, `message_html`, `work_content_html`, `store_id`, `country`, `region_prefecture`, `city`, `employment_type`, `category`, `salary_min`, `salary_max`, `salary_unit`, `benefits_json`, `status`, `published_at`, `author_user_id`, `is_pinned`, `meta_json`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '【サンプル求人】No.1', NULL, NULL, 'あ', 'a', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1400, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:02:35', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-1\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:02:35', '2025-09-22 16:32:10'),
(2, '【サンプル求人】No.2', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1400, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:02:35', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-2\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:02:35', '2025-09-22 16:29:00'),
(3, '【サンプル求人】No.3', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1400, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:02:35', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-3\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:02:35', '2025-09-22 16:29:00'),
(4, '【サンプル求人】No.4', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1400, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:02:35', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-4\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:02:35', '2025-09-22 16:29:00'),
(5, '【サンプル求人】No.5', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1400, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:02:35', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-5\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:02:35', '2025-09-22 16:29:00'),
(6, '【サンプル求人】No.1', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1450, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:09:43', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-6\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:09:43', '2025-09-22 16:29:00'),
(7, '【サンプル求人】No.2', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1500, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:09:43', NULL, 0, '{\"period\":\"3ヶ月以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-7\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:09:43', '2025-09-22 16:29:00'),
(8, '【サンプル求人】No.3', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1550, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:09:43', NULL, 0, '{\"period\":\"半年以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-8\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:09:43', '2025-09-22 16:29:00'),
(9, '【サンプル求人】No.4', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1600, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:09:43', NULL, 0, '{\"period\":\"1ヶ月未満\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-9\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:09:43', '2025-09-22 16:29:00'),
(10, '【サンプル求人】No.5', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1650, NULL, 'HOUR', '[\"寮費完全無料\",\"日払いOK\",\"Wi-Fi完備\"]', 'published', '2025-09-22 14:09:43', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"JOB-10\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\"]}', NULL, '2025-09-22 14:09:43', '2025-09-22 16:29:00'),
(11, '【サンプル求人】No.1', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1450, 1900, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:15:06', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-1\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:15:06', '2025-09-22 16:29:00'),
(12, '【サンプル求人】No.2', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1500, 2000, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:15:06', NULL, 0, '{\"period\":\"3ヶ月以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-2\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:15:06', '2025-09-22 16:29:00'),
(13, '【サンプル求人】No.3', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1550, 2100, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:15:06', NULL, 0, '{\"period\":\"半年以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-3\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:15:06', '2025-09-22 16:29:00'),
(14, '【サンプル求人】No.4', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1600, 2200, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:15:06', NULL, 0, '{\"period\":\"1ヶ月未満\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-4\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:15:06', '2025-09-22 16:29:00'),
(15, '【サンプル求人】No.5', NULL, NULL, 'これはサンプル求人です。実装確認用の説明テキストです。', 'この求人では、沖縄で働ける素晴らしい機会をご提供しています。未経験者から経験者まで、様々な方にご応募いただけます。充実したサポート体制と魅力的な待遇で、あなたの新しいキャリアをスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1650, 2300, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:15:06', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-5\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:15:06', '2025-09-22 16:29:00'),
(16, '【サンプル求人】No.1', NULL, NULL, 'これはサンプル求人No.1のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1450, 1900, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:29:51', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-1\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:29:51', '2025-09-22 16:29:51'),
(17, '【サンプル求人】No.2', NULL, NULL, 'これはサンプル求人No.2のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1500, 2000, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:29:51', NULL, 0, '{\"period\":\"3ヶ月以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-2\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:29:51', '2025-09-22 16:29:51'),
(18, '【サンプル求人】No.3', NULL, NULL, 'これはサンプル求人No.3のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1550, 2100, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:29:51', NULL, 0, '{\"period\":\"半年以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-3\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:29:51', '2025-09-22 16:29:51'),
(19, '【サンプル求人】No.4', NULL, NULL, 'これはサンプル求人No.4のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1600, 2200, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:29:51', NULL, 0, '{\"period\":\"1ヶ月未満\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-4\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:29:51', '2025-09-22 16:29:51'),
(20, '【サンプル求人】No.5', NULL, NULL, 'これはサンプル求人No.5のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1650, 2300, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 16:29:51', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-5\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 16:29:51', '2025-09-22 16:29:51'),
(21, 'CRAZY CAT\'S 求人サンプル 1', NULL, '', 'ハノイでのお仕事サンプルです。未経験歓迎。\r\naaaaaa', NULL, NULL, NULL, NULL, NULL, 'ハノイ', NULL, 'キャスト', NULL, 1600, 2120, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"CRAZY_CATS_HANOI-1\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:37:38'),
(22, 'CRAZY CAT\'S 求人サンプル 2', NULL, '', 'ハノイでのお仕事サンプルです。未経験歓迎。', NULL, NULL, NULL, 6, NULL, 'ハノイ', NULL, 'キャスト', NULL, 1700, 2240, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"CRAZY_CATS_HANOI-2\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-25 06:44:59'),
(23, 'Club Diamond Okinawa 求人サンプル 1', NULL, '', '沖縄でのお仕事サンプルです。未経験歓迎。', NULL, NULL, NULL, 7, NULL, '沖縄', NULL, 'キャスト', NULL, 1600, 2120, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"CLUB_DIAMOND_OKINAWA-1\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:21:25'),
(24, 'Club Diamond Okinawa 求人サンプル 2', NULL, '', '沖縄でのお仕事サンプルです。未経験歓迎。', NULL, NULL, NULL, 7, NULL, '沖縄', NULL, 'キャスト', NULL, 1700, 2240, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"CLUB_DIAMOND_OKINAWA-2\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:22:16'),
(25, 'Sample Lounge Tokyo 求人サンプル 1', NULL, '', '東京でのお仕事サンプルです。未経験歓迎。', NULL, NULL, NULL, 8, NULL, '東京', NULL, 'キャスト', NULL, 1600, 2120, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE_LOUNGE_TOKYO-1\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:22:29'),
(26, 'Sample Lounge Tokyo 求人サンプル 2', NULL, '', '東京でのお仕事サンプルです。未経験歓迎。', NULL, NULL, NULL, 8, NULL, '東京', NULL, 'キャスト', NULL, 1700, 2240, 'HOUR', NULL, 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE_LOUNGE_TOKYO-2\",\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:22:39'),
(27, '【サンプル求人】No.1', NULL, '', 'これはサンプル求人No.1のお仕事内容です。\r\n\r\n主な業務内容：\r\n・お客様への接客サービス\r\n・店内の清掃・整理整頓\r\n・商品の管理・補充\r\n\r\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\r\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\r\n\r\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\r\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\r\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\r\n\r\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\r\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1450, 1900, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-1\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"],\"home_sections\":[\"pickup\",\"new\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"]}', NULL, '2025-09-22 17:37:22', '2025-09-26 17:27:18'),
(28, '【サンプル求人】No.2', NULL, NULL, 'これはサンプル求人No.2のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 20, NULL, '沖縄', NULL, 'ホール', NULL, 1500, 2000, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"3ヶ月以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-2\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 17:37:22', '2025-10-03 18:53:17'),
(29, '【サンプル求人】No.3', NULL, NULL, 'これはサンプル求人No.3のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1550, 2100, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"半年以上\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-3\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(30, '【サンプル求人】No.4', NULL, NULL, 'これはサンプル求人No.4のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1600, 2200, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1ヶ月未満\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-4\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(31, '【サンプル求人】No.5', NULL, NULL, 'これはサンプル求人No.5のお仕事内容です。\n\n主な業務内容：\n・お客様への接客サービス\n・店内の清掃・整理整頓\n・商品の管理・補充\n\n未経験者でも安心してスタートできるよう、丁寧な研修を行います。\n一緒に働く仲間と楽しく、充実した時間を過ごしましょう！', 'この求人では、沖縄の美しい自然環境の中で働ける素晴らしい機会をご提供しています。\n\nお客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。\n一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意。\n座って落ち着いて接客できるので、未経験の方でも働きやすい環境です。\n\nまずは「笑顔」と「やってみたい」という気持ちがあればOK！\n私たちと一緒に、沖縄での新しい毎日をスタートしませんか？', NULL, NULL, 2, NULL, '沖縄', NULL, 'ホール', NULL, 1650, 2300, 'HOUR', '[\"無料英会話レッスン（週3時間）\",\"寮費完全無料\",\"往復航空券支給\",\"VISAサポート（申請代行・費用会社負担）\",\"日払いOK\",\"Wi-Fi完備\",\"各種高額バックあり\"]', 'published', '2025-09-22 17:37:22', NULL, 0, '{\"period\":\"1〜3ヶ月\",\"hours\":\"20:00～LAST\",\"holiday\":\"月曜日\",\"job_code\":\"SAMPLE-5\",\"valid_through\":\"2026-09-22\",\"qualifications\":[\"18歳以上\",\"未経験者歓迎\",\"経験者優遇\"]}', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(32, '【未経験歓迎】手厚い生活サポート付き！海外キャバクラキャスト月収30万～100万円', NULL, '🌸 海外キャバクラのお仕事って？<br>\nCLUB KYOTOでは、お客様と楽しくおしゃべりしたり、ドリンクを一緒に楽しんだりするのがメインのお仕事です。<br>\n「お酒に詳しくないとダメかな？」とか「会話に自信がない…」という方も大丈夫！<br>\nちょっとした気配りや笑顔があれば、自然とお客様に喜んでもらえます。<br>\n<br>\n💄 具体的にはこんな感じ<br>\n・お客様の隣に座ってお話しする<br>\n・ドリンクを作ったり、一緒に乾杯したり<br>\n・ときにはお客様からの指名やイベントで盛り上げることも♪<br>\n<br>\n✨ このお仕事のポイント<br>\n・ノルマや罰金はなく、安心して働けます<br>\n・英語やカンボジア語はできなくてもOK！通訳サポートあり<br>\n・海外ならではの非日常感を楽しみながら、しっかり稼げます<br>\n<br>\n💕 こんな方におすすめ<br>\n・海外で新しい生活を始めてみたい<br>\n・普通のバイトよりも高収入を目指したい<br>\n・楽しく働きながら語学やコミュニケーション力も身につけたい<br>\n<br>\n✈️ 渡航費も住まいもサポート完備なので、手ぶらで飛び込んできても大丈夫！<br>\n安心して海外デビューできる環境を用意しています。', '', 'はじめまして🌸\n「CLUB KYOTO」の求人ページをご覧いただきありがとうございます😊\n\n私たちはカンボジアの首都・プノンペンにある日本人経営のキャバクラです🍸\n海外が初めての方でも安心して働けるように、航空券✈️や住まい🏠、生活サポートまで全部ご用意しています！\n\nお仕事はとってもシンプル✨\nお客様と楽しくおしゃべりしたり、一緒に乾杯したりすることがメインです🍹\n「会話に自信ないかも…」という方も、笑顔と気配りがあれば大丈夫🙆‍♀️💕\n語学レッスンや通訳サポートもあるので、自然に海外生活にも慣れていけます🌏\n\n💰お給料は月30万円〜100万円以上も可能！\n🍱日本人シェフによる本格和食の夕食無料\n🏊‍♀️ジム・プール・露天風呂も完備\nなどなど、働きやすい環境＆待遇が盛りだくさんです✨\n\n短期も長期も大歓迎🙌\n海外で新しいチャレンジをしてみたい方、楽しみながらしっかり稼ぎたい方、\nぜひCLUB KYOTOで一緒に働いてみませんか？💕\nご応募お待ちしています😊', 'はじめまして🌸\r\n「CLUB KYOTO」の求人ページをご覧いただきありがとうございます😊\r\n\r\n私たちはカンボジアの首都・プノンペンにある日本人経営のキャバクラです🍸\r\n海外が初めての方でも安心して働けるように、航空券✈️や住まい🏠、生活サポートまで全部ご用意しています！\r\n\r\nお仕事はとってもシンプル✨\r\nお客様と楽しくおしゃべりしたり、一緒に乾杯したりすることがメインです🍹\r\n「会話に自信ないかも…」という方も、笑顔と気配りがあれば大丈夫🙆‍♀️💕\r\n語学レッスンや通訳サポートもあるので、自然に海外生活にも慣れていけます🌏\r\n\r\n💰お給料は月30万円〜100万円以上も可能！\r\n🍱日本人シェフによる本格和食の夕食無料\r\n🏊‍♀️ジム・プール・露天風呂も完備\r\nなどなど、働きやすい環境＆待遇が盛りだくさんです✨\r\n\r\n短期も長期も大歓迎🙌\r\n海外で新しいチャレンジをしてみたい方、楽しみながらしっかり稼ぎたい方、\r\nぜひCLUB KYOTOで一緒に働いてみませんか？💕\r\nご応募お待ちしています😊', '🌸 海外キャバクラのお仕事って？<br>\r\nCLUB KYOTOでは、お客様と楽しくおしゃべりしたり、ドリンクを一緒に楽しんだりするのがメインのお仕事です。<br>\r\n「お酒に詳しくないとダメかな？」とか「会話に自信がない…」という方も大丈夫！<br>\r\nちょっとした気配りや笑顔があれば、自然とお客様に喜んでもらえます。<br>\r\n<br>\r\n💄 具体的にはこんな感じ<br>\r\n・お客様の隣に座ってお話しする<br>\r\n・ドリンクを作ったり、一緒に乾杯したり<br>\r\n・ときにはお客様からの指名やイベントで盛り上げることも♪<br>\r\n<br>\r\n✨ このお仕事のポイント<br>\r\n・ノルマや罰金はなく、安心して働けます<br>\r\n・英語やカンボジア語はできなくてもOK！通訳サポートあり<br>\r\n・海外ならではの非日常感を楽しみながら、しっかり稼げます<br>\r\n<br>\r\n💕 こんな方におすすめ<br>\r\n・海外で新しい生活を始めてみたい<br>\r\n・普通のバイトよりも高収入を目指したい<br>\r\n・楽しく働きながら語学やコミュニケーション力も身につけたい<br>\r\n<br>\r\n✈️ 渡航費も住まいもサポート完備なので、手ぶらで飛び込んできても大丈夫！<br>\r\n安心して海外デビューできる環境を用意しています。', 17, NULL, 'プノンペン', NULL, 'キャバクラ', NULL, 300000, 1000000, 'MONTH', '[\"航空チケット往復無料\",\"個室コンドミニアム無料(光熱費実費)\",\"露天風呂、ジム、プール完備\",\"VISA無料\",\"日払い有り\",\"夕食系列店日本人シェフ本格和食無料\",\"通訳サポート有り無料\",\"銀行口座開設サポート無料\",\"携帯SIMカード支給無料\",\"生活支援無料\",\"空港迎え無料\",\"英語、カンボジア語レッスン無料\"]', 'published', NULL, NULL, 0, '{\"period\":\"短期・長期共に大歓迎！\",\"hours\":\"20:00～2:00\",\"holiday\":\"無し\",\"job_code\":\"SAMPLE-1\",\"valid_through\":\"2025-10-31\",\"qualifications\":[\"18歳以上\",\"経験者優遇\"],\"home_sections\":[\"pickup\",\"overseas\",\"domestic\",\"popular\",\"long\",\"short\"],\"business_hours\":\"20:00～LAST\",\"regular_holiday\":\"月曜日\"}', NULL, '2025-09-26 17:35:34', '2025-10-02 22:06:26'),
(33, 'test1002', NULL, 'a', '', 'a', 'aaa', 'aaaaa', 20, NULL, 'バンコク', NULL, 'キャバクラキャスト', NULL, 300000, 2000000, 'MONTH', NULL, 'published', '2025-10-03 16:20:26', NULL, 0, '{\"home_sections\":[\"pickup\"]}', NULL, '2025-10-02 12:53:56', '2025-10-06 19:17:49');

-- --------------------------------------------------------

--
-- テーブルの構造 `job_images`
--

CREATE TABLE `job_images` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `image_url` varchar(512) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `job_images`
--

INSERT INTO `job_images` (`id`, `job_id`, `image_url`, `sort_order`, `created_at`) VALUES
(1, 1, 'https://placehold.co/400x300/9DF731/ffffff?text=JOB+1', 0, '2025-09-22 14:02:35'),
(2, 2, 'https://placehold.co/400x300/6C5245/ffffff?text=JOB+2', 0, '2025-09-22 14:02:35'),
(3, 3, 'https://placehold.co/400x300/BAD678/ffffff?text=JOB+3', 0, '2025-09-22 14:02:35'),
(4, 4, 'https://placehold.co/400x300/69AF0C/ffffff?text=JOB+4', 0, '2025-09-22 14:02:35'),
(5, 5, 'https://placehold.co/400x300/A1ECD0/ffffff?text=JOB+5', 0, '2025-09-22 14:02:35'),
(6, 6, 'https://placehold.co/400x300/BB936C/ffffff?text=JOB+1', 0, '2025-09-22 14:09:43'),
(7, 7, 'https://placehold.co/400x300/48FB19/ffffff?text=JOB+2', 0, '2025-09-22 14:09:43'),
(8, 8, 'https://placehold.co/400x300/07DA07/ffffff?text=JOB+3', 0, '2025-09-22 14:09:43'),
(9, 9, 'https://placehold.co/400x300/4C85C9/ffffff?text=JOB+4', 0, '2025-09-22 14:09:43'),
(10, 10, 'https://placehold.co/400x300/4BAB1E/ffffff?text=JOB+5', 0, '2025-09-22 14:09:43'),
(11, 11, 'https://placehold.co/400x300/975DAE/ffffff?text=JOB+1', 0, '2025-09-22 16:15:06'),
(12, 12, 'https://placehold.co/400x300/315BA3/ffffff?text=JOB+2', 0, '2025-09-22 16:15:06'),
(13, 13, 'https://placehold.co/400x300/2BADB7/ffffff?text=JOB+3', 0, '2025-09-22 16:15:06'),
(14, 14, 'https://placehold.co/400x300/B8AFE1/ffffff?text=JOB+4', 0, '2025-09-22 16:15:06'),
(15, 15, 'https://placehold.co/400x300/54939B/ffffff?text=JOB+5', 0, '2025-09-22 16:15:06'),
(16, 16, 'https://placehold.co/400x300/3A7E7A/ffffff?text=JOB+1', 0, '2025-09-22 16:29:51'),
(17, 17, 'https://placehold.co/400x300/CE88A7/ffffff?text=JOB+2', 0, '2025-09-22 16:29:51'),
(18, 18, 'https://placehold.co/400x300/30044D/ffffff?text=JOB+3', 0, '2025-09-22 16:29:51'),
(19, 19, 'https://placehold.co/400x300/421EB8/ffffff?text=JOB+4', 0, '2025-09-22 16:29:51'),
(20, 20, 'https://placehold.co/400x300/D577AB/ffffff?text=JOB+5', 0, '2025-09-22 16:29:51'),
(21, 21, 'https://placehold.co/600x400/9FE231/ffffff?text=JOB+21', 0, '2025-09-22 17:37:22'),
(22, 22, 'https://placehold.co/600x400/6A7FCB/ffffff?text=JOB+22', 0, '2025-09-22 17:37:22'),
(23, 23, 'https://placehold.co/600x400/98CBBA/ffffff?text=JOB+23', 0, '2025-09-22 17:37:22'),
(24, 24, 'https://placehold.co/600x400/75D5D9/ffffff?text=JOB+24', 0, '2025-09-22 17:37:22'),
(25, 25, 'https://placehold.co/600x400/4A9EDF/ffffff?text=JOB+25', 0, '2025-09-22 17:37:22'),
(26, 26, 'https://placehold.co/600x400/6B3ADA/ffffff?text=JOB+26', 0, '2025-09-22 17:37:22'),
(27, 27, 'https://placehold.co/400x300/6FE986/ffffff?text=JOB+1', 0, '2025-09-22 17:37:22'),
(28, 28, 'https://placehold.co/400x300/BFC3CD/ffffff?text=JOB+2', 0, '2025-09-22 17:37:22'),
(29, 29, 'https://placehold.co/400x300/4DA1FC/ffffff?text=JOB+3', 0, '2025-09-22 17:37:22'),
(30, 30, 'https://placehold.co/400x300/B273FA/ffffff?text=JOB+4', 0, '2025-09-22 17:37:22'),
(31, 31, 'https://placehold.co/400x300/37C4A8/ffffff?text=JOB+5', 0, '2025-09-22 17:37:22'),
(32, 21, 'https://t4.ftcdn.net/jpg/10/94/75/13/360_F_1094751365_H0VkmjzenKNJsrqkotApdo65cSs6OBpQ.jpg', 0, '2025-09-24 18:45:01'),
(35, 32, 'https://gaicaba-st.monochrome-inc.net/shop_images/3249.jpg', 0, '2025-09-26 18:01:00'),
(36, 32, 'https://gaicaba-st.monochrome-inc.net/shop_images/3250.jpg', 1, '2025-09-26 18:01:11'),
(37, 32, 'https://gaicaba-st.monochrome-inc.net/shop_images/3251.jpg', 2, '2025-09-26 18:01:20'),
(38, 32, 'https://gaicaba-st.monochrome-inc.net/shop_images/3252.jpg', 3, '2025-09-26 18:01:34'),
(39, 32, 'https://gaicaba-st.monochrome-inc.net/shop_images/3253.jpg', 4, '2025-09-26 18:01:41'),
(40, 33, 'https://placehold.co/1200x800/0ABAB5/ffffff?text=test1002+1', 1, '2025-10-03 20:01:24'),
(41, 33, 'https://placehold.co/1200x800/059669/ffffff?text=test1002+2', 2, '2025-10-03 20:01:24'),
(42, 33, 'https://placehold.co/1200x800/DC2626/ffffff?text=test1002+3', 3, '2025-10-03 20:01:24');

-- --------------------------------------------------------

--
-- テーブルの構造 `job_tag`
--

CREATE TABLE `job_tag` (
  `job_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `logo_url` varchar(512) DEFAULT NULL,
  `description_html` mediumtext DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `region_prefecture` varchar(64) DEFAULT NULL,
  `area_tag_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone_domestic` varchar(32) DEFAULT NULL,
  `phone_international` varchar(32) DEFAULT NULL,
  `business_hours` varchar(255) DEFAULT NULL,
  `holiday` varchar(255) DEFAULT NULL,
  `site_url` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `stores`
--

INSERT INTO `stores` (`id`, `name`, `slug`, `logo_url`, `description_html`, `country`, `region_prefecture`, `area_tag_id`, `address`, `phone`, `phone_domestic`, `phone_international`, `business_hours`, `holiday`, `site_url`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'サンプル店舗', 'sample-store', NULL, NULL, NULL, '沖縄', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 14:02:35', '2025-09-22 14:02:35'),
(6, 'CRAZY CAT\'S', 'crazy-cats-hanoi', 'https://placehold.co/600x400/f87171/ffffff?text=CRAZY+CAT\'S', '<p>安心の日本人オーナー店。無料英会話レッスン付きでスキルアップも！</p>', 'ベトナム', 'ハノイ', NULL, 'Hanoi Old Quarter', NULL, NULL, NULL, NULL, NULL, 'https://example.com/crazy-cats', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(7, 'Club Diamond Okinawa', 'club-diamond-okinawa', 'https://placehold.co/600x400/a5f3fc/0ABAB5?text=Diamond', '<p>最高のロケーションでリゾートバイトデビュー！</p>', '日本', '沖縄', NULL, '那覇市内', NULL, NULL, NULL, NULL, NULL, 'https://example.com/diamond', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(8, 'Sample Lounge Tokyo', 'sample-lounge-tokyo', 'https://placehold.co/600x400/c4b5fd/ffffff?text=Lounge', '<p>落ち着いた雰囲気で高収入が可能です。</p>', '日本', '東京', NULL, '六本木', NULL, NULL, NULL, NULL, NULL, 'https://example.com/lounge', NULL, '2025-09-22 17:37:22', '2025-09-22 17:37:22'),
(10, 'Bangkok Paradise', NULL, 'https://placehold.co/600x400/ff99c8/ffffff?text=Bangkok+Paradise', 'タイの首都バンコクにある人気店舗です。', 'タイ', 'バンコク', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(11, 'Cebu Beach Club', NULL, 'https://placehold.co/600x400/fcf6bd/ffffff?text=Cebu+Beach+Club', 'フィリピン・セブ島のビーチクラブです。', 'フィリピン', 'セブ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(12, 'Ho Chi Minh Lounge', NULL, 'https://placehold.co/600x400/d0f4de/ffffff?text=Ho+Chi+Minh+Lounge', 'ベトナム・ホーチミンのラウンジです。', 'ベトナム', 'ホーチミン', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(13, 'Singapore Elite', NULL, 'https://placehold.co/600x400/a9def9/ffffff?text=Singapore+Elite', 'シンガポールのエリート店舗です。', 'シンガポール', 'シンガポール', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(14, 'Phuket Resort', NULL, 'https://placehold.co/600x400/e4c1f9/ffffff?text=Phuket+Resort', 'タイ・プーケットのリゾート店舗です。', 'タイ', 'プーケット', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(15, 'Naha Central', NULL, 'https://placehold.co/600x400/fca5a5/ffffff?text=Naha+Central', '沖縄・那覇の中央店舗です。', '日本', '沖縄', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:09:05', '2025-09-24 19:09:05'),
(16, 'Sapporo Snow', NULL, 'https://placehold.co/600x400/fdba74/ffffff?text=Sapporo+Snow', '北海道・札幌のスノー店舗です。', '日本', '北海道', NULL, '東京都豊島区西池袋３丁目２５−２ 大晴ビル 2階', '08093647927', '08093647927', NULL, '20:00~', '火曜日', '', NULL, '2025-09-24 19:09:05', '2025-09-26 21:37:44'),
(17, 'CLUB KYOTO', NULL, NULL, '\r\n\r\nプノンペン キャリア採用のご案内\r\n<style>\r\n  body {\r\n    margin:0; padding:0; background:#f8fafc; color:#111827;\r\n    font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto,\r\n                 \"Hiragino Kaku Gothic ProN\", Meiryo, \"Noto Sans JP\", sans-serif;\r\n  }\r\n  table { border-collapse:collapse; width:100%; }\r\n  .wrap { max-width:640px; margin:0 auto; background:#ffffff; }\r\n  .section { padding:24px; }\r\n  .header { padding:28px 24px 16px; }\r\n  .brand { font-size:12px; color:#6b7280; letter-spacing:.12em; text-transform:uppercase; }\r\n  h1 { margin:8px 0 6px; font-size:22px; line-height:1.4; font-weight:700; color:#0f172a; }\r\n  .sub { font-size:14px; color:#6b7280; margin:0 0 16px; }\r\n  .divider { height:1px; background:#e5e7eb; }\r\n  h2 { font-size:17px; margin:0 0 12px; color:#0ea5e9; font-weight:700; }\r\n  p { font-size:14px; line-height:1.8; margin:0 0 14px; color:#111827; }\r\n  ul { padding-left:18px; margin:0 0 16px; }\r\n  li { font-size:14px; margin-bottom:6px; color:#111827; }\r\n  .note { margin-top:10px; padding:12px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; font-size:13px; color:#374151; }\r\n  .footer { padding:18px 24px 28px; font-size:12px; color:#6b7280; line-height:1.6; }\r\n</style>\r\n\r\n\r\n  \r\n    \r\n      \r\n        UNAGI × Washoku × Entertainment\r\n        プノンペンで始める新しいキャリアと暮らし\r\n        <p class=\"sub\">和の上質空間で安心して働けるキャバクラスタッフ募集</p>\r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        <p>\r\n          当店は、鰻料理や和食レストランの上品さとキャバクラのエンターテイメント性を融合させた\r\n          <strong>世界初のコンセプト店舗</strong>です。落ち着いた和モダンの店内で、\r\n          日本のおもてなし文化を海外のお客様に届けています。\r\n        </p>\r\n        <p>\r\n          <strong>海外キャバクラが初めての方も大歓迎</strong>です。\r\n          日本国内の一般的なナイトワーク経験だけでなく、\r\n          接客未経験の方にも基礎から丁寧に指導します。\r\n        </p>\r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        客層について\r\n        <p>\r\n          来店されるお客様は主に以下のような方々です。\r\n        </p>\r\n        <ul>\r\n          <li>現地在住または出張で訪れる<strong>日本人ビジネス層</strong></li>\r\n          <li>観光や接待で利用される<strong>カンボジア人富裕層やアジア各国の駐在員</strong></li>\r\n          <li>落ち着いた環境を求める<strong>ビジネスマン・経営者層</strong></li>\r\n        </ul>\r\n        <p>\r\n          客層は比較的落ち着いており、過度な接客や難しい外国語スキルは不要です。\r\n          <strong>日本語だけでの接客</strong>が中心ですので、語学に不安がある方も安心して働けます。\r\n        </p>\r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        安心のサポート体制\r\n        <p>\r\n          「海外生活は不安」という方も安心いただけるよう、生活面・お仕事面ともに\r\n          <strong>日本人スタッフが常駐し、手厚くサポート</strong>します。\r\n        </p>\r\n        <ul>\r\n          <li>到着時の空港送迎と生活立ち上げサポート</li>\r\n          <li>完全個室のコンドミニアム寮（Wi-Fi・家電完備）</li>\r\n          <li>プール・ジム付きでオフも充実</li>\r\n          <li>接客は日本語で対応可能</li>\r\n          <li>日々の相談も日本語で安心</li>\r\n        </ul>\r\n        \r\n          はじめは「お客様のグラスの扱い方」「笑顔の作り方」など基本動作から練習します。<br>\r\n          海外ナイトワーク未経験の方にも分かりやすく、一緒に成長できる環境です。\r\n        \r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        待遇・働きやすさ\r\n        <ul>\r\n          <li><strong>米ドル支給</strong>（円安の今はさらに高収入メリット）</li>\r\n          <li>日給保証＋各種バックで安定収入</li>\r\n          <li>短期（数か月）〜長期まで自由に選択可能</li>\r\n          <li>プノンペンは物価が安く生活費を抑えやすい</li>\r\n        </ul>\r\n        \r\n          海外就労が初めての方でも安心できるよう、給与・勤務時間・休日は事前に明確にご案内します。\r\n        \r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        こんな方におすすめ\r\n        <ul>\r\n          <li>海外で新しいキャリアを積みたい方</li>\r\n          <li>短期間でしっかり収入を得たい方</li>\r\n          <li>国際的な環境で接客スキルを伸ばしたい方</li>\r\n          <li>初めての海外生活を安心サポート付きで始めたい方</li>\r\n        </ul>\r\n      \r\n    \r\n\r\n    \r\n\r\n    \r\n      \r\n        本メールは日本国内の求職者向けの採用案内です。<br>\r\n        記載の条件は状況により変更される場合があります。詳細は面談時にご案内します。<br>\r\n        ご不明な点はお気軽にお問い合わせください。\r\n      \r\n    \r\n  \r\n\r\n\r\n', 'カンボジア', 'プノンペン', NULL, 'Phnom Penh, KH 120102 Phnom Penh, Cambodia 37A,St 306,Sangkat Boeung Keng kang1', '+855-87-636-944', '+855-87-636-944', '+855-87-636-944', '20:00～2:00', '無し', 'https://www.facebook.com/p/Club-Kyoto-61558105083416/?locale=ja_JP', NULL, '2025-09-26 10:37:25', '2025-09-29 10:10:52'),
(18, 'CLUB PREMIER', NULL, NULL, '日本の東京銀座、札幌、函館、千葉 海外ではシンガポール、バンコク、香港で キャバクラ、飲食店を数多く展開する シティーグループのベトナム店 PremiereVIETNAMとなります。 ベトナム1番の豪華内装と広々とした店内 ベトナム最多の席数と最多在籍で繰り広げる日本人キャバクラ‼ 海外最多数の店舗を抱えるグループ 世界No.1の実績とノウハウだからこそ安心、安全、稼げるベトナムNo1の給与システム、豪華な寮を完備。 キャバクラ経験や海外経験がなくても全面サポー ト致します。 アメリカやヨーロッパにも店舗展開予定。 海外での夢のライフスタイルを実現させ 海外の厳しいビザ環境の中でも ロングライフスタイルを楽しめます。', 'ベトナム', 'ホーチミン', NULL, '9 D. Le Thanh Ton, Ben Nghe, Quan 1, Thanh pho Ho Chi Minh,', '08038894752', '08038894752', '0902810544', '20:00〜2:00', '日曜日', 'https://www.instagram.com/premier_clubvn/', NULL, '2025-09-26 11:02:26', '2025-09-29 10:10:20'),
(19, 'CLUB PREMIER', NULL, NULL, '東京銀座、札幌、函館、千葉 海外では シンガポール、バンコク、香港、ベトナムで キャバクラを展開するシティーグループ海外のシンガポール店 Premiere SINGAPOREです。 海外最多数の店舗を誇る世界No.1グループ シンガポールでは人気店としての実績を誇り 1番稼げる給料システムとなっております。 寮に関しても豪華でサポートも充実 1番低価格の寮費に自信あり！ 現在4カ国に拠点があり今後更に店舗拡大中‼ たくさんの国へ回ることもできます！ 大手グループだからこそサポート充実 楽しく働け安心した海外生活を過ごせます。', 'シンガポール', 'シンガポール', NULL, '5 Koek Rd, #03-14/16 Cuppage Plaza, Singapore 228796', '+6580281681', '+6580281681', '+6580281681', '20:00~02:00', '日曜日', 'https://www.instagram.com/clubpremiersg/#', NULL, '2025-09-26 11:07:47', '2025-09-29 10:10:03'),
(20, 'Club Premier BKK', NULL, NULL, '日本の東京銀座、札幌、函館、千葉 海外ではシンガポール、香港、ベトナムでキャバクラ、飲食店を数多く展開する シティーグループのバンコク店 Premiere BKKとなります。 バンコク1番の豪華内装と広々とした店内 バンコク最多の席数と最多在籍で繰り広げる日本人キャバクラ‼ 海外最多数の店舗を抱えるグループ 世界No.1の実績とノウハウだからこそ安心、安全、稼げるバンコクNo1の給与システム、豪華な寮を完備。 キャバクラ経験や海外経験がなくても 全面サポート致します。 アメリカやヨーロッパにも店舗展開予定。 海外での夢のライフスタイルを実現させ 海外の厳しいビザ環境の中でも ロングライフスタイルを楽しめます。', 'タイ', 'バンコク', NULL, '9:53 Community Mall (3rd floor 124 Sukhumvit 53 Alley, Khlong Tan Nuea, Watthana, Bangkok 10110)', '+66979955433', '+66979955433', '097995543', '20:00～2:00', '日曜日', 'https://www.instagram.com/premier_bkk/', NULL, '2025-09-26 11:32:34', '2025-09-29 10:09:49'),
(21, 'CLUB PREMIER', NULL, NULL, '日本の東京銀座、札幌、函館、千葉 海外ではシンガポール、バンコク、ベトナムでキャバクラ、飲食店を数多く展開する シティーグループの香港店 Premiere HONG KONGとなります。 香港でワンランク上の⽉給・バックシステムを実現。 海外最多数の店舗を抱えるグループ、世界No.1の実績とノウハウだからこそ 安心、安全、稼げる香港No1の給与システム、豪華な寮を完備。 キャバクラ経験や海外経験がなくても 全面サポート致します。 アメリカやヨーロッパにも店舗展開予定。 海外での夢のライフスタイルを実現させ 海外の厳しいビザ環境の中でも ロングライフスタイルを楽しめます。', '中国', '香港', NULL, '#11F,Circle Plaza,499 Hennessy Road,CausewayBay Hong kong', '+886912821909', '+886912821909', '+886912821909', '-', '-', 'https://www.instagram.com/premier.hk/', NULL, '2025-09-26 11:35:06', '2025-09-29 05:33:34');

-- --------------------------------------------------------

--
-- テーブルの構造 `store_images`
--

CREATE TABLE `store_images` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `image_url` varchar(512) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `store_images`
--

INSERT INTO `store_images` (`id`, `store_id`, `image_url`, `sort_order`, `created_at`) VALUES
(3, 2, 'https://via.placeholder.com/400x300/3b82f6/ffffff?text=Store+Image+1', 0, '2025-09-24 18:56:50'),
(4, 2, 'https://via.placeholder.com/400x300/10b981/ffffff?text=Store+Image+2', 1, '2025-09-24 18:56:50'),
(5, 6, 'https://via.placeholder.com/400x300/f87171/ffffff?text=CRAZY+CAT+STORE', 0, '2025-09-24 19:03:25'),
(6, 7, 'https://via.placeholder.com/400x300/a5f3fc/0ABAB5?text=Diamond+Store', 0, '2025-09-24 19:03:25'),
(7, 8, 'https://via.placeholder.com/400x300/c4b5fd/ffffff?text=Lounge+Store', 0, '2025-09-24 19:03:25'),
(9, 17, 'https://gaicaba-st.monochrome-inc.net/cast_recruit/496-shop-0.jpg', 0, '2025-09-26 10:41:22'),
(10, 17, 'https://gaicaba-st.monochrome-inc.net/shop_images/3250.jpg', 1, '2025-09-26 10:59:30'),
(11, 17, 'https://gaicaba-st.monochrome-inc.net/shop_images/3251.jpg', 2, '2025-09-26 10:59:38'),
(12, 17, 'https://gaicaba-st.monochrome-inc.net/shop_images/3252.jpg', 3, '2025-09-26 10:59:47'),
(13, 17, 'https://gaicaba-st.monochrome-inc.net/shop_images/3253.jpg', 4, '2025-09-26 10:59:57'),
(14, 18, 'https://image.poste-vn.com/upload/vn/town-basic-info-logo/town_basic_info_logo_20250108_1736347072.3768.jpg', 0, '2025-09-26 11:04:39'),
(15, 18, 'https://image.poste-vn.com/upload/vn/town-guide-mid-description-image/town_guide_mid_description_image_20250108_1736347319.9357.jpg', 1, '2025-09-26 11:04:49'),
(16, 18, 'https://image.poste-vn.com/upload/vn/town-guide-mid-description-image/town_guide_mid_description_image_20250108_1736347266.1279.jpg', 2, '2025-09-26 11:05:00'),
(17, 18, 'https://gaicaba-st.monochrome-inc.net/shop_images/3659.jpg', 3, '2025-09-26 11:05:56'),
(18, 19, 'https://gaicaba-st.monochrome-inc.net/shop_images/2234.jpg', 0, '2025-09-26 11:09:43'),
(21, 19, 'https://helloasia-19f49.kxcdn.com/wp-content/uploads/%E3%82%AF%E3%83%A9%E3%83%96-%E3%83%97%E3%83%AC%E3%83%9F%E3%82%A2-%E3%82%B7%E3%83%B3%E3%82%AC%E3%83%9D%E3%83%BC%E3%83%AB-%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3-%E8%92%BC-%E3%83%96%E3%83%AB%E3%83%BC.jpg', 1, '2025-09-26 11:12:28'),
(22, 19, 'https://gaicaba-st.monochrome-inc.net/shop_images/2235.jpg', 2, '2025-09-26 11:13:15'),
(23, 19, 'https://gaicaba-st.monochrome-inc.net/shop_images/2238.jpg', 3, '2025-09-26 11:13:27'),
(25, 20, 'https://gaicaba-st.monochrome-inc.net/shop_images/2408.jpg', 0, '2025-09-26 11:32:53'),
(26, 20, 'https://gaicaba-st.monochrome-inc.net/shop_images/2409.jpg', 1, '2025-09-26 11:33:01'),
(27, 20, 'https://gaicaba-st.monochrome-inc.net/shop_images/2410.jpg', 2, '2025-09-26 11:33:08'),
(28, 20, 'https://gaicaba-st.monochrome-inc.net/shop_images/2411.jpg', 3, '2025-09-26 11:33:16'),
(29, 20, 'https://gaicaba-st.monochrome-inc.net/shop_images/2412.jpg', 4, '2025-09-26 11:33:25'),
(30, 21, 'https://gaicaba-st.monochrome-inc.net/shop_images/3684.jpg', 0, '2025-09-26 11:36:10'),
(31, 21, 'https://pbs.twimg.com/media/GUyRgdTb0AEeS1m?format=jpg&name=large', 1, '2025-09-26 11:37:34');

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `type` enum('job_feature','area','custom') NOT NULL DEFAULT 'job_feature',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `type`, `sort_order`, `created_at`) VALUES
(38, '未経験OK', 'ok', 'job_feature', 0, '2025-10-06 13:40:14'),
(39, '日払いOK', 'ok-2', 'job_feature', 1, '2025-10-06 13:40:25'),
(40, '前払い相談可', 'tag', 'job_feature', 2, '2025-10-06 13:40:43');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','editor','author','viewer') NOT NULL DEFAULT 'author',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$9VvBO0fZJn1.h0Ckof70RuAZ71ZKh34HR2P91blNVqzvOW9LW2VM6', 'admin', NULL, '2025-09-22 13:42:39', '2025-09-24 08:16:00');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `ad_banners`
--
ALTER TABLE `ad_banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ad_banners_sort` (`sort_order`,`id`),
  ADD KEY `idx_ad_banners_active` (`is_active`);

--
-- テーブルのインデックス `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_ann_published` (`published_at`),
  ADD KEY `idx_ann_deleted` (`deleted_at`),
  ADD KEY `fk_ann_author` (`author_user_id`);

--
-- テーブルのインデックス `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_job` (`job_id`),
  ADD KEY `fk_app_user` (`user_id`);

--
-- テーブルのインデックス `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_art_published` (`published_at`),
  ADD KEY `idx_art_deleted` (`deleted_at`),
  ADD KEY `fk_art_author` (`author_user_id`);

--
-- テーブルのインデックス `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assets_user` (`created_by`);

--
-- テーブルのインデックス `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audit_user` (`user_id`);

--
-- テーブルのインデックス `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_fav` (`user_id`,`job_id`),
  ADD KEY `fk_fav_job` (`job_id`);

--
-- テーブルのインデックス `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_jobs_region` (`region_prefecture`),
  ADD KEY `idx_jobs_employment` (`employment_type`),
  ADD KEY `idx_jobs_salary` (`salary_min`),
  ADD KEY `idx_jobs_store` (`store_id`),
  ADD KEY `idx_jobs_published` (`published_at`),
  ADD KEY `idx_jobs_deleted` (`deleted_at`),
  ADD KEY `fk_jobs_author` (`author_user_id`),
  ADD KEY `idx_jobs_message` (`message_text`(255));

--
-- テーブルのインデックス `job_images`
--
ALTER TABLE `job_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_job_images_job` (`job_id`);

--
-- テーブルのインデックス `job_tag`
--
ALTER TABLE `job_tag`
  ADD PRIMARY KEY (`job_id`,`tag_id`),
  ADD KEY `fk_job_tag_tag` (`tag_id`);

--
-- テーブルのインデックス `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_stores_region` (`region_prefecture`),
  ADD KEY `idx_stores_area_tag_id` (`area_tag_id`);

--
-- テーブルのインデックス `store_images`
--
ALTER TABLE `store_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_store_images_store` (`store_id`);

--
-- テーブルのインデックス `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `ad_banners`
--
ALTER TABLE `ad_banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- テーブルの AUTO_INCREMENT `job_images`
--
ALTER TABLE `job_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- テーブルの AUTO_INCREMENT `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- テーブルの AUTO_INCREMENT `store_images`
--
ALTER TABLE `store_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- テーブルの AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_ann_author` FOREIGN KEY (`author_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_app_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_art_author` FOREIGN KEY (`author_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `fk_assets_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_fav_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_author` FOREIGN KEY (`author_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jobs_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- テーブルの制約 `job_images`
--
ALTER TABLE `job_images`
  ADD CONSTRAINT `fk_job_images_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `job_tag`
--
ALTER TABLE `job_tag`
  ADD CONSTRAINT `fk_job_tag_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_job_tag_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `fk_stores_area_tag` FOREIGN KEY (`area_tag_id`) REFERENCES `tags` (`id`);

--
-- テーブルの制約 `store_images`
--
ALTER TABLE `store_images`
  ADD CONSTRAINT `fk_store_images_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
