document.addEventListener('DOMContentLoaded', function () {
    // --- Modal Elements ---
    const customDateModal = document.getElementById('customDateModal');
    const confirmDateRangeBtn = document.getElementById('confirmDateRange');
    const cancelDateRangeBtn = document.getElementById('cancelDateRange');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const downloadReportBtn = document.getElementById('download-report-btn');

    // --- Library Resources ---
    const libraryResourcesData = [
        { year: 2025, title: '-', volume: '-', processed: '-' },
        { year: 2026, title: '-', volume: '-', processed: '-' },
        { year: 2027, title: '-', volume: '-', processed: '-' },
    ];

    const libraryResourcesTbody = document.getElementById('library-resources-tbody');
    if (libraryResourcesTbody) {
        libraryResourcesData.forEach(data => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-orange-100');
            row.innerHTML = `
                <td class="px-6 py-4 text-center font-medium">${data.year}</td>
                <td class="px-6 py-4 text-center font-medium">${data.title}</td>
                <td class="px-6 py-4 text-center font-medium">${data.volume}</td>
                <td class="px-6 py-4 text-center font-medium">${data.processed}</td>
            `;
            libraryResourcesTbody.appendChild(row);
        });
    }

        // --- Circulated Books ---
        async function populateCirculatedBooks() {
            const tbody = document.getElementById('circulated-books-tbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4">Loading...</td></tr>';
    
            try {
                const response = await fetch(`${BASE_URL}/api/superadmin/reports/circulated-books`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
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
            } catch (error) {
                console.error('Error loading circulated books report:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load report. Check console for details.</td></tr>';
            }
        }
    
    
        // --- Deleted Books ---
        async function populateDeletedBooks() {
            const tbody = document.getElementById('deleted-books-tbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="4" class="text-center p-4">Loading...</td></tr>';
    
            try {
                const response = await fetch(`${BASE_URL}/api/superadmin/reports/deleted-books`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
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
            } catch (error) {
                console.error('Error loading deleted books report:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            }
        }
    
        // --- Library Visit by Department ---
        const libraryVisitByDepartmentData = [
            { department: 'CBA', today: 0, week: 0, month: 0, year: 0 },
            { department: 'CCJE', today: 0, week: 0, month: 0, year: 0 },
            { department: 'CLAS', today: 0, week: 0, month: 0, year: 0 },
            { department: 'COE', today: 0, week: 0, month: 0, year: 0 },
            { department: 'COEngr', today: 0, week: 0, month: 0, year: 0 },
            { department: 'LAW', today: 0, week: 0, month: 0, year: 0 },
            { department: 'GS', today: 0, week: 0, month: 0, year: 0 },
        ];
    
        function populateLibraryVisitByDepartment() {
            const tbody = document.getElementById('library-visit-tbody');
            if (!tbody) return;
            tbody.innerHTML = ''; // Clear existing rows
    
            let totalToday = 0;
            let totalWeek = 0;
            let totalMonth = 0;
            let totalYear = 0;
    
            libraryVisitByDepartmentData.forEach(data => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-orange-100');
                row.innerHTML = `
                    <td class="px-4 py-2 text-left font-medium text-gray-700">${data.department}</td>
                    <td class="px-4 py-2 text-center">${data.today}</td>
                    <td class="px-4 py-2 text-center">${data.week}</td>
                    <td class="px-4 py-2 text-center">${data.month}</td>
                    <td class="px-4 py-2 text-center">${data.year}</td>
                `;
                tbody.appendChild(row);
    
                totalToday += data.today;
                totalWeek += data.week;
                totalMonth += data.month;
                totalYear += data.year;
            });
    
            const totalRow = document.createElement('tr');
            totalRow.classList.add('bg-orange-50', 'font-bold', 'text-gray-800');
            totalRow.innerHTML = `
                <td class="px-4 py-2 text-left">TOTAL</td>
                <td class="px-4 py-2 text-center">${totalToday}</td>
                <td class="px-4 py-2 text-center">${totalWeek}</td>
                <td class="px-4 py-2 text-center">${totalMonth}</td>
                <td class="px-4 py-2 text-center">${totalYear}</td>
            `;
            tbody.appendChild(totalRow);
        }
    
        // --- Top 10 Visitors ---
        async function populateTopVisitors() {
            const tbody = document.getElementById('top-visitors-tbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4">Loading...</td></tr>';
    
            try {
                const response = await fetch(`${BASE_URL}/api/superadmin/reports/top-visitors`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
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
            } catch (error) {
                console.error('Error loading top visitors report:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load report.</td></tr>';
            }
        }
    // --- Call Functions ---
    populateCirculatedBooks();
    populateDeletedBooks();
    populateLibraryVisitByDepartment();
    populateTopVisitors();
    

    // --- Download Report Button ---
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
                
                const reportUrl = `/LibSys/src/Views/report_pdf_template/pdfTemplate.php?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
                window.open(reportUrl, '_blank');
            } else {
                alert('Please select both start and end dates.');
            }
        });
    }

    // --- Charts ---
    initializeCharts(); // Call initializeCharts here
});

// Define initializeCharts function outside DOMContentLoaded
function initializeCharts() {
    // Top Visitors Chart
    const topCtx = document.getElementById('topVisitorsChart');
    if (topCtx) {
        new Chart(topCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['CBA', 'CCJE', 'CLAS', 'COE', 'COEngr'],
                datasets: [{
                    label: 'Visits',
                    data: [15, 10, 8, 12, 9],
                    backgroundColor: '#22c55e',
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
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Weekly Activity Chart
    const weeklyCtx = document.getElementById('weeklyActivityChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx.getContext('2d'), {
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
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8,
                            padding: 16,
                            font: {
                                size: 12,
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
    }
}