<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StaffCartRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function addToCart(int $staffId, int $bookId): bool
  {
    $check = $this->db->prepare("SELECT 1 FROM staff_carts WHERE staff_id = ? AND book_id = ?");
    $check->execute([$staffId, $bookId]);
    if ($check->fetch()) return false;

    $stmt = $this->db->prepare("INSERT INTO staff_carts (staff_id, book_id) VALUES (?, ?)");
    return $stmt->execute([$staffId, $bookId]);
  }

  public function getCartByStaff(int $staffId): array
  {
    $sql = "SELECT sc.cart_id, b.book_id, b.title, b.author, b.accession_number, b.subject, b.call_number
                FROM staff_carts sc
                INNER JOIN books b ON sc.book_id = b.book_id
                WHERE sc.staff_id = :staff_id
                ORDER BY sc.cart_id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['staff_id' => $staffId]);
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

  public function removeFromCart(int $cartId, int $staffId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM staff_carts WHERE cart_id = ? AND staff_id = ?");
    return $stmt->execute([$cartId, $staffId]);
  }

  public function countCartItems(int $staffId): int
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM staff_carts WHERE staff_id = ?");
    $stmt->execute([$staffId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['total']) ? (int)$row['total'] : 0;
  }

  public function getStaffIdByUserId(int $userId): ?int
  {
    $stmt = $this->db->prepare("SELECT staff_id, user_id FROM staff WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return isset($row['staff_id']) ? (int)$row['staff_id'] : null;
  }

  public function clearCart(int $staffId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM staff_carts WHERE staff_id = ?");
    return $stmt->execute([$staffId]);
  }
}
