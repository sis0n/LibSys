<?php

use App\Repositories\AttendanceRepository;

$attendanceRepo = new AttendanceRepository();
$logs = $attendanceRepo->getAllLogs();

date_default_timezone_set('Asia/Manila');

$formattedLogs = [];
foreach ($logs as $log) {
    $logTime = new DateTime($log['timestamp']);
    $formattedLogs[] = [
        'date' => $logTime->format("Y-m-d"),
        'day' => $logTime->format("l"),
        'studentName' => $log['full_name'],
        'studentNumber' => $log['student_number'],
        'time' => $logTime->format("H:i:s"),
        'status' => "Present"
    ];
}
?>

<body class="min-h-screen p-6 bg-gray-50">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-800 mb-4">
                Attendance Logs
            </h2>
            <p class="text-gray-700 text-md">
                Monitor student visits and library usage patterns.
            </p>
        </div>
        <button
            class="px-4 py-2 text-sm font-medium border rounded-lg shadow-sm bg-[var(--color-card)] border-[var(--color-border)] hover:bg-orange-100 transition">
            <i class="ph ph-download-simple mr-2"></i>
            Export Logs
        </button>
    </div>

    <div class="bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-sm mb-6">
        <div class="p-4 border-b border-[var(--color-border)]">
            <h3 class="text-md font-medium text-gray-800">Total Visitors</h3>
            <p class="text-sm text-gray-500">View visitor statistics by time period</p>
        </div>
        <div class="p-4">
            <div
                class="flex items-center border border-orange-100 bg-orange-50/50 p-1 justify-center mb-4 rounded-full">
                <button
                    class="flex-1 py-2 text-sm rounded-full text-gray-600 hover:text-orange-700 font-medium period-btn"
                    data-period="Week" data-count="0" data-active="true"> Week
                </button>
                <button class="flex-1 py-2 text-sm rounded-full text-gray-600 hover:text-orange-700 period-btn"
                    data-period="Month" data-count="0">
                    Month
                </button>
                <button class="flex-1 py-2 text-sm rounded-full text-gray-600 hover:text-orange-700 period-btn"
                    data-period="Year" data-count="0">
                    Year
                </button>
            </div>

            <div class="flex flex-col items-center justify-center p-6 rounded-lg border border-[var(--color-border)]">
                <span id="visitor-label" class="text-sm flex items-center gap-1 text-[var(--color-primary)]">
                    This Week
                </span>
                <span id="visitor-count" class="text-3xl font-bold mt-2">0</span>
                <span class="text-xs text-[var(--color-gray-500)]">Total visitors</span>
            </div>
        </div>

    </div>

    <div class="bg-[var(--color-card)] border border-orange-200 rounded-xl shadow-sm p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Attendance Records</h3>
                <p class="text-sm text-gray-600">Browse student check-in history</p>
            </div>

            <div class="flex items-center gap-2 text-sm">

                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="attendanceSearchInput" placeholder="Search by student..."
                        class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-48 focus:ring-1 focus:ring-orange-400">
                </div>

                <div class="relative">
                    <input type="date" id="datePickerInput"
                        class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-700 w-36 focus:ring-1 focus:ring-orange-400">
                </div>

                <div class="relative inline-block text-left">
                    <button id="courseFilterBtn"
                        class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                        <span id="courseFilterValue">All Courses</span>
                        <i class="ph ph-caret-down text-xs"></i>
                    </button>
                    <div id="courseFilterMenu"
                        class="filter-dropdown-menu absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20 text-sm">
                        <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                            data-value="All Courses">All Courses</div>
                        <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSIT">BSIT
                        </div>
                        <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSCS">BSCS
                        </div>
                        <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSEMC">BSEMC
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-orange-200">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-orange-50 text-gray-700 border border-orange-100">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Student</th>
                        <th class="text-left px-4 py-3 font-medium">Date</th>
                        <th class="text-left px-4 py-3 font-medium">First Check-in</th>
                        <th class="text-left px-4 py-3 font-medium">Total Check-ins</th>
                        <th class="text-left px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody" class="divide-y divide-orange-100">
                    <tr id="noRecordsRow" class="bg-white">
                        <td colspan="5" class="text-center text-gray-500 py-10">
                            <i class="ph ph-clipboard text-4xl block mb-2"></i>
                            Loading records...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div id="pagination-controls" class="hidden mt-6">
            <div class="flex justify-center">
                <nav class="flex items-center bg-white rounded-full shadow-sm p-2 text-sm font-medium text-gray-600">
                    <button class="flex items-center gap-1 px-4 py-2 hover:text-orange-600 disabled:text-gray-400">
                        <i class="ph ph-caret-left text-base"></i>
                        <span>Previous</span>
                    </button>
                    <div class="flex items-center gap-2 mx-2">
                        <button
                            class="flex items-center justify-center h-8 w-8 rounded-full bg-orange-500 text-white font-bold text-xs">1</button>
                        <button
                            class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-orange-50 hover:text-orange-600 text-xs">2</button>
                        <button
                            class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-orange-50 hover:text-orange-600 text-xs">3</button>
                        <span class="px-2 text-gray-500">...</span>
                        <button
                            class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-orange-50 hover:text-orange-600 text-xs">155</button>
                    </div>
                    <button class="flex items-center gap-1 px-4 py-2 hover:text-orange-600 disabled:text-gray-400">
                        <span>Next</span>
                        <i class="ph ph-caret-right text-base"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>



    <div id="viewCheckinsModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div
            class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-sm p-4 animate-fadeIn">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 id="checkinsModalTitle" class="text-lg font-semibold text-gray-800">Viewing Check-ins</h2>
                    <p id="checkinsModalSubtitle" class="text-sm text-gray-600 mt-1">Student / Date</p>
                </div>
                <button id="closeCheckinsModal" class="text-gray-500 hover:text-red-700 transition">
                    <i class="ph ph-x text-2xl"></i> </button>
            </div>

            <div class="max-h-60 overflow-y-auto space-y-2" id="checkinsList">
            </div>

            <div class="text-right mt-4">
                <button id="closeCheckinsModalBtn"
                    class="mt-2 border border-gray-300 px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="/libsys/public/js/superadmin/attendanceLogs.js" defer></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initializeAttendanceLogs === 'function') {
            initializeAttendanceLogs();
        } else {
            console.error(
                "AttendanceLogs Error: Initialization function not found. Check if attendanceLogs.js loaded correctly."
                );
            const tableBody = document.getElementById("attendanceTableBody");
            if (tableBody) {
                const errorRow = tableBody.querySelector('#noRecordsRow') || tableBody.insertRow();
                errorRow.innerHTML =
                    `<td colspan="5" class="text-center text-red-600 py-10">Error initializing page. Please refresh.</td>`;
                errorRow.classList.remove('hidden');
            }
        }
    });
    </script>
</body>