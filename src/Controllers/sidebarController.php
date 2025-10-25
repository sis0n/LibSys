<?php
namespace App\Controllers;

use App\Core\Controller;

class SidebarController extends Controller
{
    
    // student Display Caller 
    
    public function studentDashboard()
    {
        $this->view("student/dashboard", [
            "title" => "Student Dashboard",
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

        public function studentChangePassword()
    {
        $this->view("student/changePassword", [
            "title" => "Change Password",
            "currentPage" => "changePassword"
        ]);
    }
        public function studentMyProfile()
    {
        $this->view("student/myProfile", [
            "title" => "My Profile",
            "currentPage" => "myProfile"
        ]);
    }

    // Super Admin Display Caller 

    public function superAdminDashboard()
    {
        $this->view("superadmin/dashboard", [
            "title" => "Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function userManagement()
    {
        $this->view("superadmin/userManagement", [
            "title"=> "User Management",
            "currentPage" => "userManagement"
        ]);
    }

    public function bookManagement()
    {
        $this->view("superadmin/bookManagement", [
            "title"=> "Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function equipmentManagement()
    {
        $this->view("superadmin/equipmentManagement", [
            "title"=> "Equipment Management",
            "currentPage" => "equipmentManagement"
        ]);
    }
    
    public function qrScanner()
    {
        $this->view("superadmin/qrScanner", [
            "title"=> "QR Code Scanner",
            "currentPage" => "qrScanner"
        ]);
    }

    public function attendanceLogs()
    {
        $this->view("superadmin/attendanceLogs", [
            "title"=> "Attendance Logs",
            "currentPage" => "attendanceLogs"
        ]);
    }
    public function topVisitor()
    {
        $this->view("superadmin/topVisitor", [
            "title"=> "Top Visitor",
            "currentPage" => "topVisitor"
        ]);
    }

    public function borrowingHistory()
    {
        $this->view("superadmin/borrowingHistory", [
            "title"=> "Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function returning()
    {
        $this->view("superadmin/returning", [
            "title"=> "Returning",
            "currentPage" => "returning"
        ]);
    }

    public function globalLogs()
    {
        $this->view("superadmin/globalLogs", [
            "title"=> "Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function backupAndRestore()
    {
        $this->view("superadmin/backupAndRestore", [
            "title"=> "Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

     public function changePassword()
    {
        $this->view("superadmin/changePassword", [
            "title" => "Change Password",
            "currentPage" => "changePassword"
        ]);
    }
     public function superadminMyProfile()
    {
        $this->view("superadmin/myProfile", [
            "title" => "My Profile",
            "currentPage" => "myProfile"
        ]);
    }
    
    // Admin Display Caller

    public function adminDashboard()
    {
        $this->view("admin/dashboard", [
            "title" => "Admin Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function adminBookManagement()
    {
        $this->view("admin/bookManagement", [
            "title"=> "Admin Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function adminEquipmentManagement()
    {
        $this->view("admin/equipmentManagement", [
            "title"=> "Admin Equipment Management",
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
            "title"=> "Admin Attendance Logs",
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
            "title"=> "Admin Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function adminReturning()
    {
        $this->view("admin/returning", [
            "title"=> "Admin Returning",
            "currentPage" => "returning"
        ]);
    }

    public function adminGlobalLogs()
    {
        $this->view("admin/globalLogs", [
            "title"=> "Admin Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function adminBackupAndRestore()
    {
        $this->view("admin/backupAndRestore", [
            "title"=> "Admin Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

     public function adminChangePassword()
    {
        $this->view("admin/changePassword", [
            "title" => "Admin Change Password",
            "currentPage" => "changePassword"
        ]);
    }

     public function adminMyProfile()
    {
        $this->view("admin/myProfile", [
            "title" => "My Profile",
            "currentPage" => "myProfile"
        ]);
    }

    // Librarian Display Caller
    
    public function librarianDashboard()
    {
        $this->view("librarian/dashboard", [
            "title" => "Librarian Dashboard",
            "currentPage" => "dashboard"
        ]);
    }

    public function librarianBookManagement()
    {
        $this->view("librarian/bookManagement", [
            "title"=> "Librarian Book Management",
            "currentPage" => "bookManagement"
        ]);
    }

    public function librarianEquipmentManagement()
    {
        $this->view("librarian/equipmentManagement", [
            "title"=> "Librarian Equipment Management",
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
            "title"=> "Librarian Attendance Logs",
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
            "title"=> "Librarian Borrowing History",
            "currentPage" => "borrowingHistory"
        ]);
    }

    public function librarianReturning()
    {
        $this->view("librarian/returning", [
            "title"=> "Librarian Returning",
            "currentPage" => "returning"
        ]);
    }

    public function librarianGlobalLogs()
    {
        $this->view("librarian/globalLogs", [
            "title"=> "Librarian Global Logs",
            "currentPage" => "globalLogs"
        ]);
    }

    
    public function librarianBackupAndRestore()
    {
        $this->view("librarian/backupAndRestore", [
            "title"=> "Librarian Backup & Restore",
            "currentPage" => "backupAndRestore"
        ]);
    }

     public function librarianChangePassword()
    {
        $this->view("librarian/changePassword", [
            "title" => "Librarian Change Password",
            "currentPage" => "changePassword"
        ]);
    }

    public function librarianMyProfile()
    {
        $this->view("librarian/myProfile", [
            "title" => "My Profile",
            "currentPage" => "myProfile"
        ]);
    }
    
}
