<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Config\Router;

$router = new Router();

/**
 * Auth routes
 */
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->post('logout', 'AuthController@logout');

/**
 * user routes (CRUD, admin) SAMPLE LANG TO
 */
// $router->get('users', 'UserController@index');     //list users
// $router->get('users/create', 'UserController@create');  //create form
// $router->post('users/create', 'UserController@create');  //handle create

/**
 * dashboard routes (role based access)
 */
$router->get('dashboard/superadmin', 'DashboardController@superadmin', ['superadmin']);
$router->get('dashboard/admin', 'DashboardController@admin', ['admin', 'superadmin']);
$router->get('dashboard/librarian', 'DashboardController@librarian', ['librarian', 'admin', 'superadmin']);
$router->get('dashboard/student', 'DashboardController@student', ['student']);

/**
 * resolve URI
 */
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/libsys/public/';
$uri = substr($uri, strlen($basePath));

$uri = $uri === '' ? 'login' : $uri;

$method = $_SERVER['REQUEST_METHOD'];

$router->resolve($uri, $method);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Online Software</title>
    <!-- Tailwind.v4 -->
    <link href="/libsys/public/css/output.css" rel="stylesheet">
    <!-- PHOSPHOR ICONS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" />
</head>
<body>
</body>
</html>
