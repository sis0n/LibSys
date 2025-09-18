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

    public function logAttendance(Attendance $attendance): bool{
        try {
            $stmt = $this->db->prepare("
                INSERT INTO attendance_logs 
                    (student_id, student_number, full_name, year_level, course, method, timestamp)
                VALUES 
                    (:student_id, :student_number, :full_name, :year_level, :course, :method, :timestamp)
            ");

            return $stmt->execute([
                'student_id' => $attendance->studentId,
                'student_number' => $attendance->studentNumber,
                'full_name' => $attendance->fullName,
                'year_level' => $attendance->yearLevel,
                'course' => $attendance->course,
                'method' => $attendance->method,
                'timestamp' => $attendance->timestamp
            ]);
        } catch (PDOException $e) {
            error_log("Attendance log failed: " . $e->getMessage());
            return false;
        }
    }
}
