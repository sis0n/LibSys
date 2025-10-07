<?php

namespace App\Controllers;

use App\Repositories\AuthRepository;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $AuthRepository;

    public function __construct()
    {
        $this->AuthRepository = new AuthRepository();
    }

    public function showLogin()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view("auth/login", [
            "title" => "Login Page",
            "csrf_token" => $_SESSION['csrf_token']
        ], false);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /libsys/public/login");
            exit;
        }

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die('Invalid CSRF token.');
        }
        session_regenerate_id(true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars(trim($_POST['username'] ?? ''));
            $password   = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                echo "Username and password are required.";
                return;
            }

            $user = $this->AuthRepository->attemptLogin($username, $password);

            if ($user) {
                // redirect based on role
                if (User::isAdmin($user)) {
                    header("Location: /libsys/public/admin/dashboard");
                } elseif (User::isLibrarian($user)) {
                    header("Location: /libsys/public/librarian/dashboard");
                } elseif (User::isStudent($user)) {
                    header("Location: /libsys/public/student/dashboard");
                } elseif (User::isSuperadmin($user)) {
                    header("Location: /libsys/public/superadmin/dashboard");
                } elseif (User::isScanner($user)) {
                    header("Location: /libsys/public/scanner/attendance");
                } else {
                    http_response_code(404);
                    $this->view("errors/404");
                }
                exit;
            } else {
                echo "Invalid login credentials.";
            }
        }
    }

    public function logout()
    {
        session_start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header("Location: /libsys/public/login");
    }



    // forgot password

    public function forgotPassword()
    {
        session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view("auth/forgotPassword", [
            "title" => "Forgot Password",
            "csrf_token" => $_SESSION['csrf_token']
        ], false);
    }

    //change password 
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            require __DIR__ . '/../Views/errors/405.php';
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            // require __DIR__ . '/../errors/401.php'; 
            require __DIR__ . '/../Views/errors/401.php';
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // 1. Check match
        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            // require __DIR__ . '/../errors/400.php'; 
            require __DIR__ . '/../Views/errors/400.php';

            return;
        }

        $userRepo = new \App\Repositories\UserRepository();
        $user = $userRepo->findUserById($userId);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            http_response_code(403);
            // require __DIR__ . '/../errors/403.php'; 
            require __DIR__ . '/../Views/errors/403.php';

            return;
        }

        // 2. Hash and update
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $userRepo->updatePassword($userId, $hashedPassword);

        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Password successfully updated!']);
        } else {
            http_response_code(400);
            // require __DIR__ . '/../errors/400.php'; 
            require __DIR__ . '/../Views/errors/400.php';
        }
    }

    // =========================
    // WRAPPER METHODS PER ROLE
    // =========================
    public function studentChangePassword()
    {
        $this->roleCheck('student');
        $this->changePassword();
    }

    public function librarianChangePassword()
    {
        $this->roleCheck('librarian');
        $this->changePassword();
    }

    public function adminChangePassword()
    {
        $this->roleCheck('admin');
        $this->changePassword();
    }

    public function superAdminChangePassword()
    {
        $this->roleCheck('superadmin');
        $this->changePassword();
    }

    // =========================
    // ROLE CHECK HELPER
    // =========================
    private function roleCheck($role)
    {
        $userRole = $_SESSION['role'] ?? null;

        if ($userRole !== $role) {
            http_response_code(403);
            // require __DIR__ . '/../errors/403.php'; 
            require __DIR__ . '/../Views/errors/403.php';

            exit;
        }
    }
}
