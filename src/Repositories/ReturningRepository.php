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
      // --- NEAR DUE & OVERDUE BASE QUERY ---
      $baseSelect = "
                SELECT 
                    bti.status,
                    bt.due_date,
                    bt.borrowed_at,
                    b.title AS item_borrowed,
                    u.first_name, u.last_name,
                    s.student_number AS id_number, s.year_level, s.section, s.contact AS contact_number,
                    f.faculty_id AS id_number_f, f.contact AS contact_number_f,
                    st.staff_id AS id_number_st, st.position AS department_st, st.contact AS contact_number_st,
                    g.guest_id AS id_number_g, g.contact AS contact_number_g, g.first_name AS g_first_name, g.last_name AS g_last_name,

                    -- NEW ID FIELDS
                    s.course_id, 
                    f.college_id, 
                    
                    -- NEW DISPLAY FIELDS (COALESCE for Union compatibility)
                    COALESCE(c.course_code, cl.college_code, st.position) AS department_course_code,
                    COALESCE(c.course_title, cl.college_name, st.position) AS department_course_name
            ";

      $baseFrom = "
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                JOIN books b ON bti.book_id = b.book_id
                LEFT JOIN students s ON bt.student_id = s.student_id
                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN staff st ON bt.staff_id = st.staff_id
                LEFT JOIN guests g ON bt.guest_id = g.guest_id
                LEFT JOIN users u ON u.user_id = COALESCE(s.user_id, f.user_id, st.user_id)
                -- NEW JOINS
                LEFT JOIN courses c ON s.course_id = c.course_id
                LEFT JOIN colleges cl ON f.college_id = cl.college_id
            ";


      // --- NEAR DUE QUERY ---
      $queryNearDue = $baseSelect . $baseFrom . "
                WHERE bti.status = 'borrowed' 
                AND DATE(bt.due_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ";
      $stmtNearDue = $this->db->prepare($queryNearDue . " ORDER BY bt.due_date ASC");
      $stmtNearDue->execute();
      $nearDue = $stmtNearDue->fetchAll(PDO::FETCH_ASSOC);

      // --- OVERDUE QUERY ---
      $queryOverdue = $baseSelect . $baseFrom . "
                WHERE bti.status = 'borrowed' 
                AND DATE(bt.due_date) < CURDATE()
            ";
      $stmtOverdue = $this->db->prepare($queryOverdue . " ORDER BY bt.due_date ASC");
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
      $stmt = $this->db->prepare("SELECT * FROM books WHERE accession_number = ?");
      $stmt->execute([$accessionNumber]);
      $book = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$book) {
        return ['status' => 'not_found'];
      }

      $availability = strtolower(trim($book['availability'] ?? ''));

      if ($availability === 'borrowed') {
        $stmt = $this->db->prepare("
                SELECT 
                    bti.item_id, bti.transaction_id, bti.book_id,
                    bt.borrowed_at AS date_borrowed, bt.due_date,
                    
                    bt.student_id, bt.faculty_id, bt.staff_id, bt.guest_id,

                    s.student_number, s.year_level, s.section, s.contact AS student_contact,
                    u_student.first_name AS student_first_name, u_student.last_name AS student_last_name, u_student.email AS student_email, 
                    c.course_code, c.course_title, 

                    f.unique_faculty_id, f.college_id, f.contact AS faculty_contact, 
                    u_faculty.first_name AS faculty_first_name, u_faculty.last_name AS faculty_last_name, u_faculty.email AS faculty_email, 
                    cl.college_code, cl.college_name, 

                    st.staff_id AS staff_id_num, st.employee_id, st.position AS staff_position, st.contact AS staff_contact,
                    u_staff.first_name AS staff_first_name, u_staff.last_name AS staff_last_name, u_staff.email AS staff_email,

                    g.guest_id AS guest_id_num, g.first_name AS guest_first_name, g.last_name AS guest_last_name, g.contact AS guest_contact,
                    
                    bti.status, bti.item_id AS borrowing_id, b.title AS book_title
                FROM borrow_transaction_items bti
                JOIN borrow_transactions bt ON bti.transaction_id = bt.transaction_id
                
                LEFT JOIN students s ON bt.student_id = s.student_id
                LEFT JOIN users u_student ON s.user_id = u_student.user_id
                LEFT JOIN courses c ON s.course_id = c.course_id 

                LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
                LEFT JOIN users u_faculty ON f.user_id = u_faculty.user_id
                LEFT JOIN colleges cl ON f.college_id = cl.college_id 

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

          $borrowerName = $borrowerType = $contact = $email = $idNumber = $courseOrDept = $yearSectionDisplay = 'N/A';

          if (!empty($borrowInfo['student_id'])) {
            $borrowerName = trim(($borrowInfo['student_first_name'] ?? '') . ' ' . ($borrowInfo['student_last_name'] ?? ''));
            $borrowerType = 'student';
            $contact = $borrowInfo['student_contact'] ?? 'N/A';
            $email = $borrowInfo['student_email'] ?? 'N/A';
            $idNumber = $borrowInfo['student_number'] ?? 'N/A';

            $courseOrDept = trim(($borrowInfo['course_code'] ?? 'N/A') . ' - ' . ($borrowInfo['course_title'] ?? 'N/A'));
            $yearSectionDisplay = trim(($borrowInfo['year_level'] ?? 'N/A') . ' ' . ($borrowInfo['section'] ?? 'N/A'));
          } elseif (!empty($borrowInfo['faculty_id'])) {
            $borrowerName = trim(($borrowInfo['faculty_first_name'] ?? '') . ' ' . ($borrowInfo['faculty_last_name'] ?? ''));
            $borrowerType = 'faculty';
            $contact = $borrowInfo['faculty_contact'] ?? 'N/A';
            $email = $borrowInfo['faculty_email'] ?? 'N/A';
            $idNumber = $borrowInfo['unique_faculty_id'] ?? $borrowInfo['faculty_id'] ?? 'N/A';
            $courseOrDept = trim(($borrowInfo['college_code'] ?? 'N/A') . ' - ' . ($borrowInfo['college_name'] ?? 'N/A'));
            $yearSectionDisplay = $borrowInfo['college_name'] ?? 'N/A';
          } elseif (!empty($borrowInfo['staff_id'])) {
            $borrowerName = trim(($borrowInfo['staff_first_name'] ?? '') . ' ' . ($borrowInfo['staff_last_name'] ?? ''));
            $borrowerType = 'staff';
            $contact = $borrowInfo['staff_contact'] ?? 'N/A';
            $email = $borrowInfo['staff_email'] ?? 'N/A';
            $idNumber = $borrowInfo['employee_id'] ?? $borrowInfo['staff_id_num'] ?? 'N/A';
            $courseOrDept = $borrowInfo['staff_position'] ?? 'N/A';
            $yearSectionDisplay = $borrowerType;
          } elseif (!empty($borrowInfo['guest_id'])) {
            $borrowerName = trim(($borrowInfo['guest_first_name'] ?? '') . ' ' . ($borrowInfo['guest_last_name'] ?? ''));
            $borrowerType = 'guest';
            $contact = $borrowInfo['guest_contact'] ?? 'N/A';
            $email = 'N/A';
            $idNumber = $borrowInfo['guest_id_num'] ?? 'N/A';
            $courseOrDept = 'N/A';
            $yearSectionDisplay = $borrowerType;
          }

          return [
            'status' => 'borrowed',
            'details' => array_merge($book, [
              'borrower_type' => $borrowerType,
              'borrower_name' => $borrowerName,
              'id_number' => $idNumber,
              'course_or_department' => $courseOrDept,
              'student_year_section' => $yearSectionDisplay,
              'contact' => $contact,
              'email' => $email,
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

      return [
        'status' => 'available',
        'details' => $book
      ];
    } catch (PDOException $e) {
      error_log('[ReturningRepository::findBookByAccession] ' . $e->getMessage());
      return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
  }

  public function markAsReturned($itemId): ?array
  {
    try {
      $this->db->beginTransaction();

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

      $stmtUpdateItem = $this->db->prepare("
                UPDATE borrow_transaction_items
                SET status = 'returned', returned_at = NOW()
                WHERE item_id = ? AND status = 'borrowed'
            ");
      $stmtUpdateItem->execute([$itemId]);

      $stmtUpdateBook = $this->db->prepare("UPDATE books SET availability = 'available' WHERE book_id = ?");
      $stmtUpdateBook->execute([$bookId]);

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
      'student' => ($row['department_course_code'] ?? 'N/A') . ' - ' . ($row['department_course_name'] ?? 'N/A') . ' ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'faculty' => $row['department_course_name'] ?? 'N/A', // Gagamitin ang College Name
      'staff' => $row['department_st'] ?? 'N/A', // Position/Department ng Staff
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
      'student' => ($row['course_code'] ?? 'N/A') . ' - ' . ($row['course_title'] ?? 'N/A') . ' ' . ($row['year_level'] ?? 'N/A') . ' ' . ($row['section'] ?? ''),
      'faculty' => ($row['faculty_dept_code'] ?? 'N/A') . ' - ' . ($row['faculty_dept_name'] ?? 'N/A'),
      'staff' => $row['staff_position'] ?? 'N/A',
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
      'date_borrowed' => (new \DateTime($row['date_borrowed']))->format('Y-m-d H:i'),
      'due_date' => (new \DateTime($row['due_date']))->format('Y-m-d H:i')
    ];
  }
}
