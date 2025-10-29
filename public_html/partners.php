<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    $title = '掲載店舗一覧｜海外リゾキャバ求人.COM';
    $description = '海外リゾキャバ求人.COMに掲載されている優良店舗の一覧です。各店舗の詳細情報や最新の求人をご確認いただけます。';
    $og_title = $title;
    $og_description = '海外リゾキャバ求人.COMに掲載されている優良店舗の一覧です。';
    $og_type = 'website';
    $og_url = 'https://example.com/partners/';
    $og_image = 'https://placehold.co/1200x630/0ABAB5/ffffff?text=掲載店舗一覧';
    require_once __DIR__ . '/includes/header.php';
    // 一覧データ取得（PHPループ化）
    require_once __DIR__ . '/../config/functions.php';
    $limit = 12;
    $page = max(1, (int)($_GET['page'] ?? 1));

    // エリア絞り込み（features のカテゴリ絞り込みと同様に GET クエリで制御）
    $selectedArea = $_GET['area'] ?? '';
    $filters = [];
    if ($selectedArea !== '' && $selectedArea !== 'all') {
        $filters['area'] = $selectedArea;
    }

    $total = count_stores($filters);
    $pages = max(1, (int)ceil($total / $limit));
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $limit;
    $stores = get_stores($filters, $offset, $limit) ?: [];

    function h($v)
    {
        return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
    function build_partners_url(int $toPage, array $keep = ['area', 'type']): string
    {
        $qs = [];
        foreach ($keep as $k) {
            if (isset($_GET[$k]) && $_GET[$k] !== '') {
                $qs[$k] = $_GET[$k];
            }
        }
        $qs['page'] = $toPage;
        $q = http_build_query($qs);
        return '/partners/' . ($q !== '' ? ('?' . $q) : '');
    }
    ?>

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

        .filter-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0.5rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            font-size: 0.8125rem;
            /* 13px */
            transition: all 0.2s ease;
            cursor: pointer;
            color: var(--text-secondary);
            background-color: white;
            font-weight: 500;
        }

        .filter-tag:hover {
            background-color: var(--bg-muted);
            border-color: #d1d5db;
        }

        .filter-tag.active {
            background-color: var(--brand-primary);
            color: white;
            border-color: var(--brand-primary);
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
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
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
            <div class="bg-white border-b border-[var(--border-color)]">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                    <nav class="text-xs mb-3" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                            <li class="flex items-center"><span class="text-gray-700 font-medium">掲載店舗一覧</span></li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">掲載店舗一覧</h1>
                    <p class="text-sm text-[var(--text-secondary)] mt-1">海外リゾキャバ求人.COMがおすすめする優良店舗をご紹介します。</p>
                </div>
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <!-- Filter Section -->
                <div class="bg-white border border-[var(--border-color)] p-6 mb-8">
                    <form id="filter-form">
                        <input type="hidden" name="area" id="area-input" value="">
                        <input type="hidden" name="type" id="type-input">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-slate-400"></i>
                                    <h3 class="text-lg font-bold text-slate-800">エリア</h3>
                                </div>
                                <div id="area-filters" class="flex flex-wrap gap-2">
<?php
    // 表示するエリア候補リスト
    $areaOptions = [
        '' => 'すべて',
        'シンガポール' => 'シンガポール',
        'セブ' => 'セブ',
        'ハノイ' => 'ハノイ',
        'バンコク' => 'バンコク',
        'プノンペン' => 'プノンペン',
        'プーケット' => 'プーケット',
        'ホーチミン' => 'ホーチミン',
        '北海道' => '北海道',
        '東京' => '東京',
        '沖縄' => '沖縄',
        '香港' => '香港',
    ];

    // type を維持（将来用）
    $keepType = isset($_GET['type']) && $_GET['type'] !== '' ? (string)$_GET['type'] : '';

    foreach ($areaOptions as $value => $label):
        $qs = [];
        if ($value !== '') { $qs['area'] = $value; }
        if ($keepType !== '') { $qs['type'] = $keepType; }
        $href = '/partners/' . (!empty($qs) ? ('?' . http_build_query($qs)) : '');
        $isActive = ($value === '' && ($selectedArea === '' || $selectedArea === 'all')) || $selectedArea === $value;
?>
                                    <a href="<?php echo h($href); ?>" class="filter-tag <?php echo $isActive ? 'active' : ''; ?>"><?php echo h($label); ?></a>
<?php endforeach; ?>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <i data-lucide="briefcase" class="w-5 h-5 text-slate-400"></i>
                                    <h3 class="text-lg font-bold text-slate-800">業種</h3>
                                </div>
                                <div id="type-filters" class="flex flex-wrap gap-2">
                                    <!-- Type filter buttons will be populated by JS -->
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 border-t border-[var(--border-color)] pt-6">
                            <button type="submit" class="w-full sm:w-auto flex-1 text-center py-3 px-6 bg-[var(--brand-primary)] text-white font-bold hover:bg-opacity-90 transition-opacity flex items-center justify-center gap-x-2">
                                <i data-lucide="search" class="w-5 h-5"></i>
                                <span>この条件で検索</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mb-6 text-sm font-bold text-slate-800">
                    <?php echo (int)$total; ?>件の掲載店舗が見つかりました
                </div>

                <div id="partner-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php if (!empty($stores)): ?>
                        <?php foreach ($stores as $store):
                            $storeId = (int)($store['id'] ?? 0);
                            $name = $store['name'] ?? '';
                            $country = $store['country'] ?? '';
                            $city = $store['city'] ?? ($store['region_prefecture'] ?? '');
                            $locationLabel = '';
                            if ($country && $city) {
                                $locationLabel = $country . ' / ' . $city;
                            } else {
                                $locationLabel = $country ?: $city;
                            }
                            $imageUrl = null;
                            if (!empty($store['images']) && is_array($store['images'])) {
                                $firstImage = $store['images'][0] ?? null;
                                if ($firstImage && !empty($firstImage['image_url'])) {
                                    $imageUrl = $firstImage['image_url'];
                                }
                            }
                            if (!$imageUrl) {
                                $imageUrl = 'https://placehold.co/1200x800/0ABAB5/ffffff?text=NO+IMAGE';
                            }
                        ?>
                            <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                                <div class="relative">
                                    <div class="overflow-hidden"><img src="<?php echo h($imageUrl); ?>" alt="<?php echo h($name); ?>の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110" loading="lazy"></div>
                                    <span class="absolute top-2 left-2 inline-block bg-slate-600 text-white text-xs font-bold px-2.5 py-1">掲載店舗</span>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <h3 class="font-bold text-lg mb-1 leading-tight"><a href="/partner/<?php echo $storeId; ?>/" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo h($name); ?></a></h3>
                                    <?php if ($locationLabel): ?>
                                        <p class="flex items-center gap-x-1.5 text-xs text-[var(--text-secondary)] mb-3"><i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0"></i><span><?php echo h($locationLabel); ?></span></p>
                                    <?php endif; ?>
                                    <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="/partner/<?php echo $storeId; ?>/" class="block w-full text-center bg-[var(--brand-primary)] border border-[var(--brand-primary)] text-white font-bold py-2.5 px-4 hover:bg-opacity-80 transition-all text-sm">店舗詳細・求人を見る</a></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="md:col-span-2 lg:col-span-3 text-center text-slate-500">ご指定の条件に合う店舗は見つかりませんでした。</p>
                    <?php endif; ?>
                </div>

                <?php if ($pages > 1): ?>
                    <div class="mt-12 text-center">
                        <nav class="inline-flex items-center gap-2" aria-label="Pagination">
                            <?php if ($page > 1): ?>
                                <a href="<?php echo build_partners_url($page - 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">前へ</a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <a href="<?php echo build_partners_url($i); ?>" class="px-3 py-1 border border-[var(--border-color)] <?php echo ($i === $page ? 'font-bold text-[var(--brand-primary)]' : 'text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]'); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>
                            <?php if ($page < $pages): ?>
                                <a href="<?php echo build_partners_url($page + 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">次へ</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
                });
            }

            const partners = [];

            const partnerListContainer = document.getElementById('partner-list');
            const areaFiltersContainer = document.getElementById('area-filters');
            const typeFiltersContainer = document.getElementById('type-filters');
            const areaInput = document.getElementById('area-input');
            const typeInput = document.getElementById('type-input');
            const partnerCountEl = document.getElementById('partner-count');

            const urlParams = new URLSearchParams(window.location.search);
            let activeArea = urlParams.get('area') || 'すべて';
            let activeType = urlParams.get('type') || 'すべて';

            areaInput.value = activeArea;
            typeInput.value = activeType;

            const createPartnerCard = (partner) => {
                const card = document.createElement('div');
                card.innerHTML = `
                    <div class="group bg-white shadow-sm border border-[var(--border-color)] overflow-hidden flex flex-col h-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                        <div class="relative">
                            <div class="overflow-hidden"><img src="${partner.image}" alt="${partner.name}の画像" class="w-full aspect-video object-cover transition-transform duration-500 ease-in-out group-hover:scale-110"></div>
                             <span class="absolute top-2 left-2 inline-block bg-slate-600 text-white text-xs font-bold px-2.5 py-1">${partner.type}</span>
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg mb-1 leading-tight"><a href="/partner/${partner.id}/" class="hover:text-[var(--brand-primary)] transition-colors">${partner.name}</a></h3>
                            <p class="flex items-center gap-x-1.5 text-xs text-[var(--text-secondary)] mb-3"><i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0"></i><span>${partner.location} / ${partner.area_detail}</span></p>
                            <p class="text-sm text-slate-600 flex-grow mb-4">${partner.description}</p>
                             <div class="flex flex-wrap gap-2 mb-4">
                                ${partner.tags.map(tag => `<span class="inline-flex items-center bg-teal-50 text-teal-800 text-xs font-semibold px-2.5 py-1">${tag}</span>`).join('')}
                            </div>
                            <div class="mt-auto pt-3 border-t border-[var(--border-color)]">
                                <a href="/partner/${partner.id}/" class="block w-full text-center bg-[var(--brand-primary)] border border-[var(--brand-primary)] text-white font-bold py-2.5 px-4 hover:bg-opacity-80 transition-all text-sm">店舗詳細・求人を見る</a>
                            </div>
                        </div>
                    </div>`;
                return card;
            };

            function renderFilters() {
                const areas = ['すべて', ...new Set(partners.map(p => p.location))];
                const types = ['すべて', ...new Set(partners.map(p => p.type))];

                areaFiltersContainer.innerHTML = areas.map(area =>
                    `<button type="button" class="filter-tag ${area === activeArea ? 'active' : ''}" data-area="${area}">${area}</button>`
                ).join('');

                typeFiltersContainer.innerHTML = types.map(type =>
                    `<button type="button" class="filter-tag ${type === activeType ? 'active' : ''}" data-type="${type}">${type}</button>`
                ).join('');

                areaFiltersContainer.addEventListener('click', e => {
                    if (e.target.matches('.filter-tag')) {
                        activeArea = e.target.dataset.area;
                        areaInput.value = activeArea;
                        updateActiveButtons(areaFiltersContainer, activeArea, 'area');
                    }
                });

                typeFiltersContainer.addEventListener('click', e => {
                    if (e.target.matches('.filter-tag')) {
                        activeType = e.target.dataset.type;
                        typeInput.value = activeType;
                        updateActiveButtons(typeFiltersContainer, activeType, 'type');
                    }
                });
            }

            function updateActiveButtons(container, activeValue, dataType) {
                container.querySelectorAll('.filter-tag').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset[dataType] === activeValue);
                });
            }

            function renderPartners() {
                partnerListContainer.innerHTML = '';

                const filteredPartners = partners.filter(partner => {
                    const areaMatch = activeArea === 'すべて' || partner.location === activeArea;
                    const typeMatch = activeType === 'すべて' || partner.type === activeType;
                    return areaMatch && typeMatch;
                });

                partnerCountEl.textContent = filteredPartners.length;

                if (filteredPartners.length > 0) {
                    filteredPartners.forEach(partner => {
                        partnerListContainer.appendChild(createPartnerCard(partner));
                    });
                } else {
                    partnerListContainer.innerHTML = `<p class="md:col-span-2 lg:col-span-3 text-center text-slate-500">ご指定の条件に合う店舗は見つかりませんでした。</p>`;
                }

                lucide.createIcons();
            }

            // Initial render
            // renderFilters();
            // renderPartners();
        });
    </script>
</body>

</html>