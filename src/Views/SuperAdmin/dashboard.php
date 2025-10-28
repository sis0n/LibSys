<?php
// dashboard.php
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<main class="min-h-min px-4 sm:px-6 md:px-10 flex flex-col gap-8">

  <!-- Welcome Section -->
  <section class="py-0">
    <h2 class="text-2xl font-bold text-gray-900">
      Welcome back, <span id="adminName"><?= htmlspecialchars($adminName) ?></span>!
    </h2>
    <p class="text-gray-600 mt-1 text-sm md:text-base">Here's your library system overview.</p>
  </section>

  <!-- Quick Admin Actions -->
  <section class="border border-orange-100 border-t-4 border-t-green-500 rounded-2xl shadow p-8 bg-white">
    <div class="flex flex-col mb-6">
      <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-1">
        <i class="ph ph-gear text-orange-500 text-xl"></i>
        Quick Admin Actions
      </h3>
      <p class="text-sm text-gray-500 ml-7">Common administrative tasks</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 justify-items-center gap-4">
      <a href="userManagement" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
        <i class="ph ph-users text-xl mr-2 align-middle"></i>
        User Management
      </a>
      <a href="bookManagement" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
        <i class="ph ph-books text-xl mr-2 align-middle"></i>
        Book Management
      </a>
      <a href="returning" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
        <i class="ph ph-swap text-xl mr-2 align-middle"></i>
        Returning Books
      </a>
      <a href="topVisitor" class="flex items-center justify-center bg-white border border-gray-200 text-gray-800 font-semibold py-4 px-6 rounded-lg shadow hover:bg-orange-500 hover:text-white hover:border-orange-500 transition w-full sm:w-56">
        <i class="ph ph-activity text-xl mr-2 align-middle"></i>
        Analytics
      </a>
    </div>
  </section>

  <!-- Dashboard Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
    <?php
    $cards = [
      ['id' => 'totalUsers', 'title' => 'Total Users', 'icon' => 'ph-user', 'color' => 'orange', 'subtitle' => '+0 this month'],
      ['id' => 'dailyVisitors', 'title' => 'Daily Visitors', 'icon' => 'ph-users-three', 'color' => 'yellow', 'subtitle' => 'Today'],
      ['id' => 'activeBooks', 'title' => 'Available Books', 'icon' => 'ph-book', 'color' => 'green', 'subtitle' => '0% available'],
      ['id' => 'borrowedBooks', 'title' => 'Borrowed Books', 'icon' => 'ph-books', 'color' => 'blue', 'subtitle' => '0% of total books'],
    ];
    foreach ($cards as $c):
    ?>
      <div class="flex flex-col justify-between rounded-lg shadow-sm bg-white border-l-4 border-<?= $c['color'] ?>-500 overflow-hidden h-full">
        <div class="bg-<?= $c['color'] ?>-100 px-6 py-3 flex justify-between items-center">
          <span class="text-sm font-semibold text-gray-600"><?= $c['title'] ?></span>
          <i class="ph <?= $c['icon'] ?> text-<?= $c['color'] ?>-500 text-xl"></i>
        </div>
        <div class="p-6 flex flex-col justify-between flex-grow">
          <h4 id="<?= $c['id'] ?>" class="text-3xl font-bold text-gray-900">0</h4>
          <p class="text-sm text-<?= $c['color'] ?>-600 mt-1"><?= $c['subtitle'] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- Charts Section -->
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Visitors -->
    <div class="border border-green-200 rounded-lg p-6 shadow-sm bg-white">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
          <i class="ph ph-chart-bar text-green-500 text-lg"></i> Top Visitors
        </h3>
        <button class="text-xs bg-orange-100 text-orange-600 px-3 py-1 rounded">This Month</button>
      </div>
      <canvas id="topVisitorsChart" class="h-48"></canvas>
    </div>

    <!-- Weekly Activity -->
    <div class="border border-blue-200 rounded-lg p-6 shadow-sm bg-white">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", async () => {
    try {
      const res = await fetch("/LibSys/public/superadmin/dashboard/getData");
      const result = await res.json();

      if (!result.success) {
        console.error(result.message);
        return;
      }

      const data = result.data;

      // === Update Cards ===
      const cardMap = {
        totalUsers: data.totalUsers,
        dailyVisitors: data.attendance_today,
        activeBooks: data.availableBooks,
        borrowedBooks: data.borrowed_books
      };

      for (const id in cardMap) {
        document.getElementById(id).textContent = cardMap[id];
      }

      document.querySelector("#totalUsers + p").textContent = `+${data.usersAddedThisMonth} this month`;
      document.querySelector("#activeBooks + p").textContent = `${data.availableBooksPercent}% available`;
      document.querySelector("#borrowedBooks + p").textContent = `${data.borrowedBooksPercent}% of total books`;

      // === Top Visitors Chart ===
      const topLabels = result.topVisitors.map(v => v.user_name || "Unknown");
      const topData = result.topVisitors.map(v => v.visits);

      new Chart(document.getElementById("topVisitorsChart").getContext("2d"), {
        type: "bar",
        data: {
          labels: topLabels,
          datasets: [{
            label: "Visits",
            data: topData,
            backgroundColor: "#22c55e",
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // === Weekly Activity Chart ===
      const weeklyLabels = result.weeklyActivity.map(w => w.day);
      const visitorsData = result.weeklyActivity.map(w => w.visitors);
      const borrowsData = result.weeklyActivity.map(w => w.borrows);

      new Chart(document.getElementById("weeklyActivityChart").getContext("2d"), {
        type: "line",
        data: {
          labels: weeklyLabels,
          datasets: [{
              label: "Visitors",
              data: visitorsData,
              borderColor: "#3b82f6",
              backgroundColor: "rgba(59,130,246,0.1)",
              tension: 0.4,
              fill: true
            },
            {
              label: "Borrows",
              data: borrowsData,
              borderColor: "#f59e0b",
              backgroundColor: "rgba(245,158,11,0.1)",
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

    } catch (err) {
      console.error("Error loading dashboard:", err);
    }
  });
</script>