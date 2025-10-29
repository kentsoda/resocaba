<?php

/**
 * Simple form helpers for admin
 */

function getPostString(string $key, int $maxLength = 10000): string {
    $v = isset($_POST[$key]) ? (string)$_POST[$key] : '';
    if (mb_strlen($v, 'UTF-8') > $maxLength) {
        $v = mb_substr($v, 0, $maxLength, 'UTF-8');
    }
    return $v;
}

function getPostInt(string $key, int $default = 0): int {
    if (!isset($_POST[$key])) {
        return $default;
    }
    $v = filter_var($_POST[$key], FILTER_VALIDATE_INT);
    return $v === false ? $default : (int)$v;
}

function getPostEnum(string $key, array $allowed, string $default = ''): string {
    $v = isset($_POST[$key]) ? (string)$_POST[$key] : $default;
    return in_array($v, $allowed, true) ? $v : $default;
}

function getPostArrayStrings(string $key): array {
    $arr = isset($_POST[$key]) && is_array($_POST[$key]) ? $_POST[$key] : [];
    $out = [];
    foreach ($arr as $v) {
        if (is_string($v) || is_numeric($v)) {
            $out[] = (string)$v;
        }
    }
    return $out;
}

function getPostArrayInt(string $key): array {
    $arr = isset($_POST[$key]) && is_array($_POST[$key]) ? $_POST[$key] : [];
    $out = [];
    foreach ($arr as $v) {
        $iv = filter_var($v, FILTER_VALIDATE_INT);
        if ($iv !== false) {
            $out[] = (int)$iv;
        }
    }
    return $out;
}

function getPostDateString(string $key): string {
    $v = isset($_POST[$key]) ? (string)$_POST[$key] : '';
    // accept YYYY-MM-DD
    if ($v !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
        return $v;
    }
    return '';
}

function sanitizeAllowedHtml(string $html): string {
    // Very small whitelist. Expand as needed.
    // Remove script/style and event handlers
    $html = preg_replace('#<\/(?:script|style)[^>]*>#i', '', $html);
    $html = preg_replace('#<(?:script|style)[^>]*>[\s\S]*?<\/\s*(?:script|style)\s*>#i', '', $html);
    $html = preg_replace('/ on[a-z]+\s*=\s*"[^"]*"/i', '', $html);
    $html = preg_replace("/ on[a-z]+\s*=\s*'[^']*'/i", '', $html);
    $html = preg_replace('/ on[a-z]+\s*=\s*[^\s>]+/i', '', $html);
    // Allow only safe protocols in links
    $html = preg_replace('#href\s*=\s*"javascript:[^"]*"#i', 'href="#"', $html);
    $html = preg_replace("#href\s*=\s*'javascript:[^']*'#i", "href='#'", $html);
    // Optionally further sanitize or use HTML Purifier if available in future
    return $html;
}


