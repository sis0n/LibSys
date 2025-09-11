<?php
namespace App\Config;

use App\Core\Router;

class RouteConfig {
    public static function register(): Router {
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
        $router->get('dashboard/superadmin', 'DashboardController@superadmin', ['superadmin']);
        $router->get('dashboard/admin', 'DashboardController@admin', ['admin', 'superadmin']);
        $router->get('dashboard/librarian', 'DashboardController@librarian', ['librarian', 'admin', 'superadmin']);
        $router->get('dashboard/student', 'DashboardController@student', ['student']);

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
