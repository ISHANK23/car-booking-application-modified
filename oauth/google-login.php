<?php
declare(strict_types=1);

require_once __DIR__ . '/../inc/security.php';

$clientId = getenv('GOOGLE_CLIENT_ID');
if (!$clientId) {
    exit('Google OAuth client ID is not configured.');
}

$redirectUri = getenv('GOOGLE_REDIRECT_URI');
if (!$redirectUri) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir = str_replace('\\', '/', dirname($scriptName));
    $scriptDir = rtrim($scriptDir, '/');
    if ($scriptDir === '' || $scriptDir === '.' || $scriptDir === '/') {
        $scriptDir = '';
    }
    $redirectUri = $scheme . '://' . $host . $scriptDir . '/google-callback.php';
}

$state = bin2hex(random_bytes(24));
$_SESSION['oauth2state'] = $state;

$params = [
    'response_type' => 'code',
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'scope' => 'openid email profile',
    'state' => $state,
    'prompt' => 'consent',
];

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

header('Location: ' . $authUrl);
exit;
