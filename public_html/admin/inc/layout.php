<?php
// 基本レイアウト: 左サイドバー + コンテンツ

$menu = [
    ['label' => 'ダッシュボード', 'href' => '/admin/'] ,
    ['label' => '求人管理', 'href' => '/admin/jobs/'] ,
    ['label' => 'タグ', 'href' => '/admin/tags/'] ,
    ['label' => '応募管理', 'href' => '/admin/applications/'] ,
    ['label' => '店舗', 'href' => '/admin/stores/'] ,
    ['label' => '広告管理', 'href' => '/admin/ads/'] ,
    ['label' => 'FAQ', 'href' => '/admin/faqs/'] ,
    ['label' => 'お知らせ', 'href' => '/admin/notices/'] ,
    ['label' => 'ブログ', 'href' => '/admin/blog/'] ,
    ['label' => 'アセット管理', 'href' => '/admin/assets_manager/'] ,
];

function isActive(string $href): bool {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return rtrim($uri, '/') === rtrim($href, '/');
}

function renderHeader(string $title = '管理画面'): void {
    echo "<!doctype html>\n<html lang=\"ja\">\n<head>\n<meta charset=\"utf-8\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n<title>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</title>\n<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH\" crossorigin=\"anonymous\">\n<link rel=\"stylesheet\" href=\"/admin/assets/admin.css\">\n</head>\n<body>\n";
}

function renderFooter(): void {
    echo "<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz\" crossorigin=\"anonymous\"></script>\n</body></html>";
}

function renderLayout(string $title, callable $contentRenderer): void {
    global $menu;
    renderHeader($title);
    echo "<div class=\"admin-wrap\">\n  <aside class=\"sidebar\">\n    <div class=\"brand\">Admin</div>\n    <nav class=\"nav\">\n";
    foreach ($menu as $item) {
        $activeClass = isActive($item['href']) ? ' active' : '';
        echo "      <a class=\"nav-item$activeClass\" href=\"" . htmlspecialchars($item['href']) . "\">" . htmlspecialchars($item['label']) . "</a>\n";
    }
    echo "    </nav>\n  </aside>\n  <main class=\"content\">\n    <div class=\"container-fluid py-4\">\n";
    $contentRenderer();
    echo "    </div>\n  </main>\n</div>\n";
    renderFooter();
}


