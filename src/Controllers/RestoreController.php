<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\RestoreRepository;

class RestoreController extends Controller
{
  protected RestoreRepository $restoreRepo;

  public function __construct()
  {
    $this->restoreRepo = new RestoreRepository();
  }

  private function validateCsrf(string $token): bool
  {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
  }

  public function index()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    $viewData = [
      "title" => "Restore User",
      "currentPage" => "restoreUser",
      'csrf_token' => $_SESSION['csrf_token']
    ];
    $this->view('superadmin/restoreUser', $viewData);
  }

  public function getDeletedUsersJson()
  {
    header('Content-Type: application/json');
    try {
      $users = $this->restoreRepo->getDeletedUsers();
      echo json_encode(['success' => true, 'users' => $users]);
    } catch (\Throwable $e) {
      error_log("Error in getDeletedUsersJson: " . $e->getMessage());
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'Failed to fetch deleted users.']);
    }
  }

  public function restore()
  {
    header('Content-Type: application/json');
    if (session_status() === PHP_SESSION_NONE) session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
      return;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $userId = $input['user_id'] ?? null;
    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($input['csrf_token'] ?? '');

    if (!$this->validateCsrf($csrfToken)) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'CSRF token validation failed.']);
      return;
    }

    if (!$userId) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'User ID is required.']);
      return;
    }

    try {
      $restored = $this->restoreRepo->restoreUser((int)$userId);
      if ($restored) {
        echo json_encode(['success' => true, 'message' => 'User restored successfully.']);
      } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Failed to restore user. User might not exist or already restored.']);
      }
    } catch (\Throwable $e) {
      error_log("Error restoring user $userId: " . $e->getMessage());
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'An internal error occurred during restoration.']);
    }
  }

  public function archive($id)
  {
    header('Content-Type: application/json');
    $userId = filter_var($id, FILTER_VALIDATE_INT);
    $librarianId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null; 

    if (!$this->validateCsrf($csrfToken)) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'CSRF token validation failed.']);
      return;
    }
    if (!$userId) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'Invalid User ID provided in URL.']);
      return;
    }
    if (!$librarianId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Unauthorized action. Admin ID not found.']);
      return;
    }

    try {
      $result = $this->restoreRepo->archiveUser($userId, $librarianId);
      if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'User data successfully archived.']);
      } else {
          http_response_code(404);
          echo json_encode([
            'success' => false,
            'message' => 'Failed to archive user. Check debug info.',
            'debug_reason' => $result['debug_reason'] ?? 'Unknown reason.',
            'debug_data' => $result['debug_data'] ?? null
          ]);
        }
    } catch (\Exception $e) {
      http_response_code(500);
      error_log("Error archiving user $userId: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'An internal error occurred during archiving.']);
    }
  }
}
