<?php

function renderTinyMceLoaderOnce(): void {
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;
    // TinyMCE CDN (no API key variant)
    echo '<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>' . "\n";
}

function enableWysiwyg(string $selector = '.js-wysiwyg'): void {
    renderTinyMceLoaderOnce();
    $config = [
        'selector' => $selector,
        'plugins' => 'lists link',
        'toolbar' => 'undo redo | styles | bold italic underline | bullist numlist | link | removeformat',
        'menubar' => false,
        'branding' => false,
        'height' => 280,
        'paste_as_text' => true,
        'image_advtab' => false,
        'automatic_uploads' => false,
        'file_picker_types' => '',
        'image_dimensions' => false,
        'images_upload_handler' => 'function (blobInfo, success, failure) { failure("Image upload disabled"); }',
        'content_style' => 'body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Noto Sans JP, Arial, sans-serif; font-size:14px }',
    ];

    // Output inline JS init
    echo "<script>\n";
    echo "(function(){\n";
    echo "  if (!window.tinymce) return;\n";
    // Build JS config
    $json = json_encode($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "  tinymce.init(" . $json . ");\n";
    echo "})();\n";
    echo "</script>\n";
}


