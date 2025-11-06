<!DOCTYPE html>
<html lang="ja">
<head>
    <?php
    // 1) 取得 & バリデーション
    require_once __DIR__ . '/../config/functions.php';
    $jobId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $job = $jobId > 0 ? get_job_by_id($jobId) : null;

    // 2) 404処理
    if (!$job) {
        http_response_code(404);
        $title = '求人が見つかりません｜海外リゾキャバ求人.COM';
        $description = 'お探しの求人は見つかりませんでした。URLをご確認ください。';
        $og_title = $title; $og_description = $description;
        $og_type = 'article';
        $og_url = 'https://' . $_SERVER['HTTP_HOST'] . '/job/' . ($jobId ?: '');
        $og_image = '/assets/images/jobs/no-image-ogp-1200x630.jpg';
    } else {
        // 3) メタ生成
        $job_title = $job['title'] ?? '求人情報';
        $desc_text = $job['description_text'] ?? '';
        $short_desc = mb_strimwidth(strip_tags($desc_text), 0, 200, '...', 'UTF-8');
        $first_image = (!empty($job['images']) && !empty($job['images'][0]['image_url'])) ? $job['images'][0]['image_url'] : '';

        $title = $job_title . '｜海外リゾキャバ求人.COM';
        $description = $short_desc !== '' ? $short_desc : '求人詳細ページ';
        $og_title = $title; $og_description = $description;
        $og_type = 'article';
        $og_url = 'https://' . $_SERVER['HTTP_HOST'] . '/job/' . $jobId . '/';
        $og_image = $first_image;
    }
    require_once __DIR__ . '/includes/header.php';
    // カノニカル
    $canonical = isset($jobId) && $jobId > 0 ? '/job/' . $jobId . '/' : '';
    if ($canonical !== '') {
        echo '<link rel="canonical" href="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . '">';
    }
    ?>
    <!-- SEO: JSON-LD for Structured Data -->
    <?php if ($job) { 
        $salaryUnit = $job['salary_unit'] ?? 'HOUR';
        $salaryMin = isset($job['salary_min']) ? (int)$job['salary_min'] : null;
        $orgName = $job['store']['name'] ?? '掲載店舗';
        $region = $job['region_prefecture'] ?? '';
        $country = $job['country'] ?? '';
        $addressRegion = $region ?: '';
        $addressCountry = $country ?: '';
        $datePosted = $job['created_at'] ?? null;
        $validThrough = $job['valid_through'] ?? null;
        $jp = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem','position' => 1,'name' => 'トップ','item' => 'https://' . $_SERVER['HTTP_HOST'] . '/'],
                        ['@type' => 'ListItem','position' => 2,'name' => '求人一覧','item' => 'https://' . $_SERVER['HTTP_HOST'] . '/jobs/'],
                        ['@type' => 'ListItem','position' => 3,'name' => $job['title'] ?? '求人詳細']
                    ]
                ],
                [
                    '@type' => 'JobPosting',
                    'title' => $job['title'] ?? '',
                    'description' => $job['description_text'] ?? '',
                    'datePosted' => $datePosted ? date('Y-m-d', strtotime($datePosted)) : null,
                    'validThrough' => $validThrough ? date('Y-m-d', strtotime($validThrough)) : null,
                    'baseSalary' => $salaryMin ? [
                        '@type' => 'MonetaryAmount',
                        'currency' => 'JPY',
                        'value' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => $salaryMin,
                            'unitText' => $salaryUnit,
                        ]
                    ] : null,
                    'hiringOrganization' => [
                        '@type' => 'Organization',
                        'name' => $orgName,
                    ],
                    'jobLocation' => [
                        '@type' => 'Place',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressRegion' => $addressRegion,
                            'addressCountry' => $addressCountry,
                        ]
                    ]
                ]
            ]
        ];
    ?>
    <script type="application/ld+json"><?= json_encode($jp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php } ?>

    <style>
        :root {
            --text-primary: #1e293b; /* slate-800 */
            --text-secondary: #475569; /* slate-600 */
            --bg-base: #f1f5f9; /* slate-100 */
            --bg-surface: #ffffff;
            --bg-muted: #f1f5f9; /* slate-100 */
            --border-color: #e2e8f0; /* slate-200 */
            --brand-primary: #0ABAB5; /* Tiffany Blue */
            --brand-secondary: #f59e0b; /* amber-500 */
            --brand-accent: #ef4444; /* red-500 for CTA */
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
            margin-bottom: 1.5rem;
        }
        .prose p, .prose ul {
            margin-bottom: 1.5rem;
        }
        .prose ul {
            list-style-type: none;
            padding-left: 0;
        }
        .prose li {
            position: relative;
            padding-left: 1.75rem;
            margin-bottom: 0.75rem;
        }
        .prose li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0.4rem;
            width: 1rem;
            height: 1rem;
            background-color: var(--brand-primary);
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E") no-repeat center / contain;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E") no-repeat center / contain;
        }

        .section-title { font-size: 1.75rem; font-weight: 700; text-align: center; margin-bottom: 0.5rem; letter-spacing: 0.1em; }
        .section-subtitle { font-size: 0.875rem; text-align: center; color: var(--text-secondary); margin-bottom: 3rem; letter-spacing: 0.1em; }
        .swiper-nav-button { color: var(--text-secondary); background-color: transparent; width: 44px; height: 44px; transition: all 0.2s ease; --swiper-navigation-size: 28px; border-radius: 50%; display: none; }
        @media (min-width: 768px) { .swiper-nav-button { display: flex; } }
        .swiper-nav-button:hover { background-color: var(--bg-muted); color: var(--brand-primary); }
        .swiper-button-disabled { opacity: 0.1; pointer-events: none; }
        /* Hide carousel arrows on small screens */
        @media (max-width: 768px) {
            .swiper-button-prev,
            .swiper-button-next { display: none !important; }
        }

        /* Clamp long descriptions like index.php */
        .description-truncate {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Lightbox for gallery */
        .lightbox { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.9); }
        .lightbox-content { margin: auto; display: block; max-width: 90%; max-height: 90%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .lightbox-close { position: absolute; top: 15px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; transition: 0.3s; }
        .lightbox-close:hover, .lightbox-close:focus { color: #bbb; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body class="antialiased">

    <div id="app">
        <?php require_once __DIR__ . '/includes/menu.php'; ?>

        <main>
            <!-- Page Header -->
            <div class="bg-white border-b border-[var(--border-color)] py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <nav class="text-xs mb-4" aria-label="Breadcrumb">
                      <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><a href="/jobs/" class="text-gray-500 hover:text-[var(--brand-primary)]">求人一覧</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><span class="text-gray-700 font-medium truncate max-w-[200px] sm:max-w-xs"><?php echo htmlspecialchars($job['title'] ?? '求人詳細', ENT_QUOTES, 'UTF-8'); ?></span></li>
                      </ol>
                    </nav>

                    <?php if ($job): ?>
                    <?php if (!empty($job['store']['name'])): ?>
                    <a href="#" class="text-sm font-semibold text-[var(--brand-primary)] hover:underline"><?php echo htmlspecialchars($job['store']['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endif; ?>
                    <h1 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)] mt-1 !leading-tight"><?php echo htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    <?php else: ?>
                    <h1 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)] mt-1 !leading-tight">求人が見つかりません</h1>
                    <?php endif; ?>
                    
                    <div class="mt-6 flex flex-wrap gap-x-6 gap-y-3 text-sm text-[var(--text-secondary)]">
                        <?php if ($job): ?>
                        <div class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i><span><?php echo htmlspecialchars(($job['country'] ?? '') . ' / ' . ($job['region_prefecture'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span></div>
                        <div class="flex items-center gap-x-2"><i data-lucide="dollar-sign" class="w-4 h-4 text-slate-400"></i><span><?php echo ($job['salary_unit'] ?? 'HOUR') === 'MONTH' ? '月給 ' . number_format((int)($job['salary_min'] ?? 0)) . '円' : '時給 ' . number_format((int)($job['salary_min'] ?? 0)) . '円'; ?></span></div>
                        <div class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 text-slate-400"></i><span><?php echo htmlspecialchars($job['employment_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span></div>
                        <?php endif; ?>
                    </div>

                     <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="#apply-form" class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-3 text-base font-bold text-white bg-red-500 hover:bg-opacity-90 transition-all shadow-lg">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            <span>今すぐ応募する</span>
                        </a>
                        <button class="w-full sm:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-3 text-base font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                            <span>気になるリストに追加</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="max-w-4xl mx-auto space-y-12">
                    <!-- Main Image Slider -->
                    <?php if ($job): ?>
                    <section class="relative">
                        <?php if (!empty($job['images'])): ?>
                        <div class="swiper main-visual-slider border border-[var(--border-color)]">
                            <div class="swiper-wrapper">
                                <?php foreach ($job['images'] as $img): 
                                    $src = htmlspecialchars($img['image_url'], ENT_QUOTES, 'UTF-8');
                                    $alt = htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8');
                                ?>
                                <div class="swiper-slide"><img src="<?= $src ?>" alt="<?= $alt ?>" class="w-full h-full object-cover"></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev main-visual-prev !left-2 md:!-left-4 !bg-white/50 hover:!bg-white/80 top-1/2 -translate-y-1/2"></div>
                        <div class="swiper-button-next main-visual-next !right-2 md:!-right-4 !bg-white/50 hover:!bg-white/80 top-1/2 -translate-y-1/2"></div>
                        <div class="swiper-pagination main-visual-pagination !bottom-3"></div>
                        <?php else: ?>
                        <div class="border border-[var(--border-color)] bg-white aspect-video flex items-center justify-center text-slate-400">
                            画像は準備中です
                        </div>
                        <?php endif; ?>
                    </section>
                    <?php endif; ?>

                    <!-- Merits/Tags Section -->
                    <?php 
                    $merits = [];
                    if ($job) {
                        if (!empty($job['merits']) && is_array($job['merits'])) {
                            $merits = $job['merits'];
                        } elseif (!empty($job['tags']) && is_array($job['tags'])) {
                            $merits = $job['tags'];
                        }
                    }
                    if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                        <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">この求人のメリット</h2>
                        <?php if (!empty($merits)): ?>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($merits as $m): ?>
                            <span class="inline-flex items-center gap-x-1.5 bg-teal-50 text-teal-800 text-xs font-semibold px-3 py-1.5"><i data-lucide="check-circle-2" class="w-4 h-4 text-teal-600"></i><?php echo htmlspecialchars((string)$m, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-sm text-slate-500">メリット情報は準備中です。</p>
                        <?php endif; ?>
                    </section>
                    <?php endif; ?>

                    <!-- Message Section -->
                    <?php if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                        <div class="prose max-w-none">
                            <h2>メッセージ</h2>
                            <?php if (!empty($job['message_html'])): ?>
                            <?= $job['message_html'] ?>
                            <?php else: ?>
                            <p class="text-sm text-slate-500">メッセージは準備中です。</p>
                            <?php endif; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Job Description Section -->
                    <?php if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                        <div class="prose max-w-none">
                            <h2>お仕事の内容</h2>
                            <?php if (!empty($job['description_html'])): ?>
                            <?= $job['description_html'] ?>
                            <?php else: ?>
                            <p class="text-sm text-slate-500">お仕事の内容は準備中です。</p>
                            <?php endif; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Summary Table -->
                    <?php if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">募集要項</h2>
                         <dl class="text-sm">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">職種</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?php echo htmlspecialchars($job['employment_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?></dd></div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">給与</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?php echo ($job['salary_unit'] ?? 'HOUR') === 'MONTH' ? '月給 ' . number_format((int)($job['salary_min'] ?? 0)) . '円' : '時給 ' . number_format((int)($job['salary_min'] ?? 0)) . '円'; ?></dd></div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200"><dt class="font-semibold text-slate-500">勤務地</dt><dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?php echo htmlspecialchars(($job['country'] ?? '') . ($job['region_prefecture'] ? ' / ' . $job['region_prefecture'] : ''), ENT_QUOTES, 'UTF-8'); ?></dd></div>
                         </dl>
                    </section>
                    <?php endif; ?>

                    <!-- Photo Gallery -->
                    <?php if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">フォトギャラリー</h2>
                         <?php if (!empty($job['images'])): ?>
                         <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 gallery-container">
                            <?php foreach ($job['images'] as $img): 
                                $src = htmlspecialchars($img['image_url'], ENT_QUOTES, 'UTF-8');
                                $alt = htmlspecialchars(($job['title'] ?? '画像') . 'の写真', ENT_QUOTES, 'UTF-8');
                            ?>
                            <img src="<?= $src ?>" alt="<?= $alt ?>" class="w-full aspect-[4/3] object-cover transition-opacity hover:opacity-80 border border-[var(--border-color)] cursor-pointer">
                            <?php endforeach; ?>
                         </div>
                         <?php else: ?>
                         <p class="text-sm text-slate-500">写真は準備中です。</p>
                         <?php endif; ?>
                    </section>
                    <?php endif; ?>

                    <!-- Map -->
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">勤務地の地図</h2>
                         <div class="aspect-video bg-slate-200 border border-[var(--border-color)]">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.901358991471!2d105.80931097593456!3d21.03666028061448!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab6b2529944b%3A0x764ab26e3c50174!2sCRAZY%20CAT&#39;S!5e0!3m2!1sja!2sjp!4v1726469493774!5m2!1sja!2sjp" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                         </div>
                    </section>

                    <!-- Apply Form -->
                    <section id="apply-form" class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">この求人に応募する</h2>
                         <form action="#" method="POST" class="space-y-6">
                            <div class="bg-slate-50 p-4 border-l-4 border-slate-300">
                                <p class="text-sm font-medium text-slate-700">求人番号: <span class="font-bold text-slate-900 text-base ml-2">JOB-<?= htmlspecialchars((string)$jobId, ENT_QUOTES, 'UTF-8') ?></span></p>
                                <input type="hidden" name="job_id" value="<?= htmlspecialchars((string)$jobId, ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                 <div>
                                     <label for="name" class="block text-sm font-medium text-slate-700 mb-1">お名前 <span class="text-red-500">*</span></label>
                                     <input type="text" id="name" name="name" required placeholder="例：山田 花子" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                 </div>
                                  <div>
                                     <label for="furigana" class="block text-sm font-medium text-slate-700 mb-1">ふりがな <span class="text-red-500">*</span></label>
                                     <input type="text" id="furigana" name="furigana" required placeholder="例：やまだ はなこ" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                 </div>
                            </div>
                             <div>
                                 <label class="block text-sm font-medium text-slate-700 mb-2">性別 <span class="text-red-500">*</span></label>
                                 <div class="flex items-center gap-x-6">
                                     <label class="inline-flex items-center"><input type="radio" name="gender" value="female" required class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">女性</span></label>
                                     <label class="inline-flex items-center"><input type="radio" name="gender" value="male" class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">男性</span></label>
                                 </div>
                             </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                 <div>
                                     <label for="email" class="block text-sm font-medium text-slate-700 mb-1">メールアドレス <span class="text-red-500">*</span></label>
                                     <input type="email" id="email" name="email" required placeholder="例：example@email.com" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                 </div>
                                  <div>
                                     <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">電話番号 <span class="text-red-500">*</span></label>
                                     <input type="tel" id="phone" name="phone" required placeholder="例：09012345678" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                 </div>
                            </div>
                             <div>
                                 <label for="birthdate" class="block text-sm font-medium text-slate-700 mb-1">生年月日 <span class="text-red-500">*</span></label>
                                 <input type="text" id="birthdate" name="birthdate" required placeholder="例：2000/01/01" class="w-full sm:w-1/2 border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                             </div>
                            <div>
                                <label for="residence" class="block text-sm font-medium text-slate-700 mb-1">現在のお住まい <span class="text-red-500">*</span></label>
                                <input type="text" id="residence" name="residence" required placeholder="例：東京都、タイ・バンコク" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                            </div>

                            <!-- Work Experience Questionnaire -->
                            <div class="space-y-6 pt-6 border-t border-slate-200 sm:col-span-2">
                                <h3 class="text-base font-semibold text-slate-800">職務経歴について</h3>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">ナイトワークの経験はありますか？ <span class="text-red-500">*</span></label>
                                    <div class="flex items-center gap-x-6">
                                        <label class="inline-flex items-center"><input type="radio" name="has_experience" value="yes" required class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">はい</span></label>
                                        <label class="inline-flex items-center"><input type="radio" name="has_experience" value="no" class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">いいえ</span></label>
                                    </div>
                                </div>

                                <div id="experience-details-wrapper" class="hidden space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">主な経験職種はどちらですか？ <span class="text-xs text-slate-500">（任意）</span></label>
                                         <div class="flex items-center gap-x-6">
                                            <label class="inline-flex items-center"><input type="radio" name="main_role" value="cast" class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">キャスト</span></label>
                                            <label class="inline-flex items-center"><input type="radio" name="main_role" value="staff" class="mr-2 text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]"><span class="text-sm text-slate-800">スタッフ（内勤）</span></label>
                                        </div>
                                    </div>
                                    
                                    <div id="experience-entries" class="space-y-4">
                                        <div class="experience-entry border border-slate-200 p-4 space-y-4 relative">
                                            <p class="text-sm font-semibold text-slate-600">職務経歴 1</p>
                                             <div>
                                                <label for="job_title_1" class="block text-xs font-medium text-slate-600 mb-1">職種 <span class="text-xs text-slate-500">（任意）</span></label>
                                                <input type="text" id="job_title_1" name="experience[1][job_title]" placeholder="例：キャスト, フロアレディ" class="w-full border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                            </div>
                                             <div>
                                                <label for="area_1" class="block text-xs font-medium text-slate-600 mb-1">地域 <span class="text-xs text-slate-500">（任意）</span></label>
                                                <input type="text" id="area_1" name="experience[1][area]" placeholder="例：ベトナム・ハノイ" class="w-full border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                            </div>
                                             <div>
                                                 <label class="block text-xs font-medium text-slate-600 mb-1">期間 <span class="text-xs text-slate-500">（任意）</span></label>
                                                 <div class="flex items-center gap-x-2 text-sm">
                                                     <input type="text" name="experience[1][start_date]" placeholder="YYYY/MM" class="w-full border border-slate-300 p-2 focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                                     <span>～</span>
                                                     <input type="text" name="experience[1][end_date]" placeholder="YYYY/MM" class="w-full border border-slate-300 p-2 focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                                 </div>
                                             </div>
                                        </div>
                                    </div>

                                    <button type="button" id="add-experience-btn" class="w-full text-center py-2 px-4 bg-slate-100 text-slate-600 font-semibold hover:bg-slate-200 transition-colors flex items-center justify-center gap-x-2 text-sm border border-slate-300">
                                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                        <span>職務経歴を追加</span>
                                    </button>
                                </div>
                            </div>

                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                 <div>
                                     <label for="start-date" class="block text-sm font-medium text-slate-700 mb-1">いつから働きたいですか？ <span class="text-red-500">*</span></label>
                                     <select id="start-date" name="start_date" required class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition bg-white">
                                         <option>相談して決めたい</option>
                                         <option>すぐにでも</option>
                                         <option>1ヶ月以内</option>
                                     </select>
                                 </div>
                                 <div>
                                     <label for="duration" class="block text-sm font-medium text-slate-700 mb-1">働きたい期間 <span class="text-red-500">*</span></label>
                                     <select id="duration" name="duration" required class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition bg-white">
                                         <option>相談して決めたい</option>
                                         <option>1ヶ月未満（短期）</option>
                                         <option>1〜3ヶ月</option>
                                         <option>3ヶ月以上（長期）</option>
                                     </select>
                                 </div>
                             </div>
                             <div>
                                 <label for="message" class="block text-sm font-medium text-slate-700 mb-1">お店へのメッセージ</label>
                                 <textarea id="message" name="message" rows="5" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition" placeholder="勤務希望期間や経験の有無、ご質問などをご自由にご記入ください。"></textarea>
                             </div>
                             <button type="submit" class="w-full text-center py-4 px-6 bg-[var(--brand-accent)] text-white font-bold hover:bg-opacity-90 transition-opacity flex items-center justify-center gap-x-2 text-base">
                                 <i data-lucide="send" class="w-5 h-5"></i>
                                 <span>入力内容を送信する</span>
                             </button>
                         </form>
                    </section>

                    <!-- LINE Support -->
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">お店と連絡が取れない・応募前に相談したい方</h2>
                         <p class="text-center text-slate-600 mb-6">応募に関するご相談や、お店と連絡が取れないなどのトラブルは、<br class="hidden sm:block">海外リゾキャバ求人.COMの公式LINEでサポートします。</p>
                         <div class="max-w-sm mx-auto">
                            <a href="#" target="_blank" class="w-full text-center py-4 px-6 bg-green-500 text-white font-bold hover:bg-opacity-90 transition-opacity flex items-center justify-center gap-x-3 text-base">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M16.5 12c0-1.38-1.12-2.5-2.5-2.5s-2.5 1.12-2.5 2.5c0 .9.48 1.69 1.21 2.14-.13.38-.28.76-.46 1.12-.58 1.12-1.61 2.01-2.9 2.5-1.07.4-2.22.48-3.34.2-1.13-.28-2.18-.89-3.03-1.74-.25-.25-.49-.51-.72-.78-.05.08-.09.16-.14.24-.63 1.08-.85 2.37-.62 3.63.23 1.26 1.01 2.36 2.14 3.03.21.13.43.24.65.34 1.14.51 2.39.64 3.63.43 1.24-.21 2.4-.78 3.34-1.61 1.07-.97 1.83-2.25 2.14-3.63.28-1.29.13-2.61-.43-3.8-.18-.38-.4-.73-.65-1.07.69-.48 1.16-1.25 1.16-2.14zm-6.25 1.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zm5 0c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm7.04 17.58c-.52.52-1.11.95-1.75 1.29-.65.34-1.36.57-2.1.65-.74.09-1.48.02-2.2-.18-.72-.2-1.41-.56-2.01-.9-1.29-.75-2.39-1.84-3.14-3.14-.56-.97-.88-2.07-.9-3.21-.02-1.14.23-2.28.78-3.34.62-1.22 1.58-2.25 2.81-2.97.2-.11.4-.22.62-.31.78-.34 1.61-.53 2.45-.55.84-.02 1.68.12 2.48.43.8.31 1.55.78 2.2 1.38.65.6 1.2 1.3 1.61 2.07.41.77.68 1.61.78 2.48.09.87.02 1.75-.21 2.58-.23.83-.64 1.61-1.19 2.29z"/></svg>
                                <span>公式LINEで相談する</span>
                            </a>
                         </div>
                    </section>
                    
                    <!-- Other jobs from this store -->
                    <?php 
                    $otherJobs = [];
                    $storeExternalUrl = '';
                    if (!empty($job['store'])) {
                        if (!empty($job['store']['other_jobs']) && is_array($job['store']['other_jobs'])) {
                            $otherJobs = $job['store']['other_jobs'];
                        }
                        if (!empty($job['store']['external_url'])) {
                            $storeExternalUrl = (string)$job['store']['external_url'];
                        }
                    }
                    if ($job): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">このお店の他の求人</h2>
                         <?php if (!empty($otherJobs)): ?>
                         <ul class="space-y-4">
                             <?php foreach ($otherJobs as $oj): 
                                 $ojTitle = htmlspecialchars((string)($oj['title'] ?? ''), ENT_QUOTES, 'UTF-8');
                                 $ojHref  = htmlspecialchars((string)($oj['url'] ?? '#'), ENT_QUOTES, 'UTF-8');
                                 $ojMeta  = htmlspecialchars((string)($oj['meta'] ?? ''), ENT_QUOTES, 'UTF-8');
                             ?>
                             <li>
                                <a href="<?= $ojHref ?>" class="group flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <h4 class="font-semibold group-hover:text-[var(--brand-primary)] leading-tight"><?= $ojTitle ?></h4>
                                    <?php if ($ojMeta !== ''): ?><p class="text-xs text-slate-500 mt-1 sm:mt-0"><?= $ojMeta ?></p><?php endif; ?>
                                </a>
                             </li>
                             <?php endforeach; ?>
                         </ul>
                         <?php else: ?>
                         <p class="text-sm text-slate-500">現在、他の求人情報はありません。</p>
                         <?php endif; ?>
                         <?php if (!empty($storeExternalUrl)): ?>
                         <a href="<?= htmlspecialchars($storeExternalUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="mt-6 block text-center text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors py-3">お店の情報をさらに詳しく見る</a>
                         <?php endif; ?>
                    </section>
                    <?php endif; ?>

                </div>
            </div>

            <?php if ($job): ?>
            <!-- Related Contents: 同じエリアの求人 -->
            <?php 
                $sameAreaFilters = [];
                $areaKey = '';
                if (!empty($job['region_prefecture'])) {
                    $areaKey = (string)$job['region_prefecture'];
                } elseif (!empty($job['country'])) {
                    $areaKey = (string)$job['country'];
                }
                if ($areaKey !== '') {
                    $sameAreaFilters['area'] = $areaKey;
                }
                $sameAreaFilters['exclude_id'] = (int)($job['id'] ?? 0);
                try {
                    $sameAreaJobs = get_jobs($sameAreaFilters, 0, 8);
                    if ($sameAreaJobs === false) { $sameAreaJobs = []; }
                } catch (Throwable $e) { $sameAreaJobs = []; }
            ?>
            <div class="py-16 sm:py-24 bg-white border-t border-[var(--border-color)] space-y-24">
                 <section id="pickup-jobs" class="job-section">
                    <div class="max-w-7xl mx-auto">
                       <div class="px-4 sm:px-6 lg:px-8"><h2 class="section-title">同じエリアの求人</h2><p class="section-subtitle">JOBS IN SAME AREA</p></div>
                       <div class="relative">
                           <div class="swiper card-carousel">
                               <div class="swiper-wrapper">
                                   <?php if (!empty($sameAreaJobs)): ?>
                                       <?php foreach ($sameAreaJobs as $sj): 
                                           $sjId = (int)($sj['id'] ?? 0);
                                           $sjTitle = htmlspecialchars((string)($sj['title'] ?? ''), ENT_QUOTES, 'UTF-8');
                                           $sjLocation = htmlspecialchars((string)(($sj['region_prefecture'] ?? '') ?: ($sj['country'] ?? '')), ENT_QUOTES, 'UTF-8');
                                           $sjJobType = htmlspecialchars((string)($sj['employment_type'] ?? ''), ENT_QUOTES, 'UTF-8');
                                           $sjSalary = (($sj['salary_unit'] ?? 'HOUR') === 'MONTH') ? ('月給 ' . number_format((int)($sj['salary_min'] ?? 0)) . '円') : ('時給 ' . number_format((int)($sj['salary_min'] ?? 0)) . '円');
                                           $sjSalary = htmlspecialchars($sjSalary, ENT_QUOTES, 'UTF-8');
                                           $imgUrl = '';
                                           if (!empty($sj['images']) && !empty($sj['images'][0]['image_url'])) {
                                               $imgUrl = htmlspecialchars((string)$sj['images'][0]['image_url'], ENT_QUOTES, 'UTF-8');
                                           } else {
                                               $imgUrl = '/assets/images/jobs/no-image-1280w.jpg';
                                           }
                                       ?>
                                       <div class="swiper-slide">
                                         <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                           <div class="relative">
                                             <div class="overflow-hidden"><img src="<?= $imgUrl ?>" alt="<?= $sjTitle ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                           </div>
                                           <div class="p-4 flex flex-col flex-grow">
                                             <h3 class="font-bold text-base mb-3 leading-tight"><a href="/job/<?= $sjId ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?= $sjTitle ?></a></h3>
                                             <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3">
                                               <p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span><?= $sjLocation ?></span></p>
                                               <p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span><?= $sjJobType ?></span></p>
                                               <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span><?= $sjSalary ?></span></p>
                                             </div>
                                             <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/job/<?= $sjId ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                                           </div>
                                         </div>
                                       </div>
                                       <?php endforeach; ?>
                                   <?php else: ?>
                                       <div class="swiper-slide">
                                           <div class="bg-white border border-[var(--border-color)] p-6 flex items-center justify-center text-slate-500 text-sm h-full">同じエリアの求人は現在ありません。</div>
                                       </div>
                                   <?php endif; ?>
                               </div>
                           </div>
                           <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                           <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                       </div>
                       <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                           <a href="/jobs/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                       </div>
                    </div>
                </section>
            </div>
            <?php endif; ?>

            <!-- ★ ピックアップ求人 -->
            <section id="new-jobs" class="job-section">
                <div class="max-w-7xl mx-auto">
                    <div class="px-4 sm:px-6 lg:px-8"><h2 class="section-title">ピックアップ求人</h2><p class="section-subtitle">PICKUP JOBS</p></div>
                   <div class="relative">
                        <?php
                        $pickup_jobs = [];
                        try {
                            $job_list = get_job_list_with_images();
                            if ($job_list !== false && !empty($job_list)) {
                                foreach ($job_list as $j) {
                                    $job_meta = json_decode($j['meta_json'] ?? '', true);
                                    if (!empty($job_meta['home_sections']) && is_array($job_meta['home_sections']) && in_array('pickup', $job_meta['home_sections'], true)) {
                                        $pickup_jobs[] = $j;
                                    }
                                }
                            }
                        } catch (Throwable $e) {
                            $pickup_jobs = [];
                        }
                        ?>
                       <div class="swiper card-carousel">
                           <div class="swiper-wrapper">
                                <?php if (!empty($pickup_jobs)): ?>
                                    <?php foreach ($pickup_jobs as $job) { 
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
                                                        <p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span>時給 <?php echo number_format((int)$job['salary_min']); ?>円</span></p>
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
                                <?php else: ?>
                                    <div class="swiper-slide">
                                        <div class="bg-white border border-[var(--border-color)] p-6 flex items-center justify-center text-slate-500 text-sm h-full">ピックアップ求人は現在ありません。</div>
                                    </div>
                                <?php endif; ?>
                                   </div>
                               </div>
                               <div class="swiper-button-prev swiper-nav-button !left-2 md:!-left-2 lg:!-left-4"></div>
                               <div class="swiper-button-next swiper-nav-button !right-2 md:!-right-2 lg:!-left-4"></div>
                           </div>
                           <div class="text-center mt-8 px-4 sm:px-6 lg:px-8">
                               <a href="/jobs/" class="inline-block px-8 py-3 text-sm font-semibold text-white bg-[var(--brand-primary)] hover:bg-opacity-80 transition-all shadow-lg">もっと見る</a>
                           </div>
                        </div>
                    </section>
                    
                    <!-- 特集・コラム -->
                    <section id="features" class="job-section">
                        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div><h2 class="section-title">特集・コラム</h2><p class="section-subtitle">FEATURES & COLUMNS</p></div>
                            <div class="relative">
                                <?php $articles = get_article_list(4); ?>
                                <div class="swiper card-carousel">
                                    <div id="features-grid" class="swiper-wrapper">
                                        <?php if ($articles !== false && !empty($articles)): ?>
                                            <?php foreach ($articles as $article): 
                                                $aId = (int)$article['id'];
                                                $aTitle = htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8');
                                                $aCategory = !empty($article['category']) ? htmlspecialchars($article['category'], ENT_QUOTES, 'UTF-8') : '';
                                                $aImg = !empty($article['og_image_url']) ? htmlspecialchars($article['og_image_url'], ENT_QUOTES, 'UTF-8') : '/assets/images/articles/article-default-600w.jpg';
                                                $aUpdated = !empty($article['updated_at']) ? date('Y.m.d', strtotime($article['updated_at'])) : '';
                                            ?>
                                            <div class="swiper-slide">
                                                <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                                    <div class="relative">
                                                        <div class="overflow-hidden"><img src="<?= $aImg ?>" alt="<?= $aTitle ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                                    </div>
                                                    <div class="p-4 flex flex-col flex-grow">
                                                        <?php if ($aCategory !== ''): ?><p class="text-xs font-bold text-slate-600 mb-2"><?= $aCategory ?></p><?php endif; ?>
                                                        <h3 class="font-bold text-base mb-3 leading-tight"><a href="/features/<?= $aId ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?= $aTitle ?></a></h3>
                                                        <div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1.5"></i><span><?= $aUpdated !== '' ? ('公開日: ' . $aUpdated) : '' ?></span></span></div>
                                                        <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/features/<?= $aId ?>/" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">記事を読む</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="swiper-slide">
                                                <div class="bg-white border border-[var(--border-color)] p-6 flex items-center justify-center text-slate-500 text-sm h-full">記事はありません。</div>
                                            </div>
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
            
            <?php require_once __DIR__ . '/includes/footer.php'; ?>
        </main>
    </div>
    
    <!-- Lightbox Modal -->
    <div id="lightbox-modal" class="lightbox">
        <span class="lightbox-close" id="lightbox-close-button">&times;</span>
        <img class="lightbox-content" id="lightbox-image">
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Main visual slider
            const mainVisualEl = document.querySelector('.main-visual-slider');
            if (mainVisualEl) {
                new Swiper(mainVisualEl, {
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
            }

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

            // Work Experience Questionnaire Logic (from register/)
            const experienceRadios = document.querySelectorAll('input[name="has_experience"]');
            const experienceDetailsWrapper = document.getElementById('experience-details-wrapper');
            const addExperienceBtn = document.getElementById('add-experience-btn');
            const experienceEntriesContainer = document.getElementById('experience-entries');
            let experienceCount = 1;

            experienceRadios.forEach(radio => {
                radio.addEventListener('change', (event) => {
                    if (event.target.value === 'yes') {
                        experienceDetailsWrapper.classList.remove('hidden');
                    } else {
                        experienceDetailsWrapper.classList.add('hidden');
                    }
                });
            });

            if (addExperienceBtn) {
                addExperienceBtn.addEventListener('click', () => {
                    experienceCount++;
                    const newEntry = document.createElement('div');
                    newEntry.className = 'experience-entry border border-slate-200 p-4 space-y-4 relative';
                    newEntry.innerHTML = `
                        <p class="text-sm font-semibold text-slate-600">職務経歴 ${experienceCount}</p>
                        <div>
                            <label for="job_title_${experienceCount}" class="block text-xs font-medium text-slate-600 mb-1">職種 <span class="text-xs text-slate-500">（任意）</span></label>
                            <input type="text" id="job_title_${experienceCount}" name="experience[${experienceCount}][job_title]" placeholder="例：キャスト, フロアレディ" class="w-full border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                        </div>
                        <div>
                            <label for="area_${experienceCount}" class="block text-xs font-medium text-slate-600 mb-1">地域 <span class="text-xs text-slate-500">（任意）</span></label>
                            <input type="text" id="area_${experienceCount}" name="experience[${experienceCount}][area]" placeholder="例：ベトナム・ハノイ" class="w-full border border-slate-300 p-2 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">期間 <span class="text-xs text-slate-500">（任意）</span></label>
                            <div class="flex items-center gap-x-2 text-sm">
                                <input type="text" name="experience[${experienceCount}][start_date]" placeholder="YYYY/MM" class="w-full border border-slate-300 p-2 focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                                <span>～</span>
                                <input type="text" name="experience[${experienceCount}][end_date]" placeholder="YYYY/MM" class="w-full border border-slate-300 p-2 focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                            </div>
                        </div>
                        <button type="button" class="remove-experience-btn absolute top-2 right-2 p-1 text-slate-400 hover:text-red-500 transition-colors" aria-label="この職務経歴を削除">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    `;
                    experienceEntriesContainer.appendChild(newEntry);
                    lucide.createIcons();
                });
            }

            if (experienceEntriesContainer) {
                experienceEntriesContainer.addEventListener('click', (event) => {
                    const removeBtn = event.target.closest('.remove-experience-btn');
                    if (removeBtn) {
                        const entryToRemove = removeBtn.closest('.experience-entry');
                        entryToRemove.remove();
                        // Renumber remaining entries if necessary
                        const remainingEntries = experienceEntriesContainer.querySelectorAll('.experience-entry');
                        remainingEntries.forEach((entry, index) => {
                            const newCount = index + 1;
                            entry.querySelector('p').textContent = `職務経歴 ${newCount}`;
                            // This is a simplified re-numbering. A full implementation would update all IDs and names.
                        });
                        experienceCount = remainingEntries.length;
                    }
                });
            }

            const swiperInstances = new Map();

            // --- DOM MANIPULATION (Same as jobs/, minified for brevity) ---
            const createJobCard=(e)=>{const t=document.createElement("div");t.className="swiper-slide";const a=[e.isPR?`<span class="inline-block bg-green-500 text-white text-xs font-bold px-2.5 py-1">PR</span>`:"",e.isNew?`<span class="inline-block bg-yellow-400 text-slate-800 text-xs font-bold px-2.5 py-1">NEW</span>`:""].filter(Boolean).join("");return t.innerHTML=`
                    <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="relative">
                            <div class="overflow-hidden"><img src="${e.image}" alt="${e.title}の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                            <div class="absolute top-2 left-2 flex gap-x-2">${a}</div>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-base mb-3 leading-tight"><a href="/jobs/${e.id}/" class="hover:text-[var(--brand-primary)] transition-colors">${e.title}</a></h3>
                            <div class="flex flex-col space-y-1.5 text-xs text-[var(--text-secondary)] mb-3"><p class="flex items-center gap-x-2"><i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0"></i><span>${e.location}</span></p><p class="flex items-center gap-x-2"><i data-lucide="briefcase" class="w-4 h-4 flex-shrink-0"></i><span>${e.jobType}</span></p><p class="flex items-center gap-x-2"><i data-lucide="japanese-yen" class="w-4 h-4 flex-shrink-0"></i><span>${e.salary}</span></p><p class="flex items-center gap-x-2"><i data-lucide="calendar-days" class="w-4 h-4 flex-shrink-0"></i><span>${e.period}</span></p></div>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mb-3 tags-container">${e.tags.map(t=>`<span class="inline-flex items-center text-slate-600 pb-px text-xs" style="border-bottom: 1px solid #e2e8f0;"><i data-lucide="tag" class="w-3 h-3 mr-1 flex-shrink-0"></i> ${t}</span>`).join("")}</div>
                            <p class="text-xs text-slate-500 flex-grow description-truncate">${e.description}</p>
                            <div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="refresh-cw" class="w-3 h-3 mr-1.5"></i><span>更新日: ${e.updatedDate}</span></span></div>
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
                            <div class="text-right text-xs text-slate-400 mt-2"><span class="inline-flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1.5"></i><span>公開日: ${e.updatedDate}</span></span></div>
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
                // Dynamic cards are server-rendered; no client-side injection
                setupLayouts();
                lucide.createIcons();
            }

            init();
            window.addEventListener('resize', debounce(setupLayouts));
        });
    </script>
</body>
</html>
