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

  public function insertUser(array $data): int
  {
    $stmt = $this->db->prepare("
    INSERT INTO users (username, password, full_name, email, role, is_active, created_at)
    VALUES (:username, :password, :full_name, :email, :role, :is_active, :created_at)
  ");

    $stmt->execute([
      ':username'   => $data['username'],
      ':password'   => $data['password'],
      ':full_name'  => $data['full_name'],
      ':email'      => $data['email'] ?? null,
      ':role'       => $data['role'],
      ':is_active'  => $data['is_active'] ?? 1,
      ':created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
    ]);

    return (int)$this->db->lastInsertId();
  }


  public function updateUser(int $id, array $data): bool
  {
    $fields = [];
    $params = [':id' => $id];

    if (isset($data['full_name'])) {
      $fields[] = "full_name = :full_name";
      $params[':full_name'] = $data['full_name'];
    }
    if (isset($data['username'])) {
      $fields[] = "username = :username";
      $params[':username'] = $data['username'];
    }
    if (isset($data['password']) && !empty($data['password'])) {
      $fields[] = "password = :password";
      $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    }
    if (isset($data['role'])) {
      $fields[] = "role = :role";
      $params[':role'] = $data['role'];
    }
    if (isset($data['status'])) {
      $fields[] = "status = :status";
      $params[':status'] = $data['status'];
    }

    if (empty($fields)) return false;

    $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :id";
    return $this->db->execute($query, $params);
  }

  public function getAllUsers(): array
  {
    try {
      $stmt = $this->db->query("
        SELECT 
          user_id,
          username,
          full_name,
          email,
          role,
          is_active,
          created_at
        FROM users
        WHERE deleted_at IS NULL
        ORDER BY user_id DESC
      ");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log('[UserRepository::getAllUsers] ' . $e->getMessage());
      return [];
    }
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

  public function getUserById($id)
  {
    $stmt = $this->db->prepare("SELECT user_id, full_name, username, role FROM users WHERE user_id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function searchUsers(string $query): array
  {
    $query = "%" . strtolower($query) . "%";
    $stmt = $this->db->prepare("
        SELECT user_id, username, full_name, email, role, is_active, created_at
        FROM users
        WHERE LOWER(full_name) LIKE :query
           OR LOWER(username) LIKE :query
           OR LOWER(email) LIKE :query
        ORDER BY user_id DESC
    ");
    $stmt->execute(['query' => $query]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deleteUserWithCascade(int $userId, int $deletedBy, $studentRepo): bool
  {
    try {
      // kunin user info
      $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id");
      $stmt->execute([':id' => $userId]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        throw new \Exception("User not found.");
      }

      //  kapag yung dinelete is student, imamark as deleted sa students table 
      if ($user['role'] === 'student') {
        $stmt = $this->db->prepare("
        UPDATE students 
        SET deleted_at = NOW(), deleted_by = :deleted_by 
        WHERE user_id = :uid
      ");
        $stmt->execute([
          ':deleted_by' => $deletedBy,
          ':uid' => $userId
        ]);

        // iinsert sa deleted_students table pag student yung dinelete
        $stmt = $this->db->prepare("
        INSERT INTO deleted_students (student_id, user_id, student_number, course, year_level, status, deleted_by)
        SELECT student_id, user_id, student_number, course, year_level, status, :deleted_by
        FROM students WHERE user_id = :uid
      ");
        $stmt->execute([
          ':uid' => $userId,
          ':deleted_by' => $deletedBy
        ]);
      }

      // imamark lang as deleted sa users table pag hindi student yung dinelete
      $stmt = $this->db->prepare("
      UPDATE users 
      SET deleted_at = NOW(), deleted_by = :deleted_by 
      WHERE user_id = :uid
    ");
      $stmt->execute([
        ':deleted_by' => $deletedBy,
        ':uid' => $userId
      ]);

      // iinsert sa deleted_users kahit student or di student yung idedelete
      $stmt = $this->db->prepare("
      INSERT INTO deleted_users (user_id, username, full_name, email, role, deleted_by)
      SELECT user_id, username, full_name, email, role, :deleted_by
      FROM users WHERE user_id = :uid
    ");
      $stmt->execute([
        ':uid' => $userId,
        ':deleted_by' => $deletedBy
      ]);

      return true;
    } catch (PDOException $e) {
      error_log("[UserRepository::deleteUserWithCascade] " . $e->getMessage());
      throw $e;
    }
  }
}
