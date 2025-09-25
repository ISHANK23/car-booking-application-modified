<?php
declare(strict_types=1);

require_once __DIR__ . '/../inc/connection.inc.php';

function redirectWithAlert(string $message, string $target = '../login.php'): void
{
    $safeMessage = addslashes($message);
    echo "<script>alert('{$safeMessage}');window.location.href='{$target}';</script>";
    exit;
}

if (empty($_GET['state']) || empty($_SESSION['oauth2state']) || !hash_equals($_SESSION['oauth2state'], (string) $_GET['state'])) {
    unset($_SESSION['oauth2state']);
    redirectWithAlert('Invalid OAuth state. Please try again.');
}

unset($_SESSION['oauth2state']);

$code = $_GET['code'] ?? null;
if (!$code) {
    redirectWithAlert('Missing authorization code.');
}

$clientId = getenv('GOOGLE_CLIENT_ID');
$clientSecret = getenv('GOOGLE_CLIENT_SECRET');
if (!$clientId || !$clientSecret) {
    redirectWithAlert('Google OAuth credentials are not configured.');
}

$redirectUri = getenv('GOOGLE_REDIRECT_URI');
if (!$redirectUri) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $redirectUri = $scheme . '://' . $host . '/oauth/google-callback.php';
}

function httpPostJson(string $url, array $body): array
{
    $payload = http_build_query($body, '', '&', PHP_QUERY_RFC3986);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('cURL error: ' . $error);
        }
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'ignore_errors' => true,
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $status = (int) $matches[1];
        }
        if ($response === false) {
            throw new RuntimeException('HTTP request failed.');
        }
    }

    if ($status !== 200) {
        throw new RuntimeException('Unexpected HTTP status ' . $status . ': ' . $response);
    }

    return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
}

function httpGetJson(string $url, string $accessToken): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
        ]);
        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('cURL error: ' . $error);
        }
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $accessToken,
                'ignore_errors' => true,
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
        $status = 0;
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $status = (int) $matches[1];
        }
        if ($response === false) {
            throw new RuntimeException('HTTP request failed.');
        }
    }

    if ($status !== 200) {
        throw new RuntimeException('Unexpected HTTP status ' . $status . ': ' . $response);
    }

    return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
}

try {
    $tokenResponse = httpPostJson('https://oauth2.googleapis.com/token', [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ]);
} catch (Throwable $exception) {
    error_log('OAuth token exchange failed: ' . $exception->getMessage());
    redirectWithAlert('Unable to authenticate with Google.');
}

$accessToken = $tokenResponse['access_token'] ?? null;
if (!$accessToken) {
    redirectWithAlert('Missing access token from Google.');
}

try {
    $userInfo = httpGetJson('https://openidconnect.googleapis.com/v1/userinfo', $accessToken);
} catch (Throwable $exception) {
    error_log('OAuth userinfo fetch failed: ' . $exception->getMessage());
    redirectWithAlert('Unable to fetch Google profile information.');
}

$subject = $userInfo['sub'] ?? null;
$email = $userInfo['email'] ?? null;
$name = trim($userInfo['name'] ?? '');

if (!$subject || !$email) {
    redirectWithAlert('Google account data is incomplete.');
}

$provider = 'google';
$displayName = $name !== '' ? $name : $email;

try {
    $con->begin_transaction();

    $stmt = $con->prepare('SELECT id, username, email FROM users WHERE oauth_provider = ? AND oauth_subject = ? LIMIT 1');
    $stmt->bind_param('ss', $provider, $subject);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $emailStmt = $con->prepare('SELECT id, username, email, oauth_provider FROM users WHERE email = ? LIMIT 1');
        $emailStmt->bind_param('s', $email);
        $emailStmt->execute();
        $existingByEmail = $emailStmt->get_result()->fetch_assoc();

        if ($existingByEmail) {
            $update = $con->prepare('UPDATE users SET oauth_provider = ?, oauth_subject = ? WHERE id = ?');
            $update->bind_param('ssi', $provider, $subject, $existingByEmail['id']);
            $update->execute();
            $userId = (int) $existingByEmail['id'];
            $finalName = $existingByEmail['username'];
        } else {
            $insert = $con->prepare('INSERT INTO users(username, email, phone, password, oauth_provider, oauth_subject) VALUES(?, ?, ?, NULL, ?, ?)');
            $emptyPhone = '';
            $insert->bind_param('sssss', $displayName, $email, $emptyPhone, $provider, $subject);
            $insert->execute();
            $userId = $insert->insert_id;
            $finalName = $displayName;
        }
    } else {
        $userId = (int) $user['id'];
        $finalName = $user['username'];
    }

    $con->commit();
} catch (Throwable $exception) {
    $con->rollback();
    error_log('Failed to persist OAuth user: ' . $exception->getMessage());
    redirectWithAlert('Unable to save Google login.');
}

session_regenerate_id(true);
$_SESSION['id'] = $userId;
$_SESSION['email'] = $email;
$_SESSION['username'] = $email;
$_SESSION['user_display_name'] = $finalName;
$_SESSION['oauth_provider'] = $provider;

echo "<script>window.location.href='../my_account.php';</script>";
exit;
