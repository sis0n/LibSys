<?php

namespace App\Repositories;

use App\Repositories\UserRepository;

class AuthRepository 
{
  private UserRepository $userRepo; 

  public function __construct()
  {
    $this->userRepo = new UserRepository();
  }

  public function attemptLogin(string $username, string $password): ?array 
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $user = $this->userRepo->findByIdentifier($username);

    error_log("[AuthRepository] User found by identifier ('$username'): " . print_r($user, true));

    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
      session_regenerate_id(true);

      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $user['username'] ?? $user['student_number'] ?? $username; 

      $_SESSION['role'] = !empty($user['role']) ? strtolower(trim($user['role'])) : 'guest';

      $firstName = $user['first_name'] ?? '';
      $middleName = $user['middle_name'] ?? '';
      $lastName = $user['last_name'] ?? '';
      $fullName = implode(' ', array_filter([$firstName, $middleName, $lastName]));

      $_SESSION['fullname'] = !empty(trim($fullName)) ? $fullName : ($_SESSION['username'] ?? 'User');

      error_log("[AuthRepository] Session data set: " . print_r($_SESSION, true));


      return $user; 
    }
    error_log("[AuthRepository] Login failed for '$username'. User found: " . ($user ? 'Yes' : 'No') . ", Password verify: " . ($user && isset($user['password']) ? (password_verify($password, $user['password']) ? 'Success' : 'Fail') : 'N/A'));
    return null; 
  }

  public function logout(): void 
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

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
  }

  public function changePassword(int $userId, string $newPassword): bool 
  {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    return $this->userRepo->updatePassword($userId, $hashedPassword);
  }
}
