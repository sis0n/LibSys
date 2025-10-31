<?php

namespace App\Controllers;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserPermissionModuleRepository;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $AuthRepository;
    private $UserRepository;
    private $UserPermissionRepo;

    public function __construct()
    {
        $this->AuthRepository = new AuthRepository();
        $this->UserRepository = new UserRepository();
        $this->UserPermissionRepo = new UserPermissionModuleRepository();
    }

    public function showLogin()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        // === [BINAGO DITO] ===
        // Imbes na mag-check ng roles, i-redirect na lang lahat sa generic dashboard.
        // Ang ViewController@handleDashboard na ang bahala mag-sort kung saan sila dapat mapunta.
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            header("Location: " . BASE_URL . "/dashboard"); // Palaging generic dashboard
            exit;
        }
        // === [WAKAS NG PAGBABAGO] ===


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
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
            return;
        }

        header('Content-Type: application/json');

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
            // === [BINAGO DITO] ===
            // Imbes na mag-switch case o tumawag ng getFirstAccessibleModuleUrl,
            // ang redirect ay palaging sa generic dashboard.

            $redirect = BASE_URL . '/dashboard';

            // === [WAKAS NG PAGBABAGO] ===

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
        $this->AuthRepository->logout();

        // === [BINAGO DITO] ===
        // Gumamit ng BASE_URL para sa tamang redirect path
        header("Location: " . BASE_URL . "/login");
    }

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

        $user = $this->UserRepository->getUserById($userId);

        if (!$user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
            exit;
        }

        $userRepo = new UserRepository();
        $userData = $userRepo->findByIdentifier($user['username']);

        if (!$userData || !password_verify($currentPassword, $userData['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Current password is incorrect.'
            ]);
            exit;
        }

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
