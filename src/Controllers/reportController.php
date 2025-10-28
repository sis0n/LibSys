<?php

namespace App\Controllers;

use App\Core\Controller;
use Dompdf\Dompdf;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
  protected ReportRepository $reportsRepo;

  public function __construct()
  {
    $this->reportsRepo = new ReportRepository();
  }

  public function getReportData()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');
    $limit = $_GET['limit'] ?? 10;

    $data = [
      'libraryResources' => $this->reportsRepo->getLibraryResources(),
      'circulatedBooks'  => $this->reportsRepo->getCirculatedBooks($startDate, $endDate),
      'libraryVisits'    => $this->reportsRepo->getLibraryVisits($startDate, $endDate),
      'topVisitors'      => $this->reportsRepo->getTopVisitorsPerCourse($startDate, $endDate, (int)$limit),
      'weeklyActivity'   => $this->reportsRepo->getWeeklyActivity($startDate, $endDate),
      'topVisitorsOverall' => $this->reportsRepo->getTopVisitorsOverall($startDate, $endDate, (int)$limit)
    ];

    $this->sendJson($data);
  }

  public function downloadReportPDF()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate   = $_GET['end_date'] ?? date('Y-m-t');

    // --- Get data from repository ---
    $libraryResources = $this->reportsRepo->getLibraryResources();
    $circulatedBooks  = $this->reportsRepo->getCirculatedBooks($startDate, $endDate);
    $libraryVisits    = $this->reportsRepo->getLibraryVisits($startDate, $endDate);
    $topVisitors      = $this->reportsRepo->getTopVisitorsPerCourse($startDate, $endDate, 10);
    $weeklyActivity   = $this->reportsRepo->getWeeklyActivity($startDate, $endDate);
    $topVisitorsOverall = $this->reportsRepo->getTopVisitorsOverall($startDate, $endDate, 10);

    // --- Render HTML for PDF ---
    ob_start();
    include __DIR__ . '/../Views/superadmin/pdf_report.php';
    $html = ob_get_clean();

    // --- Generate PDF ---
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = "Library_Report_{$startDate}_to_{$endDate}.pdf";

    // --- Stream PDF to browser ---
    $dompdf->stream($filename, ["Attachment" => true]);
  }


  public function topVisitorsPerCourse()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');
    $limit = $_GET['limit'] ?? 10;

    $data = $this->reportsRepo->getTopVisitorsPerCourse($startDate, $endDate, (int)$limit);
    $this->sendJson($data);
  }

  public function weeklyActivity()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');

    $data = $this->reportsRepo->getWeeklyActivity($startDate, $endDate);
    $this->sendJson($data);
  }

  public function libraryResources()
  {
    $data = $this->reportsRepo->getLibraryResources();
    $this->sendJson($data);
  }

  public function circulatedBooks()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');

    $data = $this->reportsRepo->getCirculatedBooks($startDate, $endDate);
    $this->sendJson($data);
  }

  public function libraryVisits()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');

    $data = $this->reportsRepo->getLibraryVisits($startDate, $endDate);
    $this->sendJson($data);
  }

  public function topVisitorsOverall()
  {
    $startDate = $_GET['start_date'] ?? date('Y-m-01');
    $endDate = $_GET['end_date'] ?? date('Y-m-t');
    $limit = $_GET['limit'] ?? 10;

    $data = $this->reportsRepo->getTopVisitorsOverall($startDate, $endDate, (int)$limit);
    $this->sendJson($data);
  }

  private function sendJson($data)
  {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }
}
