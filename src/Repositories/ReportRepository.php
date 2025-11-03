<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ReportRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCirculatedBooksSummary()
    {
        $sql = "
            SELECT
                'Student' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.student_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bti.book_id IS NOT NULL
            UNION ALL
            SELECT
                'Faculty' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.faculty_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bti.book_id IS NOT NULL
            UNION ALL
            SELECT
                'Staff' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.staff_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bti.book_id IS NOT NULL
            UNION ALL
            SELECT
                'TOTAL' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bti.status IN ('borrowed', 'returned', 'overdue') AND bti.book_id IS NOT NULL;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopVisitorsByYear()
    {
        $sql = "
            SELECT
                CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                s.student_number,
                COALESCE(c.course_code, 'N/A') AS course,
                top_visitors.visits
            FROM (
                SELECT
                    student_number,
                    COUNT(*) AS visits
                FROM
                    attendance_logs
                WHERE
                    YEAR(timestamp) = YEAR(CURDATE()) AND student_number IS NOT NULL AND student_number != ''
                GROUP BY
                    student_number
                ORDER BY
                    visits DESC
                LIMIT 10
            ) AS top_visitors
            JOIN
                students s ON top_visitors.student_number = s.student_number
            JOIN
                users u ON s.user_id = u.user_id
            LEFT JOIN
                courses c ON s.course_id = c.course_id
            ORDER BY
                top_visitors.visits DESC;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLibraryVisitsByDepartment()
    {
        $sql = "
            (SELECT
                cl.college_name AS department,
                SUM(CASE WHEN al.id IS NOT NULL AND DATE(al.timestamp) = CURDATE() THEN 1 ELSE 0 END) AS today,
                SUM(CASE WHEN al.id IS NOT NULL AND YEARWEEK(al.timestamp) = YEARWEEK(CURDATE()) THEN 1 ELSE 0 END) AS week,
                SUM(CASE WHEN al.id IS NOT NULL AND MONTH(al.timestamp) = MONTH(CURDATE()) AND YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS month,
                SUM(CASE WHEN al.id IS NOT NULL AND YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS year
            FROM colleges cl
            LEFT JOIN courses c ON cl.college_id = c.college_id
            LEFT JOIN attendance_logs al ON c.course_id = al.course_id
            GROUP BY cl.college_name)
            UNION ALL
            (SELECT
                'Uncategorized' AS department,
                SUM(CASE WHEN DATE(al.timestamp) = CURDATE() THEN 1 ELSE 0 END) AS today,
                SUM(CASE WHEN YEARWEEK(al.timestamp) = YEARWEEK(CURDATE()) THEN 1 ELSE 0 END) AS week,
                SUM(CASE WHEN MONTH(al.timestamp) = MONTH(CURDATE()) AND YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS month,
                SUM(CASE WHEN YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS year
            FROM attendance_logs al
            WHERE al.course_id IS NULL AND al.student_id IS NOT NULL)
            UNION ALL
            (SELECT
                'TOTAL' AS department,
                SUM(CASE WHEN DATE(al.timestamp) = CURDATE() THEN 1 ELSE 0 END) AS today,
                SUM(CASE WHEN YEARWEEK(al.timestamp) = YEARWEEK(CURDATE()) THEN 1 ELSE 0 END) AS week,
                SUM(CASE WHEN MONTH(al.timestamp) = MONTH(CURDATE()) AND YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS month,
                SUM(CASE WHEN YEAR(al.timestamp) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS year
            FROM attendance_logs al
            WHERE al.student_id IS NOT NULL)
            ORDER BY CASE WHEN department = 'TOTAL' THEN 2 WHEN department = 'Uncategorized' THEN 1 ELSE 0 END, department ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDeletedBooksReport()
    {
        $sql = "
            SELECT
                YEAR(deleted_at) as year,
                SUM(CASE WHEN MONTH(deleted_at) = MONTH(CURDATE()) AND YEAR(deleted_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as month,
                SUM(CASE WHEN DATE(deleted_at) = CURDATE() THEN 1 ELSE 0 END) as today,
                COUNT(*) as count
            FROM books
            WHERE deleted_by IS NOT NULL
            GROUP BY YEAR(deleted_at)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- New Methods for PDF Generation with Date Range --- 

    public function getLibraryResourcesData($startDate, $endDate)
    {
        // Placeholder data as logic is not specified
        return [
            [ 'year' => 2025, 'title' => '-', 'volume' => '-', 'processed' => '-' ],
            [ 'year' => 2026, 'title' => '-', 'volume' => '-', 'processed' => '-' ],
            [ 'year' => 2027, 'title' => '-', 'volume' => '-', 'processed' => '-' ],
        ];
    }

    public function getDeletedBooksData($startDate, $endDate)
    {
        $sql = "
            SELECT
                SUM(CASE WHEN DATE(deleted_at) = :endDate THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN YEARWEEK(deleted_at, 1) = YEARWEEK(:endDate, 1) THEN 1 ELSE 0 END) as week,
                SUM(CASE WHEN MONTH(deleted_at) = MONTH(:endDate) AND YEAR(deleted_at) = YEAR(:endDate) THEN 1 ELSE 0 END) as month,
                COUNT(*) as year
            FROM books
            WHERE deleted_at BETWEEN :startDate AND :endDate;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCirculatedBooksData($startDate, $endDate)
    {
        $sql = "
            SELECT
                'Student' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = :endDate THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(:endDate, 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(:endDate) AND MONTH(bt.borrowed_at) = MONTH(:endDate) THEN bti.item_id END) AS month,
                COUNT(bti.item_id) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.student_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bt.borrowed_at BETWEEN :startDate AND :endDate
            UNION ALL
            SELECT
                'Faculty' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = :endDate THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(:endDate, 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(:endDate) AND MONTH(bt.borrowed_at) = MONTH(:endDate) THEN bti.item_id END) AS month,
                COUNT(bti.item_id) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.faculty_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bt.borrowed_at BETWEEN :startDate AND :endDate
            UNION ALL
            SELECT
                'Staff' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = :endDate THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(:endDate, 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(:endDate) AND MONTH(bt.borrowed_at) = MONTH(:endDate) THEN bti.item_id END) AS month,
                COUNT(bti.item_id) AS year
            FROM borrow_transactions bt JOIN borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE bt.staff_id IS NOT NULL AND bti.status IN ('borrowed', 'returned', 'overdue') AND bt.borrowed_at BETWEEN :startDate AND :endDate;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopVisitorsData($startDate, $endDate)
    {
        $sql = "
            SELECT
                CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                s.student_number,
                COALESCE(c.course_code, 'N/A') AS course_code,
                top_visitors.visits
            FROM (
                SELECT
                    student_number,
                    COUNT(*) AS visits
                FROM
                    attendance_logs
                WHERE
                    timestamp BETWEEN :startDate AND :endDate AND student_number IS NOT NULL AND student_number != ''
                GROUP BY
                    student_number
                ORDER BY
                    visits DESC
                LIMIT 10
            ) AS top_visitors
            JOIN
                students s ON top_visitors.student_number = s.student_number
            JOIN
                users u ON s.user_id = u.user_id
            LEFT JOIN
                courses c ON s.course_id = c.course_id
            ORDER BY
                top_visitors.visits DESC;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLibraryVisitsData($startDate, $endDate)
    {
        $sql = "
            SELECT
                cl.college_name AS department,
                SUM(CASE WHEN DATE(al.timestamp) = :endDate THEN 1 ELSE 0 END) AS today,
                SUM(CASE WHEN YEARWEEK(al.timestamp, 1) = YEARWEEK(:endDate, 1) THEN 1 ELSE 0 END) AS week,
                SUM(CASE WHEN MONTH(al.timestamp) = MONTH(:endDate) AND YEAR(al.timestamp) = YEAR(:endDate) THEN 1 ELSE 0 END) AS month,
                COUNT(al.id) AS year
            FROM colleges cl
            LEFT JOIN courses c ON cl.college_id = c.college_id
            LEFT JOIN attendance_logs al ON c.course_id = al.course_id
            WHERE al.timestamp BETWEEN :startDate AND :endDate
            GROUP BY cl.college_name;
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['startDate' => $startDate, 'endDate' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
