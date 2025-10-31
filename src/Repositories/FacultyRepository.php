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
    $uniqueId = $this->generateUniqueFacultyId();

    $stmt = $this->db->prepare("
        INSERT INTO faculty (user_id, faculty_id, department, contact, status, created_at)
        VALUES (:user_id, :unique_id, :department, :contact, :status, NOW())
    ");
    $stmt->execute([
      ':user_id' => $userId,
      ':unique_id' => $uniqueId,
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

  private function generateUniqueFacultyId(): string
  {
    $prefix = 'FAC';
    $year = date('Y');

    $stmt = $this->db->query("SELECT faculty_id FROM faculty ORDER BY faculty_id DESC LIMIT 1");
    $lastId = $stmt->fetchColumn();

    if ($lastId) {
      preg_match('/(\d+)$/', $lastId, $matches);
      $number = str_pad($matches[1] + 1, 4, '0', STR_PAD_LEFT);
    } else {
      $number = '0001';
    }

    return "{$prefix}-{$year}-{$number}";
  }
}
