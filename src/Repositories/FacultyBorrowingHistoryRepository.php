<?php

namespace App\Repositories;

use App\Core\Database;

class FacultyBorrowingHistoryRepository
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
            JOIN faculty f ON bt.faculty_id = f.faculty_id
            WHERE f.user_id = :user_id
            AND bt.status != 'pending'
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
                CONCAT(faculty_user.first_name, ' ', faculty_user.last_name) AS faculty_name,
                COALESCE(CONCAT(librarian.first_name, ' ', librarian.last_name), 'N/A') AS librarian_name
            FROM borrow_transactions bt
            JOIN borrow_transaction_items bti 
                ON bt.transaction_id = bti.transaction_id
            JOIN books b 
                ON bti.book_id = b.book_id
            JOIN faculty f 
                ON bt.faculty_id = f.faculty_id
            LEFT JOIN users faculty_user 
                ON f.user_id = faculty_user.user_id
            LEFT JOIN users librarian 
                ON bt.librarian_id = librarian.user_id
            WHERE f.user_id = :uid
            AND bt.status != 'pending'
            ORDER BY bt.borrowed_at DESC
        ");
    $stmt->execute(['uid' => $userId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
