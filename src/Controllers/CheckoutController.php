<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CartRepository;
use App\Repositories\BorrowingRepository;

class CheckoutController extends Controller
{
  private $cartRepo;
  private $borrowingRepo;

  public function __construct()
  {
    $this->cartRepo = new CartRepository();
    $this->borrowingRepo = new BorrowingRepository();
  }

  public function checkout()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      http_response_code(405);
      $this->view('errors/405');
      exit;
    }

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      http_response_code(403);
      $this->view('errors/403');
      exit;
    }

    if (!isset($_SESSION['student_id'])) {
      header("Location: /login");
      exit;
    }
    $studentId = $_SESSION['student_id'];

    $cartItems = $this->cartRepo->getCartItems($studentId);
    if (empty($cartItems)) {
      header("Location: /cart");
      exit;
    }

    $this->borrowingRepo->createFromCart($studentId, $cartItems, $_ENV['SECRET_KEY']);
    $this->cartRepo->clearCart($studentId);

    header("Location: /borrowings/receipt");
  }
}
