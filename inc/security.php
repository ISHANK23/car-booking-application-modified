<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $cookieParams = session_get_cookie_params();
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookieParams['path'] ?? '/',
        'domain' => $cookieParams['domain'] ?? '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

/**
 * Generate (or reuse) the CSRF token for the current session.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validate the provided CSRF token against the session token.
 */
function validateCsrfToken(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Escape output for safe HTML rendering.
 */
function escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Detect whether a legacy MD5 password hash is stored.
 */
function isLegacyMd5Hash(?string $hash): bool
{
    return is_string($hash) && preg_match('/^[a-f0-9]{32}$/i', $hash) === 1;
}

/**
 * Build a fully-qualified URL for the current script.
 */
function currentUrl(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = strtok($requestUri, '?') ?: '/';

    return $scheme . '://' . $host . $path;
}
