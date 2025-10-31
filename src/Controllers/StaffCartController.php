<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\StaffCartRepository;

class StaffCartController extends Controller
{
  private StaffCartRepository $cartRepo;

  public function __construct()
  {
    $this->cartRepo = new StaffCartRepository();
  }

  private function getStaffId($userId)
  {
    return $this->cartRepo->getStaffIdByUserId($userId);
  }

  private function showErrorPage(int $status, string $message = "")
  {
    http_response_code($status);
    $this->view("errors/{$status}", ["message" => $message]);
    exit;
  }

  public function index()
  {
    $userId = $this->ensureStaff();
    if (!$userId) $this->showErrorPage(401, "Access denied");

    $staffId = $this->getStaffId($userId);
    if (!$staffId) $this->showErrorPage(400, "No staff record found for this user.");

    $cartItems = $this->cartRepo->getCartByStaff($staffId);
    $this->view("staff/cart", [
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

    $staffId = $this->getStaffId($userId);
    if (!$staffId) {
      http_response_code(400);
      echo json_encode([
        'success' => false,
        'message' => 'No staff record found for this user.'
      ]);
      return;
    }

    $result = $this->cartRepo->addToCart((int)$staffId, $bookId);
    echo json_encode(['success' => $result]);
  }

  public function remove($cartId)
  {
    $userId = $this->ensureStaff();
    $staffId = $this->getStaffId($userId);

    $this->cartRepo->removeFromCart($cartId, $staffId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function clearCart()
  {
    $userId = $this->ensureStaff();
    $staffId = $this->getStaffId($userId);

    $this->cartRepo->clearCart($staffId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function getCartJson()
  {
    $userId = $this->ensureStaff();
    $staffId = $this->getStaffId($userId);
    // var_dump($staffId);


    if (!$staffId) {
      echo json_encode([]);
      return;
    }

    $cartItems = $this->cartRepo->getCartByStaff($staffId);

    header('Content-Type: application/json');
    echo json_encode($cartItems);
  }

  public function checkout()
  {
    $userId = $this->ensureStaff();
    $staffId = $this->getStaffId($userId);

    $data = json_decode(file_get_contents("php://input"), true);
    $cartIds = $data['cart_ids'] ?? [];

    if (empty($cartIds)) {
      header('Content-Type: application/json');
      echo json_encode(["success" => false, "message" => "No items selected"]);
      return;
    }

    $ticketId = uniqid("TICKET-");

    foreach ($cartIds as $cid) {
      $this->cartRepo->removeFromCart((int)$cid, $staffId);
    }

    header('Content-Type: application/json');
    echo json_encode([
      "success" => true,
      "ticket_id" => $ticketId
    ]);
  }

  private function ensureStaff()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
      header('Location: '. BASE_URL . '/login');
      exit;
    }

    return $_SESSION['user_id'];
  }
}
