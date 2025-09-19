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
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch();
    }

    public function getAllLogs()
    {
        $sql = "SELECT al.id, al.user_id, al.student_number, al.full_name, 
                   al.course, al.year_level, al.method, al.timestamp
            FROM attendance_logs al
            ORDER BY al.timestamp DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertAttendance(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO attendance (user_id, date, first_scan_at, last_scan_at, created_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['user_id'],
            $data['date'],
            $data['first_scan_at'],
            $data['last_scan_at'],
            $data['created_at']
        ]);
    }

    public function updateLastScan(int $attendanceId, string $time): bool
    {
        $stmt = $this->db->prepare("UPDATE attendance SET last_scan_at = ? WHERE id = ?");
        return $stmt->execute([$time, $attendanceId]);
    }
    public function logAttendanceLog(array $data): bool
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO attendance (student_id, date, first_scan_at, last_scan_at, created_at)
            VALUES (:student_id, CURDATE(), :scanned_at, :scanned_at, :scanned_at)
            ON DUPLICATE KEY UPDATE last_scan_at = :scanned_at
        ");
            return $stmt->execute([
                'student_id' => $data['student_id'],
                'scanned_at' => $data['scanned_at']
            ]);
        } catch (PDOException $e) {
            error_log("Attendance table log failed: " . $e->getMessage());
            return false;
        }
    }
}
