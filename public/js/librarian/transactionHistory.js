document.addEventListener('DOMContentLoaded', () => {
    const statusBtn = document.getElementById('statusFilterBtn');
    const statusMenu = document.getElementById('statusFilterMenu');
    const statusValue = document.getElementById('statusFilterValue');
    const tableBody = document.getElementById('transactionHistoryTableBody');
    const noTransactionsMessage = document.getElementById('no-transactions-found');
    const rowTemplate = document.getElementById('transaction-row-template').content;
    const paginationContainer = document.getElementById('pagination-container');
    const paginationNumbers = document.getElementById('pagination-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const modal = document.getElementById('transactionDetailsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    let currentPage = 1;
    const rowsPerPage = 5;

    // Dummy data - replace with actual data from your backend
    const transactions = Array.from({ length: 25 }, (_, i) => ({
        id: i,
        studentName: `Student ${i + 1}`,
        studentNumber: `202300${i + 1}-S`,
        course: 'BSCS',
        year: `${(i % 4) + 1}`,
        section: `A`,
        itemsBorrowed: (i % 3) + 1,
        borrowedDate: `Oct ${24 - (i % 5)}, 2025 ${11 - (i % 6)}:${(i % 60).toString().padStart(2, '0')} PM`,
        returnedDate: i % 2 === 0 ? 'Not yet returned' : `Oct ${25 - (i % 2)}, 2025 ${1 - (i % 12)}:${(i % 60).toString().padStart(2, '0')} PM`,
        status: i % 2 === 0 ? 'Borrowed' : 'Returned',
        title: 'Introduction to Computer Science',
        author: 'John Smith',
        accession: `123${i}`,
        callNo: `CS-100.S45${i}`,
        isbn: `978-0-123456-78-${i}`,
        processedBy: 'Sarah Chen'
    }));

    // Toggle dropdown
    statusBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        statusMenu.classList.toggle('hidden');
    });

    // Click outside to close
    document.addEventListener('click', () => {
        statusMenu.classList.add('hidden');
    });

    // Select dropdown item
    statusMenu.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            statusValue.textContent = item.dataset.value;
            statusMenu.classList.add('hidden');
        });
    });

    function renderTable(data, page) {
        tableBody.innerHTML = '';
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = data.slice(start, end);

        if (paginatedData.length === 0 && page === 1) {
            tableBody.appendChild(noTransactionsMessage.cloneNode(true));
            paginationContainer.classList.add('hidden');
            return;
        }

        paginationContainer.classList.remove('hidden');
        paginatedData.forEach(transaction => {
            const newRow = rowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.transactionId = transaction.id;

            const cells = newRow.querySelectorAll('td');
            cells[0].textContent = transaction.studentName;
            cells[1].textContent = transaction.studentNumber;
            cells[2].textContent = transaction.itemsBorrowed;
            cells[3].textContent = transaction.borrowedDate;
            cells[4].textContent = transaction.returnedDate;

            const statusCell = cells[5].querySelector('span');
            statusCell.textContent = transaction.status;

            if (transaction.status === 'Borrowed') {
                statusCell.classList.add('bg-red-100', 'text-red-800');
            } else if (transaction.status === 'Returned') {
                statusCell.classList.add('bg-green-100', 'text-green-800');
            }

            tableBody.appendChild(newRow);
        });
    }

    function renderPagination(data) {
        paginationNumbers.innerHTML = '';
        const pageCount = Math.ceil(data.length / rowsPerPage);

        // Previous button state
        prevPageBtn.classList.toggle('text-gray-400', currentPage === 1);
        prevPageBtn.classList.toggle('hover:text-orange-600', currentPage !== 1);

        // Next button state
        nextPageBtn.classList.toggle('text-gray-400', currentPage === pageCount);
        nextPageBtn.classList.toggle('hover:text-orange-600', currentPage !== pageCount);

        // Page numbers
        for (let i = 1; i <= pageCount; i++) {
            const pageNumber = document.createElement('a');
            pageNumber.href = '#';
            pageNumber.textContent = i;
            pageNumber.classList.add('px-4', 'py-1.5', 'rounded-full', 'transition', 'duration-200');

            if (i === currentPage) {
                pageNumber.classList.add('bg-orange-500', 'text-white', 'shadow-sm');
            } else {
                pageNumber.classList.add(
                    'text-gray-700',
                    'hover:text-orange-600',
                    'hover:bg-orange-50'
                );
            }

            pageNumber.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                renderTable(transactions, currentPage);
                renderPagination(transactions);
            });

            paginationNumbers.appendChild(pageNumber);
        }
    }

    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderTable(transactions, currentPage);
            renderPagination(transactions);
        }
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const pageCount = Math.ceil(transactions.length / rowsPerPage);
        if (currentPage < pageCount) {
            currentPage++;
            renderTable(transactions, currentPage);
            renderPagination(transactions);
        }
    });

    // Modal logic
    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    tableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.transaction-row');
        if (row) {
            const transactionId = parseInt(row.dataset.transactionId, 10);
            const transaction = transactions.find(t => t.id === transactionId);
            if (transaction) {
                openModalWithTransaction(transaction);
            }
        }
    });

    function openModalWithTransaction(transaction) {
        // Student Info
        document.getElementById('modalStudentName').textContent = transaction.studentName;
        document.getElementById('modalStudentId').textContent = transaction.studentNumber;
        document.getElementById('modalCourse').textContent = transaction.course;
        document.getElementById('modalYear').textContent = transaction.year;
        document.getElementById('modalSection').textContent = transaction.section;

        // Item Info
        document.getElementById('modalItemTitle').textContent = transaction.title;
        document.getElementById('modalItemAuthor').textContent = transaction.author;
        document.getElementById('modalItemAccession').textContent = transaction.accession;
        document.getElementById('modalItemCallNo').textContent = transaction.callNo;
        document.getElementById('modalItemISBN').textContent = transaction.isbn;

        // Transaction Details
        document.getElementById('modalBorrowedDate').textContent = transaction.borrowedDate;
        document.getElementById('modalReturnedDate').textContent = transaction.returnedDate;
        document.getElementById('modalProcessedBy').textContent = transaction.processedBy;

        const statusEl = document.getElementById('modalStatus');
        statusEl.innerHTML = '';

        const statusSpan = document.createElement('span');
        statusSpan.textContent = transaction.status.toUpperCase();
        statusSpan.classList.add('font-semibold', 'tracking-wide');

        if (transaction.status === 'Borrowed') {
            statusSpan.classList.add('text-orange-600');
        } else if (transaction.status === 'Returned') {
            statusSpan.classList.add('text-green-600');
        }
        statusEl.appendChild(statusSpan);

        modal.classList.remove('hidden');
    }

    // Initial render
    renderTable(transactions, currentPage);
    renderPagination(transactions);
});