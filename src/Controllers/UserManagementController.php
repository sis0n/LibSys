<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserPermissionModuleRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\StaffRepository;

class UserManagementController extends Controller
{
  private UserRepository $userRepo;
  private StudentRepository $studentRepo;
  private UserPermissionModuleRepository $userPermissionRepo;
  private FacultyRepository $facultyRepo;
  private StaffRepository $staffRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
    $this->studentRepo = new StudentRepository();
    $this->userPermissionRepo = new UserPermissionModuleRepository();
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
      echo json_encode(['success' => true, 'users' => $users]);
    } catch (\Exception $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
  }

  public function getUserById($id)
  {
    header('Content-Type: application/json');
    $user = $this->userRepo->getUserById($id);
    if (!$user) {
      http_response_code(404);
      echo json_encode(['error' => 'User not found']);
      return;
    }

    $modules = [];
    if(in_array(strtolower($user['role']), ['admin', 'librarian'])) {
      $modules = $this->userPermissionRepo->getModulesByUserId((int)$id);
    }
    echo json_encode(['user' => $user, 'modules' => $modules]);
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
      echo json_encode(['success' => true, 'users' => $users]);
    } catch (\Exception $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  public function addUser()
  {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    $first_name = trim($data['first_name'] ?? '');
    $middle_name = trim($data['middle_name'] ?? null);
    $last_name = trim($data['last_name'] ?? '');
    $username = trim($data['username'] ?? '');
    $role = strtolower(trim($data['role'] ?? ''));

    if (!$first_name || !$last_name || !$username || !$role) {
      echo json_encode([
        'success' => false,
        'message' => 'First Name, Last Name, Username, and Role are required.'
      ]);
      return;
    }

    try {
      if ($role === 'student') {
        $studentNumber = $username;

        if (!$studentNumber) {
          echo json_encode(['success' => false, 'message' => 'Student Number (Username) is required for students.']);
          return;
        }

        if ($this->studentRepo->studentNumberExists($studentNumber)) {
          echo json_encode(['success' => false, 'message' => 'Student Number already exists.']);
          return;
        }
      }

      $defaultPassword = '12345';
      $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

      $userData = [
        'username' => $username,
        'password' => $hashedPassword,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'email' => $data['email'] ?? null,
        'role' => ucfirst($role),
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s')
      ];

      $userId = $this->userRepo->insertUser($userData);

      // roles based
      switch ($role) {
        case 'student':
          $this->studentRepo->insertStudent(
            $userId,
            $username,
            $data['course'] ?? 'N/A',
            $data['year_level'] ?? 1,
            'enrolled'
          );
          break;

        case 'faculty':
          $facultyRepo = new \App\Repositories\FacultyRepository();
          $facultyRepo->insertFaculty(
            $userId,
            $data['department'] ?? 'N/A',
            $data['contact'] ?? 'N/A',
            'active'
          );
          break;

        case 'staff':
          $staffRepo = new \App\Repositories\StaffRepository();
          $staffRepo->insertStaff(
            $userId,
            $data['employee_id'] ?? 'N/A',
            $data['position'] ?? 'N/A',
            $data['contact_number'] ?? 'N/A',
            'active'
          );

          break;
        case 'admin':
        case 'librarian':
          if (empty($data['modules']) || !is_array($data['modules'])) {
            echo json_encode([
              'success' => false,
              'message' => 'Please select at least one module for ' . ucfirst($role) . '.'
            ]);
            return;
          }

          $validModules = [
            'book management',
            'qr scanner',
            'returning',
            'borrowing form',
            'attendance',
            'reports',
            'transaction history',
            'backup',
            'restore books'
          ];
          $modules = array_filter($data['modules'], fn($m) => in_array($m, $validModules));

          $this->userPermissionRepo->assignModules($userId, $modules);
          break;

        default:
          echo json_encode(['success' => false, 'message' => 'Invalid role specified.']);
          return;
      }

      echo json_encode([
        'success' => true,
        'message' => ucfirst($role) . ' user added successfully.',
        'user_id' => $userId
      ]);
    } catch (\Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
      ]);
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

      $deleted = $this->userRepo->deleteUserWithCascade((int)$id, $deletedBy, $this->studentRepo);

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

  public function toggleStatus($id)
  {
    header('Content-Type: application/json');
    try {
      $user = $this->userRepo->getUserById((int)$id);
      if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        return;
      }

      if (strtolower($user['role']) === 'superadmin') {
        echo json_encode(['success' => false, 'message' => 'Superadmin status cannot be changed.']);
        return;
      }

      $this->userRepo->toggleUserStatus((int)$id);

      $updatedUser = $this->userRepo->getUserById((int)$id);
      $newStatus = $updatedUser['is_active'] ? 'Active' : 'Inactive';

      echo json_encode([
        'success' => true,
        'message' => 'User status updated successfully.',
        'newStatus' => $newStatus
      ]);
    } catch (\Exception $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  public function updateUser($id)
  {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data)) {
      echo json_encode(['success' => false, 'message' => 'Incomplete user data provided.']);
      return;
    }

    try {
      if (isset($data['password']) && !empty($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
      }

      unset($data['user_id']);

      $updated = $this->userRepo->updateUser((int)$id, $data);

      if(in_array(strtolower($data['role'] ?? ''), ['admin', 'librarian']) && isset($data['modules'])){
        $this->userPermissionRepo->assignModules((int)$id, $data['modules']);
      }

      if ($updated) {
        echo json_encode([
          'success' => true,
          'message' => 'User updated successfully.'
        ]);
      } else {
        echo json_encode([
          'success' => false,
          'message' => 'Failed to update user or no changes were made.'
        ]);
      }
    } catch (\Exception $e) {
      error_log("[UserManagementController::updateUser] " . $e->getMessage());
      echo json_encode([
        'success' => false,
        'message' => 'An internal server error occurred.'
      ]);
    }
  }

  public function allowEdit($id)
  {
    header('Content-Type: application/json');
    try {
      $studentRepo = new \App\Repositories\StudentProfileRepository();
      $updated = $studentRepo->setEditAccess((int)$id, true);
      echo json_encode([
        'success' => $updated,
        'message' => $updated
          ? 'Student can now edit their profile again.'
          : 'Failed to grant edit access.'
      ]);
    } catch (\Exception $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  public function bulkImport()
  {
    header('Content-Type: application/json');

    $imported = 0;
    $errors = [];

    if (!isset($_FILES['csv_file'])) {
      echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
      exit;
    }

    $file = $_FILES['csv_file']['tmp_name'];

    if (!file_exists($file) || !is_readable($file)) {
      echo json_encode(['success' => false, 'message' => 'Uploaded file not readable.']);
      exit;
    }

    $userRepo = new \App\Repositories\UserRepository();

    if (($handle = fopen($file, 'r')) !== false) {
      $header = fgetcsv($handle);
      $rowNumber = 2;

      while (($row = fgetcsv($handle)) !== false) {
        $firstName = trim($row[0] ?? '');
        $middleName = trim($row[1] ?? '');
        $lastName = trim($row[2] ?? '');
        $username = trim($row[3] ?? '');
        $role = trim($row[4] ?? '');

        if (!$firstName || !$lastName || !$username || !$role) {
          $errors[] = "Row $rowNumber missing required fields";
          $rowNumber++;
          continue;
        }

        $existingUser = $userRepo->findByIdentifier($username);
        if ($existingUser) {
          $errors[] = "Row $rowNumber: Username '$username' already exists";
          $rowNumber++;
          continue;
        }

        try {
          if (strtolower($role) === 'student') {
            $userRepo->insertStudent([
              'first_name' => $firstName,
              'middle_name' => $middleName ?: null,
              'last_name' => $lastName,
              'username' => $username,
              'role' => 'student',
              'password' => password_hash('defaultpassword', PASSWORD_DEFAULT),
              'is_active' => 1
            ]);
          } else {
            $userRepo->insertUser([
              'first_name' => $firstName,
              'middle_name' => $middleName ?: null,
              'last_name' => $lastName,
              'username' => $username,
              'role' => $role,
              'password' => password_hash('defaultpassword', PASSWORD_DEFAULT),
              'is_active' => 1,
              'created_at' => date('Y-m-d H:i:s')
            ]);
          }

          $imported++;
        } catch (\Exception $e) {
          $errors[] = "Row $rowNumber: " . $e->getMessage();
        }

        $rowNumber++;
      }

      fclose($handle);
    }

    echo json_encode([
      'success' => true,
      'imported' => $imported,
      'errors' => $errors
    ]);
  }
}
