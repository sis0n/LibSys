<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\FacultyCartRepository;

class FacultyCartController extends Controller
{
  private FacultyCartRepository $cartRepo;

  public function __construct()
  {
    $this->cartRepo = new FacultyCartRepository();
  }

  private function getFacultyId($userId)
  {
    return $this->cartRepo->getFacultyIdByUserId($userId);
  }


  private function showErrorPage(int $status, string $message = "")
  {
    http_response_code($status);
    $this->view("errors/{$status}", ["message" => $message]);
    exit;
  }

  public function index()
  {
    $userId = $this->ensureFaculty();
    if (!$userId) $this->showErrorPage(401, "Access denied");

    $facultyId = $this->getFacultyId($userId);
    if (!$facultyId) $this->showErrorPage(400, "No faculty record found for this user.");

    $cartItems = $this->cartRepo->getCartByFaculty($facultyId);
    $this->view("faculty/cart", [
      "cartItems" => $cartItems,
      "title" => "My Cart"
    ]);
  }

  public function add($bookId)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      http_response_code(401);
      echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Please log in first.'
      ]);
      return;
    }

    // Kunin ang tamang faculty_id
    $facultyId = $this->getFacultyId($userId);
    if (!$facultyId) {
      http_response_code(400);
      echo json_encode([
        'success' => false,
        'message' => 'No faculty record found for this user.'
      ]);
      return;
    }

    // Add to cart
    $result = $this->cartRepo->addToCart((int)$facultyId, $bookId);
    echo json_encode(['success' => $result]);
  }

  public function remove($cartId)
  {
    $userId = $this->ensureFaculty();
    $facultyId = $this->getFacultyId($userId);

    $this->cartRepo->removeFromCart($cartId, $facultyId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function clearCart()
  {
    $userId = $this->ensureFaculty();
    $facultyId = $this->getFacultyId($userId);

    $this->cartRepo->clearCart($facultyId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function getCartJson()
  {
    $userId = $this->ensureFaculty();
    $facultyId = $this->getFacultyId($userId);

    $cartItems = $this->cartRepo->getCartByFaculty($facultyId);

    header('Content-Type: application/json');
    echo json_encode($cartItems);
  }

  public function checkout()
  {
    $userId = $this->ensureFaculty();
    $facultyId = $this->getFacultyId($userId);

    $data = json_decode(file_get_contents("php://input"), true);
    $cartIds = $data['cart_ids'] ?? [];

    if (empty($cartIds)) {
      header('Content-Type: application/json');
      echo json_encode(["success" => false, "message" => "No items selected"]);
      return;
    }

    $ticketId = uniqid("TICKET-");

    foreach ($cartIds as $cid) {
      $this->cartRepo->removeFromCart((int)$cid, $facultyId);
    }

    header('Content-Type: application/json');
    echo json_encode([
      "success" => true,
      "ticket_id" => $ticketId
    ]);
  }

  private function ensureFaculty()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
      header('Location: ' . BASE_URL . '/login');
      exit;
    }

    return $_SESSION['user_id'];
  }
}
