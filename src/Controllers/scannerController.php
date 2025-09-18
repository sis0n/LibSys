<?php
namespace App\Controllers;

use App\Core\Controller;

class ScannerController extends Controller
{
//   pang display lang ng scanner
  public function scannerDisplay(){
    $this->view("scanner/attendance",[
        "title" => "Scanner"
    ],false);
  }
}
?>