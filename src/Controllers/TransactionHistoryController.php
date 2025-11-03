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

    // Parameters from the frontend, with defaults
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $status = strtolower($_GET['status'] ?? 'all');
    $date = $_GET['date'] ?? null;
    $search = $_GET['search'] ?? null;
    $offset = ($page - 1) * $limit;

    // Fetch total records based on filters
    $totalRecords = $this->repo->countTransactions($status, $date, $search);

    // Fetch paginated data
    $transactions = $this->repo->getPaginatedTransactions($status, $date, $search, $limit, $offset);

    // Calculate total pages
    $totalPages = ceil($totalRecords / $limit);

    // This structure matches the User Management pagination response
    echo json_encode([
        'success' => true,
        'data' => $transactions,
        'totalRecords' => $totalRecords,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
  }
}
