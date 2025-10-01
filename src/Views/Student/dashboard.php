<?php
use App\Repositories\AttendanceRepository;

$attendanceRepo = new AttendanceRepository();
$userId = $_SESSION['user_id'];

// get all attendance logs for user
$allLogs = $attendanceRepo->getByUserId($userId);

// count days visited for current month
date_default_timezone_set('Asia/Manila');
$firstOfMonth = new DateTime('first day of this month 00:00:00');
$lastOfMonth = new DateTime('last day of this month 23:59:59');

$daysVisitedThisMonth = 0;
$visitedDates = []; // para hindi double count

foreach ($allLogs as $log) {
    $logDate = (new DateTime($log['timestamp']))->format('Y-m-d');

    if (!in_array($logDate, $visitedDates)) {
        $logDT = new DateTime($log['timestamp']);
        if ($logDT >= $firstOfMonth && $logDT <= $lastOfMonth) {
            $daysVisitedThisMonth++;
            $visitedDates[] = $logDate;
        }
    }
}
?>

<body class="min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
        <div class="text-gray-700">Here's your library overview for today.</div>
    </div>

    <!-- Top Stats -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Books Borrowed -->
        <div
            class="relative bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] p-4 overflow-hidden">
            <div class="absolute top-0 left-0 h-full w-1 bg-[var(--color-orange-500)]"></div>
            <div class="absolute top-3 right-3 text-xl text-[var(--color-orange-500)]"><i class="ph ph-books"></i></div>
            <h3 class="text-sm text-gray-600">Books Borrowed</h3>
            <p class="text-3xl font-bold mt-2">0</p>
            <span class="text-sm text-gray-500">Currently borrowed</span>
        </div>

        <!-- Days Visited -->
        <div
            class="relative bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] p-4 overflow-hidden">
            <div class="absolute top-0 left-0 h-full w-1 bg-[var(--color-green-500)]"></div>
            <div class="absolute top-3 right-3 text-xl text-[var(--color-green-500)]"><i
                    class="ph ph-calendar-check"></i></div>
            <h3 class="text-sm text-gray-600">Days Visited</h3>
            <p class="text-3xl font-bold mt-2"><?php echo $daysVisitedThisMonth?></p>
            <span class="text-sm text-gray-500">This month</span>
        </div>

        <!-- Overdue Books -->
        <div
            class="relative bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] p-4 overflow-hidden">
            <div class="absolute top-0 left-0 h-full w-1 bg-[var(--color-destructive)]"></div>
            <div class="absolute top-3 right-3 text-xl text-[var(--color-destructive)]"><i class="ph ph-warning"></i>
            </div>
            <h3 class="text-sm text-gray-600">Overdue Books</h3>
            <p class="text-3xl font-bold mt-2 text-[var(--color-destructive)]">0</p>
            <span class="text-sm text-gray-500">Need attention</span>
        </div>
    </section>

    <!-- Bottom Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Currently Borrowed Books -->
        <div
            class="bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] p-4 border-t-4 border-t-[var(--color-orange-500)]">
            <h4 class="text-lg font-semibold mb-2">Currently Borrowed</h4>
            <p class="text-sm text-gray-600 mb-4">Books you need to return</p>

            <!-- Book Item -->
            <div
                class="bg-[var(--color-orange-50)] border border-[var(--color-border)] rounded-md p-3 mb-3 flex justify-between items-center">
                <div>
                    <p class="font-medium">Introduction to Computer Science</p>
                    <p class="text-sm text-gray-600">by John Smith</p>
                    <p class="text-xs text-gray-500">Due: 2025-08-15</p>
                </div>
                <span class="bg-[var(--color-orange-500)] text-white px-3 py-1 text-xs rounded-full">Borrowed</span>
            </div>

            <div
                class="bg-[var(--color-orange-50)] border border-[var(--color-border)] rounded-md p-3 mb-3 flex justify-between items-center">
                <div>
                    <p class="font-medium">Data Structures and Algorithms</p>
                    <p class="text-sm text-gray-600">by Jane Doe</p>
                    <p class="text-xs text-gray-500">Due: 2025-08-10</p>
                </div>
                <span class="bg-[var(--color-destructive)] text-white px-3 py-1 text-xs rounded-full">Overdue</span>
            </div>

            <div
                class="bg-[var(--color-orange-50)] border border-[var(--color-border)] rounded-md p-3 flex justify-between items-center">
                <div>
                    <p class="font-medium">Database Management Systems</p>
                    <p class="text-sm text-gray-600">by Mike Johnson</p>
                    <p class="text-xs text-gray-500">Due: 2025-08-20</p>
                </div>
                <span class="bg-[var(--color-orange-500)] text-white px-3 py-1 text-xs rounded-full">Borrowed</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div
            class="bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] p-4 border-t-4 border-t-[var(--color-green-500)]">
            <h4 class="text-lg font-semibold mb-2">Quick Actions</h4>
            <p class="text-sm text-gray-600 mb-4">Common tasks</p>

            <div class="space-y-3">
                <a href="/libsys/public/student/bookCatalog"
                    class="flex items-start gap-3 bg-[var(--color-orange-50)] border border-[var(--color-border)] rounded-md p-3 hover:bg-[var(--color-orange-100)] transition">
                    <i class="ph ph-magnifying-glass text-lg mt-0.5"></i>
                    <span>
                        <span class="block font-medium">Search Books</span>
                        <span class="block text-xs text-gray-500">Find books in our catalog</span>
                    </span>
                </a>

                <a href="/libsys/public/student/qrBorrowingTicket"
                    class="flex items-start gap-3 bg-[var(--color-green-50)] border border-[var(--color-border)] rounded-md p-3 hover:bg-[var(--color-green-100)] transition">
                    <i class="ph ph-qr-code text-lg mt-0.5"></i>
                    <span>
                        <span class="block font-medium">Generate QR Ticket</span>
                        <span class="block text-xs text-gray-500">For borrowing books</span>
                    </span>
                </a>

                <a href="/libsys/public/student/borrowingHistory"
                    class="flex items-start gap-3 bg-[var(--color-amber-50)] border border-[var(--color-border)] rounded-md p-3 hover:bg-[var(--color-amber-100)] transition">
                    <i class="ph ph-clock-counter-clockwise text-lg mt-0.5"></i>
                    <span>
                        <span class="block font-medium">View History</span>
                        <span class="block text-xs text-gray-500">Check your borrowing history</span>
                    </span>
                </a>

                <a href="/libsys/public/student/myAttendance"
                    class="flex items-start gap-3 bg-[var(--color-green-100)] border border-[var(--color-border)] rounded-md p-3 hover:bg-[var(--color-green-200)] transition">
                    <i class="ph ph-user-check text-lg mt-0.5"></i>
                    <span>
                        <span class="block font-medium">My Attedance</span>
                        <span class="block text-xs text-gray-500">Check your atttedance history</span>
                    </span>
                </a>
            </div>
        </div>

    </section>
</body>