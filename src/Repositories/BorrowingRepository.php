<?php

namespace App\Repositories;

use App\Core\Database;
use App\Repositories\BookRepository;
use Error;
use PDO;
use Exception;

class BorrowingRepository
{
  private $db;
  private $bookRepo;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
    $this->bookRepo = new BookRepository();
  }

  public function getAll()
  {
    $sql = "
            SELECT b.borrowing_id, b.student_id, b.book_id, b.borrowed_at, b.due_date, b.returned_at, b.status,
                   bk.title, bk.author
            FROM borrowings b
            JOIN books bk ON b.book_id = bk.book_id
            ORDER BY b.borrowed_at DESC
        ";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM borrowings WHERE borrowing_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  private function insertBorrowing(array $data)
  {
    $stmt = $this->db->prepare("
            INSERT INTO borrowings (student_id, book_id, borrowed_at, due_date, status, qr_token)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
    return $stmt->execute([
      $data['student_id'],
      $data['book_id'],
      $data['borrowed_at'],
      $data['due_date'],
      $data['status'],
      $data['qr_token']
    ]);
  }

  public function markAsReturned($id)
  {
    $stmt = $this->db->prepare("
            UPDATE borrowings SET returned_at = NOW(), status = 'returned'
            WHERE borrowing_id = ?
        ");
    return $stmt->execute([$id]);
  }


  public function createFromCart($studentId, array $cartItems, $secretKey)
  {
    $now = date('Y-m-d H:i:s');
    $due = date('Y-m-d H:i:s', strtotime('+7 days'));

    try {
      $this->db->beginTransaction();

      foreach ($cartItems as $item) {
        $book = $this->bookRepo->getBookById($item['book_id']);
        if (!$book || $book['availability'] !== 'available') {
          throw new Exception("Book not available: " . $item['book_id']);
        }

        $qrToken = hash('sha256', $studentId . $item['book_id'] . time() . $secretKey);

        $this->insertBorrowing([
          'student_id'  => $studentId,
          'book_id'     => $item['book_id'],
          'borrowed_at' => $now,
          'due_date'    => $due,
          'status'      => 'borrowed',
          'qr_token'    => $qrToken
        ]);

        $this->bookRepo->updateAvailability($item['book_id'], 'borrowed');
      }

      $this->db->commit();
      return true;
    } catch (Exception $e) {
      $this->db->rollBack();
      Error_log("Failed to create borrowings from cart: " . $e->getMessage());
      return false;
    }
  }

  public function getByStudent($studentId)
  {
      $stmt = $this->db->prepare("
          SELECT b.borrowing_id, b.student_id, b.book_id, b.borrowed_at, b.due_date, b.returned_at, b.status,
                bk.title, bk.author
          FROM borrowings b
          JOIN books bk ON b.book_id = bk.book_id
          WHERE b.student_id = ?
          ORDER BY b.borrowed_at DESC
      ");
      $stmt->execute([$studentId]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

}
