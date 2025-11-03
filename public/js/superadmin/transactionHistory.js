document.addEventListener('DOMContentLoaded', () => {
    // --- DOM Elements ---
    const statusBtn = document.getElementById('statusFilterBtn');
    const statusMenu = document.getElementById('statusFilterMenu');
    const statusValue = document.getElementById('statusFilterValue');
    const tableBody = document.getElementById('transactionHistoryTableBody');
    const rowTemplate = document.getElementById('transaction-row-template').content;
    const searchInput = document.getElementById('transactionSearchInput');
    const dateInput = document.getElementById('transactionDate');
    const resultsIndicator = document.getElementById('resultsIndicator');
    const paginationControls = document.getElementById('paginationControls');
    const paginationList = document.getElementById('paginationList');
    const modal = document.getElementById('transactionDetailsModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // --- State ---
    let transactions = [];
    let searchDebounce;
    const limit = 10;
    let currentPage = 1;
    let totalPages = 1;
    let totalRecords = 0;
    let isLoading = false;

    // --- Main Data Loading Function (Rebuilt to match User Management) ---
    async function loadTransactions(page = 1) {
        if (isLoading) return;
        isLoading = true;
        currentPage = page;

        // Show loading spinner
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-12"><i class="ph ph-spinner animate-spin text-3xl text-orange-500"></i></td></tr>`;
        resultsIndicator.textContent = 'Loading...';
        paginationControls.classList.add('hidden');

        const offset = (page - 1) * limit;
        const status = statusValue.textContent.trim();
        const search = searchInput.value.trim();
        const date = dateInput.value;

        try {
            const params = new URLSearchParams({
                page: currentPage,
                limit: limit,
                status: status === 'All Status' ? 'all' : status,
                search: search,
                date: date
            });

            const response = await fetch(`api/superadmin/transactionHistory/json?${params.toString()}`);
            const result = await response.json();

            if (!result.success) throw new Error(result.message || 'Failed to load data');

            transactions = result.data;
            totalRecords = result.totalRecords;
            totalPages = result.totalPages;
            currentPage = result.currentPage;

            renderTable(transactions);
            renderPagination();
            updateCounts();

        } catch (error) {
            console.error('Error fetching transactions:', error);
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-12 text-red-500">Failed to load transactions. Please try again.</td></tr>`;
            resultsIndicator.textContent = 'Error';
        } finally {
            isLoading = false;
        }
    }

    // --- UI Rendering ---
    function renderTable(data) {
        tableBody.innerHTML = '';
        if (data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <div class="p-4 bg-orange-50 rounded-full mb-4">
                                <i class="ph ph-clock text-4xl text-orange-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">No Transactions Found</h3>
                            <p class="text-sm">There are no activities matching the current filters.</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        data.forEach(transaction => {
            const newRow = rowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.transactionId = transaction.transaction_id;
            tr.classList.add('transaction-row');

            const cells = newRow.querySelectorAll('td');
            cells[0].textContent = transaction.user_name || 'N/A';
            cells[1].textContent = transaction.user_id_number || 'N/A';
            cells[2].textContent = transaction.accession_number || 'N/A';
            cells[3].textContent = transaction.borrowed_at || '';
            cells[4].textContent = transaction.returned_at || 'Not yet returned';

            const statusCell = cells[5].querySelector('span');
            const statusText = (transaction.transaction_status || '').toLowerCase();
            statusCell.textContent = transaction.transaction_status || 'N/A';
            statusCell.className = 'px-3 py-1 rounded-full font-medium text-xs';

            if (statusText === 'borrowed') statusCell.classList.add('bg-red-100', 'text-red-800');
            else if (statusText === 'returned') statusCell.classList.add('bg-green-100', 'text-green-800');
            else if (statusText === 'expired') statusCell.classList.add('bg-gray-100', 'text-gray-800');
            else statusCell.classList.add('bg-gray-100', 'text-gray-500');

            tableBody.appendChild(newRow);
        });
    }

    function updateCounts() {
        if (totalRecords > 0) {
            const start = (currentPage - 1) * limit + 1;
            const end = Math.min(currentPage * limit, totalRecords);
            resultsIndicator.textContent = `Showing ${start}-${end} of ${totalRecords} results`;
        } else {
            resultsIndicator.textContent = 'No results found';
        }
    }

    // --- Pagination Rendering (Matches User Management, with ellipsis and no icons) ---
    function renderPagination() {
        paginationList.innerHTML = '';
        if (totalPages <= 1) {
            paginationControls.classList.add('hidden');
            return;
        }
        paginationControls.classList.remove('hidden');

        const createLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.innerHTML = text;
            a.setAttribute('data-page', pageNum);

            let classes = 'flex items-center text-sm font-medium justify-center transition-colors duration-200';

            if (type === 'prev' || type === 'next') {
                classes += isDisabled ? ' text-gray-400 cursor-not-allowed' : ' text-gray-600 hover:text-orange-600';
            } else if (type === 'ellipsis') {
                classes += ' text-gray-500 cursor-default'; // Ellipsis should not be clickable
                a.removeAttribute('href'); // Make it non-clickable
                a.removeAttribute('data-page');
            } else { // Page number
                classes += ' w-9 h-9 rounded-full';
                classes += isActive ? ' bg-orange-500 text-white shadow-sm' : ' text-gray-700 hover:bg-orange-50';
            }
            a.className = classes;
            li.appendChild(a);
            return li;
        };

        // Previous button (no icon)
        paginationList.appendChild(createLink("prev", `<i class="flex ph ph-caret-left text-lg"></i>Previous`, currentPage - 1, currentPage === 1));

        // Page numbers with ellipsis logic
        const windowSize = 1; // Number of pages to show around the current page
        let pagesToShow = new Set([1, totalPages, currentPage]);

        for (let i = 1; i <= windowSize; i++) {
            if (currentPage - i > 0) pagesToShow.add(currentPage - i);
            if (currentPage + i <= totalPages) pagesToShow.add(currentPage + i);
        }

        const sortedPages = [...pagesToShow].sort((a, b) => a - b);

        let lastPage = 0;
        for (const p of sortedPages) {
            if (p > lastPage + 1) {
                paginationList.appendChild(createLink("ellipsis", "…", "...", true)); // Ellipsis
            }
            paginationList.appendChild(createLink("number", p, p, false, p === currentPage));
            lastPage = p;
        }

        // Next button (no icon)
        paginationList.appendChild(createLink("next", `Next<i class="flex ph ph-caret-right text-lg">`, currentPage + 1, currentPage === totalPages));
    }

    // --- Event Listeners ---
    function setupEventListeners() {
        statusMenu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                statusValue.textContent = item.dataset.value;
                statusMenu.classList.add('hidden');
                loadTransactions(1);
            });
        });

        statusBtn.addEventListener('click', e => {
            e.stopPropagation();
            statusMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => statusMenu.classList.add('hidden'));

        searchInput.addEventListener('input', () => {
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(() => loadTransactions(1), 500);
        });

        dateInput.addEventListener('change', () => loadTransactions(1));

        paginationList.addEventListener('click', e => {
            e.preventDefault();
            const target = e.target.closest('a[data-page]');
            if (!target || target.classList.contains('cursor-not-allowed')) return;
            const page = parseInt(target.dataset.page, 10);
            if (page !== currentPage) {
                loadTransactions(page);
            }
        });

        closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
        modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

        tableBody.addEventListener('click', e => {
            const row = e.target.closest('.transaction-row');
            if (!row) return;
            const transactionId = parseInt(row.dataset.transactionId, 10);
            const transaction = transactions.find(t => t.transaction_id === transactionId);
            if (transaction) openModalWithTransaction(transaction);
        });
    }

    function openModalWithTransaction(transaction) {
        document.getElementById('modalStudentName').textContent = transaction.user_name || 'N/A';
        document.getElementById('modalStudentId').textContent = transaction.user_id_number || 'N/A';
        document.getElementById('modalCourse').textContent = transaction.course_code ? `${transaction.course_code} - ${transaction.course_title}` : 'N/A';
        document.getElementById('modalYear').textContent = transaction.year_level || 'N/A';
        document.getElementById('modalSection').textContent = transaction.section || 'N/A';
        document.getElementById('modalItemTitle').textContent = transaction.book_title || 'N/A';
        document.getElementById('modalItemAuthor').textContent = transaction.book_author || 'N/A';
        document.getElementById('modalItemAccession').textContent = transaction.accession_number || 'N/A';
        document.getElementById('modalItemCallNo').textContent = transaction.call_number || 'N/A';
        document.getElementById('modalItemISBN').textContent = transaction.book_isbn || 'N/A';
        document.getElementById('modalBorrowedDate').textContent = transaction.borrowed_at || 'N/A';
        document.getElementById('modalReturnedDate').textContent = transaction.returned_at || 'Not yet returned';
        document.getElementById('modalProcessedBy').textContent = transaction.librarian_name || 'N/A';

        const statusEl = document.getElementById('modalStatus');
        statusEl.innerHTML = '';
        const statusSpan = document.createElement('span');
        const statusText = (transaction.transaction_status || '').toLowerCase();
        statusSpan.textContent = (transaction.transaction_status || 'N/A').toUpperCase();
        statusSpan.className = 'font-semibold tracking-wide';

        if (statusText === 'borrowed') statusSpan.classList.add('text-orange-600');
        else if (statusText === 'returned') statusSpan.classList.add('text-green-600');
        else if (statusText === 'expired') statusSpan.classList.add('text-gray-600');

        statusEl.appendChild(statusSpan);
        modal.classList.remove('hidden');
    }

    // --- Initial Load ---
    const today = new Date();
    dateInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    setupEventListeners();
    loadTransactions();
});
