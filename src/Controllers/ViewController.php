<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserPermissionModuleRepository;

class ViewController extends Controller
{
  private $userPermissionsRepo;

  public function __construct()
  {
    $this->userPermissionsRepo = new UserPermissionModuleRepository();
  }

  public function handleDashboard()
  {
    if (!isset($_SESSION['user_id'])) {
      header('Location: ' . BASE_URL . '/login');
      exit;
    }

    $role = strtolower($_SESSION['role'] ?? '');
    $userPermissions = $_SESSION['user_permissions'] ?? [];
    $normalizedPermissions = array_map(fn($p) => trim(strtolower($p)), $userPermissions);

    $view_path = null;
    $current_page = null;
    $title = "Dashboard";

    switch ($role) {
      case 'student':
      case 'faculty':
      case 'staff':
      case 'superadmin':
        $view_path = $role . '/dashboard';
        $current_page = 'dashboard';
        break;

      case 'admin':
      case 'librarian':
        $priority_privileges = [
          'book management',
          'qr scanner',
          'returning',
          'borrowing form',
          'attendance logs',
          'reports',
          'transaction history',
          'restore books',
        ];

        foreach ($priority_privileges as $privilege) {
          if (in_array($privilege, $normalizedPermissions) || $role === 'superadmin') {
            $action = str_replace(' ', '', ucwords($privilege));
            $view_path = $role . '/' . $action;
            $current_page = $action;
            $title = ucfirst($action);
            break;
          }
        }
        break;
    }

    if ($view_path) {
      $this->view($view_path, [
        "title" => $title,
        "currentPage" => $current_page
      ]);
    } else {
      $this->view("errors/403", ["title" => "Forbidden"], false);
    }
  }

  public function handleGenericPage($action, $id = null)
  {
    if (!isset($_SESSION['user_id'])) {
      header('Location: ' . BASE_URL . '/login');
      exit;
    }

    $userId = (int)$_SESSION['user_id'];
    $role = strtolower($_SESSION['role'] ?? '');

    $protectedModules = [
      'bookManagement' => 'book management',
      'qrScanner' => 'qr scanner',
      'returning' => 'returning',
      'borrowingForm' => 'borrowing form',
      'attendanceLogs' => 'attendance logs',
      'reports' => 'reports',
      'topVisitor' => 'reports',
      'transactionHistory' => 'transaction history',
      'backup' => 'backup',
      'restoreBooks' => 'restore books',
      'restoreUser' => 'restore users',
      'userManagement' => 'user management' 
    ];

    $universalPages = [
      'changePassword',
      'myProfile',
      'bookCatalog',
      'myCart',
      'qrBorrowingTicket',
      'borrowingHistory',
      'myAttendance',
      'dashboard',
    ];

    if (array_key_exists($action, $protectedModules)) {

      if ($role === 'superadmin') {
      } else if ($role === 'admin' || $role === 'librarian') {
        $permissionName = $protectedModules[$action];

        if (!$this->userPermissionsRepo->hasAccess($userId, $permissionName)) {
          $this->view("errors/403", ["title" => "Forbidden"], false);
          exit;
        }
      } else {
        $this->view("errors/403", ["title" => "Forbidden"], false);
        exit;
      }
    } else if (in_array($action, $universalPages)) {
    }
    else {
      $this->view("errors/404", ["title" => "Not Found"], false);
      exit;
    }

    $viewPath = $role . '/' . $action;
    $data = [
      "title" => ucfirst($action),
      "currentPage" => $action
    ];

    $this->view($viewPath, $data);
  }
}
