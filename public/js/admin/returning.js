document.addEventListener('DOMContentLoaded', function() {
    // Dummy data for demonstration
    const booksNearDue = [{
            student_name: 'Alex Johnson',
            student_id: 'ST-2024-001',
            student_course: 'BS Computer Science - 3rd A',
            item_borrowed: 'Introduction to Algorithms Introduction to Algorithms Introduction to Algorithms Introduction to Algorithms',
            date_borrowed: '2025-09-15',
            due_date: '2025-10-25',
            contact: '+639123456789',
            days_until_due: 4
        },
        {
            student_name: 'Sarah Lee',
            student_id: 'ST-2024-007',
            student_course: 'BS Civil Engineering - 4th C',
            item_borrowed: 'Structural Analysis',
            date_borrowed: '2025-10-05',
            due_date: '2025-10-22',
            contact: '+639152345678',
            days_until_due: 1
        }
    ];

    const overdueBooks = [{
            student_name: 'David Chen',
            student_id: 'ST-2024-003',
            student_course: 'BS Accountancy - 2nd B',
            item_borrowed: 'Financial Accounting Principles Financial Accounting Principles Financial Accounting Principles',
            date_borrowed: '2025-09-10',
            due_date: '2025-10-10',
            contact: '+639176543210',
            days_overdue: 11
        },
        {
            student_name: 'Emma Wilson',
            student_id: 'ST-2024-009',
            student_course: 'BS Psychology - 3rd A',
            item_borrowed: 'Cognitive Psychology',
            date_borrowed: '2025-09-25',
            due_date: '2025-10-15',
            contact: '+639201234567',
            days_overdue: 6
        },
        {
            student_name: 'James Rodriguez',
            student_id: 'ST-2024-012',
            student_course: 'BS Marketing - 1st D',
            item_borrowed: 'Marketing Management',
            date_borrowed: '2025-09-20',
            due_date: '2025-10-12',
            contact: '+639229876543',
            days_overdue: 9
        }
    ];

    const booksNearDueTableBody = document.querySelector('.books-near-due-table tbody');
    const overdueBooksTableBody = document.querySelector('.overdue-books-table tbody');

    function renderBooksNearDueTable(data) {
        booksNearDueTableBody.innerHTML = '';
        if (data.length === 0) {
            booksNearDueTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    No books near due date.
                </td>
            </tr>
        `;
            return;
        }

        data.forEach(book => {
            const row = `
            <tr class="align-middle"> <!-- Vertically centered -->
                <td class="px-6 py-4 align-middle">
                    <div class="font-semibold text-gray-800">${book.student_name}</div>
                    <div class="text-gray-500 text-xs">${book.student_id}</div>
                    <div class="text-gray-500 text-xs">${book.student_course}</div>
                </td>
                <td class="px-6 py-4 align-middle text-gray-800 max-w-[240px] whitespace-normal break-words">
                    ${book.item_borrowed}
                </td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.date_borrowed}</td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.due_date}</td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.contact}</td>
                <td class="px-6 py-4 align-middle">
                    <a href="tel:${book.contact}"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-500">
                        <i class="ph ph-phone text-base mr-1"></i> Contact
                    </a>
                </td>
            </tr>
        `;
            booksNearDueTableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderOverdueBooksTable(data) {
        overdueBooksTableBody.innerHTML = '';
        if (data.length === 0) {
            overdueBooksTableBody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    No overdue books.
                </td>
            </tr>
        `;
            return;
        }

        data.forEach(book => {
            const row = `
            <tr class="align-middle"> <!-- Vertically center cells -->
                <td class="px-6 py-4 align-middle">
                    <div class="font-semibold text-gray-800">${book.student_name}</div>
                    <div class="text-gray-500 text-xs">${book.student_id}</div>
                    <div class="text-gray-500 text-xs">${book.student_course}</div>
                </td>
                <td class="px-6 py-4 align-middle text-gray-800 max-w-[240px] whitespace-normal break-words">
                    ${book.item_borrowed}
                </td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.date_borrowed}</td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.due_date}</td>
                <td class="px-6 py-4 align-middle text-gray-800">${book.contact}</td>
                <td class="px-6 py-4 align-middle">
                    <a href="tel:${book.contact}"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white">
                        <i class="ph ph-phone text-base mr-1"></i> Contact
                    </a>
                </td>
            </tr>
        `;
            overdueBooksTableBody.insertAdjacentHTML('beforeend', row);
        });
    }


    // Initial render
    renderBooksNearDueTable(booksNearDue);
    renderOverdueBooksTable(overdueBooks);
});