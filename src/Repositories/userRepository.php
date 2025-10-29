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
      // ----------- Try student first -----------
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id, 
                u.username, 
                u.password, 
                u.first_name, 
                u.middle_name, 
                u.last_name, 
                u.suffix, 
                u.profile_picture, 
                u.is_active, 
                u.role,
                u.email,
                s.student_id, 
                s.student_number, 
                s.year_level, 
                s.course,
                s.section
            FROM students s
            LEFT JOIN users u ON u.user_id = s.user_id
            WHERE UPPER(s.student_number) = UPPER(:identifier)
            AND u.deleted_at IS NULL
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $student = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($student) {
        return $student;
      }

      // ----------- Try by username fallback -----------
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id, 
                u.username, 
                u.password, 
                u.first_name, 
                u.middle_name, 
                u.last_name, 
                u.suffix, 
                u.profile_picture, 
                u.is_active, 
                u.role,
                u.email,
                s.student_id, 
                s.student_number, 
                s.year_level, 
                s.course,
                s.section
            FROM users u
            LEFT JOIN students s ON u.user_id = s.user_id
            WHERE LOWER(u.username) = LOWER(:identifier)
            AND u.deleted_at IS NULL
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        return $user;
      }

      // ----------- Try faculty table if no student found -----------
      $stmt = $this->db->prepare("
            SELECT 
                u.user_id,
                u.username,
                u.password,
                u.first_name,
                u.middle_name,
                u.last_name,
                u.suffix,
                u.profile_picture,
                u.is_active,
                u.role,
                u.email,
                f.faculty_id,
                f.department
            FROM faculty f
            LEFT JOIN users u ON u.user_id = f.user_id
            WHERE LOWER(u.username) = LOWER(:identifier)
            AND u.deleted_at IS NULL
            LIMIT 1
        ");
      $stmt->execute(['identifier' => $identifier]);
      $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($faculty) {
        $faculty['role'] = 'faculty'; // ensure role is set
        return $faculty;
      }

      return null;
    } catch (\PDOException $e) {
      error_log("[UserRepository::findByIdentifier] " . $e->getMessage());
      return null;
    }
  }



  public function insertUser(array $data): int
  {
    $stmt = $this->db->prepare("
            INSERT INTO users (username, password, first_name, middle_name, last_name, suffix, email, role, is_active, created_at)
            VALUES (:username, :password, :first_name, :middle_name, :last_name, :suffix, :email, :role, :is_active, :created_at)
        ");

    $stmt->execute([
      ':username' => $data['username'],
      ':password' => $data['password'],
      ':first_name' => $data['first_name'],
      ':middle_name' => $data['middle_name'] ?? null,
      ':last_name' => $data['last_name'],
      ':suffix' => $data['suffix'] ?? null,
      ':email' => $data['email'] ?? null,
      ':role' => $data['role'],
      ':is_active' => $data['is_active'] ?? 1,
      ':created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
    ]);

    return (int)$this->db->lastInsertId();
  }

  public function updateUser(int $id, array $data): bool
  {
    $fields = [];
    $params = [':id' => $id];

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
    if (isset($data['suffix'])) {
      $fields[] = "suffix = :suffix";
      $params[':suffix'] = $data['suffix'];
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
    if (isset($data['profile_picture'])) {
      $fields[] = "profile_picture = :profile_picture";
      $params[':profile_picture'] = $data['profile_picture'];
    }

    if (empty($fields)) {
      return false;
    }

    $fields[] = "updated_at = NOW()";

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
                u.user_id,
                u.username,
                u.first_name,
                u.middle_name,
                u.last_name,
                u.suffix,
                u.email,
                u.role,
                u.is_active,
                u.created_at,
                GROUP_CONCAT(um.module_name) AS modules
            FROM users u
            LEFT JOIN user_module_permissions um ON um.user_id = u.user_id
            WHERE u.deleted_at IS NULL
            GROUP BY u.user_id
            ORDER BY u.user_id DESC
        ");
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($users as &$user) {
        $user['modules'] = $user['modules'] ? explode(',', $user['modules']) : [];
      }

      return $users;
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
                    u.suffix,
                    u.profile_picture,
                    u.is_active, 
                    u.role,
                    s.student_id, 
                    s.student_number, 
                    s.year_level, 
                    s.course,
                    s.section
                FROM users u
                JOIN students s ON u.user_id = s.user_id
                WHERE UPPER(s.student_number) = UPPER(:student_number)
                AND u.deleted_at IS NULL
                LIMIT 1
            ");
      $stmt->execute(['student_number' => $studentNumber]);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
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
                suffix,
                username, 
                email,
                role, 
                is_active 
            FROM users 
            WHERE user_id = :id AND deleted_at IS NULL
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
                suffix,
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

  public function deleteUserWithCascade(int $userId, int $deletedBy): bool
  {
    try {
      $this->db->beginTransaction();

      $stmtCheck = $this->db->prepare("SELECT role FROM users WHERE user_id = :id AND deleted_at IS NULL");
      $stmtCheck->execute([':id' => $userId]);
      $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        $this->db->rollBack();
        error_log("[UserRepository::deleteUserWithCascade] User $userId not found or already deleted.");
        return false;
      }

      $stmtUpdateUser = $this->db->prepare("
                UPDATE users 
                SET deleted_at = NOW(), deleted_by = :deleted_by 
                WHERE user_id = :uid AND deleted_at IS NULL 
            ");
      $stmtUpdateUser->execute([
        ':deleted_by' => $deletedBy,
        ':uid' => $userId
      ]);

      if ($stmtUpdateUser->rowCount() === 0) {
        $this->db->rollBack();
        error_log("[UserRepository::deleteUserWithCascade] Failed to update user $userId.");
        return false;
      }

      if (isset($user['role']) && strtolower($user['role']) === 'student') {
        $stmtUpdateStudent = $this->db->prepare("
                    UPDATE students 
                    SET deleted_at = NOW(), deleted_by = :deleted_by 
                    WHERE user_id = :uid AND deleted_at IS NULL
                ");
        $stmtUpdateStudent->execute([
          ':deleted_by' => $deletedBy,
          ':uid' => $userId
        ]);
      }

      $this->db->commit();
      return true;
    } catch (PDOException $e) {
      $this->db->rollBack();
      error_log("[UserRepository::deleteUserWithCascade] PDOException: " . $e->getMessage());
      return false;
    } catch (\Exception $e) {
      $this->db->rollBack();
      error_log("[UserRepository::deleteUserWithCascade] Exception: " . $e->getMessage());
      return false;
    }
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

  public function findFacultyByIdentifier(string $identifier): ?array
  {
    $stmt = $this->db->prepare("
        SELECT * FROM faculty
        WHERE (username = :identifier OR email = :identifier)
        AND deleted_at IS NULL
        LIMIT 1
    ");
    $stmt->execute(['identifier' => $identifier]);
    return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
  }

  public function insertStudent(array $data): int
  {
    $userId = $this->insertUser([
      'first_name' => $data['first_name'],
      'middle_name' => $data['middle_name'] ?? null,
      'last_name' => $data['last_name'],
      'username' => $data['username'],
      'role' => 'student',
      'password' => $data['password'] ?? password_hash('defaultpassword', PASSWORD_DEFAULT),
      'is_active' => $data['is_active'] ?? 1,
      'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
    ]);

    $stmt = $this->db->prepare("
        INSERT INTO students (user_id, student_number, year_level, course, section)
        VALUES (:user_id, :student_number, :year_level, :course, :section)
    ");

    $stmt->execute([
      ':user_id' => $userId,
      ':student_number' => $data['username'],
      ':year_level' => $data['year_level'] ?? 1,
      ':course' => $data['course'] ?? 'N/A',
      ':section' => $data['section'] ?? 'N/A'
    ]);

    return $userId;
  }
}
