<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
  protected DashboardRepository $dashboardRepo;

  public function __construct()
  {
    $this->dashboardRepo = new DashboardRepository();
  }

  public function getData()
  {
    try {
      $stats = $this->dashboardRepo->getDashboardStats()['data'] ?? [];

      $response = [
        'success' => true,
        'data' => [
          'students' => $stats['students'] ?? 0,
          'faculty' => $stats['faculty'] ?? 0,
          'staff' => $stats['staff'] ?? 0,
          'attendance_today' => $stats['attendance_today'] ?? 0,
          'books' => ($stats['availableBooks'] ?? 0) + ($stats['borrowedBooks'] ?? 0),
          'borrowed_books' => $stats['borrowed_books'] ?? 0,
          'totalUsers' => $stats['totalUsers'] ?? 0,
          'usersAddedThisMonth' => $stats['usersAddedThisMonth'] ?? 0,
          'availableBooks' => $stats['availableBooks'] ?? 0,
          'availableBooksPercent' => $stats['availableBooksPercent'] ?? 0,
          'borrowedBooksPercent' => $stats['borrowedBooksPercent'] ?? 0,
        ],
        'topVisitors' => $this->dashboardRepo->getTopVisitors(),
        'weeklyActivity' => $this->dashboardRepo->getWeeklyActivity(),
      ];

      echo json_encode($response);
    } catch (\Exception $e) {
      echo json_encode([
        'success' => false,
        'message' => 'Failed to load dashboard data: ' . $e->getMessage(),
      ]);
    }
  }
}
