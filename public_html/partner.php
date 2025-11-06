<?php
require_once __DIR__ . '/../config/functions.php';

$partnerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($partnerId <= 0) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('#/partner/(\d+)/?#', $requestUri, $matches)) {
        $partnerId = (int)$matches[1];
    }
}

$store = null;
if ($partnerId > 0) {
    $storeRaw = executeQuerySingle(
        "SELECT * FROM stores WHERE id = ? AND deleted_at IS NULL LIMIT 1",
        [$partnerId]
    );
    if ($storeRaw !== false && !empty($storeRaw)) {
        $store = normalize_store_record($storeRaw);
        
        // 画像を取得
        $images = executeQuery(
            "SELECT image_url, sort_order FROM store_images WHERE store_id = ? ORDER BY sort_order",
            [$partnerId]
        );
        $store['images'] = ($images !== false) ? $images : [];
    }
}

$storeName = $store ? (string)$store['name'] : '店舗が見つかりません';
$host = $_SERVER['HTTP_HOST'] ?? 'example.com';
$pageUrl = 'https://' . $host . '/partner/' . ($partnerId > 0 ? $partnerId . '/' : '');
$defaultOgImage = '/assets/images/articles/news-ogp-1200x630.jpg';

if ($store) {
    $locationParts = [];
    if (!empty($store['city'])) {
        $locationParts[] = $store['city'];
    }
    if (!empty($store['region_prefecture']) && !in_array($store['region_prefecture'], $locationParts, true)) {
        $locationParts[] = $store['region_prefecture'];
    }
    if (!empty($store['country']) && !in_array($store['country'], $locationParts, true)) {
        $locationParts[] = $store['country'];
    }
    $locationLabel = implode(' / ', array_filter($locationParts));
    
    $descriptionSource = !empty($store['description_html']) ? strip_tags($store['description_html']) : '';
    $descriptionSource = trim(preg_replace('/\s+/u', ' ', $descriptionSource));
    $description = mb_strimwidth($descriptionSource, 0, 160, '…', 'UTF-8');
    if ($description === '') {
        $description = $storeName . 'の詳細情報';
    }
    
    $title = $storeName . '｜掲載店舗【海外リゾキャバ求人.COM】';
    $og_title = $title;
    $og_description = $description;
    $og_type = 'website';
    $og_url = $pageUrl;
    $og_image = !empty($store['images']) && !empty($store['images'][0]['image_url']) 
        ? $store['images'][0]['image_url'] 
        : $defaultOgImage;
} else {
    http_response_code(404);
    $description = 'お探しの店舗は見つかりませんでした。';
    $title = '店舗が見つかりません｜掲載店舗【海外リゾキャバ求人.COM】';
    $og_title = $title;
    $og_description = $description;
    $og_type = 'website';
    $og_url = $pageUrl;
    $og_image = $defaultOgImage;
    $locationLabel = '';
}

$canonical = $store ? '/partner/' . $partnerId . '/' : '';

$ogImageAbsolute = (strpos($og_image, 'http://') === 0 || strpos($og_image, 'https://') === 0)
    ? $og_image
    : 'https://' . $host . $og_image;

require_once __DIR__ . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>

    <?php if ($canonical !== ''): ?>
        <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    
    <!-- SEO: JSON-LD for Structured Data -->
    <?php if ($store): ?>
    <script type="application/ld+json">
    <?php
    $jsonLdData = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'トップ', 'item' => 'https://' . $host . '/'],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => '掲載店舗一覧', 'item' => 'https://' . $host . '/partners/'],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $storeName]
                ]
            ],
            [
                '@type' => 'NightClub',
                'name' => $storeName,
                'image' => $ogImageAbsolute,
                'description' => $description,
            ]
        ]
    ];
    
    if (!empty($store['address'])) {
        $jsonLdData['@graph'][1]['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $store['address'],
        ];
        if (!empty($store['city'])) {
            $jsonLdData['@graph'][1]['address']['addressLocality'] = $store['city'];
        }
        if (!empty($store['postal_code'])) {
            $jsonLdData['@graph'][1]['address']['postalCode'] = $store['postal_code'];
        }
        if (!empty($store['country'])) {
            $jsonLdData['@graph'][1]['address']['addressCountry'] = $store['country'];
        }
    }
    
    $telephone = $store['phone_domestic'] ?: $store['phone_international'];
    if (!empty($telephone)) {
        $jsonLdData['@graph'][1]['telephone'] = $telephone;
    }
    
    if (!empty($store['business_hours'])) {
        $jsonLdData['@graph'][1]['openingHours'] = $store['business_hours'];
    }
    
    echo json_encode($jsonLdData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?>
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
        <?php require_once __DIR__ . '/includes/menu.php'; ?>

        <main>
            <!-- Page Header -->
            <div class="bg-white border-b border-[var(--border-color)] py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <nav class="text-xs mb-4" aria-label="Breadcrumb">
                      <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><a href="/partners/" class="text-gray-500 hover:text-[var(--brand-primary)]">掲載店舗一覧</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><span class="text-gray-700 font-medium truncate max-w-[200px] sm:max-w-md"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?></span></li>
                      </ol>
                    </nav>

                    <?php if ($locationLabel): ?>
                    <p class="text-sm font-semibold text-[var(--brand-primary)]"><?= htmlspecialchars($locationLabel, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                    <h1 class="text-2xl sm:text-4xl font-bold text-[var(--text-primary)] mt-1 !leading-tight"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="max-w-4xl mx-auto space-y-12">
                    
                    <!-- Main Image Slider -->
                    <?php if ($store && !empty($store['images'])): ?>
                    <section class="relative">
                        <div class="swiper main-visual-slider border border-[var(--border-color)]">
                            <div class="swiper-wrapper">
                                <?php foreach ($store['images'] as $img): ?>
                                <div class="swiper-slide"><img src="<?= htmlspecialchars($img['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?>のイメージ画像" class="w-full aspect-[3/2] object-cover"></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="swiper-button-prev main-visual-prev !left-2 md:!-left-4 !bg-white/50 hover:!bg-white/80"></div>
                        <div class="swiper-button-next main-visual-next !right-2 md:!-right-4 !bg-white/50 hover:!bg-white/80"></div>
                        <div class="swiper-pagination main-visual-pagination !bottom-3"></div>
                    </section>
                    <?php elseif ($store && !empty($og_image)): ?>
                    <section class="relative">
                        <div class="border border-[var(--border-color)]">
                            <img src="<?= htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?>のイメージ画像" class="w-full aspect-[3/2] object-cover">
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php if ($store): ?>
                    <!-- コンセプト/説明 -->
                    <?php if (!empty($store['description_html'])): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                        <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">コンセプト</h2>
                        <div class="prose max-w-none"><?= $store['description_html']; ?></div>
                    </section>
                    <?php endif; ?>

                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">店舗情報</h2>
                         <dl class="text-sm">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">店名</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">カテゴリ（業種）</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['category']) ? htmlspecialchars($store['category'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <?php if ($locationLabel): ?>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">エリア</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($locationLabel, ENT_QUOTES, 'UTF-8'); ?></dd>
                            </div>
                            <?php endif; ?>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">住所</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['address']) ? htmlspecialchars($store['address'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">電話番号（国内）</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['phone_domestic']) ? htmlspecialchars($store['phone_domestic'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">電話番号（海外）</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['phone_international']) ? htmlspecialchars($store['phone_international'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">営業時間</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['business_hours']) ? htmlspecialchars($store['business_hours'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3 border-b border-dashed border-slate-200">
                                <dt class="font-semibold text-slate-500">店休日</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2"><?= !empty($store['holiday']) ? htmlspecialchars($store['holiday'], ENT_QUOTES, 'UTF-8') : '未定'; ?></dd>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 py-3">
                                <dt class="font-semibold text-slate-500">公式サイト</dt>
                                <dd class="text-slate-800 font-medium mt-1 sm:mt-0 sm:col-span-2">
                                    <?php if (!empty($store['site_url'])): ?>
                                    <a href="<?= htmlspecialchars($store['site_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-x-1 text-[var(--brand-primary)] font-semibold hover:opacity-80">
                                        公式サイトを開く
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            <polyline points="15 3 21 3 21 9"/>
                                            <line x1="10" y1="14" x2="21" y2="3"/>
                                        </svg>
                                    </a>
                                    <?php else: ?>
                                    未定
                                    <?php endif; ?>
                                </dd>
                            </div>
                         </dl>
                    </section>

                    <!-- Photo Gallery -->
                    <?php if ($store && !empty($store['images']) && count($store['images']) > 1): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">フォトギャラリー</h2>
                         <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 gallery-container">
                            <?php foreach ($store['images'] as $img): ?>
                            <img src="<?= htmlspecialchars($img['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8'); ?>の写真" class="w-full aspect-[4/3] object-cover transition-opacity hover:opacity-80 border border-[var(--border-color)] cursor-pointer">
                            <?php endforeach; ?>
                         </div>
                    </section>
                    <?php endif; ?>

                    <!-- Access Map -->
                    <?php if ($store && !empty($store['address'])): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">アクセスマップ</h2>
                         <div class="aspect-video bg-slate-200 border border-[var(--border-color)]">
                            <iframe src="https://www.google.com/maps?output=embed&amp;q=<?= urlencode($store['address']); ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                         </div>
                    </section>
                    <?php endif; ?>
                    
                    <!-- このお店の求人 -->
                    <?php if ($store): ?>
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
                         <h2 class="text-xl font-bold text-slate-800 pb-3 border-b-2 border-[var(--brand-primary)] mb-6">このお店の求人</h2>
                         <div class="space-y-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <!-- 求人カードは将来実装 -->
                            </div>
                            <div class="mt-4 text-right">
                                <a href="/jobs/?store_id=<?= $partnerId; ?>" class="inline-flex items-center gap-x-1 text-sm font-bold text-[var(--brand-primary)] hover:opacity-80">
                                    詳しく見る<i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </div>
                         </div>
                    </section>
                    <?php endif; ?>
                    
                    <?php else: ?>
                    <!-- 404 Not Found -->
                    <section class="bg-white p-6 sm:p-8 border border-[var(--border-color)] text-center">
                        <h2 class="text-xl font-bold text-slate-800 mb-4">店舗が見つかりません</h2>
                        <p class="text-slate-600 mb-6">お探しの店舗は存在しないか、公開が終了しました。</p>
                        <a href="/partners/" class="inline-flex items-center justify-center gap-x-2 px-8 py-3 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>掲載店舗一覧へ戻る</span>
                        </a>
                    </section>
                    <?php endif; ?>
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

