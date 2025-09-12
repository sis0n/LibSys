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
        $this->view("SuperAdmin/dashboard", [
            "title" => "Superadmin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function adminUserManagement()
    {
        $this->view("SuperAdmin/userManagement", [
            "title"=> "Superadmin User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function adminFeatures()
    {
        $this->view("SuperAdmin/features", [
            "title"=> "Superadmin Features",
            "currentPage" => "features"
        ]);
    }

    public function adminBookManagement()
    {
        $this->view("SuperAdmin/bookManagement", [
            "title"=> "Superadmin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function adminEquipmentManagement()
    {
        $this->view("SuperAdmin/equipmentManagement", [
            "title"=> "Superadmin Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }

    public function adminAttendanceLogs()
    {
        $this->view("SuperAdmin/attendanceLogs", [
            "title"=> "Superadmin Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }

    public function adminBorrowingHistory()
    {
        $this->view("SuperAdmin/borrowingHistory", [
            "title"=> "Superadmin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function adminOverdueAlert()
    {
        $this->view("SuperAdmin/overdueAlert", [
            "title"=> "Superadmin Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function adminGlobalLogs()
    {
        $this->view("SuperAdmin/globalLogs", [
            "title"=> "Superadmin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function adminBackupAndRestore()
    {
        $this->view("SuperAdmin/backupAndRestore", [
            "title"=> "Superadmin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

    // Librarian Display Caller

    

    public function admin()
    {
        $this->view("dashboard/admin", [
            "title" => "Admin Dashboard"
        ]);
    }
    
    public function librarian()
    {
        $this->view("dashboard/librarian", [
            "title" => "Librarian Dashboard"
        ]);
    }
    
}
