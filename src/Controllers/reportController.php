<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    public function getCirculatedBooksReport()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $repository = new ReportRepository();
        $data = $repository->getCirculatedBooksSummary();
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    }

    public function getTopVisitors()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $repository = new ReportRepository();
        $data = $repository->getTopVisitorsByYear();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function getLibraryVisitsByDepartment()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $repository = new ReportRepository();
        $data = $repository->getLibraryVisitsByDepartment();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
    }

    public function getDeletedBooks()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        $repository = new ReportRepository();
        $dbData = $repository->getDeletedBooksReport();

        $statsByYear = [];
        foreach ($dbData as $row) {
            $statsByYear[$row['year']] = [
                'month' => (int)$row['month'],
                'today' => (int)$row['today'],
                'count' => (int)$row['count']
            ];
        }

        $years = [2025, 2026, 2027];
        $reportData = [];
        $totalCount = 0;
        $totalMonth = 0;
        $totalToday = 0;

        foreach ($years as $year) {
            $stats = $statsByYear[$year] ?? ['month' => 0, 'today' => 0, 'count' => 0];
            $reportData[] = [
                "year" => (string)$year,
                "month" => $stats['month'],
                "today" => $stats['today'],
                "count" => $stats['count']
            ];
            $totalCount += $stats['count'];
            $totalMonth += $stats['month'];
            $totalToday += $stats['today'];
        }

        $reportData[] = [
            "year" => "TOTAL",
            "month" => $totalMonth,
            "today" => $totalToday,
            "count" => $totalCount
        ];

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $reportData]);
    }
}
