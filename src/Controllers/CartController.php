<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CartRepository;

class CartController extends Controller
{
  private CartRepository $cartRepo;

  public function __construct()
  {
    $this->cartRepo = new CartRepository();
  }

  private function getStudentId($userId)
  {
    return $this->cartRepo->getStudentIdByUserId($userId);
  }

  private function showErrorPage(int $status, string $message = "")
  {
    http_response_code($status);
    $this->view("errors/{$status}", ["message" => $message]);
    exit;
  }

  public function index()
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Bawal ka dito boi");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user.");

    $cartItems = $this->cartRepo->getCartByStudent($studentId);
    $this->view("student/cart", [
      "cartItems" => $cartItems,
      "title" => "My Cart"
    ]);
  }

  public function add($bookId)
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "User is not a student");

    $success = $this->cartRepo->addToCart($studentId, $bookId);

    header('Content-Type: application/json');
    echo json_encode([
      "success" => $success,
      "cart_count" => $this->cartRepo->countCartItems($studentId)
    ]);
  }

  public function remove($cartId)
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $this->cartRepo->removeFromCart($cartId, $studentId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function clearCart()
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $this->cartRepo->clearCart($studentId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function getCartJson()
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $cartItems = $this->cartRepo->getCartByStudent($studentId);

    header('Content-Type: application/json');
    echo json_encode($cartItems);
  }

  public function checkout()
  {
    $userId = $this->ensureStudent();
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $data = json_decode(file_get_contents("php://input"), true);
    $cartIds = $data['cart_ids'] ?? [];

    if (empty($cartIds)) {
      header('Content-Type: application/json');
      echo json_encode(["success" => false, "message" => "No items selected"]);
      return;
    }

    $ticketId = uniqid("TICKET-");

    foreach ($cartIds as $cid) {
      $this->cartRepo->removeFromCart((int)$cid, $studentId);
    }

    header('Content-Type: application/json');
    echo json_encode([
      "success" => true,
      "ticket_id" => $ticketId
    ]);
  }

  private function ensureStudent()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
      header('Location: ' . base_url('login'));
      exit;
    }

    return $_SESSION['user_id'];
  }
}
