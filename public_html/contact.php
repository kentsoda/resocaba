<!DOCTYPE html>
<html lang="ja">

<head>
  <?php
  $title = 'お問い合わせ｜海外リゾキャバ求人.COM';
  $description = '求人や掲載に関するお問い合わせはこちらのフォームからご連絡ください。';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'website';
  $og_url = 'https://resocaba-info.com/contact/';
  $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=Contact';
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
            'name' => 'お問い合わせ',
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
              <li class="flex items-center"><span class="text-gray-700 font-medium">お問い合わせ</span></li>
            </ol>
          </nav>
          <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">お問い合わせ</h1>
          <p class="mt-2 text-slate-600">求人に関するご質問、掲載についてのご相談など、お気軽にお寄せください。</p>
        </div>
      </div>

      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div id="contact-form-area" class="bg-white p-6 sm:p-8 border border-[var(--border-color)]">
          <form method="POST" action="/contact/" class="space-y-6">
            <div>
              <label for="type" class="block text-sm font-medium text-slate-700 mb-1">お問い合わせ種別</label>
              <select id="type" name="type" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
                <option value="">選択してください</option>
                <option value="求人について">求人について</option>
                <option value="掲載について">掲載について</option>
                <option value="その他">その他</option>
              </select>
            </div>
            <div>
              <label for="name" class="block text-sm font-medium text-slate-700 mb-1">お名前 <span class="text-red-500">*</span></label>
              <input type="text" id="name" name="name" required placeholder="例：山田 花子" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-slate-700 mb-1">メールアドレス <span class="text-red-500">*</span></label>
              <input type="email" id="email" name="email" required placeholder="例：example@email.com" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
            </div>
            <div>
              <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">件名 <span class="text-red-500">*</span></label>
              <input type="text" id="subject" name="subject" required placeholder="お問い合わせの件名" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition">
            </div>
            <div>
              <label for="message" class="block text-sm font-medium text-slate-700 mb-1">お問い合わせ内容 <span class="text-red-500">*</span></label>
              <textarea id="message" name="message" rows="6" required placeholder="できるだけ具体的にご記入ください。" class="w-full border border-slate-300 p-3 text-sm focus:ring-1 focus:ring-[var(--brand-primary)] focus:border-[var(--brand-primary)] transition"></textarea>
            </div>
            <div class="text-xs text-slate-500">
              このフォームから送信された情報は<a href="/privacy/" class="text-[var(--brand-primary)] hover:underline">プライバシーポリシー</a>に基づき取り扱います。
            </div>
            <button type="submit" class="w-full text-center py-4 px-6 bg-[var(--brand-primary)] text-white font-bold hover:bg-opacity-90 transition-opacity flex items-center justify-center gap-x-2 text-base">
              <span>送信する</span>
              <i data-lucide="send" class="w-5 h-5"></i>
            </button>
          </form>
        </div>
      </div>

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
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
    });
  </script>
</body>

</html>

