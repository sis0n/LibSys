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

  public function getDueSoonAndOverdue(): ?array
  {
    try {
      // --- Near Due ---
      $stmtNearDue = $this->db->prepare("
            SELECT * FROM (
                -- Students
                SELECT 
                    'student' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    s.student_number AS id_number,
                    s.course,
                    s.year_level,
                    s.section,
                    s.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                JOIN students s ON bt.student_id = s.student_id
                JOIN users u ON s.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND DATE(bt.due_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)

                UNION ALL

                -- Faculty
                SELECT 
                    'faculty' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    f.faculty_id AS id_number,
                    f.department,
                    NULL AS year_level,
                    NULL AS section,
                    f.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN users u ON f.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND bt.faculty_id IS NOT NULL
                  AND DATE(bt.due_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)

                UNION ALL

                -- Staff
                SELECT 
                    'staff' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    st.staff_id AS id_number,
                    st.position AS department,
                    NULL AS year_level,
                    NULL AS section,
                    st.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN staff st ON bt.staff_id = st.staff_id
                LEFT JOIN users u ON st.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND bt.staff_id IS NOT NULL
                  AND DATE(bt.due_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ) AS combined
            ORDER BY due_date ASC
        ");
      $stmtNearDue->execute();
      $nearDue = $stmtNearDue->fetchAll(PDO::FETCH_ASSOC);

      // --- Overdue ---
      $stmtOverdue = $this->db->prepare("
            SELECT * FROM (
                -- Students
                SELECT 
                    'student' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    s.student_number AS id_number,
                    s.course,
                    s.year_level,
                    s.section,
                    s.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                JOIN students s ON bt.student_id = s.student_id
                JOIN users u ON s.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND DATE(bt.due_date) < CURDATE()

                UNION ALL

                -- Faculty
                SELECT 
                    'faculty' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    f.faculty_id AS id_number,
                    f.department,
                    NULL AS year_level,
                    NULL AS section,
                    f.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN users u ON f.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND bt.faculty_id IS NOT NULL
                  AND DATE(bt.due_date) < CURDATE()

                UNION ALL

                -- Staff
                SELECT 
                    'staff' AS borrower_type,
                    u.first_name,
                    u.last_name,
                    st.staff_id AS id_number,
                    st.position AS department,
                    NULL AS year_level,
                    NULL AS section,
                    st.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN staff st ON bt.staff_id = st.staff_id
                LEFT JOIN users u ON st.user_id = u.user_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed' 
                  AND bt.staff_id IS NOT NULL
                  AND DATE(bt.due_date) < CURDATE()
            ) AS combined
            ORDER BY due_date ASC
        ");
      $stmtOverdue->execute();
      $overdue = $stmtOverdue->fetchAll(PDO::FETCH_ASSOC);

      return [
        'nearDue' => array_map([$this, 'formatTableData'], $nearDue),
        'overdue' => array_map([$this, 'formatTableData'], $overdue)
      ];
    } catch (PDOException $e) {
      http_response_code(500);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      exit;
    }
  }




  public function findBookByAccession($accessionNumber): ?array
  {
    try {
      $stmtBook = $this->db->prepare("SELECT * FROM books WHERE accession_number = ?");
      $stmtBook->execute([$accessionNumber]);
      $book = $stmtBook->fetch(PDO::FETCH_ASSOC);

      if (!$book) return ['status' => 'not_found'];

      if ($book['availability'] === 'available') return ['status' => 'available', 'details' => $book];

      $stmtBorrower = $this->db->prepare("
                SELECT bti.item_id, bt.borrowed_at, bt.due_date, u.first_name, u.last_name,
                       s.student_number AS student_id, s.course, s.year_level, s.section,
                       f.faculty_id AS faculty_id, f.department AS faculty_dept,
                       st.staff_id AS staff_id, st.position AS department,
                       u.email, COALESCE(s.contact, f.contact, st.contact) AS contact_number
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN students s ON bt.student_id = s.student_id
                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN staff st ON bt.staff_id = st.staff_id
                JOIN users u ON u.user_id = COALESCE(s.user_id, f.user_id, st.user_id)
                WHERE bti.book_id = ? AND bti.status = 'borrowed'
                LIMIT 1
            ");
      $stmtBorrower->execute([$book['book_id']]);
      $borrowerInfo = $stmtBorrower->fetch(PDO::FETCH_ASSOC);

      if ($borrowerInfo) {
        $details = array_merge($book, $borrowerInfo);
        return ['status' => 'borrowed', 'details' => $this->formatModalData($details)];
      }

      return ['status' => 'available', 'details' => $book];
    } catch (PDOException $e) {
      error_log('[ReturningRepository::findBookByAccession] ' . $e->getMessage());
      return null;
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

  private function formatTableData($row)
  {
    $borrowerType = $row['borrower_type'] ?? 'student';

    $courseOrDept = match ($borrowerType) {
      'student' => ($row['course'] ?? 'N/A') . ' - ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'faculty' => $row['department'] ?? 'N/A',
      'staff' => $row['department'] ?? 'N/A',
      default => 'N/A'
    };

    return [
      'borrower_type' => $borrowerType,
      'user_name' => ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''), 
      'user_id' => $row['id_number'] ?? 'N/A', 
      'department_or_course' => $courseOrDept, 
      'item_borrowed' => $row['item_borrowed'],
      'date_borrowed' => (new \DateTime($row['borrowed_at']))->format('Y-m-d'),
      'due_date' => (new \DateTime($row['due_date']))->format('Y-m-d'),
      'contact' => $row['contact_number'] ?? 'N/A'
    ];
  }


  private function formatModalData($row)
  {
    $borrowerType = $row['student_id'] ? 'student' : ($row['faculty_id'] ? 'faculty' : 'staff');
    $courseOrDept = match ($borrowerType) {
      'student' => ($row['course'] ?? 'N/A') . ' - ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'faculty' => $row['faculty_dept'] ?? 'N/A',
      'staff' => $row['staff_dept'] ?? 'N/A',
      default => 'N/A'
    };

    return [
      'title' => $row['title'],
      'author' => $row['author'] ?? 'N/A',
      'isbn' => $row['book_isbn'] ?? 'N/A',
      'accession_number' => $row['accession_number'] ?? 'N/A',
      'call_number' => $row['call_number'] ?? 'N/A',
      'status' => 'borrowed',
      'borrowing_id' => $row['item_id'],
      'borrower_type' => $borrowerType,
      'name' => ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''),
      'id_number' => $row['student_id'] ?? $row['faculty_id'] ?? $row['staff_id'] ?? 'N/A',
      'course_or_department' => $courseOrDept,
      'email' => $row['email'] ?? 'N/A',
      'contact' => $row['contact_number'] ?? 'N/A',
      'date_borrowed' => (new \DateTime($row['borrowed_at']))->format('Y-m-d H:i'),
      'due_date' => (new \DateTime($row['due_date']))->format('Y-m-d H:i')
    ];
  }
}
