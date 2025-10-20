<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BookManagementRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getPaginatedBooks(int $limit, int $offset, string $search, string $status, string $sort): array
  {
    $limit = max(1, min($limit, 100));
    $offset = max(0, $offset);

    $query = "SELECT * FROM books WHERE 1=1";
    $params = [];

    if ($search !== '') {
      $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ? OR call_number LIKE ?)";
      $searchTerm = "%$search%";
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
    }

    if ($status !== '' && strtolower($status) !== 'all status') {
      $query .= " AND availability = ?";
      $params[] = strtolower($status);
    }

    $orderBy = "ORDER BY created_at DESC";
    switch ($sort) {
      case 'title_asc':
        $orderBy = "ORDER BY title ASC";
        break;
      case 'title_desc':
        $orderBy = "ORDER BY title DESC";
        break;
      case 'year_asc':
        $orderBy = "ORDER BY year ASC, title ASC";
        break;
      case 'year_desc':
        $orderBy = "ORDER BY year DESC, title ASC";
        break;
    }

    $query .= " " . $orderBy . " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function countPaginatedBooks(string $search, string $status): int
  {
    $query = "SELECT COUNT(*) FROM books WHERE 1=1";
    $params = [];

    if ($search !== '') {
      $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ? OR call_number LIKE ?)";
      $searchTerm = "%$search%";
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
    }

    if ($status !== '' && strtolower($status) !== 'all status') {
      $query .= " AND availability = ?";
      $params[] = strtolower($status);
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
  }

  public function findBookById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function createBook($data)
  {
    $stmt = $this->db->prepare("
            INSERT INTO books 
            (accession_number, call_number, title, author, book_place, book_publisher, year, book_edition, description, book_isbn, book_supplementary, subject, availability) 
            VALUES (:accession_number, :call_number, :title, :author, :book_place, :book_publisher, :year, :book_edition, :description, :book_isbn, :book_supplementary, :subject, :availability)
        ");

    return $stmt->execute([
      ':accession_number' => $data['accession_number'],
      ':call_number' => $data['call_number'],
      ':title' => $data['title'],
      ':author' => $data['author'],
      ':book_place' => $data['book_place'] ?? null,
      ':book_publisher' => $data['book_publisher'] ?? null,
      ':year' => $data['year'] ?? null,
      ':book_edition' => $data['book_edition'] ?? null,
      ':description' => $data['description'] ?? null,
      ':book_isbn' => $data['book_isbn'] ?? null,
      ':book_supplementary' => $data['book_supplementary'] ?? null,
      ':subject' => $data['subject'] ?? null,
      ':availability' => $data['availability'] ?? 'available' 
    ]);
  }

  public function updateBook($id, $data)
  {
    $stmt = $this->db->prepare("
            UPDATE books 
            SET accession_number=:accession_number, call_number=:call_number, title=:title, author=:author, 
                book_place=:book_place, book_publisher=:book_publisher, year=:year, book_edition=:book_edition, 
                description=:description, book_isbn=:book_isbn, book_supplementary=:book_supplementary, subject=:subject,
                availability=:availability
            WHERE book_id=:book_id
        ");

    return $stmt->execute([
      ':accession_number' => $data['accession_number'],
      ':call_number' => $data['call_number'],
      ':title' => $data['title'],
      ':author' => $data['author'],
      ':book_place' => $data['book_place'] ?? null,
      ':book_publisher' => $data['book_publisher'] ?? null,
      ':year' => $data['year'] ?? null,
      ':book_edition' => $data['book_edition'] ?? null,
      ':description' => $data['description'] ?? null,
      ':book_isbn' => $data['book_isbn'] ?? null,
      ':book_supplementary' => $data['book_supplementary'] ?? null,
      ':subject' => $data['subject'] ?? null,
      ':availability' => $data['availability'] ?? 'available',
      ':book_id' => $id
    ]);
  }

  public function deleteBook($id)
  {
    $stmt = $this->db->prepare("DELETE FROM books WHERE book_id = ?");
    return $stmt->execute([$id]);
  }
}
