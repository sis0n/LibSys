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

    return "/libsys/public/qrcodes/{$transactionCode}.png";
  }

  public function checkout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      exit;
    }

    $studentId = $this->ticketRepo->getStudentIdByUserId((int)$userId);
    if (!$studentId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'No student record found']);
      exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $selectedIds = $input['cart_ids'] ?? [];

    if (!is_array($selectedIds)) {
      http_response_code(400);
      echo json_encode(['success' => false, 'message' => 'cart_ids must be an array']);
      exit;
    }

    try {
      $cartItems = !empty($selectedIds)
        ? $this->ticketRepo->getCartItemsByIds($studentId, $selectedIds)
        : $this->ticketRepo->getCartItems($studentId);

      if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
      }

      $existingTransaction = $this->ticketRepo->getLatestTransactionByStudentId($studentId);

      $useExisting = false;
      if ($existingTransaction) {
        $dueDate = strtotime($existingTransaction['due_date']);
        if ($dueDate > time()) { // still valid
          $useExisting = true;
          $transactionId = $existingTransaction['transaction_id'];
          $transactionCode = $existingTransaction['transaction_code'];
        }
      }

      if ($useExisting) {
        $borrowedBooks = $this->ticketRepo->getBorrowedBooksByTransaction($transactionId);

        $borrowedAccessions = array_column($borrowedBooks, 'accession_number');
        $duplicateBooks = [];

        foreach ($cartItems as $book) {
          if (in_array($book['accession_number'], $borrowedAccessions)) {
            $duplicateBooks[] = $book['title'];
          }
        }

        if (!empty($duplicateBooks)) {
          echo json_encode([
            'success' => false,
            'message' => 'Some books are already in your active ticket: ' . implode(', ', $duplicateBooks)
          ]);
          exit;
        }
      }

      if (!$useExisting) {
        $transactionCode = strtoupper(uniqid("TICKET-"));
        $dueDate = date("Y-m-d H:i:s", strtotime("+7 days"));
        $transactionId = $this->ticketRepo->createTransaction($studentId, $transactionCode, $dueDate);
      }

      $this->ticketRepo->addTransactionItems($transactionId, $cartItems);

      if (!empty($selectedIds)) {
        $this->ticketRepo->removeCartItemsByIds($studentId, $selectedIds);
      } else {
        $this->ticketRepo->clearCart($studentId);
      }

      $_SESSION['last_ticket_code'] = $transactionCode;

      $qrPath = $this->generateQr($transactionCode);

      header('Content-Type: application/json');
      echo json_encode([
        'success' => true,
        'message' => $useExisting
          ? 'Books added to your existing Borrowing Ticket!'
          : 'Checkout successful! A new Borrowing Ticket has been created.',
        'ticket_code' => $transactionCode,
        'qrPath' => $qrPath
      ]);
      exit;
    } catch (\Throwable $e) {
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
      ]);
      exit;
    }
  }

  public function show(string $transactionCode = null)
  {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      http_response_code(403);
      echo "Unauthorized - Please login.";
      exit;
    }

    $studentId = $this->ticketRepo->getStudentIdByUserId((int)$userId);

    $transaction = [
      'transaction_id' => null,
      'transaction_code' => 'N/A',
      'due_date' => 'N/A',
      'borrowed_at' => null
    ];

    $books = [];
    $student = [
      'student_number' => 'N/A',
      'name' => 'Student Name',
      'year_level' => 'N/A',
      'course' => 'N/A'
    ];

    $qrPath = null;

    try {
      if (!$transactionCode) {
        $transactionCode = $_SESSION['last_ticket_code'] ?? null;
      }

      if (!$transactionCode && $studentId) {
        $latestTransaction = $this->ticketRepo->getLatestTransactionByStudentId($studentId);
        $transactionCode = $latestTransaction['transaction_code'] ?? null;
      }

      if ($transactionCode) {
        $transaction = $this->ticketRepo->getTransactionByCode($transactionCode);
        $books = $this->ticketRepo->getTransactionItems($transaction['transaction_id']);
        $student = $this->ticketRepo->getStudentInfo($transaction['student_id']);
        $qrPath = $this->generateQr($transaction['transaction_code']);
      }
    } catch (\Throwable $e) {
      file_put_contents(__DIR__ . '/../../checkout_test.txt', "ERROR in show(): " . $e->getMessage() . "\n", FILE_APPEND); //pang debug
    }
    file_put_contents(
      __DIR__ . '/../../checkout_test.txt',
      "SHOW DEBUG:\n" .
        "transactionCode: " . ($transactionCode ?? 'NULL') . "\n" .
        "transaction_id: " . ($transaction['transaction_id'] ?? 'NULL') . "\n" .
        "books count: " . count($books) . "\n" .
        "qrPath: " . ($qrPath ?? 'NULL') . "\n\n",
      FILE_APPEND
    );

    $this->view("student/qrBorrowingTicket", [
      "transaction_id"   => $transaction['transaction_id'],
      "transaction_code" => $transaction['transaction_code'],
      "books" => $books,
      "qrPath" => $qrPath,
      "due_date" => $transaction['due_date'],
      "student" => $student,
      "borrowed_at" => $transaction['borrowed_at']
    ]);
  }
}
