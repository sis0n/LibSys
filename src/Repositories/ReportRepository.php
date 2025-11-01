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
            FROM
                borrow_transactions bt
            JOIN
                borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE
                bt.student_id IS NOT NULL
                AND bti.status IN ('borrowed', 'returned', 'overdue')
                AND bti.book_id IS NOT NULL

            UNION ALL

            SELECT
                'Faculty' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM
                borrow_transactions bt
            JOIN
                borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE
                bt.faculty_id IS NOT NULL
                AND bti.status IN ('borrowed', 'returned', 'overdue')
                AND bti.book_id IS NOT NULL

            UNION ALL

            SELECT
                'Staff' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM
                borrow_transactions bt
            JOIN
                borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE
                bt.staff_id IS NOT NULL
                AND bti.status IN ('borrowed', 'returned', 'overdue')
                AND bti.book_id IS NOT NULL

            UNION ALL

            SELECT
                'TOTAL' AS category,
                COUNT(CASE WHEN DATE(bt.borrowed_at) = CURDATE() THEN bti.item_id END) AS today,
                COUNT(CASE WHEN YEARWEEK(bt.borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN bti.item_id END) AS week,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) AND MONTH(bt.borrowed_at) = MONTH(CURDATE()) THEN bti.item_id END) AS month,
                COUNT(CASE WHEN YEAR(bt.borrowed_at) = YEAR(CURDATE()) THEN bti.item_id END) AS year
            FROM
                borrow_transactions bt
            JOIN
                borrow_transaction_items bti ON bt.transaction_id = bti.transaction_id
            WHERE
                bti.status IN ('borrowed', 'returned', 'overdue')
                AND bti.book_id IS NOT NULL;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopVisitorsByYear()
    {
        $sql = "
            SELECT 
                MAX(full_name) as full_name, 
                MAX(student_number) as student_number, 
                MAX(course) as course, 
                COUNT(user_id) AS visits
            FROM 
                attendance_logs
            WHERE 
                YEAR(timestamp) = YEAR(CURDATE())
                AND user_id IS NOT NULL
            GROUP BY 
                user_id
            ORDER BY 
                visits DESC
            LIMIT 10;
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
            FROM
                books
            WHERE
                deleted_by IS NOT NULL
            GROUP BY
                YEAR(deleted_at)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
