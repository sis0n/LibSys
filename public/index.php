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

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Config\RouteConfig;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim($_ENV['APP_URL'], '/')); 
}

$router = RouteConfig::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = parse_url($_ENV['APP_URL'], PHP_URL_PATH) . '/';
$uri = substr($uri, strlen($basePath));

// default route
$uri = $uri === '' ? 'landingPage' : $uri;

// http method
$method = $_SERVER['REQUEST_METHOD'];

// resolve
$router->resolve($uri, $method);


