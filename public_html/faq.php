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
    <?php require_once __DIR__ . '/includes/menu.php'; ?>

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