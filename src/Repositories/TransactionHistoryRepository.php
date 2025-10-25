<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TransactionHistoryRepository
{
  protected PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAllTransactions(?string $date = null): array
  {
    $sql = "
            SELECT 
                bt.transaction_id,
                bt.transaction_code,
                bt.borrowed_at,
                bt.due_date,
                bt.expires_at,
                bti.returned_at,
                bt.status AS transaction_status,

                librarian.user_id AS librarian_id,
                CONCAT(librarian.first_name, ' ', COALESCE(librarian.middle_name,''), ' ', librarian.last_name) AS librarian_name,

                s.student_id,
                CONCAT(student_user.first_name, ' ', COALESCE(student_user.middle_name,''), ' ', student_user.last_name) AS studentName,
                s.student_number,
                s.course,
                s.year_level,
                s.section,

                b.book_id,
                b.title AS book_title,
                b.author AS book_author,
                b.accession_number,
                b.call_number,
                b.book_isbn,
                b.cover
            FROM borrow_transactions bt
            JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            JOIN books b ON bti.book_id = b.book_id
            JOIN students s ON bt.student_id = s.student_id
            LEFT JOIN users librarian ON bt.librarian_id = librarian.user_id
            LEFT JOIN users student_user ON s.user_id = student_user.user_id
            WHERE bt.status != 'Pending'
            " . ($date ? " AND DATE(bt.borrowed_at) = :date" : "") . "
            ORDER BY bt.borrowed_at DESC
        ";

    $stmt = $this->db->prepare($sql);
    if ($date) $stmt->bindParam(':date', $date);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTransactionsByStatus(string $status, ?string $date = null): array
  {
    // Prevent Pending from being queried
    if (strtolower($status) === 'pending') {
      return [];
    }

    $sql = "
            SELECT 
                bt.transaction_id,
                bt.transaction_code,
                bt.borrowed_at,
                bt.due_date,
                bt.expires_at,
                bti.returned_at,
                bt.status AS transaction_status,

                librarian.user_id AS librarian_id,
                CONCAT(librarian.first_name, ' ', COALESCE(librarian.middle_name,''), ' ', librarian.last_name) AS librarian_name,

                s.student_id,
                CONCAT(student_user.first_name, ' ', COALESCE(student_user.middle_name,''), ' ', student_user.last_name) AS studentName,
                s.student_number,
                s.course,
                s.year_level,
                s.section,

                b.book_id,
                b.title AS book_title,
                b.author AS book_author,
                b.accession_number,
                b.call_number,
                b.book_isbn,
                b.cover
            FROM borrow_transactions bt
            JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            JOIN books b ON bti.book_id = b.book_id
            JOIN students s ON bt.student_id = s.student_id
            LEFT JOIN users librarian ON bt.librarian_id = librarian.user_id
            LEFT JOIN users student_user ON s.user_id = student_user.user_id
            WHERE bt.status = :status
            " . ($date ? " AND DATE(bt.borrowed_at) = :date" : "") . "
            ORDER BY bt.borrowed_at DESC
        ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':status', $status);
    if ($date) $stmt->bindParam(':date', $date);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
