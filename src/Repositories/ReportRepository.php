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
            SELECT 'Student' AS category,
              COALESCE(SUM(CASE WHEN DATE(borrowed_at) = CURDATE() THEN 1 ELSE 0 END), 0) AS today,
              COALESCE(SUM(CASE WHEN YEARWEEK(borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) AND MONTH(borrowed_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year
            FROM borrow_transactions
            WHERE student_id IS NOT NULL
              AND status IN ('borrowed','returned','overdue')

            UNION ALL

            SELECT 'Faculty' AS category,
              COALESCE(SUM(CASE WHEN DATE(borrowed_at) = CURDATE() THEN 1 ELSE 0 END), 0) AS today,
              COALESCE(SUM(CASE WHEN YEARWEEK(borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) AND MONTH(borrowed_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year
            FROM borrow_transactions
            WHERE faculty_id IS NOT NULL
              AND status IN ('borrowed','returned','overdue')

            UNION ALL

            SELECT 'Staff' AS category,
              COALESCE(SUM(CASE WHEN DATE(borrowed_at) = CURDATE() THEN 1 ELSE 0 END), 0) AS today,
              COALESCE(SUM(CASE WHEN YEARWEEK(borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) AND MONTH(borrowed_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year
            FROM borrow_transactions
            WHERE staff_id IS NOT NULL
              AND status IN ('borrowed','returned','overdue')

            UNION ALL

            SELECT 'TOTAL' AS category,
              COALESCE(SUM(CASE WHEN DATE(borrowed_at) = CURDATE() THEN 1 ELSE 0 END), 0) AS today,
              COALESCE(SUM(CASE WHEN YEARWEEK(borrowed_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END), 0) AS week,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) AND MONTH(borrowed_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END), 0) AS month,
              COALESCE(SUM(CASE WHEN YEAR(borrowed_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END), 0) AS year
            FROM borrow_transactions
            WHERE status IN ('borrowed','returned','overdue');
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
