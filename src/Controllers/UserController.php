<?php
namespace App\Controllers;

use App\Core\Database; // Ito yung galing sa src/Core/Database.php
use PDO;

class UserController {
    public function showLogin() {
        include __DIR__ . "/../Views/auth/login.php";
    }

    public function login() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if (!$username || !$password) {
                echo "Username and password are required.";
                return;
            }

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT u.*, s.student_number 
                FROM users u 
                LEFT JOIN students s ON u.user_id = s.user_id
                WHERE (u.username = :identifier OR s.student_number = :identifier)
                AND u.is_active = 1
                LIMIT 1
            ");
            $stmt->execute(['identifier' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user['password'] === $password) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'] ?? $user['student_number'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['fullname'] = $user['full_name'];

                // redirect depende sa role
                header("Location: /libsys/src/Views/dashboard/{$user['role']}.php");
                exit;
            } else {
                echo "Invalid username or password.";
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /libsys/public/index.php?url=login");
        exit;
    }
}
