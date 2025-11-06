<!DOCTYPE html>
<html lang="ja">

<head>
  <?php
  $title = '利用規約｜海外リゾキャバ求人.COM';
  $description = '海外リゾキャバ求人.COMの利用規約をご確認ください。サービス利用に関するルールや条件を定めています。';
  $og_title = $title;
  $og_description = $description;
  $og_type = 'website';
  $og_url = 'https://example.com/terms/';
  $og_image = 'https://placehold.co/1200x630/0abab5/ffffff?text=Terms+of+Service';
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
            'name' => '利用規約',
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

    .terms-content h2 {
      color: var(--brand-primary);
      border-bottom: 2px solid var(--brand-primary);
      padding-bottom: 0.5rem;
      margin-top: 2rem;
      margin-bottom: 1rem;
    }

    .terms-content h3 {
      color: var(--text-primary);
      margin-top: 1.5rem;
      margin-bottom: 0.5rem;
      font-size: 1.125rem;
      font-weight: 600;
    }

    .terms-content p {
      margin-bottom: 1rem;
      line-height: 1.7;
    }

    .terms-content ol {
      margin-left: 1.5rem;
      margin-bottom: 1rem;
    }

    .terms-content li {
      margin-bottom: 0.5rem;
      line-height: 1.6;
    }

    .terms-content .article-number {
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
              <li class="flex items-center"><span class="text-gray-700 font-medium">利用規約</span></li>
            </ol>
          </nav>
          <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-4">利用規約</h1>
          <p class="mt-2 text-slate-600">海外リゾキャバ求人.COMのサービス利用に関するルールと条件をご確認ください。</p>
        </div>
      </div>

      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="bg-white border border-[var(--border-color)] p-8 sm:p-12">
          <div class="terms-content prose prose-slate max-w-none">
            <p class="text-lg font-semibold text-center mb-8 text-[var(--brand-primary)]">
              海外リゾキャバ求人.COM 利用規約
            </p>

            <p class="mb-6">
              本利用規約（以下、「本規約」といいます。）は、海外リゾキャバ求人.COM（以下、「当サイト」といいます。）がこのウェブサイト上で提供する求人情報掲載サービス（以下、「本サービス」といいます。）の利用条件を定めるものです。
            </p>

            <p class="mb-6">
              サイトを利用される皆様（以下、「ユーザー」といいます。）および当サイトに求人情報を掲載する店舗様（以下、「店舗」といいます。）には、本規約に従って本サービスをご利用いただきます。
            </p>

            <h3 class="article-number">第1条（適用）</h3>
            <p>
              本規約は、ユーザー及び店舗と当サイトとの間の本サービス利用に関する一切の関係に適用されます。
            </p>
            <p>
              当サイトは本サービス上で別途利用ルールやガイドライン等を定める場合があります。その場合、それらも本規約の一部として構成されます。
            </p>

            <h3 class="article-number">第2条（店舗の掲載登録）</h3>
            <p>
              本サービスに店舗情報や求人情報を掲載しようとする者（以下「掲載希望者」）は、当サイトの定める方法により掲載登録を申請し、当サイトがこれを承認することによって、店舗としての掲載登録が完了します。
            </p>
            <p>
              当サイトは、掲載希望者に以下の事由があると判断した場合、掲載登録の申請を承認しないことがあり、その理由については一切開示義務を負いません。
            </p>
            <ol class="list-decimal">
              <li>掲載申請の内容に虚偽の事項が含まれていた場合</li>
              <li>過去に本規約に違反したことがある者からの申請である場合</li>
              <li>その他、当サイトが掲載登録を不適当と判断した場合</li>
            </ol>
            <p>
              なお、当サイトでは店舗の正式な管理者以外の第三者（仲介業者・ブローカー等）からの掲載申請はお受けしておりません。
            </p>
            <p>
              また、風俗営業や売春に該当するなど公序良俗に反する内容の求人情報は掲載できません。
            </p>
            <p>
              万一、掲載後に上記事項に違反していることが判明した場合、当サイトは当該店舗の掲載情報を削除することができるものとします。
            </p>

            <h3 class="article-number">第3条（禁止事項）</h3>
            <p>
              ユーザーおよび店舗は、本サービスの利用にあたり、以下の行為をしてはなりません。
            </p>
            <ol class="list-decimal">
              <li>法令または公序良俗に違反する行為</li>
              <li>犯罪行為に関連する行為</li>
              <li>当サイトのサーバーまたはネットワークの機能を破壊したり、妨害したりする行為</li>
              <li>本サービスの運営を妨害するおそれのある行為</li>
              <li>他のユーザーや第三者に関する個人情報等を無断で収集または蓄積する行為</li>
              <li>他のユーザーになりすます行為</li>
              <li>本サービスを利用して反社会的勢力に直接または間接に利益を提供する行為</li>
              <li>その他、当サイトが不適切と判断する行為</li>
              <li>当サイト内のコンテンツ（文章・画像・動画等）を無断で転載・複写・転送する行為</li>
              <li>他者の著作権、肖像権、商標権など知的財産権を侵害する行為、または侵害するおそれのある行為</li>
            </ol>

            <h3 class="article-number">第4条（本サービスの提供の停止等）</h3>
            <p>
              当サイトは、以下のいずれかの事由があると判断した場合、ユーザーおよび店舗に事前に通知することなく、本サービスの全部または一部の提供を一時的に停止または中断することができます。
            </p>
            <ol class="list-decimal">
              <li>本サービスにかかるコンピュータシステムの保守、点検や更新を行う場合</li>
              <li>地震、落雷、火災、停電、天災地変などの不可抗力により、本サービスの提供が困難となった場合</li>
              <li>コンピュータや通信回線等が事故により停止した場合</li>
              <li>その他、当サイトが本サービスの提供を困難と判断した場合</li>
            </ol>
            <p>
              当サイトは、本サービスの提供の停止または中断により、ユーザーまたは店舗もしくは第三者が被ったいかなる不利益または損害についても、一切の責任を負わないものとします。
            </p>

            <h3 class="article-number">第5条（利用制限および登録抹消）</h3>
            <p>
              当サイトは、ユーザーまたは店舗が以下のいずれかに該当すると判断した場合には、事前の通知なく当該ユーザーの本サービス利用を一時的に停止し、または当該店舗の掲載登録を抹消することができるものとします。
            </p>
            <ol class="list-decimal">
              <li>本規約のいずれかの条項に違反した場合</li>
              <li>登録内容に虚偽の事実が判明した場合</li>
              <li>その他、当サイトが本サービスの利用を適当でないと判断した場合</li>
            </ol>
            <p>
              当サイトは、本条に基づき当サイトが行った措置によりユーザーまたは店舗に生じた損害について、一切の責任を負いません。
            </p>

            <h3 class="article-number">第6条（免責事項）</h3>
            <ol class="list-decimal">
              <li>当サイトは、本サービスに事実上または法律上の瑕疵（安全性・信頼性・正確性・完全性・有効性・特定目的への適合性・セキュリティ等の欠陥やエラー・バグ・権利侵害などを含みます。）がないことを保証しておりません。</li>
              <li>当サイトは、本サービスに起因してユーザーまたは店舗に生じたあらゆる損害について、一切の責任を負いません。ただし、消費者契約法に基づく場合を除きます。</li>
              <li>前項ただし書に定める場合であっても、当サイトの過失（重過失を除きます。）による損害賠償の責任は、当サイトが当該損害発生月に受領した利用料金額を上限とします（無料サービスの場合は0円）。</li>
              <li>当サイトは、本サービスに関連してユーザー間、ユーザーと店舗間、またはユーザー・店舗と第三者との間で生じた一切の取引、連絡、紛争等について、一切の責任を負いません。</li>
              <li>労働条件や就労ビザ等に関する法規制は国や地域によって異なります。求人内容の詳細については各店舗に直接ご確認ください。雇用条件やビザに関して生じたトラブルについて、当サイトは一切責任を負いかねます。</li>
              <li>当サイトは、求人募集の広告媒体を提供するものであり、いかなる雇用の斡旋・仲介行為も行いません。当サイト上で提供される求人情報や応募機能は、あくまでユーザーと店舗とのマッチングを支援するためのものであり、採用の可否や労働条件等について当サイトが保証するものではありません。</li>
            </ol>

            <h3 class="article-number">第7条（サービス内容の変更・終了）</h3>
            <p>
              当サイトは、ユーザーおよび店舗に通知することなく、本サービスの内容を変更し、または提供を終了することができるものとします。
            </p>
            <p>
              これによりユーザーまたは店舗に損害が生じた場合でも、当サイトは一切の責任を負いません。
            </p>

            <h3 class="article-number">第8条（利用規約の変更）</h3>
            <p>
              当サイトは、当サイトが必要と判断した場合には、ユーザーおよび店舗に通知することなく、いつでも本規約を変更できるものとします。
            </p>
            <p>
              変更後の本規約は、当サイトが別途定める場合を除き、当サイト上に表示した時点から効力を生じるものとします。
            </p>

            <h3 class="article-number">第9条（通知または連絡）</h3>
            <p>
              ユーザーや店舗から当サイトへのお問い合わせ、その他ユーザー・店舗への通知は、当サイトの定める方法（当サイト上の掲示またはユーザーが登録した連絡先への電子メール送信等）によって行うものとします。
            </p>
            <p>
              当サイトは、提出された連絡先情報が不正確であったことによりユーザーまたは店舗が通知を受け取れなかった場合でも、一切の責任を負いません。
            </p>

            <h3 class="article-number">第10条（権利義務の譲渡禁止）</h3>
            <p>
              ユーザーおよび店舗は、当サイトの書面による事前の承諾なく、本サービス利用契約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡もしくは承継させ、または担保提供することはできません。
            </p>

            <h3 class="article-number">第11条（準拠法・裁判管轄）</h3>
            <p>
              本規約の解釈および本サービスに関して生じる一切の紛争については、日本法を準拠法とします。
            </p>
            <p>
              また、本サービスに関して紛争が生じた場合、当サイトの所在地を管轄する日本の裁判所を第一審の専属的合意管轄裁判所とします。
            </p>
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
