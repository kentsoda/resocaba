
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php
    require_once __DIR__ . '/../config/functions.php';

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $article = null;
    $notFound = false;

    if ($id) {
        $article = executeQuerySingle(
            "SELECT id, title, slug, body_html, category, og_image_url, status, published_at, updated_at FROM articles WHERE id = ? AND status = 'published' AND deleted_at IS NULL LIMIT 1",
            [(int)$id]
        );
    }

    if (!$id || !$article) {
        http_response_code(404);
        $notFound = true;
        $article = [
            'id' => 0,
            'title' => '記事が見つかりませんでした',
            'body_html' => '<p>お探しの記事は存在しないか、公開が終了しました。</p><p><a href="/features/">特集・コラム一覧へ戻る</a></p>',
            'category' => '',
            'og_image_url' => null,
            'published_at' => null,
            'updated_at' => null,
        ];
    }

    $raw = strip_tags($article['body_html'] ?? '');
    $raw = preg_replace('/\s+/u', ' ', $raw);
    $description = mb_strimwidth($raw, 0, 160, '...', 'UTF-8');

    $title = ($article['title'] ?? '') . '｜特集・コラム｜海外リゾキャバ求人.COM';
    $og_title = $article['title'] ?? '';
    $og_description = $description;
    $og_type = 'article';
    $og_url = $notFound
        ? sprintf('https://%s/features/', $_SERVER['HTTP_HOST'])
        : sprintf('https://%s/feature/%d/', $_SERVER['HTTP_HOST'], (int)$article['id']);
    $og_image = !empty($article['og_image_url']) ? $article['og_image_url'] : 'https://placehold.co/1200x630/0ABAB5/ffffff?text=FEATURE';

    require_once __DIR__ . '/includes/header.php';
    ?>

    <!-- SEO: JSON-LD for Structured Data -->
        <script type="application/ld+json">
<?php
    $tz = new DateTimeZone('Asia/Tokyo');
    $pub = null; $mod = null;
    if (!empty($article['published_at'])) {
        $d = new DateTime($article['published_at']);
        $d->setTimezone($tz);
        $pub = $d->format('c');
    }
    if (!empty($article['updated_at'])) {
        $d2 = new DateTime($article['updated_at']);
        $d2->setTimezone($tz);
        $mod = $d2->format('c');
    }
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'トップ', 'item' => sprintf('https://%s/', $_SERVER['HTTP_HOST'])],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => '特集・コラム一覧', 'item' => sprintf('https://%s/features/', $_SERVER['HTTP_HOST'])],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $article['title'] ?? '']
                ]
            ],
            [
                '@type' => 'Article',
                'headline' => $article['title'] ?? '',
                'datePublished' => $pub,
                'dateModified' => $mod ?: $pub,
                'author' => ['@type' => 'Organization', 'name' => '海外リゾキャバ求人.COM 編集部'],
                'publisher' => ['@type' => 'Organization', 'name' => '海外リゾキャバ求人.COM'],
                'description' => $og_description,
                'image' => $og_image,
                'mainEntityOfPage' => $og_url
            ]
        ]
    ];
    echo json_encode($jsonLd, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
?>
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
        .prose { line-height: 1.8; }
        .prose h2 { font-size: 1.25rem; font-weight: 700; padding-bottom: 0.75rem; border-bottom: 2px solid var(--brand-primary); margin-top: 2.5rem; margin-bottom: 1.5rem; }
        .prose p, .prose ul, .prose ol { margin-bottom: 1.5rem; }
        .prose a { color: var(--brand-primary); text-decoration: underline; }
        .prose a:hover { opacity: 0.8; }
        .prose ul { list-style: disc; padding-left: 1.5em; }
        .prose strong { font-weight: 700; }
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
                        <a href="/announcements/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
                        <a href="/features/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">特集・コラム</a>
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
                    <nav class="text-xs mb-3" aria-label="Breadcrumb">
                      <ol class="list-none p-0 flex flex-wrap items-center">
                        <li class="flex items-center flex-shrink-0"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center flex-shrink-0"><a href="/features/" class="text-gray-500 hover:text-[var(--brand-primary)]">特集・コラム一覧</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center min-w-0"><span class="text-gray-700 font-medium truncate"><?php echo htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span></li>
                      </ol>
                    </nav>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto bg-white border border-[var(--border-color)] p-6 sm:p-10">
                    <div class="mb-6 pb-6 border-b border-[var(--border-color)]">
                        <div class="flex items-center gap-x-4 mb-3">
                            <p class="text-sm text-slate-500"><?php if (!empty($article['published_at'])) { $d=new DateTime($article['published_at']); $d->setTimezone(new DateTimeZone('Asia/Tokyo')); echo htmlspecialchars($d->format('Y.m.d'), ENT_QUOTES, 'UTF-8'); } ?></p>
                            <?php if (!empty($article['category'])): ?>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1"><?php echo htmlspecialchars($article['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endif; ?>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)] !leading-tight"><?php echo htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
                    </div>

                    <article class="prose max-w-none">
<?php echo $article['body_html'] ?? ''; ?>                    </article>

                    <!-- Prev/Next Navigation -->
                    <div id="post-navigation" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-12 pt-8 border-t border-[var(--border-color)]">
<?php if (!$notFound && !empty($article['id'])): 
    $prev = null; $next = null;
    if (!empty($article['published_at'])) {
        $prev = executeQuerySingle(
            "SELECT id, title FROM articles WHERE status='published' AND deleted_at IS NULL AND published_at < ? ORDER BY published_at DESC LIMIT 1",
            [$article['published_at']]
        );
        $next = executeQuerySingle(
            "SELECT id, title FROM articles WHERE status='published' AND deleted_at IS NULL AND published_at > ? ORDER BY published_at ASC LIMIT 1",
            [$article['published_at']]
        );
    } else {
        $prev = executeQuerySingle(
            "SELECT id, title FROM articles WHERE status='published' AND deleted_at IS NULL AND id < ? ORDER BY id DESC LIMIT 1",
            [(int)$article['id']]
        );
        $next = executeQuerySingle(
            "SELECT id, title FROM articles WHERE status='published' AND deleted_at IS NULL AND id > ? ORDER BY id ASC LIMIT 1",
            [(int)$article['id']]
        );
    }
?>
                        <div>
<?php if (!empty($prev)): ?>
                            <a href="/feature/<?php echo (int)$prev['id']; ?>/" class="inline-flex items-center gap-x-2 text-sm text-slate-600 hover:text-[var(--brand-primary)]">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                <span><?php echo htmlspecialchars($prev['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </a>
<?php endif; ?>
                        </div>
                        <div class="text-right">
<?php if (!empty($next)): ?>
                            <a href="/feature/<?php echo (int)$next['id']; ?>/" class="inline-flex items-center gap-x-2 justify-end text-sm text-slate-600 hover:text-[var(--brand-primary)]">
                                <span><?php echo htmlspecialchars($next['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
<?php endif; ?>
                        </div>
<?php endif; ?>
                    </div>

                    <!-- Author Box -->
                    <div class="mt-12 pt-8 border-t border-[var(--border-color)]">
                        <h3 class="font-bold mb-4">この記事を書いた人</h3>
                        <div class="flex items-start gap-4 bg-slate-50 p-4">
                            <img src="https://placehold.co/100x100/ced4da/495057?text=Staff" alt="著者" class="w-20 h-20 rounded-full">
                            <div>
                                <p class="font-bold text-[var(--text-primary)]">海外リゾキャバ求人.COM 編集部</p>
                                <p class="text-xs text-slate-500 mt-1">海外でのリゾートバイトやワーキングホリデーに関する情報を発信する編集チームです。経験豊富なスタッフが、あなたの海外での挑戦をサポートします。</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-[var(--border-color)] text-center">
                        <a href="/features/" class="inline-flex items-center justify-center gap-x-2 w-full sm:w-auto px-8 py-3 text-sm font-semibold text-slate-600 bg-white border border-[var(--border-color)] hover:bg-slate-50 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>特集・コラム一覧へ戻る</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ad Banner Section -->
            <div class="bg-white py-12 sm:py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                     <div class="flex flex-col gap-4 lg:grid lg:grid-cols-4 lg:gap-0">
                        <a href="#" class="block group overflow-hidden"><img src="https://placehold.co/640x200/0ABAB5/ffffff?text=AD+BANNER+1" alt="広告バナー 1" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy"></a>
                        <a href="#" class="block group overflow-hidden"><img src="https://placehold.co/640x200/f59e0b/ffffff?text=AD+BANNER+2" alt="広告バナー 2" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy"></a>
                        <a href="#" class="block group overflow-hidden"><img src="https://placehold.co/640x200/1e293b/ffffff?text=AD+BANNER+3" alt="広告バナー 3" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy"></a>
                        <a href="#" class="block group overflow-hidden"><img src="https://placehold.co/640x200/475569/ffffff?text=AD+BANNER+4" alt="広告バナー 4" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy"></a>
                    </div>
                </div>
            </div>
        </main>
        
        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', !mobileMenu.classList.contains('hidden'));
                });
            }

            // --- Table of Contents Generator ---
            const tocContainer = document.getElementById('toc-container');
            const tocList = document.getElementById('toc-list');
            const articleForToc = document.querySelector('.prose');
            if (tocContainer && tocList && articleForToc) {
                const headings = articleForToc.querySelectorAll('h2');
                if (headings.length > 1) {
                    headings.forEach(h => {
                        if (h.id) {
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `<a href="#${h.id}" class="text-slate-600 hover:text-[var(--brand-primary)] text-sm flex items-start gap-x-2"><i data-lucide="chevrons-right" class="w-3.5 h-3.5 mt-0.5 flex-shrink-0"></i><span>${h.textContent}</span></a>`;
                            tocList.appendChild(listItem);
                        }
                    });
                    if (window.lucide && typeof lucide.createIcons === 'function') {
                        lucide.createIcons();
                    }
                } else if (tocContainer) {
                    tocContainer.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
