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

    const searchInput = document.getElementById('transactionSearchInput');
    const dateInput = document.getElementById('transactionDate');

    let currentPage = 1;
    const rowsPerPage = 5;
    let allTransactions = [];
    let currentFilteredTransactions = [];

    async function loadTransactions() {
    try {
        const response = await fetch(`${BASE_URL}superadmin/transactionHistory/json`);
        allTransactions = await response.json();
        currentFilteredTransactions = allTransactions;
        applyAndRenderFilters();
    } catch (error) {
        console.error('Error fetching transactions:', error);
    }
}

    function applyAndRenderFilters() {
        const status = statusValue.textContent;
        const searchTerm = searchInput.value.toLowerCase();
        const date = dateInput.value;

        currentFilteredTransactions = allTransactions.filter(transaction => {
            const statusMatch = status === 'All Status' || (transaction.transaction_status && transaction.transaction_status.toLowerCase() === status.toLowerCase());
            const searchMatch = !searchTerm ||
                (transaction.studentName && transaction.studentName.toLowerCase().includes(searchTerm)) ||
                (transaction.studentNumber && transaction.studentNumber.toLowerCase().includes(searchTerm)) ||
                (transaction.book_title && transaction.book_title.toLowerCase().includes(searchTerm));
            const dateMatch = !date ||
                (transaction.borrowed_at && transaction.borrowed_at.startsWith(date)) ||
                (transaction.returned_at && transaction.returned_at.startsWith(date));

            return statusMatch && searchMatch && dateMatch;
        });

        currentPage = 1;
        renderTable(currentFilteredTransactions, currentPage);
        renderPagination(currentFilteredTransactions);
    }

    // Status dropdown toggle
    statusBtn.addEventListener('click', e => {
        e.stopPropagation();
        statusMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => statusMenu.classList.add('hidden'));

    statusMenu.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            statusValue.textContent = item.dataset.value;
            statusMenu.classList.add('hidden');
            applyAndRenderFilters();
        });
    });

    searchInput.addEventListener('input', applyAndRenderFilters);
    dateInput.addEventListener('change', applyAndRenderFilters);

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
            tr.dataset.transactionId = transaction.transaction_id;

            const cells = newRow.querySelectorAll('td');
            cells[0].textContent = transaction.studentName || transaction.student_number;
            cells[1].textContent = transaction.studentNumber || transaction.student_number;
            cells[2].textContent = transaction.itemsBorrowed || 1;
            cells[3].textContent = transaction.borrowed_at || '';
            cells[4].textContent = transaction.returned_at ? transaction.returned_at : 'Not yet returned';

            const statusCell = cells[5].querySelector('span');
            const statusText = transaction.transaction_status ? transaction.transaction_status.toLowerCase() : 'unknown';
            statusCell.textContent = transaction.transaction_status || 'N/A';
            statusCell.className = 'px-3 py-1 rounded-full font-medium text-xs';

            // Remove Pending logic
            if (statusText === 'borrowed') {
                statusCell.classList.add('bg-red-100', 'text-red-800');
            } else if (statusText === 'returned') {
                statusCell.classList.add('bg-green-100', 'text-green-800');
            } else if (statusText === 'expired') {
                statusCell.classList.add('bg-gray-100', 'text-gray-800');
            } else {
                statusCell.classList.add('bg-gray-100', 'text-gray-500');
            }

            tableBody.appendChild(newRow);
        });
    }

    // Pagination
    function renderPagination(data) {
        paginationNumbers.innerHTML = '';
        const pageCount = Math.ceil(data.length / rowsPerPage);

        prevPageBtn.classList.toggle('text-gray-400', currentPage === 1);
        prevPageBtn.classList.toggle('hover:text-orange-600', currentPage !== 1);
        nextPageBtn.classList.toggle('text-gray-400', currentPage === pageCount);
        nextPageBtn.classList.toggle('hover:text-orange-600', currentPage !== pageCount);

        for (let i = 1; i <= pageCount; i++) {
            const pageNumber = document.createElement('a');
            pageNumber.href = '#';
            pageNumber.textContent = i;
            pageNumber.classList.add('px-4', 'py-1.5', 'rounded-full', 'transition', 'duration-200');
            if (i === currentPage) pageNumber.classList.add('bg-orange-500', 'text-white', 'shadow-sm');
            else pageNumber.classList.add('text-gray-700', 'hover:text-orange-600', 'hover:bg-orange-50');

            pageNumber.addEventListener('click', e => {
                e.preventDefault();
                currentPage = i;
                renderTable(currentFilteredTransactions, currentPage);
                renderPagination(currentFilteredTransactions);
            });
            paginationNumbers.appendChild(pageNumber);
        }
    }

    prevPageBtn.addEventListener('click', e => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderTable(currentFilteredTransactions, currentPage);
            renderPagination(currentFilteredTransactions);
        }
    });

    nextPageBtn.addEventListener('click', e => {
        e.preventDefault();
        const pageCount = Math.ceil(currentFilteredTransactions.length / rowsPerPage);
        if (currentPage < pageCount) {
            currentPage++;
            renderTable(currentFilteredTransactions, currentPage);
            renderPagination(currentFilteredTransactions);
        }
    });

    // Modal
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

    tableBody.addEventListener('click', e => {
        const row = e.target.closest('.transaction-row');
        if (!row) return;
        const transactionId = parseInt(row.dataset.transactionId, 10);
        const transaction = allTransactions.find(t => t.transaction_id === transactionId);
        if (transaction) openModalWithTransaction(transaction);
    });

    function openModalWithTransaction(transaction) {
        document.getElementById('modalStudentName').textContent = transaction.studentName || '';
        document.getElementById('modalStudentId').textContent = transaction.student_number ?? '-';
        document.getElementById('modalCourse').textContent = transaction.course || '';
        document.getElementById('modalYear').textContent = transaction.year_level || '';
        document.getElementById('modalSection').textContent = transaction.section || '';
        document.getElementById('modalItemTitle').textContent = transaction.book_title || '';
        document.getElementById('modalItemAuthor').textContent = transaction.book_author || '';
        document.getElementById('modalItemAccession').textContent = transaction.accession_number || '';
        document.getElementById('modalItemCallNo').textContent = transaction.call_number || '';
        document.getElementById('modalItemISBN').textContent = transaction.book_isbn || '';
        document.getElementById('modalBorrowedDate').textContent = transaction.borrowed_at || '';
        document.getElementById('modalReturnedDate').textContent = transaction.returned_at ?? 'Not yet returned';
        document.getElementById('modalProcessedBy').textContent = transaction.librarian_name ?? 'Not yet processed';

        const statusEl = document.getElementById('modalStatus');
        statusEl.innerHTML = '';
        const statusSpan = document.createElement('span');
        const statusText = transaction.transaction_status ? transaction.transaction_status.toLowerCase() : 'unknown';
        statusSpan.textContent = transaction.transaction_status?.toUpperCase() || 'N/A';
        statusSpan.classList.add('font-semibold', 'tracking-wide');

        if (statusText === 'borrowed') statusSpan.classList.add('text-orange-600');
        else if (statusText === 'returned') statusSpan.classList.add('text-green-600');
        else if (statusText === 'expired') statusSpan.classList.add('text-gray-600');

        statusEl.appendChild(statusSpan);
        modal.classList.remove('hidden');
    }

    // Default date
    const today = new Date();
    dateInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

    loadTransactions();
});
