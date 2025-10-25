<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\TicketRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


class TicketController extends Controller
{
  protected TicketRepository $ticketRepo;

  public function __construct()
  {
    $this->ticketRepo = new TicketRepository();
  }

  private function generateQr(string $transactionCode): string
  {
    $qrDir = __DIR__ . "/../../public/qrcodes";
    $qrPath = $qrDir . "/{$transactionCode}.png";

    if (!is_dir($qrDir)) {
      if (!mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
        error_log("Failed to create directory: " . $qrDir);
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $qrDir));
      }
    }

    try {
      $builder = new Builder(
        writer: new PngWriter(),
        data: $transactionCode,
        encoding: new Encoding('UTF-8'),
        errorCorrectionLevel: ErrorCorrectionLevel::High,
        size: 300,
        margin: 10,
        roundBlockSizeMode: RoundBlockSizeMode::Margin
      );

      $result = $builder->build();
      $result->saveToFile($qrPath);

      return "/libsys/public/qrcodes/{$transactionCode}.png";
    } catch (\Exception $e) {
      error_log("QR Code Generation Error for code '$transactionCode': " . $e->getMessage());
      return '';
    }
  }

  public function checkout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    header('Content-Type: application/json');

    $userId = $_SESSION['user_id'] ?? null;
    $MAX_BOOKS_PER_TICKET = 5;

    if (!$userId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      exit;
    }

    $studentId = $this->ticketRepo->getStudentIdByUserId((int)$userId);
    if (!$studentId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'No student record found for this user.']);
      exit;
    }

    // PROFILE COMPLETION CHECK
    $profileCheck = $this->ticketRepo->checkProfileCompletion($studentId);
    if (!$profileCheck['complete']) {
      http_response_code(400);
      echo json_encode(["success" => false, "message" => $profileCheck['message']]);
      return;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $selectedIds = $input['cart_ids'] ?? [];

    if (!is_array($selectedIds)) {
      $selectedIds = [];
    }

    try {
      $cartItems = !empty($selectedIds)
        ? $this->ticketRepo->getCartItemsByIds($studentId, $selectedIds)
        : $this->ticketRepo->getCartItems($studentId);

      if (empty($cartItems)) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty or selected items not found.']);
        exit;
      }

      $existingTicket = $this->ticketRepo->getPendingTransactionByStudentId($studentId);
      $newItemsCount = count($cartItems);

      if ($existingTicket) {
        $transactionId = (int)$existingTicket['transaction_id'];
        $transactionCode = $existingTicket['transaction_code'];
        $currentItemsCount = $this->ticketRepo->countItemsInTransaction($transactionId);
        $totalItems = $currentItemsCount + $newItemsCount;

        if ($totalItems > $MAX_BOOKS_PER_TICKET) {
          http_response_code(400);
          echo json_encode([
            'success' => false,
            'message' => "You can only borrow a total of {$MAX_BOOKS_PER_TICKET} books per pending ticket (Current: {$currentItemsCount}). You are trying to add {$newItemsCount} more."
          ]);
          exit;
        }

        $message = 'Checkout successful! Items added to your pending ticket.';
      } else {
        if ($newItemsCount > $MAX_BOOKS_PER_TICKET) {
          http_response_code(400);
          echo json_encode([
            'success' => false,
            'message' => "The number of books ({$newItemsCount}) exceeds the maximum limit of {$MAX_BOOKS_PER_TICKET} per ticket."
          ]);
          exit;
        }

        $transactionCode = strtoupper(uniqid());
        $dueDate = date("Y-m-d H:i:s", strtotime("+7 days"));
        $transactionId = $this->ticketRepo->createTransaction($studentId, $transactionCode, $dueDate);
        $message = 'Checkout successful! A new Borrowing Ticket has been created.';
      }

      $this->ticketRepo->addTransactionItems($transactionId, $cartItems);

      $cartItemIdsToRemove = array_column($cartItems, 'cart_id');
      if (!empty($cartItemIdsToRemove)) {
        $this->ticketRepo->removeCartItemsByIds($studentId, $cartItemIdsToRemove);
      }

      $_SESSION['last_ticket_code'] = $transactionCode;

      $qrPath = $this->generateQr($transactionCode);
      if (empty($qrPath)) {
        error_log("Failed to generate QR code image for transaction: " . $transactionCode);
        echo json_encode([
          'success' => true,
          'message' => 'Checkout successful! Ticket created/updated, but QR image generation failed.',
          'ticket_code' => $transactionCode,
          'qrPath' => null
        ]);
        exit;
      }

      echo json_encode([
        'success' => true,
        'message' => $message,
        'ticket_code' => $transactionCode,
        'qrPath' => $qrPath
      ]);
      exit;
    } catch (\PDOException $dbExc) {
      error_log("Database Error during checkout: " . $dbExc->getMessage());
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'A database error occurred during checkout. Please try again later.']);
      exit;
    } catch (\Throwable $e) {
      error_log("General Error during checkout: " . $e->getMessage());
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'An internal server error occurred during checkout: ' . $e->getMessage()
      ]);
      exit;
    }
  }


  public function show(string $transactionCode = null)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      $_SESSION['error_message'] = "Please login to view your ticket.";
      header("Location: /libsys/public/login");
      exit;
    }

    $studentId = $this->ticketRepo->getStudentIdByUserId((int)$userId);
    if (!$studentId) {
      error_log("User ID $userId logged in but has no associated student record.");
      $this->view("errors/no_student_record", ["title" => "Error: No Student Record"], false);
      exit;
    }

    $transactionData = null;
    $books = [];
    $studentInfo = [
      'student_number' => 'N/A',
      'name' => 'Student Name',
      'year_level' => 'N/A',
      'course' => 'N/A'
    ];
    $qrPath = null;
    $viewMessage = null;
    $viewError = null;

    try {
      if (empty($transactionCode)) {
        $transactionCode = $_SESSION['last_ticket_code'] ?? null;
      }
      if (empty($transactionCode)) {
        $latestTransaction = $this->ticketRepo->getLatestTransactionByStudentId($studentId);
        if ($latestTransaction) {
          $transactionCode = $latestTransaction['transaction_code'] ?? null;
        }
      }

      if ($transactionCode) {
        $transaction = $this->ticketRepo->getTransactionByCode($transactionCode);

        if ($transaction && isset($transaction['student_id']) && $transaction['student_id'] == $studentId) {
          $transactionData = $transaction;
          $books = $this->ticketRepo->getTransactionItems($transactionData['transaction_id']);
          $studentDetails = $this->ticketRepo->getStudentInfo($transactionData['student_id']);

          if ($studentDetails) {
            $fullName = implode(' ', array_filter([
              $studentDetails['first_name'] ?? '',
              $studentDetails['middle_name'] ?? '',
              $studentDetails['last_name'] ?? ''
            ]));

            $studentInfo['student_number'] = $studentDetails['student_number'] ?? 'N/A';
            $studentInfo['name'] = !empty(trim($fullName)) ? trim($fullName) : 'Student Name';
            $studentInfo['year_level'] = $studentDetails['year_level'] ?? 'N/A';
            $studentInfo['course'] = $studentDetails['course'] ?? 'N/A';
          } else {
            error_log("Could not retrieve student details for student_id: " . $transactionData['student_id']);
          }

          $qrPath = $this->generateQr($transactionData['transaction_code']);
          if (empty($qrPath)) {
            error_log("Failed to generate QR code image for transaction view: " . $transactionCode);
            $viewError = "Could not generate the QR code image for this ticket.";
          }
        } else {
          error_log("Attempt to access invalid/unauthorized transaction code '$transactionCode' by student ID $studentId (User ID $userId).");
          unset($_SESSION['last_ticket_code']);
          $transactionCode = null;
          $viewError = "The requested borrowing ticket was not found or is invalid.";
        }
      } else {
        $viewMessage = "You do not currently have an active borrowing ticket.";
      }
    } catch (\Throwable $e) {
      error_log("ERROR in TicketController show(): " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
      $viewError = "An unexpected error occurred while loading your ticket details. Please try again later.";
    }

    $viewData = [
      "title" => "QR Borrowing Ticket",
      "currentPage" => "qrBorrowingTicket",
      "transaction_id" => $transactionData['transaction_id'] ?? null,
      "transaction_code" => $transactionData['transaction_code'] ?? null,
      "books" => $books,
      "qrPath" => $qrPath,
      "due_date" => $transactionData['due_date'] ?? null,
      "student" => $studentInfo,
      "borrowed_at" => $transactionData['borrowed_at'] ?? null,
      "message" => $viewMessage,
      "error_message" => $viewError
    ];

    $this->view("student/qrBorrowingTicket", $viewData);
  }
}
