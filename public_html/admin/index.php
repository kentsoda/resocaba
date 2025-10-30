<?php
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/db.php';

renderLayout('ダッシュボード', function () {
    $pdo = db();
    $totalJobs = fetchCountOrNull($pdo, 'SELECT COUNT(*) FROM jobs');
    $publishedJobs = fetchCountOrNull($pdo, "SELECT COUNT(*) FROM jobs WHERE status = 'published'");
    $draftJobs = fetchCountOrNull($pdo, "SELECT COUNT(*) FROM jobs WHERE status IN ('draft','archived')");
    $weekApplications = fetchCountOrNull($pdo, "SELECT COUNT(*) FROM applications WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");

    echo '<h1>ダッシュボード</h1>';
    echo '<div class="kpi-grid d-flex flex-row gap-3">';
    echo '  <div class="card d-flex flex-column justify-content-center align-items-center px-3 py-2"><span class="text-bold fs-3">総求人件数</span><div class="value">' . htmlspecialchars((string)($totalJobs ?? '-')) . '</div></div>';
    echo '  <div class="card d-flex flex-column justify-content-center align-items-center px-3 py-2"><span class="text-bold fs-3">公開求人</span><div class="value">' . htmlspecialchars((string)($publishedJobs ?? '-')) . '</div></div>';
    echo '  <div class="card d-flex flex-column justify-content-center align-items-center px-3 py-2"><span class="text-bold fs-3">非公開/下書き</span><div class="value">' . htmlspecialchars((string)($draftJobs ?? '-')) . '</div></div>';
    echo '  <div class="card d-flex flex-column justify-content-center align-items-center px-3 py-2"><span class="text-bold fs-3">今週の応募</span><div class="value">' . htmlspecialchars((string)($weekApplications ?? '-')) . '</div></div>';
    echo '</div>';
});


