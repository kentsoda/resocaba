<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    $title = '特集・コラム一覧｜海外リゾキャバ求人.COM';
    $description = '海外リゾキャバ・リゾートバイトに関する特集やコラムの一覧です。エリア紹介や準備のノウハウ、体験談など、役立つ情報をお届けします。';
    $og_title = $title;
    $og_description = $description;
    $og_type = 'website';
    $og_url = 'https://example.com/features/';
    $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=Features';
    require_once __DIR__ . '/includes/header.php';
    // 一覧データ取得（PHPループ化）
    require_once __DIR__ . '/../config/functions.php';
    $limit = 20;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $selectedCategory = $_GET['category'] ?? 'all';
    $filters = [];
    if ($selectedCategory !== '' && $selectedCategory !== 'all') {
        $filters['category'] = $selectedCategory;
    }
    $total = count_articles($filters);
    $pages = max(1, (int)ceil($total / $limit));
    if ($page > $pages) { $page = $pages; }
    $offset = ($page - 1) * $limit;
    $articles = get_articles($filters, $offset, $limit) ?: [];

    // ページネーションURL（categoryを維持）
    function build_features_url(int $toPage, array $keep = ['category']): string {
        $qs = [];
        foreach ($keep as $k) {
            if (isset($_GET[$k]) && $_GET[$k] !== '') { $qs[$k] = $_GET[$k]; }
        }
        $qs['page'] = $toPage;
        $q = http_build_query($qs);
        return '/features/' . ($q !== '' ? ('?' . $q) : '');
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
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Noto Sans JP', sans-serif;
            color: var(--text-primary);
            background-color: var(--bg-base);
            overflow-x: hidden;
        }

        .filter-button {
            border-radius: 0.25rem;
            transition: all 0.2s ease-in-out;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
                });
            }
        });
    </script>
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
                        <a href="/partners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
                        <a href="/announcements/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
                        <a href="/features/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">特集・コラム</a>
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
                        <a href="/partners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">掲載店舗</a>
                        <a href="/announcements/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">お知らせ</a>
                        <a href="/features/" class="block px-3 py-2 text-sm font-medium text-[var(--brand-primary)] bg-[var(--bg-muted)]">特集・コラム</a>
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
                    <nav class="text-sm" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-4 h-4 mx-1 text-gray-400"></i></li>
                            <li class="flex items-center"><span class="text-gray-700 font-medium">特集・コラム一覧</span></li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">特集・コラム</h1>
                    <p class="mt-2 text-slate-600">海外リゾキャバ・リゾートバイトに関する特集やコラムの一覧です。</p>
                </div>
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <!-- Filter Section -->
                <div class="bg-white border border-[var(--border-color)] p-4 mb-8">
                    <form id="filter-form" method="get">
                        <div class="flex flex-col items-start gap-y-3 sm:flex-row sm:items-center sm:gap-x-4">
                            <h3 class="text-sm font-semibold text-slate-700 flex-shrink-0">カテゴリで絞り込む</h3>
                            <div id="category-filters" class="flex flex-wrap gap-2">
                                <button type="submit" name="category" value="all"
                                    class="filter-button px-3 py-1.5 text-xs sm:text-sm font-semibold border <?php echo ($selectedCategory === 'all' || $selectedCategory === '' ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'); ?>">
                                    すべて </button>
                                <button type="submit" name="category" value="エリア紹介"
                                    class="filter-button px-3 py-1.5 text-xs sm:text-sm font-semibold border <?php echo ($selectedCategory === 'エリア紹介' ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'); ?>">
                                    エリア紹介 </button>
                                <button type="submit" name="category" value="ノウハウ"
                                    class="filter-button px-3 py-1.5 text-xs sm:text-sm font-semibold border <?php echo ($selectedCategory === 'ノウハウ' ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'); ?>">
                                    ノウハウ </button>
                                <button type="submit" name="category" value="体験談"
                                    class="filter-button px-3 py-1.5 text-xs sm:text-sm font-semibold border <?php echo ($selectedCategory === '体験談' ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'); ?>">
                                    体験談 </button>
                                <button type="submit" name="category" value="初めての方"
                                    class="filter-button px-3 py-1.5 text-xs sm:text-sm font-semibold border <?php echo ($selectedCategory === '初めての方' ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50'); ?>">
                                    初めての方 </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Results Count -->
                <div class="mb-6">
                    <p class="text-sm text-slate-600">全 <strong id="results-count" class="text-lg font-bold text-[var(--brand-primary)]"><?php echo (int)$total; ?></strong> 件の記事が見つかりました。</p>
                </div>

                <!-- Features Grid -->
                <div id="features-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($articles as $a):
    $img = !empty($a['og_image_url']) ? $a['og_image_url'] : 'https://placehold.co/600x400/0ABAB5/ffffff?text=Feature';
    $cardUrl = '/feature/' . (int)$a['id'] . '/';
?>
                    <div class="bg-white border border-[var(--border-color)] overflow-hidden flex flex-col h-full">
                        <div class="overflow-hidden"><img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($a['title']); ?>の画像" class="w-full aspect-video object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex items-center gap-x-2 mb-2">
<?php if (!empty($a['category'])): ?>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1"><?php echo htmlspecialchars($a['category']); ?></span>
<?php endif; ?>
<?php if (!empty($a['published_at'])): ?>
                                <span class="text-xs text-slate-500"><?php echo date('Y.m.d', strtotime($a['published_at'])); ?></span>
<?php endif; ?>
                            </div>
                            <h3 class="font-bold text-base mb-3 leading-tight"><a href="<?php echo $cardUrl; ?>" class="hover:text-[var(--brand-primary)] transition-colors"><?php echo htmlspecialchars($a['title']); ?></a></h3>
                            <div class="mt-auto pt-3 border-t border-[var(--border-color)]"><a href="<?php echo $cardUrl; ?>" class="block w-full text-center bg-white border border-[var(--border-color)] text-[var(--text-secondary)] font-bold py-2 px-4 hover:border-[var(--brand-primary)] hover:text-[var(--brand-primary)] transition-all text-xs">詳しく見る</a></div>
                        </div>
                    </div>
<?php endforeach; ?>
                </div>

                <!-- Pagination (Optional) -->
                <div id="pagination" class="mt-12 text-center">
<?php if ($pages > 1): ?>
                    <nav class="inline-flex items-center gap-2" aria-label="Pagination">
<?php if ($page > 1): ?>
                        <a href="<?php echo build_features_url($page - 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">前へ</a>
<?php endif; ?>
<?php for ($i = 1; $i <= $pages; $i++): ?>
                        <a href="<?php echo build_features_url($i); ?>" class="px-3 py-1 border border-[var(--border-color)] <?php echo ($i === $page ? 'font-bold text-[var(--brand-primary)]' : 'text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]'); ?>"><?php echo $i; ?></a>
<?php endfor; ?>
<?php if ($page < $pages): ?>
                        <a href="<?php echo build_features_url($page + 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">次へ</a>
<?php endif; ?>
                    </nav>
<?php endif; ?>
                </div>
            </div>
        </main>

        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>

</body>

</html>