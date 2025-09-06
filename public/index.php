<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\UserController;

$controller = new UserController();

$url = $_GET['url'] ?? 'login';

switch ($url) {
    case 'login':
        $controller->showLogin();
        break;
    case 'login_post':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        http_response_code(404);
        echo "404 Page Not Found";
}
