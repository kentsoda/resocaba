
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php
    $title = 'お知らせ一覧｜海外リゾキャバ求人.COM';
    $description = '海外リゾキャバ求人.COMからのお知らせ一覧です。サイトの更新情報やお得なキャンペーンなど、最新情報をご確認いただけます。';
    $og_title = $title; $og_description = $description;
    $og_type = 'website'; $og_url = 'https://example.com/announcements/';
    $og_image = 'https://placehold.co/1200x630/0ABAB5/ffffff?text=お知らせ';
    require_once __DIR__ . '/includes/header.php';
    // 一覧データ取得（PHPループ化）
    require_once __DIR__ . '/../config/functions.php';
    $limit = 20;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $total = count_announcements([]);
    $pages = max(1, (int)ceil($total / $limit));
    if ($page > $pages) { $page = $pages; }
    $offset = ($page - 1) * $limit;
    $announcements = get_announcements([], $offset, $limit) ?: [];

    // ページネーションURL生成（将来のクエリ引継ぎに対応）
    function build_announcements_url(int $toPage, array $keep = []): string {
        $qs = [];
        foreach ($keep as $k) {
            if (isset($_GET[$k]) && $_GET[$k] !== '') { $qs[$k] = $_GET[$k]; }
        }
        $qs['page'] = $toPage;
        $q = http_build_query($qs);
        return '/announcements/' . ($q !== '' ? ('?' . $q) : '');
    }
    ?>

    <!-- SEO: JSON-LD for Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "トップ",
        "item": "https://example.com/"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "お知らせ一覧"
      }]
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
        .filter-tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0.5rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            font-size: 0.8125rem; /* 13px */
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
        <?php require_once __DIR__ . '/includes/menu.php'; ?>

        <main>
            <!-- Page Header -->
            <div class="bg-white border-b border-[var(--border-color)]">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
                    <nav class="text-xs mb-3" aria-label="Breadcrumb">
                      <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center"><a href="/" class="text-gray-500 hover:text-[var(--brand-primary)]">トップ</a><i data-lucide="chevron-right" class="w-3 h-3 mx-1 text-gray-400"></i></li>
                        <li class="flex items-center"><span class="text-gray-700 font-medium">お知らせ一覧</span></li>
                      </ol>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">お知らせ</h1>
                    <p class="text-sm text-[var(--text-secondary)] mt-1">サイトの最新情報やキャンペーンなどをお届けします。</p>
                </div>
            </div>
            
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
                <div class="max-w-4xl mx-auto">
                    

                    <div class="bg-white border border-[var(--border-color)]">
                        <div id="announcement-list" class="divide-y divide-[var(--border-color)]">
<?php if (!empty($announcements)): ?>
<?php foreach ($announcements as $n):
    $dateRaw = $n['published_at'] ?: ($n['created_at'] ?? '');
    $date = $dateRaw ? date('Y.m.d', strtotime($dateRaw)) : '';
    $url = '/announcement/' . (int)$n['id'] . '/';
?>
                            <a href="<?php echo $url; ?>" class="block p-6 hover:bg-[var(--bg-muted)] transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
<?php if ($date): ?>
                                            <span class="text-xs text-slate-500"><?php echo $date; ?></span>
<?php endif; ?>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800"><?php echo htmlspecialchars($n['title']); ?></h3>
                                    </div>
                                    <div class="text-[var(--brand-primary)] font-bold text-sm flex items-center gap-1">詳しく見る<i data-lucide="arrow-right" class="w-4 h-4"></i></div>
                                </div>
                            </a>
<?php endforeach; ?>
<?php else: ?>
                            <p class="p-6 text-center text-slate-500">該当するお知らせはありません。</p>
<?php endif; ?>
                        </div>
                    </div>

<?php if ($pages > 1): ?>
                    <div class="mt-12 text-center">
                        <nav class="inline-flex items-center gap-2" aria-label="Pagination">
<?php if ($page > 1): ?>
                            <a href="<?php echo build_announcements_url($page - 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">前へ</a>
<?php endif; ?>
<?php for ($i = 1; $i <= $pages; $i++): ?>
                            <a href="<?php echo build_announcements_url($i); ?>" class="px-3 py-1 border border-[var(--border-color)] <?php echo ($i === $page ? 'font-bold text-[var(--brand-primary)]' : 'text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]'); ?>"><?php echo $i; ?></a>
<?php endfor; ?>
<?php if ($page < $pages): ?>
                            <a href="<?php echo build_announcements_url($page + 1); ?>" class="px-3 py-1 border border-[var(--border-color)] text-[var(--text-secondary)] hover:text-[var(--brand-primary)] hover:border-[var(--brand-primary)]">次へ</a>
<?php endif; ?>
                        </nav>
                    </div>
<?php endif; ?>
                </div>
            </div>
        </main>
        
        <?php require_once __DIR__ . '/includes/footer.php'; ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
            
            // --- Data ---
            const announcements = [];

            const categoryFiltersContainer = document.getElementById('category-filters');
            const categoryInput = document.getElementById('category-input');
            const announcementListContainer = document.getElementById('announcement-list');

            const urlParams = new URLSearchParams(window.location.search);
            let activeCategory = urlParams.get('category') || 'すべて';
            categoryInput.value = activeCategory;

            // --- Functions ---
            const createAnnouncementItem = (item) => {
                const link = document.createElement('a');
                link.href = item.link;
                link.className = 'block p-6 group transition-colors hover:bg-slate-50';
                link.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:gap-x-6">
                        <div class="flex-shrink-0 mb-2 sm:mb-0 flex items-center gap-x-4">
                            <p class="text-sm text-slate-500 w-24">${item.date}</p>
                            <span class="inline-block ${item.category_class} text-xs font-semibold px-2.5 py-1">${item.category}</span>
                        </div>
                        <h3 class="font-semibold text-slate-800 group-hover:text-[var(--brand-primary)]">${item.title}</h3>
                    </div>`;
                return link;
            };

            function renderFilters() {
                const categories = ['すべて', ...new Set(announcements.map(a => a.category))];
                
                categoryFiltersContainer.innerHTML = categories.map(cat => 
                    `<button type="button" class="filter-tag ${cat === activeCategory ? 'active' : ''}" data-category="${cat}">${cat}</button>`
                ).join('');

                categoryFiltersContainer.addEventListener('click', e => {
                    if (e.target.matches('.filter-tag')) {
                        activeCategory = e.target.dataset.category;
                        categoryInput.value = activeCategory;
                        updateActiveButtons();
                    }
                });
            }
            
            function updateActiveButtons() {
                categoryFiltersContainer.querySelectorAll('.filter-tag').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.category === activeCategory);
                });
            }

            function renderAnnouncements() {
                announcementListContainer.innerHTML = '';
                const filteredAnnouncements = announcements.filter(a => 
                    activeCategory === 'すべて' || a.category === activeCategory
                );

                if(filteredAnnouncements.length > 0) {
                    filteredAnnouncements.forEach(a => announcementListContainer.appendChild(createAnnouncementItem(a)));
                } else {
                    announcementListContainer.innerHTML = `<p class="p-6 text-center text-slate-500">該当するお知らせはありません。</p>`;
                }
            }

            // --- Initial Render ---
            // renderFilters();
            // renderAnnouncements();
        });
    </script>
</body>
</html>
