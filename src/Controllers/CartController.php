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
    $userId = $_SESSION['user_id'] ?? null;
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
    $userId = $_SESSION['user_id'] ?? null;
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
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $this->cartRepo->removeFromCart($cartId, $studentId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function clearCart()
  {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $this->cartRepo->clearCart($studentId);

    header('Content-Type: application/json');
    echo json_encode(["success" => true]);
  }

  public function getCartJson()
  {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) $this->showErrorPage(401, "Not logged in");

    $studentId = $this->getStudentId($userId);
    if (!$studentId) $this->showErrorPage(400, "No student record found for this user");

    $cartItems = $this->cartRepo->getCartByStudent($studentId);

    header('Content-Type: application/json');
    echo json_encode($cartItems);
  }
}
