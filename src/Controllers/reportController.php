<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    public function getCirculatedBooksReport()
    {
        $repository = new ReportRepository();
        $data = $repository->getCirculatedBooksSummary();
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    }

    public function getTopVisitors()
    {
        $repository = new ReportRepository();
        $data = $repository->getTopVisitorsByYear();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $data]);
    }
}
