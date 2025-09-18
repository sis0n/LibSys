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

    public function scannerDisplay()
    {
        $this->view("scanner/attendance", [
            "title" => "Scanner"
        ], false);
    }

    public function attendance()
    {
        $qrValue  = $_POST['qrCodeValue'] ?? null;

        if (!$qrValue) {
            echo "No student number provided.";
            return;
        }

        $user = $this->userRepo->findByStudentNumber($qrValue);

        if (!$user) {
            echo "User not found.";
            return;
        }

        $manila = new \DateTimeZone('Asia/Manila');
        $now = new \DateTime('now', $manila); // current manila time

        // check if yung user nag log na ng attendance today, burahin na lang yung comment sa demo day.
        
        // $today = (new \DateTime('now', new \DateTimeZone('Asia/Manila')))->format('Y-m-d');
        // $existing = $this->attendanceRepo->getByUserAndDate((int)$user['user_id'], $today);

        // if ($existing) {
        //     echo "Attendance already logged today for " . htmlspecialchars($user['full_name']);
        //     return;
        // }


        $attendance = new Attendance(
            (int)$user['user_id'],
            $user['student_number'],
            $user['full_name'],
            $user['year_level'],
            $user['course'],
            'qr',
            $now->format('Y-m-d H:i:s')
        );

        // save db
        $success = $this->attendanceRepo->logAttendance($attendance);

        if ($success) {
            echo "<div style='border:1px solid #ccc; padding:10px; text-align:center;'>
                <h3>Attendance Logged </h3>
                <p><strong>Name:</strong> " . htmlspecialchars($user['full_name']) . "</p>
                <p><strong>Student Number:</strong> " . htmlspecialchars($user['student_number']) . "</p>
                <p><strong>Time:</strong> " . $now->format('g:i A') . "</p>
              </div>
              <br>
              <a href='/libsys/public/scanner/attendance'>Back to Scan</a>";    
        } else {
            echo "Failed to log attendance.";
        }
    }

    public function manual()
    {
        $studentNumber = $_POST['studentNumber'] ?? null;
        $studentName = $_POST['studentName'] ?? null;

        if (!$studentNumber || !$studentName) {
            echo "Student number and name are required.";
            return;
        }

        //check lang kung existing yung user
        $user = $this->userRepo->findByStudentNumber($studentNumber);

        if (!$user) {
            $user = [
                'user_id' => 0,
                'student_number' => $studentNumber,
                'full_name' => $studentName,
                'year_level' => 'N/A',
                'course' => 'N/A'
            ];
        }

        // timezone manila
        $now = new \DateTime('now', new \DateTimeZone('Asia/Manila'));

        $attendance = new \App\Models\Attendance(
            (int)$user['user_id'],
            $user['student_number'],
            $user['full_name'],
            $user['year_level'],
            $user['course'],
            'manual', // source
            $now->format('Y-m-d H:i:s')
        );

        $success = $this->attendanceRepo->logAttendance($attendance);

        if ($success) {
            echo "<div style='border:1px solid #ccc; padding:10px; text-align:center;'>
                <h3>Attendance Logged</h3>
                <p><strong>Name:</strong> " . htmlspecialchars($user['full_name']) . "</p>
                <p><strong>Student Number:</strong> " . htmlspecialchars($user['student_number']) . "</p>
                <p><strong>Time:</strong> " . $now->format('g:i A') . "</p>
              </div>
              <br>
              <a href='/libsys/public/scanner/attendance'>Back to Scan</a>";    
        } else {
            echo "Failed to log attendance.";
        }
    }
}
