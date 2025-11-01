<?php

/**
 * Dynamic View Loader: Bridge sa pagitan ng Router at Role View Router.
 * File Path: libsys/src/views/dynamic_view_loader.php
 */

// 1. Kuhanin ang $role, $action, at $id mula sa global scope
$role = $GLOBALS['role'] ?? null;
$action = $GLOBALS['action'] ?? null;
$id = $GLOBALS['id'] ?? null;

if (!$role || !$action) {
  http_response_code(400);
  exit;
}

// 2. I-normalize ang Role para tugma sa Folder Name
$folder_name = ucfirst(strtolower($role));

// 3. I-set ang $action at $id sa kasalukuyang scope
$action = $action;
$id = $id;

// 4. Hanapin ang path papunta sa Role View Router (e.g., Student/index.php)
$view_router_path = __DIR__ . '/' . $folder_name . '/index.php';

if (file_exists($view_router_path)) {
  require_once $view_router_path;
} else {
  http_response_code(404);
  echo "404 Error: View router not found for " . htmlspecialchars($folder_name);
}
