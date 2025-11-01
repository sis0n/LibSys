<?php

/**
 * Role View Router (Internal Router)
 * File Path: libsys/src/views/[ROLE_FOLDER]/index.php
 * Ang $action ay ang view file name (e.g., myProfile).
 */

// Ang $action at $id ay galing sa dynamic_view_loader.php

$action = $action ?? 'dashboard';
$id = $id ?? null;

// 1. I-set ang variables na kailangan ng View at Sidebar
$data = [
  "title" => ucfirst($action),
  "currentPage" => $action // Ito ang ginagamit ng sidebar mo para sa highlighting
];

// 2. Gawin ang filename (e.g., bookCatalog.php)
$action_file = $action . '.php';

// 3. I-check ang file at i-load
if (file_exists(__DIR__ . '/' . $action_file)) {
  // I-extract ang data para maging available ang $title at $currentPage sa view
  extract($data);

  // Ang $id variable ay available na rin dito para magamit sa view file
  require_once __DIR__ . '/' . $action_file;
} else {
  http_response_code(404);
  echo "404 Error: The requested page (" . htmlspecialchars($action_file) . ") was not found in this area.";
}
