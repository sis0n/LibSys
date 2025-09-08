<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller{
    public function superadmin(){
        $this->view("dashboard/superadmin", [
            "title" => "Superadmin Dashboard"
        ]);
    }

    public function admin(){
        $this->view("dashboard/admin", [
            "title" => "Admin Dashboard"
        ]);
    }

    public function librarian(){
        $this->view("dashboard/librarian", [
            "title" => "Librarian Dashboard"
        ]);
    }

    public function student(){
        $this->view("dashboard/student", [
            "title" => "Student Dashboard"
        ]);
    }
}
