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
         * Landing Page Routes
         * ========================
         */
        $router->get('landingPage', 'GuestController@guestDisplay');
        $router->get('guest/fetchBooks', 'GuestController@fetchGuestBooks');

        /**
         * ========================
         * auth routes
         * ========================
         */
        $router->get('login', 'AuthController@showLogin');
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');

        /**
         * ========================
         * forgot password route
         * ========================
         */
        $router->get('forgotPassword', 'AuthController@forgotPassword');

        /**
         * ========================
         * scanner route
         * ========================
         */
        $router->post('scanner/scan', 'ScannerController@attendance', ['scanner']);
        $router->get('scanner/attendance', 'ScannerController@scannerDisplay', ['scanner']);
        $router->post('scanner/manual', 'ScannerController@manual', ['scanner']);


        /**
         * ========================
         * dashboard routes
         * ========================
         */
        // Librarian Sidebar Page Navigation Display
        $router->get('librarian/dashboard', 'SidebarController@librarianDashboard', ['librarian']);
        $router->get('librarian/bookManagement', 'SidebarController@librarianBookManagement', ['librarian']);
        $router->get('librarian/equipmentManagement', 'SidebarController@librarianEquipmentManagement', ['librarian']);
        $router->get('librarian/qrScanner', 'SidebarController@librarianQrScanner', ['librarian']);
        $router->get('librarian/attendanceLogs', 'SidebarController@librarianAttendanceLogs', ['librarian']);
        $router->get('librarian/topVisitor', 'SidebarController@librarianTopVisitor', ['librarian']);
        $router->get('librarian/borrowingHistory', 'SidebarController@librarianBorrowingHistory', ['librarian']);
        $router->get('librarian/overdueAlert', 'SidebarController@librarianOverdueAlert', ['librarian']);
        $router->get('librarian/globalLogs', 'SidebarController@librarianGlobalLogs', ['librarian']);
        $router->get('librarian/backupAndRestore', 'SidebarController@librarianBackupAndRestore', ['librarian']);
        $router->get('librarian/changePassword', 'SidebarController@librarianChangePassword', ['librarian']);
        $router->get('librarian/myProfile', 'SidebarController@librarianMyProfile', ['librarian']);


        // Admin Sidebar Page Navigation Display
        $router->get('admin/dashboard', 'SidebarController@adminDashboard', ['admin']);
        $router->get('admin/bookManagement', 'SidebarController@adminBookManagement', ['admin']);
        $router->get('admin/equipmentManagement', 'SidebarController@adminEquipmentManagement', ['admin']);
        $router->get('admin/qrScanner', 'SidebarController@adminQrScanner', ['admin']);
        $router->get('admin/attendanceLogs', 'SidebarController@adminAttendanceLogs', ['admin']);
        $router->get('admin/topVisitor', 'SidebarController@adminTopVisitor', ['admin']);
        $router->get('admin/borrowingHistory', 'SidebarController@adminBorrowingHistory', ['admin']);
        $router->get('admin/overdueAlert', 'SidebarController@adminOverdueAlert', ['admin']);
        $router->get('admin/globalLogs', 'SidebarController@adminGlobalLogs', ['admin']);
        $router->get('admin/backupAndRestore', 'SidebarController@adminBackupAndRestore', ['admin']);
        $router->get('admin/changePassword', 'SidebarController@adminChangePassword', ['admin']);
        $router->get('admin/myProfile', 'SidebarController@adminMyProfile', ['admin']);


        // Super Admin Sidebar Page Navigation Display
        $router->get('superadmin/dashboard', 'SidebarController@superAdminDashboard', ['superadmin']);
        $router->get('superadmin/userManagement', 'SidebarController@userManagement', ['superadmin']);
        $router->get('superadmin/bookManagement', 'SidebarController@bookManagement', ['superadmin']);
        $router->get('superadmin/equipmentManagement', 'SidebarController@equipmentManagement', ['superadmin']);
        $router->get('superadmin/qrScanner', 'SidebarController@qrScanner', ['superadmin']);
        $router->get('superadmin/attendanceLogs', 'SidebarController@attendanceLogs', ['superadmin']);
        $router->get('superadmin/topVisitor', 'SidebarController@topVisitor', ['superadmin']);
        $router->get('superadmin/borrowingHistory', 'SidebarController@borrowingHistory', ['superadmin']);
        $router->get('superadmin/overdueAlert', 'SidebarController@overdueAlert', ['superadmin']);
        $router->get('superadmin/globalLogs', 'SidebarController@globalLogs', ['superadmin']);
        $router->get('superadmin/backupAndRestore', 'SidebarController@backupAndRestore', ['superadmin']);
        $router->get('superadmin/changePassword', 'SidebarController@changePassword', ['superadmin']);
        $router->get('superadmin/myProfile', 'SidebarController@superadminMyProfile', ['superadmin']);


        // Student Sidebar Page Navigation Display
        $router->get('student/dashboard', 'SidebarController@studentDashboard', ['student']);
        $router->get('student/bookCatalog', 'SidebarController@studentBookCatalog', ['student']);
        $router->get('student/equipmentCatalog', 'SidebarController@studentEquipmentCatalog', ['student']);
        $router->get('student/myCart', 'SidebarController@studentMyCart', ['student']);
        $router->get('student/qrBorrowingTicket', 'SidebarController@studentQrBorrowingTicket', ['student']);
        $router->get('student/myAttendance', 'SidebarController@studentMyAttendance', ['student']);
        $router->get('student/borrowingHistory', 'SidebarController@studentBorrowingHistory', ['student']);
        $router->get('student/attendance/get', 'AttendanceController@getMyAttendance', ['student']);
        $router->get('student/changePassword', 'SidebarController@studentChangePassword', ['student']);
        $router->get('student/myProfile', 'SidebarController@studentMyProfile', ['student']);



        /**
         * 
         * ATTENDANCE ROUTES (SAMPLE LANG TO BES)
         * $router->get('attendance', 'AttendanceController@index', ['librarian', 'admin']);
         * $router->post('attendance/scan', 'AttendanceController@scan', ['librarian']);
         * $router->get('attendance/logs', 'AttendanceController@logs', ['librarian', 'admin']);
         */

        // AJAX route para sa dropdown filter logs (librarian at admin)
        $router->get('attendance/logs/ajax', 'AttendanceController@fetchLogsAjax', ['librarian', 'admin', 'superadmin']);


        /**
         * ========================
         * BOOK INVENTORY ROUTES
         * ========================
         */

        $router->get('books', 'BookController@index', ['librarian', 'admin']);
        $router->get('books/create', 'BookController@create', ['librarian', 'admin']);
        $router->post('books/store', 'BookController@store', ['librarian', 'admin']);
        $router->get('books/edit/{id}', 'BookController@edit', ['librarian', 'admin']);
        $router->post('books/update/{id}', 'BookController@update', ['librarian', 'admin']);
        $router->post('books/delete/{id}', 'BookController@destroy', ['librarian', 'admin']);
        $router->get('books/search', 'BookController@search', ['librarian', 'admin']);
        $router->get('books/filter', 'BookController@filter', ['librarian', 'admin']);

        /**
         * ========================
         * STUDENT CART & CHECKOUT ROUTES
         * ========================
         */
        $router->get('student/cart', 'CartController@index', ['student']);
        $router->get('student/cart/add/{id}', 'CartController@add', ['student']);
        $router->post('student/cart/remove/{id}', 'CartController@remove', ['student']);
        $router->post('student/cart/clear', 'CartController@clearCart', ['student']);
        $router->get('student/cart/json', 'CartController@getCartJson', ['student']);


        // checkout routes
        $router->post('student/cart/checkout', 'TicketController@checkout', ['student']);
        $router->get('student/qrBorrowingTicket', 'TicketController@show', ['student']);



        $router->get('student/bookCatalog/availableCount', 'BookController@getAvailableCount', ['student']);
        $router->get('student/bookCatalog/fetch', 'BookController@fetch', ['student']);

        //change password
        $router->post('/change-password', 'AuthController@changePassword');


        // ===============================
        // SUPERADMIN USER MANAGEMENT ROUTES
        // ===============================

        // $router->get('superadmin/userManagement', 'UserManagementController@index', ['superadmin']);
        $router->get('superadmin/userManagement/getAll', 'UserManagementController@getAll', ['superadmin']);
        $router->get('superadmin/userManagement/get/{id}', 'UserManagementController@getUserById', ['superadmin']);
        $router->get('superadmin/userManagement/search', 'UserManagementController@search', ['superadmin']);
        $router->post('superadmin/userManagement/add', 'UserManagementController@addUser');
        $router->post('superadmin/userManagement/update/{id}', 'UserManagementController@updateUser', ['superadmin']);
        $router->post('superadmin/userManagement/delete/{id}', 'UserManagementController@deleteUser', ['superadmin']);
        $router->post('superadmin/userManagement/toggleStatus/{id}', 'UserManagementController@toggleStatus', ['superadmin']);

        return $router;
    }
}
