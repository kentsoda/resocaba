<!DOCTYPE html>
<html lang="ja">

<head>
  <?php
  $title = 'プライバシーポリシー｜海外リゾキャバ求人.COM';
  $description = '海外リゾキャバ求人.COMのプライバシーポリシーをご確認ください。個人情報の取り扱いについて定めています。';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'website';
  $og_url = 'https://example.com/privacy/';
  $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=Privacy+Policy';
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
            'item' => 'https://example.com/',
          ],
          [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'プライバシーポリシー',
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

    .privacy-content h2 {
      color: var(--brand-primary);
      border-bottom: 2px solid var(--brand-primary);
      padding-bottom: 0.5rem;
      margin-top: 2rem;
      margin-bottom: 1rem;
    }

    .privacy-content h3 {
      color: var(--text-primary);
      margin-top: 1.5rem;
      margin-bottom: 0.5rem;
      font-size: 1.125rem;
      font-weight: 600;
    }

    .privacy-content p {
      margin-bottom: 1rem;
      line-height: 1.7;
    }

    .privacy-content ol {
      margin-left: 1.5rem;
      margin-bottom: 1rem;
    }

    .privacy-content li {
      margin-bottom: 0.5rem;
      line-height: 1.6;
    }

    .privacy-content .article-number {
      font-weight: 600;
      color: var(--brand-primary);
      margin-bottom: 0.5rem;
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
              <li class="flex items-center"><span class="text-gray-700 font-medium">プライバシーポリシー</span></li>
            </ol>
          </nav>
          <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">プライバシーポリシー</h1>
          <p class="mt-2 text-slate-600">海外リゾキャバ求人.COMの個人情報の取り扱いについてご確認ください。</p>
        </div>
      </div>

      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="bg-white border border-[var(--border-color)] p-8 sm:p-12">
          <div class="privacy-content prose prose-slate max-w-none">
            <p class="text-lg font-semibold text-center mb-8 text-[var(--brand-primary)]">
              海外リゾキャバ求人.COM プライバシーポリシー
            </p>

            <p class="mb-6">
              海外リゾキャバ求人.COM 以下「当サイト」といいます）は、当サイト上で提供する求人情報サービスにおいて取扱う個人情報について、以下のとおりプライバシーポリシーを定めます。
            </p>

            <p class="mb-6">
              当サイトをご利用いただく場合、本プライバシーポリシーに同意いただいたものとみなします。
            </p>

            <h3 class="article-number">1. 個人情報の管理</h3>
            <p>
              当サイトは、ユーザーの個人情報を正確かつ最新の状態に保ち、不正アクセス、紛失、破損、改ざん、漏洩等を防止するための適切なセキュリティ対策を講じ、個人情報の厳重な管理に努めます。
            </p>

            <h3 class="article-number">2. 取得する個人情報と利用目的</h3>
            <p>
              当サイトでは、求人応募やお問い合わせの際に、以下の情報を収集することがあります。
            </p>
            <ul class="list-disc ml-6 mb-4">
              <li>お名前（ふりがな）</li>
              <li>性別</li>
              <li>生年月日</li>
              <li>メールアドレス</li>
              <li>電話番号</li>
              <li>現在のお住まいの地域名</li>
            </ul>
            <p>
              これらの個人情報は、以下の目的で利用します。
            </p>
            <ul class="list-disc ml-6 mb-4">
              <li>応募またはお問い合わせへの回答や連絡</li>
              <li>応募先店舗との連絡や面接調整のための情報提供</li>
              <li>当サイトの運営およびサービス改善のための統計的分析</li>
              <li>必要に応じたサービス案内やサポートの提供</li>
            </ul>
            <p>
              当サイトは、上記目的以外で個人情報を利用することはありません。
            </p>

            <h3 class="article-number">3. 個人情報の第三者提供</h3>
            <p>
              当サイトは、以下の場合を除き、ユーザーの個人情報を第三者に提供することはありません。
            </p>
            <ol class="list-decimal">
              <li>ユーザーの同意がある場合</li>
              <li>求人応募など、ユーザーが希望するサービス提供のために必要な場合（例：応募先店舗への情報提供）</li>
              <li>法令に基づき開示が必要な場合</li>
            </ol>
            <p>
              なお、当サイトは求人広告掲載サイトであり、職業紹介・斡旋業務は行っておりません。<br>
              応募者と店舗との間で発生する連絡や契約は、すべて当事者間の責任において行われます。
            </p>

            <h3 class="article-number">4. 個人情報の安全対策</h3>
            <p>
              当サイトは、個人情報の正確性および安全性を確保するため、SSLによる通信暗号化などの合理的な安全対策を実施し、個人情報の漏洩・改ざん・不正アクセスなどを防止します。
            </p>

            <h3 class="article-number">5. 個人情報の照会・修正・削除</h3>
            <p>
              ユーザーが自身の個人情報の照会・修正・削除を希望される場合には、当サイト所定の手続きにより、本人確認のうえ速やかに対応いたします。
            </p>

            <h3 class="article-number">6. アクセス解析ツールの利用</h3>
            <p>
              当サイトでは、サービス改善および利用状況の把握のため、匿名のトラフィックデータを収集します。<br>
              このデータは個人を特定するものではありません。<br>
              ユーザーはブラウザ設定によりクッキーの受け入れを拒否することができますが、その場合、一部機能が利用できない場合があります。
            </p>

            <h3 class="article-number">7. 法令遵守と見直し</h3>
            <p>
              当サイトは、個人情報の取扱いに関して、個人情報保護法およびその他関連法令を遵守します。<br>
              また、本ポリシーの内容を適宜見直し、改善に努めます。<br>
              変更があった場合は、当サイト上で速やかに公表いたします。
            </p>

            <h3 class="article-number">8. お問い合わせ窓口</h3>
            <p>
              当サイトのプライバシーポリシーに関するお問い合わせは、下記の窓口までご連絡ください。
            </p>
            <div class="bg-slate-50 p-4 mt-4 border-l-4 border-[var(--brand-primary)]">
              <a href="https://resocaba-info.com/contact/" class="font-semibold text-[var(--brand-primary)] hover:underline">お問い合わせ</a>
            </div>
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
