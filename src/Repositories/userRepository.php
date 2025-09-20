<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;
use PDOException;

class UserRepository
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function findByIdentifier(string $identifier)
  {
    try {
      // try student_number
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id, 
                u.username, 
                u.password,
                u.full_name, 
                u.is_active, 
                u.role,
                s.student_id, 
                s.student_number, 
                s.year_level, 
                s.course
            FROM students s
            LEFT JOIN users u ON u.user_id = s.user_id
            WHERE LOWER(s.student_number) = LOWER(:identifier)
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $student = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($student) {
        return $student;
      }

      //fallback by username
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id, 
                u.username, 
                u.password,
                u.full_name, 
                u.is_active, 
                u.role,
                s.student_id, 
                s.student_number, 
                s.year_level, 
                s.course
            FROM users u
            LEFT JOIN students s ON u.user_id = s.user_id
            WHERE LOWER(u.username) = LOWER(:identifier)
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $user = $stmt->fetch(\PDO::FETCH_ASSOC);

      return $user ?: null;
    } catch (\PDOException $e) {
      error_log("[UserRepository::findByIdentifier] " . $e->getMessage());
      return null;
    }
  }



  public function createUser(array $data)
  {
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $this->db->prepare("
        INSERT INTO users (username, password, full_name, role) 
        VALUES (:username, :password, :full_name, :role)
    ");
    return $stmt->execute([
      'username' => htmlspecialchars(trim($data['username'])),
      'password' => $hashedPassword,
      'full_name' => htmlspecialchars(trim($data['full_name'])),
      'role' => in_array($data['role'], ['admin', 'librarian', 'student', 'scanner', 'superadmin']) ? $data['role'] : 'student'
    ]);
  }

  public function getAllUsers()
  {
    $stmt = $this->db->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function findByStudentNumber(string $studentNumber)
  {
    $stmt = $this->db->prepare("
        SELECT 
            u.user_id, 
            u.username, 
            u.full_name, 
            u.is_active, 
            u.role,
            s.student_id, 
            s.student_number, 
            s.year_level, 
            s.course
        FROM users u
        LEFT JOIN students s ON u.user_id = s.user_id
        WHERE s.student_number = :student_number
        LIMIT 1
    ");
    $stmt->execute(['student_number' => $studentNumber]);
    return $stmt->fetch();
  }
}
