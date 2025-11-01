<?php

namespace App\Controllers;

use App\Core\Controller;

class ViewController extends Controller
{
  /**
   * Hahawakan ang generic /dashboard request.
   */
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
          'change password', // Fallback
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
      // [TAMA NA ITO]
      $this->view($view_path, [
        "title" => $title,
        "currentPage" => $current_page
      ]);
    } else {
      // [TAMA NA ITO]
      $this->view("errors/403", ["title" => "Forbidden"], false);
    }
  }

  /**
   * Hahawakan ang LAHAT ng ibang generic pages (e.g., /myProfile, /bookManagement).
   */
  public function handleGenericPage($action, $id = null)
  {
    // 1. Kunin ang role mula sa Session
    if (!isset($_SESSION['user_id'])) {
      header('Location: ' . BASE_URL . '/login');
      exit;
    }

    $role = strtolower($_SESSION['role'] ?? '');

    // 2. I-construct ang View Path gamit ang role mula sa session
    $viewPath = $role . '/' . $action;

    // 3. I-construct ang Data
    $data = [
      "title" => ucfirst($action),
      "currentPage" => $action // Para sa sidebar highlighting
    ];

    // 4. Gamitin ang "view" method
    // [INAYOS DITO - Ito ang line 96]
    $this->view($viewPath, $data);
  }
}
