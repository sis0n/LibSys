<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CartRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function addToCart(int $studentId, int $bookId): bool
  {
    $check = $this->db->prepare("SELECT 1 FROM carts WHERE student_id = ? AND book_id = ?");
    $check->execute([$studentId, $bookId]);
    if ($check->fetch()) {
      return false;
    }

    $stmt = $this->db->prepare("INSERT INTO carts (student_id, book_id) VALUES (?, ?)");
    return $stmt->execute([$studentId, $bookId]);
  }

  public function getCartByStudent(int $studentId): array
  {
    $sql = "SELECT c.cart_id, b.book_id, b.title, b.author, b.accession_number, b.subject, b.call_number
                FROM carts c
                INNER JOIN books b ON c.book_id = b.book_id
                WHERE c.student_id = :student_id
                ORDER BY c.cart_id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['student_id' => $studentId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    return array_map(fn($row) => [
      "id" => (int)$row["book_id"],
      "cart_id" => (int)$row["cart_id"],
      "title" => $row["title"],
      "author" => $row["author"],
      "accessionNumber" => $row["accession_number"],
      "subject" => $row["subject"],
      "callNumber" => $row["call_number"],
      "type" => "book",
      "icon" => "ph-book"
    ], $rows);
  }

  public function removeFromCart(int $cartId, int $studentId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE cart_id = ? AND student_id = ?");
    return $stmt->execute([$cartId, $studentId]);
  }

  public function countCartItems(int $studentId): int
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM carts WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['total']) ? (int)$row['total'] : 0;
  }

  public function getStudentIdByUserId(int $userId): ?int
  {
    $stmt = $this->db->prepare("SELECT student_id FROM students WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['student_id']) ? (int)$row['student_id'] : null;
  }

  public function clearCart(int $studentId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE student_id = ?");
    return $stmt->execute([$studentId]);
  }
}
