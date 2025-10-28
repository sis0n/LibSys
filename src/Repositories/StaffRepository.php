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

  public function insertStaff(int $userId, string $employeeId, string $position, string $contactNumber, string $status = 'active'): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO staff (user_id, employee_id, position, contact_number, status, created_at)
            VALUES (:user_id, :employee_id, :position, :contact_number, :status, NOW())
        ");
    $stmt->execute([
      ':user_id' => $userId,
      ':employee_id' => $employeeId,
      ':position' => $position,
      ':contact_number' => $contactNumber,
      ':status' => $status
    ]);
    return (int)$this->db->lastInsertId();
  }

  public function getStaffByUserId(int $userId)
  {
    $stmt = $this->db->prepare("SELECT * FROM staff WHERE user_id = :user_id LIMIT 1");
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}
