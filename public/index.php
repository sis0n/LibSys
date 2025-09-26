<?php
session_start();

// pang load lang ng lahat ng classes
require __DIR__ . '/../vendor/autoload.php';

use App\Config\RouteConfig;

// pangload lang ng routes - check mo na lang sa routeconfig.php
$router = RouteConfig::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// pangdefine lang ng base path to
$basePath = '/libsys/public/';
$uri = substr($uri, strlen($basePath));

// default route
$uri = $uri === '' ? 'login' : $uri;

// http method
$method = $_SERVER['REQUEST_METHOD'];

// resolve
$router->resolve($uri, $method);
