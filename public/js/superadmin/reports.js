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
    const circulatedBooksData = [
        { category: 'Student', today: '-', week: '-', month: '-', year: '-' },
        { category: 'Faculty', today: '-', week: '-', month: '-', year: '-' },
        { category: 'Staff', today: '-', week: '-', month: '-', year: '-' }
    ];

    function populateCirculatedBooks() {
        const tbody = document.getElementById('circulated-books-tbody');
        if (!tbody) return;
        tbody.innerHTML = ''; // Clear existing rows

        circulatedBooksData.forEach(data => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-orange-100');
            row.innerHTML = `
                <td class="px-4 py-2 text-left font-medium text-gray-700">${data.category}</td>
                <td class="px-4 py-2 text-center">${data.today}</td>
                <td class="px-4 py-2 text-center">${data.week}</td>
                <td class="px-4 py-2 text-center">${data.month}</td>
                <td class="px-4 py-2 text-center">${data.year}</td>
            `;
            tbody.appendChild(row);
        });

        const totalRow = document.createElement('tr');
        totalRow.classList.add('bg-orange-50', 'font-bold');
        totalRow.innerHTML = `
            <td class="px-4 py-2 text-left">TOTAL</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
        `;
        tbody.appendChild(totalRow);
    }


    // --- Deleted Books ---
    const deletedBooksData = [
        { count: '-', today: '-', month: '-', year: '-' },
        { count: '-', today: '-', month: '-', year: '-' },
        { count: '-', today: '-', month: '-', year: '-' }
    ];

    function populateDeletedBooks() {
        const tbody = document.getElementById('deleted-books-tbody');
        if (!tbody) return;
        tbody.innerHTML = ''; // Clear existing rows

        deletedBooksData.forEach(data => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-orange-100');
            row.innerHTML = `
                <td class="px-4 py-2 text-left font-medium text-gray-700">${data.count}</td>
                <td class="px-4 py-2 text-center">${data.today}</td>
                <td class="px-4 py-2 text-center">${data.month}</td>
                <td class="px-4 py-2 text-center">${data.year}</td>
            `;
            tbody.appendChild(row);
        });

        const totalRow = document.createElement('tr');
        totalRow.classList.add('bg-orange-50', 'font-bold');
        totalRow.innerHTML = `
            <td class="px-4 py-2 text-left">TOTAL</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
        `;
        tbody.appendChild(totalRow);
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
    const topVisitorsData = [
        { rank: 1, name: 'Alex Johnson', id: '20230001-S', course:'BSCS', visits: '-' },
        { rank: 2, name: 'Maria Garcia', id: '20230002-S', course:'BSCS', visits: '-' },
        { rank: 3, name: 'David Wilson', id: '20230003-S', course:'BSCS', visits: '-' },
        { rank: 4, name: 'Emma Davis', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 5, name: 'Michael Brown', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 6, name: 'Sophie Wilson', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 7, name: 'James Taylor', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 8, name: 'Lisa Anderson', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 9, name: 'Paul Walker', id: '20230004-S', course:'BSCS', visits: '-' },
        { rank: 10, name: 'Nancy Wheeler', id: '20230004-S', course:'BSCS', visits: '-' },
    ];

    function populateTopVisitors() {
        const tbody = document.getElementById('top-visitors-tbody');
        if (!tbody) return;
        
        topVisitorsData.forEach(data => {
        const row = document.createElement('tr');
        row.classList.add('border-b', 'border-orange-100');

        let rankColor = 'text-black font-normal';
        if (data.rank === 1) rankColor = 'text-yellow-400 font-bold';
        else if (data.rank === 2) rankColor = 'text-gray-400 font-bold';
        else if (data.rank === 3) rankColor = 'text-orange-500 font-bold';

        row.innerHTML = `
            <td class="px-4 py-2 text-left">
                <span class="inline-block text-center ${rankColor}">${data.rank}</span>
            </td>
            <td class="px-4 py-2 text-left font-medium text-gray-700">${data.name}</td>
            <td class="px-4 py-2 text-center">${data.id}</td>
            <td class="px-4 py-2 text-center">${data.course}</td>
            <td class="px-4 py-2 text-center">${data.visits}</td>
        `;
        tbody.appendChild(row);
    });

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