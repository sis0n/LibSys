<?php

namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php'; // Ensure Composer autoloader is included

use App\Core\Controller;
use App\Repositories\ReportRepository;
use Dompdf\Dompdf;

class DomPdfTemplateController extends Controller
{
    public function generatePdfTemplate()
    {
        $this->view("report_pdf_template/pdfTemplate", [
            "title" => "PDF Report Template"
        ], false);
    }

    public function generateLibraryReport()
    {
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;

        if (!$startDate || !$endDate) {
            die("Start date and end date are required.");
        }

        $reportRepo = new ReportRepository();

        $data = [
            'libraryResources' => $reportRepo->getLibraryResourcesData($startDate, $endDate),
            'deletedBooks'     => $reportRepo->getDeletedBooksData($startDate, $endDate),
            'circulatedBooks'  => $reportRepo->getCirculatedBooksData($startDate, $endDate),
            'libraryVisits'    => $reportRepo->getLibraryVisitsData($startDate, $endDate),
            'dateRange'        => [$startDate, $endDate],
        ];

        ob_start();
        extract($data);
        include __DIR__ . '/../Views/report_pdf_template/pdfTemplate.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Library_Report_' . date('Ymd') . '.pdf', ['Attachment' => true]);
    }
}


