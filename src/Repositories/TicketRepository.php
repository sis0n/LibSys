<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class TicketRepository
{
  protected PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getStudentIdByUserId(int $userId): ?int
  {
    $stmt = $this->db->prepare("SELECT student_id FROM students WHERE user_id = :uid");
    $stmt->execute(['uid' => $userId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    return $student['student_id'] ?? null;
  }

  public function checkProfileCompletion(int $studentId): array
  {
    $sql = "SELECT s.profile_updated, u.profile_picture, s.registration_form
                FROM students s
                JOIN users u ON s.user_id = u.user_id
                WHERE s.student_id = ?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$studentId]);
    $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$studentData) {
      return ['complete' => false, 'message' => 'No student record found.'];
    }

    $isProfileComplete = (bool)$studentData['profile_updated'];
    $hasPicture = !empty($studentData['profile_picture']);
    $hasForm = !empty($studentData['registration_form']);

    if (!$isProfileComplete || !$hasPicture || !$hasForm) {
      return [
        'complete' => false,
        'message' => 'Profile details are incomplete. Please complete your profile, upload a picture, and submit your registration form in "My Profile" before checking out.'
      ];
    }

    return ['complete' => true, 'message' => 'Profile is complete.'];
  }

  public function getCartItems(int $studentId): array
  {
    $stmt = $this->db->prepare("
            SELECT c.cart_id, c.book_id, b.title, b.author, b.accession_number
            FROM carts c
            JOIN books b ON c.book_id = b.book_id
            WHERE c.student_id = :sid 
        ");
    $stmt->execute(['sid' => $studentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addTransactionItems(int $transactionId, array $items): void
  {
    $stmt = $this->db->prepare("
            INSERT INTO borrow_transaction_items (transaction_id, book_id)
            VALUES (:tid, :bid)
        ");
    foreach ($items as $item) {
      $stmt->execute([
        'tid' => $transactionId,
        'bid' => $item['book_id']
      ]);
    }
  }

  public function clearCart(int $studentId): void
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE student_id = :sid");
    $stmt->execute(['sid' => $studentId]);
  }

  public function getStudentInfo(int $studentId): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                s.student_number, 
                s.course, 
                s.year_level, 
                u.first_name, 
                u.middle_name, 
                u.last_name
            FROM students s
            JOIN users u ON s.user_id = u.user_id
            WHERE s.student_id = :sid
        ");
    $stmt->execute(['sid' => $studentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
  }


  public function getLatestTransactionByStudentId($studentId)
  {
    $stmt = $this->db->prepare("
            SELECT * FROM borrow_transactions
            WHERE student_id = ?
            ORDER BY borrowed_at DESC
            LIMIT 1
        ");
    $stmt->execute([$studentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getTransactionByCode(string $transactionCode): array
  {
    $stmt = $this->db->prepare("SELECT * FROM borrow_transactions WHERE transaction_code = :tcode");
    $stmt->execute(['tcode' => $transactionCode]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
      return [];
    }

    return $transaction;
  }

  public function getCartItemsByIds(int $studentId, array $cartIds): array
  {
    $cartIds = array_map('intval', $cartIds);
    if (empty($cartIds)) return [];
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));

    $stmt = $this->db->prepare("
            SELECT c.cart_id, c.book_id, b.title, b.author, b.accession_number
            FROM carts c
            JOIN books b ON c.book_id = b.book_id
            WHERE c.student_id = ? AND c.cart_id IN ($placeholders)
        ");

    $params = array_merge([$studentId], $cartIds);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function removeCartItemsByIds(int $studentId, array $cartIds): void
  {
    if (empty($cartIds)) return;
    $cartIds = array_map('intval', $cartIds);
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));

    $stmt = $this->db->prepare(
      "DELETE FROM carts WHERE student_id = ? AND cart_id IN ($placeholders)"
    );

    $stmt->execute(array_merge([$studentId], $cartIds));
  }

  public function getTransactionItems(int $transactionId): array
  {
    $stmt = $this->db->prepare("
            SELECT 
                b.book_id, 
                b.title, 
                b.author, 
                b.accession_number, 
                b.call_number, 
                b.subject
            FROM borrow_transaction_items t
            JOIN books b ON t.book_id = b.book_id
            WHERE t.transaction_id = :tid
        ");
    $stmt->execute(['tid' => $transactionId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function createTransaction(int $studentId, string $transactionCode, string $dueDate): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO borrow_transactions (student_id, transaction_code, due_date)
            VALUES (:sid, :tcode, :due_date)
        ");
    $stmt->execute([
      'sid' => $studentId,
      'tcode' => $transactionCode,
      'due_date' => $dueDate
    ]);

    return (int) $this->db->lastInsertId();
  }

  public function getBorrowedBooksByTransaction(int $transactionId): array
  {
    $stmt = $this->db->prepare("
            SELECT b.title, b.accession_number
            FROM borrow_transaction_items bti
            INNER JOIN books b ON bti.book_id = b.book_id
            WHERE bti.transaction_id = :tid
        ");
    $stmt->bindValue(':tid', $transactionId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getPendingTransactionByStudentId(int $studentId): ?array
  {
    $stmt = $this->db->prepare("
            SELECT transaction_id, transaction_code, due_date
            FROM borrow_transactions
            WHERE student_id = :sid AND status = 'pending'
            LIMIT 1
        ");
    $stmt->execute(['sid' => $studentId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
  }

  public function countItemsInTransaction(int $transactionId, bool $forUpdate = false): int
  {
    $lock = $forUpdate ? ' FOR UPDATE' : '';
    $stmt = $this->db->prepare("
        SELECT COUNT(*) as total
        FROM borrow_transaction_items
        WHERE transaction_id = :tid{$lock}
    ");
    $stmt->execute(['tid' => $transactionId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['total']) ? (int)$row['total'] : 0;
  }


  public function beginTransaction(): void
  {
    $this->db->beginTransaction();
  }

  public function commit(): void
  {
    $this->db->commit();
  }

  public function rollback(): void
  {
    $this->db->rollBack();
  }
}
