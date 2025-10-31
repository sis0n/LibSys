document.addEventListener('DOMContentLoaded', () => {
  const totalUsersEl = document.getElementById('totalUsers');
  const totalUsersChangeEl = document.getElementById('totalUsersChange'); 
  const dailyVisitorsEl = document.getElementById('dailyVisitors');
  const availableBooksEl = document.getElementById('availableBooks'); 
  const availableBooksPercentEl = document.getElementById('availableBooksPercent');
  const borrowedBooksEl = document.getElementById('borrowedBooks');
  const borrowedBooksPercentEl = document.getElementById('borrowedBooksPercent');

  const topVisitorsCtx = document.getElementById('topVisitorsChart')?.getContext('2d');
  const weeklyActivityCtx = document.getElementById('weeklyActivityChart')?.getContext('2d');
  const topVisitorsErrorEl = document.getElementById('topVisitorsError'); 
  const weeklyActivityErrorEl = document.getElementById('weeklyActivityError');

  let topVisitorsChartInstance = null;
  let weeklyActivityChartInstance = null;

  async function fetchStatsData() {
    if (totalUsersEl) totalUsersEl.textContent = '...';
    if (totalUsersChangeEl) totalUsersChangeEl.textContent = '';
    if (dailyVisitorsEl) dailyVisitorsEl.textContent = '...';
    if (availableBooksEl) availableBooksEl.textContent = '...'; 
    if (availableBooksPercentEl) availableBooksPercentEl.textContent = '';
    if (borrowedBooksEl) borrowedBooksEl.textContent = '...';
    if (borrowedBooksPercentEl) borrowedBooksPercentEl.textContent = '';

    try {
      const response = await fetch(`dashboard/stats`);
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      const data = await response.json();

      if (data.success && data.stats) {
        if (totalUsersEl) totalUsersEl.textContent = data.stats.totalUsers?.count ?? '0';
        if (totalUsersChangeEl) {
          totalUsersChangeEl.textContent = data.stats.totalUsers?.changeText ?? '';
          if (data.stats.totalUsers?.changePositive === true) {
            totalUsersChangeEl.className = 'text-sm text-green-600 mt-1';
          } else if (data.stats.totalUsers?.changePositive === false) {
            totalUsersChangeEl.className = 'text-sm text-red-600 mt-1';
          } else {
            totalUsersChangeEl.className = 'text-sm text-gray-500 mt-1';
          }
        }

        if (dailyVisitorsEl) dailyVisitorsEl.textContent = data.stats.dailyVisitors ?? '0';

        if (availableBooksEl) availableBooksEl.textContent = data.stats.availableBooks?.count ?? '0';
        if (availableBooksPercentEl) {
          availableBooksPercentEl.textContent = data.stats.availableBooks?.percentageText ?? '';
          availableBooksPercentEl.className = 'text-sm text-green-600 mt-1';
        }

        if (borrowedBooksEl) borrowedBooksEl.textContent = data.stats.borrowedBooks?.count ?? '0';
        if (borrowedBooksPercentEl) {
          borrowedBooksPercentEl.textContent = data.stats.borrowedBooks?.percentageText ?? '';
          borrowedBooksPercentEl.className = 'text-sm text-blue-600 mt-1';
        }

      } else {
        console.error('API Error fetching stats:', data.message || 'Unknown error');
        showErrorOnCards();
      }
    } catch (error) {
      console.error('Fetch Error fetching stats:', error);
      showErrorOnCards();
    }
  }

  function showErrorOnCards() {
    if (totalUsersEl) totalUsersEl.textContent = 'Err';
    if (dailyVisitorsEl) dailyVisitorsEl.textContent = 'Err';
    if (availableBooksEl) availableBooksEl.textContent = 'Err';
    if (borrowedBooksEl) borrowedBooksEl.textContent = 'Err';
    if (totalUsersChangeEl) totalUsersChangeEl.textContent = '';
    if (availableBooksPercentEl) availableBooksPercentEl.textContent = '';
    if (borrowedBooksPercentEl) borrowedBooksPercentEl.textContent = '';
  }

  async function fetchTopVisitorsChart() {
    if (!topVisitorsCtx || !topVisitorsErrorEl) {
      console.warn("Top visitors chart canvas or error element not found.");
      return;
    }
    topVisitorsErrorEl.classList.add('hidden');

    try {
      const response = await fetch(`dashboard/top-visitors`);
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      const result = await response.json();

      if (result.success) {
        if (topVisitorsChartInstance) {
          topVisitorsChartInstance.destroy();
        }
        topVisitorsChartInstance = new Chart(topVisitorsCtx, {
          type: 'bar',
          data: {
            labels: result.labels || [],
            datasets: [{
              label: 'Visits',
              data: result.data || [],
              backgroundColor: '#22c55e',
              borderRadius: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#4b5563', precision: 0 } },
              x: { grid: { display: false }, ticks: { color: '#4b5563' } }
            }
          }
        });
      } else {
        console.error('API Error fetching top visitors chart:', result.message);
        topVisitorsErrorEl.textContent = result.message || 'Could not load chart data.';
        topVisitorsErrorEl.classList.remove('hidden');
      }
    } catch (error) {
      console.error('Fetch Error fetching top visitors chart:', error);
      topVisitorsErrorEl.textContent = 'Network error loading chart data.';
      topVisitorsErrorEl.classList.remove('hidden');
    }
  }

  async function fetchWeeklyActivityChart() {
    if (!weeklyActivityCtx || !weeklyActivityErrorEl) {
      console.warn("Weekly activity chart canvas or error element not found.");
      return;
    }
    weeklyActivityErrorEl.classList.add('hidden');

    try {
      const response = await fetch(`dashboard/weekly-activity`);
      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
      const result = await response.json();

      if (result.success) {
        if (weeklyActivityChartInstance) {
          weeklyActivityChartInstance.destroy();
        }
        weeklyActivityChartInstance = new Chart(weeklyActivityCtx, {
          type: 'line',
          data: {
            labels: result.labels || [],
            datasets: [
              {
                label: 'Visitors',
                data: result.visitorsData || [],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
              },
              {
                label: 'Checkouts',
                data: result.checkoutsData || [],
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                tension: 0.4,
                fill: false,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                labels: {
                  usePointStyle: true, pointStyle: 'circle',
                  boxWidth: 6, boxHeight: 6, padding: 12,
                  font: { size: 11, weight: '500' }
                }
              },
              tooltip: { mode: 'index', intersect: false }
            },
            scales: {
              y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { color: '#4b5563', precision: 0 } },
              x: { grid: { display: false }, ticks: { color: '#4b5563' } }
            }
          }
        });
      } else {
        console.error('API Error fetching weekly activity chart:', result.message);
        weeklyActivityErrorEl.textContent = result.message || 'Could not load chart data.';
        weeklyActivityErrorEl.classList.remove('hidden');
      }
    } catch (error) {
      console.error('Fetch Error fetching weekly activity chart:', error);
      weeklyActivityErrorEl.textContent = 'Network error loading chart data.';
      weeklyActivityErrorEl.classList.remove('hidden');
    }
  }

  function loadDashboardData() {
    fetchStatsData();
    fetchTopVisitorsChart();
    fetchWeeklyActivityChart();
  }

  loadDashboardData();

});
