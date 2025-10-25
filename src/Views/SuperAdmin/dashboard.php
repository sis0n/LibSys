<!-- DASHBOARD MAIN CONTENT -->
<main class="min-h-[85vh]px-4 sm:px-6 md:px-10 flex flex-col gap-8 ">

  <!-- Welcome Section -->
  <section class="py-0">
  <h2 class="text-2xl font-bold text-gray-900">
    Welcome back, <span id="adminName">John Anderson</span>!
  </h2>
  <p class="text-gray-600 mt-1 text-sm md:text-base">Here's your library system overview.</p>
</section>

<!-- Quick Admin Actions -->
<section class="border border-orange-100 border-t-4 border-t-green-500 rounded-2xl shadow- p-8 bg-white">
  <div class="flex flex-col mb-6">
    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-1">
      <i class="ph ph-gear text-orange-500 text-xl"></i>
      Quick Admin Actions
    </h3>
    <p class="text-sm text-gray-500 ml-7">Common administrative tasks</p>
  </div>

  <!-- Responsive Action Buttons -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 justify-items-center gap-4">
    <a href="userManagement" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
      <i class="ph ph-users text-xl mr-2 align-middle"></i>
     User Management  
    </a>

    <a href="bookManagement" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
      <i class="ph ph-books text-xl mr-2 align-middle"></i>
      Book Catalog
    </a>

    <a href="#" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
      <i class="ph ph-warning-circle text-xl mr-2 align-middle"></i>
     Returning Books
    </a>

    <a href="#" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
      <i class="ph ph-activity text-xl mr-2 align-middle"></i>
      Analytics
    </a>
  </div>
</section>

<section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
  <!-- CARD 1 -->
  <div class="flex flex-col justify-between rounded-lg shadow-sm bg-white border-l-4 border-orange-500 overflow-hidden h-full">
    <div class="bg-orange-100 px-6 py-3 flex justify-between items-center">
      <span class="text-sm font-semibold text-gray-600">Total Users</span>
      <i class="ph ph-user text-orange-500 text-xl"></i>
    </div>
    <div class="p-6 flex flex-col justify-between flex-grow">
      <h4 id="totalUsers" class="text-3xl font-bold text-gray-900">234</h4>
      <p class="text-sm text-green-600 mt-1">+12 this month</p>
    </div>
  </div>

  <!-- CARD 2 -->
  
<div class="flex flex-col justify-between rounded-lg shadow-sm bg-white border-l-4 border-yellow-500 overflow-hidden h-full">
    <div class="bg-yellow-100 px-6 py-3 flex justify-between items-center">
      <span class="text-sm font-semibold text-gray-600">Daily Visitors</span>
      <i class="ph ph-users-three text-yellow-500 text-xl"></i>
    </div>
    <div class="p-6 flex flex-col justify-between flex-grow">
      <h4 id="dailyVisitors" class="text-3xl font-bold text-gray-900">47</h4>
      <p class="text-sm text-green-600 mt-1">Today</p>
    </div>
  </div>
  <!-- CARD 3 -->
  

  <div class="flex flex-col justify-between rounded-lg shadow-sm bg-white border-l-4 border-green-500 overflow-hidden h-full">
    <div class="bg-green-50 px-6 py-3 flex justify-between items-center">
      <span class="text-sm font-semibold text-gray-600">Available Books</span>
      <i class="ph ph-book text-green-500 text-xl"></i>
    </div>
    <div class="p-6 flex flex-col justify-between flex-grow">
      <h4 id="activeBooks" class="text-3xl font-bold text-gray-900">1,247</h4>  
      <p class="text-sm text-green-600 mt-1">89% available</p>
    </div>
  </div>

  <!-- CARD 4 -->
  <div class="flex flex-col justify-between rounded-lg shadow-sm bg-white border-l-4 border-blue-500 overflow-hidden h-full">
    <div class="bg-blue-100 px-6 py-3 flex justify-between items-center">
      <span class="text-sm font-semibold text-gray-600">Borrowed Books</span>
      <i class="ph ph-books text-blue-500 text-xl"></i>
    </div>
    <div class="p-6 flex flex-col justify-between flex-grow">
      <h4 id="borrowedBooks" class="text-3xl font-bold text-gray-900">145</h4>
      <p class="text-sm text-blue-600 mt-1">20% of total books</p>
    </div>
  </div>
</section>




  <!-- Charts Section -->
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Visitors -->
    <div class="border border-green-200 rounded-lg p-6 shadow-sm">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
          <i class="ph ph-chart-bar text-green-500 text-lg"></i> Top Visitors
        </h3>
        <button class="text-xs bg-orange-100 text-orange-600 px-3 py-1 rounded">This Month</button>
      </div>
      <canvas id="topVisitorsChart" class="h-48"></canvas>
    </div>

    <!-- Weekly Activity -->
    <div class="border border-blue-200 rounded-lg p-6 shadow-sm">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
          <i class="ph ph-activity text-blue-500 text-lg"></i> Weekly Activity
        </h3>
        <p class="text-xs text-gray-500">Daily visitors and book checkouts</p>
      </div>
      <canvas id="weeklyActivityChart" class="h-48"></canvas>
    </div>
  </section>


</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const topCtx = document.getElementById('topVisitorsChart').getContext('2d');
  new Chart(topCtx, {
    type: 'bar',
    data: {
      labels: ['John', 'Maria', 'Luke', 'Ella', 'Sophia'],
      datasets: [{
        label: 'Visits',
        data: [15, 10, 8, 12, 9],
        backgroundColor: '#22c55e',
        borderRadius: 6
      }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
  });

 const weeklyCtx = document.getElementById('weeklyActivityChart').getContext('2d');

new Chart(weeklyCtx, {
  type: 'line',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [
      {
        label: 'Visitors',
        data: [12, 19, 9, 14, 8, 11, 15],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.1)',
        tension: 0.4,
        fill: true,
      },
      {
        label: 'Checkouts',
        data: [8, 14, 5, 10, 6, 9, 11],
        borderColor: '#f97316',
        backgroundColor: 'rgba(249,115,22,0.1)',
        tension: 0.4,
        fill: false,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true,
        labels: {
          usePointStyle: true,
          pointStyle: 'circle',
          boxWidth: 6,   // mas maliit pa
          boxHeight: 6,  // mas maliit pa
          padding: 12,
          font: {
            size: 11,
            weight: '500',
          },
        },
      },
      tooltip: {
        mode: 'index',
        intersect: false,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: { color: '#f3f4f6' },
        ticks: { color: '#4b5563' },
      },
      x: {
        grid: { display: false },
        ticks: { color: '#4b5563' },
      },
    },
  },
});
</script>
