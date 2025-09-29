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
        header('Content-Type: application/json');
        $qrValue  = $_POST['qrCodeValue'] ?? null;
        if ($qrValue) {
            $qrValue = strtoupper(trim($qrValue));  //force uppercase
        }

        if (!$qrValue) {
            echo json_encode([
                "status" => "Error.",
                "message" => "No student number provided."
                // echo "No student number provided.";
            ]); 
             return;
        }

        $user = $this->userRepo->findByStudentNumber($qrValue);
        // var_dump($user);
        // exit;


        if (!$user) {
            echo json_encode([
                "status" => "Error.",
                "message" => "Student not found in records."
                // echo "User not found.";
                // echo "<a href='/libsys/public/scanner/attendance'>back to scan</a>";
            ]);
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
        $success = $this->attendanceRepo->logBoth($attendance);

        if ($success) {
            //     echo "<div style='border:1px solid #ccc; padding:10px; text-align:center;'>
            //         <h3>Attendance Logged </h3>
            //         <p><strong>Name:</strong> " . htmlspecialchars($user['full_name']) . "</p>
            //         <p><strong>Student Number:</strong> " . htmlspecialchars($user['student_number']) . "</p>
            //         <p><strong>Time:</strong> " . $now->format('g:i A') . "</p>
            //       </div>
            //       <br>
            //       <a href='/libsys/public/scanner/attendance'>Back to Scan</a>";
            // } else {
            //     echo "Failed to log attendance.";
            // }

             echo json_encode([
                "status" => "Success",
                "message" => "Attendance logged for " . htmlspecialchars($user['full_name']) . " at " . $now->format('g:i A')
            ]);
        }
              else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to log attendance."
            ]);
        } 
    }

    public function manual()
    {
        $studentNumber = $_POST['studentNumber'] ?? null;
        $studentName = $_POST['studentName'] ?? null;
        if ($studentNumber) {
            $studentNumber = strtoupper(trim($studentNumber)); //force uppercase rin
        }

        // comment ko muna yung name ni rerequired kasi.
        // if (!$studentNumber || !$studentName) {
        //     echo "Student number and name are required.";
        //     return;
        // }

        //check lang kung existing yung user
        $user = $this->userRepo->findByStudentNumber($studentNumber);
        error_log("[ScannerController] Manual Lookup result for {$studentNumber}: " . print_r($user, true));

        if (!$user) {
            echo json_encode([
                "status" => "error",
                "message" => "Student not found in records."
            ]);
            // echo "Student not found in records.";
            // echo "<br><a href='/libsys/public/scanner/attendance'>Back to Scan</a>";
            return;
        }

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
            'manual',
            $now->format('Y-m-d H:i:s')
        );

        $success = $this->attendanceRepo->logBoth($attendance);


        if ($success) {
        //     echo "<div style='border:1px solid #ccc; padding:10px; text-align:center;'>
        //         <h3>Attendance Logged</h3>
        //         <p><strong>Name:</strong> " . htmlspecialchars($user['full_name']) . "</p>
        //         <p><strong>Student Number:</strong> " . htmlspecialchars($user['student_number']) . "</p>
        //         <p><strong>Time:</strong> " . $now->format('g:i A') . "</p>
        //       </div>
        //       <br>
        //       <a href='/libsys/public/scanner/attendance'>Back to Scan</a>";
        // } else {
        //     echo "Failed to log attendance.";
        // }
            echo json_encode([
                "status" => "success",
                "message" => "Attendance logged for " . htmlspecialchars($user['full_name']) . " at " . $now->format('g:i A')
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to log attendance."
            ]);
        }   
    }
}
