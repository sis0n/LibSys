<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException; // Idinagdag para sa error handling

class ReturningRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  /**
   * Kukunin ang 'Near Due' at 'Overdue' books.
   * @return array|null Returns data array on success, null on failure.
   */
  public function getDueSoonAndOverdue(): ?array
  {
    try {
      // --- Near Due (due in next 7 days) ---
      $stmtNearDue = $this->db->prepare("
            SELECT
                u.first_name,
                u.last_name,
                s.student_number AS student_id,
                s.course,
                s.year_level,
                s.section,
                s.contact AS contact_number,
                b.title AS item_borrowed,
                bt.borrowed_at AS date_borrowed,
                bt.due_date AS due_date
            FROM borrow_transaction_items bti
            JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
            JOIN students s ON bt.student_id = s.student_id
            JOIN users u ON s.user_id = u.user_id
            JOIN books b ON bti.book_id = b.book_id
            WHERE bti.status = 'borrowed'
              AND bt.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        ");
      $stmtNearDue->execute();
      $nearDue = $stmtNearDue->fetchAll(PDO::FETCH_ASSOC);

      // --- Overdue (due date passed) ---
      $stmtOverdue = $this->db->prepare("
            SELECT
                u.first_name,
                u.last_name,
                s.student_number AS student_id,
                s.course,
                s.year_level,
                s.section,
                s.contact AS contact_number,
                b.title AS item_borrowed,
                bt.borrowed_at AS date_borrowed,
                bt.due_date AS due_date
            FROM borrow_transaction_items bti
            JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
            JOIN students s ON bt.student_id = s.student_id
            JOIN users u ON s.user_id = u.user_id
            JOIN books b ON bti.book_id = b.book_id
            WHERE bti.status = 'borrowed'
              AND bt.due_date < CURDATE()
        ");
      $stmtOverdue->execute();
      $overdue = $stmtOverdue->fetchAll(PDO::FETCH_ASSOC);

      // --- Format for frontend ---
      $formattedNearDue = array_map([$this, 'formatTableData'], $nearDue);
      $formattedOverdue = array_map([$this, 'formatTableData'], $overdue);

      return ['nearDue' => $formattedNearDue, 'overdue' => $formattedOverdue];
    } catch (\PDOException $e) {
      // Temporary debug para makita sa browser
      http_response_code(500);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      exit;
    }
  }



  /**
   * Hahanapin ang libro at ang status nito (Borrowed o Available) gamit ang accession number.
   * @return array|null Returns data array on success, null on failure.
   */
  public function findBookByAccession($accessionNumber): ?array
  {
    try {
      $stmtBook = $this->db->prepare("SELECT * FROM books WHERE accession_number = ?");
      $stmtBook->execute([$accessionNumber]);
      $book = $stmtBook->fetch(PDO::FETCH_ASSOC);

      if (!$book) {
        return ['status' => 'not_found'];
      }

      if ($book['availability'] === 'available') {
        return ['status' => 'available', 'details' => $book];
      }

      $stmtBorrowing = $this->db->prepare("
    SELECT
        bti.item_id,
        bt.borrowed_at,
        bt.due_date,
        u.first_name,
        u.last_name,
        s.student_number AS student_id,
        s.course,
        s.year_level,
        s.section,
        u.email,
        s.contact AS contact_number
    FROM borrow_transaction_items bti
    JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
    JOIN students s ON bt.student_id = s.student_id
    JOIN users u ON s.user_id = u.user_id
    WHERE bti.book_id = ? AND bti.status = 'borrowed'
    LIMIT 1
");
      $stmtBorrowing->execute([$book['book_id']]);
      $borrowerInfo = $stmtBorrowing->fetch(PDO::FETCH_ASSOC);

      if ($borrowerInfo) {
        $details = array_merge($book, $borrowerInfo);
        return ['status' => 'borrowed', 'details' => $this->formatModalData($details)];
      } else {
        return ['status' => 'available', 'details' => $book];
      }
    } catch (PDOException $e) {
      error_log('[ReturningRepository::findBookByAccession] ' . $e->getMessage());
      return null; // Nag-fail ang query
    }
  }

  /**
   * I-update ang borrowing record at ibalik ang status ng libro sa 'available'.
   */
  public function markAsReturned($itemId): bool
  {
    try {
      $this->db->beginTransaction();

      $stmtGetItem = $this->db->prepare("SELECT book_id, transaction_id FROM borrow_transaction_items WHERE item_id = ? AND status = 'borrowed'");
      $stmtGetItem->execute([$itemId]);
      $itemInfo = $stmtGetItem->fetch(PDO::FETCH_ASSOC);

      if (!$itemInfo) {
        $this->db->rollBack();
        return false; // Baka na-return na
      }

      $bookId = $itemInfo['book_id'];
      $transactionId = $itemInfo['transaction_id'];

      $stmtUpdateItem = $this->db->prepare("UPDATE borrow_transaction_items SET status = 'returned', returned_at = NOW() WHERE item_id = ? AND status = 'borrowed'");
      $stmtUpdateItem->execute([$itemId]);

      $stmtUpdateBook = $this->db->prepare("UPDATE books SET availability = 'available' WHERE book_id = ?");
      $stmtUpdateBook->execute([$bookId]);

      $stmtCheckAll = $this->db->prepare("SELECT COUNT(*) as remaining FROM borrow_transaction_items WHERE transaction_id = ? AND status = 'borrowed'");
      $stmtCheckAll->execute([$transactionId]);
      $remaining = $stmtCheckAll->fetch(PDO::FETCH_ASSOC)['remaining'];

      if ($remaining == 0) {
        $stmtUpdateTrans = $this->db->prepare("UPDATE borrow_transactions SET status = 'returned' WHERE transaction_id = ?");
        $stmtUpdateTrans->execute([$transactionId]);
      }

      $this->db->commit();
      return $stmtUpdateItem->rowCount() > 0;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('[ReturningRepository::markAsReturned] ' . $e->getMessage());
      return false;
    }
  }

  /**
   * I-e-extend ang due date ng isang transaction.
   * @return string|null Ang bagong due date, or null kung failed.
   */
  public function extendDueDate($itemId, $days): ?string
  {
    try {
      $this->db->beginTransaction();

      $stmtGetTrans = $this->db->prepare("SELECT transaction_id FROM borrow_transaction_items WHERE item_id = ?");
      $stmtGetTrans->execute([$itemId]);
      $item = $stmtGetTrans->fetch(PDO::FETCH_ASSOC);

      if (!$item) {
        $this->db->rollBack();
        return null;
      }
      $transactionId = $item['transaction_id'];

      $stmtUpdate = $this->db->prepare("UPDATE borrow_transactions SET due_date = DATE_ADD(due_date, INTERVAL ? DAY) WHERE transaction_id = ? AND status = 'borrowed'");
      $stmtUpdate->execute([$days, $transactionId]);

      if ($stmtUpdate->rowCount() == 0) {
        $this->db->rollBack();
        return null; // Walang na-update
      }

      $stmtSelect = $this->db->prepare("SELECT due_date FROM borrow_transactions WHERE transaction_id = ?");
      $stmtSelect->execute([$transactionId]);
      $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

      $this->db->commit();

      $date = new \DateTime($result['due_date']);
      return $date->format('Y-m-d'); // Ibalik ang bagong date

    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('[ReturningRepository::extendDueDate] ' . $e->getMessage());
      return null;
    }
  }

  // --- Helper Functions (Same as before) ---

  private function formatTableData($row)
  {
    return [
      'student_name' => ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''),
      'student_id' => $row['student_id'] ?? 'N/A',
      'student_course' => ($row['course'] ?? 'N/A') . ' - ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'item_borrowed' => $row['item_borrowed'],
      'date_borrowed' => (new \DateTime($row['date_borrowed']))->format('Y-m-d'),
      'due_date' => (new \DateTime($row['due_date']))->format('Y-m-d'),
      'contact' => $row['contact_number'] ?? 'N/A'
    ];
  }

  private function formatModalData($row)
  {
    return [
      'title' => $row['title'],
      'author' => $row['author'] ?? 'N/A',
      'isbn' => $row['book_isbn'] ?? 'N/A',
      'accession_number' => $row['accession_number'] ?? 'N/A',
      'call_number' => $row['call_number'] ?? 'N/A',
      'status' => 'borrowed',
      'borrowing_id' => $row['item_id'],
      'student_name' => ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''),
      'student_id' => $row['student_id'] ?? 'N/A',
      'student_course' => ($row['course'] ?? 'N/A') . ' - ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'email' => $row['email'] ?? 'N/A',
      'contact' => $row['contact_number'] ?? 'N/A',
      'date_borrowed' => (new \DateTime($row['borrowed_at']))->format('Y-m-d H:i'),
      'due_date' => (new \DateTime($row['due_date']))->format('Y-m-d H:i')
    ];
  }
}
