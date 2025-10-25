<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\TransactionHistoryRepository;

class TransactionHistoryController extends Controller
{
  private TransactionHistoryRepository $repo;

  public function __construct()
  {
    $this->repo = new TransactionHistoryRepository();
  }

  public function getTransactionsJson()
  {

    header('Content-Type: application/json');

    $status = strtolower($_GET['status'] ?? 'all');
    $date   = $_GET['date'] ?? null;

    if ($status === 'pending') {
      echo json_encode([]);
      return;
    }

    if ($status === 'all') {
      $transactions = $this->repo->getAllTransactions($date);
    } else {
      $transactions = $this->repo->getTransactionsByStatus($status, $date);
    }

    echo json_encode($transactions);
  }
}
