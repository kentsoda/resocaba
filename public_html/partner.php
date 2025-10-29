
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php
    $title = 'CLUB PREMIER｜掲載店舗【海外リゾキャバ求人.COM】';
    $description = '日本の東京銀座、札幌、函館、千葉 海外ではシンガポール、バンコク、ベトナムでキャバクラ、飲食店を数多く展開する シティーグループの香港店 Premiere HONG KONGとなります。 香港でワンランク上の⽉給・バックシステムを実現。 海外最多数の店舗を抱えるグループ、世界No.1の実績とノウハウだからこそ 安心、安全、稼げる香港No1の給与システム、豪華な寮を完備。 キャバクラ経験や海外経験がなくても 全面サポート致します。 アメリカやヨーロッパにも店舗展開予定。 海外での夢のライフスタイルを実現させ 海外の厳しいビザ環境の中でも ロングライフスタイルを楽しめます。';
    $og_title = $title; $og_description = $description;
    $og_type = 'website'; $og_url = 'https://xs161700.xsrv.jp/partner/21/';
    $og_image = 'https://gaicaba-st.monochrome-inc.net/shop_images/3684.jpg';
    require_once __DIR__ . '/includes/header.php';
    ?>

    <!-- SEO: JSON-LD for Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "BreadcrumbList",
          "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "トップ",
            "item": "https://example.com/"
          },{
            "@type": "ListItem",
            "position": 2,
            "name": "掲載店舗一覧",
            "item": "https://example.com/partners/"
          },{
            "@type": "ListItem",
            "position": 3,
            "name": "CRAZY CAT'S"
          }]
        },
        {
          "@type": "NightClub",
          "name": "CRAZY CAT'S (クレイジーキャッツ)",
          "image": "https://placehold.co/1200x800/f87171/ffffff?text=CRAZY+CAT'S+Hanoi",
          "description": "ベトナムの首都ハノイにある、2023年2月に新規OPENした日本人オーナーのキャバクラです。お客様は常連さんが多く、アットホームな雰囲気で安心してスタートできます。一階にはカウンター席、二階と三階には最新カラオケ付きの完全個室をご用意しています。",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "76 P. Linh Lang, Cống Vị, Ba Đình",
            "addressLocality": "Hà Nội",
            "postalCode": "100000",
            "addressCountry": "VN"
          },
          "telephone": "+84-345-521-001",
          "openingHours": "Mo-Su 20:00-02:00",
          "priceRange": "$$",
          "servesCuisine": "Cocktails, Beer, Spirits"
        }
      ]
    }
    </script>

    <style>
        :root {
            --text-primary: #1e293b; /* slate-800 */
            --text-secondary: #475569; /* slate-600 */
            --bg-base: #f1f5f9; /* slate-100 */
            --bg-surface: #ffffff;
            --bg-muted: #f1f5f9; /* slate-100 */
            --border-color: #e2e8f0; /* slate-200 */
            --brand-primary: #0ABAB5; /* Tiffany Blue */
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Noto Sans JP', sans-serif;
            color: var(--text-primary);
            background-color: var(--bg-base);
            overflow-x: hidden;
            font-size: 0.9375rem; /* 15px */
        }
        .swiper-nav-button { color: var(--text-secondary); background-color: transparent; width: 44px; height: 44px; transition: all 0.2s ease; --swiper-navigation-size: 28px; border-radius: 50%; display: none; }
        @media (min-width: 768px) { .swiper-nav-button { display: flex; } }
        .swiper-nav-button:hover { background-color: var(--bg-muted); color: var(--brand-primary); }
        .swiper-button-disabled { opacity: 0.1; pointer-events: none; }

        /* Lightbox for gallery */
        .lightbox { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); }
        .lightbox-content { margin: auto; display: block; max-width: 90%; max-height: 90%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .lightbox-close { position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; transition: 0.3s; }
        .lightbox-close:hover, .lightbox-close:focus { color: #bbb; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body class="antialiased">

    <div id="app">
        <!-- Header -->
        <header id="header" class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 border-b border-[var(--border-color)] transition-all duration-300">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[var(--brand-primary)] flex items-center justify-center">
                               <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                            </div>
                            <span class="font-bold text-lg text-[var(--text-primary)] tracking-wide">海外リゾキャバ求人.COM</span>
                        </a>
                    </div>
                    <nav class="hidden lg:flex items-center gap-x-6">
                        <a href="/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">トップ</a>
                        <a href="/for-beginners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">初めての方</a>
                        <a href="/jobs/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">求人検索</a>
                        <a href="/partners/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">掲載店舗</a>
                        <a href="/announcements/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
                        <a href="/features/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">特集・コラム</a>
                        <a href="/faq/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">よくある質問</a>
                    </nav>
                    <div class="hidden lg:flex items-center gap-x-3">
                         <a href="/login/" class="px-5 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] transition-colors">ログイン</a>
                         <a href="/register/" class="px-5 py-2 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">無料登録</a>
                    </div>
                    <button id="mobile-menu-button" aria-label="メニューを開く" class="lg:hidden p-2 text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--brand-primary)]">
                         <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
                <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-[var(--border-color)]">
                     <nav class="flex flex-col p-4 gap-y-3">
                        <a href="/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">トップ</a>
                        <a href="/for-beginners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">初めての方</a>
                        <a href="/jobs/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">求人検索</a>
                        <a href="/partners/" class="block px-3 py-2 text-sm font-medium text-[var(--brand-primary)] bg-[var(--bg-muted)]">掲載店舗</a>
                        <a href="/announcements/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">お知らせ</a>
                        <a href="/features/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">特集・コラム</a>
                        <a href="/faq/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">よくある質問</a>
                        <div class="flex items-center gap-x-3 pt-3 mt-3 border-t border-[var(--border-color)]">
                             <a href="/login/" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold border border-[var(--border-color)] text-[var(--text-secondary)] bg-white hover:bg-[var(--bg-muted)] transition-colors">ログイン</a>
                             <a href="/register/" class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">無料登録</a>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <!-- Page Header -->
            <div class="bg-white border-b border-[var(--border-color)] py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <nav class="text-xs mb-4" aria-label="Breadcrumb">
                      <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><a href="/partners/" class="text-gray-500 hover:text-[var(--brand-primary)]">掲載店舗一覧</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><span class="text-gray-700 font-medium">CLUB PREMIER</span></li>
                      </ol>
                    </nav>

                    <p class="text-sm font-semibold text-[var(--brand-primary)]">中国 / 香港</p>
                    <h1 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)] mt-1 !leading-tight">CLUB PREMIER</h1>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="max-w-4xl mx-auto space-y-12">
                    
                    <!-- Main Image Slider -->
                    <section class="relative">
                        <div class="swiper main-visual-slider border border-[var(--border-color)]">
                            <div class="swiper-wrapper"><div class="swiper-slide"><img src="https://gaicaba-st.monochrome-inc.net/shop_images/3684.jpg" alt="CLUB PREMIERのイメージ画像1" class="w-full aspect-[3/2] object-cover"></div><div class="swiper-slide"><img src="https://pbs.twimg.com/media/GUyRgdTb0AEeS1m?format=jpg&amp;name=large" alt="CLUB PREMIERのイメージ画像2" class="w-full aspect-[3/2] object-cover"></div></div>
                        </div>
                        <div class="swiper-button-prev main-visual-prev !left-2 md:!-left-4 !bg-white/50 hover:!bg-white/80"></div>
                        <div class="swiper-button-next main-visual-next !right-2 md:!-right-4 !bg-white/50 hover:!bg-white/80"></div>
                        <div class="swiper-pagination main-visual-pagination !bottom-3"></div>
                    </section>

                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                        <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">コンセプト</h2>日本の東京銀座、札幌、函館、千葉 海外ではシンガポール、バンコク、ベトナムでキャバクラ、飲食店を数多く展開する シティーグループの香港店 Premiere HONG KONGとなります。 香港でワンランク上の⽉給・バックシステムを実現。 海外最多数の店舗を抱えるグループ、世界No.1の実績とノウハウだからこそ 安心、安全、稼げる香港No1の給与システム、豪華な寮を完備。 キャバクラ経験や海外経験がなくても 全面サポート致します。 アメリカやヨーロッパにも店舗展開予定。 海外での夢のライフスタイルを実現させ 海外の厳しいビザ環境の中でも ロングライフスタイルを楽しめます。</section>

                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">店舗情報</h2>
                         <dl class="text-sm"><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">店名</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">CLUB PREMIER</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">エリア</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">中国 / 香港</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">住所</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">#11F,Circle Plaza,499 Hennessy Road,CausewayBay Hong kong</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">電話番号</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">+886912821909<br>+886912821909</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">営業時間</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">-</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">店休日</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">-</dd></div><div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3"><dt class="font-semibold text-slate-500">公式サイト</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><a href="https://www.instagram.com/premier.hk/" target="_blank" rel="noopener" class="inline-flex items-center gap-x-1 text-[var(--brand-primary)] font-semibold hover:opacity-80">公式サイトを開く<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a></dd></div></dl>
                    </section>

                    <!-- Photo Gallery -->
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">フォトギャラリー</h2>
                         <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 gallery-container"><img src="https://gaicaba-st.monochrome-inc.net/shop_images/3684.jpg" alt="CLUB PREMIERの写真" class="w-full aspect-[4/3] object-cover transition-opacity hover:opacity-80 border border-[var(--border-color)] cursor-pointer"><img src="https://pbs.twimg.com/media/GUyRgdTb0AEeS1m?format=jpg&amp;name=large" alt="CLUB PREMIERの写真" class="w-full aspect-[4/3] object-cover transition-opacity hover:opacity-80 border border-[var(--border-color)] cursor-pointer"></div>
                    </section>

                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">アクセスマップ</h2>
                         <div class="aspect-video bg-slate-200 border border-[var(--border-color)]">
                            <iframe src="https://www.google.com/maps?output=embed&amp;q=%2311F%2CCircle%20Plaza%2C499%20Hennessy%20Road%2CCausewayBay%20Hong%20kong" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                         </div>
                    </section>
                    
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">このお店の求人</h2>
                         <div class="space-y-6"><div class="grid grid-cols-2 md:grid-cols-4 gap-4"></div></div>
                                <div class="mt-4 text-right">
                                    <span class="inline-flex items-center gap-x-1 text-sm font-bold text-[var(--brand-primary)]">詳しく見る<i data-lucide="arrow-right" class="w-4 h-4"></i></span>
                                </div>
                            </a>
                             <!-- Add more job cards if available -->
                         </div>
                    </section>
                </div>
            </div>
        </main>
        
        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>
    
    <!-- Lightbox Modal -->
    <div id="lightbox-modal" class="lightbox">
        <span class="lightbox-close" id="lightbox-close-button">&times;</span>
        <img class="lightbox-content" id="lightbox-image">
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
                });
            }
            
            // Main visual slider
            new Swiper('.main-visual-slider', {
                loop: true,
                slidesPerView: 1,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.main-visual-next',
                    prevEl: '.main-visual-prev',
                },
                pagination: {
                    el: '.main-visual-pagination',
                    clickable: true,
                },
            });

            // Gallery Lightbox
            const lightbox = document.getElementById('lightbox-modal');
            const lightboxImg = document.getElementById('lightbox-image');
            const galleryImages = document.querySelectorAll('.gallery-container img');
            
            galleryImages.forEach(img => {
                img.addEventListener('click', () => {
                    lightbox.style.display = 'block';
                    lightboxImg.src = img.src;
                });
            });

            const closeBtn = document.getElementById('lightbox-close-button');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    lightbox.style.display = 'none';
                });
            }

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
