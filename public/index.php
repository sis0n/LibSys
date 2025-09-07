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
            include __DIR__ . '/../src/Views/errors/404.php';
            break;
    }
    ?>

</body>

</html>