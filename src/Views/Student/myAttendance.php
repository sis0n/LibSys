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
      <div class="tab-content" data-content="day">
        <!-- No Records -->
        <div
          class="flex flex-col items-center justify-center py-10 text-center border border-dashed border-[var(--color-border)] rounded-lg">
          <i class="ph ph-clipboard text-6xl"></i>
          <p class="text-sm font-medium">No attendance records</p>
          <p class="text-xs text-[var(--color-gray-500)]">No visits found for the selected time period.</p>
        </div>

      </div>

      <div class="tab-content hidden" data-content="week">
        <!-- No Records -->
        <div
          class="flex flex-col items-center justify-center py-10 text-center border border-dashed border-[var(--color-border)] rounded-lg">
          <i class="ph ph-clipboard text-6xl"></i>
          <p class="text-sm font-medium">No attendance records</p>
          <p class="text-xs text-[var(--color-gray-500)]">No visits found for the selected time period.</p>
        </div>
      </div>

      <div class="tab-content hidden" data-content="month">
        <!-- No Records -->
        <div
          class="flex flex-col items-center justify-center py-10 text-center border border-dashed border-[var(--color-border)] rounded-lg">
          <i class="ph ph-clipboard text-6xl"></i>
          <p class="text-sm font-medium">No attendance records</p>
          <p class="text-xs text-[var(--color-gray-500)]">No visits found for the selected time period.</p>
        </div>
      </div>

      <div class="tab-content hidden" data-content="year">

        <!-- Sample record -->
        <div class="p-4 mb-2 bg-[var(--color-orange-50)] rounded-lg flex justify-between">
          <div>
            <div class="flex items-center gap-2 ">
              <i class="ph ph-calendar-check"></i>
              <p class="font-medium">Sept 19, Fri</p>
            </div>
            <div class="flex items-center gap-2">
              <i class="ph ph-clock"></i>
              <p>Check-in: 3:30 PM</p>
            </div>

          </div>
          <div class="text-right">
            <span class="text-sm font-medium text-green-600">Checked In</span><br>
            <span class="text-xs text-gray-500">Status</span>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Toggle tabs and content
      const tabs = document.querySelectorAll(".att-tab");
      const contents = document.querySelectorAll(".tab-content");

      tabs.forEach(tab => {
        tab.addEventListener("click", () => {
          // reset tabs
          tabs.forEach(btn => btn.dataset.active = "false");
          tab.dataset.active = "true";

          // hide all contents
          contents.forEach(c => c.classList.add("hidden"));
          // show target
          document.querySelector(`[data-content="${tab.dataset.tab}"]`).classList.remove("hidden");
        });
      });
    </script>


  </section>
</body>