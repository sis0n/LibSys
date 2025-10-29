<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class FacultyProfileRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getProfileByUserId(int $userId): ?array
  {
    $stmt = $this->db->prepare("
            SELECT 
                u.user_id,
                u.username,
                u.first_name,
                u.middle_name,
                u.last_name,
                u.suffix,
                u.email,
                u.profile_picture,
                u.role,
                u.is_active,
                f.unique_faculty_id,
                f.department,
                f.contact,
                f.status,
                f.profile_updated
            FROM users u
            LEFT JOIN faculty f ON u.user_id = f.user_id
            WHERE u.user_id = :userId AND u.deleted_at IS NULL
        ");
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
  }

  public function updateFacultyProfile(int $userId, array $data): bool
  {
    $allowedFields = [
      'department',
      'contact',
      'profile_updated',
      'status'
    ];

    $sqlParts = [];
    $params = [':user_id' => $userId];

    foreach ($allowedFields as $field) {
      if (isset($data[$field])) {
        $sqlParts[] = "$field = :$field";
        $params[":$field"] = $data[$field];
      }
    }

    if (empty($sqlParts)) return true;

    $sql = "UPDATE faculty SET " . implode(", ", $sqlParts) . " WHERE user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }

  public function setEditAccess(int $userId, bool $allow = true): bool
  {
    $stmt = $this->db->prepare("
            UPDATE faculty 
            SET profile_updated = :allow
            WHERE user_id = :user_id
        ");
    return $stmt->execute([
      ':allow' => $allow ? 0 : 1, // 0 = can edit, 1 = locked
      ':user_id' => $userId
    ]);
  }
}
