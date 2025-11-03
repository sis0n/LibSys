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

    /**
     * Retrieves attendance logs for a specific user (used by My Profile).
     */
    public function getByUserId(int $userId): array
    {
        try {
            $sql = "
                SELECT 
                    al.student_number, al.full_name, al.year_level, al.section, al.method, al.timestamp,
                    c.course_code, c.course_title
                FROM attendance_logs al
                LEFT JOIN users u ON al.user_id = u.user_id
                LEFT JOIN students s ON u.user_id = s.user_id
                LEFT JOIN courses c ON al.course_id = c.course_id
                WHERE al.user_id = :user_id
                ORDER BY al.timestamp DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $userId]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($row) {
                // Pinagsama ang year_level at section (hal: 3 A)
                $row['year_level_section'] = trim(($row['year_level'] ?? '') . ' ' . ($row['section'] ?? ''));
                $row['course'] = ($row['course_code'] && $row['course_title'])
                    ? $row['course_code'] . ' - ' . $row['course_title']
                    : 'N/A';
                return $row;
            }, $results);
        } catch (PDOException $e) {
            error_log("Fetch attendance failed: " . $e->getMessage());
            return [];
        }
    }

    public function getByUserAndDate(int $userId, string $date)
    {
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllLogs(): array
    {
        // Pinalitan ng tawag sa getLogsByPeriod na walang parameters
        return $this->getLogsByPeriod(null, null, '');
    }

    /**
     * Retrieves all logs for the Admin/Superadmin viewing (Ajax Table).
     */
    public function getLogsByPeriod(?string $start = null, ?string $end = null, string $search = ''): array
    {
        $query = "
            SELECT al.*, 
                   c.course_code, c.course_title
            FROM attendance_logs al
            LEFT JOIN users u ON al.user_id = u.user_id
            LEFT JOIN courses c ON al.course_id = c.course_id
            WHERE 1=1
        ";
        $params = [];

        if ($start && $end) {
            $endOfDay = $end . ' 23:59:59';
            $query .= " AND al.timestamp BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $endOfDay;
        }

        if ($search) {
            $query .= " AND (al.full_name LIKE :search OR al.student_number LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY al.timestamp DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $row['course'] = ($row['course_code'] && $row['course_title'])
                ? $row['course_code'] . ' - ' . $row['course_title']
                : ($row['course'] ?? 'N/A');
            $row['year_level_section'] = trim(($row['year_level'] ?? '') . ' ' . ($row['section'] ?? ''));
            return $row;
        }, $results);
    }

    public function updateLastScan(int $attendanceId, string $time): bool
    {
        $stmt = $this->db->prepare("UPDATE attendance SET last_scan_at = ? WHERE id = ?");
        return $stmt->execute([$time, $attendanceId]);
    }

    /**
     * Saves attendance to logs and updates summary table.
     * Assumes Attendance model has getYearLevel(), getSection(), and getCourseId().
     */
    public function logBoth(\App\Models\Attendance $attendance): bool
    {
        try {
            $this->db->beginTransaction();

            $parts = [
                $attendance->getFirstName(),
                $attendance->getMiddleName(),
                $attendance->getLastName()
            ];
            $fullName = implode(' ', array_filter($parts));

            // FIXED SQL: Uses year_level, section, and course_id
            $sqlLogs = "
            INSERT INTO attendance_logs 
                (user_id, student_number, full_name, year_level, section, course_id, method, timestamp) 
            VALUES 
                (:user_id, :student_number, :full_name, :year_level, :section, :course_id, :method, :timestamp)
            ";
            $stmtLogs = $this->db->prepare($sqlLogs);

            $ok1 = $stmtLogs->execute([
                ':user_id' => $attendance->getUserId(),
                ':student_number' => $attendance->getStudentNumber(),
                ':full_name' => $fullName,
                ':year_level' => $attendance->getYearLevel(),
                ':section' => $attendance->getSection(),
                ':course_id' => $attendance->getCourseId(),
                ':method' => $attendance->getSource(),
                ':timestamp' => $attendance->getTimestamp()
            ]);

            if (!$ok1) {
                $errorInfo = $stmtLogs->errorInfo();
                $this->db->rollBack();
                error_log("SQL Error (Logs): " . implode(" - ", $errorInfo));
                throw new \Exception("SQL Error (Logs): " . $errorInfo[2]);
            }

            $dt   = new \DateTime($attendance->getTimestamp(), new \DateTimeZone('Asia/Manila'));
            $date = $dt->format('Y-m-d');
            $ts   = $dt->format('Y-m-d H:i:s');

            $sqlSummary = "
            INSERT INTO attendance 
                (user_id, date, first_scan_at, last_scan_at, created_at)
            VALUES 
                (:user_id, :date, :first_scan_at, :last_scan_at, :created_at)
            ON DUPLICATE KEY UPDATE 
                last_scan_at = :last_scan_at_update
            ";
            $stmtSummary = $this->db->prepare($sqlSummary);

            $ok2 = $stmtSummary->execute([
                ':user_id' => $attendance->getUserId(),
                ':date' => $date,
                ':first_scan_at' => $ts,
                ':last_scan_at' => $ts,
                ':created_at' => $ts,
                ':last_scan_at_update' => $ts
            ]);

            if (!$ok2) {
                $errorInfo = $stmtSummary->errorInfo();
                $this->db->rollBack();
                error_log("SQL Error (Summary): " . implode(" - ", $errorInfo));
                throw new \Exception("SQL Error (Summary): " . $errorInfo[2]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("EXCEPTION: " . $e->getMessage());
            throw $e;
        }
    }
}
