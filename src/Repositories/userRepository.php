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
                    u.user_id, u.username, u.password, u.first_name, u.middle_name, u.last_name, u.is_active, u.role,
                    s.student_id, s.student_number, s.year_level, s.course
                FROM students s
                LEFT JOIN users u ON u.user_id = s.user_id
                WHERE UPPER(s.student_number) = UPPER(:identifier)
                AND u.deleted_at IS NULL
                LIMIT 1
            ");
      $stmt->execute(['identifier' => $identifier]);
      $student = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($student) {
        return $student;
      }

      $stmt = $this->db->prepare("
                SELECT 
                    u.user_id, u.username, u.password, u.first_name, u.middle_name, u.last_name, u.is_active, u.role,
                    s.student_id, s.student_number, s.year_level, s.course
                FROM users u
                LEFT JOIN students s ON u.user_id = s.user_id
                WHERE LOWER(u.username) = LOWER(:identifier)
                AND u.deleted_at IS NULL
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
        INSERT INTO users (username, password, first_name, middle_name, last_name, email, role, is_active, created_at)
        VALUES (:username, :password, :first_name, :middle_name, :last_name, :email, :role, :is_active, :created_at)
    ");

    $stmt->execute([
      ':username'     => $data['username'],
      ':password'     => $data['password'],
      ':first_name'   => $data['first_name'],
      ':middle_name'  => $data['middle_name'],
      ':last_name'    => $data['last_name'],
      ':email'        => $data['email'] ?? null,
      ':role'         => $data['role'],
      ':is_active'    => $data['is_active'] ?? 1,
      ':created_at'   => $data['created_at'] ?? date('Y-m-d H:i:s')
    ]);

    return (int)$this->db->lastInsertId();
  }

  public function updateUser(int $id, array $data): bool
  {
    $fields = [];
    $params = [':id' => $id];

    // Pinalitan ng bagong fields
    if (isset($data['first_name'])) {
      $fields[] = "first_name = :first_name";
      $params[':first_name'] = $data['first_name'];
    }
    if (isset($data['middle_name'])) {
      $fields[] = "middle_name = :middle_name";
      $params[':middle_name'] = $data['middle_name'];
    }
    if (isset($data['last_name'])) {
      $fields[] = "last_name = :last_name";
      $params[':last_name'] = $data['last_name'];
    }
    if (isset($data['username'])) {
      $fields[] = "username = :username";
      $params[':username'] = $data['username'];
    }
    if (isset($data['email'])) {
      $fields[] = "email = :email";
      $params[':email'] = $data['email'];
    }
    if (isset($data['password']) && !empty($data['password'])) {
      $fields[] = "password = :password";
      $params[':password'] = $data['password'];
    }
    if (isset($data['role'])) {
      $fields[] = "role = :role";
      $params[':role'] = $data['role'];
    }
    if (isset($data['is_active'])) {
      $fields[] = "is_active = :is_active";
      $params[':is_active'] = $data['is_active'];
    }

    $fields[] = "updated_at = NOW()";

    if (empty($fields)) {
      return false;
    }

    $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = :id";
    try {
      $stmt = $this->db->prepare($query);
      return $stmt->execute($params);
    } catch (\PDOException $e) {
      error_log("[UserRepository::updateUser] " . $e->getMessage());
      return false;
    }
  }

  public function getAllUsers(): array
  {
    try {
      $stmt = $this->db->query("
            SELECT 
                user_id,
                username,
                first_name,
                middle_name,
                last_name,
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
    try {
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id, 
                u.username, 
                u.first_name, 
                u.middle_name, 
                u.last_name, 
                u.is_active, 
                u.role,
                s.student_id, 
                s.student_number, 
                s.year_level, 
                s.course
            FROM users u
            JOIN students s ON u.user_id = s.user_id
            WHERE UPPER(s.student_number) = UPPER(:student_number)
            AND u.deleted_at IS NULL
            LIMIT 1
        ");
      $stmt->execute(['student_number' => $studentNumber]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      error_log("[UserRepository] findByStudentNumber CALLED with: $studentNumber");
      error_log("[UserRepository] findByStudentNumber query result count: " . ($result ? '1' : '0'));

      return $result; 

    } catch (PDOException $e) {
      error_log("[UserRepository::findByStudentNumber] " . $e->getMessage());
      return false; 
    }
  }

  public function getUserById($id)
  {
    $stmt = $this->db->prepare("
        SELECT 
            user_id, 
            first_name, 
            middle_name, 
            last_name, 
            username, 
            email,
            role, 
            is_active 
        FROM users 
        WHERE user_id = :id
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function searchUsers(string $query): array
  {
    $searchQuery = "%" . strtolower($query) . "%";
    $stmt = $this->db->prepare("
            SELECT 
                user_id, 
                username, 
                first_name, 
                middle_name, 
                last_name, 
                email, 
                role, 
                is_active, 
                created_at
            FROM users
            WHERE (LOWER(first_name) LIKE :query
               OR LOWER(last_name) LIKE :query
               OR LOWER(username) LIKE :query
               OR LOWER(email) LIKE :query)
            AND deleted_at IS NULL
            ORDER BY user_id DESC
        ");
    $stmt->execute(['query' => $searchQuery]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function deleteUserWithCascade(int $userId, int $deletedBy, $studentRepo): bool
  {
    try {
      $this->db->beginTransaction(); 

      $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :id");
      $stmt->execute([':id' => $userId]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        throw new \Exception("User not found.");
      }

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

      $stmt = $this->db->prepare("
            UPDATE users 
            SET deleted_at = NOW(), deleted_by = :deleted_by 
            WHERE user_id = :uid
        ");
      $stmt->execute([
        ':deleted_by' => $deletedBy,
        ':uid' => $userId
      ]);

      $stmt = $this->db->prepare("
            INSERT INTO deleted_users (user_id, username, first_name, middle_name, last_name, email, role, deleted_by)
            SELECT user_id, username, first_name, middle_name, last_name, email, role, :deleted_by
            FROM users WHERE user_id = :uid
        ");
      $stmt->execute([
        ':uid' => $userId,
        ':deleted_by' => $deletedBy
      ]);

      $this->db->commit();
      return true;
    } catch (\Exception $e) { 
      $this->db->rollBack();
      error_log("[UserRepository::deleteUserWithCascade] " . $e->getMessage());
      throw $e;
    }
  }

  public function usernameExists(string $username): bool
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = :username AND deleted_at IS NULL");
    $stmt->execute([':username' => $username]);
    return $stmt->fetchColumn() > 0;
  }

  public function toggleUserStatus(int $userId): bool
  {
    $stmt = $this->db->prepare("
            UPDATE users 
            SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END, updated_at = NOW()
            WHERE user_id = :id
        ");
    return $stmt->execute([':id' => $userId]);
  }

  public function updatePassword(int $userId, string $hashedPassword): bool
  {
    try {
      $stmt = $this->db->prepare("
            UPDATE users
            SET password = :password, updated_at = NOW()
            WHERE user_id = :user_id AND deleted_at IS NULL
        ");
      return $stmt->execute([
        ':password' => $hashedPassword,
        ':user_id' => $userId
      ]);
    } catch (\PDOException $e) {
      error_log("[UserRepository::updatePassword]" . $e->getMessage());
      return false;
    }
  }
}
