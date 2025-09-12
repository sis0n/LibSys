<?php
namespace App\Controllers;

use App\Core\Controller;

class SidebarController extends Controller
{
    
    // Student Display Caller 
    
    public function studentDashboard()
    {
        $this->view("Student/dashboard", [
            "title" => "Student Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function studentBookCatalog()
    {
        $this->view("Student/bookCatalog", [
            "title" => "Book Catalog",
            "currentPage" => "bookCatalog"
        ]);
    }

    public function studentEquipmentCatalog()
    {
        $this->view("Student/equipmentCatalog", [
            "title" => "Equipment Catalog",
            "currentPage" => "equimentCatalog"
        ]);
    }

    public function studentMyCart()
    {
        $this->view("Student/myCart", [
            "title" => "My Cart",
            "currentPage" => "myCart"
        ]);
    }

    public function studentQrBorrowingTicket()
    {
        $this->view("Student/qrBorrowingTicket", [
            "title" => "QR Borrowing Ticket",
            "currentPage" => "qrBorrowingTicket"
        ]);
    }

    public function studentMyAttendance()
    {
        $this->view("Student/myAttendance", [
            "title" => "My Attendance",
            "currentPage" => "myAttendance"
        ]);
    }

    public function studentBorrowingHistory()
    {
        $this->view("Student/borrowingHistory", [
            "title" => "Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    // Super Admin Display Caller 

    public function superAdminDashboard()
    {
        $this->view("SuperAdmin/dashboard", [
            "title" => "Superadmin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function userManagement()
    {
        $this->view("SuperAdmin/userManagement", [
            "title"=> "Superadmin User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function features()
    {
        $this->view("SuperAdmin/features", [
            "title"=> "Superadmin Features",
            "currentPage" => "features"
        ]);
    }

    public function bookManagement()
    {
        $this->view("SuperAdmin/bookManagement", [
            "title"=> "Superadmin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function equipmentManagement()
    {
        $this->view("SuperAdmin/equipmentManagement", [
            "title"=> "Superadmin Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }

    public function attendanceLogs()
    {
        $this->view("SuperAdmin/attendanceLogs", [
            "title"=> "Superadmin Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }

    public function borrowingHistory()
    {
        $this->view("SuperAdmin/borrowingHistory", [
            "title"=> "Superadmin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function overdueAlert()
    {
        $this->view("SuperAdmin/overdueAlert", [
            "title"=> "Superadmin Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function globalLogs()
    {
        $this->view("SuperAdmin/globalLogs", [
            "title"=> "Superadmin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function backupAndRestore()
    {
        $this->view("SuperAdmin/backupAndRestore", [
            "title"=> "Superadmin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }
    
    
    // Admin Display Caller

    public function adminDashboard()
    {
        $this->view("Admin/dashboard", [
            "title" => "admin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function adminUserManagement()
    {
        $this->view("Admin/userManagement", [
            "title"=> "admin User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function adminFeatures()
    {
        $this->view("Admin/features", [
            "title"=> "admin Features",
            "currentPage" => "features"
        ]);
    }

    public function adminBookManagement()
    {
        $this->view("Admin/bookManagement", [
            "title"=> "admin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function adminEquipmentManagement()
    {
        $this->view("Admin/equipmentManagement", [
            "title"=> "admin Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }

    public function adminAttendanceLogs()
    {
        $this->view("Admin/attendanceLogs", [
            "title"=> "admin Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }

    public function adminBorrowingHistory()
    {
        $this->view("Admin/borrowingHistory", [
            "title"=> "admin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function adminOverdueAlert()
    {
        $this->view("Admin/overdueAlert", [
            "title"=> "admin Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function adminGlobalLogs()
    {
        $this->view("Admin/globalLogs", [
            "title"=> "admin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function adminBackupAndRestore()
    {
        $this->view("Admin/backupAndRestore", [
            "title"=> "admin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

    // Librarian Display Caller
    
    public function librarianDashboard()
    {
        $this->view("Librarian/dashboard", [
            "title" => "Librarian Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function librarianUserManagement()
    {
        $this->view("Librarian/userManagement", [
            "title"=> "Librarian User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function librarianFeatures()
    {
        $this->view("Librarian/features", [
            "title"=> "Librarian Features",
            "currentPage" => "features"
        ]);
    }

    public function librarianBookManagement()
    {
        $this->view("Librarian/bookManagement", [
            "title"=> "Librarian Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function librarianEquipmentManagement()
    {
        $this->view("Librarian/equipmentManagement", [
            "title"=> "Librarian Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }

    public function librarianAttendanceLogs()
    {
        $this->view("Librarian/attendanceLogs", [
            "title"=> "Librarian Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }

    public function librarianBorrowingHistory()
    {
        $this->view("Librarian/borrowingHistory", [
            "title"=> "Librarian Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function librarianOverdueAlert()
    {
        $this->view("Librarian/overdueAlert", [
            "title"=> "Librarian Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function librarianGlobalLogs()
    {
        $this->view("Librarian/globalLogs", [
            "title"=> "Librarian Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function librarianBackupAndRestore()
    {
        $this->view("SuperAdmin/backupAndRestore", [
            "title"=> "Superadmin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }
    
}
