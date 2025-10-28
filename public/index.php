<?php
session_start();

// pang load lang ng lahat ng classes
require __DIR__ . '/../vendor/autoload.php';

use App\Config\RouteConfig;

if(file_exists(__DIR__ . '/../.env')){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

if(!function_exists('base_url')){
    function base_url($path = ''){
        $base = rtrim($_ENV['APP_URL'] ?? '', '/');    
        return $base . '/' . ltrim($path, '/');
    }
}

// pangload lang ng routes - check mo na lang sa routeconfig.php
$router = RouteConfig::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// pangdefine lang ng base path to
$basePath = '/libsys/public/';
$uri = substr($uri, strlen($basePath));

// default route
$uri = $uri === '' ? 'landingPage' : $uri;

// http method
$method = $_SERVER['REQUEST_METHOD'];

// resolve
$router->resolve($uri, $method);
