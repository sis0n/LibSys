<?php
// session_start();

// require __DIR__ . '/../vendor/autoload.php';

// use App\Config\RouteConfig;

// $router = RouteConfig::register();

// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// $basePath = '/libsys/public/';
// $uri = substr($uri, strlen($basePath));

// $uri = $uri === '' ? 'landingPage' : $uri;

// $method = $_SERVER['REQUEST_METHOD'];

// $router->resolve($uri, $method);

session_start();

// pang load lang ng lahat ng classes
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Config\RouteConfig;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// =======================================================
// CRITICAL ADDITION: Define BASE_URL for all Views and Scripts
// =======================================================
if (!defined('BASE_URL')) {
    // Kinukuha natin ang buong URL: http://localhost/libsys/public
    define('BASE_URL', rtrim($_ENV['APP_URL'], '/')); 
}
// =======================================================

// pangload lang ng routes - check mo na lang sa routeconfig.php
$router = RouteConfig::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// pangdefine lang ng base path to
$basePath = parse_url($_ENV['APP_URL'], PHP_URL_PATH) . '/';
$basePath = parse_url($_ENV['APP_URL'], PHP_URL_PATH) . '/';
$uri = substr($uri, strlen($basePath));

// default route
$uri = $uri === '' ? 'landingPage' : $uri;

// http method
$method = $_SERVER['REQUEST_METHOD'];

// resolve
$router->resolve($uri, $method);
