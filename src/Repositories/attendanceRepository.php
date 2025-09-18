<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class AttendanceRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function logAttendance(int $studentId, string $method = 'qr'): bool
    {
      try {
          $stmt = $this->db->prepare("
              INSERT INTO attendance_logs (student_id, method, timestamp)
              VALUES (:student_id, :method, NOW())
          ");

          return $stmt->execute([
              'student_id' => $studentId,
              'method' => $method
          ]);
      } catch (PDOException $e) {
          // Optional: log to file
          return false;
      }
    }
}
