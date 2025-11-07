// --- SweetAlert Helper Functions (Global Declarations) ---

// TANGGALIN: showSuccessToast definition
// TANGGALIN: showErrorToast definition

// 🟠 LOADING MODAL (ORANGE THEME) - Dito lang tayo magfofocus
function showLoadingModal(message = "Processing request...", subMessage = "Please wait.") {
    if (typeof Swal == "undefined") return;
    Swal.fire({
        background: "transparent",
        html: `
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                <p class="text-gray-700 text-[14px]">${message}<br><span class="text-sm text-gray-500">${subMessage}</span></p>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            // ORANGE THEME DESIGN
            popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
        },
    });
}

// 🟠 CONFIRMATION MODAL (ORANGE THEME) - Hindi na ito gagamit ng Toast sa loob
async function showConfirmationModal(title, text, confirmText = "Confirm", icon = "ph-warning-circle") {
    if (typeof Swal == "undefined") return confirm(title);
    const result = await Swal.fire({
        background: "transparent",
        html: `
            <div class="flex flex-col text-center">
                <div class="flex justify-center mb-3">
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                        <i class="ph ${icon} text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-[17px] font-semibold text-orange-700">${title}</h3>
                <p class="text-[14px] text-gray-700 mt-1">${text}</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: "Cancel",
        customClass: {
            popup:
                "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
            confirmButton:
                "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
            cancelButton:
                "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
        },
    });
    return result.isConfirmed;
}
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


document.addEventListener('DOMContentLoaded', function () {
    // --- Modal Elements ---
    const customDateModal = document.getElementById('customDateModal');
    const confirmDateRangeBtn = document.getElementById('confirmDateRange');
    const cancelDateRangeBtn = document.getElementById('cancelDateRange');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const downloadReportBtn = document.getElementById('download-report-btn');

    // --- LOADING CONTROL ---
    const startTime = Date.now();
    // 🟠 START LOADING SWEETALERT MODAL PARA SA INITIAL PAGE LOAD
    if (typeof showLoadingModal !== 'undefined') {
        showLoadingModal("Loading Reports Dashboard...", "Fetching all reports and charts.");
    }


    // --- Circulated Books ---
    async function populateCirculatedBooks() {
        const tbody = document.getElementById('circulated-books-tbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4"><i class="ph ph-spinner animate-spin text-lg mr-2"></i>Loading...</td></tr>';
    
        try {
            const response = await fetch(`${BASE_URL}/api/admin/reports/circulated-books`);
            if (!response.ok) {
                const errorData = await response.json();
                // TANGGALIN: alert(errorData.message || `HTTP error! status: ${response.status}`);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            tbody.innerHTML = ''; // Clear loading message
    
            if (result.data && result.data.length > 0) {
                result.data.forEach(row => {
                    const isTotalRow = row.category === 'TOTAL';
                    const tr = document.createElement('tr');
                    
                    if (isTotalRow) {
                        tr.classList.add('bg-orange-50', 'font-bold');
                    } else {
                        tr.classList.add('border-b', 'border-orange-100');
                    }
    
                    tr.innerHTML = `
                        <td class="px-4 py-2 text-left ${isTotalRow ? 'font-bold' : 'font-medium text-gray-700'}">${row.category}</td>
                        <td class="px-4 py-2 text-center">${row.today || 0}</td>
                        <td class="px-4 py-2 text-center">${row.week || 0}</td>
                        <td class="px-4 py-2 text-center">${row.month || 0}</td>
                        <td class="px-4 py-2 text-center">${row.year || 0}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4">No data available.</td></tr>';
            }
            return true; // Success flag
        } catch (error) {
            console.error('Error loading circulated books report:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            return false; // Failure flag
        }
    }
    
    
    // --- Deleted Books ---
    async function populateDeletedBooks() {
        const tbody = document.getElementById('deleted-books-tbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="4" class="text-center p-4"><i class="ph ph-spinner animate-spin text-lg mr-2"></i>Loading...</td></tr>';
    
        try {
            const response = await fetch(`${BASE_URL}/api/admin/reports/deleted-books`);
            if (!response.ok) {
                const errorData = await response.json();
                // TANGGALIN: alert(errorData.message || `HTTP error! status: ${response.status}`);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            tbody.innerHTML = ''; // Clear loading message
    
            if (result.success && result.data && result.data.length > 0) {
                result.data.forEach(row => {
                    const isTotalRow = row.year === 'TOTAL';
                    const tr = document.createElement('tr');
    
                    if (isTotalRow) {
                        tr.classList.add('bg-orange-50', 'font-bold');
                    } else {
                        tr.classList.add('border-b', 'border-orange-100');
                    }
    
                    tr.innerHTML = `
                        <td class="px-4 py-2 text-left">${row.year}</td>
                        <td class="px-4 py-2 text-center">${row.month}</td>
                        <td class="px-4 py-2 text-center">${row.today}</td>
                        <td class="px-4 py-2 text-center font-medium text-gray-700">${row.count}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center p-4">No data available.</td></tr>';
            }
            return true; // Success flag
        } catch (error) {
            console.error('Error loading deleted books report:', error);
            tbody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            return false; // Failure flag
        }
    }
    
    // --- Library Visit by Department ---
    async function populateLibraryVisitByDepartment() {
        const tbody = document.getElementById('library-visit-tbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4"><i class="ph ph-spinner animate-spin text-lg mr-2"></i>Loading...</td></tr>';

        try {
            const response = await fetch(`${BASE_URL}/api/admin/reports/library-visits-department`);
            if (!response.ok) {
                const errorData = await response.json();
                // TANGGALIN: alert(errorData.message || `HTTP error! status: ${response.status}`);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            tbody.innerHTML = ''; // Clear loading message

            if (result.success && result.data && result.data.length > 0) {
                result.data.forEach(row => {
                    const isTotalRow = row.department === 'TOTAL';
                    const tr = document.createElement('tr');

                    if (isTotalRow) {
                        tr.classList.add('bg-orange-50', 'font-bold');
                    } else {
                        tr.classList.add('border-b', 'border-orange-100');
                    }

                    tr.innerHTML = `
                        <td class="px-4 py-2 text-left ${isTotalRow ? 'font-bold' : 'font-medium text-gray-700'}">${row.department}</td>
                        <td class="px-4 py-2 text-center">${row.today || 0}</td>
                        <td class="px-4 py-2 text-center">${row.week || 0}</td>
                        <td class="px-4 py-2 text-center">${row.month || 0}</td>
                        <td class="px-4 py-2 text-center">${row.year || 0}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4">No data available.</td></tr>';
            }
            return true; // Success flag
        } catch (error) {
            console.error('Error loading library visits by department report:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            return false; // Failure flag
        }
    }
    
    // --- Top 10 Visitors ---
    async function populateTopVisitors() {
        const tbody = document.getElementById('top-visitors-tbody');
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4"><i class="ph ph-spinner animate-spin text-lg mr-2"></i>Loading...</td></tr>';
    
        try {
            const response = await fetch(`${BASE_URL}/api/admin/reports/top-visitors`);
            if (!response.ok) {
                const errorData = await response.json();
                // TANGGALIN: alert(errorData.message || `HTTP error! status: ${response.status}`);
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            tbody.innerHTML = ''; // Clear loading message
    
            if (result.success && result.data && result.data.length > 0) {
                result.data.forEach((visitor, index) => {
                    const rank = index + 1;
                    const tr = document.createElement('tr');
                    tr.classList.add('border-b', 'border-orange-100');
    
                    let rankColor = 'text-black font-normal';
                    if (rank === 1) rankColor = 'text-yellow-400 font-bold';
                    else if (rank === 2) rankColor = 'text-gray-400 font-bold';
                    else if (rank === 3) rankColor = 'text-orange-500 font-bold';
    
                    tr.innerHTML = `
                        <td class="px-4 py-2 text-left">
                            <span class="inline-block text-center ${rankColor}">${rank}</span>
                        </td>
                        <td class="px-4 py-2 text-left font-medium text-gray-700">${visitor.full_name}</td>
                        <td class="px-4 py-2 text-center">${visitor.student_number}</td>
                        <td class="px-4 py-2 text-center">${visitor.course}</td>
                        <td class="px-4 py-2 text-center">${visitor.visits}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4">No visitor data available for this year.</td></tr>';
            }
            return true; // Success flag
        } catch (error) {
            console.error('Error loading top visitors report:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            return false; // Failure flag
        }
    }

    // --- Charts ---
    async function initializeCharts() {
        // Top Visitors Chart
        const topCtx = document.getElementById('topVisitorsChart')?.getContext('2d');
        
        // Weekly Activity Chart
        const weeklyCtx = document.getElementById('weeklyActivityChart')?.getContext('2d');
        
        try {
            const res = await fetch(`${BASE_URL}/api/admin/dashboard/getData`);
            const result = await res.json();
            
            if (!result.success) {
                console.error("Failed to load chart data:", result.message);
                return false; // Failure flag
            }

            // === Top Visitors Chart ===
            if (topCtx && result.topVisitors) {
                const topLabels = result.topVisitors.map(v => v.user_name || "Unknown");
                const topData = result.topVisitors.map(v => v.visits);

                new Chart(topCtx, {
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
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            // === Weekly Activity Chart ===
            if (weeklyCtx && result.weeklyActivity) {
                const weeklyLabels = result.weeklyActivity.map(w => w.day);
                const visitorsData = result.weeklyActivity.map(w => w.visitors);
                const borrowsData = result.weeklyActivity.map(w => w.borrows);

                new Chart(weeklyCtx, {
                    type: "line",
                    data: {
                        labels: weeklyLabels,
                        datasets: [
                            {
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
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
            return true; // Success flag
        } catch (err) {
            console.error("Error loading chart data:", err);
            return false; // Failure flag
        }
    }
    
    // --- MAIN INITIALIZATION LOGIC (The one that runs first) ---
    async function initReports() {
        // Run all parallel loading operations and get their status (success/fail)
        const results = await Promise.all([
            populateCirculatedBooks(),
            populateDeletedBooks(),
            populateLibraryVisitByDepartment(),
            populateTopVisitors(),
            initializeCharts()
        ]);

        // Check if any critical section failed
        const criticalFailure = results.some(result => result === false);

        // --- 4. CLOSE LOADING MODAL with Minimum Delay ---
        const elapsed = Date.now() - startTime;
        const minDelay = 1000; // Minimum 1 second loading time
        
        if (elapsed < minDelay) await new Promise(r => setTimeout(r, minDelay - elapsed));
        if (typeof Swal !== 'undefined') Swal.close();
        
        if (criticalFailure) {
            // TANGGALIN TOAST, PALITAN NG ALERT KUNG KRITIKAL
            alert("Page Load Alert: Some reports or charts failed to load due to a network or server issue.");
        } 
        // TANGGALIN: Success Toast
    }
    
    // --- EXECUTION ---
    initReports();
    
    // --- Download Report Button Handlers (No change) ---
    if (downloadReportBtn) {
        downloadReportBtn.addEventListener('click', function() {
            if (customDateModal) customDateModal.classList.remove('hidden');
        });
    }

    if(customDateModal) {
        cancelDateRangeBtn.addEventListener('click', () => {
            customDateModal.classList.add('hidden');
        });

        confirmDateRangeBtn.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                customDateModal.classList.add('hidden');

                // Create a form to submit the data via POST to trigger the PDF download
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `${BASE_URL}/generate-report`;
                form.target = '_blank'; // Open in a new tab to not interrupt the user's view

                const startInput = document.createElement('input');
                startInput.type = 'hidden';
                startInput.name = 'start_date';
                startInput.value = startDate;
                form.appendChild(startInput);

                const endInput = document.createElement('input');
                endInput.type = 'hidden';
                endInput.name = 'end_date';
                endInput.value = endDate;
                form.appendChild(endInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            } else {
                alert('Please select both start and end dates.');
            }
        });
    }
});