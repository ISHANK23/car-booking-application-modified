<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';

if (isset($con) && $con instanceof mysqli) {
    return;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'rentcar';

try {
    $con = new mysqli($host, $user, $password, $database);
    $con->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit('Database connection error.');
}
