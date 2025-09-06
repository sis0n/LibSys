<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        include __DIR__ . "/../Views/auth/login.php";
    }

    public function login() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = $_POST['username'] ?? null;
            $password   = $_POST['password'] ?? null;

            if (!$identifier || !$password) {
                echo "Username and password are required.";
                return;
            }

            $user = $this->userModel->findByIdentifier($identifier);

            if ($user && $user['password'] === $password) {
                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'] ?? $user['student_number'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['fullname'] = $user['full_name'];

                header("Location: /libsys/src/Views/dashboard/{$user['role']}.php");
                exit;
            } else {
                echo "Invalid username or password.";
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /libsys/public/index.php?url=login");
        exit;
    }
}
