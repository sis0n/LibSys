<?php

namespace App\Repositories;

use App\Core\Database;

class BookCatalogRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAllBooks()
  {
    $stmt = $this->db->prepare("SELECT * FROM books ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getBookById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function addBook($data)
  {
    $stmt = $this->db->prepare("
            INSERT INTO books 
            (accession_number, call_number, title, author, book_place, book_publisher, year, book_edition, description, book_isbn, book_supplementary, subject, availability) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
            UPDATE books 
            SET accession_number=?, call_number=?, title=?, author=?, book_place=?, book_publisher=?, year=?, book_edition=?, description=?, book_isbn=?, book_supplementary=?, subject=?, availability=? 
            WHERE book_id=?
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
    $stmt = $this->db->prepare("DELETE FROM books WHERE book_id = ?");
    return $stmt->execute([$id]);
  }

  public function updateAvailability($id, $status)
  {
    $stmt = $this->db->prepare("UPDATE books SET availability = ? WHERE book_id = ?");
    return $stmt->execute([$status, $id]);
  }

  public function searchBooks($keyword)
  {
    $search = "%$keyword%";
    $stmt = $this->db->prepare("
            SELECT * FROM books 
            WHERE title LIKE ? OR author LIKE ? OR accession_number LIKE ? OR subject LIKE ? OR book_isbn LIKE ?
        ");
    $stmt->execute([$search, $search, $search, $search, $search]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function filterBooks($filters = [])
  {
    $query = "SELECT * FROM books WHERE 1=1";
    $place_holder = [];

    foreach ($filters as $column => $value) {
      if (!empty($value) && in_array($column, ['subject', 'availability', 'author', 'year', 'book_publisher'])) {
        $query .= " AND $column = ?";
        $place_holder[] = $value;
      }
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($place_holder);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getPaginatedFiltered(
    int $limit,
    int $offset,
    string $search = '',
    string $category = '',
    string $status = '',
    string $sort = 'default'
  ): array {
    $limit = max(1, min($limit, 100));
    $offset = max(0, $offset);

    $query = "SELECT * FROM books WHERE 1=1";
    $params = [];

    if ($search !== '') {
      $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ?)";
      $searchTerm = "%$search%";
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
    }

    if ($category !== '' && $category !== 'All Categories') {
      $query .= " AND subject = ?";
      $params[] = $category;
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

    return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Fetch results
  }

  public function countAvailableBooks(): int
  {
    $stmt = $this->db->query("SELECT COUNT(*) FROM books WHERE availability = 'available'");
    return (int) $stmt->fetchColumn();
  }

  public function countPaginatedFiltered(
    string $search = '',
    string $category = '',
    string $status = ''
  ): int {
    $query = "SELECT COUNT(*) FROM books WHERE 1=1";
    $params = [];

    if ($search !== '') {
      $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ?)";
      $searchTerm = "%$search%";
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
      $params[] = $searchTerm;
    }

    if ($category !== '' && $category !== 'All Categories') {
      $query .= " AND subject = ?";
      $params[] = $category;
    }

    if ($status !== '' && $status !== 'All Status') {
      $query .= " AND availability = ?";
      $params[] = strtolower($status);
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
  }
}
