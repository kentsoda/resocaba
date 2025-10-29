<?php

/**
 * CSRF token utilities for admin forms
 */

function ensureSessionStarted(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
}

function csrf_token(): string {
    ensureSessionStarted();
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): void {
    echo '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function isValidCsrfFromPost(): bool {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return false;
    }
    ensureSessionStarted();
    $token = isset($_POST['_csrf']) && is_string($_POST['_csrf']) ? $_POST['_csrf'] : '';
    $sessionToken = isset($_SESSION['csrf_token']) && is_string($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';
    if ($token === '' || $sessionToken === '') {
        return false;
    }
    return hash_equals($sessionToken, $token);
}

function requireValidCsrfOrAbort(): void {
    if (!isValidCsrfFromPost()) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=UTF-8');
        echo 'Bad Request';
        exit;
    }
}


