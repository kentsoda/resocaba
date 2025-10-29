<?php
// 基本レイアウト: 左サイドバー + コンテンツ

$menu = [
    ['label' => 'ダッシュボード', 'href' => '/admin/'] ,
    ['label' => '求人管理', 'href' => '/admin/jobs/'] ,
    ['label' => '応募管理', 'href' => '/admin/applications/'] ,
    ['label' => '店舗', 'href' => '/admin/stores/'] ,
    ['label' => 'お知らせ', 'href' => '/admin/notices/'] ,
    ['label' => 'ブログ', 'href' => '/admin/blog/'] ,
    ['label' => 'アセット管理', 'href' => '/admin/assets_manager/'] ,
];

function isActive(string $href): bool {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return rtrim($uri, '/') === rtrim($href, '/');
}

function renderHeader(string $title = '管理画面'): void {
    echo "<!doctype html>\n<html lang=\"ja\">\n<head>\n<meta charset=\"utf-8\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n<title>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</title>\n<link rel=\"stylesheet\" href=\"/admin/assets/admin.css\">\n</head>\n<body>\n";
}

function renderFooter(): void {
    echo "</body></html>";
}

function renderLayout(string $title, callable $contentRenderer): void {
    global $menu;
    renderHeader($title);
    echo "<div class=\"admin-wrap\">\n  <aside class=\"sidebar\">\n    <div class=\"brand\">Admin</div>\n    <nav class=\"nav\">\n";
    foreach ($menu as $item) {
        $activeClass = isActive($item['href']) ? ' active' : '';
        echo "      <a class=\"nav-item$activeClass\" href=\"" . htmlspecialchars($item['href']) . "\">" . htmlspecialchars($item['label']) . "</a>\n";
    }
    echo "    </nav>\n  </aside>\n  <main class=\"content\">\n";
    $contentRenderer();
    echo "  </main>\n</div>\n";
    renderFooter();
}


