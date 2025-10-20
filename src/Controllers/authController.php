<?php

namespace App\Controllers;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $AuthRepository;
    private $UserRepository;

    public function __construct()
    {
        $this->AuthRepository = new AuthRepository();
        $this->UserRepository = new UserRepository();
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

    // public function login()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         header("Location: /libsys/public/login");
    //         exit;
    //     }

    //     // CSRF Check
    //     if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    //         die('Invalid CSRF token.');
    //     }
    //     session_regenerate_id(true);

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    //         $password   = $_POST['password'] ?? '';

    //         if (empty($username) || empty($password)) {
    //             echo "Username and password are required.";
    //             return;
    //         }

    //         $user = $this->AuthRepository->attemptLogin($username, $password);

    //         if ($user && isset($user['is_active']) && !$user['is_active']) {
    //             echo "Your account has been deactivated by the administrator.";
    //             return;
    //         }

    //         if ($user) {
    //             // redirect based on role
    //             if (User::isAdmin($user)) {
    //                 header("Location: /libsys/public/admin/dashboard");
    //             } elseif (User::isLibrarian($user)) {
    //                 header("Location: /libsys/public/librarian/dashboard");
    //             } elseif (User::isStudent($user)) {
    //                 header("Location: /libsys/public/student/dashboard");
    //             } elseif (User::isSuperadmin($user)) {
    //                 header("Location: /libsys/public/superadmin/dashboard");
    //             } elseif (User::isScanner($user)) {
    //                 header("Location: /libsys/public/scanner/attendance");
    //             } else {
    //                 http_response_code(404);
    //                 $this->view("errors/404");
    //             }
    //             exit;
    //         } else {
    //             echo "Invalid login credentials.";
    //         }
    //     }
    // }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
            return;
        }

        header('Content-Type: application/json');

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid CSRF token.'
            ]);
            return;
        }

        session_regenerate_id(true);

        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Username and password are required.'
            ]);
            return;
        }

        $user = $this->AuthRepository->attemptLogin($username, $password);

        if ($user && isset($user['is_active']) && !$user['is_active']) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Your account has been deactivated by the administrator.'
            ]);
            return;
        }

        if ($user) {
            if (User::isAdmin($user)) {
                $redirect = '/libsys/public/admin/dashboard';
            } elseif (User::isLibrarian($user)) {
                $redirect = '/libsys/public/librarian/dashboard';
            } elseif (User::isStudent($user)) {
                $redirect = '/libsys/public/student/dashboard';
            } elseif (User::isSuperadmin($user)) {
                $redirect = '/libsys/public/superadmin/dashboard';
            } elseif (User::isScanner($user)) {
                $redirect = '/libsys/public/scanner/attendance';
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Role not recognized.'
                ]);
                return;
            }

            echo json_encode([
                'status' => 'success',
                'redirect' => $redirect
            ]);
            return;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid username or password.'
            ]);
            return;
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

    public function changePassword()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'You must be logged in to change your password.'
            ]);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'All password fields are required.'
            ]);
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo json_encode([
                'status' => 'error',
                'message' => 'New passwords do not match.'
            ]);
            exit;
        }

        // get user record
        $user = $this->UserRepository->getUserById($userId);

        if (!$user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
            exit;
        }

        // fetch the full user record (para may password hash)
        $userRepo = new UserRepository();
        $userData = $userRepo->findByIdentifier($user['username']);

        if (!$userData || !password_verify($currentPassword, $userData['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Current password is incorrect.'
            ]);
            exit;
        }

        // update password 
        $changed = $this->AuthRepository->changePassword($userId, $newPassword);

        if ($changed) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Your password has been successfully updated.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong while changing your password.'
            ]);
        }
    }
}
