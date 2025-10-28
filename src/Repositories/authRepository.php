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

    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
      session_regenerate_id(true);

      $firstName = $user['first_name'] ?? '';
      $middleName = $user['middle_name'] ?? '';
      $lastName = $user['last_name'] ?? '';
      $suffix = $user['suffix'] ?? '';
      $fullName = implode(' ', array_filter([$firstName, $middleName, $lastName, $suffix]));

      $_SESSION['user_data'] = [
        'user_id' => $user['user_id'],
        'student_id' => $user['student_id'] ?? null,
        'faculty_id' => $user['faculty_id'] ?? null,
        'department' => $user['department'] ?? null,
        'username' => $user['username'] ?? $user['student_number'] ?? $username,
        'role' => !empty($user['role']) ? strtolower(trim($user['role'])) : 'guest',
        'fullname' => !empty(trim($fullName)) ? $fullName : ($_SESSION['username'] ?? 'User'),
        'profile_picture' => $user['profile_picture'] ?? null,
        'is_active' => $user['is_active'] ?? 0,
      ];

      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $_SESSION['user_data']['username'];
      $_SESSION['role'] = $_SESSION['user_data']['role'];

      return $user;
    }
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
