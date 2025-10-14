<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StudentRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function insertStudent(int $userId, string $studentNumber, string $course, int $yearLevel, string $status): int
  {
    $stmt = $this->db->prepare("
    INSERT INTO students (user_id, student_number, course, year_level, status)
    VALUES (:user_id, :student_number, :course, :year_level, :status)
  ");

    $stmt->execute([
      ':user_id' => $userId,
      ':student_number' => $studentNumber,
      ':course' => $course,
      ':year_level' => $yearLevel,
      ':status' => $status
    ]);

    return (int)$this->db->lastInsertId();
  }
}
