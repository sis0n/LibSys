<?php

namespace App\Controllers;

use App\Repositories\QRScannerRepository;
use App\Core\Controller;

class QRScannerController extends Controller
{
  protected $qrScannerRepository;
  const MAX_BORROW_LIMIT = 5;

  public function __construct()
  {
    $this->qrScannerRepository = new QRScannerRepository();
  }

  private function processTicketLookup(string $transactionCode)
  {
    if (empty($transactionCode)) {
      return ['success' => false, 'message' => 'Please enter a ticket code.'];
    }

    $transaction = $this->qrScannerRepository->getStudentByTransactionCode($transactionCode);

    if (!$transaction) {
      return ['success' => false, 'message' => 'Invalid ticket code or transaction not found.'];
    }

    if (strtolower($transaction['status']) === 'borrowed' || strtolower($transaction['status']) === 'returned') {
      return ['success' => false, 'message' => 'This ticket is already processed. Please use the Return Scanner.'];
    }

    $currentBorrowed = $this->qrScannerRepository->getStudentBorrowedCount(
      (int) $transaction['student_id'],
      $transactionCode
    );

    $itemsInTicket = count($this->qrScannerRepository->getTransactionItems($transactionCode));
    $projectedTotal = $currentBorrowed + $itemsInTicket;

    if ($projectedTotal > self::MAX_BORROW_LIMIT) {
      return ['success' => false, 'message' => "Borrowing limit exceeded. Student currently has {$currentBorrowed} items. Cannot borrow {$itemsInTicket} more (Limit: " . self::MAX_BORROW_LIMIT . " books)."];
    }

    $items = $this->qrScannerRepository->getTransactionItems($transactionCode);

    $middleInitial = !empty($transaction['middle_name']) ? strtoupper(substr($transaction['middle_name'], 0, 1)) . '. ' : '';
    $suffix = !empty($transaction['suffix']) ? ' ' . $transaction['suffix'] : '';
    $fullName = trim("{$transaction['first_name']} {$middleInitial}{$transaction['last_name']}{$suffix}");


    $responseData = [
      'student' => [
        'name' => $fullName,
        'id' => $transaction['student_number'],
        'course' => $transaction['course'],
        'yearsection' => $transaction['year_level'] . '-' . $transaction['section'],
        'profilePicture' => $transaction['profile_picture']
      ],
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

    $transaction = $this->qrScannerRepository->getStudentByTransactionCode($transactionCode);

    if (!$transaction || strtolower($transaction['status']) !== 'pending') {
      echo json_encode(['success' => false, 'message' => 'Transaction is not in PENDING state.']);
      return;
    }

    $currentBorrowed = $this->qrScannerRepository->getStudentBorrowedCount(
      (int) $transaction['student_id'],
      $transactionCode
    );

    if ($currentBorrowed >= self::MAX_BORROW_LIMIT) {
      echo json_encode(['success' => false, 'message' => "Borrowing limit check failed. Student is already borrowing the maximum amount (Limit: " . self::MAX_BORROW_LIMIT . " books)."]);
      return;
    }

    $result = $this->qrScannerRepository->processBorrowing($transactionCode);

    if ($result) {
      unset($_SESSION['last_scanned_ticket']);
      echo json_encode(['success' => true, 'message' => 'Borrow transaction successfully processed. Due date set to 1 week.']);
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
        'studentName' => $fullName,
        'studentNumber' => $h['student_number'],
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
