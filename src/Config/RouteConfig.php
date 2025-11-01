<?php

namespace App\Config;

use App\Core\Router;
use App\Controllers\ViewController; // Siguraduhin na na-import

class RouteConfig
{
    public static function register(): Router
    {
        $router = new Router();

        // --- VIEW ROUTES (Walang API prefix) ---
        $router->get('landingPage', 'GuestController@guestDisplay');
        $router->get('login', 'AuthController@showLogin');
        $router->get('forgotPassword', 'AuthController@forgotPassword');
        $router->get('scanner/attendance', 'ScannerController@scannerDisplay', ['scanner']);

        // --- API / DATA ROUTES (Lahat ay may 'api/' prefix) ---

        // Auth API
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
        $router->post('api/change-password', 'AuthController@changePassword', ['change password']);

        // Scanner API
        $router->post('api/scanner/scan', 'ScannerController@attendance', ['scanner']);
        $router->post('api/scanner/manual', 'ScannerController@manual', ['scanner']);

        // Guest API
        $router->get('api/guest/fetchBooks', 'GuestController@fetchGuestBooks');

        // --- FACULTY (AJAX/Data Routes) ---
        $router->get('api/faculty/attendance/get', 'AttendanceController@getMyAttendance', ['faculty']);
        $router->get('api/faculty/qrBorrowingTicket/checkStatus', 'FacultyTicketController@checkStatus');
        $router->get('api/faculty/bookCatalog/availableCount', 'FacultyBookCatalogController@getAvailableCount', ['faculty']);
        $router->get('api/faculty/bookCatalog/fetch', 'FacultyBookCatalogController@fetch', ['faculty']);
        $router->get('api/faculty/cart', 'FacultyCartController@index', ['faculty']);
        $router->get('api/faculty/cart/add/{id}', 'FacultyCartController@add', ['faculty']);
        $router->post('api/faculty/cart/remove/{id}', 'FacultyCartController@remove', ['faculty']);
        $router->post('api/faculty/cart/clear', 'FacultyCartController@clearCart', ['faculty']);
        $router->get('api/faculty/cart/json', 'FacultyCartController@getCartJson', ['faculty']);
        $router->post('api/faculty/cart/checkout', 'FacultyTicketController@checkout', ['faculty']);
        $router->get('api/faculty/qrBorrowingTicket', 'FacultyTicketController@show', ['faculty']);
        $router->get('api/faculty/myprofile/get', 'FacultyProfileController@getProfile', ['faculty']);
        $router->post('api/faculty/myprofile/update', 'FacultyProfileController@updateProfile', ['faculty']);
        $router->get('api/faculty/borrowingHistory/fetch', 'FacultyBorrowingHistoryController@fetchHistory', ['faculty']);

        // --- STAFF (AJAX/Data Routes) ---
        $router->get('api/staff/attendance/get', 'AttendanceController@getMyAttendance', ['staff']);
        $router->get('api/staff/qrBorrowingTicket/checkStatus', 'StaffTicketController@checkStatus', ['staff']);
        $router->get('api/staff/bookCatalog/availableCount', 'StaffBookCatalogController@getAvailableCount', ['staff']);
        $router->get('api/staff/bookCatalog/fetch', 'StaffBookCatalogController@fetch', ['staff']);
        $router->get('api/staff/cart', 'StaffCartController@index', ['staff']);
        $router->get('api/staff/cart/add/{id}', 'StaffCartController@add', ['staff']);
        $router->post('api/staff/cart/remove/{id}', 'StaffCartController@remove', ['staff']);
        $router->post('api/staff/cart/clear', 'StaffCartController@clearCart', ['staff']);
        $router->get('api/staff/cart/json', 'StaffCartController@getCartJson', ['staff']);
        $router->post('api/staff/cart/checkout', 'StaffTicketController@checkout', ['staff']);
        $router->get('api/staff/qrBorrowingTicket', 'StaffTicketController@show', ['staff']);
        $router->get('api/staff/myprofile/get', 'StaffProfileController@getProfile', ['staff']);
        $router->post('api/staff/myprofile/update', 'StaffProfileController@updateProfile', ['staff']);
        $router->get('api/staff/borrowingHistory/fetch', 'StaffBorrowingHistoryController@fetchHistory', ['staff']);

        // --- LIBRARIAN (AJAX/Data Routes) ---
        $router->get('api/librarian/booksmanagement/fetch', 'BookManagementController@fetch', ['book management']);
        $router->get('api/librarian/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['book management']);
        $router->post('api/librarian/booksmanagement/store', 'BookManagementController@store', ['book management']);
        $router->post('api/librarian/booksmanagement/update/{id}', 'BookManagementController@update', ['book management']);
        $router->post('api/librarian/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['book management']);
        $router->post('api/librarian/qrScanner/scanTicket', 'QRScannerController@scan', ['qr scanner']);
        $router->post('api/librarian/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['qr scanner']);
        $router->get('api/librarian/returning/getTableData', 'ReturningController@getDueSoonAndOverdue', ['returning']);
        $router->post('api/librarian/returning/checkBook', 'ReturningController@checkBookStatus', ['returning']);
        $router->post('api/librarian/returning/markReturned', 'ReturningController@returnBook', ['returning']);
        $router->post('api/librarian/returning/extend', 'ReturningController@extendDueDate', ['returning']);

        // --- ADMIN (AJAX/Data Routes) ---
        $router->get('api/admin/restoreBooks/fetch', 'RestoreBookController@getDeletedBooksJson', ['restore books']);
        $router->post('api/admin/restoreBooks/restore', 'RestoreBookController@restore', ['restore books']);
        $router->post('api/admin/restoreBooks/archive/{id}', 'RestoreBookController@archiveBookAction', ['restore books']);
        $router->get('api/admin/bookManagement/fetch', 'BookManagementController@fetch', ['book management']);
        $router->get('api/admin/bookManagement/get/{id}', 'BookManagementController@getDetails', ['book management']);
        $router->post('api/admin/bookManagement/store', 'BookManagementController@store', ['book management']);
        $router->post('api/admin/bookManagement/update/{id}', 'BookManagementController@update', ['book management']);
        $router->post('api/admin/bookManagement/delete/{id}', 'BookManagementController@destroy', ['book management']);
        $router->post('api/admin/bookManagement/bulkImport', 'BookManagementController@bulkImport', ['book management']);
        $router->post('api/admin/qrScanner/scanTicket', 'QRScannerController@scan', ['qr scanner']);
        $router->post('api/admin/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['qr scanner']);
        $router->get('api/admin/returning/getTableData', 'ReturningController@getDueSoonAndOverdue', ['returning']);
        $router->post('api/admin/returning/checkBook', 'ReturningController@checkBookStatus', ['returning']);
        $router->post('api/admin/returning/markReturned', 'ReturningController@returnBook', ['returning']);
        $router->post('api/admin/returning/extend', 'ReturningController@extendDueDate', ['returning']);

        $router->get('admin/attendanceLogs', 'SidebarController@adminAttendanceLogs', ['attendance logs']);
        $router->get('admin/topVisitor', 'SidebarController@adminTopVisitor', ['reports']);
        $router->get('admin/transactionHistory', 'SidebarController@adminBorrowingHistory', ['transaction history']);
        $router->get('admin/globalLogs', 'SidebarController@adminGlobalLogs', ['global logs']);
        $router->get('admin/restoreBooks', 'SidebarController@AdminRestoreBooks', ['restore books']);
        $router->get('admin/borrowingForm', 'SidebarController@borrowingForm', ['borrowing form']);

        



        // --- SUPERADMIN ROUTES ---
        $router->get('superadmin/dashboard', 'SidebarController@superAdminDashboard', ['superadmin']);
        $router->get('superadmin/userManagement', 'SidebarController@userManagement', ['superadmin']);
        $router->get('superadmin/bookManagement', 'SidebarController@bookManagement', ['superadmin']);
        $router->get('superadmin/equipmentManagement', 'SidebarController@equipmentManagement', ['superadmin']);
        $router->get('superadmin/qrScanner', 'SidebarController@qrScanner', ['superadmin']);
        $router->get('superadmin/attendanceLogs', 'SidebarController@attendanceLogs', ['superadmin']);
        $router->get('superadmin/topVisitor', 'SidebarController@topVisitor', ['superadmin']);
        $router->get('report/circulated-books-report', 'ReportController@getCirculatedBooksReport', ['superadmin']);
        $router->get('superadmin/transactionHistory', 'SidebarController@borrowingHistory', ['superadmin']);
        $router->get('superadmin/returning', 'SidebarController@returning', ['superadmin']);
        $router->get('superadmin/borrowingForm', 'SidebarController@borrowingForm', ['superadmin']);
        $router->get('superadmin/changePassword', 'SidebarController@changePassword', ['superadmin']);
        $router->get('superadmin/myProfile', 'SidebarController@superadminMyProfile', ['superadmin']);
        $router->get('superadmin/backup', 'SidebarController@backup', ['superadmin']);
        $router->get('superadmin/restoreBooks', 'SidebarController@restoreBooks', ['superadmin']);
        $router->get('superadmin/restoreUser', 'SidebarController@restoreUser', ['superadmin']);
        $router->get('superadmin/restoreEquipment', 'SidebarController@restoreEquipment', ['superadmin']);

        $router->get('superadmin/userManagement/getAll', 'UserManagementController@getAll', ['superadmin']);
        $router->get('superadmin/userManagement/get/{id}', 'UserManagementController@getUserById', ['superadmin']);
        $router->get('superadmin/userManagement/search', 'UserManagementController@search', ['superadmin']);
        $router->post('superadmin/userManagement/add', 'UserManagementController@addUser', ['superadmin']);
        $router->post('superadmin/userManagement/update/{id}', 'UserManagementController@updateUser', ['superadmin']);
        $router->post('superadmin/userManagement/delete/{id}', 'UserManagementController@deleteUser', ['superadmin']);
        $router->post('superadmin/userManagement/toggleStatus/{id}', 'UserManagementController@toggleStatus', ['superadmin']);
        $router->post('superadmin/userManagement/allowEdit/{id}', 'UserManagementController@allowEdit', ['superadmin']);
        $router->post('superadmin/userManagement/bulkImport', 'UserManagementController@bulkImport', ['superadmin']);
        $router->get('superadmin/booksmanagement/fetch', 'BookManagementController@fetch', ['superadmin']);
        $router->get('superadmin/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['superadmin']);
        $router->post('superadmin/booksmanagement/store', 'BookManagementController@store', ['superadmin']);
        $router->post('superadmin/booksmanagement/update/{id}', 'BookManagementController@update', ['superadmin']);
        $router->post('superadmin/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['superadmin']);
        $router->post('superadmin/booksmanagement/bulkImport', 'BookManagementController@bulkImport', ['superadmin']);
        $router->post('superadmin/qrScanner/scanTicket', 'QRScannerController@scan', ['superadmin']);
        $router->post('superadmin/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['superadmin']);
        $router->get('superadmin/qrScanner/transactionHistory', 'QRScannerController@history', ['superadmin']);
        $router->get('superadmin/returning/getTableData', 'ReturningController@getDueSoonAndOverdue', ['superadmin']);
        $router->post('superadmin/returning/checkBook', 'ReturningController@checkBookStatus', ['superadmin']);
        $router->post('superadmin/returning/markReturned', 'ReturningController@returnBook', ['superadmin']);
        $router->post('superadmin/returning/extend', 'ReturningConadmin/backuptroller@extendDueDate', ['superadmin']);
        $router->get('superadmin/restoreUser/fetch', 'RestoreUserController@getDeletedUsersJson', ['superadmin']);
        $router->post('superadmin/restoreUser/restore', 'RestoreUserController@restore', ['superadmin']);
        $router->post('superadmin/restoreUser/delete/{id}', 'RestoreUserController@archive', ['superadmin']);
        $router->get('superadmin/restoreBooks/fetch', 'RestoreBookController@getDeletedBooksJson', ['superadmin']);
        $router->post('superadmin/restoreBooks/restore', 'RestoreBookController@restore', ['superadmin']);
        $router->post('superadmin/restoreBooks/archive/{id}', 'RestoreBookController@archiveBookAction', ['superadmin']);
        $router->get('superadmin/backup/export/zip/{table}', 'BackupController@exportBothFormats', ['superadmin']);
        $router->get('superadmin/backup/database/full', 'BackupController@initiateBackup', ['superadmin']);
        $router->get('superadmin/backup/secure_download/{filename}', 'BackupController@downloadBackup', ['superadmin']);
        $router->get('superadmin/backup/logs', 'BackupController@listBackupLogs', ['superadmin']);
        $router->get('superadmin/dashboard/stats', 'App\Controllers\DashboardController@getStats', ['superadmin']);
        $router->get('superadmin/dashboard/top-visitors', 'App\Controllers\DashboardController@getTopVisitors', ['superadmin']);
        $router->get('superadmin/dashboard/weekly-activity', 'App\Controllers\DashboardController@getWeeklyActivity', ['superadmin']);
        $router->get('superadmin/dashboard/getData', 'DashboardController@getData', ['superadmin']);
        $router->post('superadmin/borrowingForm/manualBorrow', 'ManualBorrowController@store', ['superadmin']);
        $router->get('superadmin/transactionHistory/json', 'TransactionHistoryController@getTransactionsJson', ['superadmin']);
        $router->get('superadmin/borrowingForm/manualBorrow', 'ManualBorrowingController@manualBorrow', ['superadmin']);
        $router->post('superadmin/borrowingForm/checkUser', 'ManualBorrowingController@checkUser');
        $router->post('superadmin/borrowingForm/create', 'ManualBorrowingController@create');

        // --- STUDENT (AJAX/Data Routes) ---
        $router->get('api/student/attendance/get', 'AttendanceController@getMyAttendance', ['student']);
        $router->get('api/student/cart', 'CartController@index', ['student']);
        $router->get('api/student/cart/add/{id}', 'CartController@add', ['student']);
        $router->post('api/student/cart/remove/{id}', 'CartController@remove', ['student']);
        $router->post('api/student/cart/clear', 'CartController@clearCart', ['student']);
        $router->get('api/student/cart/json', 'CartController@getCartJson', ['student']);
        $router->post('api/student/cart/checkout', 'TicketController@checkout', ['student']);
        $router->get('api/student/qrBorrowingTicket/checkStatus', 'TicketController@checkStatus');
        $router->get('api/student/bookCatalog/availableCount', 'BookCatalogController@getAvailableCount', ['student']);
        $router->get('api/student/bookCatalog/fetch', 'BookCatalogController@fetch', ['student']);
        $router->get('api/student/borrowingHistory/fetch', 'StudentBorrowingHistoryController@fetchHistory', ['student']);
        $router->get('api/student/myprofile/get', 'StudentProfileController@getProfile', ['student']);
        $router->post('api/student/myprofile/update', 'StudentProfileController@updateProfile', ['student']);

        // General AJAX routes
        $router->get('api/attendance/logs/ajax', 'AttendanceController@fetchLogsAjax', ['attendance logs', 'superadmin']);


        // ----------------------------------------------------------------------
        // --- BAGONG GENERIC VIEW ROUTES (Fully Generic Plan) ---
        // ----------------------------------------------------------------------

        // 1. GENERIC DASHBOARD ROUTE (Para sa login redirect)
        $router->get('dashboard', 'ViewController@handleDashboard');

        // 2. DYNAMIC VIEW ROUTES (Pumapalit sa lahat ng SidebarController)
        // Hahawakan nito ang /myProfile, /bookManagement, atbp.
        $router->get('{action}', 'ViewController@handleGenericPage');
        $router->get('{action}/{id}', 'ViewController@handleGenericPage');

        // (Magdagdag ng POST kung kailangan mong mag-submit ng form papunta sa view)
        // $router->post('{action}', 'ViewController@handleGenericPage');

        return $router;
    }
}
