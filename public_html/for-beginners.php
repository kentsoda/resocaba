<!DOCTYPE html>
<html lang="ja">
<?php
  $title = '初めての方｜海外リゾキャバ求人.COM';
  $description = '海外キャバクラで初めて働きたい方向けの完全ガイド';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'article';
  $og_url = 'https://resocaba-info.com/for-beginners/';
  require_once __DIR__ . '/includes/header.php';
?>
<style>
            :root {
                --brand: #00bfa6;
                --ink: #222;
                --muted: #667085;
                --bg: #ffffff;
                --card: #f7f7f8;
                --border: #e6e6ea;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                background: var(--bg);
                color: var(--ink);
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Kaku Gothic ProN", "Noto Sans JP", "Yu Gothic", "Helvetica Neue", Arial, sans-serif;
                line-height: 1.75
            }

            :root {
                --text-primary: #1e293b;
                --text-secondary: #475569;
                --bg-base: #f1f5f9;
                --bg-surface: #ffffff;
                --bg-muted: #f1f5f9;
                --border-color: #e2e8f0;
                --brand-primary: #0ABAB5;
            }

            .container {
                max-width: 1080px;
                margin-inline: auto;
                padding: 24px
            }

            .hero {
                display: grid;
                gap: 20px;
                padding: 28px;
                border-radius: 16px;
                /* background: linear-gradient(180deg, #f0fffc 0%, #ffffff 60%); */
                border: 1px solid var(--border)
            }

            .eyebrow {
                color: var(--brand);
                font-weight: 700;
                letter-spacing: .06em;
                font-size: .9rem
            }

            h1 {
                font-size: clamp(2.0rem, 2.8vw, 3.0rem) !important;
                line-height: 1.3;
                margin: 0;
                color: #0f172a;
                border-bottom: 3px solid #00bfa6;
                padding-bottom: 12px
            }

            .lead {
                color: #374151;
                margin: 8px 0 0
            }

            .hero .cta {
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
                margin-top: 8px;
                justify-content: center
            }

            /* 中央寄せ */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 12px 18px;
                border-radius: 12px;
                font-weight: 700;
                text-decoration: none;
                border: 1px solid transparent
            }

            .btn-primary {
                background: var(--brand);
                color: #fff
            }

            nav.toc {
                margin: 28px 0;
                border: 1px solid var(--border);
                border-radius: 14px;
                background: #fff
            }

            nav.toc h2 {
                font-size: 1.15rem;
                font-weight: 700;
                margin: 0;
                padding: 14px 18px;
                border-bottom: 1px solid var(--border)
            }

            nav.toc ol {
                margin: 0;
                padding: 12px 24px 16px 32px;
                list-style-type: decimal
            }

            nav.toc li::marker {
                color: #000;
                font-weight: 700
            }

            nav.toc a {
                color: #00bfa6;
                text-decoration: none
            }

            section {
                margin: 40px 0
            }

            section h2 {
                font-size: 1.6rem;
                font-weight: 700;
                margin: 0 0 10px;
                border-bottom: 2px solid #00bfa6;
                padding-bottom: 8px
            }

            .subtitle {
                color: var(--muted);
                margin: 0 0 16px
            }

            .grid {
                display: grid;
                gap: 20px
            }

            @media(min-width:768px) {
                .grid-2 {
                    grid-template-columns: 1.1fr .9fr
                }
            }

            .card {
                background: #eff8f7;
                border: 1px solid #00bfa6;
                padding: 20px
            }

            .card h3 {
                font-size: 1.6rem;
                font-weight: 700;
                color: #00bfa6
            }

            .card p {
                font-size: 1.5rem;
                line-height: 1.6;
                margin-top: 1.6rem
            }

            .card p + p {
                margin-top: 1.6rem
            }

            .card[style*="margin-top:18px"] {
                background: transparent;
                border: none
            }

            .card[style*="margin-top:18px"] li {
                font-size: 1.5rem;
                line-height: 1.6;
                margin-bottom: 1rem
            }

            ul.clean {
                margin: 0;
                padding-left: 0
            }

            ul.clean li {
                display: flex;
                gap: 12px;
                align-items: flex-start;
                margin-bottom: 1rem
            }

            ul.clean li b {
                color: #00bfa6;
                font-weight: 700;
                flex-shrink: 0;
                width: 30%;
                display: flex;
                justify-content: space-between;
                align-items: center
            }

            ul.clean li b span {
                flex: 1
            }

            /* 画像プレースホルダー */
            figure {
                margin: 0;
                border: 1px dashed var(--border);
                border-radius: 12px;
                overflow: hidden;
                background: #fff
            }

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
            .ph--9 { background-image: url("../assets/images/for-beginners/5つの魅力3.webp"); }
            .ph--10 { background-image: url("../assets/images/for-beginners/5つの魅力4.webp"); }
            .ph--11 { background-image: url("../assets/images/for-beginners/5つの魅力5.webp"); }
            .ph--12 { background-image: url("../assets/images/for-beginners/インタビュー例3.webp"); }
            .ph--13 { background-image: url("../assets/images/for-beginners/インタビュー例4.webp"); }
            .ph--14 { background-image: url("../assets/images/for-beginners/タイ.webp"); }
            .ph--15 { background-image: url("../assets/images/for-beginners/マレーシア.webp"); }
            
            figcaption {
                font-size: .9rem;
                color: #475569;
                padding: 10px 12px;
                background: #f9fafb;
                border-top: 1px dashed var(--border)
            }

            /* メリット：画像→文面→画像→文面… */
            .merits {
                display: grid;
                gap: 24px
            }

            .merit-row {
                display: grid;
                gap: 14px
            }

            @media(min-width:900px) {
                .merit-row {
                    grid-template-columns: 1.05fr 1fr;
                    align-items: start
                }
            }

            .merit-row.reverse {
                direction: rtl
            }

            .merit-row.reverse>* {
                direction: ltr
            }

            .merit-text {
                background: #fff;
                border-radius: 14px;
                padding: 16px
            }

            .merit-text h3 {
                font-size: 1.6rem;
                font-weight: 700;
                margin: 0 0 6px;
                background: #eff8f7;
                border-left: 4px solid #00bfa6;
                padding: 8px 12px;
                color: #00bfa6
            }

            .merit-text p {
                font-size: 1.5rem;
                line-height: 1.6;
                margin-top: 1.6rem
            }

            .merit-text p + p {
                margin-top: 1.6rem
            }

            /* インタビュー */
            .stack {
                display: grid;
                gap: 20px
            }

            .stack .block {
                display: grid;
                gap: 10px
            }

            @media(min-width:840px) {
                .stack .block {
                    grid-template-columns: 1fr 1.2fr;
                    align-items: start
                }
            }

            .stack .card h3 {
                margin-top: 0
            }

            /* 国エリア */
            .country {
                display: grid;
                gap: 16px
            }

            @media(min-width:840px) {
                .country {
                    grid-template-columns: 1fr 1.1fr
                }
            }

            .country h3 {
                font-size: 1.6rem;
                font-weight: 700;
                color: #00bfa6;
                margin-bottom: 1rem;
                background: #eff8f7;
                border-left: 4px solid #00bfa6;
                padding: 8px 12px
            }

            .country p {
                font-size: 1.5rem;
                line-height: 1.6;
                margin-bottom: 1rem
            }

            /* CTA */
            .cta-band {
                display: grid;
                gap: 12px;
                align-items: center;
                grid-template-columns: 1fr;
                padding: 16px;
                border: 1px solid #00bfa6;
                background: #eff8f7;
                text-align: center
            }


            .cta-band h3 {
                margin: 0;
                font-size: 1.6rem
            }

            .cta-band .btn {
                padding: 16px 24px;
                font-size: 1.2rem
            }

            /* FAQ 表示調整 */
            details {
                background: #fff;
                border: 1px solid #00bfa6;
                margin: 10px 0
            }

            summary {
                cursor: pointer;
                padding: 16px 20px;
                font-weight: 700;
                background: #00bfa6;
                color: #fff;
                font-size: 1.1rem
            }

            details[open] summary {
                border-bottom: 1px solid var(--border)
            }

            .a {
                padding: 16px 24px;
                color: #374151;
                font-size: 1.1rem
            }

            /* Flow timeline */
            .flow-steps { list-style: none; margin: 0; padding: 0 }
            .flow-step { display: grid; grid-template-columns: 56px 1fr; gap: 20px; margin: 18px 0; align-items: start; min-height: 80px }
            .flow-step__icon { position: relative; width: 56px; height: 56px; border-radius: 9999px; background: var(--brand-primary); display: flex; align-items: center; justify-content: center; color: #fff }
            .flow-step__icon svg { width: 26px; height: 26px }
            .flow-step:not(:last-child) .flow-step__icon::after { content: ""; position: absolute; left: 50%; top: 56px; transform: translateX(-50%); width: 0; height: calc(100% + 18px); border-left: 2px dashed var(--brand-primary) }
            .flow-step__title { margin: 0 0 6px; font-weight: 700; color: var(--brand-primary); font-size: 1.2rem }
            .flow-step__desc { margin: 0; background: #eff8f7; border: 1px solid #aceae2; padding: 8px 12px }
            @media(min-width:900px) { .flow-step { grid-template-columns: 64px 1fr } .flow-step__icon { width: 64px; height: 64px } .flow-step:not(:last-child) .flow-step__icon::after { top: 64px } }

            footer {
                margin: 52px 0 24px;
                color: #6b7280;
                font-size: .9rem
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="app">
            <?php require_once __DIR__ . '/includes/menu.php'; ?>
            <header class="container hero mt-10">
                <span class="eyebrow">初めての方</span>
                <h1>海外キャバクラで“はじめて”働くあなたへ｜魅力・応募の流れ・Q&Aガイド</h1>
                <figure style="margin:8px 0 4px">
                    <div class="ph ph--1" aria-hidden="true"></div>
                </figure>
                <p class="lead">
                    「海外でちょっと働いて、ついでに旅行も楽しみたい」「できれば楽に、サクッと稼ぎたい」——そんなあなた向けのページです。まずは“海外キャバクラのいいところ”をぎゅっと紹介。“応募〜お仕事スタート”までの流れとQ&Aを、カンタンにまとめました。エントリーも相談も無料です👌
                </p>
                <p class="lead" style="margin-top:-4px;color:#475569">
                    <small>※ご案内のサポート内容・待遇・働き方・期間などは<b>求人・時期・エリアによって異なります</b>。最新の条件は各求人ページや担当からのご連絡でご確認ください。</small></p>
                <div class="cta">
                    <a class="btn btn-primary" href="/jobs">求人を探す</a>
                </div>
            </header>
            <main class="container">
                <nav class="toc" aria-label="目次">
                    <h2>この記事の目次</h2>
                    <ol>
                        <li><a href="#merit">海外キャバクラで働く“5つの魅力”</a></li>
                        <li><a href="#voice">どんな人がどれくらい働いてる？</a></li>
                        <li><a href="#area">働ける主な国・エリアの例</a></li>
                        <li><a href="#flow">応募〜勤務開始までの流れ</a></li>
                        <li><a href="#life">現地生活のイメージ</a></li>
                        <li><a href="#faq">よくある質問（Q&A）</a></li>
                    </ol>
                </nav>
                <section id="merit">
                    <h2>1. 海外キャバクラで働く“5つの魅力”</h2>
                    <p class="subtitle">旅行×お仕事のいいとこ取り。未経験でもはじめやすく、ムリなく続けやすい環境が選べます。</p>
                    <div class="merits">
                        <div class="merit-row">
                            <figure>
                                <div class="ph ph--2" aria-hidden="true"></div>
                            </figure>
                            <div class="merit-text">
                                <h3>旅行とお仕事を両立できる</h3>
                                <p>昼は観光、夜はお仕事——“稼ぎながら旅する”スタイルが叶います。海・街・夜景・グルメ…気分でエリアを選べるのも楽しい。</p>
                                <p>休日は人気スポットのハシゴや、映える写真スポット巡りも◎。思い出づくりと貯金が同時に進みます。</p>
                            </div>
                        </div>
                        <div class="merit-row reverse">
                            <figure>
                                <div class="ph ph--3" aria-hidden="true"></div>
                            </figure>
                            <div class="merit-text">
                                <h3>未経験からはじめやすい</h3>
                                <p>“笑顔でおしゃべり”がいちばんの武器。初めての人でも入り口は広く、先輩のコツを真似しながら少しずつ慣れていけます。</p>
                                <p>まずは短期で雰囲気をつかんで、気に入ったら延長や再渡航へ…というステップも定番です。</p>
                            </div>
                        </div>
                        <div class="merit-row">
                            <figure>
                                <div class="ph ph--9" aria-hidden="true"></div>
                            </figure>
                            <div class="merit-text">
                                <h3>ムリのない働き方</h3>
                                <p>私服OKやノンアル対応など“働きやすさ重視”の雰囲気。がっつり営業よりも「楽しくおしゃべり」を大切にしているお店が多めです。</p>
                                <p>シフトは事前に組まれるから、遊ぶ日・休む日のバランスを取りやすく、生活リズムも整えやすい。</p>
                            </div>
                        </div>
                        <div class="merit-row reverse">
                            <figure>
                                <div class="ph ph--10" aria-hidden="true"></div>
                            </figure>
                            <div class="merit-text">
                                <h3>生活コストをおさえやすい</h3>
                                <p>“寮・食事サポート・空港送迎”などがセットの募集もあるので、初期費用をグッと抑えやすいのがうれしい。</p>
                                <p>家賃や光熱費の負担が軽いぶん、手元にお金が残りやすい実感が持てます。</p>
                            </div>
                        </div>
                        <div class="merit-row">
                            <figure>
                                <div class="ph ph--11" aria-hidden="true"></div>
                            </figure>
                            <div class="merit-text">
                                <h3>身バレの心配をしにくい環境</h3>
                                <p>海外でのお仕事だから、地元の知り合いに会う心配はほぼナシ。プライベートとお仕事をすっきり分けたい人にも向いています。</p>
                                <p>SNSの見せ方や距離感は、先輩の小ワザを聞きながら上手にコントロールできます。</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="voice">
                    <h2>2. どんな人がどれくらい働いてる？</h2>
                    <div class="stack">
                        <div class="block">
                            <figure>
                                <div class="ph ph--4"></div>
                            </figure>
                            <article class="card">
                                <h3>例①：21歳／学生（春休み2週間）</h3>
                                <p>「友だちと2人で挑戦しました。昼はカフェ巡り、夜はお仕事でメリハリがついて、短期でも“思ったよりちゃんと貯金できた”のが嬉しかったです。担当さんが相談に乗ってくれたので不安もすぐ解消できました。次は夏休みにもう少し長めで行きたい！」
                                </p>
                            </article>
                        </div>
                        <div class="block">
                            <figure>
                                <div class="ph ph--5"></div>
                            </figure>
                            <article class="card">
                                <h3>例②：24歳／フリーター（1か月）</h3>
                                <p>「“海外で住む”を一回やってみたくて来ました。生活まわりのサポートが整った求人を選んだので、着いた日からスムーズに動けました。オフは海沿いでのんびりして、気持ちもリフレッシュ。帰国後に再渡航を相談中です！」
                                </p>
                            </article>
                        </div>
                        <div class="block">
                            <figure>
                                <div class="ph ph--12"></div>
                            </figure>
                            <article class="card">
                                <h3>例③：27歳／転職前の有休消化（3週間）</h3>
                                <p>「次の仕事が始まるまでの間は、夜だけ働くスタイルにしました。体調も整えやすくて、短期でも常連さんができました。最終日に“また来てね”って言われたのが一番うれしかったです。短期間でもちゃんと手応えがありました。」</p>
                            </article>
                            </div>
                            <div class="block">
                            <figure>
                                <div class="ph ph--13"></div>
                            </figure>
                            <article class="card">
                                <h3>例④：29歳／アパレル販売経験あり（6週間）</h3>
                                <p>「接客の経験がそのまま活きました。英語は挨拶レベルからでしたが、先輩のフレーズを真似してるうちに後半は指名も増えて、自信がつきました。観光も仕事も充実して、“もう一度来たい”って素直に思えました。」</p>
                            </article>
                        </div>
                    </div>
                </section>
                <section id="area">
                    <h2>3. 働ける主な国・エリアの例</h2>
                    <div class="country">
                        <figure>
                            <div class="ph ph--6"></div>
                        </figure>
                        <div>
                            <h3>シンガポール（都会×夜景）</h3>
                            <p>夜景やショッピング、清潔感のある街並みが魅力。きれいめ私服や落ち着いた接客スタイルの募集が見つかりやすいです。</p>
                            <p>街歩きだけでも楽しく、カフェや屋上バーなど写真が映えるスポットも豊富。短期でも満足度が高いエリアです。</p>
                            <p>交通の便がよく移動がラクなので、限られたオフでも予定が組みやすいのがうれしい。夜のライトアップは思わず写真を撮りたくなるはず。</p>
                            <p>ごはんも多国籍で選び放題。気分に合わせて毎日違う味を楽しめます。</p>
                        </div>
                    </div>
                    <div class="country">
                        <figure>
                            <div class="ph ph--7"></div>
                        </figure>
                        <div>
                            <h3>ベトナム（ホーチミン／ハノイ）</h3>
                            <p>カフェやごはんが豊富で“毎日ちょっと楽しい”。コンドミニアム系の寮や送迎がセットになった求人も探しやすい印象です。</p>
                            <p>物価も比較的やさしめで、オフのカフェ巡りや雑貨屋さん探しがはかどります。街の活気に元気をもらえるはず。</p>
                            <p>写真が映える壁アートやローカル市場も多く、休日の散策がほんとうに充実。気づいたら常連のカフェができてます。</p>
                            <p>屋台のバインミーや生春巻きなど、手軽でおいしいローカル飯も楽しみのひとつ。</p>
                        </div>
                    </div>
                    <div class="country">
                        <figure>
                            <div class="ph ph--14"></div>
                        </figure>
                        <div>
                        <h3>タイ（バンコク／リゾート）</h3>
                        <p>休日の観光ネタが尽きない人気エリア。衣装はドレス系〜私服系まで幅広く、写真映えスポットも多くて遊び場に困りません。</p>
                        <p>屋台グルメやナイトマーケットも楽しく、昼夜どちらも充実。リピーターが多いのも納得のロケーションです。</p>
                        <p>マッサージやスパも手頃で、オフのリフレッシュにぴったり。気軽に“自分メンテ”ができるのが最高。</p>
                        <p>雨でも楽しめる大型モールや水上マーケットなど、気分に合わせて遊び方を選べます。</p>
                        </div>
                    </div>
                    <div class="country">
                        <figure>
                            <div class="ph ph--15"></div>
                        </figure>
                        <div>
                        <h3>マレーシア（クアラルンプールなど）</h3>
                        <p>大型モールから屋台まで“便利さ×多国籍感”が魅力。日本語の生活情報も集めやすく、初めてでも動きやすいです。</p>
                        <p>雨でも遊べる屋内スポットが多いので、オフの選択肢が広め。暮らしと遊びのバランスが取りやすいエリアです。</p>
                        <p>広くて快適なカフェが多く、仕事前のひと息や休日のパソコン作業にも◎。街の夜景もキレイで、気分転換にちょうどいい。</p>
                        <p>配車アプリで移動しやすく、はじめての場所でも迷いにくいのが助かります。</p>
                        </div>
                    </div>
                </section>
                <section id="flow">
                    <h2>4. 応募〜勤務開始までの流れ</h2>
                    <div class="grid grid-2">
                        <ol class="flow-steps">
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="search"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 1：条件に合った求人情報を探す</h3>
                                    <p class="flow-step__desc">当サイトの求人情報から自分が行きたい国、期間とマッチした求人情報をチェック！</p>
                                </div>
                            </li>
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="id-card"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 2：プロフィール登録</h3>
                                    <p class="flow-step__desc">簡単な情報を入力してエントリーを行います。<br>新規登録すると次回以降の情報入力が全て自動化されます。</p>
                                </div>
                            </li>
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="video"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 3：オンライン面談</h3>
                                    <p class="flow-step__desc">不安や質問をまとめて解消。<br>働き方・生活面・準備物をわかりやすく説明。</p>
                                </div>
                            </li>
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="building-2"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 4：お店のご紹介・比較</h3>
                                    <p class="flow-step__desc">寮・送迎・給与内訳・衣装ルールを比べて、<br>あなたに合うお店を一緒に決定。</p>
                                </div>
                            </li>
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="check-circle-2"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 5：決定＆準備</h3>
                                    <p class="flow-step__desc">スケジュールが合えばスピード決定も。<br>持ち物リストと到着日の動き方を共有。</p>
                                </div>
                            </li>
                            <li class="flow-step">
                                <div class="flow-step__icon" aria-hidden="true"><i data-lucide="plane"></i></div>
                                <div class="flow-step__body">
                                    <h3 class="flow-step__title">Step 6：現地到着→スタート</h3>
                                    <p class="flow-step__desc">空港お迎えや入寮案内、初日の説明がセットの求人も。<br>初日で生活とお仕事の流れをつかめます。</p>
                                </div>
                            </li>
                        </ol>
                    </div>
                </section>
                <section id="life">
                    <h2>5. 現地生活のイメージ</h2>
                    <div class="grid" style="grid-template-columns:1fr;gap:18px">
                        <figure>
                            <div class="ph ph--8"></div>
                        </figure>
                    </div>
                    <div class="card" style="margin-top:18px">
                        <ul class="clean">
                            <li><b><span>寮</span>：</b>清潔なお部屋にWi-Fi・エアコン完備の物件が人気。<br>スーパーやカフェが近い立地だと毎日がさらにラク。</li>
                            <li><b><span>送迎</span>：</b>通勤は専用車でサクッと移動の求人もあり。<br>夜道の移動も安心感があって続けやすい。</li>
                            <li><b><span>サポート</span>：</b>到着日から担当がチャットでフォローの体制がある求人も。<br>美容院・ネイル・両替スポットなど生活情報も共有。</li>
                            <li><b><span>働き方</span>：</b>私服OK／ドレス支給／ヘアメイクあり等、スタイルはいろいろ。<br>自分に合う“ムリのないスタンス”で続けやすい。</li>
                        </ul>
                    </div>
                </section>
                <section class="cta-band">
                    <h3>まずは相談だけでもOK。<br>あなたに合う“ラクに続けやすい”働き方を一緒に。</h3>
                    <div><a class="btn btn-primary" href="/jobs">求人を探す</a></div>
                </section>
                <section id="faq">
                    <h2>6. よくある質問（Q&A）</h2>
                    <details>
                        <summary>未経験でも大丈夫？</summary>
                        <div class="a">大丈夫。まずは笑顔でおしゃべりできればOK。接客のコツは面談でしっかり共有します。</div>
                    </details>
                    <details>
                        <summary>お酒が弱いんですが…</summary>
                        <div class="a">ノンアルや軽めのドリンクで働いている方も多いです。自分のペースで無理なく続けられます。</div>
                    </details>
                    <details>
                        <summary>英語がほとんど話せません</summary>
                        <div class="a">挨拶レベルからスタートした先輩もたくさん。よく使うフレーズは面談でお渡しします。</div>
                    </details>
                    <details>
                        <summary>どのくらいの期間から行ける？</summary>
                        <div class="a">まずは1〜2週間の“お試し”が人気。気に入ったら延長や再渡航も相談できます。</div>
                    </details>
                    <details>
                        <summary>到着してからの流れが不安</summary>
                        <div class="a">空港お迎え→入寮→店舗案内→シフト説明までがセットになった求人もあります。初日で生活のリズムをつかめるので安心です。</div>
                    </details>
                    <script type="application/ld+json">
      {
        "@context":"https://schema.org",
        "@type":"FAQPage",
        "mainEntity":[
          {"@type":"Question","name":"未経験でも大丈夫？","acceptedAnswer":{"@type":"Answer","text":"未経験でもOK。まずは笑顔でおしゃべりできればOK。接客のコツは面談で共有します。"}},
          {"@type":"Question","name":"お酒が弱いんですが…","acceptedAnswer":{"@type":"Answer","text":"ノンアルや軽めのドリンクで働いている方も多いです。自分のペースで無理なく続けられます。"}},
          {"@type":"Question","name":"英語がほとんど話せません","acceptedAnswer":{"@type":"Answer","text":"挨拶レベルからスタートした先輩もたくさん。よく使うフレーズは面談でお渡しします。"}},
          {"@type":"Question","name":"どのくらいの期間から行ける？","acceptedAnswer":{"@type":"Answer","text":"まずは1〜2週間の“お試し”が人気。気に入ったら延長や再渡航も相談できます。"}},
          {"@type":"Question","name":"到着してからの流れが不安","acceptedAnswer":{"@type":"Answer","text":"空港お迎え→入寮→店舗案内→シフト説明までがセットになった求人もあります。初日で生活のリズムをつかめます。"}}
        ]
      }
      </script>
                </section>
            </main>
            <?php require_once __DIR__ . '/includes/footer.php'; ?>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.lucide && typeof lucide.createIcons === 'function') {
                    lucide.createIcons();
                }
            });
        </script>
    </body>
</html>