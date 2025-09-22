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
        $sql = "SELECT al.id, al.user_id, al.student_number, al.full_name, 
                   al.course, al.year_level, al.method, al.timestamp
            FROM attendance_logs al
            ORDER BY al.timestamp DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateLastScan(int $attendanceId, string $time): bool
    {
        $stmt = $this->db->prepare("UPDATE attendance SET last_scan_at = ? WHERE id = ?");
        return $stmt->execute([$time, $attendanceId]);
    }

    public function logBoth(\App\Models\Attendance $attendance): bool
    {
        try {
            $this->db->beginTransaction();
            // echo "<pre>debug start\n";  

            // inser raw scan in attendance_logs
            $sqlLogs = "
            INSERT INTO attendance_logs 
                (user_id, student_number, full_name, year_level, course, method, timestamp) 
            VALUES 
                (:user_id, :student_number, :full_name, :year_level, :course, :method, :timestamp)
        ";
            $stmtLogs = $this->db->prepare($sqlLogs);
            $ok1 = $stmtLogs->execute([
                ':user_id'       => $attendance->getUserId(),
                ':student_number' => $attendance->getStudentNumber(),
                ':full_name'     => $attendance->getFullName(),
                ':year_level'    => $attendance->getYearLevel(),
                ':course'        => $attendance->getCourse(),
                ':method'        => $attendance->getSource(),
                ':timestamp'     => $attendance->getTimestamp()
            ]);
            // echo "after logs insert: " . ($ok1 ? "OK\n" : "FAILED\n");

            if (!$ok1) {
                var_dump($stmtLogs->errorInfo());
                $this->db->rollBack();
                exit("STOP at logs insert");
            }

            // insert or update summary in attendance
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
            // echo "after attendance insert/update: " . ($ok2 ? "OK\n" : "FAILED\n");

            if (!$ok2) {
                var_dump($stmtSummary->errorInfo());
                $this->db->rollBack();
                exit("STOP at attendance insert");
            }

            $this->db->commit();
            // echo "COMMIT OK\n</pre>";
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            exit("EXCEPTION: " . $e->getMessage());
        }
    }
    public function getLogsByPeriod(?string $start = null, ?string $end = null, string $search = ''): array
    {
        $query = "SELECT * FROM attendance_logs WHERE 1=1";
        $params = [];

        if ($start && $end) {
            $query .= " AND timestamp BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }

        if ($search) {
            $query .= " AND (full_name LIKE :search OR student_number LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $query .= " ORDER BY timestamp DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
