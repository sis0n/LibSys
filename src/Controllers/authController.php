<?php
namespace App\Controllers;

use App\Repositories\AuthRepository;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller{
  private $AuthRepository;

  public function __construct(){
    $this->AuthRepository = new AuthRepository();
  }

  public function showLogin(){
        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view("auth/login", [
            "title" => "Login Page",
            "csrf_token" => $_SESSION['csrf_token']
        ], false);
  }

  public function login(){
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header("Location: /libsys/public/login");
        exit;
    }

    // CSRF Check
    if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
        die('Invalid CSRF token.');
    }
    session_regenerate_id(true);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $password   = $_POST['password'] ?? '';

        if(empty($username) || empty($password)){
            echo "Username and password are required.";
            return;
        }

        $user = $this->AuthRepository->attemptLogin($username, $password);

        if($user){
            // redirect based on role
            if(User::isAdmin($user)){
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

  public function logout(){
    session_start();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: /libsys/public/login");
  }



// forgot password

  public function forgotPassword(){
    session_start();
        if(empty($_SESSION['csrf_token'])){
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
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }

    header('Content-Type: application/json'); // JSON response

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
        return;
    }

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // 1. Check match
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'New passwords do not match!']);
        return;
    }

    $userRepo = new \App\Repositories\UserRepository();
    $user = $userRepo->findUserById($userId);

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect!']);
        return;
    }

    // 2. Hash and update
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $userRepo->updatePassword($userId, $hashedPassword);

    if ($update) {
        echo json_encode(['status' => 'success', 'message' => 'Password successfully updated!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }
}


  
}

  
