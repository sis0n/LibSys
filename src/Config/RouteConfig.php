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
        $router->get('librarian/transactionHistory', 'SidebarController@librarianBorrowingHistory', ['librarian']);
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
        $router->get('admin/transactionHistory', 'SidebarController@adminBorrowingHistory', ['admin']);
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
        $router->get('superadmin/transactionHistory', 'SidebarController@borrowingHistory', ['superadmin']);
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



        $router->get('student/bookCatalog/availableCount', 'BookCatalogController@getAvailableCount', ['student']);
        $router->get('student/bookCatalog/fetch', 'BookCatalogController@fetch', ['student']);

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
        $router->post('superadmin/userManagement/allowEdit/{id}', 'UserManagementController@allowEdit', ['superadmin']);

        $router->get('superadmin/booksmanagement/fetch', 'BookManagementController@fetch', ['superadmin']);
        $router->get('superadmin/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['superadmin']);
        $router->post('superadmin/booksmanagement/store', 'BookManagementController@store', ['superadmin']);
        $router->post('superadmin/booksmanagement/update/{id}', 'BookManagementController@update', ['superadmin']);
        $router->post('superadmin/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['superadmin']);

        $router->get('admin/booksmanagement/fetch', 'BookManagementController@fetch', ['admin']);
        $router->get('admin/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['admin']);
        $router->post('admin/booksmanagement/store', 'BookManagementController@store', ['admin']);
        $router->post('admin/booksmanagement/update/{id}', 'BookManagementController@update', ['admin']);
        $router->post('admin/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['admin']);

        $router->get('librarian/booksmanagement/fetch', 'BookManagementController@fetch', ['librarian']);
        $router->get('librarian/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['librarian']);
        $router->post('librarian/booksmanagement/store', 'BookManagementController@store', ['librarian']);
        $router->post('librarian/booksmanagement/update/{id}', 'BookManagementController@update', ['librarian']);
        $router->post('librarian/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['librarian']);

        //student profile routes
        $router->get('student/myprofile/get', 'StudentProfileController@getProfile', ['student']);
        $router->post('student/myprofile/update', 'StudentProfileController@updateProfile', ['student']);


        // Superadmin QR Scanner
        $router->post('superadmin/qrScanner/scanTicket', 'QRScannerController@scan', ['superadmin']);
        $router->post('superadmin/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['superadmin']); // <-- Add this
        $router->post('superadmin/qrScanner/returnTransaction', 'QRScannerController@returnTransaction', ['superadmin']); // <-- Add this
        $router->get('superadmin/qrScanner/transactionHistory', 'QRScannerController@history', ['superadmin']);
        $router->post('superadmin/qrScanner/clearSession', 'QRScannerController@clearSession', ['superadmin']);
        $router->get('superadmin/qrScanner/lookup', 'QRScannerController@lookup', ['superadmin']);

        // Admin QR Scanner
        $router->post('admin/qrScanner/scanTicket', 'QRScannerController@scan', ['admin']);
        $router->post('admin/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['admin']); // <-- Add this
        $router->post('admin/qrScanner/returnTransaction', 'QRScannerController@returnTransaction', ['admin']); // <-- Add this
        $router->get('admin/qrScanner/transactionHistory', 'QRScannerController@history', ['admin']);
        $router->post('admin/qrScanner/clearSession', 'QRScannerController@clearSession', ['admin']);
        $router->get('admin/qrScanner/lookup', 'QRScannerController@lookup', ['admin']);

        // Librarian QR Scanner
        $router->post('librarian/qrScanner/scanTicket', 'QRScannerController@scan', ['librarian']);
        $router->post('librarian/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['librarian']); // <-- Add this
        $router->post('librarian/qrScanner/returnTransaction', 'QRScannerController@returnTransaction', ['librarian']); // <-- Add this
        $router->get('librarian/qrScanner/transactionHistory', 'QRScannerController@history', ['librarian']);
        $router->post('librarian/qrScanner/clearSession', 'QRScannerController@clearSession', ['librarian']);
        $router->get('librarian/qrScanner/lookup', 'QRScannerController@lookup', ['librarian']);


        return $router;
    }
}
