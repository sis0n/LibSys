<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserRepository;

class UserManagementController extends Controller
{
  private UserRepository $userRepo;

  public function __construct()
  {
    $this->userRepo = new UserRepository();
  }

  public function index()
  {
    // render the main user management page (HTML view)
    $this->view('superadmin/userManagement', [
      'title' => 'User Management'
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
}
