<?php

namespace App\Views\partials;

$currentPage = $currentPage ?? '';
$role = $_SESSION['role'] ?? 'guest';

$userPermissions = $_SESSION['user_permissions'] ?? [];
$isSuperAdmin = $role === 'superadmin';

$normalizedPermissions = array_map(function($p) {
    return trim(strtolower($p));
}, $userPermissions);

// var_dump($userPermissions, $normalizedPermissions);


$hasPermission = function ($code) use ($normalizedPermissions, $isSuperAdmin) {
    if ($isSuperAdmin) {
        return true;
    }
    return in_array(trim(strtolower($code)), $normalizedPermissions);
};

switch ($role) {
    case 'student':
        $roleFolder = 'student';
        break;
    case 'faculty':
        $roleFolder = 'faculty';
        break;
    case 'staff':
        $roleFolder = 'staff';
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


    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/dashboard"
        class="flex items-center gap-4 px-6 py-4 border-b border-orange-200 cursor-pointer">
        <img src="/LibSys/assets/library-icons/apple-touch-icon.png" alt="Logo" class="h-18">
        <span class="font-semibold text-lg text-orange-700">
            Library Online Software
        </span>
    </a>
    <div class="flex-1 space-y-1 overflow-hidden hover:overflow-y-auto scroll-smooth
            [scrollbar-width:none] 
            [&::-webkit-scrollbar]:w-0 
            hover:[scrollbar-width:thin] 
            hover:[scrollbar-color:#d4d4d4_transparent] 
            hover:[&::-webkit-scrollbar-thumb]:bg-gray-300 
            hover:[&::-webkit-scrollbar-thumb]:rounded">

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <?php if ($role === 'student'): ?>
            <!-- Student Sidebar (FULL MENU - NOT FILTERED) -->
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/dashboard"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'dashboard') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>
            <!-- Book Catalog -->
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/bookCatalog"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'bookCatalog') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Book Catalog</span>
            </a>

            <!-- Borrowings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myCart", "qrBorrowingTicket"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myCart", "qrBorrowingTicket"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-handbag text-2xl"></i>
                        <span class="text-base">Borrowings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myCart"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myCart') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <span class="text-base text-sm">Bookbag</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/qrBorrowingTicket"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'qrBorrowingTicket') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Borrowing Ticket</span>
                    </a>
                </div>
            </div>

            <!-- History Dropdown -->
            <div class="sidebar-dropdown" data-pages='["borrowingHistory", "myAttendance"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["borrowingHistory", "myAttendance"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                        <span class="text-base">History</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/borrowingHistory"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-bookmarks text-xl"></i>
                        <span class="text-base text-sm">Borrowing History</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myAttendance"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myAttendance') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-calendar-check text-xl"></i>
                        <span class="text-base text-sm">Attendance</span>
                    </a>
                </div>
            </div>

            <!-- Account Settings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myProfile", "changePassword"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myProfile", "changePassword"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-user-gear text-2xl"></i>
                        <span class="text-base">Account Settings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myProfile"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myProfile') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-circle text-xl"></i>
                        <span class="text-base text-sm">Profile</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/changePassword"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-key text-xl"></i>
                        <span class="text-base text-sm">Change Password</span>
                    </a>
                </div>
            </div>



            <?php elseif ($role === 'faculty'): ?>

            <!-- Faculty Sidebar (FULL MENU - NOT FILTERED) -->
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/dashboard"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'dashboard') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/bookCatalog"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'bookCatalog') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Book Catalog</span>
            </a>
            <!-- Borrowings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myCart", "qrBorrowingTicket"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myCart", "qrBorrowingTicket"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-handbag text-2xl"></i>
                        <span class="text-base">Borrowings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myCart"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myCart') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <span class="text-base text-sm">Bookbag</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/qrBorrowingTicket"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'qrBorrowingTicket') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Borrowing Ticket</span>
                    </a
                </div>
            </div>

            <!-- History Dropdown -->
            <div class="sidebar-dropdown" data-pages='["borrowingHistory"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                        <span class="text-base">History</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/borrowingHistory"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-bookmarks text-xl"></i>
                        <span class="text-base text-sm">Borrowing History</span>
                    </a>
                </div>
            </div>

            <!-- Account Settings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myProfile", "changePassword"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myProfile", "changePassword"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-user-gear text-2xl"></i>
                        <span class="text-base">Account Settings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myProfile"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myProfile') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-circle text-xl"></i>
                        <span class="text-base text-sm">Profile</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/changePassword"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-key text-xl"></i>
                        <span class="text-base text-sm">Change Password</span>
                    </a
                </div>
            </div>

            <?php elseif ($role === 'staff'): ?>

            <!-- Staff Sidebar (FULL MENU - NOT FILTERED) -->
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/dashboard"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'dashboard') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>

            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/bookCatalog"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'bookCatalog') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Book Catalog</span>
            </a>

            <!-- Borrowings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myCart", "qrBorrowingTicket"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myCart", "qrBorrowingTicket"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-handbag text-2xl"></i>
                        <span class="text-base">Borrowings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myCart"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myCart') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <span class="text-base text-sm">Bookbag</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/qrBorrowingTicket"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'qrBorrowingTicket') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Borrowing Ticket</span>
                    </a>
                </div>
            </div>

            <!-- History Dropdown -->
            <div class="sidebar-dropdown" data-pages='["borrowingHistory"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                        <span class="text-base">History</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/borrowingHistory"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'borrowingHistory') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-bookmarks text-xl"></i>
                        <span class="text-base text-sm">Borrowing History</span>
                    </a>
                </div>
            </div>

            <!-- Account Settings Dropdown -->
            <div class="sidebar-dropdown" data-pages='["myProfile", "changePassword"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["myProfile", "changePassword"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-user-gear text-2xl"></i>
                        <span class="text-base">Account Settings</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/myProfile"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'myProfile') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-circle text-xl"></i>
                        <span class="text-base text-sm">Profile</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/changePassword"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-key text-xl"></i>
                        <span class="text-base text-sm">Change Password</span>
                    </a
                </div>
            </div>

            <?php elseif ($role === 'superadmin'): ?>
            <!-- Super Admin Sidebar (FULL MENU - NOT FILTERED) -->
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/dashboard"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'dashboard' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- Library Management Dropdown -->
            <div class="sidebar-dropdown" data-pages='["userManagement","bookManagement"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["userManagement", "bookManagement"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-folders text-2xl"></i>
                        <span class="text-base">Management</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/userManagement"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'userManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-users text-xl"></i>
                        <span class="text-base text-sm">User Management</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/bookManagement"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'bookManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book-open text-xl"></i>
                        <span class="text-base text-sm">Book Management</span>
                    </a
                </div>
            </div>

            <!-- QR & Returning Dropdown -->
            <div class="sidebar-dropdown" data-pages='["qrScanner", "returning", "borrowingForm"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["qrScanner", "returning", "borrowingForm"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-swap text-2xl"></i>
                        <span class="text-base">QR & Returning</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/qrScanner"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'qrScanner' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-qr-code text-xl"></i>
                        <span class="text-base text-sm">QR Scanner</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/returning"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'returning' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrow-counter-clockwise text-xl"></i>
                        <span class="text-base text-sm">Returning</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/borrowingForm"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'borrowingForm' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-receipt text-xl"></i>
                        <span class="text-base text-sm">Borrowing Form</span>
                    </a>
                </div>
            </div>

            <!-- Activity & Logs Dropdown -->
            <div class="sidebar-dropdown" data-pages='["attendanceLogs", "topVisitor", "transactionHistory"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["attendanceLogs", "topVisitor", "transactionHistory"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-activity text-2xl"></i>
                        <span class="text-base">Activity & Logs</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden ml-4">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/attendanceLogs"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'attendanceLogs' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-calendar-check text-xl"></i>
                        <span class="text-base text-sm">Attendance Logs</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/topVisitor"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'topVisitor' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-chart-bar text-xl"></i>
                        <span class="text-base text-sm">Reports</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/transactionHistory"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'transactionHistory' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-arrows-left-right text-xl"></i>
                        <span class="text-base text-sm">Transaction History</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-dropdown" data-pages='["backup", "restoreBooks", "restoreUser"]'>
                <button
                    class="sidebar-dropdown-toggle flex items-center justify-between w-full gap-x-3 px-3 py-2 rounded-lg transition <?= (in_array($currentPage, ["backup", "restoreBooks", "restoreUser"])) ? 'bg-orange-100 text-orange-900' : 'hover:bg-orange-100 text-orange-900' ?>">
                    <span class="flex items-center gap-x-3">
                        <i class="ph ph-database text-2xl"></i>
                        <span class="text-base">Backup & Restore</span>
                    </span>
                    <i class="ph ph-caret-down text-xl dropdown-icon transition-transform"></i>
                </button>
                <div class="pl-5 pt-1 space-y-1 hidden">
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/backup"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'backup' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-floppy-disk text-xl"></i>
                        <span class="text-base text-sm">Backup</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/restoreBooks"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreBooks' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-book text-xl"></i>
                        <span class="text-base text-sm">Restore Books</span>
                    </a>
                    <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/restoreUser"
                        class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreUser' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                        <i class="ph ph-user-gear text-xl"></i>
                        <span class="text-base text-sm">Restore User</span>
                    </a
                </div>
            </div>

            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/changePassword"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>


            <?php elseif ($role === 'admin' || $role === 'librarian'): ?>
            
            <?php if ($hasPermission('book management')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/bookManagement"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'bookManagement' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span class="text-base">Book Management</span>
            </a>
            <?php endif; ?>

            <?php if ($hasPermission('qr scanner')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/qrScanner"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'qrScanner' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span class="text-base">QR Scanner</span>
            </a>
            <?php endif; ?>
            
            <?php if ($hasPermission('returning')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/returning"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'returning' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-arrow-counter-clockwise text-2xl"></i>
                <span class="text-base">Returning</span>
            </a>
            <?php endif; ?>

            <?php if ($hasPermission('borrowing form')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/borrowingForm"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'borrowingForm' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-receipt text-2xl"></i>
                <span class="text-base">Borrowing Form</span>
            </a>
            <?php endif; ?>

            <?php if ($hasPermission('attendance logs')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/attendanceLogs"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'attendanceLogs' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-calendar-check text-2xl"></i>
                <span class="text-base">Attendance Logs</span>
            </a>
            <?php endif; ?>
            
            <?php if ($hasPermission('reports')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/topVisitor"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'topVisitor' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-chart-bar text-2xl"></i>
                <span class="text-base">Reports</span>
            </a>
            <?php endif; ?>

            <?php if ($hasPermission('transaction history')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/transactionHistory"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'transactionHistory' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-arrows-left-right text-2xl"></i>
                <span class="text-base">Transaction History</span>
            </a>
            <?php endif; ?>

            <?php if ($hasPermission('restore books')): ?>
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/restoreBooks"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= $currentPage === 'restoreBooks' ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Restore Books</span>
            </a>
            <?php endif; ?>
            
            <a href="<?= BASE_URL ?>/<?= $roleFolder ?>/changePassword"
                class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition <?= ($currentPage === 'changePassword') ? 'bg-green-600 text-white font-medium' : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-key text-2xl"></i>
                <span class="text-base">Change Password</span>
            </a>

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

            const isManagedRole = ['admin', 'librarian'].includes('<?= $role ?>');
            

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
