<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class FacultyRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function insertFaculty(int $userId, string $department, string $contact, string $status = 'active'): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO faculty (user_id, department, contact, status, created_at)
            VALUES (:user_id, :department, :contact, :status, NOW())
        ");
    $stmt->execute([
      ':user_id' => $userId,
      ':department' => $department,
      ':contact' => $contact,
      ':status' => $status
    ]);
    return (int)$this->db->lastInsertId();
  }

  public function getFacultyByUserId(int $userId)
  {
    $stmt = $this->db->prepare("SELECT * FROM faculty WHERE user_id = :user_id LIMIT 1");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
