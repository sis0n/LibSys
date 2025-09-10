<?php
namespace App\Services;

use App\Repositories\UserRepository;

class AuthService {
    private $userRepo;

    public function __construct(){
      $this->userRepo = new UserRepository();
    }

    public function attemptLogin(string $identifier, string $password){
      $user = $this->userRepo->findByIdentifier($identifier);

      if($user && $user['password'] === $password){
          session_regenerate_id(true);

          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'] ?? $user['student_number'];
          $_SESSION['role'] = $user['role'];
          $_SESSION['fullname'] = $user['full_name'];

          return $user;
      }
      return null;
    }

    public function logout(){
      session_unset();
      session_destroy();
    }
}
