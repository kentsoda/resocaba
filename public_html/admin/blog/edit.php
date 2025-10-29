<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/editor.php';

renderLayout('ブログ 編集/新規', function () {
    echo '<h1>ブログ 編集/新規（雛形）</h1>';
    echo '<p>将来、本文にWYSIWYG適用予定。</p>';
    echo '<textarea class="js-wysiwyg" rows="8"></textarea>';
    enableWysiwyg('.js-wysiwyg');
});


