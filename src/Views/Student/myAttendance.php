<?php
if (!isset($_SESSION['user_id'])) {
  header("Location: /login");
  exit;
}

date_default_timezone_set('Asia/Manila');

use App\Repositories\AttendanceRepository;

$attendanceRepo = new AttendanceRepository();
$userId = $_SESSION['user_id']; // logged-in user

$allLogs = $attendanceRepo->getByUserId($userId);

$attendanceJS = [
  'day' => [],
  'week' => [],
  'month' => [],
  'year' => []
];

$now = new DateTime(); // manila time now

foreach ($allLogs as $log) {
  // DB timestamp is already in manila (DATETIME)
  $logTime = new DateTime($log['timestamp']);

  // format date and time for display
  $dateStr = $logTime->format('D, M d, Y'); // Fri, Sep 19, 2025
  $timeStr = $logTime->format('g:i A'); // 3:30 PM

  // calculate difference in days, months, years
  $diff = $now->diff($logTime);


  $today = new DateTime('today'); // midnight today
  $weekAgo = (clone $today)->modify('-6 days'); // last 7 days including today
  $firstOfMonth = new DateTime('first day of this month');
  $firstOfYear = new DateTime('first day of January this year');

  foreach ($allLogs as $log) {
    $logTime = new DateTime($log['timestamp']);
    $entry = [
      'date' => $logTime->format('D, M d, Y'),
      'time' => $logTime->format('g:i A'),
      'status' => 'Checked In'
    ];

    // compare only dates 
    $logDate = $logTime->format('Y-m-d');

    if ($logDate === $today->format('Y-m-d')) {
      $attendanceJS['day'][] = $entry;
    } elseif ($logTime >= $weekAgo) {
      $attendanceJS['week'][] = $entry;
    } elseif ($logTime >= $firstOfMonth) {
      $attendanceJS['month'][] = $entry;
    } elseif ($logTime >= $firstOfYear) {
      $attendanceJS['year'][] = $entry;
    }
  }
}
?>


<body class="min-h-screen p-6">

  <div class="mb-6">
    <h2 class="text-2xl font-bold mb-4">My Attendance</h2>
    <p class="text-gray-700">Track your library visits and check-in times.</p>
  </div>

  <!-- Attendance History Section -->
  <section
    class="bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] border-t-4 border-t-[var(--color-primary)] p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
      <div>
        <h4 class="text-base font-semibold">Attendance History</h4>
        <p class="text-sm text-[var(--color-gray-600)]">View your library check-ins by time period</p>
      </div>
    </div>
    <!-- Tabs -->
    <div class="w-full mb-4">
      <div class="flex items-center w-full rounded-full bg-[var(--color-orange-50)] p-1">
        <button class="att-tab flex-1 px-4 py-2 text-sm font-medium text-center rounded-full transition
             text-[var(--color-gray-600)] hover:text-[var(--color-orange-600)]
             data-[active=true]:bg-white data-[active=true]:border-2 data-[active=true]:border-[var(--color-orange-500)]
             data-[active=true]:text-[var(--color-orange-600)] data-[active=true]:shadow-sm" data-tab="day"
          data-active="true">
          Day
        </button>
        <button class="att-tab flex-1 px-4 py-2 text-sm font-medium text-center rounded-full transition
             text-[var(--color-gray-600)] hover:text-[var(--color-orange-600)]
             data-[active=true]:bg-white data-[active=true]:border-2 data-[active=true]:border-[var(--color-orange-500)]
             data-[active=true]:text-[var(--color-orange-600)] data-[active=true]:shadow-sm" data-tab="week"
          data-active="false">
          Week
        </button>
        <button class="att-tab flex-1 px-4 py-2 text-sm font-medium text-center rounded-full transition
             text-[var(--color-gray-600)] hover:text-[var(--color-orange-600)]
             data-[active=true]:bg-white data-[active=true]:border-2 data-[active=true]:border-[var(--color-orange-500)]
             data-[active=true]:text-[var(--color-orange-600)] data-[active=true]:shadow-sm" data-tab="month"
          data-active="false">
          Month
        </button>
        <button class="att-tab flex-1 px-4 py-2 text-sm font-medium text-center rounded-full transition
             text-[var(--color-gray-600)] hover:text-[var(--color-orange-600)]
             data-[active=true]:bg-white data-[active=true]:border-2 data-[active=true]:border-[var(--color-orange-500)]
             data-[active=true]:text-[var(--color-orange-600)] data-[active=true]:shadow-sm" data-tab="year"
          data-active="false">
          Year
        </button>
      </div>
    </div>

    <!-- Tab Contents -->
    <div id="tab-contents">
      <div class="tab-content" data-content="day"></div>
      <div class="tab-content hidden" data-content="week"></div>
      <div class="tab-content hidden" data-content="month"></div>
      <div class="tab-content hidden" data-content="year"></div>
    </div>

    <script>
      const tabs = document.querySelectorAll(".att-tab");
      const contents = document.querySelectorAll(".tab-content");

      const attendanceData = <?php echo json_encode($attendanceJS); ?>;

      function renderContent(tabName, container) {
        const data = attendanceData[tabName] || [];
        container.innerHTML = ""; // clear previous content

        if (data.length === 0) {
          container.innerHTML = `
      <div class="no-records flex flex-col items-center justify-center py-10 text-center border border-dashed border-[var(--color-border)] rounded-lg">
        <i class="ph ph-clipboard text-6xl"></i>
        <p class="text-sm font-medium">No attendance records</p>
        <p class="text-xs text-[var(--color-gray-500)]">No visits found for the selected time period.</p>
      </div>
    `;
          return;
        }

        const fragment = document.createDocumentFragment(); // better performance
        data.forEach(item => {
          const div = document.createElement("div");
          div.className = "record p-4 mb-2 bg-[var(--color-orange-50)] rounded-lg flex justify-between";
          div.innerHTML = `
      <div>
        <div class="flex items-center gap-2">
          <i class="ph ph-calendar-check"></i>
          <p class="font-medium">${item.date}</p>
        </div>
        <div class="flex items-center gap-2">
          <i class="ph ph-clock"></i>
          <p>Check-in: ${item.time}</p>
        </div>
      </div>
      <div class="text-right">
        <span class="text-sm font-medium text-green-600">${item.status}</span><br>
        <span class="text-xs text-gray-500">Status</span>
      </div>
    `;
          fragment.appendChild(div);
        });
        container.appendChild(fragment);
      }

      contents.forEach(c => renderContent(c.dataset.content, c));

      tabs.forEach(tab => {
        tab.addEventListener("click", () => {

          tabs.forEach(btn => btn.dataset.active = "false");
          tab.dataset.active = "true";

          // hide all contents and show the selected
          contents.forEach(c => c.classList.add("hidden"));
          const target = document.querySelector(`[data-content="${tab.dataset.tab}"]`);
          target.classList.remove("hidden");

          renderContent(tab.dataset.tab, target);
        });
      });
    </script>
  </section>
</body>