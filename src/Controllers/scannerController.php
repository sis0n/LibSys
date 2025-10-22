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
        try {
            $qrValue  = $_POST['qrCodeValue'] ?? null;
            if ($qrValue) {
                $qrValue = strtoupper(trim($qrValue));
            }

            if (!$qrValue) {
                throw new \Exception("No student number provided.");
            }

            $user = $this->userRepo->findByStudentNumber($qrValue);
            if (!$user) {
                throw new \Exception("Student not found in records.");
            }

            $manila = new \DateTimeZone('Asia/Manila');
            $now = new \DateTime('now', $manila);
            $timestamp = $now->format('Y-m-d H:i:s');

            $attendance = new Attendance(
                (int)$user['user_id'],
                $user['student_number'],
                $user['first_name'],
                $user['middle_name'],
                $user['last_name'],
                $user['year_level'],
                $user['course'],
                'qr',
                $timestamp
            );

            $success = $this->attendanceRepo->logBoth($attendance);
            if (!$success) {
                throw new \Exception("Failed to log attendance. (Repository Error)");
            }

            $fullName = implode(' ', array_filter([$user['first_name'], $user['middle_name'], $user['last_name']]));

            echo json_encode([
                "status" => "success",
                "full_name" => $fullName,
                "student_number" => $user['student_number'],
                "time" => $now->format('g:i A')
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function manual()
    {
        header('Content-Type: application/json');
        try {
            $studentNumber = $_POST['studentNumber'] ?? null;
            if ($studentNumber) {
                $studentNumber = strtoupper(trim($studentNumber));
            }

            if (!$studentNumber) {
                throw new \Exception("No student number provided.");
            }

            $user = $this->userRepo->findByStudentNumber($studentNumber);
            if (!$user) {
                throw new \Exception("Student not found in records.");
            }

            $now = new \DateTime('now', new \DateTimeZone('Asia/Manila'));
            $timestamp = $now->format('Y-m-d H:i:s');

            $attendance = new \App\Models\Attendance(
                (int)$user['user_id'],
                $user['student_number'],
                $user['first_name'],
                $user['middle_name'],
                $user['last_name'],
                $user['year_level'],
                $user['course'],
                'manual',
                $timestamp
            );

            $success = $this->attendanceRepo->logBoth($attendance);
            if (!$success) {
                throw new \Exception("Failed to log attendance. (Repository Error)");
            }

            $fullName = implode(' ', array_filter([$user['first_name'], $user['middle_name'], $user['last_name']]));

            echo json_encode([
                "status" => "success",
                "full_name" => $fullName,
                "student_number" => $user['student_number'],
                "time" => $now->format('g:i A')
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
