
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>初めての方｜海外リゾキャバ求人.COM</title>
        <meta name="description" content="海外キャバクラで初めて働きたい方向けの完全ガイド" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/lucide@latest" defer></script>
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
                font-size: clamp(1.6rem, 2.2vw, 2.2rem);
                line-height: 1.3;
                margin: 0;
                color: #0f172a
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
                font-size: 1.05rem;
                margin: 0;
                padding: 14px 18px;
                border-bottom: 1px solid var(--border)
            }

            nav.toc ol {
                margin: 0;
                padding: 12px 24px 16px 32px
            }

            nav.toc a {
                color: #0f172a;
                text-decoration: none
            }

            section {
                margin: 40px 0
            }

            section h2 {
                font-size: 1.4rem;
                margin: 0 0 10px
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
                background: var(--card);
                border: 1px solid var(--border);
                border-radius: 14px;
                padding: 20px
            }

            ul.clean {
                margin: 0;
                padding-left: 1.1em
            }

            ul.clean li {
                margin: 10px 0
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
                    align-items: center
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
                border: 1px solid var(--border);
                border-radius: 14px;
                padding: 16px
            }

            .merit-text h3 {
                font-size: 1.06rem;
                margin: 0 0 6px
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

            /* CTA */
            .cta-band {
                display: grid;
                gap: 12px;
                align-items: center;
                grid-template-columns: 1fr;
                padding: 16px;
                border: 1px solid var(--border);
                border-radius: 16px;
                background: linear-gradient(180deg, #f0fffc 0%, #ffffff 70%)
            }

            @media(min-width:720px) {
                .cta-band {
                    grid-template-columns: 1fr auto
                }
            }

            .cta-band h3 {
                margin: 0;
                font-size: 1.2rem
            }

            /* FAQ 表示調整 */
            details {
                background: #fff;
                border: 1px solid var(--border);
                border-radius: 12px;
                margin: 10px 0
            }

            summary {
                cursor: pointer;
                padding: 12px 14px;
                font-weight: 700
            }

            details[open] summary {
                border-bottom: 1px solid var(--border)
            }

            .a {
                padding: 12px 14px;
                color: #374151
            }

            footer {
                margin: 52px 0 24px;
                color: #6b7280;
                font-size: .9rem
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="app">
            <!-- Site Header -->
            <header id="header"
                class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 border-b border-[var(--border-color)] transition-all duration-300">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20">
                        <div class="flex-shrink-0">
                            <a href="/" class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[var(--brand-primary)] flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                    </svg>
                                </div>
                                <span
                                    class="font-bold text-lg text-[var(--text-primary)] tracking-wide">海外リゾキャバ求人.COM</span>
                            </a>
                        </div>
                        <nav class="hidden lg:flex items-center gap-x-6">
                            <a href="/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">トップ</a>
                            <a href="/for-beginners/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">初めての方</a>
                            <a href="/jobs/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">求人検索</a>
                            <a href="/partners/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
                            <a href="/announcements/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
                            <a href="/features/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">特集・コラム</a>
                            <a href="/faq/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">よくある質問</a>
                            <a href="/contact-ad/"
                                class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">広告掲載</a>
                        </nav>
                        <div class="hidden lg:flex items-center gap-x-3">
                            <a href="/login/"
                                class="px-5 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] transition-colors">ログイン</a>
                            <a href="/register/"
                                class="px-5 py-2 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">無料登録</a>
                        </div>
                        <button id="mobile-menu-button" aria-label="メニューを開く"
                            class="lg:hidden p-2 text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--brand-primary)]">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-[var(--border-color)]">
                        <nav class="flex flex-col p-4 gap-y-3">
                            <a href="/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">トップ</a>
                            <a href="/for-beginners/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">初めての方</a>
                            <a href="/jobs/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">求人検索</a>
                            <a href="/partners/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">掲載店舗</a>
                            <a href="/announcements/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">お知らせ</a>
                            <a href="/features/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">特集・コラム</a>
                            <a href="/faq/"
                                class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">よくある質問</a>
                            <div class="flex items-center gap-x-3 pt-3 mt-3 border-t border-[var(--border-color)]">
                                <a href="/login/"
                                    class="flex-1 text-center px-4 py-2.5 text-sm font-semibold border border-[var(--border-color)] text-[var(--text-secondary)] bg-white hover:bg-[var(--bg-muted)] transition-colors">ログイン</a>
                                <a href="/register/"
                                    class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">無料登録</a>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
            <header class="container hero mt-10">
                <span class="eyebrow">初めての方</span>
                <h1>海外キャバクラで“はじめて”働くあなたへ｜魅力・応募の流れ・Q&Aガイド</h1>
                <figure style="margin:8px 0 4px">
                    <div class="ph ph--1" aria-hidden="true"></div>
                    <figcaption>ヒーロー画像：海辺・夜景・街歩きなど「旅×しごと」をイメージできる横長写真（16:9）</figcaption>
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
                                <figcaption>旅×しごと：海・街・夜景・グルメのコラージュ（16:9）</figcaption>
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
                                <figcaption>はじめてでもOK：笑顔の接客シーン（16:9）</figcaption>
                            </figure>
                            <div class="merit-text">
                                <h3>未経験からはじめやすい</h3>
                                <p>“笑顔でおしゃべり”がいちばんの武器。初めての人でも入り口は広く、先輩のコツを真似しながら少しずつ慣れていけます。</p>
                                <p>まずは短期で雰囲気をつかんで、気に入ったら延長や再渡航へ…というステップも定番です。</p>
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
                                <figcaption>例①イメージ：学生2人の女子旅スナップ（カフェ・街歩き）</figcaption>
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
                                <figcaption>例②イメージ：海沿いでリラックスするシーン</figcaption>
                            </figure>
                            <article class="card">
                                <h3>例②：24歳／フリーター（1か月）</h3>
                                <p>「“海外で住む”を一回やってみたくて来ました。生活まわりのサポートが整った求人を選んだので、着いた日からスムーズに動けました。オフは海沿いでのんびりして、気持ちもリフレッシュ。帰国後に再渡航を相談中です！」
                                </p>
                            </article>
                        </div>
                    </div>
                </section>
                <section id="area">
                    <h2>3. 働ける主な国・エリアの例</h2>
                    <div class="country">
                        <figure>
                            <div class="ph ph--6"></div>
                            <figcaption>エリア写真：シンガポールの夜景（マリーナ周辺など）</figcaption>
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
                            <figcaption>エリア写真：ベトナムのカフェ・市場・街角アート</figcaption>
                        </figure>
                        <div>
                            <h3>ベトナム（ホーチミン／ハノイ）</h3>
                            <p>カフェやごはんが豊富で“毎日ちょっと楽しい”。コンドミニアム系の寮や送迎がセットになった求人も探しやすい印象です。</p>
                            <p>物価も比較的やさしめで、オフのカフェ巡りや雑貨屋さん探しがはかどります。街の活気に元気をもらえるはず。</p>
                            <p>写真が映える壁アートやローカル市場も多く、休日の散策がほんとうに充実。気づいたら常連のカフェができてます。</p>
                            <p>屋台のバインミーや生春巻きなど、手軽でおいしいローカル飯も楽しみのひとつ。</p>
                        </div>
                    </div>
                </section>
                <section id="flow">
                    <h2>4. 応募〜勤務開始までの流れ</h2>
                    <div class="grid grid-2">
                        <ol class="card" style="margin:0">
                            <li><b>条件に合った求人情報を探す</b><br>当サイトの求人情報から自分が行きたい国、期間とマッチした求人情報をチェック！</li>
                            <li><b>プロフィール登録</b><br>簡単な情報を入力してエントリーを行います。新規登録すると次回以降の情報入力が全て自動化されます。</li>
                            <li><b>オンライン面談</b><br>不安や質問をまとめて解消。働き方・生活面・準備物をわかりやすく説明。</li>
                            <li><b>お店のご紹介・比較</b><br>寮・送迎・給与内訳・衣装ルールを比べて、あなたに合うお店を一緒に決定。</li>
                            <li><b>決定＆準備</b><br>スケジュールが合えばスピード決定も。持ち物リストと到着日の動き方を共有。</li>
                            <li><b>現地到着→スタート</b><br>空港お迎えや入寮案内、初日の説明がセットの求人も。初日で生活とお仕事の流れをつかめます。</li>
                        </ol>
                    </div>
                </section>
                <section id="life">
                    <h2>5. 現地生活のイメージ</h2>
                    <div class="grid" style="grid-template-columns:1fr;gap:18px">
                        <figure>
                            <div class="ph ph--8"></div>
                            <figcaption>寮イメージ：清潔な室内・Wi-Fi・エアコン・立地感が伝わる写真</figcaption>
                        </figure>
                    </div>
                    <div class="card" style="margin-top:18px">
                        <ul class="clean">
                            <li><b>寮</b>：清潔なお部屋にWi-Fi・エアコン完備の物件が人気。スーパーやカフェが近い立地だと毎日がさらにラク。</li>
                            <li><b>送迎</b>：通勤は専用車でサクッと移動の求人もあり。夜道の移動も安心感があって続けやすい。</li>
                            <li><b>サポート</b>：到着日から担当がチャットでフォローの体制がある求人も。美容院・ネイル・両替スポットなど生活情報も共有。</li>
                            <li><b>働き方</b>：私服OK／ドレス支給／ヘアメイクあり等、スタイルはいろいろ。自分に合う“ムリのないスタンス”で続けやすい。</li>
                        </ul>
                    </div>
                </section>
                <section class="cta-band">
                    <h3>まずは相談だけでもOK。あなたに合う“ラクに続けやすい”働き方を一緒に。</h3>
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
            <footer class="bg-slate-800 text-slate-300">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 pb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-12">
                        <div class="md:col-span-2 lg:col-span-4">
                            <a href="/" class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-white/10 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                    </svg>
                                </div>
                                <span class="font-bold text-lg text-white tracking-wide">海外リゾキャバ求人.COM</span>
                            </a>
                            <p class="text-xs text-slate-400 mt-4 leading-relaxed">
                                新しい働き方、見つけよう。国内外のリゾートバイト・ワーキングホリデーの求人情報サイト。</p>
                            <div class="flex space-x-4 mt-6">
                                <a href="#" class="text-slate-400 hover:text-white" aria-label="Twitter"><i
                                        data-lucide="twitter"></i></a>
                                <a href="#" class="text-slate-400 hover:text-white" aria-label="Instagram"><i
                                        data-lucide="instagram"></i></a>
                                <a href="#" class="text-slate-400 hover:text-white" aria-label="Facebook"><i
                                        data-lucide="facebook"></i></a>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">運営者：海外リゾキャバ求人.COM運営</p>
                        </div>
                        <div class="md:col-span-2 lg:col-span-8 grid grid-cols-2 sm:grid-cols-4 gap-8">
                            <div>
                                <h4 class="font-semibold text-sm text-white mb-4 tracking-wider">サイトマップ</h4>
                                <ul class="space-y-3 text-xs">
                                    <li><a href="/for-beginners/" class="hover:text-white transition-colors">初めての方</a>
                                    </li>
                                    <li><a href="/jobs/" class="hover:text-white transition-colors">求人検索</a></li>
                                    <li><a href="/announcements/" class="hover:text-white transition-colors">お知らせ</a>
                                    </li>
                                    <li><a href="/features/" class="hover:text-white transition-colors">特集・コラム</a></li>
                                    <li><a href="/faq/" class="hover:text-white transition-colors">よくある質問</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm text-white mb-4 tracking-wider">規約・サポート情報</h4>
                                <ul class="space-y-3 text-xs">
                                    <li><a href="/contact/" class="hover:text-white transition-colors">お問い合わせ</a></li>
                                    <li><a href="/terms/" class="hover:text-white transition-colors">利用規約</a></li>
                                    <li><a href="/privacy/" class="hover:text-white transition-colors">プライバシーポリシー</a>
                                    </li>
                                    <li><a href="/contact-ad/" class="hover:text-white transition-colors">広告掲載</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 border-t border-slate-700 pt-8 text-center">
                        <p class="text-xs text-slate-400">&copy; 2025 海外リゾキャバ求人.COM All Rights Reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.lucide && typeof lucide.createIcons === 'function') {
                    lucide.createIcons();
                }
                var mobileMenuButton = document.getElementById('mobile-menu-button');
                var mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function () {
                        mobileMenu.classList.toggle('hidden');
                        mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
                    });
                }
            });
        </script>
    </body>
</html>