<?php

namespace App\Controllers;

use App\Repositories\QRScannerRepository;
use App\Core\Controller;

class QRScannerController extends Controller
{
  protected $qrScannerRepository;

  public function __construct()
  {
    // Tiyakin na ang session ay started sa Controller base class o index.php
    $this->qrScannerRepository = new QRScannerRepository();
  }

  /**
   * Helper function to process ticket verification and return data structure.
   */
  private function processTicketLookup(string $transactionCode)
  {
    if (empty($transactionCode)) {
      return ['success' => false, 'message' => 'Please enter a ticket code.'];
    }

    $transaction = $this->qrScannerRepository->getStudentByTransactionCode($transactionCode);

    if (!$transaction) {
      return ['success' => false, 'message' => 'Invalid ticket code or transaction not found.'];
    }

    if (strtolower($transaction['status']) === 'returned') {
      return ['success' => false, 'message' => 'This transaction has already been fully processed and returned.'];
    }

    $items = $this->qrScannerRepository->getTransactionItems($transactionCode);

    // Construct Full Name
    $middleInitial = !empty($transaction['middle_name']) ? strtoupper(substr($transaction['middle_name'], 0, 1)) . '. ' : '';
    $suffix = !empty($transaction['suffix']) ? ' ' . $transaction['suffix'] : '';
    $fullName = trim("{$transaction['first_name']} {$middleInitial}{$transaction['last_name']}{$suffix}");


    $responseData = [
      'student' => [
        'name' => $fullName,
        'id' => $transaction['student_number'], // Student Number na ang ginamit
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

  /**
   * Scan ticket (QR or manual) - POST method for initial scan.
   */
  public function scan()
  {
    header('Content-Type: application/json');
    $transactionCode = trim($_POST['transaction_code'] ?? '');

    $result = $this->processTicketLookup($transactionCode);

    // ⭐ PERSISTENCE: I-save sa PHP Session ang code
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
      echo json_encode(['success' => false, 'message' => 'Transaction is not for borrowing or invalid.']);
      return;
    }

    $result = $this->qrScannerRepository->processBorrowing($transactionCode);

    if ($result) {
      unset($_SESSION['last_scanned_ticket']);
      echo json_encode(['success' => true, 'message' => 'Borrow transaction successfully confirmed.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to confirm borrow transaction.']);
    }
  }

  public function returnTransaction()
  {
    header('Content-Type: application/json');
    $transactionCode = trim($_POST['transaction_code'] ?? '');

    $transaction = $this->qrScannerRepository->getStudentByTransactionCode($transactionCode);

    if (!$transaction || strtolower($transaction['status']) !== 'borrowed') {
      echo json_encode(['success' => false, 'message' => 'Transaction is not for returning or invalid.']);
      return;
    }

    $result = $this->qrScannerRepository->processReturning($transactionCode);

    if ($result) {
      unset($_SESSION['last_scanned_ticket']);
      echo json_encode(['success' => true, 'message' => 'Return transaction successfully processed.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Failed to process return transaction.']);
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
      return [
        'student_number' => $h['student_number'],
        'student_id' => $h['student_id'],
        'items_borrowed' => $h['items_borrowed'],
        'status' => ucfirst($h['status']),
        'borrowed_at' => date('M d, Y h:i A', strtotime($h['borrowed_at'])),
        'returned_at' => $h['returned_at'] ? date('M d, Y h:i A', strtotime($h['returned_at'])) : 'Not yet returned'
      ];
    }, $history);

    echo json_encode([
      'success' => true,
      'transactions' => $formattedHistory
    ]);
  }
}
