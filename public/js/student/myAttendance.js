document.addEventListener('DOMContentLoaded', () => {
  if (typeof allAttendanceLogs === 'undefined') {
    console.error("myAttendance.js Error: Global variable 'allAttendanceLogs' not found.");
    const errorDiv = document.getElementById('attendance-error');
    if (errorDiv) {
      errorDiv.textContent = "Could not initialize attendance data.";
      errorDiv.classList.remove('hidden');
    }
    return;
  }

  const dateInput = document.getElementById('attendanceDate');
  const methodSelect = document.getElementById('attendanceMethod');
  const tableBody = document.getElementById('attendanceTableBody');
  const loadingRow = document.getElementById('tableLoadingRow');
  const noRecordsRow = document.getElementById('tableNoRecordsRow');
  const errorDiv = document.getElementById('attendance-error');

  function formatDate(dateString) {
    try {
      const localDate = new Date(dateString.replace(/-/g, '/'));
      if (isNaN(localDate)) return "Invalid Date";
      return localDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
    } catch (e) {
      console.error("Error formatting date:", dateString, e);
      return "Err Date";
    }
  }

  function formatDay(dateString) {
    try {
      const localDate = new Date(dateString.replace(/-/g, '/'));
      if (isNaN(localDate)) return "";
      return localDate.toLocaleDateString('en-US', { weekday: 'long' });
    } catch (e) {
      console.error("Error formatting day:", dateString, e);
      return "";
    }
  }

  function formatTime(dateString) {
    try {
      const localDate = new Date(dateString.replace(/-/g, '/'));
      if (isNaN(localDate)) return "";
      return localDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    } catch (e) {
      console.error("Error formatting time:", dateString, e);
      return "";
    }
  }

  function renderTable() {
    if (!tableBody || !loadingRow || !noRecordsRow || !dateInput || !methodSelect) {
      console.error("myAttendance.js Error: Required elements not found.");
      return;
    }

    const selectedDate = dateInput.value;
    const selectedMethod = methodSelect.value;

    tableBody.querySelectorAll("tr:not([data-placeholder])").forEach(row => row.remove());
    loadingRow.classList.add('hidden');
    noRecordsRow.classList.add('hidden');
    if (errorDiv) errorDiv.classList.add('hidden');

    if (!/^\d{4}-\d{2}-\d{2}$/.test(selectedDate)) {
      console.error("Invalid date format selected:", selectedDate);
      if (noRecordsRow) noRecordsRow.classList.remove('hidden');
      if (errorDiv) {
        errorDiv.textContent = "Invalid date selected.";
        errorDiv.classList.remove('hidden');
      }
      return;
    }

    let filteredLogs = allAttendanceLogs.filter(log => {
      return typeof log.timestamp === 'string' && log.timestamp.startsWith(selectedDate);
    });

    if (selectedMethod !== 'all') {
      filteredLogs = filteredLogs.filter(log => {
        return typeof log.method === 'string' && log.method.toLowerCase() === selectedMethod.toLowerCase();
      });
    }


    const paginationControls = document.getElementById("pagination-controls");

    if (filteredLogs.length === 0) {
      noRecordsRow.classList.remove('hidden');
      const noRecordsCell = noRecordsRow.querySelector('td');
      if (noRecordsCell) noRecordsCell.innerHTML = `
                 <i class="ph ph-clipboard text-4xl block mb-2"></i>
                 No attendance records found for the selected criteria.
             `;
      if (paginationControls) paginationControls.classList.add("hidden");
      return;
    }

    if (paginationControls) {
      // testing lang ulet kaya naka 5 yan 
      if (filteredLogs.length >= 5) {
        paginationControls.classList.remove("hidden");
      } else {
        paginationControls.classList.add("hidden");
      }
    }

    const fragment = document.createDocumentFragment();
    filteredLogs.forEach(log => {
      const row = document.createElement("tr");
      row.className = "hover:bg-orange-50 transition-colors";
      row.innerHTML = `
                <td class="px-4 py-3 text-gray-700">${formatDate(log.timestamp)}</td>
                <td class="px-4 py-3 text-gray-600">${formatDay(log.timestamp)}</td>
                <td class="px-4 py-3 text-gray-800 font-medium">${formatTime(log.timestamp)}</td>
                <td class="px-4 py-3 text-gray-600 capitalize">${log.method || 'N/A'}</td>
            `;
      fragment.appendChild(row);
    });
    tableBody.appendChild(fragment);
  }

  if (errorDiv && errorDiv.textContent.trim() && !errorDiv.classList.contains('hidden')) {
    if (loadingRow) loadingRow.classList.add('hidden');
  } else if (dateInput && methodSelect) {

    renderTable();

    dateInput.addEventListener('change', renderTable);
    methodSelect.addEventListener('change', renderTable);
  } else {
    console.error("myAttendance.js Error: Date input or Method select element not found.");
    if (loadingRow) loadingRow.classList.add('hidden');
    if (errorDiv) {
      errorDiv.textContent = "Could not find the date or method selector.";
      errorDiv.classList.remove('hidden');
    }
  }
});

