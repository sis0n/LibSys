<!-- Header -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h2 class="text-xl font-bold flex items-center gap-2">
      </i> Attendance Logs
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

<!-- Total Visitors Card -->
<div class="bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-sm mb-6">
  <div class="p-4 border-b border-[var(--color-border)]">
    <h3 class="text-md font-medium">Total Visitors</h3>
    <p class="text-sm text-[var(--color-gray-500)]">View visitor statistics by time period</p>
  </div>
  <div class="p-4">
    <!-- Tabs -->
    <div
      class="flex items-center border border-[var(--color-border)]  bg-[color:var(--color-muted)] p-1 justify-center mb-4 rounded-full">
      <button
        class="flex-1 py-2 text-sm rounded-full bg-[var(--color-popover)] hover:bg-[var(--color-popover)] font-medium period-btn"
        data-period="Week" data-count="0">
        Week
      </button>
      <button class="flex-1 py-2 text-sm rounded-full hover:bg-[var(--color-popover)] period-btn" data-period="Month"
        data-count="0">
        Month
      </button>
      <button class="flex-1 py-2 text-sm rounded-full hover:bg-[var(--color-popover)] period-btn" data-period="Year"
        data-count="0">
        Year
      </button>
    </div>

    <!-- Visitor Count -->
    <div class="flex flex-col items-center justify-center p-6 rounded-lg border border-[var(--color-border)]">
      <span id="visitor-label" class="text-sm flex items-center gap-1 text-[var(--color-primary)]">
        This Week
      </span>
      <span id="visitor-count" class="text-3xl font-bold mt-2">0</span>
      <span class="text-xs text-[var(--color-gray-500)]">Total visitors</span>
    </div>
  </div>

  <!-- Eto sa paglipat lang sa tabs, ikaw na mag ayos sa Controller di ko kasi sure kung need to sa attendanceController.php -->
  <script>
    const buttons = document.querySelectorAll('.period-btn');
    const label = document.getElementById('visitor-label');
    const count = document.getElementById('visitor-count');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const period = btn.dataset.period;
        const visitorCount = btn.dataset.count;

        // Update UI
        label.textContent = `This ${period}`;
        count.textContent = visitorCount;

        // Active button style
        buttons.forEach(b => b.classList.remove('bg-[var(--color-popover)]', 'font-medium'));
        btn.classList.add('bg-[var(--color-popover)]', 'font-medium');
      });
    });
  </script>
</div>

<!-- Filter Logs -->
<div class="border border-[var(--color-border)] bg-[var(--color-card)] rounded-xl p-4 mb-6 shadow-sm">
  <div class="flex items-center gap-2 mb-3">
    <h3 class="font-medium text-gray-800">Filter Logs</h3>
  </div>

  <!-- Search and Dropdown -->
  <div class="flex items-center gap-3">
    <!-- Search -->
    <input type="text" placeholder="Search by student name or ID..."
      class="flex-1 border border-orange-100 rounded-lg p-2 bg-orange-50 focus:ring-2 focus:ring-orange-400 outline-none text-sm text-orange-900 font-medium" />
    <div class="relative inline-block w-48">

      <!-- Trigger Button -->
      <button id="dropdownButton" class="w-full flex justify-between items-center rounded-md border border-orange-300 bg-orange-50 
        px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400">
        <span id="dropdownValue">Today</span>
        <i class="ph ph-caret-down"></i>
      </button>

      <!-- Dropdown Options -->
      <div id="dropdownMenu"
        class="absolute z-10 mt-1 w-full hidden rounded-md bg-white shadow-lg border border-orange-200">
        <div class="py-1">
          <div class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-orange-100 rounded-t-md"
            onclick="selectOption('Today')">Today</div>
          <div class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-orange-100"
            onclick="selectOption('Yesterday')">Yesterday</div>
          <div class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-orange-100 rounded-b-md"
            onclick="selectOption('All dates')">All dates</div>
        </div>
      </div>
    </div>

    <!-- Sa style lang to dahil bawal malagyan ng tailwind yung option, kaya custom nalang option natin, nag provide lang sariling gawang option-->
    <script>
      const dropdownBtn = document.getElementById("dropdownButton");
      const dropdownMenu = document.getElementById("dropdownMenu");
      const dropdownValue = document.getElementById("dropdownValue");

      dropdownBtn.addEventListener("click", () => {
        dropdownMenu.classList.toggle("hidden");
      });

      function selectOption(value) {
        dropdownValue.textContent = value;
        dropdownMenu.classList.add("hidden");
      }

      // Optional: close dropdown if click outside
      document.addEventListener("click", (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
          dropdownMenu.classList.add("hidden");
        }
      });
    </script>
  </div>
</div>

<!-- Attendance Records -->
<div class="border border-orange-200 bg-[var(--color-card)] rounded-xl p-4 shadow-sm">
  <h3 class="font-semibold text-gray-800 mb-1">Attendance Records</h3>
  <p class="text-gray-500 text-sm mb-4">Showing records</p>

  <div class="space-y-3">
    <!-- Record Item -->
    <div class="flex justify-between items-center border border-orange-200 rounded-lg p-3 hover:bg-orange-50">
      <div class="flex items-center gap-3">
        <!-- Date -->
        <div class="text-center text-sm">
          <p class="font-semibold">Date</p>
          <p class="text-gray-500 text-xs">Day</p>
        </div>

        <!-- Student Info -->
        <div>
          <p class="font-medium text-gray-800">Alwyn Adriano <span
              class="bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-lg">Student Number</span></p>
          <p class="text-gray-500 text-xs">Time</p>
        </div>
      </div>
      
      <!-- Status -->
      <div class="text-right">
        <p class="text-green-600 font-medium text-sm">Visited</p>
        <p class="text-gray-500 text-xs">Library attendance</p>
      </div>
    </div>

    <!-- Duplicate same block for other records -->
    <div class="flex justify-between items-center border border-orange-200 rounded-lg p-3 hover:bg-orange-50">
      <div class="flex items-center gap-3">
        <div class="text-center text-sm">
          <p class="font-semibold">Date</p>
          <p class="text-gray-500 text-xs">Day</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Joshua Colmo <span
              class="bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-lg">20230114-S</span></p>
          <p class="text-gray-500 text-xs">Time</p>
        </div>
      </div>
      <div class="text-right">
        <p class="text-green-600 font-medium text-sm">Visited</p>
        <p class="text-gray-500 text-xs">Library attendance</p>
      </div>
    </div>

    <!-- Maria -->
    <div class="flex justify-between items-center border border-orange-200 rounded-lg p-3 hover:bg-orange-50">
      <div class="flex items-center gap-3">
        <div class="text-center text-sm">
          <p class="font-semibold">Aug 1</p>
          <p class="text-gray-500 text-xs">Fri</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Kyle Madriaga <span
              class="bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-lg">Student Number</span></p>
          <p class="text-gray-500 text-xs">Check-in: 10:30 (Sample lang to)</p>
        </div>
      </div>
      <div class="text-right">
        <p class="text-green-600 font-medium text-sm">Visited</p>
        <p class="text-gray-500 text-xs">Library attendance</p>
      </div>
    </div>

    <!-- Alex -->
    <div class="flex justify-between items-center border border-orange-200 rounded-lg p-3 hover:bg-orange-50">
      <div class="flex items-center gap-3">
        <div class="text-center text-sm">
          <p class="font-semibold">Date</p>
          <p class="text-gray-500 text-xs">Day</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Renz Geronimo <span
              class="bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-lg">Student Number</span></p>
          <p class="text-gray-500 text-xs">Check-in: 09:15 (Sample format ulit)</p>
        </div>
      </div>
      <div class="text-right">
        <p class="text-green-600 font-medium text-sm">Visited</p>
        <p class="text-gray-500 text-xs">Library attendance</p>
      </div>
    </div>
  </div>
</div>