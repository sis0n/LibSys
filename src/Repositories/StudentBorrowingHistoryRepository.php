<?php

namespace App\Repositories;

use App\Core\Database;

class StudentBorrowingHistoryRepository
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getBorrowingStats(int $userId): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            COUNT(bti.item_id) AS total_borrowed,
            SUM(CASE WHEN bti.status = 'borrowed' THEN 1 ELSE 0 END) AS currently_borrowed,
            SUM(CASE WHEN bti.status = 'returned' THEN 1 ELSE 0 END) AS total_returned,
            SUM(CASE WHEN bti.status = 'borrowed' AND bt.due_date < NOW() THEN 1 ELSE 0 END) AS total_overdue
        FROM borrow_transactions bt
        JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
        JOIN students s ON bt.student_id = s.student_id
        WHERE s.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $userId]);
    $stats = $stmt->fetch(\PDO::FETCH_ASSOC);

    return [
      'total_borrowed' => (int)($stats['total_borrowed'] ?? 0),
      'currently_borrowed' => (int)($stats['currently_borrowed'] ?? 0),
      'total_returned' => (int)($stats['total_returned'] ?? 0),
      'total_overdue' => (int)($stats['total_overdue'] ?? 0)
    ];
  }

  public function getDetailedHistory(int $userId): array
  {
    $stmt = $this->db->prepare("
        SELECT 
            bt.transaction_id, 
            bti.item_id, 
            b.title, 
            b.author, 
            bt.borrowed_at, 
            bt.due_date, 
            bti.returned_at, 
            bti.status,
            CONCAT(borrower.first_name, ' ', borrower.last_name) AS borrower_name,
            COALESCE(CONCAT(staff.first_name, ' ', staff.last_name), 'N/A') AS staff_name
        FROM borrow_transactions bt
        JOIN borrow_transaction_items bti 
            ON bt.transaction_id = bti.transaction_id
        JOIN books b 
            ON bti.book_id = b.book_id
        JOIN students s 
            ON bt.student_id = s.student_id
        LEFT JOIN users borrower 
            ON s.user_id = borrower.user_id
        LEFT JOIN users staff 
            ON bt.staff_id = staff.user_id
        WHERE s.user_id = :uid
        ORDER BY bt.borrowed_at DESC
    ");
    $stmt->execute(['uid' => $userId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
