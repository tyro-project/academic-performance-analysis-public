<?php
/**
 * bootstrap.php — Academy Backend Foundation
 * Include this at the top of every API endpoint.
 * Usage: require_once __DIR__ . '/../../bootstrap.php';
 */

declare(strict_types=1);

// ── Error Handling ───────────────────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// ── Base Path Constants ──────────────────────────────────
define('ROOT_PATH',    dirname(__DIR__));
define('BACKEND_PATH', __DIR__);
define('UPLOAD_PATH',  ROOT_PATH . '/uploads');
define('LOG_PATH',     ROOT_PATH . '/logs');

// ── Autoloader (Composer) ────────────────────────────────
if (file_exists(BACKEND_PATH . '/vendor/autoload.php')) {
    require_once BACKEND_PATH . '/vendor/autoload.php';
}

// ── Environment Config ───────────────────────────────────
define('APP_ENV',  getenv('APP_ENV')  ?: 'production');
define('BASE_URL', getenv('APP_URL')  ?: 'https://academic.tyro-project.in');
define('API_URL',  getenv('API_URL')  ?: 'https://academic.tyro-project.in/backend');

// ── CORS Headers ─────────────────────────────────────────
header('Access-Control-Allow-Origin: ' . BASE_URL);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ── Database Connection (PDO) ────────────────────────────
function get_db(): PDO {
    static $dbh = null;
    if ($dbh === null) {
        $host = getenv('DB_HOST') ?: 'localhost';
        $name = getenv('DB_NAME') ?: 'mj';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        try {
            $dbh = new PDO(
                "mysql:host={$host};dbname={$name};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            json_error('Database connection failed', 500);
        }
    }
    return $dbh;
}

// ── Session ──────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path'     => '/',
        'secure'   => APP_ENV === 'production',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ── Response Helpers ─────────────────────────────────────
function json_response(mixed $data, string $message = 'Success', int $status = 200): never {
    http_response_code($status);
    echo json_encode([
        'success' => true,
        'message' => $message,
        'data'    => $data,
        'errors'  => [],
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function json_error(string $message, int $status = 400, array $errors = []): never {
    http_response_code($status);
    echo json_encode([
        'success' => false,
        'message' => $message,
        'data'    => null,
        'errors'  => $errors,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// ── Auth Guards ──────────────────────────────────────────
function require_auth(array $roles = []): void {
    if (empty($_SESSION['user'])) {
        json_error('Unauthenticated', 401);
    }
    if (!empty($roles) && !in_array($_SESSION['user']['role'], $roles, true)) {
        json_error('Unauthorized', 403);
    }
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function set_session(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = $user;
}

function clear_session(): void {
    $_SESSION = [];
    session_destroy();
}

// ── Request Helpers ──────────────────────────────────────
function get_json_body(): array {
    $body = file_get_contents('php://input');
    return json_decode($body, true) ?? [];
}

function method_required(string $method): void {
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        json_error('Method not allowed', 405);
    }
}
