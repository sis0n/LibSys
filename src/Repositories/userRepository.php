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
      $stmt = $this->db->prepare("
            SELECT u.*, s.student_id, s.student_number, s.year_level, s.course
            FROM users u
            LEFT JOIN students s ON u.user_id = s.user_id
            WHERE (u.username = :identifier OR s.student_number = :identifier)
              AND u.is_active = 1
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user ?: null;
    } catch (PDOException $e) {
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
        SELECT u.*, s.student_id, s.student_number, s.year_level, s.course
        FROM users u
        LEFT JOIN students s ON u.user_id = s.user_id
        WHERE s.student_number = :student_number
        LIMIT 1
    ");
    $stmt->execute(['student_number' => $studentNumber]);
    return $stmt->fetch();
  }
}
