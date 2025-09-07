<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Config\Router;

$router = new Router();

$router->get('login', 'UserController@showLogin');
$router->post('login_post', 'UserController@login');
$router->get('logout', 'UserController@logout');

$router->get('dashboard/superadmin', 'DashboardController@superadmin', ['superadmin']);
$router->get('dashboard/admin', 'DashboardController@admin', ['admin', 'superadmin']);
$router->get('dashboard/librarian', 'DashboardController@librarian', ['librarian', 'admin', 'superadmin']);
$router->get('dashboard/student', 'DashboardController@student', ['student']);

$url = $_GET['url'] ?? 'login';
$method = $_SERVER['REQUEST_METHOD'];

$router->resolve($url, $method);
