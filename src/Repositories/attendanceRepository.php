<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Core\Database;
use PDO;
use PDOException;

class AttendanceRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function logAttendance(Attendance $attendance): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO attendance_logs 
                    (user_id, student_number, full_name, year_level, course, method, timestamp)
                VALUES 
                    (:user_id, :student_number, :full_name, :year_level, :course, :method, :timestamp)
            ");

            return $stmt->execute([
                'user_id' => $attendance->studentId,
                'student_number' => $attendance->studentNumber,
                'full_name' => $attendance->fullName,
                'year_level' => $attendance->yearLevel,
                'course' => $attendance->course,
                'method' => $attendance->method,
                'timestamp' => $attendance->timestamp
            ]);
        } catch (PDOException $e) {
            error_log("Attendance log failed: " . $e->getMessage());
            echo "DB Error: " . $e->getMessage();
            return false;
        }
    }
    public function getByUserId(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT student_number, full_name, course, year_level, method, timestamp
                FROM attendance_logs
                WHERE user_id = :user_id
                ORDER BY timestamp DESC
            ");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Fetch attendance failed: " . $e->getMessage());
            return [];
        }
    }

    public function getByUserAndDate(int $userId, string $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM attendance_logs WHERE user_id = ? AND DATE(timestamp) = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch();
    }
}
