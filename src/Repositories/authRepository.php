<?php

namespace App\Repositories;

use App\Repositories\UserRepository;
use App\Repositories\UserPermissionModuleRepository;
use App\Repositories\CollegeCourseRepository;

class AuthRepository
{
  private UserRepository $userRepo;
  private UserPermissionModuleRepository $userModuleRepo;
  private CollegeCourseRepository $collegeCourseRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
    $this->userModuleRepo = new UserPermissionModuleRepository();
    $this->collegeCourseRepo = new CollegeCourseRepository();
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

      $modules = [];
      $role = strtolower(trim($user['role'] ?? 'guest'));

      $departmentOrCourse = null;

      if ($role === 'student' && isset($user['course_id'])) {
        $course = $this->collegeCourseRepo->getCourseById((int)$user['course_id']);
        $departmentOrCourse = $course['course_code'] ?? 'N/A';
      } elseif (in_array($role, ['faculty', 'staff']) && isset($user['college_id'])) {
        $college = $this->collegeCourseRepo->getCollegeById((int)$user['college_id']);
        $departmentOrCourse = $college['college_code'] ?? 'N/A';
      }

      if (in_array($role, ['admin', 'librarian', 'superadmin'])) {
        $modules = $this->userModuleRepo->getModulesByUserId($user['user_id']);
      }

      $_SESSION['user_data'] = [
        'user_id' => $user['user_id'],
        'student_id' => $user['student_id'] ?? null,
        'faculty_id' => $user['faculty_id'] ?? null,
        'staff_id' => $user['staff_id'] ?? null,

        'program_department' => $departmentOrCourse,

        'username' => $user['username'] ?? $user['student_number'] ?? $username,
        'role' => !empty($user['role']) ? strtolower(trim($user['role'])) : 'guest',
        'fullname' => !empty(trim($fullName)) ? $fullName : ($_SESSION['username'] ?? 'User'),
        'profile_picture' => $user['profile_picture'] ?? null,
        'is_active' => $user['is_active'] ?? 0,
        'modules' => $modules,
      ];

      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['username'] = $_SESSION['user_data']['username'];
      $_SESSION['role'] = $_SESSION['user_data']['role'];
      $_SESSION['user_permissions'] = $modules;

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
