<?php

namespace App\Controllers;

use App\Repositories\QRScannerRepository;
use App\Core\Controller;

class QRScannerController extends Controller
{
  protected $qrScannerRepository;
  const MAX_BORROW_LIMIT_STUDENT = 5;
  const MAX_BORROW_LIMIT_FACULTY = 10;
  const MAX_BORROW_LIMIT_STAFF = 7;

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

    if (in_array(strtolower($transaction['status']), ['expired', 'borrowed', 'returned'])) {
      return ['success' => false, 'message' => 'This ticket is already processed or expired.'];
    }

    // Determine user type
    if (!empty($transaction['faculty_id'])) {
      $userType = 'faculty';
      $idColumn = 'faculty_id';
      $idValue = (int) $transaction['faculty_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_FACULTY;
    } elseif (!empty($transaction['staff_id'])) {
      $userType = 'staff';
      $idColumn = 'staff_id';
      $idValue = (int) $transaction['staff_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_STAFF;
    } else {
      $userType = 'student';
      $idColumn = 'student_id';
      $idValue = (int) $transaction['student_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_STUDENT;
    }

    $currentBorrowed = $this->qrScannerRepository->getBorrowedCount($idColumn, $idValue, $transactionCode);
    $itemsInTicket = count($this->qrScannerRepository->getTransactionItems($transactionCode));
    $projectedTotal = $currentBorrowed + $itemsInTicket;

    if ($projectedTotal > $MAX_LIMIT) {
      return [
        'success' => false,
        'message' => ucfirst($userType) . " borrowing limit exceeded. Has {$currentBorrowed} items. Cannot borrow {$itemsInTicket} more (Limit: {$MAX_LIMIT})."
      ];
    }

    $items = $this->qrScannerRepository->getTransactionItems($transactionCode);

    // Build unified user info
    $middleInitial = !empty($transaction['middle_name']) ? strtoupper(substr($transaction['middle_name'], 0, 1)) . '. ' : '';
    $suffix = !empty($transaction['suffix']) ? ' ' . $transaction['suffix'] : '';
    $fullName = trim("{$transaction['first_name']} {$middleInitial}{$transaction['last_name']}{$suffix}");

    $userInfo = [
      'type' => $userType,
      'name' => $fullName,
      'profilePicture' => $transaction['profile_picture'] ?? null
    ];

    if ($userType === 'student') {
      $userInfo['id'] = $transaction['student_number'];
      $userInfo['course'] = $transaction['course'];
      $userInfo['yearsection'] = $transaction['year_level'] . '-' . $transaction['section'];
    } elseif ($userType === 'faculty') {
      $userInfo['id'] = $transaction['f_id'];
      $userInfo['department'] = $transaction['department'];
    } else {
      $userInfo['id'] = $transaction['st_id'];
      $userInfo['position'] = $transaction['position'];
      $userInfo['contact'] = $transaction['contact_number'];
    }

    $responseData = [
      'user' => $userInfo,
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
      echo json_encode(['success' => false, 'message' => 'Unauthorized. Staff not logged in.']);
      return;
    }

    $transaction = $this->qrScannerRepository->getTransactionDetailsByCode($transactionCode);

    if (!$transaction || strtolower($transaction['status']) !== 'pending') {
      echo json_encode(['success' => false, 'message' => 'Transaction is not in PENDING state.']);
      return;
    }

    // Determine user type and limits
    if (!empty($transaction['faculty_id'])) {
      $idColumn = 'faculty_id';
      $idValue = $transaction['faculty_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_FACULTY;
      $userType = 'faculty';
    } elseif (!empty($transaction['staff_id'])) {
      $idColumn = 'staff_id';
      $idValue = $transaction['staff_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_STAFF;
      $userType = 'staff';
    } else {
      $idColumn = 'student_id';
      $idValue = $transaction['student_id'];
      $MAX_LIMIT = self::MAX_BORROW_LIMIT_STUDENT;
      $userType = 'student';
    }

    $currentBorrowed = $this->qrScannerRepository->getBorrowedCount($idColumn, $idValue, $transactionCode);

    if ($currentBorrowed >= $MAX_LIMIT) {
      echo json_encode(['success' => false, 'message' => "Borrowing limit reached for {$userType}."]);
      return;
    }

    $success = $this->qrScannerRepository->processBorrowing($transactionCode, $staffId);

    if ($success) {
      unset($_SESSION['last_scanned_ticket']);
      echo json_encode(['success' => true, 'message' => 'Borrow transaction successfully processed.']);
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
