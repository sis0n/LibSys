document.addEventListener("DOMContentLoaded", () => {

  const buttons = document.querySelectorAll('.period-btn');
  const label = document.getElementById('visitor-label');
  const count = document.getElementById('visitor-count');

  if (buttons.length > 0 && label && count) {
    const defaultActiveTab = document.querySelector('.period-btn.bg-\\[var\\(--color-popover\\)\\]');
    if (!defaultActiveTab && buttons.length > 0) {
      buttons[0].classList.add('bg-[var(--color-popover)]', 'font-medium');
      label.textContent = `This ${buttons[0].dataset.period || 'Week'}`;
      count.textContent = buttons[0].dataset.count || 0;
    }

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
  } else {
    console.warn("AttendanceLogs: 'Total Visitors' card elements not found.");
  }

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

  if (!searchInput || !dateInput || !courseBtn || !courseMenu || !courseValueSpan || !tableBody || !noRecordsRow || !checkinsModal) {
    console.error("AttendanceLogs Error: May kulang na essential elements (filters, table, modal). Hindi gagana nang tama ang script.");
    if (noRecordsRow) {
      const cell = noRecordsRow.querySelector('td') || noRecordsRow.insertCell(0);
      cell.colSpan = 5;
      cell.className = 'text-center text-red-500 py-10';
      cell.textContent = 'Page Error: Missing components.';
      noRecordsRow.classList.remove('hidden');
    }
    return; 
  }

  let currentSearch = "";
  let currentDate = "";
  let currentCourse = "All Courses";

  const timezone = "Asia/Manila";

  function getPhDate(date = new Date()) {
    try {
      return new Date(date.toLocaleString("en-US", { timeZone: timezone }));
    } catch (e) {
      console.warn("Could not get PH date, using local time.", e);
      return new Date();
    }
  }

  function formatDate(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  }

  const todayDateObj = getPhDate();
  const yesterdayDateObj = getPhDate(new Date());
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

    const firstItem = menu.querySelector('[classclass*="dropdown-item"]');
    if (firstItem) {
      console.warn("Typo 'classclass' detected and fixed in dropdown.");
      firstItem.className = firstItem.getAttribute('classclass');
    }

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

  let fetchDebounceTimer;
  searchInput.addEventListener("input", () => {
    currentSearch = searchInput.value.trim();
    clearTimeout(fetchDebounceTimer);
    fetchDebounceTimer = setTimeout(fetchLogs, 300); 
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

    tableBody.innerHTML = ''; 
    noRecordsRow.classList.remove('hidden');
    noRecordsRow.querySelector('td').innerHTML = `<i class="ph ph-spinner animate-spin text-2xl"></i> Loading...`;
    tableBody.appendChild(noRecordsRow);


    const url = new URL(`${BASE_URL}/attendance/logs/ajax`, window.location.origin);

    url.searchParams.append('period', periodToSend);
    url.searchParams.append('search', currentSearch);
    if (currentCourse !== "All Courses") {
      url.searchParams.append('course', currentCourse);
    }

    fetch(url)
      .then(res => {
        if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
        return res.json();
      })
      .then(data => {

        let filteredData = data;
        if (dateToFilter) {
          filteredData = data.filter(log => log.date === dateToFilter);
        }

        tableBody.innerHTML = ''; 

        if (filteredData.length === 0) {
          noRecordsRow.querySelector('td').innerHTML = `<i class="ph ph-clipboard text-4xl block mb-2"></i> No attendance records found.`;
          tableBody.appendChild(noRecordsRow);
          noRecordsRow.classList.remove('hidden');
          return;
        }

        noRecordsRow.classList.add('hidden');

        const groupedLogs = groupLogs(filteredData);
        const fragment = document.createDocumentFragment();

        groupedLogs.forEach(log => {
          const row = document.createElement('tr');
          row.className = 'bg-white';

          const studentName = log.studentName ? log.studentName.replace(/</g, "&lt;") : 'N/A';
          const studentNumber = log.studentNumber ? log.studentNumber.replace(/</g, "&lt;") : 'N/A';
          const date = log.date ? log.date.replace(/</g, "&lt;") : 'N/A';
          const firstCheckIn = log.firstCheckIn ? log.firstCheckIn.replace(/</g, "&lt;") : 'N/A';
          const checkinsAttr = JSON.stringify(log.allCheckIns || []).replace(/'/g, "&apos;"); // Handle single quotes

          row.innerHTML = `
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">${studentName}</p>
                            <p class="text-gray-500 text-xs">${studentNumber}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-700">${date}</td>
                        <td class="px-4 py-3 text-gray-700">${firstCheckIn}</td>
                        <td class="px-4 py-3 text-gray-700">
                            <span class="bg-orange-100 text-orange-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                ${log.totalCheckIns || 0}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button class="viewCheckinsBtn flex items-center gap-1 border border-orange-200 text-gray-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-orange-50 transition" 
                                data-student-name="${studentName.replace(/"/g, "&quot;")}" 
                                data-date="${date}"
                                data-checkins='${checkinsAttr}'>
                                <i class="ph ph-eye text-base"></i>
                                <span>View All</span>
                            </button>
                        </td>
                    `;
          fragment.appendChild(row);
        });
        tableBody.appendChild(fragment);
      })
      .catch(err => {
        console.error("Failed to fetch logs:", err);
        tableBody.innerHTML = '';
        noRecordsRow.querySelector('td').textContent = `Error loading data: ${err.message}. Please try again.`;
        tableBody.appendChild(noRecordsRow);
        noRecordsRow.classList.remove('hidden');
      });
  }

  function formatTo12Hour(dateStr, timeStr) {
    try {
      const dateTime = new Date(`${dateStr}T${timeStr}`);
      if (isNaN(dateTime.getTime())) throw new Error("Invalid date"); 
      return dateTime.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
      });
    } catch (e) {
      console.warn("Could not format time:", timeStr, e);
      return timeStr || 'N/A';
    }
  }

  function groupLogs(logs) {
    const studentMap = {};
    logs.forEach(log => {
      if (!log || !log.studentNumber || !log.date || !log.time) {
        console.warn("Skipping malformed log:", log);
        return; 
      }
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
    if (checkinsModal) {
      checkinsModal.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }
  }

  if (closeCheckinsBtn1 && closeCheckinsBtn2) {
    [closeCheckinsBtn1, closeCheckinsBtn2].forEach(btn => btn?.addEventListener("click", closeCheckinsModal));
  }
  if (checkinsModal) {
    checkinsModal.addEventListener("click", e => {
      if (e.target === checkinsModal) closeCheckinsModal();
    });
  }

  if (tableBody) {
    tableBody.addEventListener("click", (e) => {
      const viewBtn = e.target.closest(".viewCheckinsBtn");
      if (viewBtn) {
        try {
          const studentName = viewBtn.dataset.studentName;
          const date = viewBtn.dataset.date;
          const checkins = JSON.parse(viewBtn.dataset.checkins);

          checkinsModalTitle.textContent = `Check-ins for: ${studentName}`;
          checkinsModalSubtitle.textContent = `Date: ${date}`;
          checkinsList.innerHTML = ''; 

          if (checkins && checkins.length > 0) {
            checkins.forEach((time, index) => {
              checkinsList.innerHTML += `
                            <div class="flex justify-between items-center bg-orange-50 border border-orange-200 px-3 py-2 rounded-lg">
                                <p class="font-medium text-gray-800 text-sm">Check-in #${index + 1}</p>
                                <span class="text-sm font-semibold text-orange-700">${time}</span>
                            </div>
                            `;
            });
          } else {
            checkinsList.innerHTML = `<p class="text-center text-gray-500 text-sm">No check-in details found.</p>`;
          }

          checkinsModal.classList.remove("hidden");
          document.body.classList.add("overflow-hidden");
        } catch (err) {
          console.error("Error parsing modal data:", err);
          alert("Could not open check-in details.");
        }
      }
    });
  }

  fetchLogs();
});