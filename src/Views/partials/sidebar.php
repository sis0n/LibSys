<?php

namespace App\Views\partials;

use App\Views\Students;

$currentPage = $currentPage ?? ''; // galing sa Controller

$role = $_SESSION['role'] ?? 'guest';

// Match the role to folder name
switch ($role) {
    case 'student':
        $roleFolder = 'student';
        break;
    case 'librarian':
        $roleFolder = 'librarian';
        break;
    case 'admin':
        $roleFolder = 'admin';
        break;
    case 'superadmin':
        $roleFolder = 'superadmin';
        break;
    default:
        $roleFolder = 'Guest';
        break;
}

?>

<aside id="sidebar" class="fixed lg:sticky lg:top-0 left-0 top-0 h-screen w-64 
       bg-orange-50 border-r border-orange-200 flex flex-col 
       transform -translate-x-full lg:translate-x-0 
       transition-transform duration-300 ease-in-out 
       z-40 overflow-hidden hover:overflow-y-auto">


    <!-- Logo -->
    <a href="/LibSys/public/<?= $roleFolder ?>/dashboard"
        class="flex items-center gap-4 px-6 py-4 border-b border-orange-200 cursor-pointer">
        <img src="/LibSys/assets/library-icons/apple-touch-icon.png" alt="Logo" class="h-18">
        <span class="font-semibold text-lg text-orange-700">
            Library Online Software
        </span>
    </a>
    <div class="flex-1 space-y-2 overflow-hidden hover:overflow-y-auto scroll-smooth
            [scrollbar-width:none] 
            [&::-webkit-scrollbar]:w-0 
            hover:[scrollbar-width:thin] 
            hover:[scrollbar-color:#d4d4d4_transparent] 
            hover:[&::-webkit-scrollbar]:w-2 
            hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 
            hover:[&::-webkit-scrollbar-thumb]:rounded">


        <!-- Student Sidebar -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php if ($role === 'student'): ?>
            <!-- Dashboard -->
            <a href="/libsys/public/student/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'dashboard')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>

            <!-- Book Catalog -->
            <a href="/libsys/public/student/bookCatalog" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'bookCatalog')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Book Catalog</span>
            </a>

            <!-- Equipment Catalog -->
            <a href="/libsys/public/student/equipmentCatalog" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'equipmentCatalog')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop-tower text-2xl"></i>
                <span class="text-base">Equipment Catalog</span>
            </a>

            <!-- My Cart -->
            <a href="/libsys/public/student/myCart" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myCart')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-shopping-cart-simple text-2xl"></i>
                <span class="text-base">My Cart</span>
            </a>

            <!-- QR Borrow Ticket -->
            <a href="/libsys/public/student/qrBorrowingTicket" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'qrBorrowingTicket')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span class="text-base">QR Borrow Ticket</span>
            </a>

            <!-- My Attendance -->
            <a href="/libsys/public/student/myAttendance" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myAttendance')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-check text-2xl"></i>
                <span class="text-base">My Attendance</span>
            </a>

            <!-- My Borrowing History -->
            <a href="/libsys/public/student/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'borrowingHistory')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span class="text-base">My Borrowing History</span>
            </a>


            <!-- Change Password -->
            <a href="/libsys/public/student/changePassword" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'changePassword')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>

            <!-- My Profile -->
            <a href="/libsys/public/student/myProfile" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myProfile')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-gear text-2xl"></i>
                <span class="text-base">My Profile</span>
            </a>
        </nav>

        <!-- Super Admin Sidebar  -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php elseif ($role === 'superadmin'): ?>
            <!-- Dashboard -->
            <a href="/LibSys/public/superadmin/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'dashboard'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- User Management -->
            <a href="/LibSys/public/superadmin/userManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'userManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-users-three text-2xl"></i>
                <span>User Management</span>
            </a>

            <!-- Book Management -->
            <a href="/LibSys/public/superadmin/bookManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'bookManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span>Book Management</span>
            </a>

            <!-- Equipment Management -->
            <a href="/LibSys/public/superadmin/equipmentManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'equipmentManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop text-2xl"></i>
                <span>Equipment Management</span>
            </a>

            <!-- QR Code Scanner -->
            <a href="/LibSys/public/superadmin/qrScanner" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'qrScanner'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span>QR Scanner</span>
            </a>

            <!-- Attendance Logs -->
            <a href="/LibSys/public/superadmin/attendanceLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'attendanceLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clipboard-text text-2xl"></i>
                <span>Attendance Logs</span>
            </a>

            <!-- Top Visitors -->
            <a href="/LibSys/public/superadmin/topVisitor" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'topVisitor'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-chart-bar text-2xl"></i>
                <span>Top Visitors</span>
            </a>

            <!-- Borrowing History -->
            <a href="/LibSys/public/superadmin/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'borrowingHistory'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span>Borrowing History</span>
            </a>

            <!-- Overdue Alert -->
            <a href="/LibSys/public/superadmin/returning" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'returning'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
               <i class="ph ph-swap text-2xl"></i>
                <span>Returning</span>
            </a>

            <!-- Global Logs -->
            <a href="/LibSys/public/superadmin/globalLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'globalLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-database text-2xl"></i>
                <span>Global Logs</span>
            </a>

            <!-- Backup and Restore -->
            <a href="/LibSys/public/superadmin/backupAndRestore" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'backupAndRestore'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-cloud-arrow-up text-2xl"></i>
                <span>Backup & Restore</span>
            </a>

            <!-- Change Password -->
            <a href="/libsys/public/superadmin/changePassword" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'changePassword')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>

             <!-- My Profile -->
            <a href="/libsys/public/superadmin/myProfile" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myProfile')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-gear text-2xl"></i>
                <span class="text-base">My Profile</span>
            </a>
        </nav>

        <!--Admin Sidebar  -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php elseif ($role === 'admin'): ?>
            <!-- Dashboard -->
            <a href="/LibSys/public/admin/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'dashboard'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- Book Management -->
            <a href="/LibSys/public/admin/bookManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'bookManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span>Book Management</span>
            </a>

            <!-- Equipment Management -->
            <a href="/LibSys/public/admin/equipmentManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'equipmentManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop text-2xl"></i>
                <span>Equipment Management</span>
            </a>

            <!-- QR Code Scanner -->
            <a href="/LibSys/public/admin/qrScanner" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'qrScanner'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span>QR Scanner</span>
            </a>

            <!-- Attendance Logs -->
            <a href="/LibSys/public/admin/attendanceLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'attendanceLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clipboard-text text-2xl"></i>
                <span>Attendance Logs</span>
            </a>

            <!-- Top Visitors -->
            <a href="/LibSys/public/admin/topVisitor" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'topVisitor'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-chart-bar text-2xl"></i>
                <span>Top Visitors</span>
            </a>

            <!-- Borrowing History -->
            <a href="/LibSys/public/admin/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'borrowingHistory'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span>Borrowing History</span>
            </a>

            <!-- Overdue Alert -->
            <a href="/LibSys/public/admin/returning" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'returning'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-swap text-2xl"></i>
                <span>Returning</span>
            </a>

            <!-- Global Logs -->
            <a href="/LibSys/public/admin/globalLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'globalLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-database text-2xl"></i>
                <span>Global Logs</span>
            </a>

            <!-- Backup and Restore -->
            <a href="/LibSys/public/admin/backupAndRestore" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'backupAndRestore'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-cloud-arrow-up text-2xl"></i>
                <span>Backup & Restore</span>
            </a>

            <!-- Change Password -->
            <a href="/libsys/public/admin/changePassword" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'changePassword')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>

             <!-- My Profile -->
            <a href="/libsys/public/admin/myProfile" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myProfile')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-gear text-2xl"></i>
                <span class="text-base">My Profile</span>
            </a>
        </nav>

        <!--Librarian Sidebar  -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php elseif ($role === 'librarian'): ?>
            <!-- Dashboard -->
            <a href="/LibSys/public/librarian/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'dashboard'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- Book Management -->
            <a href="/LibSys/public/librarian/bookManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'bookManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span>Book Management</span>
            </a>

            <!-- Equipment Management -->
            <a href="/LibSys/public/librarian/equipmentManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'equipmentManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop text-2xl"></i>
                <span>Equipment Management</span>
            </a>

            <!-- QR Code Scanner -->
            <a href="/LibSys/public/librarian/qrScanner" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'qrScanner'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span>QR Scanner</span>
            </a>

            <!-- Attendance Logs -->
            <a href="/LibSys/public/librarian/attendanceLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'attendanceLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clipboard-text text-2xl"></i>
                <span>Attendance Logs</span>
            </a>

            <!-- Top Visitors -->
            <a href="/LibSys/public/librarian/topVisitor" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'topVisitor'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-chart-bar text-2xl"></i>
                <span>Top Visitors</span>
            </a>

            <!-- Borrowing History -->
            <a href="/LibSys/public/librarian/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'borrowingHistory'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span>Borrowing History</span>
            </a>

            <!-- Overdue Alert -->
            <a href="/LibSys/public/librarian/overdueAlert" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'overdueAlert'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-bell-ringing text-2xl"></i>
                <span>Overdue Alert</span>
            </a>

            <!-- Global Logs -->
            <a href="/LibSys/public/librarian/globalLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'globalLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-database text-2xl"></i>
                <span>Global Logs</span>
            </a>

            <!-- Backup and Restore -->
            <a href="/LibSys/public/librarian/backupAndRestore" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'backupAndRestore'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-cloud-arrow-up text-2xl"></i>
                <span>Backup & Restore</span>
            </a>

            <!-- Change Password -->
            <a href="/libsys/public/librarian/changePassword" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'changePassword')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>

             <!-- My Profile -->
            <a href="/libsys/public/librarian/myProfile" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myProfile')
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-gear text-2xl"></i>
                <span class="text-base">My Profile</span>
            </a>
        </nav>
    </div>
</aside>
<?php endif; ?>
</aside>