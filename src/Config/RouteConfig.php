<?php
namespace App\Config;

use App\Core\Router;

class RouteConfig
{
    public static function register(): Router
    {
        // convert {param} style to regex
        $router = new Router();

        /**
         * ========================
         * auth routes
         * ========================
         */
        $router->get('login', 'AuthController@showLogin');
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');

        /**
         * 
         * dashboard routes
         * 
         */
        $router->get('dashboard/librarian', 'SidebarController@librarian', ['librarian', 'admin', 'superadmin']);
        
        // Admin Sidebar Page Navigation Display
        $router->get('Admin/dashboard', 'SidebarController@adminDashboard', ['admin']);
        $router->get('Admin/userManagement', 'SidebarController@adminUserManagement', ['admin']);
        $router->get('Admin/features', 'SidebarController@adminFeatures', ['admin']);
        $router->get('Admin/bookManagement', 'SidebarController@adminBookManagement', ['admin']);
        $router->get('Admin/equipmentManagement', 'SidebarController@adminEquipmentManagement', ['admin']);
        $router->get('Admin/attendanceLogs', 'SidebarController@adminAttendanceLogs', ['admin']);
        $router->get('Admin/borrowingHistory', 'SidebarController@adminBorrowingHistory', ['admin']);
        $router->get('Admin/overdueAlert', 'SidebarController@adminOverdueAlert', ['admin']);
        $router->get('Admin/globalLogs', 'SidebarController@adminGlobalLogs', ['admin']);
        $router->get('Admin/backupAndRestore', 'SidebarController@adminBackupAndRestore', ['admin']);
        
        // Super Admin Sidebar Page Navigation Display
        $router->get('SuperAdmin/dashboard', 'SidebarController@superAdminDashboard', ['superadmin']);
        $router->get('SuperAdmin/userManagement', 'SidebarController@userManagement', ['superadmin']);
        $router->get('SuperAdmin/features', 'SidebarController@features', ['superadmin']);
        $router->get('SuperAdmin/bookManagement', 'SidebarController@bookManagement', ['superadmin']);
        $router->get('SuperAdmin/equipmentManagement', 'SidebarController@equipmentManagement', ['superadmin']);
        $router->get('SuperAdmin/attendanceLogs', 'SidebarController@attendanceLogs', ['superadmin']);
        $router->get('SuperAdmin/borrowingHistory', 'SidebarController@borrowingHistory', ['superadmin']);
        $router->get('SuperAdmin/overdueAlert', 'SidebarController@overdueAlert', ['superadmin']);
        $router->get('SuperAdmin/globalLogs', 'SidebarController@globalLogs', ['superadmin']);
        $router->get('SuperAdmin/backupAndRestore', 'SidebarController@backupAndRestore', ['superadmin']);
        
        // Student Sidebar Page Navigation Display
        $router->get('Student/dashboard', 'SidebarController@studentDashboard', ['student']);
        $router->get('Student/bookCatalog', 'SidebarController@studentBookCatalog', ['student']);
        $router->get('Student/equipmentCatalog', 'SidebarController@studentEquipmentCatalog', ['student']);
        $router->get('Student/myCart', 'SidebarController@studentMyCart', ['student']);
        $router->get('Student/qrBorrowingTicket', 'SidebarController@studentQrBorrowingTicket', ['student']);
        $router->get('Student/myAttendance', 'SidebarController@studentMyAttendance', ['student']);
        $router->get('Student/borrowingHistory', 'SidebarController@studentBorrowingHistory', ['student']);


        /**
         * 
         * ATTENDANCE ROUTES (SAMPLE LANG TO BES)
         * $router->get('attendance', 'AttendanceController@index', ['librarian', 'admin']);
         * $router->post('attendance/scan', 'AttendanceController@scan', ['librarian']);
         * $router->get('attendance/logs', 'AttendanceController@logs', ['librarian', 'admin']);
         */

        /**
         * 
         * BOOK INVENTORY ROUTES (SAMPLE LANG TO BES)
         * $router->get('books', 'BookController@index', ['librarian', 'admin']);
         * $router->get('books/create', 'BookController@create', ['librarian', 'admin']);
         * $router->post('books/store', 'BookController@store', ['librarian', 'admin']);
         * $router->get('books/edit', 'BookController@edit', ['librarian', 'admin']);
         * $router->post('books/update', 'BookController@update', ['librarian', 'admin']);
         * $router->post('books/delete', 'BookController@delete', ['librarian', 'admin']);
         */


        /**
         *
         * BORROWING AND RETURNING ROUTES (SAMPLE LANG BES)
         * $router->get('borrow', 'BorrowController@index', ['student']);
         * $router->post('borrow/request', 'BorrowController@request', ['student']);
         * $router->post('borrow/approve', 'BorrowController@approve', ['librarian']);
         * $router->post('borrow/return', 'BorrowController@returN', ['librarian']);
         * $router->get('borrow/logs', 'BorrowController@logs', ['librarian', 'admin']);
         */


        /**
         *
         * QR TICKET ROUTES (SAMPLE LANG TO BES)
         * $router->get('ticket/generate', 'TicketController@generate', ['student']);
         * $router->post('ticket/validate', 'TicketController@validate', ['librarian']);
         */


        /**
         *
         * GUEST ROUTES (SAMPLE LANG TO BES)
         * $router->get('books/guest', 'BookController@guest');
         */


        /**
         *
         * UTILITY ROUTES (SAMPLE LANG DIN)
         * $router->get('search', 'SearchController@index');
         * $router->get('guide', 'PageController@guide');
         */


        /**
         *
         * ADMIN FEATURES (SAMPLE LANG TO ERP)
         * $router->get('export/csv', 'ExportController@csv', ['admin']);
         * $router->get('export/pdf', 'ExportController@pdf', ['admin']);
         * $router->post('import/csv', 'ImportController@csv', ['admin']);

         * $router->get('backup', 'BackupController@index', ['admin']);
         * $router->post('backup/run', 'BackupController@run', ['admin']);
         * $router->post('restore', 'BackupController@restore', ['admin']);

         * $router->get('logs', 'AuditController@index', ['admin']);
         * $router->post('feedback', 'FeedbackController@store', ['librarian']);
         */

        return $router;
    }
}
