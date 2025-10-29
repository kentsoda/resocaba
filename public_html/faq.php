<!DOCTYPE html>
<html lang="ja">

<head>
  <?php
  $title = 'よくある質問｜海外リゾキャバ求人.COM';
  $description = '海外リゾートバイトやリゾキャバに関するよくある質問とその回答をまとめました。応募、海外生活、お仕事内容など、あなたの疑問を解決します。';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'website';
  $og_url = 'https://example.com/faq/';
  $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=FAQ';
  require_once __DIR__ . '/includes/header.php';

  // FAQデータ取得
  require_once __DIR__ . '/../config/functions.php';
  $faqs = get_faq_list();
  if ($faqs === false || $faqs === null) {
    $faqs = [];
  }

  // JSON-LD 構築
  $faqEntities = [];
  foreach ($faqs as $f) {
    $answerText = trim(preg_replace('/\s+/', ' ', strip_tags($f['answer_html'])));
    $faqEntities[] = [
      '@type' => 'Question',
      'name' => $f['question'],
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text' => $answerText,
      ],
    ];
  }

  $jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
      [
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
          [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'トップ',
            'item' => 'https://example.com/',
          ],
          [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'よくある質問',
          ],
        ],
      ],
      [
        '@type' => 'FAQPage',
        'mainEntity' => $faqEntities,
      ],
    ],
  ];
  ?>

  <!-- SEO: JSON-LD for Structured Data -->
  <script type="application/ld+json">
    <?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
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

    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-in-out;
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
            <a href="/partners/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">掲載店舗</a>
            <a href="/announcements/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">お知らせ</a>
            <a href="/features/" class="text-sm font-medium text-[var(--text-secondary)] hover:text-[var(--brand-primary)] transition-colors">特集・コラム</a>
            <a href="/faq/" class="text-sm font-medium text-[var(--brand-primary)] font-bold">よくある質問</a>
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
            <a href="/jobs/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">求人検索</a>
            <a href="/partners/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">掲載店舗</a>
            <a href="/announcements/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">お知らせ</a>
            <a href="/features/" class="block px-3 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--bg-muted)] hover:text-[var(--brand-primary)]">特集・コラム</a>
            <a href="/faq/" class="block px-3 py-2 text-sm font-medium text-[var(--brand-primary)] bg-[var(--bg-muted)]">よくある質問</a>
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
              <li class="flex items-center"><span class="text-gray-700 font-medium">よくある質問</span></li>
            </ol>
          </nav>
          <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">よくある質問</h1>
          <p class="mt-2 text-slate-600">海外リゾキャバ・リゾートバイトに関するよくある質問とその回答をまとめました。</p>
        </div>
      </div>

      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div>
          <h2 class="text-2xl font-bold text-slate-800 mb-6 pb-3 border-b-2 border-[var(--brand-primary)]">よくある質問</h2>
          <div class="space-y-4"><?php if (!empty($faqs)) : foreach ($faqs as $faq) : ?><div class="accordion-item bg-white border border-[var(--border-color)]"><button class="accordion-button w-full flex justify-between items-center text-left p-5"><span class="font-semibold text-base flex items-start gap-x-4"><span class="text-xl font-bold text-[var(--brand-primary)]">Q.</span><span><?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?></span></span><i data-lucide="plus" class="accordion-icon w-5 h-5 flex-shrink-0 text-slate-500 transition-transform"></i></button>
                  <div class="accordion-content">
                    <div class="px-5 pb-5">
                      <div class="text-slate-600 leading-relaxed flex items-start gap-x-4"><span class="text-xl font-bold text-slate-400">A.</span>
                        <div class="prose-sm"><?= $faq['answer_html'] ?></div>
                      </div>
                    </div>
                  </div>
                </div><?php endforeach;
                                  else : ?><div class="bg-white border border-[var(--border-color)] p-5 text-slate-600">現在、公開中のFAQはありません。</div><?php endif; ?></div>
        </div>
      </div>
  </div>
  </main>

  <?php require_once __DIR__ . '/includes/footer.php'; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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

      // Accordion functionality
      const accordionItems = document.querySelectorAll('.accordion-item');
      accordionItems.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-content');
        const icon = item.querySelector('.accordion-icon');

        button.addEventListener('click', () => {
          const isExpanded = content.style.maxHeight && content.style.maxHeight !== '0px';

          // Close all other items
          // accordionItems.forEach(otherItem => {
          //     if (otherItem !== item) {
          //         otherItem.querySelector('.accordion-content').style.maxHeight = '0px';
          //         const otherIcon = otherItem.querySelector('.accordion-icon');
          //         if (otherIcon) {
          //            otherIcon.outerHTML = '<i data-lucide="plus" class="accordion-icon w-5 h-5 flex-shrink-0 text-slate-500 transition-transform"></i>';
          //         }
          //     }
          // });

          if (isExpanded) {
            content.style.maxHeight = '0px';
            if (icon) icon.outerHTML = '<i data-lucide="plus" class="accordion-icon w-5 h-5 flex-shrink-0 text-slate-500 transition-transform"></i>';
          } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            if (icon) icon.outerHTML = '<i data-lucide="minus" class="accordion-icon w-5 h-5 flex-shrink-0 text-slate-500 transition-transform"></i>';
          }

          lucide.createIcons();
        });
      });
    });
  </script>
</body>

</html>