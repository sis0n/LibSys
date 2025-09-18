<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\AttendanceRepository;
use DateTime;
use DateTimeZone;

class AttendanceController extends Controller
{
    private AttendanceRepository $attendanceRepo;

    public function __construct()
    {
        $this->attendanceRepo = new AttendanceRepository();
    }

    /**
     * fetch all attendance logs for the looged in user
     * and return JSON formatted for JS tabs
     * wala pa to pero iimplement ko to para sa future.
     */
    public function fetchAttendance()
    {
        // Make sure user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $allLogs = $this->attendanceRepo->getByUserId($userId);

        $data = [
            'day' => [],
            'week' => [],
            'month' => [],
            'year' => []
        ];

        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));

        foreach ($allLogs as $log) {
            $logTime = new DateTime($log['timestamp'], new DateTimeZone('Asia/Manila'));
            $diff = $now->diff($logTime);

            $entry = [
                'date' => $logTime->format('D, M d, Y'),
                'time' => $logTime->format('g:i A'),
                'status' => 'Checked In'
            ];

            if ($diff->days === 0) $data['day'][] = $entry;
            if ($diff->days <= 7) $data['week'][] = $entry;
            if ($diff->m === 0 && $diff->y === 0) $data['month'][] = $entry;
            if ($diff->y === 0) $data['year'][] = $entry;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    
    //para sa admin to
    // public function fetchAllAttendance()
    // {
    //     $allLogs = $this->attendanceRepo->getAll();

    //     $data = [];
    //     foreach ($allLogs as $log) {
    //         $data[] = [
    //             'student_number' => $log['student_number'],
    //             'full_name' => $log['full_name'],
    //             'timestamp' => $log['timestamp'],
    //             'status' => 'Checked In'
    //         ];
    //     }

    //     header('Content-Type: application/json');
    //     echo json_encode($data);
    // }
}
