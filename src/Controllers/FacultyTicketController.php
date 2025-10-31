<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\FacultyTicketRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class FacultyTicketController extends Controller
{
  protected FacultyTicketRepository $ticketRepo;

  public function __construct()
  {
    $this->ticketRepo = new FacultyTicketRepository();
  }

  private function generateQr(string $transactionCode): string
  {
    $qrDir = __DIR__ . "/../../public/qrcodes";
    $qrPath = $qrDir . "/{$transactionCode}.png";

    if (!is_dir($qrDir) && !mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
      error_log("Failed to create directory: " . $qrDir);
      return '';
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

      return BASE_URL . "/qrcodes/{$transactionCode}.png";
    } catch (\Exception $e) {
      error_log("QR Code Generation Error for code '$transactionCode': " . $e->getMessage());
      return '';
    }
  }

  private function getFullName(array $details): string
  {
    $firstName = $details['first_name'] ?? '';
    $lastName = $details['last_name'] ?? '';
    $middleName = $details['middle_name'] ?? '';

    $middleInitial = !empty($middleName) ? substr($middleName, 0, 1) . '.' : '';

    return trim("{$firstName} {$middleInitial} {$lastName}");
  }

  public function checkout()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json');

    $userId = $_SESSION['user_id'] ?? null;
    $MAX_BOOKS = 10;

    if (!$userId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      exit;
    }

    $facultyId = $this->ticketRepo->getFacultyIdByUserId((int)$userId);
    if (!$facultyId) {
      http_response_code(403);
      echo json_encode(['success' => false, 'message' => 'No faculty record found for this user.']);
      exit;
    }

    $profileCheck = $this->ticketRepo->checkProfileCompletion($facultyId);
    if (!$profileCheck['complete']) {
      http_response_code(400);
      echo json_encode([
        'success' => false,
        'message' => $profileCheck['message']
      ]);
      exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $selectedIds = $input['cart_ids'] ?? [];
    if (!is_array($selectedIds)) $selectedIds = [];

    try {
      $this->ticketRepo->beginTransaction();
      $this->ticketRepo->expireOldPendingTransactionsFaculty();

      $cartItems = !empty($selectedIds)
        ? $this->ticketRepo->getFacultyCartItemsByIds($facultyId, $selectedIds)
        : $this->ticketRepo->getFacultyCartItems($facultyId);

      if (empty($cartItems)) {
        $this->ticketRepo->rollback();
        echo json_encode(['success' => false, 'message' => 'Cart is empty or selected items not found.']);
        exit;
      }

      $bookIds = array_column($cartItems, 'book_id');
      $unavailableBooks = $this->ticketRepo->areBooksAvailable($bookIds);
      if (!empty($unavailableBooks)) {
        $titles = implode(', ', array_column($unavailableBooks, 'title'));
        $this->ticketRepo->rollback();
        http_response_code(400);
        echo json_encode([
          'success' => false,
          'message' => "The following book(s) are already checked out or pending: $titles"
        ]);
        exit;
      }

      $borrowedThisPeriod = $this->ticketRepo->countFacultyBorrowedBooks($facultyId);
      $newItemsCount = count($cartItems);
      if ($borrowedThisPeriod + $newItemsCount > $MAX_BOOKS) {
        $this->ticketRepo->rollback();
        http_response_code(400);
        echo json_encode([
          'success' => false,
          'message' => "You can only borrow a maximum of {$MAX_BOOKS} books. Current: {$borrowedThisPeriod}, Trying to add: {$newItemsCount}"
        ]);
        exit;
      }

      $existingTransaction = $this->ticketRepo->getPendingTransactionByFacultyId($facultyId);

      if ($existingTransaction) {
        $transactionId = (int)$existingTransaction['transaction_id'];
        $transactionCode = $existingTransaction['transaction_code'];
        $message = 'Checkout successful! Items added to your pending ticket.';
      } else {
        $transactionCode = strtoupper(uniqid());
        $dueDate = date("Y-m-d H:i:s", strtotime("+14 days"));
        $transactionId = $this->ticketRepo->createPendingTransactionForFaculty($facultyId, $transactionCode, $dueDate, 15);

        $message = 'Checkout successful! A new Borrowing Ticket has been created.';
      }

      $this->ticketRepo->addTransactionItems($transactionId, $cartItems);

      $cartItemIdsToRemove = array_column($cartItems, 'cart_id');
      if (!empty($cartItemIdsToRemove)) {
        $this->ticketRepo->removeFacultyCartItemsByIds($facultyId, $cartItemIdsToRemove);
      }

      $_SESSION['last_ticket_code'] = $transactionCode;
      $qrPath = $this->generateQr($transactionCode);

      $this->ticketRepo->commit();

      echo json_encode([
        'success' => true,
        'message' => $message,
        'ticket_code' => $transactionCode,
        'qrPath' => $qrPath
      ]);
      exit;
    } catch (\Throwable $e) {
      $this->ticketRepo->rollback();
      error_log("Checkout Error (Faculty): " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
      http_response_code(500);
      echo json_encode([
        'success' => false,
        'message' => 'Checkout failed: ' . $e->getMessage()
      ]);
      exit;
    }
  }

  public function show(string $transactionCode = null)
  {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      header("Location: " . BASE_URL ."/login");
      exit;
    }

    $facultyId = $this->ticketRepo->getFacultyIdByUserId((int)$userId);
    if (!$facultyId) {
      $this->view("errors/no_faculty_record", ["title" => "Error: No Faculty Record"], false);
      exit;
    }

    $this->ticketRepo->expireOldPendingTransactionsFaculty();

    $facultyDetails = $this->ticketRepo->getFacultyInfo($facultyId);

    $facultyInfo = [
      'faculty_id' => $facultyDetails['faculty_id'] ?? null,
      'name' => $this->getFullName($facultyDetails),
      'department' => $facultyDetails['department'] ?? 'N/A'
    ];

    $transactionData = null;
    $items = [];
    $qrPath = null;
    $viewMessage = null;
    $viewError = null;
    $isExpired = false;
    $isBorrowed = false;

    try {
      if (empty($transactionCode)) {
        $transactionCode = $_SESSION['last_ticket_code'] ?? null;
      }

      if (empty($transactionCode)) {
        $latestTransaction = $this->ticketRepo->getLatestTransactionByFacultyId($facultyId);
        if ($latestTransaction) {
          $transactionCode = $latestTransaction['transaction_code'] ?? null;
        }
      }

      if ($transactionCode) {
        $transaction = $this->ticketRepo->getTransactionByCode($transactionCode);

        if ($transaction && isset($transaction['faculty_id']) && $transaction['faculty_id'] == $facultyId) {
          $transactionData = $transaction;
          $items = $this->ticketRepo->getTransactionItems($transactionData['transaction_id']); // FIX 2b: Use $items

          $status = strtolower($transactionData['status']);
          if ($status === 'expired') {
            $isExpired = true;
          } elseif ($status === 'borrowed') {
            $isBorrowed = true;
          }

          if ($status === 'pending' && strtotime($transactionData['expires_at'] ?? '9999-01-01') <= time()) {
            $isExpired = true;
          }

          $facultyDetails = $this->ticketRepo->getFacultyInfo($transactionData['faculty_id']);

          if ($facultyDetails) {
            $facultyInfo['name'] = $this->getFullName($facultyDetails);
            $facultyInfo['department'] = $facultyDetails['department'] ?? 'N/A';
          }

          if (!$isExpired && !$isBorrowed) {
            $qrPath = $this->generateQr($transactionData['transaction_code']);
            if (empty($qrPath)) {
              $viewError = "Could not generate the QR code image for this ticket.";
            }
          }

          if ($isExpired || $isBorrowed) {
            unset($_SESSION['last_ticket_code']);
            $qrFile = __DIR__ . "/../../public/qrcodes/{$transactionCode}.png";
            if (file_exists($qrFile)) unlink($qrFile);
            $qrPath = null;
          }
        } else {
          unset($_SESSION['last_ticket_code']);
          $viewError = "The requested borrowing ticket was not found or is invalid.";
        }
      } else {
        $viewMessage = "You do not currently have an active borrowing ticket.";
      }
    } catch (\Throwable $e) {
      error_log("ERROR in FacultyTicketController show(): " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
      $viewError = "An unexpected error occurred while loading your ticket details.";
    }

    if ($isExpired) {
      $viewMessage = "Your borrowing ticket has expired.";
    } elseif ($isBorrowed) {
      $viewMessage = "This ticket has been successfully processed.";
    }

    if ($isExpired || $isBorrowed) {
      $transactionData['transaction_code'] = null;
    }

    $viewData = [
      "title" => "Faculty QR Borrowing Ticket",
      "currentPage" => "qrBorrowingTicket",
      "transaction_id" => $transactionData['transaction_id'] ?? null,
      "transaction_code" => $transactionData['transaction_code'] ?? null,
      "items" => $items,
      "qrPath" => $qrPath,
      "faculty" => $facultyInfo,
      "generated_at" => $transactionData['generated_at'] ?? null,
      "expires_at" => $transactionData['expires_at'] ?? null,
      "message" => $viewMessage,
      "error_message" => $viewError,
      "isExpired" => $isExpired,
      "isBorrowed" => $isBorrowed,
    ];

    $this->view("faculty/qrBorrowingTicket", $viewData);
  }

  public function checkStatus()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();
    header('Content-Type: application/json');

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized']);
      exit;
    }

    $facultyId = $this->ticketRepo->getFacultyIdByUserId((int)$userId);
    if (!$facultyId) {
      echo json_encode(['success' => false, 'message' => 'No faculty record found.']);
      exit;
    }

    $this->ticketRepo->expireOldPendingTransactionsFaculty();

    $pendingTransaction = $this->ticketRepo->getPendingTransactionByFacultyId($facultyId);

    if ($pendingTransaction) {
      $status = $pendingTransaction['status'];

      if ($status === 'pending' && strtotime($pendingTransaction['expires_at'] ?? '9999-01-01') <= time()) {
        $status = 'expired';
      }

      echo  json_encode([
        'success' => true,
        'status' => $status,
        'transaction_code' => $pendingTransaction['transaction_code'],
        'generated_at' => $pendingTransaction['generated_at'],
        'expires_at' => $pendingTransaction['expires_at']
      ]);
    } else {
      $borrowedTransaction = $this->ticketRepo->getBorrowedTransactionByFacultyId($facultyId);
      if ($borrowedTransaction) {
        echo json_encode([
          'success' => true,
          'status' => 'borrowed',
          'transaction_code' => $borrowedTransaction['transaction_code'],
          'due_date' => $borrowedTransaction['due_date']
        ]);
      } else {
        echo json_encode([
          'success' => true,
          'status' => 'none'
        ]);
      }
    }
  }
}
