<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class FacultyCartRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function addToCart(int $facultyId, int $bookId): bool
  {
    $check = $this->db->prepare("SELECT 1 FROM faculty_carts WHERE faculty_id = ? AND book_id = ?");
    $check->execute([$facultyId, $bookId]);
    if ($check->fetch()) return false;

    $stmt = $this->db->prepare("INSERT INTO faculty_carts (faculty_id, book_id) VALUES (?, ?)");
    return $stmt->execute([$facultyId, $bookId]);
  }

  public function getCartByFaculty(int $facultyId): array
  {
    $sql = "SELECT fc.cart_id, b.book_id, b.title, b.author, b.accession_number, b.subject, b.call_number
            FROM faculty_carts fc
            INNER JOIN books b ON fc.book_id = b.book_id
            WHERE fc.faculty_id = :faculty_id
            ORDER BY fc.cart_id DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['faculty_id' => $facultyId]);
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


  public function removeFromCart(int $cartId, int $facultyId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM faculty_carts WHERE cart_id = ? AND faculty_id = ?");
    return $stmt->execute([$cartId, $facultyId]);
  }


  public function countCartItems(int $facultyId): int
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM faculty_carts WHERE faculty_id = ?");
    $stmt->execute([$facultyId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['total']) ? (int)$row['total'] : 0;
  }


  public function getFacultyIdByUserId(int $userId): ?int
  {
    $stmt = $this->db->prepare("SELECT faculty_id, user_id FROM faculty WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return isset($row['faculty_id']) ? (int)$row['faculty_id'] : null;
  }



  public function clearCart(int $facultyId): bool
  {
    $stmt = $this->db->prepare("DELETE FROM faculty_carts WHERE faculty_id = ?");
    return $stmt->execute([$facultyId]);
  }
}
 