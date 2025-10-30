<?php
require_once __DIR__ . '/../config/functions.php';

$announcementId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slugParam = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';

if ($announcementId <= 0) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('#/announcement/(\d+)/?#', $requestUri, $matches)) {
        $announcementId = (int)$matches[1];
    }
}

$announcement = null;
if ($announcementId > 0) {
    $announcement = get_announcement_by_id($announcementId);
}

if (!$announcement && $slugParam !== '') {
    $announcement = get_announcement_by_slug($slugParam);
    if ($announcement) {
        $announcementId = (int)$announcement['id'];
    }
}

if (!$announcement && $slugParam === '') {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('#/announcement/([^/]+)/?#', $requestUri, $matches)) {
        $slugParam = $matches[1];
        $announcement = get_announcement_by_slug($slugParam);
        if ($announcement) {
            $announcementId = (int)$announcement['id'];
        }
    }
}

$announcementTitle = $announcement ? (string)$announcement['title'] : 'お知らせが見つかりません';
$host = $_SERVER['HTTP_HOST'] ?? 'example.com';
$pageUrl = 'https://' . $host . '/announcement/' . ($announcementId > 0 ? $announcementId . '/' : '');
$defaultOgImage = '/assets/images/articles/news-ogp-1200x630.jpg';

if ($announcement) {
    $bodyHtml = $announcement['body_html'] ?? '';
    $bodyText = strip_tags($bodyHtml);
    $descriptionSource = trim(preg_replace('/\s+/u', ' ', $bodyText));
    $description = mb_strimwidth($descriptionSource, 0, 160, '…', 'UTF-8');
    $title = $announcementTitle . '｜お知らせ｜海外リゾキャバ求人.COM';
    $og_title = $title;
    $og_description = $description;
    $og_type = 'article';
    $og_url = $pageUrl;
    $og_image = $defaultOgImage;
    $publishedAt = $announcement['published_at'] ?? $announcement['created_at'] ?? null;
    $updatedAt = $announcement['updated_at'] ?? $publishedAt;
    $publishedDate = $publishedAt ? date('Y.m.d', strtotime($publishedAt)) : '';
    $publishedIso = $publishedAt ? date('c', strtotime($publishedAt)) : '';
    $updatedIso = $updatedAt ? date('c', strtotime($updatedAt)) : $publishedIso;
} else {
    http_response_code(404);
    $bodyHtml = '<p>お探しのお知らせは見つかりませんでした。</p>';
    $description = 'お探しのお知らせは存在しないか、公開が終了しました。';
    $title = 'お知らせが見つかりません｜海外リゾキャバ求人.COM';
    $og_title = $title;
    $og_description = $description;
    $og_type = 'article';
    $og_url = $pageUrl;
    $og_image = $defaultOgImage;
    $publishedDate = '';
    $publishedIso = '';
    $updatedIso = '';
}

$canonical = $announcement ? '/announcement/' . $announcementId . '/' : '';

$ogImageAbsolute = (strpos($og_image, 'http://') === 0 || strpos($og_image, 'https://') === 0)
    ? $og_image
    : 'https://' . $host . $og_image;

$jsonLdData = null;
if ($announcement) {
    $jsonLdData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'トップ',
                        'item' => 'https://' . $host . '/',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'お知らせ一覧',
                        'item' => 'https://' . $host . '/announcements/',
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $announcementTitle,
                    ],
                ],
            ],
            [
                '@type' => 'NewsArticle',
                'headline' => $announcementTitle,
                'datePublished' => $publishedIso ?: null,
                'dateModified' => $updatedIso ?: ($publishedIso ?: null),
                'author' => [
                    '@type' => 'Organization',
                    'name' => '海外リゾキャバ求人.COM',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => '海外リゾキャバ求人.COM',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $ogImageAbsolute,
                    ],
                ],
                'description' => $description,
                'image' => $ogImageAbsolute,
                'mainEntityOfPage' => $pageUrl,
            ],
        ],
    ];
}

$job_list = get_job_list_with_images();
$pickup_jobs = [];
$new_jobs = [];
if (is_array($job_list)) {
    foreach ($job_list as $job) {
        $meta = json_decode($job['meta_json'] ?? '', true);
        if (!is_array($meta)) {
            continue;
        }
        $sections = $meta['home_sections'] ?? [];
        if (!is_array($sections)) {
            $sections = [];
        }
        if (in_array('pickup', $sections, true)) {
            $pickup_jobs[] = $job;
        }
        if (in_array('new', $sections, true)) {
            $new_jobs[] = $job;
        }
    }
    $sortJobs = function (&$jobs) {
        usort($jobs, function ($a, $b) {
            $timeA = $a['created_at'] ?? $a['published_at'] ?? $a['updated_at'] ?? '';
            $timeB = $b['created_at'] ?? $b['published_at'] ?? $b['updated_at'] ?? '';
            $tsA = $timeA ? strtotime($timeA) : 0;
            $tsB = $timeB ? strtotime($timeB) : 0;
            return $tsB <=> $tsA;
        });
    };
    if (!empty($pickup_jobs)) {
        $sortJobs($pickup_jobs);
        $pickup_jobs = array_slice($pickup_jobs, 0, 8);
    }
    if (!empty($new_jobs)) {
        $sortJobs($new_jobs);
        $new_jobs = array_slice($new_jobs, 0, 8);
    }
} else {
    $pickup_jobs = [];
    $new_jobs = [];
}

if (!function_exists('build_job_card_context')) {
    function build_job_card_context(array $job): array
    {
        $image = '/assets/images/jobs/no-image-1280w.jpg';
        if (!empty($job['images']) && is_array($job['images'])) {
            $firstImage = $job['images'][0] ?? null;
            if (is_array($firstImage) && !empty($firstImage['image_url'])) {
                $image = $firstImage['image_url'];
            }
        }

        $locationParts = [];
        if (!empty($job['city'])) {
            $locationParts[] = $job['city'];
        }
        if (!empty($job['region_prefecture']) && !in_array($job['region_prefecture'], $locationParts, true)) {
            $locationParts[] = $job['region_prefecture'];
        }
        if (!empty($job['country']) && !in_array($job['country'], $locationParts, true)) {
            $locationParts[] = $job['country'];
        }
        $location = implode(' / ', array_filter($locationParts));

        $employment = $job['employment_type'] ?? '';

        $salaryMin = isset($job['salary_min']) ? (int)$job['salary_min'] : null;
        $salaryMax = isset($job['salary_max']) ? (int)$job['salary_max'] : null;
        $salaryUnit = $job['salary_unit'] ?? 'HOUR';
        $unitLabel = '時給';
        if ($salaryUnit === 'MONTH') {
            $unitLabel = '月給';
        } elseif ($salaryUnit === 'DAY') {
            $unitLabel = '日給';
        }
        $salaryLabel = '';
        if ($salaryMin !== null && $salaryMax !== null && $salaryMax > $salaryMin) {
            $salaryLabel = sprintf('%s %s〜%s円', $unitLabel, number_format($salaryMin), number_format($salaryMax));
        } elseif ($salaryMin !== null) {
            $salaryLabel = sprintf('%s %s円', $unitLabel, number_format($salaryMin));
        } elseif ($salaryMax !== null) {
            $salaryLabel = sprintf('%s %s円', $unitLabel, number_format($salaryMax));
        }

        $meta = json_decode($job['meta_json'] ?? '', true);
        $period = '';
        $qualifications = [];
        $additionalTags = [];
        if (is_array($meta)) {
            if (!empty($meta['period'])) {
                $period = (string)$meta['period'];
            }
            if (!empty($meta['qualifications']) && is_array($meta['qualifications'])) {
                foreach ($meta['qualifications'] as $qualification) {
                    $qualification = trim((string)$qualification);
                    if ($qualification !== '') {
                        $qualifications[] = $qualification;
                    }
                }
            }
            if (!empty($meta['hours'])) {
                $additionalTags[] = '勤務時間: ' . (string)$meta['hours'];
            }
            if (!empty($meta['holiday'])) {
                $additionalTags[] = '休日: ' . (string)$meta['holiday'];
            }
            if (!empty($meta['job_code'])) {
                $additionalTags[] = '求人コード: ' . (string)$meta['job_code'];
            }
        }

        $benefits = [];
        if (!empty($job['benefits_json'])) {
            $decodedBenefits = json_decode($job['benefits_json'], true);
            if (is_array($decodedBenefits)) {
                foreach ($decodedBenefits as $benefit) {
                    $benefit = trim((string)$benefit);
                    if ($benefit !== '') {
                        $benefits[] = $benefit;
                    }
                }
            }
        }

        $tags = array_values(array_unique(array_filter(array_merge($benefits, $qualifications, $additionalTags))));

        $descriptionSource = $job['description_text'] ?? '';
        $description = trim(preg_replace('/\s+/u', ' ', (string)$descriptionSource));
        if ($description === '' && !empty($job['description_html'])) {
            $description = trim(strip_tags((string)$job['description_html']));
        }
        if ($description !== '') {
            $description = mb_strimwidth($description, 0, 120, '…', 'UTF-8');
        }

        $updatedAt = $job['updated_at'] ?? ($job['published_at'] ?? ($job['created_at'] ?? ''));
        $updatedDate = $updatedAt ? date('Y.m.d', strtotime($updatedAt)) : '';

        return [
            'id' => (int)($job['id'] ?? 0),
            'title' => (string)($job['title'] ?? ''),
            'image' => $image,
            'location' => $location,
            'employment' => (string)$employment,
            'salary' => $salaryLabel,
            'period' => $period,
            'tags' => $tags,
            'description' => $description !== '' ? $description : '求人の詳細はリンク先でご確認ください。',
            'updatedDate' => $updatedDate,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php require_once __DIR__ . '/includes/header.php'; ?>
    <?php if ($canonical !== ''): ?>
        <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php if ($announcement && $jsonLdData !== null): ?>
    <script type="application/ld+json">
        <?= json_encode($jsonLdData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    </script>
    <?php endif; ?>

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
        .prose {
            line-height: 1.8;
        }
        .prose h2 {
            font-size: 1.25rem;
            font-weight: 700;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--brand-primary);
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
        }
        .prose p, .prose ul {
            margin-bottom: 1.5rem;
        }
        .prose a {
            color: var(--brand-primary);
            text-decoration: underline;
        }
        .prose a:hover {
            opacity: 0.8;
        }
        .section-title { font-size: 1.75rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; letter-spacing: 0.1em; }
        .section-subtitle { font-size: 0.875rem; text-align: center; color: var(--text-secondary); margin-bottom: 3rem; letter-spacing: 0.1em; }
        .swiper-nav-button { color: var(--text-secondary); background-color: transparent; width: 44px; height: 44px; transition: all 0.2s ease; --swiper-navigation-size: 28px; border-radius: 50%; display: none; }
        @media (min-width: 768px) { .swiper-nav-button { display: flex; } }
        .swiper-nav-button:hover { background-color: var(--bg-muted); color: var(--brand-primary); }
        .swiper-button-disabled { opacity: 0.1; pointer-events: none; }
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
                        <a href="/partners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
                        <a href="/announcements/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">お知らせ</a>
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
                        <a href="/partners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">掲載店舗</a>
                        <a href="/announcements/" class="block px-3 py-2 text-sm font-medium text-[var(--brand-primary)] bg-[var(--bg-muted)]">お知らせ</a>
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
            <div class="bg-white border-b border-[var(--border-color)]">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                    <nav class="text-xs mb-3" aria-label="Breadcrumb">
                      <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><a href="/announcements/" class="text-gray-500 hover:text-[var(--brand-primary)]">お知らせ一覧</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><span class="text-gray-700 font-medium truncate max-w-[200px] sm:max-w-md"><?= htmlspecialchars($announcementTitle, ENT_QUOTES, 'UTF-8'); ?></span></li>
                      </ol>
                    </nav>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto bg-white border border-[var(--border-color)] p-6 sm:p-10">
                    <div class="mb-6 pb-6 border-b border-[var(--border-color)]">
                        <div class="flex items-center gap-x-4 mb-3">
                            <?php if ($publishedDate !== ''): ?>
                                <p class="text-sm text-slate-500"><?= htmlspecialchars($publishedDate, ENT_QUOTES, 'UTF-8'); ?></p>
                            <?php endif; ?>
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 <?= $announcement ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700'; ?>"><?= $announcement ? '公開中' : '未公開'; ?></span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)] !leading-tight"><?= htmlspecialchars($announcementTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                    </div>

                    <article class="prose max-w-none"><?= $bodyHtml ?></article>

                    <div class="mt-10 pt-8 border-t border-[var(--border-color)] text-center">
                        <a href="/announcements/" class="inline-flex items-center justify-center gap-x-2 w-full sm:w-auto px-8 py-3 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>お知らせ一覧へ戻る</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Contents -->
            <div class="py-16 sm:py-24 bg-white border-t border-[var(--border-color)] space-y-24">
                 <!-- ★ ピックアップ求人 -->
                 <section id="pickup-jobs" class="job-section">
                    <div class="max-w-7xl mx-auto">
                       <div class="px-4 sm:px-6 lg:px-8"><h2 class="section-title">ピックアップ求人</h2><p class="section-subtitle">PICKUP JOBS</p></div>
                       <div class="relative">
                           <div class="swiper card-carousel">
                               <div class="swiper-wrapper">
                                   <?php if (!empty($pickup_jobs)): ?>
                                       <?php foreach ($pickup_jobs as $job):
                                           $card = build_job_card_context($job);
                                           if ($card['id'] <= 0) {
                                               continue;
                                           }
                                           $imageAltTitle = $card['title'] !== '' ? $card['title'] : '求人';
                                       ?>
                                           <div class="swiper-slide">
                                               <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                                   <div class="relative">
                                                       <div class="overflow-hidden"><img src="<?= htmlspecialchars($card['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($imageAltTitle, ENT_QUOTES, 'UTF-8'); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                                   </div>
                                                   <div class="p-4 flex flex-col flex-grow">
                                                       <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?= $card['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?= htmlspecialchars($card['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                       <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                           <?php if ($card['location'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['location'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['employment'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['employment'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['salary'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['salary'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['period'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="calendar-days" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['period'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                       </div>
                                                       <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                           <?php foreach ($card['tags'] as $tag): ?>
                                                               <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;"><i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i> <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></span>
                                                           <?php endforeach; ?>
                                                       </div>
                                                       <p class="text-xs text-slate-500 flex-grow description-truncate"><?= htmlspecialchars($card['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                       <?php if ($card['updatedDate'] !== ''): ?>
                                                           <div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: <?= htmlspecialchars($card['updatedDate'], ENT_QUOTES, 'UTF-8'); ?></span></span></div>
                                                       <?php endif; ?>
                                                       <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?= $card['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                                   </div>
                                               </div>
                                           </div>
                                       <?php endforeach; ?>
                                   <?php else: ?>
                                       <div class="swiper-slide">
                                           <div class="bg-white border border-[var(--border-color)] p-6 sm:p-8 text-center text-sm text-slate-600">
                                               現在表示できる求人はありません。
                                           </div>
                                       </div>
                                   <?php endif; ?>
                               </div>
                               <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                               <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                           </div>
                           <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                               <a href="/jobs/pickup/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                           </div>
                        </div>
                    </div>
                </section>

                <!-- ★ 新着求人 -->
                <section id="new-jobs" class="job-section">
                    <div class="max-w-7xl mx-auto">
                        <div class="px-4 sm:px-6 lg:px-8"><h2 class="section-title">新着求人</h2><p class="section-subtitle">NEW JOBS</p></div>
                       <div class="relative">
                           <div class="swiper card-carousel">
                               <div class="swiper-wrapper">
                                   <?php if (!empty($new_jobs)): ?>
                                       <?php foreach ($new_jobs as $job):
                                           $card = build_job_card_context($job);
                                           if ($card['id'] <= 0) {
                                               continue;
                                           }
                                           $imageAltTitle = $card['title'] !== '' ? $card['title'] : '求人';
                                       ?>
                                           <div class="swiper-slide">
                                               <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                                   <div class="relative">
                                                       <div class="overflow-hidden"><img src="<?= htmlspecialchars($card['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($imageAltTitle, ENT_QUOTES, 'UTF-8'); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                                   </div>
                                                   <div class="p-4 flex flex-col flex-grow">
                                                       <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?= $card['id']; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?= htmlspecialchars($card['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                                       <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                           <?php if ($card['location'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['location'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['employment'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['employment'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['salary'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['salary'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                           <?php if ($card['period'] !== ''): ?>
                                                               <p class="flex items-center gap-x-2"><i data-lucide="calendar-days" class="w-4 h-4 flex-shrink-0"></i><span><?= htmlspecialchars($card['period'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                                                           <?php endif; ?>
                                                       </div>
                                                       <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">
                                                           <?php foreach ($card['tags'] as $tag): ?>
                                                               <span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;"><i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i> <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></span>
                                                           <?php endforeach; ?>
                                                       </div>
                                                       <p class="text-xs text-slate-500 flex-grow description-truncate"><?= htmlspecialchars($card['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                       <?php if ($card['updatedDate'] !== ''): ?>
                                                           <div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: <?= htmlspecialchars($card['updatedDate'], ENT_QUOTES, 'UTF-8'); ?></span></span></div>
                                                       <?php endif; ?>
                                                       <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?= $card['id']; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                                   </div>
                                               </div>
                                           </div>
                                       <?php endforeach; ?>
                                   <?php else: ?>
                                       <div class="swiper-slide">
                                           <div class="bg-white border border-[var(--border-color)] p-6 sm:p-8 text-center text-sm text-slate-600">
                                               現在表示できる求人はありません。
                                           </div>
                                       </div>
                                   <?php endif; ?>
                               </div>
                               <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                               <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                           </div>
                           <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                               <a href="/jobs/new/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                           </div>
                        </div>
                    </div>
                </section>
                
                <!-- 特集・コラム -->
                <section id="features" class="job-section">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div><h2 class="section-title">特集・コラム</h2><p class="section-subtitle">FEATURES & COLUMNS</p></div>
                        <div class="relative">
                            <div class="swiper card-carousel">
                                <div id="features-grid" class="swiper-wrapper"><div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full"><div class="overflow-hidden"><img src="/assets/images/articles/article-default-600w.jpg" alt="海外キャバクラで初めて働きたい方向けの完全ガイドの画像" class="w-full aspect-video object-cover"></div><div class="p-4 flex flex-col flex-grow"><p class="text-xs font-bold text-slate-600 mb-2">初めての方</p><h3 class="font-bold text-base mb-3 leading-tight"><a href="/feature/4/" class="hover:text-[var(--brand-primary)] transition-colors">海外キャバクラで初めて働きたい方向けの完全ガイド</a></h3><p class="text-xs text-slate-500 flex-grow description-truncate">初めての方｜海外リゾキャバ求人.COM
  
  
    :root{ --brand:#00bfa6; --ink:#222; --muted:#667085; --bg:#ffffff; --card:#f7f7f8; --bo…</p><div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: </span></span></div><div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/feature/4/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div></div></div><div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full"><div class="overflow-hidden"><img src="/assets/images/articles/article-default-600w.jpg" alt="【エリア紹介】初めての海外リゾバ！ベトナム・ハノイの魅力とは？の画像" class="w-full aspect-video object-cover"></div><div class="p-4 flex flex-col flex-grow"><p class="text-xs font-bold text-slate-600 mb-2">エリア紹介</p><h3 class="font-bold text-base mb-3 leading-tight"><a href="/feature/1/" class="hover:text-[var(--brand-primary)] transition-colors">【エリア紹介】初めての海外リゾバ！ベトナム・ハノイの魅力とは？</a></h3><p class="text-xs text-slate-500 flex-grow description-truncate">ハノイの魅力を紹介します。</p><div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: 2025.09.22</span></span></div><div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/feature/1/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div></div></div><div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full"><div class="overflow-hidden"><img src="/assets/images/articles/article-default-600w.jpg" alt="【ノウハウ】海外リゾバ準備チェックリストの画像" class="w-full aspect-video object-cover"></div><div class="p-4 flex flex-col flex-grow"><p class="text-xs font-bold text-slate-600 mb-2">ノウハウ</p><h3 class="font-bold text-base mb-3 leading-tight"><a href="/feature/2/" class="hover:text-[var(--brand-primary)] transition-colors">【ノウハウ】海外リゾバ準備チェックリスト</a></h3><p class="text-xs text-slate-500 flex-grow description-truncate">準備物のチェックリスト。</p><div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: 2025.09.22</span></span></div><div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/feature/2/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div></div></div><div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full"><div class="overflow-hidden"><img src="/assets/images/articles/article-default-600w.jpg" alt="【体験談】沖縄で働いてみたの画像" class="w-full aspect-video object-cover"></div><div class="p-4 flex flex-col flex-grow"><p class="text-xs font-bold text-slate-600 mb-2">体験談</p><h3 class="font-bold text-base mb-3 leading-tight"><a href="/feature/3/" class="hover:text-[var(--brand-primary)] transition-colors">【体験談】沖縄で働いてみた</a></h3><p class="text-xs text-slate-500 flex-grow description-truncate">沖縄での体験談。</p><div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: 2025.09.22</span></span></div><div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/feature/3/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div></div></div></div>
                            </div>
                            <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                            <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                        </div>
                        <div class="text-center mt-12">
                            <a href="/features/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        
        <?php require_once __DIR__ . '/includes/footer.php'; ?>
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

            const swiperInstances = new Map();

            // --- DATA (Same as job-list.html, minified for brevity) ---


            // --- DOM MANIPULATION (Same as job-list.html, minified for brevity) ---
            const createJobCard=(e)=>{const t=document.createElement("div");t.className="swiper-slide";const a=[e.isPR?`<span class="inline-block bg-green-500 text-white text-xs font-bold px-2.5 py-1">PR</span>`:"",e.isNew?`<span class="inline-block bg-yellow-400 text-slate-800 text-xs font-bold px-2.5 py-1">NEW</span>`:""].filter(Boolean).join("");return t.innerHTML=`
                    <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="relative">
                            <div class="overflow-hidden"><img src="${e.image}" alt="${e.title}の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                            <div class="absolute top-2 left-2 flex gap-x-2">${a}</div>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-base mb-3 leading-tight"><a href="/jobs/${e.id}/" class="hover:text-[var(--brand-primary)] transition-colors">${e.title}</a></h3>
                            <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3"><p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span>${e.location}</span></p><p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span>${e.jobType}</span></p><p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span>${e.salary}</span></p><p class="flex items-center gap-x-2"><i data-lucide="calendar-days" class="w-4 h-4 flex-shrink-0"></i><span>${e.period}</span></p></div>
                            <p class="text-xs text-slate-500 flex-grow description-truncate">${e.description}</p>
                            <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/jobs/${e.id}/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                        </div>
                    </div>`,t};const createFeatureCard=(e)=>{const t=document.createElement("div");t.className="swiper-slide";const a=[e.isPR?`<span class="inline-block bg-green-500 text-white text-xs font-bold px-2.5 py-1">PR</span>`:"",e.isNew?`<span class="inline-block bg-yellow-400 text-slate-800 text-xs font-bold px-2.5 py-1">NEW</span>`:""].filter(Boolean).join("");return t.innerHTML=`
                    <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="relative">
                            <div class="overflow-hidden"><img src="${e.image}" alt="${e.title}の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                            <div class="absolute top-2 left-2 flex gap-x-2">${a}</div>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                             <p class="text-xs font-bold ${e.color} mb-2">${e.type}</p>
                            <h3 class="font-bold text-base mb-3 leading-tight"><a href="/features/${e.id}/" class="hover:text-[var(--brand-primary)] transition-colors">${e.title}</a></h3>
                            <p class="text-xs text-slate-500 flex-grow description-truncate">${e.description}</p>
                            <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/features/${e.id}/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div>
                        </div>
                    </div>`,t};

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

                    if (swiperInstances.has(id)) { swiperInstances.get(id).destroy(true, true); swiperInstances.delete(id); }

                    wrapper.className = 'swiper-wrapper';
                    swiperContainer.className = 'swiper card-carousel';
                    slides.forEach(slide => { slide.className = 'swiper-slide'; slide.style.display = 'block'; });
                    if(prevBtn) prevBtn.style.display = 'none';
                    if(nextBtn) nextBtn.style.display = 'none';

                    if (isDesktop) {
                        swiperContainer.classList.add('px-4', 'sm:px-6', 'lg:px-8');
                        wrapper.classList.add('grid', 'grid-cols-4', 'gap-6');
                        slides.forEach((slide) => { slide.classList.remove('swiper-slide'); });
                        slides.forEach((slide, i) => { if (i >= 8) slide.style.display = 'none'; });
                    } else {
                        wrapper.classList.add('!pl-4', 'sm:!pl-6', '!pr-4', 'sm:!pr-6');
                        const swiper = new Swiper(swiperContainer, {
                            loop: false, slidesPerView: 1.2, spaceBetween: 16,
                            navigation: { nextEl: nextBtn, prevEl: prevBtn },
                            breakpoints: { 640: { slidesPerView: 2.2 }, 768: { slidesPerView: 3.2 } },
                            on: { init: function() { this.update(); } }
                        });
                        swiperInstances.set(id, swiper);
                        if (!swiper.isLocked) { 
                           if(prevBtn) prevBtn.style.display = 'flex';
                           if(nextBtn) nextBtn.style.display = 'flex';
                        }
                    }
                });
            }

            function debounce(func) {
                let timer;
                return function(event) {
                    if (timer) clearTimeout(timer);
                    timer = setTimeout(func, 250, event);
                };
            }

            // --- INITIALIZATION ---
            function init() {
                const pickupJobs = [];
                const newJobs = [];
                const featuresData = [];

                const pickupContainer = document.querySelector('#pickup-jobs .swiper-wrapper');
                if (pickupContainer) pickupContainer.append(...pickupJobs.map(createJobCard));
                
                const newJobsContainer = document.querySelector('#new-jobs .swiper-wrapper');
                if (newJobsContainer) newJobsContainer.append(...newJobs.map(createJobCard));

                const featuresContainer = document.querySelector('#features-grid');
                if (featuresContainer) featuresContainer.append(...featuresData.map(createFeatureCard));
                
                setupLayouts();
                lucide.createIcons();
            }

            init();
            window.addEventListener('resize', debounce(setupLayouts));
        });
    </script>
</body>
</html>
