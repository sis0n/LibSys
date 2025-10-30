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
                LEFT JOIN students s ON bt.student_id = s.student_id
                LEFT JOIN users u ON s.user_id = u.user_id
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

                UNION ALL

                -- Guests
                SELECT
                    'guests' AS borrower_type,
                    g.first_name,
                    g.last_name,
                    g.guest_id AS id_number,
                    NULL AS department,
                    NULL AS year_level,
                    NULL AS section,
                    g.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN guests g ON bt.guest_id = g.guest_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed'
                  AND bt.guest_id IS NOT NULL
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

                UNION ALL

                -- Guests
                SELECT
                    'guest' AS borrower_type,
                    g.first_name,
                    g.last_name,
                    g.guest_id AS id_number,
                    NULL AS department,
                    NULL AS year_level,
                    NULL AS section,
                    g.contact AS contact_number,
                    b.title AS item_borrowed,
                    bt.borrowed_at,
                    bt.due_date
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN guests g ON bt.guest_id = g.guest_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.status = 'borrowed'
                  AND bt.guest_id IS NOT NULL
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
      // Step 1: Hanapin ang libro
      $stmt = $this->db->prepare("SELECT * FROM books WHERE accession_number = ?");
      $stmt->execute([$accessionNumber]);
      $book = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$book) {
        return ['status' => 'not_found'];
      }

      // Step 2: Check availability
      $availability = strtolower(trim($book['availability'] ?? ''));

      // Step 3: Kung borrowed, kunin borrower details
      if ($availability === 'borrowed') {
        $stmt = $this->db->prepare("
                SELECT 
                    bti.item_id,
                    bti.transaction_id,
                    bti.book_id,
                    bt.borrowed_at AS date_borrowed,
                    bt.due_date,
                    s.student_id, s.student_number, s.course, s.year_level, s.section, s.contact AS student_contact,
                    f.faculty_id, f.department AS faculty_dept, f.contact AS faculty_contact,
                    st.staff_id, st.position AS staff_position, st.contact AS staff_contact,
                    g.guest_id, g.first_name AS guest_first_name, g.last_name AS guest_last_name, g.contact AS guest_contact,
                    u_student.first_name AS student_first_name, u_student.last_name AS student_last_name,
                    u_faculty.first_name AS faculty_first_name, u_faculty.last_name AS faculty_last_name,
                    u_staff.first_name AS staff_first_name, u_staff.last_name AS staff_last_name,
                    bti.status,
                    bti.item_id AS borrowing_id,
                    b.title AS book_title
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                LEFT JOIN students s ON bt.student_id = s.student_id
                LEFT JOIN users u_student ON s.user_id = u_student.user_id
                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN users u_faculty ON f.user_id = u_faculty.user_id
                LEFT JOIN staff st ON bt.staff_id = st.staff_id
                LEFT JOIN users u_staff ON st.user_id = u_staff.user_id
                LEFT JOIN guests g ON bt.guest_id = g.guest_id
                JOIN books b ON bti.book_id = b.book_id
                WHERE bti.book_id = ? AND bti.status = 'borrowed'
                LIMIT 1
            ");
        $stmt->execute([$book['book_id']]);
        $borrowInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($borrowInfo) {
          // Determine borrower type and details
          if (!empty($borrowInfo['student_id'])) {
            $borrowerName = trim(($borrowInfo['student_first_name'] ?? '') . ' ' . ($borrowInfo['student_last_name'] ?? ''));
            $borrowerType = 'student';
            $contact = $borrowInfo['student_contact'] ?? 'N/A';
            $idNumber = $borrowInfo['student_number'] ?? 'N/A';
            $courseOrDept = trim(($borrowInfo['course'] ?? 'N/A') . ' - ' . ($borrowInfo['year_level'] ?? 'N/A') . ' ' . ($borrowInfo['section'] ?? ''));
          } elseif (!empty($borrowInfo['faculty_id'])) {
            $borrowerName = trim(($borrowInfo['faculty_first_name'] ?? '') . ' ' . ($borrowInfo['faculty_last_name'] ?? ''));
            $borrowerType = 'faculty';
            $contact = $borrowInfo['faculty_contact'] ?? 'N/A';
            $idNumber = $borrowInfo['faculty_id'] ?? 'N/A';
            $courseOrDept = $borrowInfo['faculty_dept'] ?? 'N/A';
          } elseif (!empty($borrowInfo['staff_id'])) {
            $borrowerName = trim(($borrowInfo['staff_first_name'] ?? '') . ' ' . ($borrowInfo['staff_last_name'] ?? ''));
            $borrowerType = 'staff';
            $contact = $borrowInfo['staff_contact'] ?? 'N/A';
            $idNumber = $borrowInfo['staff_id'] ?? 'N/A';
            $courseOrDept = $borrowInfo['staff_position'] ?? 'N/A';
          } elseif (!empty($borrowInfo['guest_id'])) {
            $borrowerName = trim(($borrowInfo['guest_first_name'] ?? '') . ' ' . ($borrowInfo['guest_last_name'] ?? ''));
            $borrowerType = 'guest';
            $contact = $borrowInfo['guest_contact'] ?? 'N/A';
            $idNumber = $borrowInfo['guest_id'] ?? 'N/A';
            $courseOrDept = 'N/A';
          } else {
            $borrowerName = 'Unknown';
            $borrowerType = 'unknown';
            $contact = 'N/A';
            $idNumber = 'N/A';
            $courseOrDept = 'N/A';
          }

          return [
            'status' => 'borrowed',
            'details' => array_merge($book, [
              'borrower_type' => $borrowerType,
              'borrower_name' => $borrowerName,
              'id_number' => $idNumber,
              'course_or_department' => $courseOrDept,
              'contact' => $contact,
              'date_borrowed' => $borrowInfo['date_borrowed'],
              'due_date' => $borrowInfo['due_date'],
              'borrowing_id' => $borrowInfo['borrowing_id']
            ])
          ];
        }

        return [
          'status' => 'available',
          'details' => $book
        ];
      }

      // Default available
      return [
        'status' => 'available',
        'details' => $book
      ];
    } catch (PDOException $e) {
      error_log('[ReturningRepository::findBookByAccession] ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
      return null;
    }
  }





  /**
   * I-update ang borrowing record at ibalik ang status ng libro sa 'available'.
   */
  public function markAsReturned($itemId): ?array
  {
    try {
      $this->db->beginTransaction();

      // Kunin ang item at transaction info kasama borrower types
      $stmtGetItem = $this->db->prepare("
            SELECT bti.book_id, bti.transaction_id, bt.student_id, bt.faculty_id, bt.staff_id, bt.guest_id
            FROM borrow_transaction_items bti
            JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
            WHERE bti.item_id = ? AND bti.status = 'borrowed'
        ");
      $stmtGetItem->execute([$itemId]);
      $itemInfo = $stmtGetItem->fetch(PDO::FETCH_ASSOC);

      if (!$itemInfo) {
        $this->db->rollBack();
        return null;
      }

      $bookId = $itemInfo['book_id'];
      $transactionId = $itemInfo['transaction_id'];

      // Update borrow_transaction_items
      $stmtUpdateItem = $this->db->prepare("
            UPDATE borrow_transaction_items
            SET status = 'returned', returned_at = NOW()
            WHERE item_id = ? AND status = 'borrowed'
        ");
      $stmtUpdateItem->execute([$itemId]);

      // Update book availability
      $stmtUpdateBook = $this->db->prepare("UPDATE books SET availability = 'available' WHERE book_id = ?");
      $stmtUpdateBook->execute([$bookId]);

      // Check if may natitirang borrowed items sa transaction
      $stmtCheckAll = $this->db->prepare("
            SELECT COUNT(*) as remaining
            FROM borrow_transaction_items
            WHERE transaction_id = ? AND status = 'borrowed'
        ");
      $stmtCheckAll->execute([$transactionId]);
      $remaining = $stmtCheckAll->fetch(PDO::FETCH_ASSOC)['remaining'];

      if ($remaining == 0) {
        $stmtUpdateTrans = $this->db->prepare("UPDATE borrow_transactions SET status = 'returned' WHERE transaction_id = ?");
        $stmtUpdateTrans->execute([$transactionId]);
      }

      $this->db->commit();

      // Kunin ang updated book info kasama borrower info
      $stmtBook = $this->db->prepare("
            SELECT b.*,
                   bt.student_id, bt.faculty_id, bt.staff_id, bt.guest_id,
                   u_student.first_name AS student_first_name, u_student.last_name AS student_last_name,
                   u_faculty.first_name AS faculty_first_name, u_faculty.last_name AS faculty_last_name,
                   u_staff.first_name AS staff_first_name, u_staff.last_name AS staff_last_name,
                   g.first_name AS guest_first_name, g.last_name AS guest_last_name, g.contact AS guest_contact
            FROM books b
            LEFT JOIN borrow_transaction_items bti ON b.book_id = bti.book_id
            LEFT JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
            LEFT JOIN students s ON bt.student_id = s.student_id
            LEFT JOIN users u_student ON s.user_id = u_student.user_id
            LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
            LEFT JOIN users u_faculty ON f.user_id = u_faculty.user_id
            LEFT JOIN staff st ON bt.staff_id = st.staff_id
            LEFT JOIN users u_staff ON st.user_id = u_staff.user_id
            LEFT JOIN guests g ON bt.guest_id = g.guest_id
            WHERE b.book_id = ?
            LIMIT 1
        ");
      $stmtBook->execute([$bookId]);
      $updatedBook = $stmtBook->fetch(PDO::FETCH_ASSOC);

      // Format borrower name dynamically
      if (!empty($updatedBook['student_id'])) {
        $updatedBook['borrower_name'] = trim(($updatedBook['student_first_name'] ?? '') . ' ' . ($updatedBook['student_last_name'] ?? ''));
      } elseif (!empty($updatedBook['faculty_id'])) {
        $updatedBook['borrower_name'] = trim(($updatedBook['faculty_first_name'] ?? '') . ' ' . ($updatedBook['faculty_last_name'] ?? ''));
      } elseif (!empty($updatedBook['staff_id'])) {
        $updatedBook['borrower_name'] = trim(($updatedBook['staff_first_name'] ?? '') . ' ' . ($updatedBook['staff_last_name'] ?? ''));
      } elseif (!empty($updatedBook['guest_id'])) {
        $updatedBook['borrower_name'] = trim(($updatedBook['guest_first_name'] ?? '') . ' ' . ($updatedBook['guest_last_name'] ?? ''));
        $updatedBook['contact'] = $updatedBook['guest_contact'] ?? 'N/A';
      } else {
        $updatedBook['borrower_name'] = 'N/A';
      }

      return $updatedBook;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log('[ReturningRepository::markAsReturned] ' . $e->getMessage());
      return null;
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
      'guest' => 'N/A',
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
