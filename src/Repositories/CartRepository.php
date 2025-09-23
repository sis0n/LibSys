<?php

namespace App\Repositories;

use App\Core\Database;

class CartRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function addToCart($studentId, $bookId)
  {
    $stmt = $this->db->prepare("SELECT 1 FROM carts WHERE student_id = ? AND book_id = ?");
    $stmt->execute([$studentId, $bookId]);

    if ($stmt->fetch()) {
        return false; // nasa cart na
    }

    // pag wala sa cart, insert na diretso
    $stmt = $this->db->prepare("INSERT INTO carts (student_id, book_id) VALUES (?, ?)");
    return $stmt->execute([$studentId, $bookId]);
  }

  public function getCartByStudent($studentId)
  {
    $stmt = $this->db->prepare("
          SELECT c.cart_id, b.book_id, b.accession_number, b.title, b.author, c.added_at
          FROM carts c
          JOIN books b ON c.book_id = b.book_id
          WHERE c.student_id = ?
      ");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function removeFromCart($cartId)
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE cart_id = ?");
    return $stmt->execute([$cartId]);
  }

  public function clearCart($studentId)
  {
    $stmt = $this->db->prepare("DELETE FROM carts WHERE student_id = ?");
    return $stmt->execute([$studentId]);
  }

  public function getCartItems($studentId)
  {
    return $this->getCartByStudent($studentId);
  }

}
