<?php
namespace App\Controllers;

class DashboardController{
    public function admin()
    {
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/dashboard/admin.php';
    }
    
    public function librarian()
    {
        include __DIR__ . '/../Views/partials/header.php';
        include __DIR__ . '/../Views/dashboard/librarian.php';
    }

    public function student()
    {
        include __DIR__ . '/../Views/partials/header.php';
        include __DIR__ . '/../Views/dashboard/student.php';
    }

    public function superadmin()
    {
        include __DIR__ . '/../Views/partials/header.php';
        include __DIR__ . '/../Views/dashboard/superadmin.php';
    }
}
