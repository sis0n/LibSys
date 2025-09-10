<?php
namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Core\Controller;

class UserController extends Controller{
    private $userRepo;

    public function __construct(){
        $this->userRepo = new UserRepository();
    }

    public function index(){
        $users = $this->userRepo->getAllUsers();
        $this->view("users/index", [
            "title" => "User Management",
            "users" => $users
        ]);
    }

    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'], // plain muna for testing
                'full_name' => $_POST['full_name'],
                'role' => $_POST['role']
            ];
            $this->userRepo->createUser($data);
            header("Location: /libsys/public/users");
            exit;
        }

        $this->view("users/create", ["title" => "Add New User"]);
    }
}
