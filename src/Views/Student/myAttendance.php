<?php
if (!isset($_SESSION['user_id'])) {
  header("Location: /libsys/public/login");
  exit;
}

date_default_timezone_set('Asia/Manila');

use App\Repositories\AttendanceRepository;

$attendanceRepo = new AttendanceRepository();
$userId = $_SESSION['user_id']; 

$allLogs = $attendanceRepo->getByUserId($userId);

$attendanceJS = [
  'day' => [],
  'week' => [],
  'month' => [],
  'year' => []
];

$now = new DateTime(); 
$today = new DateTime('today');

$firstOfMonth = new DateTime('first day of this month 00:00:00');
$firstOfYear = new DateTime('first day of January this year 23:59:59');

$startOfWeek = (clone $today)->modify('monday this week 00:00:00');
$endOfWeek = (clone $today)->modify('sunday this week 23:59:59');

foreach ($allLogs as $log) {
  $logTime = new DateTime($log['timestamp']);
  $entry = [
    'date' => $logTime->format('D, M d, Y'),
    'time' => $logTime->format('g:i A'),
    'status' => 'Checked In'
  ];

  $logDate = $logTime->format('Y-m-d');

  if ($logDate === $today->format('Y-m-d')) {
    $attendanceJS['day'][] = $entry;
  }

  if ($logTime >= $startOfWeek && $logTime <= $endOfWeek) {
    $attendanceJS['week'][] = $entry;
  }

  if ($logTime >= $firstOfMonth) {
    $attendanceJS['month'][] = $entry;
  }

  if ($logTime >= $firstOfYear) {
    $attendanceJS['year'][] = $entry;
  }
}

?>

<body class="min-h-screen p-6 bg-gray-50">
  <div class="mb-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">My Attendance</h2>
    <p class="text-gray-700">Track your library visits and check-in times.</p>
  </div>

  <section
    class="bg-[var(--color-card)] shadow-md rounded-lg border border-[var(--color-border)] border-t-4 border-t-[var(--color-primary)] p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
      <div>
        <h4 class="text-base font-semibold text-gray-800">Attendance History</h4>
        <p class="text-sm text-[var(--color-gray-600)]">View your library check-ins by time period</p>
      </div>
    </div>
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

    <div id="tab-contents">
      <div class="tab-content" data-content="day"></div>
      <div class="tab-content hidden" data-content="week"></div>
      <div class="tab-content hidden" data-content="month"></div>
      <div class="tab-content hidden" data-content="year"></div>
      <div id="attendance-error" class="text-red-600 text-center hidden p-4"></div>
    </div>

  </section>

  <script src="/libsys/public/js/student/myAttendance.js" defer></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      try {
        const phpAttendanceData = <?php echo json_encode($attendanceJS, JSON_THROW_ON_ERROR); ?>;

        if (typeof initializeMyAttendance === 'function') {
          initializeMyAttendance(phpAttendanceData);
        } else {
          throw new Error("Initialization function 'initializeMyAttendance' not found. Check if myAttendance.js loaded correctly.");
        }
      } catch (error) { 
        console.error("MyAttendance Init Error:", error.message, error);
        const errorDiv = document.getElementById('attendance-error');
        if (errorDiv) {
          errorDiv.textContent = "Could not load attendance data. Please refresh.";
          errorDiv.classList.remove('hidden');
          document.querySelectorAll('#tab-contents .tab-content').forEach(el => el.style.display = 'none');
        }
      }
    });
  </script>

</body>