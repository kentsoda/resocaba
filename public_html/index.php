<!DOCTYPE html>
<html lang="ja">
<?php
    $title = '海外リゾキャバ求人｜未経験OK・高収入の短期リゾバ探しなら【海外リゾキャバ求人';
    $description = '国内外のリゾートバイト、ワーキングホリデーの求人情報を網羅。あなたの新しい挑戦を全力でサポートします。';
    $url = 'https://example.com/';
    $image = '/assets/images/articles/jobs-ogp-1200x630.jpg';
    $og_title = '海外リゾキャバ求人.COM | 新しい世界で、新しい自分を';
    $og_description = '国内外のリゾートバイト、ワーキングホリデーの求人情報を網羅。';
    $og_type = 'website';
    $og_url = 'https://resocaba-info.com/';
    $og_image = '/assets/images/articles/jobs-ogp-1200x630.jpg';
    $og_locale = 'ja_JP';
    $og_site_name = '海外リゾキャバ求人.COM';
    require_once __DIR__ . '/includes/header.php';
?>
<!-- SEO: JSON-LD for Structured Data -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [{
                "@type": "WebSite",
                "name": "海外リゾキャバ求人.COM",
                "url": "https://example.com/",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "https://example.com/search?q={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "海外リゾキャバ求人.COM",
                    "url": "https://example.com/",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "/assets/images/ui/ad-banner-1-640x200.jpg"
                    }
                }
            },
            {
                "@type": "FAQPage",
                "mainEntity": [{
                        "@type": "Question",
                        "name": "登録にお金はかかりますか？",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "いいえ、ご登録からお仕事紹介、サポートまで、ご利用はすべて無料です。費用は一切かかりませんのでご安心ください。"
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "未経験でも大丈夫ですか？",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "はい、未経験者歓迎の求人を多数ご用意しています。専門のコーディネーターが、あなたのスキルや希望に合ったお仕事探しをサポートしますのでご安心ください。"
                        }
                    }
                ]
            },
            {
                "@type": "JobPosting",
                "title": "【サンプル求人】ピックアップのお仕事 No.1",
                "description": "<p>これはサンプル求人です。No.1の素晴らしい環境で、新しいチャレンジを始めませんか？たくさんの仲間が待っています。</p><ul><li>未経験OK</li><li>高時給</li><li>寮費無料</li></ul>",
                "datePosted": "2025-09-11",
                "validThrough": "2025-12-31",
                "employmentType": "TEMPORARY",
                "hiringOrganization": {
                    "@type": "Organization",
                    "name": "海外リゾキャバ求人.COM掲載店舗",
                    "sameAs": "https://example.com/"
                },
                "jobLocation": {
                    "@type": "Place",
                    "address": {
                        "@type": "PostalAddress",
                        "addressCountry": "JP",
                        "addressRegion": "北海道"
                    }
                },
                "baseSalary": {
                    "@type": "MonetaryAmount",
                    "currency": "JPY",
                    "value": {
                        "@type": "QuantitativeValue",
                        "value": 1200,
                        "unitText": "HOUR"
                    }
                }
            }
        ]
    }
</script>
<style>
    :root {
        --text-primary: #1e293b;
        /* slate-800 */
        --text-secondary: #475569;
        /* slate-600 */
        --bg-base: #ffffff;
        --bg-surface: #ffffff;
        --bg-muted: #f1f5f9;
        /* slate-100 */
        --border-color: #e2e8f0;
        /* slate-200 */
        --brand-primary: #0ABAB5;
        /* Tiffany Blue */
        --brand-secondary: #f59e0b;
        /* amber-500 */
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Noto Sans JP', sans-serif;
        color: var(--text-primary);
        background-color: var(--bg-base);
        overflow-x: hidden;
        /* Prevent horizontal scroll */
        font-size: 0.9375rem;
        /* 15px */
    }

    .section-title {
        font-size: 1.75rem;
        /* 28px */
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        letter-spacing: 0.1em;
    }

    .section-subtitle {
        font-size: 0.875rem;
        /* 14px */
        text-align: center;
        color: var(--text-secondary);
        margin-bottom: 3rem;
        letter-spacing: 0.1em;
    }

    .section-title--compact {
        font-size: 1.5rem;
        /* 24px */
        font-weight: 500;
    }

    /* Swiper Navigation Buttons */
    .swiper-nav-button {
        color: var(--text-secondary);
        background-color: transparent;
        width: 44px;
        height: 44px;
        transition: all 0.2s ease;
        --swiper-navigation-size: 28px;
        border-radius: 50%;
    }

    .swiper-nav-button:hover {
        background-color: var(--bg-muted);
        color: var(--brand-primary);
    }

    .swiper-button-disabled {
        opacity: 0.1;
        pointer-events: none;
    }

    /* Ad Banner Swiper */
    .ad-banner-swiper .swiper-pagination-bullet {
        background-color: rgba(255, 255, 255, 0.8);
        width: 10px;
        height: 10px;
        opacity: 1;
    }

    .ad-banner-swiper .swiper-pagination-bullet-active {
        background-color: var(--brand-primary);
    }

    .faq-item .answer {
        transition: max-height 0.5s ease-out;
        max-height: 0;
        overflow: hidden;
    }

    .faq-item.open .answer {
        max-height: 500px;
        /* Adjust as needed */
        transition: max-height 0.5s ease-in;
    }

    .faq-item.open .icon {
        transform: rotate(45deg);
    }

    .announcement-tab {
        color: var(--text-secondary);
        transition: all 0.2s ease-in-out;
    }

    .announcement-tab.active-tab {
        color: var(--brand-primary);
        background-color: white;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    }

    /* --- Auto Scroller for Partner Logos --- */
    @keyframes scroll-left {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(calc(-100% / 2));
        }
    }

    @keyframes scroll-right {
        from {
            transform: translateX(calc(-100% / 2));
        }

        to {
            transform: translateX(0);
        }
    }

    .scroller {
        overflow: hidden;
    }

    .scroller-inner {
        display: flex;
        flex-wrap: nowrap;
        width: max-content;
    }

    .scroller-inner.scroll-left {
        animation: scroll-left 120s linear infinite;
    }

    .scroller-inner.scroll-right {
        animation: scroll-right 120s linear infinite;
    }

    .scroller:hover .scroller-inner {
        animation-play-state: paused;
    }

    .scroller-inner>* {
        flex-shrink: 0;
        width: 250px;
        /* Adjust card width as needed */
        margin: 0 0.5rem;
        /* gap between cards */
    }

    /* --- Top search form selects normalization --- */
    .hero-search select {
        -webkit-appearance: none;
        /* iOS Safari */
        appearance: none;
        min-width: 0;
        /* prevent overflow in grid/flex */
    }

    /* --- Hide carousel arrows on small screens --- */
    @media (max-width: 768px) {

        .swiper-button-prev,
        .swiper-button-next {
            display: none !important;
        }
    }

    @media (min-width: 640px) {
        .scroller-inner>* {
            width: 300px;
        }
    }

    .description-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tags-container {
        height: 80px;
        overflow: hidden;
    }
</style>
<?php
    $job_list = get_job_list_with_images();
    $announcements = get_announcement_list(5);
    $articles = get_article_list(4);
    $pickup_jobs = [];
    $new_jobs = [];
    $overseas_jobs = [];
    $domestic_jobs = [];
    $popular_jobs = [];
    $long_jobs = [];
    $short_jobs = [];
    foreach ($job_list as $job) {
        $job_meta = json_decode($job['meta_json'], true);
        if (!isset($job_meta['home_sections'])) {
            continue;
        }
        foreach ($job_meta['home_sections'] as $section) {
            $var_name = $section . '_jobs';
            ${$var_name}[] = $job;
        }
    }
    
    // 店舗データを取得・分類
    $stores = get_store_list_with_images();
    $overseas_stores = [];
    $domestic_stores = [];
    
    if ($stores !== false && !empty($stores)) {
        foreach ($stores as $store) {
            if ($store['country'] === '日本') {
                $domestic_stores[] = $store;
            } else {
                $overseas_stores[] = $store;
            }
        }
    }
    
    // FAQデータを取得
    $faqs = get_faq_list();
?>
<body class="antialiased">

    <div id="app">
        <?php require_once __DIR__ . '/includes/menu.php'; ?>

        <main>
            <!-- Hero Section -->
            <section class="relative bg-slate-50 overflow-hidden">
                <!-- PC用背景画像 -->
                <div class="absolute inset-0 bg-cover bg-center hidden md:block" style="background-image: url('./hero.jpg');"></div>
                <!-- スマホ用背景画像 -->
                <div class="absolute inset-0 bg-cover bg-center block md:hidden" style="background-image: url('./hero_sp.jpg');"></div>
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="relative z-10 w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20 min-h-[calc(100vh-80px)] py-20 justify-center">
                        <div class="w-full lg:w-2/3 text-center">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-6 !leading-tight filter drop-shadow-lg">
                                新しい働き方、見つけよう。<br>リゾートバイトで最高の体験を。
                            </h1>
                            <p class="text-white/90 text-base mt-4 max-w-2xl mx-auto drop-shadow-md">海外リゾキャバ求人.COMでは、未経験から始められる高収入・短期のリゾートバイトやキャバクラの求人情報を多数掲載しています。</p>
                            <div class="max-w-4xl mx-auto bg-white/90 backdrop-blur-sm shadow-2xl p-5 sm:p-8 border border-white/20 mt-8 hero-search">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"><select class="w-full p-3 border border-[var(--border-color)] bg-white focus:ring-2 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition" aria-label="エリア選択">
                                        <option>エリアを選択</option>
                                        <option>ハノイ</option>
                                        <option>バンコク</option>
                                        <option>プノンペン</option>
                                        <option>東京</option>
                                        <option>沖縄</option>
                                    </select><select class="w-full p-3 border border-[var(--border-color)] bg-white focus:ring-2 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition" aria-label="期間選択">
                                        <option>期間を選択</option>
                                        <option>1ヶ月未満</option>
                                        <option>1〜3ヶ月</option>
                                    </select><select class="w-full p-3 border border-[var(--border-color)] bg-white focus:ring-2 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition" aria-label="職種選択">
                                        <option>職種を選択</option>
                                        <option>キャスト</option>
                                        <option>キャバクラ</option>
                                        <option>キャバクラキャスト</option>
                                        <option>ホール</option>
                                    </select><button id="top-search-button" class="w-full p-3 bg-[var(--brand-primary)] text-white font-bold hover:opacity-90 flex items-center justify-center gap-x-2 transition-opacity"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                                        </svg><span>検索</span></button></div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    </section>

    <!-- Ad Banner Section -->
    <section id="ad-banner" class="py-12 bg-slate-100">
        <div class="max-w-none mx-auto">
            <?php $ad_banners = get_ad_banners(4); ?>
            <!-- PC Grid Layout -->
            <div class="hidden lg:grid grid-cols-4 gap-0">
                <?php if (!empty($ad_banners)) : ?>
                    <?php foreach ($ad_banners as $banner) : 
                        $href = isset($banner['link_url']) && $banner['link_url'] !== '' ? $banner['link_url'] : '#';
                        $target = (isset($banner['target_blank']) && (int)$banner['target_blank'] === 1) ? ' target="_blank" rel="noopener"' : '';
                        $src = isset($banner['image_url']) ? $banner['image_url'] : '';
                        $alt = '';
                    ?>
                        <a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" class="block group overflow-hidden"<?= $target ?>><img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $alt ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy"></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Mobile Swiper Layout -->
            <div class="lg:hidden relative">
                <div class="swiper ad-banner-swiper">
                    <div class="swiper-wrapper">
                        <?php if (!empty($ad_banners)) : ?>
                            <?php foreach ($ad_banners as $banner) : 
                                $href = isset($banner['link_url']) && $banner['link_url'] !== '' ? $banner['link_url'] : '#';
                                $target = (isset($banner['target_blank']) && (int)$banner['target_blank'] === 1) ? ' target="_blank" rel="noopener"' : '';
                                $src = isset($banner['image_url']) ? $banner['image_url'] : '';
                                $alt = '';
                            ?>
                                <div class="swiper-slide"><a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"<?= $target ?>><img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $alt ?>" class="w-full" loading="lazy"></a></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="swiper-pagination !-bottom-6 !relative"></div>
            </div>
        </div>
    </section>

    <div class="space-y-24 py-24 bg-[var(--bg-base)]">
        <!-- ★★★ Job Sections ★★★ -->
        <div class="space-y-24">
            <!-- ★ ピックアップ求人 -->
            <section id="pickup-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">ピックアップ求人</h2>
                        <p class="section-subtitle">PICKUP JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($pickup_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span>時給 <?php echo number_format($job['salary_min']); ?>円</span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 新着求人 -->
            <section id="new-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">新着求人</h2>
                        <p class="section-subtitle">NEW JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($new_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span>時給 <?php echo number_format($job['salary_min']); ?>円</span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?sort=new" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 海外求人 -->
            <section id="overseas-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">海外求人</h2>
                        <p class="section-subtitle">OVERSEAS JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($overseas_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo $job['salary_unit'] === 'MONTH' ? '月給 ' . number_format($job['salary_min']) . '円' : '時給 ' . number_format($job['salary_min']) . '円'; ?></span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?section=overseas" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 国内求人 -->
            <section id="domestic-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">国内求人</h2>
                        <p class="section-subtitle">DOMESTIC JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($domestic_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo $job['salary_unit'] === 'MONTH' ? '月給 ' . number_format($job['salary_min']) . '円' : '時給 ' . number_format($job['salary_min']) . '円'; ?></span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?section=domestic" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 人気リゾート求人 -->
            <section id="popular-resort-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">人気リゾート求人</h2>
                        <p class="section-subtitle">POPULAR RESORT JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($popular_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo $job['salary_unit'] === 'MONTH' ? '月給 ' . number_format($job['salary_min']) . '円' : '時給 ' . number_format($job['salary_min']) . '円'; ?></span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?section=popular" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 長期採用求人 -->
            <section id="long-term-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">長期採用求人</h2>
                        <p class="section-subtitle">LONG-TERM JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($long_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo $job['salary_unit'] === 'MONTH' ? '月給 ' . number_format($job['salary_min']) . '円' : '時給 ' . number_format($job['salary_min']) . '円'; ?></span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?section=long" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- ★ 短期採用求人 -->
            <section id="short-term-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <h2 class="section-title">短期採用求人</h2>
                        <p class="section-subtitle">SHORT-TERM JOBS</p>
                    </div>
                    <div class="relative">
                        <div class="swiper card-carousel">
                            <div class="swiper-wrapper">
                                <?php foreach ($short_jobs as $job) { 
                                    // 画像URLを取得（最初の画像、なければプレースホルダー）
                                    $image_url = !empty($job['images']) ? $job['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                                    $image_alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') . 'の画像';
                                ?>
                                    <div class="swiper-slide">
                                        <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                            <div class="relative">
                                                <div class="overflow-hidden"><img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                            </div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $job['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                    <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['region_prefecture'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo htmlspecialchars($job['employment_type'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                    <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo $job['salary_unit'] === 'MONTH' ? '月給 ' . number_format($job['salary_min']) . '円' : '時給 ' . number_format($job['salary_min']) . '円'; ?></span></p>
                                                </div>
                                                <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                    <?php $jobTags = $job['tags'] ?? []; if (!empty($jobTags)): ?>
                                                        <?php foreach ($jobTags as $tag): ?>
                                                            <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;">
                                                                <i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                                                <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-xs text-slate-500 flex-grow description-truncate"><?php echo htmlspecialchars($job['description_text'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $job['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                        <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-right-4"></div>
                    </div>
                    <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                        <a href="/jobs/?section=short" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>
        </div>

        <!-- ◾️◾️◾️ Custom UI Sections ◾️◾️◾️ -->
        <div class="space-y-24">
            <!-- お知らせ -->
            <section id="announcements">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">お知らせ</h2>
                    <p class="section-subtitle">ANNOUNCEMENTS</p>
                    <div class="bg-white p-4 sm:p-6 border border-[var(--border-color)] shadow-sm max-w-3xl mx-auto">
                        <!-- Tab buttons -->
                        <div class="flex gap-1 p-1 bg-slate-100 mb-4" id="announcement-tabs">
                            <button data-tab="new" class="announcement-tab active-tab flex-1 whitespace-nowrap py-2 text-xs font-medium text-center">新着</button>
                            <button data-tab="overseas" class="announcement-tab flex-1 whitespace-nowrap py-2 text-xs font-medium text-center">海外</button>
                            <button data-tab="domestic" class="announcement-tab flex-1 whitespace-nowrap py-2 text-xs font-medium text-center">国内</button>
                            <button data-tab="other" class="announcement-tab flex-1 whitespace-nowrap py-2 text-xs font-medium text-center">その他</button>
                        </div>
                        <!-- Tab content panels -->
                        <div id="announcement-content">
                            <div id="announcement-panel-new" class="announcement-panel">
                                <?php if (!empty($announcements)): ?>
                                <ul class="divide-y divide-[var(--border-color)]">
                                    <?php foreach ($announcements as $announcement): ?>
                                    <li><a href="/announcement/<?php echo htmlspecialchars($announcement['id']); ?>/" class="block group py-4 transition-colors hover:bg-slate-50 -mx-4 px-4">
                                            <div class="flex items-center gap-x-3 mb-1 sm:mb-0">
                                                <span class="text-sm text-slate-500">
                                                    <?php 
                                                    $date = $announcement['published_at'] ?: $announcement['created_at'];
                                                    echo date('Y.m.d', strtotime($date));
                                                    ?>
                                                </span>
                                            </div>
                                            <p class="font-medium text-slate-800 group-hover:text-[var(--brand-primary)] mt-1"><?php echo htmlspecialchars($announcement['title']); ?></p>
                                        </a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php else: ?>
                                <p class="text-slate-500 text-sm py-4 text-center">お知らせはありません。</p>
                                <?php endif; ?>
                            </div>
                            <div id="announcement-panel-overseas" class="announcement-panel hidden">
                                <p class="text-slate-500 text-sm py-4 text-center">お知らせはありません。</p>
                            </div>
                            <div id="announcement-panel-domestic" class="announcement-panel hidden">
                                <p class="text-slate-500 text-sm py-4 text-center">お知らせはありません。</p>
                            </div>
                            <div id="announcement-panel-other" class="announcement-panel hidden">
                                <p class="text-slate-500 text-sm py-4 text-center">お知らせはありません。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 特集・コラム -->
            <section id="features">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">特集・コラム</h2>
                    <p class="section-subtitle">FEATURES & COLUMNS</p>
                    <div id="features-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php if (!empty($articles)): ?>
                            <?php foreach ($articles as $article): ?>
                            <div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full">
                                <div class="overflow-hidden">
                                    <img src="<?php echo !empty($article['og_image_url']) ? htmlspecialchars($article['og_image_url'], ENT_QUOTES, 'UTF-8') : '/assets/images/articles/article-default-600w.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>の画像" 
                                         class="w-full aspect-video object-cover">
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <?php if (!empty($article['category'])): ?>
                                    <p class="text-xs font-bold text-slate-600 mb-2"><?php echo htmlspecialchars($article['category'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <?php endif; ?>
                                    <h3 class="font-bold text-base mb-3 leading-tight">
                                        <a href="/feature/<?php echo $article['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors">
                                            <?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h3>
                                    <div class="text-right text-xs text-slate-400 mt-2">
                                        <span class="inline-flex items-center">
                                            <i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i>
                                            <span>更新日: <?php echo date('Y.m.d', strtotime($article['updated_at'])); ?></span>
                                        </span>
                                    </div>
                                    <div class="mt-auto pt-3 border-t border-[var(--border-color)]">
                                        <a href="/feature/<?php echo $article['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-span-full text-slate-500 text-sm py-8 text-center">記事はありません。</p>
                        <?php endif; ?>
                    </div>
                    <div class="text-center mt-12">
                        <a href="/features/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- 海外掲載店舗 -->
            <section id="overseas-partners" class="partner-section">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">海外掲載店舗</h2>
                    <p class="section-subtitle">OVERSEAS PARTNERS</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                        <?php foreach ($overseas_stores as $store): 
                            $first_image = !empty($store['images']) ? $store['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                            $store_name = htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8');
                            $country = htmlspecialchars($store['country'], ENT_QUOTES, 'UTF-8');
                            $region = htmlspecialchars($store['region_prefecture'], ENT_QUOTES, 'UTF-8');
                        ?>
                            <a href="/partner/<?= $store['id'] ?>/" class="block group relative overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                                <img src="<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $store_name ?>の画像" class="w-full aspect-[4/5] object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-3">
                                    <h3 class="font-bold text-white text-base"><?= $store_name ?></h3>
                                    <p class="text-xs text-slate-200"><?= $country ?> / <?= $region ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-12">
                        <a href="/partners/overseas/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- 国内掲載店舗 -->
            <section id="domestic-partners" class="partner-section">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">国内掲載店舗</h2>
                    <p class="section-subtitle">DOMESTIC PARTNERS</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                        <?php foreach ($domestic_stores as $store): 
                            $first_image = !empty($store['images']) ? $store['images'][0]['image_url'] : '/assets/images/jobs/no-image-1280w.jpg';
                            $store_name = htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8');
                            $country = htmlspecialchars($store['country'], ENT_QUOTES, 'UTF-8');
                            $region = htmlspecialchars($store['region_prefecture'], ENT_QUOTES, 'UTF-8');
                        ?>
                            <a href="/partner/<?= $store['id'] ?>/" class="block group relative overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                                <img src="<?= htmlspecialchars($first_image, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $store_name ?>の画像" class="w-full aspect-[4/5] object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-3">
                                    <h3 class="font-bold text-white text-base"><?= $store_name ?></h3>
                                    <p class="text-xs text-slate-200"><?= $country ?> / <?= $region ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-12">
                        <a href="/partners/domestic/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                    </div>
                </div>
            </section>

            <!-- 詳細検索セクション -->
            <section id="detailed-search">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">条件から探す</h2>
                    <p class="section-subtitle">SEARCH BY CONDITION</p>
                    <div class="bg-white p-6 sm:p-8 border border-[var(--border-color)] space-y-4">
                        <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                            <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="map-pin" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>エリアで探す</span></h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><a href="/jobs/?area=%E3%83%8F%E3%83%8E%E3%82%A4" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">ハノイ</a><a href="/jobs/?area=%E3%83%90%E3%83%B3%E3%82%B3%E3%82%AF" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">バンコク</a><a href="/jobs/?area=%E3%83%97%E3%83%8E%E3%83%B3%E3%83%9A%E3%83%B3" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">プノンペン</a><a href="/jobs/?area=%E6%9D%B1%E4%BA%AC" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">東京</a><a href="/jobs/?area=%E6%B2%96%E7%B8%84" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">沖縄</a><a href="/jobs/?section=overseas" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">その他海外</a></div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                            <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="calendar" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>働く期間で探す</span></h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><a href="/jobs/?period=short" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">1ヶ月未満</a><a href="/jobs/?period=mid" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">1〜3ヶ月</a><a href="/jobs/?period=short" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">短期（1ヶ月以内）</a><a href="/jobs/?period=mid" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">中期（1〜3ヶ月）</a><a href="/jobs/?period=long" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">長期（3ヶ月〜）</a><a href="/jobs/?period=summer" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">夏休み</a><a href="/jobs/?period=winter" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">年末年始</a></div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                            <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="briefcase" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>職種で探す</span></h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><a href="/jobs/?type=%E3%82%AD%E3%83%A3%E3%82%B9%E3%83%88" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">キャスト</a><a href="/jobs/?type=%E3%82%AD%E3%83%A3%E3%83%90%E3%82%AF%E3%83%A9" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">キャバクラ</a><a href="/jobs/?type=%E3%82%AD%E3%83%A3%E3%83%90%E3%82%AF%E3%83%A9%E3%82%AD%E3%83%A3%E3%82%B9%E3%83%88" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">キャバクラキャスト</a><a href="/jobs/?type=%E3%83%9B%E3%83%BC%E3%83%AB" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1">ホール</a></div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-start gap-4 pt-6">
                            <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="tags" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>人気のタグから探す</span></h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-6 gap-y-4 text-sm"><a href="/jobs/?tags[]=ok" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1"><i data-lucide="tag" class="w-4 h-4 mr-2 text-slate-400"></i><span>未経験OK</span></a><a href="/jobs/?tags[]=ok-2" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1"><i data-lucide="tag" class="w-4 h-4 mr-2 text-slate-400"></i><span>日払いOK</span></a><a href="/jobs/?tags[]=tag" class="inline-flex items-center text-slate-600 hover:text-[var(--brand-primary)] transition-colors border-b border-dotted pb-1"><i data-lucide="tag" class="w-4 h-4 mr-2 text-slate-400"></i><span>前払い相談可</span></a></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- よくある質問 -->
            <section id="faq">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">よくある質問</h2>
                    <p class="section-subtitle">FREQUENTLY ASKED QUESTIONS</p>
                    <div class="bg-white p-4 sm:p-6 border border-[var(--border-color)] shadow-sm max-w-3xl mx-auto">
                        <!-- FAQ content -->
                        <div id="faq-content" class="space-y-4">
                            <div id="faq-panel-general" class="faq-panel space-y-4">
                                <?php if ($faqs !== false && !empty($faqs)): ?>
                                    <?php foreach ($faqs as $faq): 
                                        $question = htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8');
                                    ?>
                                        <div class="faq-item bg-white border border-[var(--border-color)] transition-all duration-300">
                                            <button class="question flex items-center justify-between w-full p-4 sm:p-6 text-left">
                                                <span class="font-bold text-base"><?= $question ?></span>
                                                <span class="icon text-[var(--brand-primary)] transition-transform duration-300 transform">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 5v14M5 12h14" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <div class="answer">
                                                <div class="px-4 sm:px-6 pb-4 sm:pb-6 text-slate-600">
                                                    <?= $faq['answer_html'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-slate-500 py-8">現在、よくある質問はありません。</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- お問い合わせ -->
            <section id="contact">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h2 class="section-title">お問い合わせ</h2>
                    <p class="section-subtitle">CONTACT US</p>
                    <div class="bg-white p-8 border border-[var(--border-color)] shadow-sm text-center max-w-3xl mx-auto">
                        <p class="text-slate-600 mb-6 text-sm">求人に関するご質問、掲載に関するご相談など、<br class="hidden sm:block">お気軽にお問い合わせください。</p><a href="/contact/" class="inline-block bg-[var(--brand-primary)] text-white font-bold py-3 px-10 hover:opacity-90 transition-all text-sm shadow-lg">お問い合わせフォームへ</a>
                    </div>
                </div>
            </section>
        </div>

    </div>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Top hero search button → build URL to /jobs/
            (function() {
                var btn = document.getElementById('top-search-button');
                if (!btn) return;
                var selects = btn.closest('.grid');
                btn.addEventListener('click', function() {
                    var area = '';
                    var period = '';
                    var type = '';
                    try {
                        var sel = selects ? selects.querySelectorAll('select') : [];
                        if (sel && sel.length >= 3) {
                            area = sel[0].value && !/選択/.test(sel[0].value) ? sel[0].value : '';
                            period = sel[1].value && !/選択/.test(sel[1].value) ? sel[1].value : '';
                            type = sel[2].value && !/選択/.test(sel[2].value) ? sel[2].value : '';
                        }
                    } catch (e) {}
                    var params = new URLSearchParams();
                    if (area) params.set('area', area);
                    if (period) {
                        // map label to key
                        if (period.indexOf('1ヶ月') !== -1) {
                            params.set('period', 'short');
                        } else if (period.indexOf('1〜3ヶ月') !== -1) {
                            params.set('period', 'mid');
                        } else {
                            params.set('period', 'long');
                        }
                    }
                    if (type) params.set('type', type);
                    var p = (function() {
                        var b = document.querySelector('base');
                        if (b && b.href) {
                            return new URL('jobs/', b.href).pathname;
                        }
                        var dir = (window.location.pathname || '/').replace(/[^\/]*$/, '');
                        return dir + 'jobs/';
                    })();
                    var url = p + (params.toString() ? ('?' + params.toString()) : '');
                    window.location.href = url;
                });
            })();
            // Ad Banner Swiper
            new Swiper('.ad-banner-swiper', {
                loop: true,
                slidesPerView: 1,
                spaceBetween: 0,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
            });

            const swiperInstances = new Map();


            // --- LAYOUT LOGIC ---
            function setupLayouts() {
                const isDesktop = window.innerWidth >= 1024;

                document.querySelectorAll('.job-section').forEach(section => {
                    const id = section.id;
                    const swiperContainer = section.querySelector('.swiper');
                    if (!swiperContainer) return;
                    const wrapper = swiperContainer.querySelector('.swiper-wrapper');
                    const slides = Array.from(wrapper.children);
                    const prevBtn = section.querySelector('.swiper-button-prev');
                    const nextBtn = section.querySelector('.swiper-button-next');

                    if (swiperInstances.has(id)) {
                        swiperInstances.get(id).destroy(true, true);
                        swiperInstances.delete(id);
                    }

                    wrapper.className = 'swiper-wrapper';
                    swiperContainer.className = 'swiper card-carousel';
                    slides.forEach(slide => slide.style.display = 'block');
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';

                    if (isDesktop) {
                        swiperContainer.classList.add('px-4', 'sm:px-6', 'lg:px-8');
                        wrapper.classList.add('grid', 'grid-cols-4', 'gap-6');
                        slides.forEach((slide, i) => {
                            if (i >= 8) slide.style.display = 'none';
                        });
                    } else {
                        // SP: 2x4 grid (max 8 items) instead of horizontal carousel
                        swiperContainer.classList.add('px-4', 'sm:px-6', 'lg:px-8');
                        wrapper.classList.add('grid', 'grid-cols-2', 'gap-4');
                        slides.forEach((slide, i) => {
                            if (i >= 8) slide.style.display = 'none';
                        });
                    }
                });
            }

            function setupFaqAccordion(panel) {
                panel.querySelectorAll('.faq-item').forEach(item => {
                    const button = item.querySelector('.question');
                    button.addEventListener('click', () => {
                        const wasOpen = item.classList.contains('open');
                        panel.querySelectorAll('.faq-item.open').forEach(openItem => {
                            openItem.classList.remove('open');
                        });
                        if (!wasOpen) {
                            item.classList.add('open');
                        }
                    });
                });
            }

            

            function debounce(func) {
                let timer;
                return function(event) {
                    if (timer) clearTimeout(timer);
                    timer = setTimeout(func, 250, event);
                };
            }

            function init() {
                setupLayouts();
                if (window.lucide && typeof lucide.createIcons === 'function') { lucide.createIcons(); }
            }

            init();
            window.addEventListener('resize', debounce(setupLayouts));
        });
    </script>
    <script>
        (function() {
            var ct = document.getElementById("faq-content");
            if (!ct) return;
            ct.querySelectorAll(".faq-item .question").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    var item = btn.closest(".faq-item");
                    if (!item) return;
                    var open = item.classList.contains("open");
                    item.parentElement.querySelectorAll(".faq-item.open").forEach(function(o) {
                        o.classList.remove("open")
                    });
                    if (!open) {
                        item.classList.add("open");
                    }
                });
            });
        })();
    </script>
    <script>
        (function() {
            var c = document.getElementById("announcement-tabs");
            if (!c) return;
            var tabs = c.querySelectorAll(".announcement-tab");

            function show(t) {
                var id = "announcement-panel-" + t;
                document.querySelectorAll(".announcement-panel").forEach(function(p) {
                    p.id === id ? p.classList.remove("hidden") : p.classList.add("hidden")
                });
            }
            tabs.forEach(function(tab) {
                tab.addEventListener("click", function() {
                    tabs.forEach(function(t) {
                        t.classList.remove("active-tab")
                    });
                    tab.classList.add("active-tab");
                    var target = tab.dataset.tab;
                    show(target);
                });
            });
        })();
    </script>
</body>

</html>