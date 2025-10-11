<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;

class UserManagementController extends Controller
{
  private UserRepository $userRepo;
  private StudentRepository $studentRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
    $this->studentRepo = new StudentRepository();
  }

  public function index()
  {
    $this->view('superadmin/userManagement', [
      'title' => 'User Management',
    ]);
  }
  public function getAll()
  {
    header('Content-Type: application/json');

    try {
      $users = $this->userRepo->getAllUsers();

      echo json_encode([
        'success' => true,
        'users' => $users
      ]);
    } catch (\Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
    exit;
  }

  public function getUserById($id)
  {
    $user = $this->userRepo->getUserById($id);
    if (!$user) {
      http_response_code(404);
      echo json_encode(['error' => 'User not found']);
      return;
    }

    echo json_encode($user);
  }

  public function search()
  {
    header('Content-Type: application/json');
    $query = $_GET['q'] ?? '';
    error_log("Search query: " . $query);

    try {
      if (empty($query)) {
        $users = $this->userRepo->getAllUsers();
      } else {
        $users = $this->userRepo->searchUsers($query);
      }
      echo json_encode([
        'success' => true,
        'users' => $users
      ]);
    } catch (\Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function addUser()
  {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    $full_name = trim($data['full_name'] ?? '');
    $username  = trim($data['username'] ?? '');
    $role      = trim($data['role'] ?? '');

    if (!$full_name || !$username || !$role) {
      echo json_encode(['success' => false, 'message' => 'All fields are required.']);
      return;
    }

    $defaultPassword = '12345';
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    try {
      $userId = $this->userRepo->insertUser([
        'username'   => $username,
        'password'   => $hashedPassword,
        'full_name'  => $full_name,
        'email'      => null,
        'role'       => $role,
        'is_active'  => 1,
        'created_at' => date('Y-m-d H:i:s')
      ]);

      if (strtolower($role) === 'student') {
        $this->studentRepo->insertStudent(
          $userId,
          $username,
          'BSCS',
          3,
          'enrolled'
        );
      }

      echo json_encode(['success' => true, 'message' => 'User added successfully.']);
    } catch (\Exception $e) {
      echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
  }

  public function deleteUser($id)
  {
    header('Content-Type: application/json');

    try {
      $deletedBy = $_SESSION['user_id'] ?? null;
      if (!$deletedBy) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        return;
      }

      $userRepo = new UserRepository();
      $studentRepo = new StudentRepository();

      $deleted = $userRepo->deleteUserWithCascade((int)$id, $deletedBy, $studentRepo);

      echo json_encode([
        'success' => $deleted,
        'message' => $deleted ? 'User deleted successfully.' : 'Failed to delete user.'
      ]);
    } catch (\Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
      ]);
    }
  }
}
