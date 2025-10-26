<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die("CRITICAL ERROR: Could not find or read .env file from base path. Deployment is broken. " . $e->getMessage());
}

use App\Config\RouteConfig;

// pangload lang ng routes - check mo na lang sa routeconfig.php
$router = RouteConfig::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// pangdefine lang ng base path to
$basePath = '/';
$uri = substr($uri, strlen($basePath));

// default route
$uri = $uri === '' ? 'landingPage' : $uri;

// http method
$method = $_SERVER['REQUEST_METHOD'];

// resolve
$router->resolve($uri, $method);
