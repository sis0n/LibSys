<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StaffRepository
{
  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function insertStaff(int $userId, string $position, string $contact, string $status = 'active'): int
  {
    $employeeId = $this->generateUniqueEmployeeId();

    $stmt = $this->db->prepare("
            INSERT INTO staff (user_id, employee_id, position, contact, status, created_at)
            VALUES (:user_id, :employee_id, :position, :contact, :status, NOW())
        ");
    $stmt->execute([
      ':user_id'     => $userId,
      ':employee_id' => $employeeId,
      ':position'    => $position,
      ':contact'     => $contact,
      ':status'      => $status
    ]);

    return (int)$this->db->lastInsertId();
  }

  public function getStaffByUserId(int $userId)
  {
    $stmt = $this->db->prepare("SELECT * FROM staff WHERE user_id = :user_id LIMIT 1");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  private function generateUniqueEmployeeId(): string
  {
    $prefix = 'EMP';
    $year = date('Y');

    $stmt = $this->db->query("SELECT employee_id FROM staff ORDER BY staff_id DESC LIMIT 1");
    $lastId = $stmt->fetchColumn();

    if ($lastId && preg_match('/(\d+)$/', $lastId, $matches)) {
      $number = str_pad($matches[1] + 1, 4, '0', STR_PAD_LEFT);
    } else {
      $number = '0001';
    }

    return "{$prefix}-{$year}-{$number}";
  }
}
