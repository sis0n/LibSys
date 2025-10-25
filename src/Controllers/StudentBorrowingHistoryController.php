<?php

namespace App\Controllers;

use App\Repositories\StudentBorrowingHistoryRepository;
use App\Core\Controller;

class StudentBorrowingHistoryController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new StudentBorrowingHistoryRepository();
    }

    public function fetchHistory()
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['user_data']['user_id'] ?? 0;

        if (!$userId || $userId == 0) {
            echo json_encode(['success' => false, 'message' => 'Error: User session is invalid. Please log in again.']);
            return;
        }

        $stats = $this->repo->getBorrowingStats((int)$userId);
        $history = $this->repo->getDetailedHistory((int)$userId);

        $formattedHistory = array_map(function ($record) {

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
                'staffName' => $record['staff_name'] ?? 'N/A',
                'statusText' => $statusText,
                'statusBgClass' => $statusBgClass,
                'isOverdue' => $isOverdue
            ];
        }, $history);

        echo json_encode([
            'success' => true,
            'stats' => $stats,
            'history' => $formattedHistory
        ]);
    }
}
