<!DOCTYPE html>
<html lang="ja">

<head>
  <?php
  $title = '無料登録｜海外リゾキャバ求人.COM';
  $description = '無料登録ページです。';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'website';
  $og_url = 'https://resocaba-info.com/register/';
  $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=Register';
  require_once __DIR__ . '/includes/header.php';

  // JSON-LD 構築
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
            'item' => 'https://resocaba-info.com/',
          ],
          [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => '無料登録',
          ],
        ],
      ],
      [
        '@type' => 'WebPage',
        'name' => $title,
        'description' => $description,
        'url' => $og_url,
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
              <li class="flex items-center"><span class="text-gray-700 font-medium">無料登録</span></li>
            </ol>
          </nav>
          <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">無料登録</h1>
        </div>
      </div>

      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="bg-white p-6 sm:p-8 border border-[var(--border-color)] text-center">
          <p class="text-lg text-slate-700">準備中です</p>
        </div>
      </div>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
    });
  </script>
</body>

</html>

