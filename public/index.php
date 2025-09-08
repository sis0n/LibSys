<?php
    require __DIR__ . '/../vendor/autoload.php';

    use App\Config\Router;

    $router = new Router();

    $router->get('login', 'UserController@showLogin');
    $router->post('login', 'UserController@login');
    $router->post('logout', 'UserController@logout');

    $router->get('dashboard/superadmin', 'DashboardController@superadmin', ['superadmin']);
    $router->get('dashboard/admin', 'DashboardController@admin', ['admin', 'superadmin']);
    $router->get('dashboard/librarian', 'DashboardController@librarian', ['librarian', 'admin', 'superadmin']);
    $router->get('dashboard/student', 'DashboardController@student', ['student']);

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
    <link href="/LibSys/public/css/output.css" rel="stylesheet">
    <!-- PHOSPHOR ICONS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" />
</head>
<body>
</body>
</html>