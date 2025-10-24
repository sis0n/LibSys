document.addEventListener('DOMContentLoaded', function() {

    const booksNearDueTableBody = document.querySelector('.books-near-due-table tbody');
    const overdueBooksTableBody = document.querySelector('.overdue-books-table tbody');
    // Borrowed Book Modal
    const returnModal = document.getElementById('return-modal');
    const closeButton = document.getElementById('modal-close-button');
    const cancelButton = document.getElementById('modal-cancel-button');
    // Available Book Modal
    const availableBookModal = document.getElementById('available-book-modal');
    const availableModalCloseButton = document.getElementById('available-modal-close-button');
    const availableModalCloseAction = document.getElementById('available-modal-close-action');

    const scanButton = document.getElementById('scan-button');

    // Dummy data for demonstration
    const booksNearDue = [{
            student_name: 'Alex Johnson',
            student_id: 'ST-2024-001',
            student_course: 'BS Computer Science - 3rd A',
            item_borrowed: 'Introduction to Algorithms',
            date_borrowed: '2025-09-15',
            due_date: '2025-10-25',
            contact: '+63 912 345 6789',
            email: 'alex.johnson@student.ucc.edu',
            author: 'Thomas H. Cormen',
            isbn: '978-0-262-03384-8',
            book_id: 'BK-2024-001',
            status: 'borrowed' // Added status
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
            item_borrowed: 'Financial Accounting Principles',
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

    const availableBook = {
        title: 'Psychology',
        author: 'Carole Wade, Carol Tavris',
        status: 'available',
        call_number: 'PSY-150.W123',
        accession_number: '1426',
        isbn: '70572720',
        subject: 'Psychology.',
        place: 'USA',
        publisher: 'The McGraw-Hill Companies, Inc.',
        year: '1997',
        edition: '4th',
        supplementary: 'N/A',
        description: '524 pages ; illustrations ; 25 cm'
    };



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
                        class="inline-flex items-center px-3 py-1.5 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
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

    // --- MODAL LOGIC ---
    // Function to open the BORROWED book modal
    const openReturnModal = (bookData) => {
        document.getElementById('modal-book-title').textContent = bookData.item_borrowed;
        document.getElementById('modal-book-status').textContent = 'borrowed';
        document.getElementById('modal-book-author').textContent = bookData.author || 'N/A';
        document.getElementById('modal-book-isbn').textContent = bookData.isbn || 'N/A';
        document.getElementById('modal-book-id').textContent = bookData.book_id || 'N/A';
        document.getElementById('modal-book-callnumber').textContent = bookData.call_number || 'N/A';

        document.getElementById('modal-borrower-name').textContent = bookData.student_name;
        document.getElementById('modal-student-id').textContent = bookData.student_id;

        const course_parts = bookData.student_course.split(' - ');
        document.getElementById('modal-borrower-course').textContent = course_parts[0];
        document.getElementById('modal-borrower-year-section').textContent = course_parts[1];

        document.getElementById('modal-borrower-email').textContent = bookData.email;
        document.getElementById('modal-borrower-contact').textContent = bookData.contact;
        document.getElementById('modal-borrow-date').textContent = bookData.date_borrowed;
        document.getElementById('modal-due-date').textContent = bookData.due_date;

        returnModal.classList.remove('hidden');
    };

    // Function to open the AVAILABLE book modal
    const openAvailableModal = (bookData) => {
        document.getElementById('available-modal-title').textContent = bookData.title || 'N/A';
        document.getElementById('available-modal-author').textContent = bookData.author || 'N/A';
        const statusEl = document.getElementById('available-modal-status');
        statusEl.textContent = bookData.status;
        if (bookData.status === 'available') {
            statusEl.className = 'bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-full';
        } else {
            statusEl.className = 'bg-orange-200 text-orange-800 text-md font-semibold px-3 py-1 rounded-full';
        }
        document.getElementById('available-modal-call-number').textContent = bookData.call_number || 'N/A';
        document.getElementById('available-modal-accession').textContent = bookData.accession_number || 'N/A';
        document.getElementById('available-modal-isbn').textContent = bookData.isbn || 'N/A';
        document.getElementById('available-modal-subject').textContent = bookData.subject || 'N/A';
        document.getElementById('available-modal-place').textContent = bookData.place || 'N/A';
        document.getElementById('available-modal-publisher').textContent = bookData.publisher || 'N/A';
        document.getElementById('available-modal-year').textContent = bookData.year || 'N/A';
        document.getElementById('available-modal-edition').textContent = bookData.edition || 'N/A';
        document.getElementById('available-modal-supplementary').textContent = bookData.supplementary || 'N/A';
        document.getElementById('available-modal-description').textContent = bookData.description || 'N/A';

        availableBookModal.classList.remove('hidden');
    };

    const closeReturnModal = () => {
        if (returnModal) returnModal.classList.add('hidden');
    };

    const closeAvailableModal = () => {
        if (availableBookModal) availableBookModal.classList.add('hidden');
    };

    if (scanButton) {
        scanButton.addEventListener('click', (e) => {
            // SIMULATION: Click with ALT key to show borrowed modal, otherwise show available modal.
            if (e.altKey) {
                openReturnModal(booksNearDue[0]);
            } else {
                openAvailableModal(availableBook);
            }
        });
    }

    // Event listeners for closing modals
    if (closeButton) closeButton.addEventListener('click', closeReturnModal);
    if (cancelButton) cancelButton.addEventListener('click', closeReturnModal);
    if (returnModal) returnModal.addEventListener('click', (event) => {
        if (event.target === returnModal) closeReturnModal();
    });

    if (availableModalCloseButton) availableModalCloseButton.addEventListener('click', closeAvailableModal);
    if (availableModalCloseAction) availableModalCloseAction.addEventListener('click', closeAvailableModal);
    if (availableBookModal) availableBookModal.addEventListener('click', (event) => {
        if (event.target === availableBookModal) closeAvailableModal();
    });
});