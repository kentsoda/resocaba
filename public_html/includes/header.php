<head>
    <?php
    // Safe defaults for meta values
    $site_name = '海外リゾキャバ求人.COM';
    $meta_title = isset($title) && $title !== '' ? $title : $site_name;
    $meta_description = isset($description) && $description !== ''
        ? $description
        : '国内外のリゾートバイト、ワーキングホリデーの求人情報を網羅。あなたの新しい挑戦を全力でサポートします。';
    $og_title_val = isset($og_title) && $og_title !== '' ? $og_title : $meta_title;
    $og_description_val = isset($og_description) && $og_description !== '' ? $og_description : $meta_description;
    $og_type_val = isset($og_type) && $og_type !== '' ? $og_type : 'website';
    $og_url_val = isset($og_url) && $og_url !== '' ? $og_url : (isset($url) ? $url : '');
    $og_image_val = isset($og_image) && $og_image !== '' ? $og_image : ('https://placehold.co/1200x630/0ABAB5/ffffff?text=' . rawurlencode($site_name));
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="noindex">
    <!-- OGP Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($og_title_val, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($og_description_val, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($og_type_val, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($og_url_val, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image_val, ENT_QUOTES, 'UTF-8') ?>">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Swiper's CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>
<?php require_once __DIR__ . '/../../config/functions.php'; ?>