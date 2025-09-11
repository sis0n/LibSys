<?php
namespace App\Controllers;

use App\Core\Controller;

class SidebarController extends Controller
{
    public function superadmin()
    {
        // ibahin kapag gumana yung student
        $this->view("dashboard/superadmin", [
            "title" => "Superadmin Dashboard"
        ]);
    }

    public function admin()
    {
        // ibahin kapag gumana yung student
        $this->view("dashboard/admin", [
            "title" => "Admin Dashboard"
        ]);
    }

    public function librarian()
    {
        // ibahin kapag gumana yung student
        $this->view("dashboard/librarian", [
            "title" => "Librarian Dashboard"
        ]);
    }

    public function studentDashboard()
    {
        $this->view("Student/dashboard", ["title" => "Student Dashboard"]);
    }

    public function studentBookCatalog()
    {
        $this->view("Student/bookCatalog", ["title" => "Book Catalog"]);
    }

    public function studentEquipmentCatalog()
    {
        $this->view("Student/equipmentCatalog", ["title" => "Equipment Catalog"]);
    }

    public function studentMyCart()
    {
        $this->view("Student/myCart", ["title" => "My Cart"]);
    }

    public function studentQrBorrowingTicket()
    {
        $this->view("Student/qrBorrowingTicket", ["title" => "QR Borrowing Ticket"]);
    }

    public function studentMyAttendance()
    {
        $this->view("Student/myAttendance", ["title" => "My Attendance"]);
    }

    public function studentBorrowingHistory()
    {
        $this->view("Student/borrowingHistory", ["title" => "Borrowing History"]);
    }

}
