<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class BookManagementRepository
{
    private $db;
    private $baseQuery = "SELECT * FROM books WHERE deleted_at IS NULL";
    private $countQuery = "SELECT COUNT(*) FROM books WHERE deleted_at IS NULL";

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPaginatedBooks(int $limit, int $offset, string $search, string $status, string $sort): array
    {
        $limit = max(1, min($limit, 1000));
        $offset = max(0, $offset);

        $query = $this->baseQuery;
        $params = [];

        if ($search !== '') {
            $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ? OR call_number LIKE ? OR subject LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm;
        }

        if ($status !== '' && strtolower($status) !== 'all status') {
            $query .= " AND availability = ?";
            $params[] = strtolower($status);
        }

        $orderBy = "ORDER BY created_at DESC"; 
        switch ($sort) {
            case 'title_asc': $orderBy = "ORDER BY title ASC"; break;
            case 'title_desc': $orderBy = "ORDER BY title DESC"; break;
            case 'year_asc': $orderBy = "ORDER BY year ASC, title ASC"; break;
            case 'year_desc': $orderBy = "ORDER BY year DESC, title ASC"; break;
        }

        $query .= " " . $orderBy . " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPaginatedBooks(string $search, string $status): int
    {
        $query = $this->countQuery; 
        $params = [];

        if ($search !== '') {
            $query .= " AND (title LIKE ? OR author LIKE ? OR book_isbn LIKE ? OR accession_number LIKE ? OR call_number LIKE ? OR subject LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm;
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
        $stmt = $this->db->prepare("SELECT * FROM books WHERE book_id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createBook($data)
    {
        $columns = "(accession_number, call_number, title, author, book_place, book_publisher, year, book_edition, description, book_isbn, book_supplementary, subject, availability";
        $placeholders = "(:accession_number, :call_number, :title, :author, :book_place, :book_publisher, :year, :book_edition, :description, :book_isbn, :book_supplementary, :subject, :availability";
        $params = [
            ':accession_number' => $data['accession_number'],
            ':call_number' => $data['call_number'],
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':book_place' => $data['book_place'] ?? null,
            ':book_publisher' => $data['book_publisher'] ?? null,
            ':year' => empty($data['year']) ? null : $data['year'],
            ':book_edition' => $data['book_edition'] ?? null,
            ':description' => $data['description'] ?? null,
            ':book_isbn' => $data['book_isbn'] ?? null,
            ':book_supplementary' => $data['book_supplementary'] ?? null,
            ':subject' => $data['subject'] ?? null,
            ':availability' => 'available'
        ];
        if (!empty($data['cover'])) { $columns .= ", cover"; $placeholders .= ", :cover"; $params[':cover'] = $data['cover']; }
        $columns .= ")"; $placeholders .= ")";
        $stmt = $this->db->prepare("INSERT INTO books $columns VALUES $placeholders");
        return $stmt->execute($params);
    }

    public function updateBook($id, $data, $updated_by_user_id)
    {
        $sqlParts = [];
        $params = [
            ':book_id' => $id,
            ':updated_by' => $updated_by_user_id
        ];

        $allowedFields = ['accession_number', 'call_number', 'title', 'author', 'book_place', 'book_publisher', 'year', 'book_edition', 'description', 'book_isbn', 'book_supplementary', 'subject', 'availability', 'cover'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $sqlParts[] = "$field = :$field";
                $params[":$field"] = ($field === 'year' && empty($data[$field])) ? null : $data[$field];
            }
        }
        
        if (empty($sqlParts)) return true; 

        $sqlParts[] = "updated_by = :updated_by";

        $sql = "UPDATE books SET " . implode(", ", $sqlParts) . " WHERE book_id = :book_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteBook($id, $deleted_by_user_id)
    {
        $stmt = $this->db->prepare("
            UPDATE books 
            SET 
                deleted_at = CURRENT_TIMESTAMP, 
                deleted_by = ?
            WHERE 
                book_id = ?
        ");
        return $stmt->execute([$deleted_by_user_id, $id]);
    }
}