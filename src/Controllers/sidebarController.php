<?php
namespace App\Controllers;

use App\Core\Controller;

class SidebarController extends Controller
{
    
    // student Display Caller 
    
    public function studentDashboard()
    {
        $this->view("student/dashboard", [
            "title" => "student Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function studentBookCatalog()
    {
        $this->view("student/bookCatalog", [
            "title" => "Book Catalog",
            "currentPage" => "bookCatalog"
        ]);
    }

    public function studentEquipmentCatalog()
    {
        $this->view("student/equipmentCatalog", [
            "title" => "Equipment Catalog",
            "currentPage" => "equipmentCatalog"
        ]);
    }

    public function studentMyCart()
    {
        $this->view("student/myCart", [
            "title" => "My Cart",
            "currentPage" => "myCart"
        ]);
    }

    public function studentQrBorrowingTicket()
    {
        $this->view("student/qrBorrowingTicket", [
            "title" => "QR Borrowing Ticket",
            "currentPage" => "qrBorrowingTicket"
        ]);
    }

    public function studentMyAttendance()
    {
        $this->view("student/myAttendance", [
            "title" => "My Attendance",
            "currentPage" => "myAttendance"
        ]);
    }

    public function studentBorrowingHistory()
    {
        $this->view("student/borrowingHistory", [
            "title" => "Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    // Super Admin Display Caller 

    public function superAdminDashboard()
    {
        $this->view("superadmin/dashboard", [
            "title" => "Superadmin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function userManagement()
    {
        $this->view("superadmin/userManagement", [
            "title"=> "Superadmin User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function features()
    {
        $this->view("superadmin/features", [
            "title"=> "Superadmin Features",
            "currentPage" => "features"
        ]);
    }

    public function bookManagement()
    {
        $this->view("superadmin/bookManagement", [
            "title"=> "Superadmin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function equipmentManagement()
    {
        $this->view("superadmin/equipmentManagement", [
            "title"=> "Superadmin Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }
    
    public function qrScanner()
    {
        $this->view("superadmin/qrScanner", [
            "title"=> "Superadmin QR Code Scanner",
            "currentPage" => "qrScanner"
        ]);
    }

    public function attendanceLogs()
    {
        $this->view("superadmin/attendanceLogs", [
            "title"=> "Superadmin Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }
    public function topVisitor()
    {
        $this->view("superadmin/topVisitor", [
            "title"=> "Superadmin Top Visitor",
            "currentPage" => "topVisitor"
        ]);
    }

    public function borrowingHistory()
    {
        $this->view("superadmin/borrowingHistory", [
            "title"=> "Superadmin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function overdueAlert()
    {
        $this->view("superadmin/overdueAlert", [
            "title"=> "Superadmin Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function globalLogs()
    {
        $this->view("superadmin/globalLogs", [
            "title"=> "Superadmin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function backupAndRestore()
    {
        $this->view("superadmin/backupAndRestore", [
            "title"=> "Superadmin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }
    
    
    // Admin Display Caller

    public function adminDashboard()
    {
        $this->view("admin/dashboard", [
            "title" => "admin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function adminUserManagement()
    {
        $this->view("admin/userManagement", [
            "title"=> "admin User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function adminFeatures()
    {
        $this->view("admin/features", [
            "title"=> "admin Features",
            "currentPage" => "features"
        ]);
    }

    public function adminBookManagement()
    {
        $this->view("admin/bookManagement", [
            "title"=> "admin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function adminEquipmentManagement()
    {
        $this->view("admin/equipmentManagement", [
            "title"=> "admin Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }
     public function adminQrScanner()
    {
        $this->view("admin/qrScanner", [
            "title"=> "Admin QR Code Scanner",
            "currentPage" => "qrScanner"
        ]);
    }

    public function adminAttendanceLogs()
    {
        $this->view("admin/attendanceLogs", [
            "title"=> "admin Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }
     public function adminTopVisitor()
    {
        $this->view("admin/topVisitor", [
            "title"=> "Admin Top Visitor",
            "currentPage" => "topVisitor"
        ]);
    }

    public function adminBorrowingHistory()
    {
        $this->view("admin/borrowingHistory", [
            "title"=> "admin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function adminOverdueAlert()
    {
        $this->view("admin/overdueAlert", [
            "title"=> "admin Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function adminGlobalLogs()
    {
        $this->view("admin/globalLogs", [
            "title"=> "admin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function adminBackupAndRestore()
    {
        $this->view("admin/backupAndRestore", [
            "title"=> "admin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

    // Librarian Display Caller
    
    public function librarianDashboard()
    {
        $this->view("librarian/dashboard", [
            "title" => "librarian Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function librarianUserManagement()
    {
        $this->view("librarian/userManagement", [
            "title"=> "librarian User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function librarianFeatures()
    {
        $this->view("librarian/features", [
            "title"=> "librarian Features",
            "currentPage" => "features"
        ]);
    }

    public function librarianBookManagement()
    {
        $this->view("librarian/bookManagement", [
            "title"=> "librarian Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function librarianEquipmentManagement()
    {
        $this->view("librarian/equipmentManagement", [
            "title"=> "librarian Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }

     public function librarianQrScanner()
    {
        $this->view("librarian/qrScanner", [
            "title"=> "Librarian QR Code Scanner",
            "currentPage" => "qrScanner"
        ]);
    }

    public function librarianAttendanceLogs()
    {
        $this->view("librarian/attendanceLogs", [
            "title"=> "librarian Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }
     public function librarianTopVisitor()
    {
        $this->view("librarian/topVisitor", [
            "title"=> "Librarian Top Visitor",
            "currentPage" => "topVisitor"
        ]);
    }

    public function librarianBorrowingHistory()
    {
        $this->view("librarian/borrowingHistory", [
            "title"=> "librarian Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function librarianOverdueAlert()
    {
        $this->view("librarian/overdueAlert", [
            "title"=> "librarian Overdue Alert",
            "currentPage" => "overdueAlert"
        ]);
    }

    public function librarianGlobalLogs()
    {
        $this->view("librarian/globalLogs", [
            "title"=> "librarian Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function librarianBackupAndRestore()
    {
        $this->view("librarian/backupAndRestore", [
            "title"=> "librarian Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }
    
}
