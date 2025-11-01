<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\FacultyProfileRepository;
use App\Repositories\UserRepository;

class FacultyProfileController extends Controller
{
  private FacultyProfileRepository $facultyRepo;
  private UserRepository $userRepo;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();

    $this->facultyRepo = new FacultyProfileRepository();
    $this->userRepo = new UserRepository();
  }

  private function json($data, int $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  private function handleFileUpload($file)
  {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
      return null;
    }

    $uploadDir = __DIR__ . '/../../public/uploads/profile_images/';

    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = uniqid('file_', true) . '.' . $extension;
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
      // Ito ang URL path na i-store sa DB o ibabalik
      return  BASE_URL . '/uploads/profile_images/' . $fileName;
    }

    return null;
  }

  private function validateImageUpload($file)
  {
    $maxSize = 1 * 1024 * 1024; // 1MB
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if ($file['error'] !== UPLOAD_ERR_OK) return "Upload error.";
    if ($file['size'] > $maxSize) return "Image must be less than 1MB.";

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedTypes)) return "Invalid image type. Only JPG, PNG, GIF allowed.";
    return true;
  }

  public function getProfile()
  {
    $currentUserId = $_SESSION['user_id'] ?? null;
    if (!$currentUserId) return $this->json(['success' => false, 'message' => 'Unauthorized'], 401);

    $profile = $this->facultyRepo->getProfileByUserId($currentUserId);
    if (!$profile) return $this->json(['success' => false, 'message' => 'Profile not found.'], 404);

    // Faculty can always edit
    $profile['allow_edit'] = 1;

    $this->json(['success' => true, 'profile' => $profile]);
  }

  public function updateProfile()
  {
    $currentUserId = $_SESSION['user_id'] ?? null;
    if (!$currentUserId) return $this->json(['success' => false, 'message' => 'Unauthorized'], 401);

    $data = $_POST;
    $profile = $this->facultyRepo->getProfileByUserId($currentUserId);
    if (!$profile) return $this->json(['success' => false, 'message' => 'Profile not found.'], 404);

    // Faculty: unlimited edits → REMOVE profile lock
    // if ($profile['profile_updated'] == 1) ...

    // Validate required fields
    $requiredFields = ['first_name', 'last_name', 'email', 'department', 'contact'];
    $missingFields = [];
    foreach ($requiredFields as $field) {
      if (!isset($data[$field]) || trim($data[$field]) === '') $missingFields[] = $field;
    }
    if (!empty($missingFields)) {
      return $this->json([
        'success' => false,
        'message' => 'Missing required fields: ' . implode(', ', $missingFields)
      ], 400);
    }

    // Contact validation
    if (!preg_match('/^\d{11}$/', $data['contact'])) {
      return $this->json(['success' => false, 'message' => 'Contact number must be 11 digits.'], 400);
    }

    // Email validation
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      return $this->json(['success' => false, 'message' => 'Invalid email address.'], 400);
    }

    // Update users table
    $fullName = trim(implode(' ', array_filter([$data['first_name'], $data['middle_name'] ?? null, $data['last_name'], $data['suffix'] ?? null])));
    $userData = [
      'first_name' => $data['first_name'],
      'middle_name' => $data['middle_name'] ?? null,
      'last_name' => $data['last_name'],
      'suffix' => $data['suffix'] ?? null,
      'full_name' => $fullName,
      'email' => $data['email']
    ];

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
      $validation = $this->validateImageUpload($_FILES['profile_image']);
      if ($validation !== true) return $this->json(['success' => false, 'message' => $validation], 400);
      $imagePath = $this->handleFileUpload($_FILES['profile_image'], "public/uploads/profile_images/");
      $userData['profile_picture'] = $imagePath;
    }

    $this->userRepo->updateUser($currentUserId, $userData);

    // Update faculty table
    $facultyData = [
      'department' => $data['department'],
      'contact' => $data['contact'],
      'profile_updated' => 1 // optional, just for record
    ];
    $this->facultyRepo->updateFacultyProfile($currentUserId, $facultyData);

    // Update session
    $_SESSION['fullname'] = $fullName;
    if (isset($imagePath)) $_SESSION['profile_picture'] = $imagePath;

    $this->json(['success' => true, 'message' => 'Profile updated successfully!']);
  }
}
