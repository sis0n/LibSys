<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ==========================================================
// ✅ FRONT CONTROLLER (Unified for Local + Production)
// ==========================================================

// [1] Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// [2] Define ROOT_PATH
define('ROOT_PATH', dirname(__DIR__));

// [3] Load Composer Autoloader
require ROOT_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Config\RouteConfig;

// [4] Load .env
date_default_timezone_set('Asia/Manila');
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// [5] Define BASE_URL (from .env)
if (!defined('BASE_URL')) {
    $appUrl = $_ENV['APP_URL'] ?? 'http://localhost';
    define('BASE_URL', rtrim($appUrl, '/'));
}

// ==========================================================
// ✅ ROUTE CALCULATION
// ==========================================================

// Full URI from server (e.g. /libsys/public/api/superadmin/dashboard/getData)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Base path from APP_URL (e.g. /libsys/public)
$baseUrlPath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
$basePathToRemove = rtrim($baseUrlPath, '/') . '/';

// Remove the base path from URI *safely*
if ($basePathToRemove !== '/' && str_starts_with($uri, $basePathToRemove)) {
    $route = substr($uri, strlen($basePathToRemove));
} else {
    $route = ltrim($uri, '/');
}

// Normalize route (default: dashboard)
$route = trim($route, '/');
$route = $route === '' ? '' : $route;


// ==========================================================
// ✅ DEBUG MODE (Optional)
// Uncomment if you need to see what's happening
// echo "<pre>BASE_URL: " . BASE_URL . "\nURI: " . $uri . "\nBASE_PATH_TO_REMOVE: " . $basePathToRemove . "\nFINAL ROUTE: " . $route . "</pre>"; exit;
// ==========================================================

// [6] Resolve Route
$method = $_SERVER['REQUEST_METHOD'];
$router = RouteConfig::register();
$router->resolve($route, $method);
