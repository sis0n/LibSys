<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByIdentifier(string $identifier): ?array{
        try {
            $sql = "
                SELECT u.*, s.student_number 
                FROM users u
                LEFT JOIN students s ON u.user_id = s.user_id
                WHERE (u.username = :identifier OR s.student_number = :identifier)
                  AND u.is_active = 1
                LIMIT 1
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':identifier', $identifier, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function isAdmin(array $user): bool {
        return isset($user['role']) && $user['role'] === 'admin';
    }

    public static function isLibrarian(array $user): bool {
        return isset($user['role']) && $user['role'] === 'librarian';
    }

    public static function isStudent(array $user): bool {
        return isset($user['role']) && $user['role'] === 'student';
    }

    public static function isSuperadmin(array $user): bool {
        return isset($user['role']) && $user['role'] === 'superadmin';
    }
    public static function isScanner(array $user): bool {
        return isset($user['role']) && $user['role'] === 'scanner';
    }
}
