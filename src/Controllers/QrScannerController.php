<?php

namespace App\Controllers;

use App\Repositories\QRScannerRepository;

class QRScannerController
{
  protected $qrScannerRepository;

  public function __construct()
  {
    $this->qrScannerRepository = new QRScannerRepository();
  }

  public function scan()
  {
    header('Content-Type: application/json');

    $transactionCode = trim($_POST['transaction_code'] ?? '');

    $logMessage = "Scan Attempt: Code='{$transactionCode}', Length=".strlen($transactionCode)."\n";
    file_put_contents(__DIR__ . '/scan_debug_len.txt', $logMessage, FILE_APPEND);

    if (empty($transactionCode)) {
      echo json_encode([
        'success' => false,
        'message' => 'Please enter a ticket code.'
      ]);
      return;
    }

    $transaction = $this->qrScannerRepository->getStudentByTransactionCode($transactionCode);
    $logMessageResult = "Result: " . print_r($transaction, true) . "\n\n";
    file_put_contents(__DIR__ . '/scan_debug_len.txt', $logMessageResult, FILE_APPEND);

    if (!$transaction) {
      echo json_encode([
        'success' => false,
        'message' => 'Invalid ticket code or transaction not found.'
      ]);
      return;
    }

    if (strtolower($transaction['status']) === 'returned') {
      echo json_encode([
        'success' => false,
        'message' => 'This transaction has already been fully processed and returned.'
      ]);
      return;
    }

    $items = $this->qrScannerRepository->getTransactionItems($transactionCode);

    $responseData = [
      'student' => [
        'name' => $transaction['student_number'],
        'id' => $transaction['student_id'],
        'course' => $transaction['course'],
        'yearsection' => $transaction['year_level'] . '-' . $transaction['section']
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

    echo json_encode([
      'success' => true,
      'data' => $responseData
    ]);
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

    // Map to frontend format
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
