<?php

namespace App\Controllers;

use App\Repositories\CartRepository;
use App\Core\Controller;

class CartController extends Controller
{
  private $cartRepo;

  public function __construct()
  {
    $this->cartRepo = new CartRepository();
  }

  public function index()
  {
    if (!isset($_SESSION['student_id'])) {
      header("Location: /login");
      exit;
    }

    $studentId = $_SESSION['student_id'];
    $cartItems = $this->cartRepo->getCartItems($studentId);

    $this->view("cart/index", [
      "cartItems" => $cartItems,
      "title" => "My Cart"
    ]);
  }

  public function add($bookId)
  {
    $studentId = $_SESSION['student_id'];
    $this->cartRepo->addToCart($studentId, $bookId);
    header("Location: /cart");
  }

  public function remove($cartId)
  {
    $this->cartRepo->removeFromCart($cartId);
    header("Location: /cart");
  }
}
