<?php

namespace App\Controllers;

use App\Repositories\FacultyBorrowingHistoryRepository;
use App\Core\Controller;

class FacultyBorrowingHistoryController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new FacultyBorrowingHistoryRepository();
    }

    // --- Pagination Start ---
    public function fetchPaginatedBorrowingHistory()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_data']['user_id'] ?? 0;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
        $offset = ($page - 1) * $limit;

        $history = $this->repo->getPaginatedBorrowingHistory($userId, $limit, $offset);
        $totalRecords = $this->repo->countBorrowingHistory($userId);
        $totalPages = ceil($totalRecords / $limit);

        $formattedHistory = $this->formatHistoryRecords($history);

        echo json_encode([
            'success' => true,
            'borrowingHistory' => $formattedHistory,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalRecords' => $totalRecords
        ]);
    }

    private function formatHistoryRecords(array $history): array
    {
        return array_map(function ($record) {
            $dueDate = strtotime($record['due_date']);
            $returnedDate = $record['returned_at'] ? strtotime($record['returned_at']) : null;
            $now = time();

            $isBorrowed = $record['status'] === 'borrowed';
            $isOverdue = $isBorrowed && ($dueDate < $now);
            $statusText = ucfirst($record['status']);
            $statusColorClass = 'gray';

            if ($isOverdue) {
                $overdueDays = ceil(($now - $dueDate) / (60 * 60 * 24));
                $statusText = "Overdue (+" . $overdueDays . "d)";
                $statusColorClass = 'destructive';
            } elseif ($isBorrowed) {
                $statusColorClass = 'amber';
            } elseif ($record['status'] === 'returned') {
                $statusColorClass = 'green';
            }

            $statusBgClass = ($statusColorClass === 'destructive')
                ? 'bg-[var(--color-destructive)] text-white'
                : 'bg-[var(--color-' . $statusColorClass . '-100)] text-[var(--color-' . $statusColorClass . '-700)]';

            return [
                'id' => $record['item_id'],
                'title' => $record['title'] ?? 'N/A',
                'author' => $record['author'] ?? 'N/A',
                'borrowedDate' => $record['borrowed_at'] ? date('M d, Y', strtotime($record['borrowed_at'])) : 'N/A',
                'dueDate' => $record['due_date'] ? date('M d, Y', $dueDate) : 'N/A',
                'returnedDate' => $returnedDate ? date('M d, Y', $returnedDate) : 'Not returned',
                'librarianName' => $record['librarian_name'] ?? 'N/A',
                'statusText' => $statusText,
                'statusBgClass' => $statusBgClass,
                'isOverdue' => $isOverdue
            ];
        }, $history);
    }
    // --- Pagination End ---

    // --- Stats Start ---
    public function fetchStats()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_data']['user_id'] ?? 0;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $stats = $this->repo->getBorrowingStats($userId);

        echo json_encode([
            'success' => true,
            'stats' => $stats
        ]);
    }
    // --- Stats End ---
}
