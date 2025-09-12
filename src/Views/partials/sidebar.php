<?php
    namespace App\Views\partials;
    use App\Views\Students;

    $currentPage = $currentPage ?? ''; // galing sa Controller

    $role = $_SESSION['role'] ?? 'guest';

    // Match the role to folder name
    switch ($role) {
        case 'student':
            $roleFolder = 'Student';
            break;
        case 'librarian':
            $roleFolder = 'Librarian';
            break;
        case 'admin':
            $roleFolder = 'Admin';
            break;
        case 'superadmin':
            $roleFolder = 'SuperAdmin';
            break;
        default:
            $roleFolder = 'Guest';
            break;
    }

?>

<aside class="w-64 bg-orange-50 border-r border-orange-200 flex flex-col sticky top-0 h-screen">
    <!-- Logo -->
    <a href="/LibSys/public/<?= $roleFolder?>/dashboard"
        class="flex items-center gap-4 px-6 py-4 border-b border-orange-200 cursor-pointer">
        <img src="/LibSys/assets/library-icons/apple-touch-icon.png" alt="Logo" class="h-18">
        <span class="font-semibold text-lg text-orange-700">
            Library Online Software
        </span>
    </a>

    <!-- Student Sidebar -->
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <?php if ($role === 'student'): ?>
            <!-- Dashboard -->
            <a href="/libsys/public/Student/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'dashboard')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span class="text-base">Dashboard</span>
            </a>

            <!-- Book Catalog -->
            <a href="/libsys/public/Student/bookCatalog" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'bookCatalog')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book text-2xl"></i>
                <span class="text-base">Book Catalog</span>
            </a>

            <!-- Equipment Catalog -->
            <a href="/libsys/public/Student/equipmentCatalog" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'equipmentCatalog')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop-tower text-2xl"></i>
                <span class="text-base">Equipment Catalog</span>
            </a>

            <!-- My Cart -->
            <a href="/libsys/public/Student/myCart" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myCart')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-shopping-cart-simple text-2xl"></i>
                <span class="text-base">My Cart</span>
            </a>

            <!-- QR Borrow Ticket -->
            <a href="/libsys/public/Student/qrBorrowingTicket" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'qrBorrowingTicket')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-qr-code text-2xl"></i>
                <span class="text-base">QR Borrow Ticket</span>
            </a>

            <!-- My Attendance -->
            <a href="/libsys/public/Student/myAttendance" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'myAttendance')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-user-check text-2xl"></i>
                <span class="text-base">My Attendance</span>
            </a>

            <!-- My Borrowing History -->
            <a href="/libsys/public/Student/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
           <?= ($currentPage === 'borrowingHistory')
               ? 'bg-green-600 text-white font-medium'
               : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span class="text-base">My Borrowing History</span>
            </a>
        </nav>

        <!-- Super Admin Sidebar  -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
        <?php elseif ($role === 'superadmin'): ?>
            <!-- Dashboard -->
            <a href="/LibSys/public/SuperAdmin/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'dashboard'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- User Management -->
            <a href="/LibSys/public/SuperAdmin/userManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'userManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-users-three text-2xl"></i>
                <span>User Management</span>
            </a>

            <!-- Features -->
            <a href="/LibSys/public/SuperAdmin/features" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'features'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-gear-six text-2xl"></i>
                <span>Features</span>
            </a>

            <!-- Book Management -->
            <a href="/LibSys/public/SuperAdmin/bookManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'bookManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span>Book Management</span>
            </a>

            <!-- Equipment Management -->
            <a href="/LibSys/public/SuperAdmin/equipmentManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'equipmentManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop text-2xl"></i>
                <span>Equipment Management</span>
            </a>

            <!-- Attendance Logs -->
            <a href="/LibSys/public/SuperAdmin/attendanceLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'attendanceLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clipboard-text text-2xl"></i>
                <span>Attendance Logs</span>
            </a>

            <!-- Borrowing History -->
            <a href="/LibSys/public/SuperAdmin/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'borrowingHistory'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span>Borrowing History</span>
            </a>

            <!-- Overdue Alert -->
            <a href="/LibSys/public/SuperAdmin/overdueAlert" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'overdueAlert'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-bell-ringing text-2xl"></i>
                <span>Overdue Alert</span>
            </a>

            <!-- Global Logs -->
            <a href="/LibSys/public/SuperAdmin/globalLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'globalLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-database text-2xl"></i>
                <span>Global Logs</span>
            </a>

            <!-- Backup and Restore -->
            <a href="/LibSys/public/SuperAdmin/backupAndRestore" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'backupAndRestore'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-cloud-arrow-up text-2xl"></i>
                <span>Backup & Restore</span>
            </a>
        </nav>

        <!--Admin Sidebar  -->
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
        <?php elseif ($role === 'admin'): ?>
            <!-- Dashboard -->
            <a href="/LibSys/public/Admin/dashboard" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'dashboard'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-house text-2xl"></i>
                <span>Dashboard</span>
            </a>

            <!-- User Management -->
            <a href="/LibSys/public/Admin/userManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'userManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-users-three text-2xl"></i>
                <span>User Management</span>
            </a>

            <!-- Features -->
            <a href="/LibSys/public/Admin/features" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'features'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-gear-six text-2xl"></i>
                <span>Features</span>
            </a>

            <!-- Book Management -->
            <a href="/LibSys/public/Admin/bookManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'bookManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-book-open text-2xl"></i>
                <span>Book Management</span>
            </a>

            <!-- Equipment Management -->
            <a href="/LibSys/public/Admin/equipmentManagement" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'equipmentManagement'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-desktop text-2xl"></i>
                <span>Equipment Management</span>
            </a>

            <!-- Attendance Logs -->
            <a href="/LibSys/public/Admin/attendanceLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'attendanceLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clipboard-text text-2xl"></i>
                <span>Attendance Logs</span>
            </a>

            <!-- Borrowing History -->
            <a href="/LibSys/public/Admin/borrowingHistory" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'borrowingHistory'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                <span>Borrowing History</span>
            </a>

            <!-- Overdue Alert -->
            <a href="/LibSys/public/Admin/overdueAlert" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'overdueAlert'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-bell-ringing text-2xl"></i>
                <span>Overdue Alert</span>
            </a>

            <!-- Global Logs -->
            <a href="/LibSys/public/Admin/globalLogs" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'globalLogs'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-database text-2xl"></i>
                <span>Global Logs</span>
            </a>

            <!-- Backup and Restore -->
            <a href="/LibSys/public/Admin/backupAndRestore" class="flex items-center gap-x-3 px-3 py-2 rounded-lg transition 
                <?= $currentPage === 'backupAndRestore'
                    ? 'bg-green-600 text-white font-medium'
                    : 'hover:bg-orange-100 text-orange-900' ?>">
                <i class="ph ph-cloud-arrow-up text-2xl"></i>
                <span>Backup & Restore</span>
            </a>
        </nav>
    </aside>


<?php endif; ?>
</aside>