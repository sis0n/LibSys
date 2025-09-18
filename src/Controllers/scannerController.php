<?php
namespace App\Controllers;

use App\Core\Controller;  
use App\Repositories\UserRepository;
use App\Repositories\AttendanceRepository;
use App\Models\Attendance;

class ScannerController extends Controller
{
    private $userRepo;
    private $attendanceRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->attendanceRepo = new AttendanceRepository();
    }

    public function scannerDisplay(){
    $this->view("scanner/attendance",[
        "title" => "Scanner"
    ],false);
    }

    public function attendance()
    {
        $studentNumber = $_POST['qrCodeValue'] ?? null;

        if (!$studentNumber) {
            echo "No student number provided.";
            return;
        }

        $user = $this->userRepo->findByStudentNumber($studentNumber);

        if (!$user) {
            echo "User not found.";
            return;
        }

        $attendance = new Attendance(
            (int)$user['student_id'],
            $user['student_number'],
            $user['full_name'],
            $user['year_level'],
            $user['course'],
            'qr'
        );

        // save db
        $success = $this->attendanceRepo->logAttendance($attendance);

        if ($success) {
            //eto gagawan mo ng design col
            echo "Attendance logged for: " . htmlspecialchars($user['full_name']) . '</br>' . htmlspecialchars($user['student_number']) . '<br>' . htmlspecialchars($user['course']);
        } else {
            echo "Failed to log attendance.";
        }
    }
}
