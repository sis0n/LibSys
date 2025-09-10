<?php
namespace App\Controllers;


use App\Models\User;
use App\Core\Controller;

class UserController extends Controller{
    private $userModel;

    public function __construct(){
        $this->userModel = new User();
    }

    public function showLogin() {
        $this->view("auth/login",[
            "title" => "Login Page"
        ], false);
    }

    public function login(){
        session_start();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['username'] ?? '');
            $password   = $_POST['password'] ?? '';

            if(empty($identifier) || empty($password)){
                echo "Username and password are required.";
                return;
            }

            $user = $this->userModel->findByIdentifier($identifier);

            if($user && $user['password'] === $password){
                session_regenerate_id(true);

                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'] ?? $user['student_number'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['fullname'] = $user['full_name'];

                // Clean URL redirects (wala nang index.php?url=...)
                if(User::isAdmin($user)){
                    header("Location: /libsys/public/dashboard/admin");
                } elseif (User::isLibrarian($user)) {
                    header("Location: /libsys/public/dashboard/librarian");
                } elseif (User::isStudent($user)) {
                    header("Location: /libsys/public/dashboard/student");
                } elseif (User::isSuperadmin($user)) {
                    header("Location: /libsys/public/dashboard/superadmin");
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
        session_unset();
        session_destroy();
        
        header("Location: /libsys/public/login");
        exit;
    }
}
