<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ReportRepository
{
  protected $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getTopVisitorsPerCourse(string $startDate, string $endDate, int $limit = 10)
  {
    $sql = "
        SELECT 
            s.course, 
            CONCAT(u.first_name, ' ', IFNULL(u.middle_name, ''), ' ', u.last_name, ' ', IFNULL(u.suffix, '')) AS full_name,
            COUNT(*) AS visit_count
        FROM attendance_logs al
        JOIN students s ON s.user_id = al.user_id
        JOIN users u ON u.user_id = s.user_id
        WHERE al.timestamp BETWEEN :start AND :end
        GROUP BY s.course, al.user_id
        ORDER BY visit_count DESC
        LIMIT :limit
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':start', $startDate);
    $stmt->bindValue(':end', $endDate);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getWeeklyActivity(string $startDate, string $endDate)
  {
    $sql = "
            SELECT 
                WEEK(a.date, 1) as week_number,
                COUNT(DISTINCT a.user_id) as total_visits,
                COUNT(bi.item_id) as total_borrows
            FROM attendance a
            LEFT JOIN borrow_transactions bt ON bt.borrowed_at BETWEEN :start AND :end
            LEFT JOIN borrow_transaction_items bi ON bi.transaction_id = bt.transaction_id
            WHERE a.date BETWEEN :start AND :end
            GROUP BY week_number
            ORDER BY week_number ASC
        ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':start', $startDate);
    $stmt->bindValue(':end', $endDate);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getLibraryResources()
  {
    $sql = "
            SELECT COUNT(*) as total_books
            FROM books
            WHERE is_archived = 0 AND deleted_at IS NULL
        ";

    $stmt = $this->db->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getCirculatedBooks(string $startDate, string $endDate)
  {
    $sql = "
            SELECT COUNT(bi.item_id) as total_circulated
            FROM borrow_transaction_items bi
            JOIN borrow_transactions bt ON bt.transaction_id = bi.transaction_id
            WHERE bt.borrowed_at BETWEEN :start AND :end
        ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':start', $startDate);
    $stmt->bindValue(':end', $endDate);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getLibraryVisits(string $startDate, string $endDate)
  {
    $sql = "
            SELECT COUNT(*) as total_visits
            FROM attendance
            WHERE date BETWEEN :start AND :end
        ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':start', $startDate);
    $stmt->bindValue(':end', $endDate);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function getTopVisitorsOverall(string $startDate, string $endDate, int $limit = 10)
  {
    $sql = "
            SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) as full_name, COUNT(a.id) as visit_count
            FROM users u
            LEFT JOIN attendance a ON a.user_id = u.user_id AND a.date BETWEEN :start AND :end
            GROUP BY u.user_id
            ORDER BY visit_count DESC
            LIMIT :limit
        ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':start', $startDate);
    $stmt->bindValue(':end', $endDate);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
