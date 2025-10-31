<?php

/**
 * Dashboard Handler: Hahawakan ang generic /dashboard request at magre-redirect.
 * File Path: libsys/src/dashboard_handler.php
 */

if (!isset($_SESSION['user_id'])) {
  header('Location: ' . BASE_URL . '/login');
  exit;
}

$role = strtolower($_SESSION['role'] ?? '');
$userPermissions = $_SESSION['user_permissions'] ?? [];
$normalizedPermissions = array_map(fn($p) => trim(strtolower($p)), $userPermissions);

$redirect_path_action = null;
$role_folder = $role;

// 1. I-define ang Priority List (Gamit ang final list mo)
switch ($role) {
  case 'student':
  case 'faculty':
  case 'staff':
  case 'superadmin':
    // FIXED LANDING PAGE
    $redirect_path_action = 'dashboard';
    break;

  case 'admin':
  case 'librarian':
    // PRIVILEGE-BASED LANDING PAGE
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
        // Convert privilege (e.g., 'book management') to URL action (e.g., 'bookManagement')
        $url_action = str_replace(' ', '', ucwords($privilege));
        $redirect_path_action = $url_action;
        break;
      }
    }
    break;
}

// 2. Final Redirection Construction
if ($redirect_path_action) {
  $redirect_path = BASE_URL . '/' . $role_folder . '/' . $redirect_path_action;
  header('Location: ' . $redirect_path);
  exit;
} else {
  http_response_code(403);
  echo "403 Forbidden: User role '{$role}' has no configured landing page or required privileges.";
  exit;
}
