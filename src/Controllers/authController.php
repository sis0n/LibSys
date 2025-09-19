<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller{
  private $authService;

  public function __construct(){
    $this->authService = new AuthService();
  }

  public function showLogin(){
    session_start();
        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view("auth/login", [
            "title" => "Login Page",
            "csrf_token" => $_SESSION['csrf_token']
        ], false);
  }

  public function login(){
    session_start();
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
        $identifier = htmlspecialchars(trim($_POST['username'] ?? ''));
        $password   = $_POST['password'] ?? '';

        if(empty($identifier) || empty($password)){
            echo "Username and password are required.";
            return;
        }

        $user = $this->authService->attemptLogin($identifier, $password);

        if($user){
            // redirect based on role
            if(User::isAdmin($user)){
              header("Location: /libsys/public/admin/dashboard");
            } elseif (User::isLibrarian($user)) {
                header("Location: /libsys/public/librarian/dashboard");
            } elseif (User::isStudent($user)) {
                header("Location: /libsys/public/student/dashboard");
                // main landing page after mag login ni user
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
}
