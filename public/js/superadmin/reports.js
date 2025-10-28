document.addEventListener('DOMContentLoaded', function () {
    // --- Modal Elements ---
    const customDateModal = document.getElementById('customDateModal');
    const confirmDateRangeBtn = document.getElementById('confirmDateRange');
    const cancelDateRangeBtn = document.getElementById('cancelDateRange');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    let previousCirculatedFilter = 'year'; // Default value
    let previousLibraryVisitFilter = 'year'; // Default value for library visit
    let activeTableFilter = null; // To track which table initiated the modal

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
        { category: 'Student', y2025: '-', y2026: '-', y2027: '-' },
        { category: 'Faculty', y2025: '-', y2026: '-', y2027: '-' },
        { category: 'Staff', y2025: '-', y2026: '-', y2027: '-' }
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
                <td class="px-4 py-2 text-center">${data.y2025}</td>
                <td class="px-4 py-2 text-center">${data.y2026}</td>
                <td class="px-4 py-2 text-center">${data.y2027}</td>
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

    // --- Library Visit by Course ---
    const libraryVisitByCourseData = [
        { course: 'BSIT', y2025: '-', y2026: '-', y2027: '-' },
        { course: 'BSCS', y2025: '-', y2026: '-', y2027: '-' },
        { course: 'BSIS', y2025: '-', y2026: '-', y2027: '-' },
        { course: 'BSCE', y2025: '-', y2026: '-', y2027: '-' },
        { course: 'BSEE', y2025: '-', y2026: '-', y2027: '-' },
    ];

    function populateLibraryVisitByCourse() {
        const tbody = document.getElementById('library-visit-tbody');
        if (!tbody) return;
        tbody.innerHTML = ''; // Clear existing rows

        libraryVisitByCourseData.forEach(data => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'border-orange-100');
            row.innerHTML = `
                <td class="px-4 py-2 text-left font-medium text-gray-700">${data.course}</td>
                <td class="px-4 py-2 text-center">${data.y2025}</td>
                <td class="px-4 py-2 text-center">${data.y2026}</td>
                <td class="px-4 py-2 text-center">${data.y2027}</td>
            `;
            tbody.appendChild(row);
        });

        const totalRow = document.createElement('tr');
        totalRow.classList.add('bg-orange-50', 'font-bold', 'text-gray-800');
        totalRow.innerHTML = `
            <td class="px-4 py-2 text-left">TOTAL</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
            <td class="px-4 py-2 text-center">-</td>
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
    populateLibraryVisitByCourse();
    populateTopVisitors();

    // --- Dropdown Filters ---
    const circulatedBooksFilter = document.getElementById('circulatedBooksFilter');
    if (circulatedBooksFilter) {
        circulatedBooksFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                activeTableFilter = 'circulatedBooks';
                if(customDateModal) customDateModal.classList.remove('hidden');
            } else {
                updateCirculatedBooksTableHeaders(this.value);
                previousCirculatedFilter = this.value;
            }
        });
        // Initialize headers on load
        updateCirculatedBooksTableHeaders(circulatedBooksFilter.value);
    }

    const libraryVisitFilter = document.getElementById('libraryVisitFilter');
    if (libraryVisitFilter) {
        libraryVisitFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                activeTableFilter = 'libraryVisit';
                if(customDateModal) customDateModal.classList.remove('hidden');
            } else {
                updateLibraryVisitTableHeaders(this.value);
                previousLibraryVisitFilter = this.value;
            }
        });
        // Initialize headers on load
        updateLibraryVisitTableHeaders(libraryVisitFilter.value);
    }

    if(customDateModal) {
        cancelDateRangeBtn.addEventListener('click', () => {
            customDateModal.classList.add('hidden');
            if (activeTableFilter === 'circulatedBooks') {
                circulatedBooksFilter.value = previousCirculatedFilter; // Revert to previous selection
            } else if (activeTableFilter === 'libraryVisit') {
                libraryVisitFilter.value = previousLibraryVisitFilter; // Revert to previous selection
            }
            activeTableFilter = null; // Reset active filter
        });

        confirmDateRangeBtn.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                customDateModal.classList.add('hidden');
                if (activeTableFilter === 'circulatedBooks') {
                    updateCirculatedBooksTableHeaders('custom', { startDate, endDate });
                    previousCirculatedFilter = 'custom';
                } else if (activeTableFilter === 'libraryVisit') {
                    updateLibraryVisitTableHeaders('custom', { startDate, endDate });
                    previousLibraryVisitFilter = 'custom';
                }
                activeTableFilter = null; // Reset active filter
            } else {
                alert('Please select both start and end dates.');
            }
        });
    }

    function updateCirculatedBooksTableHeaders(filter, dateRange = null) {
        const theadRow = document.querySelector('#circulated-books-tbody').previousElementSibling.querySelector('tr');
        if (theadRow) {
            if (filter === 'custom' && dateRange) {
                const formattedStartDate = new Date(dateRange.startDate).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const formattedEndDate = new Date(dateRange.endDate).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });

                theadRow.innerHTML = `
                    <th scope="col" class="px-4 py-3 text-left">Category</th>
                    <th scope="col" class="px-4 py-3 text-center">
                        Total Borrowed
                        <span class="block text-xs font-normal text-gray-500">(${formattedStartDate} – ${formattedEndDate})</span>
                    </th>
                `;
                const tbody = document.getElementById('circulated-books-tbody');
                tbody.innerHTML = `
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">Student</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">Faculty</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">Staff</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="bg-orange-50 font-bold">
                        <td class="px-4 py-2 text-left">TOTAL</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                `;
            } else {
                 theadRow.innerHTML = `
                    <th scope="col" class="px-4 py-3 text-left">Category</th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                `;
                if (filter === 'month') {
                    theadRow.children[1].textContent = 'Today';
                    theadRow.children[2].textContent = 'Weekly';
                    theadRow.children[3].textContent = 'Monthly';
                } else if (filter === 'year') {
                    theadRow.children[1].textContent = '2025';
                    theadRow.children[2].textContent = '2026';
                    theadRow.children[3].textContent = '2027';
                }
                populateCirculatedBooks();
            }
        }
    }

    function updateLibraryVisitTableHeaders(filter, dateRange = null) {
        const theadRow = document.querySelector('#library-visit-tbody').previousElementSibling.querySelector('tr');
        if (theadRow) {
            if (filter === 'custom' && dateRange) {
                const formattedStartDate = new Date(dateRange.startDate).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                const formattedEndDate = new Date(dateRange.endDate).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });

                theadRow.innerHTML = `
                    <th scope="col" class="px-4 py-3 text-left">Category</th>
                    <th scope="col" class="px-4 py-3 text-center">
                        Total Count
                        <span class="block text-xs font-normal text-gray-500">(${formattedStartDate} – ${formattedEndDate})</span>
                    </th>
                `;
                const tbody = document.getElementById('library-visit-tbody');
                tbody.innerHTML = `
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">BSIT</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">BSCS</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">BSIS</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">BSCE</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="border-b border-orange-100">
                        <td class="px-4 py-2 text-left font-medium text-gray-700">BSEE</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                    <tr class="bg-orange-50 font-bold text-gray-800">
                        <td class="px-4 py-2 text-left">TOTAL</td>
                        <td class="px-4 py-2 text-center">-</td>
                    </tr>
                `;
            } else {
                 theadRow.innerHTML = `
                    <th scope="col" class="px-4 py-3 text-left">Course</th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                    <th scope="col" class="px-4 py-3 text-center"></th>
                `;
                if (filter === 'month') {
                    theadRow.children[1].textContent = 'Today';
                    theadRow.children[2].textContent = 'Weekly';
                    theadRow.children[3].textContent = 'Monthly';
                } else if (filter === 'year') {
                    theadRow.children[1].textContent = '2025';
                    theadRow.children[2].textContent = '2026';
                    theadRow.children[3].textContent = '2027';
                }
                populateLibraryVisitByCourse();
            }
        }
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
                labels: ['BSCS', 'BSIT', 'BSEMC', 'BSIS', 'BSCE'],
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