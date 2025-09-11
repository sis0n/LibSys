<?php
namespace App\Views\partials;
use App\Views\Students; 

?>

<aside class="w-64 bg-orange-50 border-r border-orange-200 flex flex-col sticky top-0 h-screen">
    <!-- Logo -->
    <div class="flex items-center gap-4 px-6 py-4 border-b border-orange-200">
       <img src="/LibSys/assets/library-icons/apple-touch-icon.png" alt="Logo" class="h-18">
        <span class="font-semibold text-lg text-orange-700">
            Library Online Software
        </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
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
</aside>