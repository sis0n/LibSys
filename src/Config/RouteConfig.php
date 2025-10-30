<?php

namespace App\Config;

use App\Core\Router;

class RouteConfig
{
    public static function register(): Router
    {
        $router = new Router();

        // Guest Routes
        $router->get('landingPage', 'GuestController@guestDisplay');
        $router->get('guest/fetchBooks', 'GuestController@fetchGuestBooks');

        // Auth Routes
        $router->get('login', 'AuthController@showLogin');
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
        $router->get('forgotPassword', 'AuthController@forgotPassword');
        $router->post('/change-password', 'AuthController@changePassword', ['change password']); // Secured POST

        // Scanner Routes
        $router->post('scanner/scan', 'ScannerController@attendance', ['scanner']);
        $router->get('scanner/attendance', 'ScannerController@scannerDisplay', ['scanner']);
        $router->post('scanner/manual', 'ScannerController@manual', ['scanner']);

        // --- FACULTY ROUTES ---
        $router->get('faculty/dashboard', 'SidebarController@facultyDashboard', ['faculty']);
        $router->get('faculty/bookCatalog', 'SidebarController@facultyBookCatalog', ['faculty']);
        $router->get('faculty/equipmentCatalog', 'SidebarController@facultyEquipmentCatalog', ['faculty']);
        $router->get('faculty/myCart', 'SidebarController@facultyMyCart', ['faculty']);
        $router->get('faculty/qrBorrowingTicket', 'SidebarController@facultyQrBorrowingTicket', ['faculty']);
        $router->get('faculty/myAttendance', 'SidebarController@facultyMyAttendance', ['faculty']);
        $router->get('faculty/borrowingHistory', 'SidebarController@facultyBorrowingHistory', ['faculty']);
        $router->get('faculty/attendance/get', 'AttendanceController@getMyAttendance', ['faculty']);
        $router->get('faculty/changePassword', 'SidebarController@facultyChangePassword', ['faculty']);
        $router->get('faculty/myProfile', 'SidebarController@facultyMyProfile', ['faculty']);
        $router->get('faculty/qrBorrowingTicket/checkStatus', 'FacultyTicketController@checkStatus');
        $router->get('faculty/bookCatalog/availableCount', 'FacultyBookCatalogController@getAvailableCount', ['faculty']);
        $router->get('faculty/bookCatalog/fetch', 'FacultyBookCatalogController@fetch', ['faculty']);
        $router->get('faculty/cart', 'FacultyCartController@index', ['faculty']);
        $router->get('faculty/cart/add/{id}', 'FacultyCartController@add', ['faculty']);
        $router->post('faculty/cart/remove/{id}', 'FacultyCartController@remove', ['faculty']);
        $router->post('faculty/cart/clear', 'FacultyCartController@clearCart', ['faculty']);
        $router->get('faculty/cart/json', 'FacultyCartController@getCartJson', ['faculty']);
        $router->post('faculty/cart/checkout', 'FacultyTicketController@checkout', ['faculty']);
        $router->get('faculty/qrBorrowingTicket', 'FacultyTicketController@show', ['faculty']);
        $router->get('faculty/myprofile/get', 'FacultyProfileController@getProfile', ['faculty']);
        $router->post('faculty/myprofile/update', 'FacultyProfileController@updateProfile', ['faculty']);
        $router->get('faculty/borrowingHistory/fetch', 'FacultyBorrowingHistoryController@fetchHistory', ['faculty']);

        // staff
        $router->get('staff/dashboard', 'SidebarController@staffDashboard', ['staff']);
        $router->get('staff/bookCatalog', 'SidebarController@staffBookCatalog', ['staff']);
        $router->get('staff/equipmentCatalog', 'SidebarController@staffEquipmentCatalog', ['staff']);
        $router->get('staff/myCart', 'SidebarController@staffMyCart', ['staff']);
        $router->get('staff/qrBorrowingTicket', 'SidebarController@staffQrBorrowingTicket', ['staff']);
        $router->get('staff/myAttendance', 'SidebarController@staffMyAttendance', ['staff']);
        $router->get('staff/borrowingHistory', 'SidebarController@staffBorrowingHistory', ['staff']);
        $router->get('staff/attendance/get', 'AttendanceController@getMyAttendance', ['staff']);
        $router->get('staff/changePassword', 'SidebarController@staffChangePassword', ['staff']);
        $router->get('staff/myProfile', 'SidebarController@staffMyProfile', ['staff']);


        // --- LIBRARIAN ROUTES (Permission Controlled) ---
        $router->get('librarian/dashboard', 'SidebarController@librarianDashboard', ['dashboard']); // Default Privilege
        $router->get('librarian/myProfile', 'SidebarController@librarianMyProfile', ['my profile']); // Default Privilege
        $router->get('librarian/changePassword', 'SidebarController@librarianChangePassword', ['change password']); // Default Privilege

        // Modules
        $router->get('librarian/bookManagement', 'SidebarController@librarianBookManagement', ['book management']);
        $router->get('librarian/booksmanagement/fetch', 'BookManagementController@fetch', ['book management']);
        $router->get('librarian/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['book management']);
        $router->post('librarian/booksmanagement/store', 'BookManagementController@store', ['book management']);
        $router->post('librarian/booksmanagement/update/{id}', 'BookManagementController@update', ['book management']);
        $router->post('librarian/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['book management']);

        $router->get('librarian/equipmentManagement', 'SidebarController@librarianEquipmentManagement', ['equipment management']);

        $router->get('librarian/qrScanner', 'SidebarController@librarianQrScanner', ['qr scanner']);
        $router->post('librarian/qrScanner/scanTicket', 'QRScannerController@scan', ['qr scanner']);
        $router->post('librarian/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['qr scanner']);

        $router->get('librarian/returning', 'SidebarController@librarianReturning', ['returning']);
        $router->get('librarian/returning/getTableData', 'ReturningController@getDueSoonAndOverdue', ['returning']);
        $router->post('librarian/returning/checkBook', 'ReturningController@checkBookStatus', ['returning']);
        $router->post('librarian/returning/markReturned', 'ReturningController@returnBook', ['returning']);
        $router->post('librarian/returning/extend', 'ReturningController@extendDueDate', ['returning']);

        $router->get('librarian/attendanceLogs', 'SidebarController@librarianAttendanceLogs', ['attendance logs']);
        $router->get('librarian/topVisitor', 'SidebarController@librarianTopVisitor', ['reports']);
        $router->get('librarian/transactionHistory', 'SidebarController@librarianBorrowingHistory', ['transaction history']);
        $router->get('librarian/globalLogs', 'SidebarController@librarianGlobalLogs', ['global logs']);
        $router->get('librarian/backupAndRestore', 'SidebarController@librarianBackupAndRestore', ['backup']);
        $router->get('librarian/restoreBooks', 'SidebarController@restoreBooks', ['restore books']);
        $router->get('librarian/borrowingForm', 'SidebarController@borrowingForm', ['borrowing form']);



        // --- ADMIN ROUTES (Permission Controlled) ---
        $router->get('admin/dashboard', 'SidebarController@adminDashboard', ['dashboard']); // Default Privilege
        $router->get('admin/myProfile', 'SidebarController@adminMyProfile', ['my profile']); // Default Privilege
        $router->get('admin/changePassword', 'SidebarController@adminChangePassword', ['change password']); // Default Privilege

        // Modules
        $router->get('admin/bookManagement', 'SidebarController@adminBookManagement', ['book management']);
        $router->get('admin/booksmanagement/fetch', 'BookManagementController@fetch', ['book management']);
        $router->get('admin/booksmanagement/get/{id}', 'BookManagementController@getDetails', ['book management']);
        $router->post('admin/booksmanagement/store', 'BookManagementController@store', ['book management']);
        $router->post('admin/booksmanagement/update/{id}', 'BookManagementController@update', ['book management']);
        $router->post('admin/booksmanagement/delete/{id}', 'BookManagementController@destroy', ['book management']);

        $router->get('admin/equipmentManagement', 'SidebarController@adminEquipmentManagement', ['equipment management']);

        $router->get('admin/qrScanner', 'SidebarController@adminQrScanner', ['qr scanner']);
        $router->post('admin/qrScanner/scanTicket', 'QRScannerController@scan', ['qr scanner']);
        $router->post('admin/qrScanner/borrowTransaction', 'QRScannerController@borrowTransaction', ['qr scanner']);

        $router->get('admin/returning', 'SidebarController@adminReturning', ['returning']);
        $router->get('admin/returning/getTableData', 'ReturningController@getDueSoonAndOverdue', ['returning']);
        $router->post('admin/returning/checkBook', 'ReturningController@checkBookStatus', ['returning']);
        $router->post('admin/returning/markReturned', 'ReturningController@returnBook', ['returning']);
        $router->post('admin/returning/extend', 'ReturningController@extendDueDate', ['returning']);

        $router->get('admin/attendanceLogs', 'SidebarController@adminAttendanceLogs', ['attendance logs']);
        $router->get('admin/topVisitor', 'SidebarController@adminTopVisitor', ['reports']);
        $router->get('admin/transactionHistory', 'SidebarController@adminBorrowingHistory', ['transaction history']);
        $router->get('admin/globalLogs', 'SidebarController@adminGlobalLogs', ['global logs']);
        $router->get('admin/backupAndRestore', 'SidebarController@adminBackupAndRestore', ['backup']);
        $router->get('admin/restoreBooks', 'SidebarController@restoreBooks', ['restore books']);
        $router->get('admin/borrowingForm', 'SidebarController@borrowingForm', ['borrowing form']);



        // --- SUPERADMIN ROUTES ---
        $router->get('superadmin/dashboard', 'SidebarController@superAdminDashboard', ['superadmin']);
        $router->get('superadmin/userManagement', 'SidebarController@userManagement', ['superadmin']);
        $router->get('superadmin/bookManagement', 'SidebarController@bookManagement', ['superadmin']);
        $router->get('superadmin/equipmentManagement', 'SidebarController@equipmentManagement', ['superadmin']);
        $router->get('superadmin/qrScanner', 'SidebarController@qrScanner', ['superadmin']);
        $router->get('superadmin/attendanceLogs', 'SidebarController@attendanceLogs', ['superadmin']);
        $router->get('superadmin/topVisitor', 'SidebarController@topVisitor', ['superadmin']);
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
        $router->post('superadmin/returning/extend', 'ReturningController@extendDueDate', ['superadmin']);
        $router->get('superadmin/restoreUser/fetch', 'RestoreUserController@getDeletedUsersJson', ['superadmin']);
        $router->post('superadmin/restoreUser/restore', 'RestoreUserController@restore', ['superadmin']);
        $router->post('superadmin/restoreUser/delete/{id}', 'RestoreUserController@archive', ['superadmin']);
        $router->get('superadmin/restoreBooks/fetch', 'RestoreBookController@getDeletedBooksJson', ['superadmin']);
        $router->post('superadmin/restoreBooks/restore', 'RestoreBookController@restore', ['superadmin']);
        $router->post('superadmin/restoreBooks/archive/{id}', 'RestoreBookController@archiveBookAction', ['superadmin']);
        $router->get('/superadmin/backup/export/zip/{table}', 'BackupController@exportBothFormats', ['superadmin']);
        $router->get('/superadmin/backup/database/full', 'BackupController@initiateBackup', ['superadmin']);
        $router->get('/superadmin/backup/secure_download/{filename}', 'BackupController@downloadBackup', ['superadmin']);
        $router->get('/superadmin/backup/logs', 'BackupController@listBackupLogs', ['superadmin']);
        $router->get('superadmin/dashboard/stats', 'App\Controllers\DashboardController@getStats', ['superadmin']);
        $router->get('superadmin/dashboard/top-visitors', 'App\Controllers\DashboardController@getTopVisitors', ['superadmin']);
        $router->get('superadmin/dashboard/weekly-activity', 'App\Controllers\DashboardController@getWeeklyActivity', ['superadmin']);
        $router->get('superadmin/dashboard/getData', 'DashboardController@getData', ['superadmin']);
        $router->post('superadmin/borrowingForm/manualBorrow', 'ManualBorrowController@store', ['superadmin']);
        $router->get('superadmin/transactionHistory/json', 'TransactionHistoryController@getTransactionsJson', ['superadmin']);
        $router->get('superadmin/borrowingForm/manualBorrow', 'ManualBorrowingController@manualBorrow', ['superadmin']);
        $router->post('superadmin/borrowingForm/checkUser', 'ManualBorrowingController@checkUser');
        $router->post('superadmin/borrowingForm/create', 'ManualBorrowingController@create');


        // --- STUDENT ROUTES ---
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
        $router->get('student/cart', 'CartController@index', ['student']);
        $router->get('student/cart/add/{id}', 'CartController@add', ['student']);
        $router->post('student/cart/remove/{id}', 'CartController@remove', ['student']);
        $router->post('student/cart/clear', 'CartController@clearCart', ['student']);
        $router->get('student/cart/json', 'CartController@getCartJson', ['student']);
        $router->post('student/cart/checkout', 'TicketController@checkout', ['student']);
        $router->get('student/qrBorrowingTicket/checkStatus', 'TicketController@checkStatus');
        $router->get('student/bookCatalog/availableCount', 'BookCatalogController@getAvailableCount', ['student']);
        $router->get('student/bookCatalog/fetch', 'BookCatalogController@fetch', ['student']);
        $router->get('student/borrowingHistory/fetch', 'StudentBorrowingHistoryController@fetchHistory', ['student']);
        $router->get('student/myprofile/get', 'StudentProfileController@getProfile', ['student']);
        $router->post('student/myprofile/update', 'StudentProfileController@updateProfile', ['student']);


        // General AJAX routes
        $router->get('attendance/logs/ajax', 'AttendanceController@fetchLogsAjax', ['attendance logs', 'reports', 'superadmin']);

        return $router;
    }
}
