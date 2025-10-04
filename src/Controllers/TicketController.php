<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\TicketRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class TicketController extends Controller
{
  protected TicketRepository $ticketRepo;

  public function __construct()
  {
    $this->ticketRepo = new TicketRepository();
  }

  /**
   * Generate QR code image and return its path
   */
  private function generateQr(string $transactionCode): string
  {
    $qrPath = __DIR__ . "/../../public/qrcodes/{$transactionCode}.png";

    if (!is_dir(dirname($qrPath))) {
      mkdir(dirname($qrPath), 0777, true);
    }

    $builder = new Builder(
      writer: new PngWriter(),
      data: $transactionCode,
      encoding: new Encoding('UTF-8'),
      size: 250,
      margin: 10
    );

    $result = $builder->build();
    $result->saveToFile($qrPath);

    return "/qrcodes/{$transactionCode}.png";
  }

  public function checkout()
  {
    file_put_contents(__DIR__ . '/../../checkout_test.txt', date('H:i:s') . " checkout() CALLED\n", FILE_APPEND);

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    file_put_contents(
      __DIR__ . '/../../checkout_test.txt',
      "SESSION DATA: " . json_encode($_SESSION) . "\n",
      FILE_APPEND
    );

    $studentId = $_SESSION['student_id'] ?? $_SESSION['user_id'] ?? null;

    if (!$studentId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Unauthorized - no session']);
      exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $selectedIds = $input['cart_ids'] ?? [];

    $logFile = __DIR__ . '/../../checkout_test.txt';
    file_put_contents($logFile, date('H:i:s') . " checkout triggered by user_id={$studentId}, selected=" . json_encode($selectedIds) . "\n", FILE_APPEND);

    try {
      if (!empty($selectedIds)) {
        $cartItems = $this->ticketRepo->getCartItemsByIds($studentId, $selectedIds);
      } else {
        $cartItems = $this->ticketRepo->getCartItems($studentId);
      }

      file_put_contents($logFile, "DEBUG student_id={$studentId}\n", FILE_APPEND);
      file_put_contents($logFile, "DEBUG selectedIds=" . json_encode($selectedIds) . "\n", FILE_APPEND);
      file_put_contents($logFile, "DEBUG fetched cartItems=" . json_encode($cartItems) . "\n", FILE_APPEND);

      if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
      }

      $transactionCode = strtoupper(uniqid("TICKET-"));
      $dueDate = date("Y-m-d H:i:s", strtotime("+7 days"));

      $transactionId = $this->ticketRepo->createTransaction($studentId, $transactionCode, $dueDate);
      $this->ticketRepo->addTransactionItems($transactionId, $cartItems);

      if (!empty($selectedIds)) {
        $this->ticketRepo->removeCartItemsByIds($studentId, $selectedIds);
      } else {
        $this->ticketRepo->clearCart($studentId);
      }

      echo json_encode(['success' => true, 'ticket_id' => $transactionCode]);
      exit;
    } catch (\Throwable $e) {
      file_put_contents($logFile, "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
      echo json_encode(['success' => false, 'message' => 'Internal server error']);
      exit;
    }
  }

  public function show(string $transactionCode)
  {

    $transaction = $this->ticketRepo->getTransactionByCode($transactionCode);

    if (!$transaction) {
      die("Transaction not found.");
    }

    $books = $this->ticketRepo->getTransactionItems($transaction['transaction_id']);

    $student = $this->ticketRepo->getStudentInfo($transaction['student_id']);

    $this->view("student/qrBorrowingTicket", [
      "transaction_id"   => $transaction['transaction_id'],
      "transaction_code" => $transaction['transaction_code'],
      "books"            => $books,
      "qrPath"           => "/qrcodes/{$transaction['transaction_code']}.png",
      "due_date"         => $transaction['due_date'],
      "student"          => $student
    ]);
  }
}
