<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StudentProfileRepository
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
                s.student_id,
                s.student_number,
                s.course,
                s.year_level,
                s.section,
                s.contact,
                s.registration_form,
                s.profile_updated,
                s.can_edit_profile
            FROM users u
            LEFT JOIN students s ON u.user_id = s.user_id
            WHERE u.user_id = :userId AND u.deleted_at IS NULL
        ");
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
  }

  public function updateStudentProfile(int $userId, array $data): bool
  {
    $allowedFields = [
      'course',
      'year_level',
      'section',
      'contact',
      'registration_form',
      'profile_updated',
      'can_edit_profile'
    ];

    $sqlParts = [];
    $params = [':user_id' => $userId];

    foreach ($allowedFields as $field) {
      if (isset($data[$field])) {
        $sqlParts[] = "$field = :$field";
        $params[":$field"] = $data[$field];
      }
    }

    if (empty($sqlParts)) {
      return true;
    }

    $sql = "UPDATE students SET " . implode(", ", $sqlParts) . " WHERE user_id = :user_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($params);
  }

  public function setEditAccess(int $userId, bool $allow = true): bool
  {
    $stmt = $this->db->prepare("
            UPDATE students 
            SET can_edit_profile = :allow 
            WHERE user_id = :user_id
        ");
    return $stmt->execute([
      ':allow' => $allow ? 1 : 0,
      ':user_id' => $userId
    ]);
  }
}
