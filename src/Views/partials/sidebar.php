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
    <a href="<?= base_url("$roleFolder/dashboard")?>"
        class="flex items-center gap-4 px-6 py-4 border-b border-orange-200 cursor-pointer">
        <img src="<?= base_url('assets/library-icons/apple-touch-icon.png') ?>" alt="Logo" class="h-18">
        <span class="font-semibold text-lg text-orange-700">
            Library Online Software
        </span>
    </a>
    <div class="flex-1 space-y-1 overflow-hidden hover:overflow-y-auto scroll-smooth
            [scrollbar-width:none] 
            [&::-webkit-scrollbar]:w-0 
            hover:[scrollbar-width:thin] 
            hover:[scrollbar-color:#d4d4d4_transparent] 
            hover:[&::-webkit-scrollbar]:w-2 
            hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 
            hover:[&::-webkit-scrollbar-thumb]:rounded">

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php if ($role === 'student'): ?>
            <!-- Student Sidebar -->
            <a href="<?= base_url("$roleFolder/dashboard")?>"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'dashboard') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>

            <!-- Library Catalog Dropdown -->
            <div class="sidebar-dropdown" data-pages='["bookCatalog", "equipmentCatalog"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-books text-2xl"></i>
                        <span class="text-base">Library Catalog</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= base_url("$roleFolder/bookCatalog")?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'bookCatalog') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book text-xl"></i>
                        <span class="text-base text-sm">Book Catalog</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/equipmentCatalog") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'equipmentCatalog') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-desktop-tower text-xl"></i>
                        <span class="text-base text-sm">Equipment Catalog</span>
                    </a>
                </div>
            </div>

            <!-- Borrowings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myCart", "qrBorrowingTicket"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-handbag text-2xl"></i>
                        <span class="text-base">Borrowings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= base_url("$roleFolder/myCart") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myCart') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <span class="text-base text-sm">Bookbag</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/qrBorrowingTicket") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'qrBorrowingTicket') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Borrowing Ticket</span>
                    </a>
                </div>
            </div>

            <!-- History Dropdown -->
            <div class="sidebar-dropdown" data-pages='["borrowingHistory", "myAttendance"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                        <span class="text-base">History</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= base_url("$roleFolder/borrowingHistory") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-bookmarks text-xl"></i>
                        <span class="text-base text-sm">Borrowing History</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/myAttendance") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myAttendance') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-calendar-check text-xl"></i>
                        <span class="text-base text-sm">Attendance</span>
                    </a>
                </div>
            </div>

            <!-- Account Settings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myProfile", "changePassword"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-user-gear text-2xl"></i>
                        <span class="text-base">Account Settings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= base_url("$roleFolder/myProfile") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myProfile') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-circle text-xl"></i>
                        <span class="text-base text-sm">Profile</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/changePassword") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-key text-xl"></i>
                        <span class="text-base text-sm">Change Password</span>
                    </a>
                </div>
            </div>

            <?php elseif ($role === 'superadmin'): ?>
            <!-- Super Admin Sidebar -->
            <a href="<?= base_url("$roleFolder/dashboard") ?>"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'dashboard' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- Library Management Dropdown -->
            <div class="sidebar-dropdown" data-pages='["userManagement", "bookManagement", "equipmentManagement"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-folders text-2xl"></i>
                        <span class="text-base">Management</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/userManagement") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'userManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-users text-xl"></i>
                        <span class="text-base text-sm">User Management</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/bookManagement") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'bookManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book-open text-xl"></i>
                        <span class="text-base text-sm">Book Management</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/equipmentManagement") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'equipmentManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-desktop-tower text-xl"></i>
                        <span class="text-base text-sm">Equipment Management</span>
                    </a>
                </div>
            </div>

            <!-- QR & Returning Dropdown -->
            <div class="sidebar-dropdown" data-pages='["qrScanner", "returning"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-swap text-2xl"></i>
                        <span class="text-base">QR & Returning</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/qrScanner") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'qrScanner' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Scanner</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/returning") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'returning' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrow-counter-clockwise text-xl"></i>
                        <span class="text-base text-sm">Returning</span>
                    </a>
                </div>
            </div>

            <!-- Activity & Logs Dropdown -->
            <div class="sidebar-dropdown" data-pages='["attendanceLogs", "topVisitor", "transactionHistory"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-activity text-2xl"></i>
                        <span class="text-base">Activity & Logs</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/attendanceLogs") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'attendanceLogs' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-calendar-check text-xl"></i>
                        <span class="text-base text-sm">Attendance Logs</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/topVisitor") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'topVisitor' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-chart-bar text-xl"></i>
                        <span class="text-base text-sm">Reports</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/transactionHistory") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'transactionHistory' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrows-left-right text-xl"></i>
                        <span class="text-base text-sm">Transaction History</span>
                    </a>
                </div>
            </div>

            <!-- Backup & Restore Dropdown -->
            <div class="sidebar-dropdown" data-pages='["backup", "restoreBooks", "restoreEquipment", "restoreUser"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-database text-2xl"></i>
                        <span class="text-base">Backup & Restore</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= base_url("$roleFolder/backup") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'backup' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-floppy-disk text-xl"></i>
                        <span class="text-base text-sm">Backup</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/restoreBooks") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreBooks' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book text-xl"></i>
                        <span class="text-base text-sm">Restore Books</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/restoreEquipment") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreEquipment' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-wrench text-xl"></i>
                        <span class="text-base text-sm">Restore Equipment</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/restoreUser") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreUser' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-gear text-xl"></i>
                        <span class="text-base text-sm">Restore User</span>
                    </a>
                </div>
            </div>

            <!-- Change Password -->
            <a href="<?= base_url("$roleFolder/changePassword") ?>"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>


            <?php elseif ($role === 'admin' || $role === 'librarian'): ?>
            <!-- Admin/Librarian Sidebar -->
            <a href="<?= base_url("$roleFolder/dashboard") ?>"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'dashboard' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- Library Management Dropdown -->
            <div class="sidebar-dropdown" data-pages='["bookManagement", "equipmentManagement"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-folders text-2xl"></i>
                        <span class="text-base">Management</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/bookManagement") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'bookManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book-open text-xl"></i>
                        <span class="text-base text-sm">Book Management</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/equipmentManagement") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'equipmentManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-desktop-tower text-xl"></i>
                        <span class="text-base text-sm">Equipment Management</span>
                    </a>
                </div>
            </div>

            <!-- QR & Returning Dropdown -->
            <div class="sidebar-dropdown" data-pages='["qrScanner", "returning"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-swap text-2xl"></i>
                        <span class="text-base">QR & Returning</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/qrScanner") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'qrScanner' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-sm text-base">QR Scanner</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/returning") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'returning' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrow-counter-clockwise text-xl"></i>
                        <span class="text-sm text-base">Returning</span>
                    </a>
                </div>
            </div>

            <!-- Activity & Logs Dropdown -->
            <div class="sidebar-dropdown" data-pages='["attendanceLogs", "topVisitor", "transactionHistory"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-activity text-2xl"></i>
                        <span class="text-base">Activity & Logs</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/attendanceLogs") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'attendanceLogs' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-calendar-check text-xl"></i>
                        <span class="text-base text-sm">Attendance Logs</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/topVisitor") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'topVisitor' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-chart-bar text-xl"></i>
                        <span class="text-base text-sm">Reports</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/transactionHistory") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'transactionHistory' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrows-left-right text-xl"></i>
                        <span class="text-base text-sm">Transaction History</span>
                    </a>
                </div>
            </div>

            <!-- Profile Settings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myProfile", "changePassword"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition hover:bg-orange-100 text-orange-900">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-user-gear text-2xl"></i>
                        <span class="text-base">Profile Settings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= base_url("$roleFolder/myProfile") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myProfile') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-circle text-xl"></i>
                        <span class="text-base text-sm">My Profile</span>
                    </a>
                    <a href="<?= base_url("$roleFolder/changePassword") ?>"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-key text-xl"></i>
                        <span class="text-base text-sm">Change Password</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </nav>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = '<?= $currentPage ?>';
        const dropdowns = document.querySelectorAll('.sidebar-dropdown');

        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.sidebar-dropdown-toggle');
            if (!toggle) return;

            const menu = toggle.nextElementSibling;
            if (!menu) return;

            const icon = toggle.querySelector('.dropdown-icon');
            const pages = JSON.parse(dropdown.dataset.pages || '[]');

            if (pages.includes(currentPage)) {
                menu.style.display = 'block';
                if (icon) icon.classList.add('rotate-180');
                toggle.classList.add('text-orange-900', 'font-semibold');
            }

            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const isVisible = menu.style.display === 'block';
                menu.style.display = isVisible ? 'none' : 'block';
                if (icon) icon.classList.toggle('rotate-180', !isVisible);
            });
        });
    });
    </script>
</aside>