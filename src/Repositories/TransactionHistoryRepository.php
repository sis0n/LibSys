<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TransactionHistoryRepository
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Combines fetching for all and by status, with pagination and search.
    public function getPaginatedTransactions(string $status, ?string $date, ?string $search, int $limit, int $offset): array
    {
        // FIX: The FROM and JOIN clauses were missing. Added getBaseFromJoinQuery().
        $sql = $this->getBaseQuery() . $this->getBaseFromJoinQuery();
        $params = [];

        $this->applyFilters($sql, $params, $status, $date, $search);

        $sql .= " ORDER BY bt.borrowed_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Combines counting for all and by status, with search.
    public function countTransactions(string $status, ?string $date, ?string $search): int
    {
        // The COUNT query must join the same tables to get an accurate count of items.
        $sql = "SELECT COUNT(bti.item_id) " . $this->getBaseFromJoinQuery();
        $params = [];

        $this->applyFilters($sql, $params, $status, $date, $search);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    // Returns the base SELECT statement.
    private function getBaseQuery(): string
    {
        return "
            SELECT 
                bt.transaction_id, bt.borrowed_at, bti.returned_at, bt.status AS transaction_status,
                COALESCE(CONCAT(su.first_name, ' ', su.last_name), CONCAT(fu.first_name, ' ', fu.last_name), CONCAT(stu.first_name, ' ', stu.last_name), CONCAT(g.first_name, ' ', g.last_name)) AS user_name,
                COALESCE(s.student_number, f.unique_faculty_id, st.employee_id, g.guest_id) AS user_id_number,
                b.accession_number, b.title AS book_title, b.author AS book_author, b.call_number, b.book_isbn,
                s.student_id, f.faculty_id, st.staff_id, g.guest_id, 
                c.course_code, c.course_title, cl.college_code, cl.college_name, s.year_level, s.section, st.position,
                CONCAT(librarian.first_name, ' ', librarian.last_name) AS librarian_name
        ";
    }

    // Returns the base FROM and JOIN clauses.
    private function getBaseFromJoinQuery(): string
    {
        return "
            FROM borrow_transactions bt
            JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            JOIN books b ON bti.book_id = b.book_id
            LEFT JOIN students s ON bt.student_id = s.student_id
            LEFT JOIN faculty f ON bt.faculty_id = f.faculty_id
            LEFT JOIN staff st ON bt.staff_id = st.staff_id
            LEFT JOIN guests g ON bt.guest_id = g.guest_id
            LEFT JOIN users su ON s.user_id = su.user_id
            LEFT JOIN users fu ON f.user_id = fu.user_id
            LEFT JOIN users stu ON st.user_id = stu.user_id
            LEFT JOIN courses c ON s.course_id = c.course_id
            LEFT JOIN colleges cl ON f.college_id = cl.college_id
            LEFT JOIN users librarian ON bt.librarian_id = librarian.user_id
        ";
    }

    // Applies WHERE clauses for all filters.
    private function applyFilters(string &$sql, array &$params, string $status, ?string $date, ?string $search)
    {
        $sql .= " WHERE 1=1 "; // Start with a true condition

        if ($status !== 'all' && $status !== 'pending') {
            $sql .= " AND bt.status = :status";
            $params[':status'] = $status;
        } else {
            $sql .= " AND bt.status != 'Pending'";
        }

        if ($date) {
            $sql .= " AND DATE(bt.borrowed_at) = :date";
            $params[':date'] = $date;
        }

        if ($search) {
            $sql .= " AND (
                CONCAT_WS(' ', su.first_name, su.last_name) LIKE :search OR
                CONCAT_WS(' ', fu.first_name, fu.last_name) LIKE :search OR
                CONCAT_WS(' ', stu.first_name, stu.last_name) LIKE :search OR
                s.student_number LIKE :search OR
                f.unique_faculty_id LIKE :search OR
                st.employee_id LIKE :search OR
                b.title LIKE :search OR
                b.accession_number LIKE :search
            )";
            $params[':search'] = "%{$search}%";
        }
    }
}