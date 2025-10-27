document.addEventListener('DOMContentLoaded', function () {
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
        { rank: 1, name: 'Alex Johnson', id: '20230001-S', visits: '-' },
        { rank: 2, name: 'Maria Garcia', id: '20230002-S', visits: '-' },
        { rank: 3, name: 'David Wilson', id: '20230003-S', visits: '-' },
        { rank: 4, name: 'Emma Davis', id: '20230004-S', visits: '-' },
        { rank: 5, name: 'Michael Brown', id: '20230004-S', visits: '-' },
        { rank: 6, name: 'Sophie Wilson', id: '20230004-S', visits: '-' },
        { rank: 7, name: 'James Taylor', id: '20230004-S', visits: '-' },
        { rank: 8, name: 'Lisa Anderson', id: '20230004-S', visits: '-' },
        { rank: 9, name: 'Paul Walker', id: '20230004-S', visits: '-' },
        { rank: 10, name: 'Nancy Wheeler', id: '20230004-S', visits: '-' },
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
            <td class="px-4 py-2 text-center">${data.visits}</td>
        `;
        tbody.appendChild(row);
    });

    }

    // --- Call Functions ---
    populateCirculatedBooks();
    populateLibraryVisitByCourse();
    populateTopVisitors();
});