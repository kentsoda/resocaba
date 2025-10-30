<?php
require_once __DIR__ . '/../config/functions.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

$filters = [];
if (isset($_GET['q']) && $_GET['q'] !== '') {
    $filters['keyword'] = $_GET['q'];
}
// 条件検索: area[]/area/country[]/country を後端フィルタに反映
if (isset($_GET['area'])) {
    if (is_array($_GET['area'])) {
        $filters['area'] = $_GET['area'][0] ?? '';
    } else {
        $filters['area'] = $_GET['area'];
    }
}
if (empty($filters['area']) && isset($_GET['country'])) {
    if (is_array($_GET['country'])) {
        $filters['area'] = $_GET['country'][0] ?? '';
    } else {
        $filters['area'] = $_GET['country'];
    }
}

$total = 0;
$jobs = [];
try {
    $total = (int)count_jobs($filters);
    $jobs = get_jobs($filters, $offset, $limit);
    if ($jobs === false) {
        $jobs = [];
    }
} catch (Throwable $e) {
    $total = 0;
    $jobs = [];
}
$totalPages = max(1, (int)ceil(($total > 0 ? $total : 1) / $limit));

function h($v)
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    $title = '求人一覧｜海外・リゾートキャバクラ求人.COM';
    $description = '検索条件に一致した海外・リゾートキャバクラの求人一覧です。あなたの希望にぴったりの高収入・短期リゾバを見つけよう。';
    $og_title = $title;
    $og_description = '検索条件に一致した海外・リゾートキャバクラの求人一覧です。';
    $og_type = 'website';
    $og_url = 'https://example.com/jobs/';
    $og_image = '/assets/images/articles/jobs-ogp-1200x630.jpg';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <!-- SEO: JSON-LD for Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [{
                    "@type": "BreadcrumbList",
                    "itemListElement": [{
                        "@type": "ListItem",
                        "position": 1,
                        "name": "トップ",
                        "item": "https://example.com/"
                    }, {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "求人一覧",
                        "item": "https://example.com/jobs/"
                    }]
                },
                {
                    "@type": "ItemList",
                    "name": "求人検索結果",
                    "numberOfItems": <?php echo (int)$total; ?>,
                    "itemListElement": []
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
            --bg-base: #f1f5f9;
            /* slate-100 */
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
            font-size: 0.9375rem;
            /* 15px */
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

        /* Swiper Navigation Buttons */
        .swiper-nav-button {
            color: var(--text-secondary);
            background-color: transparent;
            width: 44px;
            height: 44px;
            transition: all 0.2s ease;
            --swiper-navigation-size: 28px;
            border-radius: 50%;
            display: none;
            /* Initially hidden */
        }

        @media (min-width: 768px) {
            .swiper-nav-button {
                display: flex;
            }
        }

        .swiper-nav-button:hover {
            background-color: var(--bg-muted);
            color: var(--brand-primary);
        }

        .swiper-button-disabled {
            opacity: 0.1;
            pointer-events: none;
        }

        /* Hide carousel arrows on small screens */
        @media (max-width: 768px) {

            .swiper-button-prev,
            .swiper-button-next {
                display: none !important;
            }
        }
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
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                </svg>
                            </div>
                            <span class="font-bold text-lg text-[var(--text-primary)] tracking-wide">海外リゾキャバ求人.COM</span>
                        </a>
                    </div>
                    <nav class="hidden lg:flex items-center gap-x-6">
                        <a href="/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">トップ</a>
                        <a href="/for-beginners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">初めての方</a>
                        <a href="/jobs/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">求人検索</a>
                        <a href="/partners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
                        <a href="/announcements/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
                        <a href="/features/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">特集・コラム</a>
                        <a href="/faq/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">よくある質問</a>
                        <a href="/contact-ad/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">広告掲載</a>
                    </nav>
                    <div class="hidden lg:flex items-center gap-x-3">
                        <a href="/login/" class="px-5 py-2 text-sm font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] transition-colors">ログイン</a>
                        <a href="/register/" class="px-5 py-2 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">無料登録</a>
                    </div>
                    <button id="mobile-menu-button" aria-label="メニューを開く" class="lg:hidden p-2 text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--brand-primary)]">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
                <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-[var(--border-color)]">
                    <nav class="flex flex-col p-4 gap-y-3">
                        <a href="/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">トップ</a>
                        <a href="/for-beginners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">初めての方</a>
                        <a href="/jobs/" class="block px-3 py-2 text-sm font-medium text-[var(--brand-primary)] bg-[var(--bg-muted)]">求人検索</a>
                        <a href="/partners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">掲載店舗</a>
                        <a href="/announcements/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">お知らせ</a>
                        <a href="/features/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">特集・コラム</a>
                        <a href="/faq/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">よくある質問</a>
                        <a href="/contact-ad/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">広告掲載</a>
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
                    <!-- Breadcrumbs -->
                    <nav class="text-xs mb-3" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a>
                                <i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i>
                            </li>
                            <li class="flex items-center">
                                <span class="text-gray-700 font-medium">求人一覧</span>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)]" id="page-title">求人検索結果</h1>
                    <p class="text-sm text-[var(--text-secondary)] mt-1" id="page-description">あなたの希望に合ったリゾート・海外キャバクラの求人が見つかりました。</p>
                </div>
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <!-- Search Results Area -->
                <div id="search-results-area" <?php if ($total <= 0) echo ' class="hidden"'; ?>>
                    <!-- Search Summary & Controls -->
                    <div class="bg-white border border-[var(--border-color)] p-4 sm:p-6 mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex-1">
                                <h2 class="font-bold text-lg text-slate-800 mb-3">現在の検索条件</h2>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 bg-slate-100 text-slate-700 text-xs font-medium">エリア: 沖縄 <button class="group"><i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 group-hover:text-red-600"></i></button></span>
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 bg-slate-100 text-slate-700 text-xs font-medium">期間: 1ヶ月以内 <button class="group"><i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 group-hover:text-red-600"></i></button></span>
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 bg-slate-100 text-slate-700 text-xs font-medium">メリット: 日払いOK <button class="group"><i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 group-hover:text-red-600"></i></button></span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="archive-job/" class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 py-2.5 px-6 bg-[var(--brand-primary)] text-white font-bold hover:bg-opacity-90 transition-opacity text-sm">
                                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                                    <span>検索条件を変更</span>
                                </a>
                            </div>
                        </div>
                        <div class="border-t border-[var(--border-color)] mt-6 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <p class="text-sm font-bold text-slate-800" id="job-count-display">
                                <span class="text-[var(--brand-primary)] text-xl"><?php echo (int)$total; ?></span> 件の求人が見つかりました
                            </p>
                            <div class="flex items-center gap-x-2">
                                <label for="sort" class="text-sm font-medium text-slate-600">並び替え:</label>
                                <select id="sort" class="border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                    <option>新着順</option>
                                    <option>給与の高い順</option>
                                    <option>人気順</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Job List -->
                    <div id="filters-accordion" class="hidden bg-white border border-[var(--border-color)] p-4 sm:p-6 mb-8">
                        <form method="get" action="/jobs/" id="filters-form" class="space-y-4"><input type="hidden" name="q" value=""><input type="hidden" name="salary_min" value="0"><input type="hidden" name="sort" value="new"><input type="hidden" name="country" value=""><input type="hidden" name="page" value="1">
                            <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                                <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="map-pin" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>エリアで探す</span></h3>
                                <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="area[]" value="ハノイ" class="w-4 h-4"><span>ハノイ</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="area[]" value="バンコク" class="w-4 h-4"><span>バンコク</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="area[]" value="プノンペン" class="w-4 h-4"><span>プノンペン</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="area[]" value="東京" class="w-4 h-4"><span>東京</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="area[]" value="沖縄" class="w-4 h-4"><span>沖縄</span></label></div>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                                <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="calendar" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>働く期間で探す</span></h3>
                                <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="period[]" value="short" class="w-4 h-4"><span>1ヶ月未満</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="period[]" value="mid" class="w-4 h-4"><span>1〜3ヶ月</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="period[]" value="long" class="w-4 h-4"><span>長期（3ヶ月〜）</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="period[]" value="summer" class="w-4 h-4"><span>夏休み</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="period[]" value="winter" class="w-4 h-4"><span>年末年始</span></label></div>
                            </div>
                            <div class="flex flex-col md:flex-row md:items-start gap-4 py-6 border-b border-[var(--border-color)]">
                                <h3 class="flex items-center gap-2 font-bold text-slate-800 text-base w-full md:w-[180px] flex-shrink-0 mb-4 md:mb-0"><i data-lucide="briefcase" class="w-5 h-5 text-[var(--brand-primary)]"></i><span>職種で探す</span></h3>
                                <div class="flex flex-wrap gap-x-6 gap-y-4 text-sm"><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="employment[]" value="キャスト" class="w-4 h-4"><span>キャスト</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="employment[]" value="キャバクラ" class="w-4 h-4"><span>キャバクラ</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="employment[]" value="キャバクラキャスト" class="w-4 h-4"><span>キャバクラキャスト</span></label><label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="employment[]" value="ホール" class="w-4 h-4"><span>ホール</span></label></div>
                            </div>
                            <div class="flex items-center gap-3 pt-2"><button type="submit" class="inline-flex items-center justify-center gap-x-2 px-6 py-2.5 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all">この条件で検索</button><button type="button" id="filters-close" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">閉じる</button></div>
                        </form>
                    </div>
                    <div id="job-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($jobs as $job):
                            $jobId = (int)($job['id'] ?? 0);
                            $title = $job['title'] ?? '';
                            $city = $job['city'] ?? '';
                            $region = $job['region_prefecture'] ?? '';
                            $country = $job['country'] ?? '';
                            $employment = $job['employment_type'] ?? '';
                            $salaryMin = isset($job['salary_min']) ? (int)$job['salary_min'] : null;
                            $salaryUnit = $job['salary_unit'] ?? '';
                            $updatedAt = $job['updated_at'] ?? ($job['published_at'] ?? ($job['created_at'] ?? ''));
                            $updatedStr = $updatedAt ? date('Y.m.d', strtotime($updatedAt)) : '';
                            $imageUrl = null;
                            if (!empty($job['images']) && is_array($job['images'])) {
                                $firstImage = $job['images'][0] ?? null;
                                if ($firstImage && !empty($firstImage['image_url'])) {
                                    $imageUrl = $firstImage['image_url'];
                                }
                            }
                            if (!$imageUrl) {
                                continue;
                            }
                            $area = $city ?: ($region ?: $country);
                            $salaryLabel = '';
                            if ($salaryMin !== null) {
                                if ($salaryUnit === 'MONTH') {
                                    $salaryLabel = '月給 ' . number_format($salaryMin) . '円';
                                } elseif ($salaryUnit === 'DAY') {
                                    $salaryLabel = '日給 ' . number_format($salaryMin) . '円';
                                } else {
                                    $salaryLabel = '時給 ' . number_format($salaryMin) . '円';
                                }
                            }
                        ?>
                            <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                <div class="relative">
                                    <div class="overflow-hidden"><img src="<?php echo h($imageUrl); ?>" alt="<?php echo h($title); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $jobId; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo h($title); ?></a></h3>
                                    <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                        <?php if ($area): ?>
                                            <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($area); ?></span></p>
                                        <?php endif; ?>
                                        <?php if ($employment): ?>
                                            <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($employment); ?></span></p>
                                        <?php endif; ?>
                                        <?php if ($salaryLabel): ?>
                                            <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($salaryLabel); ?></span></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container"></div>
                                    <p class="text-xs text-slate-500 flex-grow description-truncate"></p>
                                    <div class="text-right text-xs text-slate-400 mt-2">
                                        <?php if ($updatedStr): ?>
                                            <span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: <?php echo h($updatedStr); ?></span></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $jobId; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav id="pagination-area" class="mt-12 flex items-center justify-center" aria-label="Pagination">
                            <ul class="inline-flex items-center space-x-1">
                                <?php $queryBase = $_GET;
                                unset($queryBase['page']);
                                $qstr = http_build_query($queryBase);
                                $suffix = $qstr ? '&' . h($qstr) : ''; ?>
                                <li>
                                    <?php if ($page > 1): ?>
                                        <a href="/jobs/?page=<?php echo $page - 1; ?><?php echo $suffix; ?>" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-slate-500 bg-white border border-[var(--border-color)] rounded-l-lg hover:bg-slate-100 hover:text-slate-700"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
                                    <?php else: ?>
                                        <span class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-slate-300 bg-white border border-[var(--border-color)] rounded-l-lg"><i data-lucide="chevron-left" class="w-4 h-4"></i></span>
                                    <?php endif; ?>
                                </li>
                                <?php $start = max(1, $page - 2);
                                $end = min($totalPages, $page + 2);
                                for ($n = $start; $n <= $end; $n++): ?>
                                    <li>
                                        <?php if ($n === $page): ?>
                                            <a href="#" aria-current="page" class="z-10 flex items-center justify-center px-3 h-8 leading-tight text-white border bg-[var(--brand-primary)] border-[var(--brand-primary)] hover:bg-opacity-90"><?php echo $n; ?></a>
                                        <?php else: ?>
                                            <a href="/jobs/?page=<?php echo $n; ?><?php echo $suffix; ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-slate-500 bg-white border border-[var(--border-color)] hover:bg-slate-100 hover:text-slate-700"><?php echo $n; ?></a>
                                        <?php endif; ?>
                                    </li>
                                <?php endfor; ?>
                                <li>
                                    <?php if ($page < $totalPages): ?>
                                        <a href="/jobs/?page=<?php echo $page + 1; ?><?php echo $suffix; ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-slate-500 bg-white border border-[var(--border-color)] rounded-r-lg hover:bg-slate-100 hover:text-slate-700"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                                    <?php else: ?>
                                        <span class="flex items-center justify-center px-3 h-8 leading-tight text-slate-300 bg-white border border-[var(--border-color)] rounded-r-lg"><i data-lucide="chevron-right" class="w-4 h-4"></i></span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="mt-12 text-center space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-center sm:space-x-4">
                        <a href="archive-job/" class="inline-flex items-center justify-center gap-x-2 w-full sm:w-auto px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">
                            <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                            <span>条件を変更して再検索する</span>
                        </a>
                        <a href="/" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                            トップページへ戻る
                        </a>
                    </div>
                </div>

                <!-- Zero Results Area (Hidden by default) -->
                <div id="zero-results-area" class="<?php echo ($total > 0 ? 'hidden' : ''); ?>">
                    <div class="bg-white border border-[var(--border-color)] p-8 sm:p-12 text-center">
                        <div class="mx-auto w-fit bg-slate-100 rounded-full p-4">
                            <i data-lucide="search-x" class="w-12 h-12 text-slate-400"></i>
                        </div>
                        <h2 class="mt-6 text-xl font-bold text-slate-800">ご指定の条件に合う求人は見つかりませんでした。</h2>
                        <p class="mt-2 text-sm text-slate-600 max-w-md mx-auto">検索条件を変更するか、条件をリセットして再度お試しください。新着求人もあわせてご確認ください。</p>
                        <div class="mt-8 text-center space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-center sm:space-x-4">
                            <a href="archive-job/" class="inline-flex items-center justify-center gap-x-2 w-full sm:w-auto px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">
                                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                                <span>検索条件を変更する</span>
                            </a>
                            <a href="/jobs/new/" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-3 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                                新着求人をすべて見る
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Contents -->
            <div class="py-16 sm:py-24 bg-white border-t border-[var(--border-color)] space-y-24">
                <?php
                $sameAreaJobs = [];
                $pickupJobs = [];
                try {
                    $areaParam = null;
                    if (isset($_GET['area'])) {
                        if (is_array($_GET['area'])) {
                            $areaParam = $_GET['area'][0] ?? null;
                        } else {
                            $areaParam = $_GET['area'];
                        }
                    }
                    if (!$areaParam && isset($_GET['country'])) {
                        if (is_array($_GET['country'])) {
                            $areaParam = $_GET['country'][0] ?? null;
                        } else {
                            $areaParam = $_GET['country'];
                        }
                    }

                    if ($areaParam) {
                        $sameAreaJobs = get_jobs(['area' => $areaParam], 0, 8) ?: [];
                    } else {
                        $sameAreaJobs = [];
                    }

                    $pickupCandidates = get_jobs([], 0, 100) ?: [];
                    foreach ($pickupCandidates as $j) {
                        $meta = json_decode($j['meta_json'] ?? '', true);
                        if (!empty($meta['home_sections']) && is_array($meta['home_sections']) && in_array('pickup', $meta['home_sections'], true)) {
                            $pickupJobs[] = $j;
                            if (count($pickupJobs) >= 8) {
                                break;
                            }
                        }
                    }
                } catch (Throwable $e) {
                    $sameAreaJobs = [];
                    $pickupJobs = [];
                }
                ?>
                <!-- ★ 同じエリアの求人 -->
                <section id="pickup-jobs" class="job-section">
                    <div class="max-w-7xl mx-auto">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <h2 class="section-title">同じエリアの求人</h2>
                            <p class="section-subtitle">JOBS IN SAME AREA</p>
                        </div>
                        <div class="relative">
                            <div class="swiper card-carousel">
                                <div class="swiper-wrapper">
                                    <?php foreach ($sameAreaJobs as $job) {
                                        $jobId = (int)($job['id'] ?? 0);
                                        $title = $job['title'] ?? '';
                                        $city = $job['city'] ?? '';
                                        $region = $job['region_prefecture'] ?? '';
                                        $country = $job['country'] ?? '';
                                        $employment = $job['employment_type'] ?? '';
                                        $salaryMin = isset($job['salary_min']) ? (int)$job['salary_min'] : null;
                                        $salaryUnit = $job['salary_unit'] ?? '';
                                        $imageUrl = null;
                                        if (!empty($job['images']) && is_array($job['images'])) {
                                            $firstImage = $job['images'][0] ?? null;
                                            if ($firstImage && !empty($firstImage['image_url'])) {
                                                $imageUrl = $firstImage['image_url'];
                                            }
                                        }
                                        if (!$imageUrl) {
                                            continue;
                                        }
                                        $area = $city ?: ($region ?: $country);
                                        $salaryLabel = '';
                                        if ($salaryMin !== null) {
                                            if ($salaryUnit === 'MONTH') {
                                                $salaryLabel = '月給 ' . number_format($salaryMin) . '円';
                                            } elseif ($salaryUnit === 'DAY') {
                                                $salaryLabel = '日給 ' . number_format($salaryMin) . '円';
                                            } else {
                                                $salaryLabel = '時給 ' . number_format($salaryMin) . '円';
                                            }
                                        }
                                    ?>
                                        <div class="swiper-slide">
                                            <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                                <div class="relative">
                                                    <div class="overflow-hidden"><img src="<?php echo h($imageUrl); ?>" alt="<?php echo h($title); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                                </div>
                                                <div class="p-4 flex flex-col flex-grow">
                                                    <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $jobId; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo h($title); ?></a></h3>
                                                    <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                        <?php if ($area): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($area); ?></span></p>
                                                        <?php endif; ?>
                                                        <?php if ($employment): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($employment); ?></span></p>
                                                        <?php endif; ?>
                                                        <?php if ($salaryLabel): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($salaryLabel); ?></span></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container"></div>
                                                    <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $jobId; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                            <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                        </div>
                        <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                            <a href="/jobs/pickup/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                        </div>
                    </div>
                </section>

                <!-- ★ ピックアップ求人 -->
                <section id="new-jobs" class="job-section">
                    <div class="max-w-7xl mx-auto">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <h2 class="section-title">ピックアップ求人</h2>
                            <p class="section-subtitle">PICKUP JOBS</p>
                        </div>
                        <div class="relative">
                            <div class="swiper card-carousel">
                                <div class="swiper-wrapper">
                                    <?php foreach ($pickupJobs as $job) {
                                        $jobId = (int)($job['id'] ?? 0);
                                        $title = $job['title'] ?? '';
                                        $city = $job['city'] ?? '';
                                        $region = $job['region_prefecture'] ?? '';
                                        $country = $job['country'] ?? '';
                                        $employment = $job['employment_type'] ?? '';
                                        $salaryMin = isset($job['salary_min']) ? (int)$job['salary_min'] : null;
                                        $salaryUnit = $job['salary_unit'] ?? '';
                                        $imageUrl = null;
                                        if (!empty($job['images']) && is_array($job['images'])) {
                                            $firstImage = $job['images'][0] ?? null;
                                            if ($firstImage && !empty($firstImage['image_url'])) {
                                                $imageUrl = $firstImage['image_url'];
                                            }
                                        }
                                        if (!$imageUrl) {
                                            continue;
                                        }
                                        $area = $city ?: ($region ?: $country);
                                        $salaryLabel = '';
                                        if ($salaryMin !== null) {
                                            if ($salaryUnit === 'MONTH') {
                                                $salaryLabel = '月給 ' . number_format($salaryMin) . '円';
                                            } elseif ($salaryUnit === 'DAY') {
                                                $salaryLabel = '日給 ' . number_format($salaryMin) . '円';
                                            } else {
                                                $salaryLabel = '時給 ' . number_format($salaryMin) . '円';
                                            }
                                        }
                                    ?>
                                        <div class="swiper-slide">
                                            <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                                <div class="relative">
                                                    <div class="overflow-hidden"><img src="<?php echo h($imageUrl); ?>" alt="<?php echo h($title); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                                </div>
                                                <div class="p-4 flex flex-col flex-grow">
                                                    <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?php echo $jobId; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo h($title); ?></a></h3>
                                                    <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                                        <?php if ($area): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($area); ?></span></p>
                                                        <?php endif; ?>
                                                        <?php if ($employment): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($employment); ?></span></p>
                                                        <?php endif; ?>
                                                        <?php if ($salaryLabel): ?>
                                                            <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?php echo h($salaryLabel); ?></span></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container"></div>
                                                    <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?php echo $jobId; ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                            <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                        </div>
                        <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                            <a href="/jobs/new/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                        </div>
                    </div>
                </section>

                <!-- 特集・コラム -->
<?php $articles = get_article_list(4); ?>
                <section id="features" class="job-section">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div>
                            <h2 class="section-title">特集・コラム</h2>
                            <p class="section-subtitle">FEATURES & COLUMNS</p>
                        </div>
                        <div class="relative">
                            <div class="swiper card-carousel">
                                <div id="features-grid" class="swiper-wrapper">
<?php if ($articles !== false && !empty($articles)): ?>
<?php foreach ($articles as $article): 
    $aId = (int)$article['id'];
    $aTitle = htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $aCategory = htmlspecialchars($article['category'] ?? '', ENT_QUOTES, 'UTF-8');
    $aImg = !empty($article['og_image_url']) ? $article['og_image_url'] : '/assets/images/articles/feature-default-600w.jpg';
    $aUrl = '/feature/' . $aId . '/';
    $aUpdated = $article['updated_at'] ?? ($article['published_at'] ?? '');
    $aUpdatedStr = $aUpdated ? date('Y.m.d', strtotime($aUpdated)) : '';
?>
                                    <div class="swiper-slide">
                                        <div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full">
                                            <div class="overflow-hidden"><img src="<?php echo htmlspecialchars($aImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo $aTitle; ?>の画像" class="w-full aspect-video object-cover"></div>
                                            <div class="p-4 flex flex-col flex-grow">
                                                <p class="text-xs font-bold text-slate-600 mb-2"><?php echo $aCategory; ?></p>
                                                <h3 class="font-bold text-base mb-3 leading-tight"><a href="<?php echo $aUrl; ?>" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo $aTitle; ?></a></h3>
                                                <div class="text-right text-xs text-slate-400 mt-2">
                                                    <span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: <?php echo htmlspecialchars($aUpdatedStr, ENT_QUOTES, 'UTF-8'); ?></span></span>
                                                </div>
                                                <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="<?php echo $aUrl; ?>" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div>
                                            </div>
                                        </div>
                                    </div>
<?php endforeach; ?>
<?php endif; ?>
                                </div>
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

            <!-- Ad Banner Section -->
            <div class="bg-white py-12 sm:py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <?php $ad_banners = get_ad_banners(4); ?>
                    <div class="flex flex-col gap-4 lg:grid lg:grid-cols-4 lg:gap-0">
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
                </div>
            </div>
        </main>

        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // サンプルデータ関連の変数は削除 - 実際のデータベースデータを使用

            // generateJobs関数は削除 - 実際のデータベースデータを使用

            // filterJobsBySearchParams関数は削除 - サーバーサイドでフィルタリング済み

            // generateFeatures関数は削除 - PHPで既に生成済み

            // createJobCard関数は削除 - PHPで既に生成済み

            // createFeatureCard関数は削除 - PHPで既に生成済み

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
                    slides.forEach(slide => {
                        slide.className = 'swiper-slide';
                        slide.style.display = 'block';
                    });
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';

                    if (isDesktop) {
                        swiperContainer.classList.add('px-4', 'sm:px-6', 'lg:px-8');
                        wrapper.classList.add('grid', 'grid-cols-4', 'gap-6');
                        slides.forEach((slide) => {
                            slide.classList.remove('swiper-slide');
                        });
                        slides.forEach((slide, i) => {
                            if (i >= 8) slide.style.display = 'none';
                        });
                    } else {
                        wrapper.classList.add('!pl-4', 'sm:!pl-6', '!pr-4', 'sm:!pr-6');
                        const swiper = new Swiper(swiperContainer, {
                            loop: false,
                            slidesPerView: 1.2,
                            spaceBetween: 16,
                            navigation: {
                                nextEl: nextBtn,
                                prevEl: prevBtn
                            },
                            breakpoints: {
                                640: {
                                    slidesPerView: 2.2
                                },
                                768: {
                                    slidesPerView: 3.2
                                }
                            },
                            on: {
                                init: function() {
                                    this.update();
                                }
                            }
                        });
                        swiperInstances.set(id, swiper);
                        if (!swiper.isLocked) {
                            if (prevBtn) prevBtn.style.display = 'flex';
                            if (nextBtn) nextBtn.style.display = 'flex';
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

            // --- DYNAMIC CONTENT & SEO LOGIC ---

            function updateDynamicContent(searchParams, jobCount) {
                // Default values
                let title = '求人検索結果';
                let description = 'あなたの希望に合ったリゾート・海外キャバクラの求人が見つかりました。';
                let metaTitle = '求人一覧｜海外・リゾートキャバクラ求人.COM';
                let metaDescription = '検索条件に一致した海外・リゾートキャバクラの求人一覧です。あなたの希望にぴったりの高収入・短期リゾバを見つけよう。';

                // Generate dynamic strings if params exist
                if (searchParams && Object.keys(searchParams).length > 0) {
                    const area = searchParams.area || '';
                    const period = searchParams.period || '';

                    if (area) {
                        title = `${area}の求人検索結果`;
                        metaTitle = `${area}の求人一覧｜海外・リゾートキャバクラ求人.COM`;
                        description = `${area}エリアの最新リゾートキャバクラ求人をご紹介します。あなたの希望に合うお仕事を見つけてください。`;
                        metaDescription = `${area}の海外・リゾートキャバクラ求人一覧です。${period ? `${period}の条件で絞り込み。` : ''}あなたの希望にぴったりの高収入・短期リゾバを見つけよう。`;
                    }
                }

                // Update DOM
                document.title = metaTitle;
                document.querySelector('meta[name="description"]').setAttribute('content', metaDescription);
                document.getElementById('page-title').textContent = title;
                document.getElementById('page-description').textContent = description;

                // 検索結果件数を更新
                const jobCountDisplay = document.getElementById('job-count-display');
                if (jobCountDisplay) {
                    jobCountDisplay.innerHTML = `<span class="text-[var(--brand-primary)] text-xl">${jobCount}</span> 件の求人が見つかりました`;
                }

                // Toggle visibility based on job count
                const resultsArea = document.getElementById('search-results-area');
                const zeroResultsArea = document.getElementById('zero-results-area');
                if (jobCount > 0) {
                    resultsArea.classList.remove('hidden');
                    zeroResultsArea.classList.add('hidden');
                } else {
                    resultsArea.classList.add('hidden');
                    zeroResultsArea.classList.remove('hidden');
                }
            }

            // --- URL QUERY PARSING ---
            function parseUrlParams() {
                const urlParams = new URLSearchParams(window.location.search);
                const params = {};

                const periodLabel = (v) => {
                    switch (v) {
                        case 'short':
                            return '1ヶ月未満';
                        case 'mid':
                            return '1〜3ヶ月';
                        case 'long':
                            return '長期（3ヶ月〜）';
                        case 'summer':
                            return '夏休み';
                        case 'winter':
                            return '年末年始';
                        default:
                            return v;
                    }
                };

                const getAllValues = (baseKey) => {
                    const names = [baseKey + '[]', baseKey];
                    const out = [];
                    names.forEach((name) => {
                        const values = urlParams.getAll(name);
                        values.forEach((v) => {
                            if (v !== null && v !== '') {
                                out.push(v);
                            }
                        });
                    });
                    return out;
                };

                // area, employment は複数選択に備えて連結
                const areas = getAllValues('area');
                if (areas.length) params.area = areas.join('、');

                // period はコード→日本語ラベルに変換して連結
                const periods = getAllValues('period');
                if (periods.length) params.period = periods.map(periodLabel).join('、');

                const merits = getAllValues('merit');
                if (merits.length) params.merit = merits.join('、');

                const emps = getAllValues('employment');
                if (emps.length) params.employment = emps.join('、');

                const countries = getAllValues('country');
                if (countries.length) params.country = countries.join('、');

                if (urlParams.get('salary_min')) params.salary_min = urlParams.get('salary_min');
                if (urlParams.get('q')) params.q = urlParams.get('q');

                return params;
            }

            // --- SEARCH CONDITIONS DISPLAY ---
            function updateSearchConditionsDisplay( /* searchParams */ ) {
                const conditionsContainer = document.querySelector('.flex.flex-wrap.gap-2');
                if (!conditionsContainer) return;

                // 既存の条件表示をクリア
                conditionsContainer.innerHTML = '';

                const urlParams = new URLSearchParams(window.location.search);

                const periodLabel = (v) => {
                    switch (v) {
                        case 'short':
                            return '1ヶ月未満';
                        case 'mid':
                            return '1〜3ヶ月';
                        case 'long':
                            return '長期（3ヶ月〜）';
                        case 'summer':
                            return '夏休み';
                        case 'winter':
                            return '年末年始';
                        default:
                            return v;
                    }
                };

                const getEntries = (baseKey) => {
                    const names = [baseKey + '[]', baseKey];
                    const out = [];
                    names.forEach((name) => {
                        const values = urlParams.getAll(name);
                        values.forEach((v) => {
                            if (v !== null && v !== '') {
                                out.push({
                                    name,
                                    value: v
                                });
                            }
                        });
                    });
                    return out;
                };

                // タグID→名称のマップ（フィルターのチェックボックスから抽出）
                const tagIdToName = new Map();
                document.querySelectorAll('input[name="tag[]"]').forEach((input) => {
                    const id = input.value;
                    let label = '';
                    const labelEl = input.closest('label');
                    if (labelEl) {
                        const span = labelEl.querySelector('span');
                        if (span) {
                            label = span.textContent ? span.textContent.trim() : '';
                        }
                    } else if (input.nextElementSibling) {
                        label = input.nextElementSibling.textContent ? input.nextElementSibling.textContent.trim() : '';
                    }
                    if (id && label) {
                        tagIdToName.set(id, label);
                    }
                });

                const chips = [];

                // エリア
                getEntries('area').forEach(({
                    name,
                    value
                }) => {
                    chips.push({
                        label: 'エリア',
                        value,
                        name
                    });
                });
                // 職種
                getEntries('employment').forEach(({
                    name,
                    value
                }) => {
                    chips.push({
                        label: '職種',
                        value,
                        name
                    });
                });
                // 期間（値を日本語ラベルに変換）
                getEntries('period').forEach(({
                    name,
                    value
                }) => {
                    chips.push({
                        label: '期間',
                        value: periodLabel(value),
                        name,
                        rawValue: value
                    });
                });
                // メリット
                getEntries('merit').forEach(({
                    name,
                    value
                }) => {
                    chips.push({
                        label: 'メリット',
                        value,
                        name
                    });
                });
                // タグ（tag / tags 両対応）
                getEntries('tag').forEach(({
                    name,
                    value
                }) => {
                    const display = tagIdToName.get(value) || value;
                    chips.push({
                        label: 'タグ',
                        value: display,
                        name,
                        rawValue: value
                    });
                });
                getEntries('tags').forEach(({
                    name,
                    value
                }) => {
                    const display = tagIdToName.get(value) || value;
                    chips.push({
                        label: 'タグ',
                        value: display,
                        name,
                        rawValue: value
                    });
                });
                // 国
                getEntries('country').forEach(({
                    name,
                    value
                }) => {
                    chips.push({
                        label: '国',
                        value,
                        name
                    });
                });
                // キーワード（単一）
                if (urlParams.has('q')) {
                    const v = urlParams.get('q');
                    if (v) {
                        chips.push({
                            label: 'キーワード',
                            value: v,
                            name: 'q'
                        });
                    }
                }
                // 最低給与（0以下は非表示）
                if (urlParams.has('salary_min')) {
                    const v = urlParams.get('salary_min');
                    const n = parseInt(v || '0', 10);
                    if (!isNaN(n) && n > 0) {
                        chips.push({
                            label: '最低給与',
                            value: `${n}円以上`,
                            name: 'salary_min',
                            rawValue: String(n)
                        });
                    }
                }

                // 出力
                chips.forEach((chip) => {
                    const span = document.createElement('span');
                    span.className = 'inline-flex items-center gap-x-1.5 py-1.5 px-3 bg-slate-100 text-slate-700 text-xs font-medium';

                    const text = document.createElement('span');
                    text.textContent = `${chip.label}: ${chip.value}`;
                    span.appendChild(text);

                    const btn = document.createElement('button');
                    btn.className = 'group';
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const valueForRemoval = chip.rawValue !== undefined ? chip.rawValue : chip.value;
                        removeSearchCondition(chip.name, valueForRemoval);
                    });
                    const icon = document.createElement('i');
                    icon.setAttribute('data-lucide', 'x');
                    icon.className = 'w-3.5 h-3.5 text-slate-500 group-hover:text-red-600';
                    btn.appendChild(icon);
                    span.appendChild(btn);

                    conditionsContainer.appendChild(span);
                });
            }

            // --- REMOVE SEARCH CONDITION ---
            function removeSearchCondition(key, value) {
                const url = new URL(window.location);
                if (typeof value === 'undefined') {
                    url.searchParams.delete(key);
                } else {
                    const all = url.searchParams.getAll(key);
                    const updated = all.filter((v) => v !== String(value));
                    url.searchParams.delete(key);
                    updated.forEach((v) => url.searchParams.append(key, v));
                }
                window.location.href = url.toString();
            }

            // --- INITIALIZATION ---

            function init() {
                // URLクエリパラメータから検索条件を取得
                const searchParams = parseUrlParams();

                // 検索条件表示を更新
                updateSearchConditionsDisplay(searchParams);

                // 実際のデータベースデータはPHPで既に挿入済み
                // JavaScriptでのモックデータ生成は削除
                const jobListContainer = document.getElementById('job-list');
                const actualJobCount = <?php echo (int)$total; ?>;

                updateDynamicContent(searchParams, <?php echo (int)$total; ?>);

                // ピックアップ求人、新着求人、特集・コラムはPHPで既に挿入済み

                setupLayouts();
                lucide.createIcons();
            }

            init();
            window.addEventListener('resize', debounce(setupLayouts));
        });
    </script>
    <script>
        (function() {
            function toggle(e) {
                if (e) {
                    e.preventDefault();
                }
                var a = document.getElementById("filters-accordion");
                if (!a) return;
                a.classList.toggle("hidden");
            }
            var root = document.getElementById("search-results-area");
            if (root) {
                root.querySelectorAll("a,button").forEach(function(el) {
                    var t = (el.textContent || "").trim();
                    if (t.indexOf("検索条件を変更") !== -1 || t.indexOf("条件を変更する") !== -1) {
                        el.addEventListener("click", toggle);
                    }
                });
            }
            var closeBtn = document.getElementById("filters-close");
            if (closeBtn) {
                closeBtn.addEventListener("click", toggle);
            }
            var form = document.getElementById("filters-form");
            if (form) {
                form.addEventListener("submit", function() {
                    var p = form.querySelector("input[name=page]");
                    if (p) {
                        p.value = "1";
                    }
                });
            }
        })();
    </script>
</body>

</html>