<?php

namespace App\Controllers;

use App\Core\Controller;

class DomPdfTemplateController extends Controller
{
    public function generatePdfTemplate()
    {
        $this->view("report_pdf_template/pdfTemplate", [
            "title" => "PDF Report Template"
        ], false);
    }
}


