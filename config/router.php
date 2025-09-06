<?php
use App\Controllers\UserController;

$routes = [
    "login" => [UserController::class, "showLogin"],
    "login_post" => [UserController::class, "login"],
    "logout" => [UserController::class, "logout"],
];
