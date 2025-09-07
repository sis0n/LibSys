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
            $identifier = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($identifier) || empty($password)) {
                echo "Username and password are required.";
                return;
            }

            $user = $this->userModel->findByIdentifier($identifier);

            if ($user && $user['password'] === $password) {
                session_regenerate_id(true);

                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'] ?? $user['student_number'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['fullname'] = $user['full_name'];

                // Instead of header file path → route na lang
                if (User::isAdmin($user)) {
                    header("Location: /libsys/public/index.php?url=dashboard/admin");
                } elseif (User::isLibrarian($user)) {
                    header("Location: /libsys/public/index.php?url=dashboard/librarian");
                } elseif (User::isStudent($user)) {
                    header("Location: /libsys/public/index.php?url=dashboard/student");
                } elseif (User::isSuperadmin($user)) {
                    header("Location: /libsys/public/index.php?url=dashboard/superadmin");
                } else {
                    http_response_code(404);
                    include __DIR__ . '/../Views/errors/404.php';
                }
                exit;
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /libsys/public/index.php?url=login");
        exit;
    }
}
