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


<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold flex items-center gap-2">
            Attendance Logs
        </h2>
        <p class="text-gray-700 text-md">
            Monitor student visits and library usage patterns.
        </p>
    </div>
    <button
        class="px-4 py-2 text-sm font-medium border rounded-lg shadow-sm bg-[var(--color-card)] border-[var(--color-border)] hover:bg-[var(--color-orange-600)] hover:text-white transition">
        <i class="ph ph-download-simple mr-2"></i>
        Export Logs
    </button>
</div>

<div class="bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-sm mb-6">
    <div class="p-4 border-b border-[var(--color-border)]">
        <h3 class="text-md font-medium">Total Visitors</h3>
        <p class="text-sm text-[var(--color-gray-500)]">View visitor statistics by time period</p>
    </div>
    <div class="p-4">
        <div
            class="flex items-center border border-[var(--color-border)]  bg-[color:var(--color-muted)] p-1 justify-center mb-4 rounded-full">
            <button
                class="flex-1 py-2 text-sm rounded-full bg-[var(--color-popover)] hover:bg-[var(--color-popover)] font-medium period-btn"
                data-period="Week" data-count="0">
                Week
            </button>
            <button class="flex-1 py-2 text-sm rounded-full hover:bg-[var(--color-popover)] period-btn"
                data-period="Month" data-count="0">
                Month
            </button>
            <button class="flex-1 py-2 text-sm rounded-full hover:bg-[var(--color-popover)] period-btn"
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

    <script>
        const buttons = document.querySelectorAll('.period-btn');
        const label = document.getElementById('visitor-label');
        const count = document.getElementById('visitor-count');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const period = btn.dataset.period;
                const visitorCount = btn.dataset.count;
                label.textContent = `This ${period}`;
                count.textContent = visitorCount;
                buttons.forEach(b => b.classList.remove('bg-[var(--color-popover)]', 'font-medium'));
                btn.classList.add('bg-[var(--color-popover)]', 'font-medium');
            });
        });
    </script>
</div>

<div class="bg-[var(--color-card)] border border-orange-200 rounded-xl shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Attendance Records</h3>
            <p class="text-sm text-gray-600">Browse student check-in history</p>
        </div>

        <div class="flex items-center gap-2 text-sm">

            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="attendanceSearchInput" placeholder="Search by student..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-48">
            </div>

            <div class="relative">
                <input type="date" id="datePickerInput"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-700 w-36">
            </div>

            <div class="relative inline-block text-left">
                <button id="courseFilterBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                    <span id="courseFilterValue">All Courses</span>
                    <i class="ph ph-caret-down text-xs"></i>
                </button>
                <div id="courseFilterMenu"
                    class="filter-dropdown-menu absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                    <div classclass="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="All Courses">All Courses</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSIT">BSIT</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSCS">BSCS</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="BSEMC">BSEMC</div>
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
                <tr id="noRecordsRow" class="bg-white hidden">
                    <td colspan="5" class="text-center text-gray-500 py-10">
                        <i class="ph ph-clipboard text-4xl block mb-2"></i>
                        No attendance records found.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="viewCheckinsModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div
        class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-sm p-4 animate-fadeIn">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 id="checkinsModalTitle" class="text-lg font-semibold">Viewing Check-ins</h2>
                <p id="checkinsModalSubtitle" class="text-sm text-gray-600 mt-1">Student / Date</p>
            </div>
            <button id="closeCheckinsModal" class="text-gray-500 hover:text-red-700 transition">
                <i class="ph ph-x text-2xl"></i> </button>
        </div>

        <div class="max-h-60 overflow-y-auto space-y-2" id="checkinsList">
        </div>

        <div class="text-right mt-4">
            <button id="closeCheckinsModalBtn"
                class="mt-2 border border-[var(--color-border)] px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition text-sm font-medium">
                Close
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("attendanceSearchInput");
        const dateInput = document.getElementById("datePickerInput");
        const courseBtn = document.getElementById("courseFilterBtn");
        const courseMenu = document.getElementById("courseFilterMenu");
        const courseValueSpan = document.getElementById("courseFilterValue");
        const tableBody = document.getElementById("attendanceTableBody");
        const noRecordsRow = document.getElementById("noRecordsRow");

        const checkinsModal = document.getElementById("viewCheckinsModal");
        const closeCheckinsBtn1 = document.getElementById("closeCheckinsModal");
        const closeCheckinsBtn2 = document.getElementById("closeCheckinsModalBtn");
        const checkinsModalTitle = document.getElementById("checkinsModalTitle");
        const checkinsModalSubtitle = document.getElementById("checkinsModalSubtitle");
        const checkinsList = document.getElementById("checkinsList");

        let currentSearch = "";
        let currentDate = "";
        let currentCourse = "All Courses";

        const timezone = "Asia/Manila";

        function getPhDate(date = new Date()) {
            return new Date(date.toLocaleString("en-US", {
                timeZone: timezone
            }));
        }

        function formatDate(date) {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        const todayDateObj = getPhDate();
        const yesterdayDateObj = getPhDate(new Date()); // Gumawa ng bagong instance para 'di magulo
        yesterdayDateObj.setDate(yesterdayDateObj.getDate() - 1);

        const todayStr = formatDate(todayDateObj);
        const yesterdayStr = formatDate(yesterdayDateObj);

        currentDate = todayStr;
        dateInput.value = currentDate;


        function setupDropdown(btn, menu, valueSpan, stateKey) {
            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                menu.classList.toggle("hidden");
            });

            menu.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    valueSpan.textContent = value;
                    menu.classList.add('hidden');

                    if (stateKey === 'course') currentCourse = value;

                    fetchLogs(); 
                });
            });
        }
        setupDropdown(courseBtn, courseMenu, courseValueSpan, 'course');

        document.addEventListener("click", () => {
            courseMenu.classList.add("hidden");
        });

        searchInput.addEventListener("input", () => {
            currentSearch = searchInput.value.trim();
            fetchLogs();
        });

        dateInput.addEventListener("change", () => {
            currentDate = dateInput.value; 
            fetchLogs();
        });

        function fetchLogs() {

            let periodToSend = 'All dates';
            let dateToFilter = currentDate; 

            if (currentDate === todayStr) {
                periodToSend = 'Today';
                dateToFilter = null; 
            } else if (currentDate === yesterdayStr) {
                periodToSend = 'Yesterday';
                dateToFilter = null; 
            }

            const url = new URL('/libsys/public/attendance/logs/ajax', window.location.origin);

            url.searchParams.append('period', periodToSend); 
            url.searchParams.append('search', currentSearch);
            if (currentCourse !== "All Courses") {
                url.searchParams.append('course', currentCourse);
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    let filteredData = data;
                    if (dateToFilter) {
                        filteredData = data.filter(log => log.date === dateToFilter);
                    }

                    tableBody.innerHTML = ''; 

                    if (filteredData.length === 0) {
                        tableBody.appendChild(noRecordsRow);
                        noRecordsRow.classList.remove('hidden');
                        return;
                    }

                    noRecordsRow.classList.add('hidden');

                    const groupedLogs = groupLogs(filteredData);

                    groupedLogs.forEach(log => {
                        const row = document.createElement('tr');
                        row.className = 'bg-white';
                        row.innerHTML = `
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">${log.studentName}</p>
                            <p class="text-gray-500 text-xs">${log.studentNumber}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-700">${log.date}</td>
                        <td class="px-4 py-3 text-gray-700">${log.firstCheckIn}</td>
                        <td class="px-4 py-3 text-gray-700">
                            <span class="bg-orange-100 text-orange-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                ${log.totalCheckIns}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="viewCheckinsBtn flex items-center gap-1 border border-orange-200 text-gray-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-orange-50 transition" 
                                data-student-name="${log.studentName}" 
                                data-date="${log.date}"
                                data-checkins='${JSON.stringify(log.allCheckIns)}'>
                                <i class="ph ph-eye text-base"></i>
                                <span>View All</span>
                            </button>
                        </td>
                    `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(err => {
                    console.error("Failed to fetch logs:", err);
                    tableBody.innerHTML = '';
                    tableBody.appendChild(noRecordsRow);
                    noRecordsRow.classList.remove('hidden');
                    noRecordsRow.querySelector('td').textContent = 'Error loading data. Please try again.';
                });
        }

        function formatTo12Hour(dateStr, timeStr) {
            try {
                const dateTime = new Date(`${dateStr}T${timeStr}`);
                return dateTime.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (e) {
                console.warn("Could not format time:", timeStr);
                return timeStr;
            }
        }

        function groupLogs(logs) {
            const studentMap = {};
            logs.forEach(log => {
                const key = `${log.studentNumber}-${log.date}`;
                const checkInTime = formatTo12Hour(log.date, log.time);

                if (!studentMap[key]) {
                    studentMap[key] = {
                        studentName: log.studentName,
                        studentNumber: log.studentNumber,
                        date: log.date,
                        firstCheckIn: checkInTime,
                        totalCheckIns: 1,
                        allCheckIns: [checkInTime]
                    };
                } else {
                    studentMap[key].totalCheckIns++;
                    studentMap[key].allCheckIns.push(checkInTime);
                }
            });
            return Object.values(studentMap);
        }

        function closeCheckinsModal() {
            checkinsModal.classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }

        [closeCheckinsBtn1, closeCheckinsBtn2].forEach(btn => btn?.addEventListener("click", closeCheckinsModal));
        checkinsModal?.addEventListener("click", e => {
            if (e.target === checkinsModal) closeCheckinsModal();
        });

        tableBody.addEventListener("click", (e) => {
            const viewBtn = e.target.closest(".viewCheckinsBtn");
            if (viewBtn) {
                const studentName = viewBtn.dataset.studentName;
                const date = viewBtn.dataset.date;
                const checkins = JSON.parse(viewBtn.dataset.checkins);

                checkinsModalTitle.textContent = `Check-ins for: ${studentName}`;
                checkinsModalSubtitle.textContent = `Date: ${date}`;
                checkinsList.innerHTML = '';

                checkins.forEach((time, index) => {
                checkinsList.innerHTML += `
                    <div class="flex justify-between items-center bg-orange-50 border border-orange-200 px-3 py-2 rounded-lg">
                        <p class="font-medium text-gray-800 text-sm">Check-in #${index + 1}</p>
                        <span class="text-sm font-semibold text-orange-700">${time}</span>
                    </div>
                    `;
                });
                checkinsModal.classList.remove("hidden");
                document.body.classList.add("overflow-hidden");
            }
        });

        fetchLogs(); 
    });
</script>