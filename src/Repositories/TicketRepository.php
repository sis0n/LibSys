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

  /**
   * Kunin lahat ng cart items ng student
   */
  public function getCartItems(int $studentId): array
  {
    $stmt = $this->db->prepare("
        SELECT c.cart_id, c.book_id, b.title, b.author
        FROM carts c
        JOIN books b ON c.book_id = b.book_id
        LEFT JOIN students s ON s.student_id = c.student_id
        LEFT JOIN users u ON u.user_id = s.user_id
        WHERE (c.student_id = :sid OR u.user_id = :sid)
    ");
    $stmt->execute(['sid' => $studentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Insert items sa borrow_transaction_items
   */
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

  /**
   * Empty cart after checkout
   */
  public function clearCart(int $studentId): void
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE student_id = :sid");
    $stmt->execute(['sid' => $studentId]);
  }

  public function getStudentInfo(int $studentId): array
  {
    $stmt = $this->db->prepare("
        SELECT s.student_number, s.course, s.year_level, u.name
        FROM students s
        JOIN users u ON s.user_id = u.user_id
        WHERE s.student_id = :sid
    ");
    $stmt->execute(['sid' => $studentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
  }

  public function getTransactionByCode(string $transactionCode): array
  {
    $stmt = $this->db->prepare("SELECT * FROM borrow_transactions WHERE transaction_code = :tcode");
    $stmt->execute(['tcode' => $transactionCode]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transaction) {
      throw new \Exception("Transaction not found.");
    }

    return $transaction;
  }

  public function getCartItemsByIds(int $studentId, array $cartIds): array
  {
    $cartIds = array_map('intval', $cartIds);
    if (empty($cartIds)) return [];
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));

    $stmt = $this->db->prepare("
        SELECT c.cart_id, c.book_id, b.title, b.author
        FROM carts c
        JOIN books b ON c.book_id = b.book_id
        LEFT JOIN students s ON s.student_id = c.student_id
        LEFT JOIN users u ON u.user_id = s.user_id
        WHERE (c.student_id = ? OR u.user_id = ?)
          AND c.cart_id IN ($placeholders)
    ");

    $params = array_merge([$studentId, $studentId], $cartIds);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents(
      __DIR__ . '/../../Controllers/checkout_test.txt',
      date('H:i:s') . " getCartItemsByIds() SQL executed.\nParams: " . json_encode($params) . "\nResult: " . json_encode($result) . "\n\n",
      FILE_APPEND
    );
    return $result;
  }





  public function removeCartItemsByIds(int $studentId, array $cartIds): void
  {
    if (empty($cartIds)) return;
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));

    $stmt = $this->db->prepare(
      "DELETE FROM carts WHERE student_id = ? AND cart_id IN ($placeholders)"
    );

    $stmt->execute(array_merge([$studentId], $cartIds));
  }

  public function getTransactionItems(int $transactionId): array
  {
    $stmt = $this->db->prepare("
        SELECT b.book_id, b.title, b.author
        FROM borrow_transaction_items t
        JOIN books b ON t.book_id = b.book_id
        WHERE t.transaction_id = :tid
    ");
    $stmt->execute(['tid' => $transactionId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
}
