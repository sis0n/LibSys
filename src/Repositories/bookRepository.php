<?php

namespace App\Repositories;

use App\Core\Database;

class BookRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAllBooks($limit = 50)
  {
    $stmt = $this->db->prepare("SELECT * FROM books ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getBookById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function addBook($data)
  {
    $stmt = $this->db->prepare("
            INSERT INTO books (
                accession_number, call_number, title, author, book_place, book_publisher, 
                year, book_edition, description, book_isbn, book_supplementary, subject, availability
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
    return $stmt->execute([
      $data['accession_number'],
      $data['call_number'],
      $data['title'],
      $data['author'],
      $data['book_place'],
      $data['book_publisher'],
      $data['year'],
      $data['book_edition'],
      $data['description'],
      $data['book_isbn'],
      $data['book_supplementary'] ?? null,
      $data['subject'],
      $data['availability'] ?? 'available'
    ]);
  }

  public function updateBook($id, $data)
  {
    $stmt = $this->db->prepare("
            UPDATE books SET
                accession_number = ?, call_number = ?, title = ?, author = ?, book_place = ?,
                book_publisher = ?, year = ?, book_edition = ?, description = ?, book_isbn = ?,
                book_supplementary = ?, subject = ?, availability = ?
            WHERE id = ?
        ");
    return $stmt->execute([
      $data['accession_number'],
      $data['call_number'],
      $data['title'],
      $data['author'],
      $data['book_place'],
      $data['book_publisher'],
      $data['year'],
      $data['book_edition'],
      $data['description'],
      $data['book_isbn'],
      $data['book_supplementary'] ?? null,
      $data['subject'],
      $data['availability'],
      $id
    ]);
  }

  public function deleteBook($id)
  {
    $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public function updateAvailability($id, $status)
  {
    $stmt = $this->db->prepare("UPDATE books SET availability = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
  }

  public function searchBooks($keyword)
  {
    $search = "%$keyword%";
    $stmt = $this->db->prepare("
            SELECT * FROM books
            WHERE title LIKE ? 
               OR author LIKE ? 
               OR accession_number LIKE ? 
               OR subject LIKE ?
               OR book_isbn LIKE ?
        ");
    $stmt->execute([$search, $search, $search, $search, $search]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function filterBooks($filters = [])
  {
    $query = "SELECT * FROM books WHERE 1=1";
    $place_holder = [];

    foreach ($filters as $column => $value) {
      if (!empty($value) && in_array($column, [
        'subject',
        'availability',
        'author',
        'year',
        'book_publisher'
      ])) {
        $query .= " AND $column = ?";
        $place_holder[] = $value;
      }
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($place_holder);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
