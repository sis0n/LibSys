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
    $this->view("auth/login", [
        "title" => "Login Page"
    ], false);
  }

  public function login(){
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $identifier = trim($_POST['username'] ?? '');
        $password   = $_POST['password'] ?? '';

        if(empty($identifier) || empty($password)){
            echo "Username and password are required.";
            return;
        }

        $user = $this->authService->attemptLogin($identifier, $password);

        if($user){
            // redirect based on role
            if(User::isAdmin($user)){
              header("Location: /libsys/public/Admin/dashboard");
            } elseif (User::isLibrarian($user)) {
                header("Location: /libsys/public/Librarian/dashboard");
            } elseif (User::isStudent($user)) {
                header("Location: /libsys/public/Student/dashboard");
                // main landing page after mag login ni user
            } elseif (User::isSuperadmin($user)) {
                header("Location: /libsys/public/SuperAdmin/dashboard");
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
    $this->authService->logout();
    header("Location: /libsys/public/login");
    exit;
  }
}
