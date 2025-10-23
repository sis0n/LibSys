<?php

namespace App\Repositories;

use App\Core\Database;

class QRScannerRepository
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getStudentByTransactionCode(string $code)
  {
    $stmt = $this->db->prepare("
        SELECT s.student_id, s.student_number, s.course, s.year_level, s.section,
               bt.transaction_code, bt.borrowed_at, bt.due_date, bt.status
        FROM borrow_transactions bt
        JOIN students s ON bt.student_id = s.student_id
        WHERE LOWER(TRIM(bt.transaction_code)) = LOWER(:code) 
        LIMIT 1
    ");
    $stmt->execute(['code' => trim($code)]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function getTransactionItems(string $code)
  {
    $stmt = $this->db->prepare("
        SELECT b.title, b.author, b.accession_number, b.call_number, b.book_isbn, b.book_id
        FROM borrow_transaction_items bti
        JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
        JOIN books b ON bti.book_id = b.book_id
        WHERE LOWER(TRIM(bt.transaction_code)) = LOWER(:code) 
    ");
    $stmt->execute(['code' => trim($code)]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function saveBorrowing(array $data)
  {
    try {
      $this->db->beginTransaction();

      $stmt = $this->db->prepare("
                INSERT INTO borrow_transactions (student_id, transaction_code, borrowed_at, due_date, status)
                VALUES (:student_id, :transaction_code, NOW(), :due_date, 'pending')
            ");
      $stmt->execute([
        'student_id' => $data['student_id'],
        'transaction_code' => $data['transaction_code'],
        'due_date' => $data['due_date']
      ]);

      $transactionId = $this->db->lastInsertId();

      $stmtItem = $this->db->prepare("
                INSERT INTO borrow_transaction_items (transaction_id, book_id, status)
                VALUES (:transaction_id, :book_id, 'pending')
            ");

      foreach ($data['book_ids'] as $bookId) {
        $stmtItem->execute([
          'transaction_id' => $transactionId,
          'book_id' => $bookId
        ]);
      }

      $this->db->commit();
      return $transactionId;
    } catch (\Exception $e) {
      $this->db->rollBack();
      error_log("Save Borrowing Error: " . $e->getMessage());
      return 0;
    }
  }

  public function processBorrowing(string $transactionCode)
  {
    try {
      $this->db->beginTransaction();

      $stmt = $this->db->prepare("
                UPDATE borrow_transactions SET status = 'borrowed', borrowed_at = NOW() 
                WHERE transaction_code = :code AND status = 'pending'
            ");
      $stmt->execute(['code' => $transactionCode]);

      $items = $this->getTransactionItems($transactionCode);

      $stmtBook = $this->db->prepare("UPDATE books SET availability = 'borrowed' WHERE book_id = :book_id");
      $stmtItemStatus = $this->db->prepare("
                UPDATE borrow_transaction_items SET status = 'borrowed' 
                WHERE book_id = :book_id AND transaction_id = (SELECT transaction_id FROM borrow_transactions WHERE transaction_code = :code)
            ");

      foreach ($items as $item) {
        $stmtBook->execute(['book_id' => $item['book_id']]);
        $stmtItemStatus->execute(['book_id' => $item['book_id'], 'code' => $transactionCode]);
      }

      $this->db->commit();
      return true;
    } catch (\Exception $e) {
      $this->db->rollBack();
      error_log("Process Borrowing Error: " . $e->getMessage());
      return false;
    }
  }


  public function processReturning(string $transactionCode)
  {
    try {
      $this->db->beginTransaction();

      $stmt = $this->db->prepare("
                UPDATE borrow_transactions SET status = 'returned' 
                WHERE transaction_code = :code AND status = 'borrowed'
            ");
      $stmt->execute(['code' => $transactionCode]);

      $items = $this->getTransactionItems($transactionCode);

      $stmtItem = $this->db->prepare("
                UPDATE borrow_transaction_items SET status = 'returned', returned_at = NOW() 
                WHERE transaction_id = (SELECT transaction_id FROM borrow_transactions WHERE transaction_code = :code) AND status = 'borrowed'
            ");
      $stmtItem->execute(['code' => $transactionCode]);

      $stmtBook = $this->db->prepare("UPDATE books SET availability = 'available' WHERE book_id = :book_id");

      foreach ($items as $item) {
        $stmtBook->execute(['book_id' => $item['book_id']]);
      }

      $this->db->commit();
      return true;
    } catch (\Exception $e) {
      $this->db->rollBack();
      error_log("Process Returning Error: " . $e->getMessage());
      return false;
    }
  }

  public function getTransactionHistory(?string $search = null, ?string $status = null, ?string $date = null)
  {
    $query = "
            SELECT 
                s.student_number, 
                s.student_id, 
                bt.transaction_code, 
                bt.borrowed_at, 
                bt.status,
                MAX(bti.returned_at) AS returned_at,
                COUNT(bti.book_id) AS items_borrowed
            FROM borrow_transactions bt
            JOIN students s ON bt.student_id = s.student_id
            JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE 1=1
            AND bt.status IN ('borrowed', 'returned', 'pending')
        ";

    $params = [];

    if ($search) {
      $query .= " AND (s.student_number LIKE :search OR s.student_id LIKE :search OR bt.transaction_code LIKE :search)";
      $params['search'] = "%$search%";
    }

    if ($status && $status !== 'All Status') {
      $query .= " AND bt.status = :status";
      $params['status'] = strtolower($status);
    }

    if ($date) {
      $query .= " AND (DATE(bt.borrowed_at) = :date OR DATE(MAX(bti.returned_at)) = :date)";
      $params['date'] = $date;
    }

    $query .= " GROUP BY bt.transaction_id ORDER BY bt.borrowed_at DESC";

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getAllTransactions()
  {
    $stmt = $this->db->query("SELECT transaction_code, status FROM borrow_transactions");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
