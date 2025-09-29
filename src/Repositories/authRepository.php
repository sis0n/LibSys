<?php

namespace App\Repositories;

use App\Repositories\UserRepository;

class authRepository
{
  private $userRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
  }

  public function attemptLogin(string $username, string $password)
  {
    $user = $this->userRepo->findByIdentifier($username);

    if ($user && password_verify($password, $user['password'])) {
      session_regenerate_id(true);

      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'] ?? $user['student_number'];
      $_SESSION['role'] = $user['role'];
      $_SESSION['fullname'] = $user['full_name'];

      return $user;
    }
    return null;
  }

  public function logout()
  {
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
  }
}
