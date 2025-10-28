<?php

namespace App\Controllers;

use App\Repositories\QRScannerRepository;
use App\Core\Controller;

class QRScannerController extends Controller
{
  protected $qrScannerRepository;
  const MAX_BORROW_LIMIT_STUDENT = 5;
  const MAX_BORROW_LIMIT_FACULTY = 10; // New limit for faculty

  public function __construct()
  {
    $this->qrScannerRepository = new QRScannerRepository();
  }

  private function processTicketLookup(string $transactionCode)
  {
    if (empty($transactionCode)) {
      return ['success' => false, 'message' => 'Please enter a ticket code.'];
    }

    $this->qrScannerRepository->expireOldPendingTransactions();

    $transaction = $this->qrScannerRepository->getTransactionDetailsByCode($transactionCode);

    if (!$transaction) {
      return ['success' => false, 'message' => 'Invalid ticket code or transaction not found.'];
    }

    if (strtolower($transaction['status']) === 'expired') {
      return ['success' => false, 'message' => 'QR Code Ticket Expired'];
    }

    if (strtolower($transaction['status']) === 'borrowed' || strtolower($transaction['status']) === 'returned') {
      return ['success' => false, 'message' => 'This ticket is already processed. Please use the Return Scanner.'];
    }

    // --- Determine User Type and Limits ---
    $isFaculty = !empty($transaction['faculty_id']);
    $userType = $isFaculty ? 'faculty' : 'student';

    $idColumn = $isFaculty ? 'faculty_id' : 'student_id';
    $idValue = (int) $transaction[$idColumn];
    $MAX_LIMIT = $isFaculty ? self::MAX_BORROW_LIMIT_FACULTY : self::MAX_BORROW_LIMIT_STUDENT;

    // Use the new dynamic repository method
    $currentBorrowed = $this->qrScannerRepository->getBorrowedCount(
      $idColumn,
      $idValue,
      $transactionCode
    );

    $itemsInTicket = count($this->qrScannerRepository->getTransactionItems($transactionCode));
    $projectedTotal = $currentBorrowed + $itemsInTicket;

    if ($projectedTotal > $MAX_LIMIT) {
      $messagePrefix = $isFaculty ? 'Faculty' : 'Student';
      return ['success' => false, 'message' => "Borrowing limit exceeded for {$userType}. {$messagePrefix} currently has {$currentBorrowed} items. Cannot borrow {$itemsInTicket} more (Limit: " . $MAX_LIMIT . " books)."];
    }

    $items = $this->qrScannerRepository->getTransactionItems($transactionCode);

    // --- Build User Info based on type ---
    $middleInitial = !empty($transaction['middle_name']) ? strtoupper(substr($transaction['middle_name'], 0, 1)) . '. ' : '';
    $suffix = !empty($transaction['suffix']) ? ' ' . $transaction['suffix'] : '';
    $fullName = trim("{$transaction['first_name']} {$middleInitial}{$transaction['last_name']}{$suffix}");

    $userInfo = [
      'type' => $isFaculty ? 'Faculty' : 'Student',
      'name' => $fullName,
      'profilePicture' => $transaction['profile_picture']
    ];

    if ($isFaculty) {
      $userInfo['id'] = $transaction['f_id']; // Use aliased faculty ID
      $userInfo['department'] = $transaction['department'];
    } else {
      $userInfo['id'] = $transaction['student_number'];
      $userInfo['course'] = $transaction['course'];
      $userInfo['yearsection'] = $transaction['year_level'] . '-' . $transaction['section'];
    }

    $responseData = [
      'user' => $userInfo, // Changed 'student' to 'user' for unified data
      'ticket' => [
        'id' => $transaction['transaction_code'],
        'generated' => $transaction['borrowed_at'],
        'status' => ucfirst($transaction['status']),
        'dueDate' => $transaction['due_date']
      ],
      'items' => array_map(function ($item) {
        return [
          'title' => $item['title'],
          'author' => $item['author'],
          'accessionNumber' => $item['accession_number'],
          'callNumber' => $item['call_number'],
          'isbn' => $item['book_isbn'],
          'bookId' => $item['book_id']
        ];
      }, $items)
    ];

    return ['success' => true, 'data' => $responseData];
  }

  public function scan()
  {
    header('Content-Type: application/json');
    $transactionCode = trim($_POST['transaction_code'] ?? '');

    $result = $this->processTicketLookup($transactionCode);

    if ($result['success']) {
      $_SESSION['last_scanned_ticket'] = $transactionCode;
    } else {
      unset($_SESSION['last_scanned_ticket']);
    }

    echo json_encode($result);
  }

  public function lookup()
  {
    header('Content-Type: application/json');

    $transactionCode = trim($_SESSION['last_scanned_ticket'] ?? '');

    $result = $this->processTicketLookup($transactionCode);

    echo json_encode($result);
  }

  public function borrowTransaction()
  {
    header('Content-Type: application/json');

    $transactionCode = trim($_POST['transaction_code'] ?? '');
    $staffId = $_SESSION['user_id'] ?? null;

    if (!$staffId) {
      echo json_encode(['success' => false, 'message' => 'Unauthorized access. Staff not logged in.']);
      return;
    }

    $transaction = $this->qrScannerRepository->getTransactionDetailsByCode($transactionCode); // Using new unified method

    if (!$transaction || strtolower($transaction['status']) !== 'pending') {
      echo json_encode(['success' => false, 'message' => 'Transaction is not in PENDING state.']);
      return;
    }

    // --- Determine User Type and Limits ---
    $isFaculty = !empty($transaction['faculty_id']);
    $userType = $isFaculty ? 'faculty' : 'student';

    $idColumn = $isFaculty ? 'faculty_id' : 'student_id';
    $idValue = (int) $transaction[$idColumn];
    $MAX_LIMIT = $isFaculty ? self::MAX_BORROW_LIMIT_FACULTY : self::MAX_BORROW_LIMIT_STUDENT;


    $currentBorrowed = $this->qrScannerRepository->getBorrowedCount( // Using new dynamic method
      $idColumn,
      $idValue,
      $transactionCode
    );

    if ($currentBorrowed >= $MAX_LIMIT) {
      echo json_encode(['success' => false, 'message' => "Borrowing limit check failed. The {$userType} already has maximum borrowed books."]);
      return;
    }

    $result = $this->qrScannerRepository->processBorrowing($transactionCode, $staffId);

    if ($result) {
      unset($_SESSION['last_scanned_ticket']);
      echo json_encode(['success' => true, 'message' => 'Borrow transaction successfully processed. Staff ID recorded.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to finalize borrow transaction.']);
    }
  }


  public function history()
  {
    header('Content-Type: application/json');

    $search = $_GET['search'] ?? null;
    $status = $_GET['status'] ?? 'All Status';
    $date = $_GET['date'] ?? null;

    $history = $this->qrScannerRepository->getTransactionHistory($search, $status, $date);

    $formattedHistory = array_map(function ($h) {

      $isFaculty = !empty($h['faculty_id']);

      $middleInitial = !empty($h['middle_name']) ? strtoupper(substr($h['middle_name'], 0, 1)) . '. ' : '';
      $suffix = !empty($h['suffix']) ? ' ' . $h['suffix'] : '';
      $fullName = trim("{$h['first_name']} {$middleInitial}{$h['last_name']}{$suffix}");

      $borrowedDateTime = $h['borrowed_at']
        ? date('M d, Y h:i A', strtotime($h['borrowed_at']))
        : 'N/A';

      $returnedDateTime = $h['returned_at']
        ? date('M d, Y h:i A', strtotime($h['returned_at']))
        : 'Not yet returned';

      return [
        'userName' => $fullName,
        'userId' => $isFaculty ? $h['faculty_id'] : $h['user_identifier'], // user_identifier holds student_number or faculty_id
        'userType' => $isFaculty ? 'Faculty' : 'Student',
        'itemsBorrowed' => (int) $h['items_borrowed'],
        'status' => ucfirst($h['status']),
        'borrowedDateTime' => $borrowedDateTime,
        'returnedDateTime' => $returnedDateTime
      ];
    }, $history);

    echo json_encode([
      'success' => true,
      'transactions' => $formattedHistory
    ]);
  }
}
