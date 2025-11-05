// --- DOM Elements ---
const recordsContainer = document.getElementById('recordsContainer');
const loadingIndicator = document.getElementById('loadingIndicator');
const paginationContainer = document.getElementById('paginationContainer');

// --- Stats Elements ---
const statTotal = document.getElementById('statTotal');
const statCurrent = document.getElementById('statCurrent');
const statOverdue = document.getElementById('statOverdue');
const statReturned = document.getElementById('statReturned');

// --- Pagination State ---
let currentPage = 1;
// --- Page Memory ---
try {
    const savedPage = sessionStorage.getItem('facultyBorrowingHistoryPage');
    if (savedPage) {
        const parsedPage = parseInt(savedPage, 10);
        if (!isNaN(parsedPage) && parsedPage > 0) {
            currentPage = parsedPage;
        } else {
            sessionStorage.removeItem('facultyBorrowingHistoryPage');
        }
    }
} catch (e) {
    console.error("SessionStorage Error:", e);
    currentPage = 1;
}
const limit = 5;

// --- Render Functions ---
function renderBorrowingTable(records) {
  if (!records || records.length === 0) {
    recordsContainer.innerHTML = `<div class="text-center py-10 text-gray-500">No borrowing records found.</div>`;
    return;
  }

  const html = records.map(record => {
    const returnedBoxClass = record.statusText === 'Returned' ? 'bg-[var(--color-green-50)]' : 'bg-[var(--color-gray-100)]';
    const returnedIconClass = record.returnedDate !== 'Not returned' ? 'text-[var(--color-green-600)]' : 'text-gray-400';
    const overdueClass = record.isOverdue ? 'bg-red-50 border-red-200' : 'border-[var(--color-border)]';

    return `
      <div class="relative rounded-lg p-4 border ${overdueClass} bg-[var(--color-card)] shadow-sm">
          <span class="absolute top-3 right-3 ${record.statusBgClass} text-xs font-medium px-3 py-1 rounded-full">
              ${record.statusText}
          </span>
          <h4 class="font-semibold text-[var(--color-foreground)] pr-24">${record.title}</h4>
          <p class="text-sm text-gray-600 mb-3">by ${record.author}</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-sm">
              <div class="bg-orange-50 p-2 rounded"><i class="ph ph-book text-orange-600 mr-1"></i>Borrowed<br><span class="font-medium">${record.borrowedDate}</span></div>
              <div class="bg-gray-100 p-2 rounded"><i class="ph ph-calendar-blank text-gray-600 mr-1"></i>Due Date<br><span class="font-medium">${record.dueDate}</span></div>
              <div class="${returnedBoxClass} p-2 rounded"><i class="ph ph-check-circle ${returnedIconClass} mr-1"></i>Returned<br><span class="font-medium">${record.returnedDate}</span></div>
              <div class="bg-gray-100 p-2 rounded"><i class="ph ph-user text-gray-600 mr-1"></i>Librarian<br><span class="font-medium">${record.librarianName || 'N/A'}</span></div>
          </div>
      </div>`;
  }).join('');

  recordsContainer.innerHTML = html;
}

function renderPagination(totalPages, currentPage) {
  const paginationContainer = document.getElementById("paginationContainer");
  if (totalPages <= 1) {
    paginationContainer.innerHTML = '';
    return;
  }

  let paginationHTML = `
    <div class="flex items-center justify-center">
      <ul class="flex items-center font-medium justify-center space-x-1 bg-white border border-gray-200 rounded-full px-2 py-1 shadow-sm max-w-[420px] overflow-x-auto scrollbar-hide">
  `;

  // Previous Button
  paginationHTML += `
    <li>
      <a href="#" data-page="${currentPage - 1}"
        class="pagination-link flex items-center gap-1 px-3 py-1.5 text-sm text-gray-500 hover:text-gray-700 
        ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
        <i class="ph ph-caret-left"></i> Previous
      </a>
    </li>
  `;

  // Ellipsis logic
  const createPageButton = (page, active = false) => `
    <li>
      <a href="#" data-page="${page}"
        class="pagination-link w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium transition 
        ${active ? 'bg-orange-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'}">
        ${page}
      </a>
    </li>
  `;

  let startPage = Math.max(1, currentPage - 1);
  let endPage = Math.min(totalPages, currentPage + 1);

  if (currentPage <= 2) endPage = Math.min(3, totalPages);
  if (currentPage >= totalPages - 1) startPage = Math.max(totalPages - 2, 1);

  // Always show first page
  if (startPage > 1) {
    paginationHTML += createPageButton(1, currentPage === 1);
    if (startPage > 2) paginationHTML += `<li><span class="px-2 text-gray-400">...</span></li>`;
  }

  // Middle pages
  for (let i = startPage; i <= endPage; i++) {
    paginationHTML += createPageButton(i, i === currentPage);
  }

  // Always show last page
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) paginationHTML += `<li><span class="px-2 text-gray-400">...</span></li>`;
    paginationHTML += createPageButton(totalPages, currentPage === totalPages);
  }

  // Next Button
  paginationHTML += `
    <li>
      <a href="#" data-page="${currentPage + 1}"
        class="pagination-link flex items-center gap-1 px-3 py-1.5 text-sm text-gray-500 hover:text-gray-700 
        ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}">
        Next <i class="ph ph-caret-right"></i>
      </a>
    </li>
  `;

  paginationHTML += `
      </ul>
    </div>
  `;

  paginationContainer.innerHTML = paginationHTML;
}


// --- Data Fetching ---
async function fetchBorrowingData(page = 1) {
  // --- Page Memory ---
  try {
      sessionStorage.setItem('facultyBorrowingHistoryPage', page);
  } catch (e) {
      console.error("SessionStorage Error:", e);
  }

  if (typeof BASE_URL_JS === 'undefined') {
    recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">Configuration error.</div>`;
    return;
  }

  recordsContainer.innerHTML = `<div class="text-center py-10 text-gray-500">Loading history...</div>`;
  paginationContainer.innerHTML = '';

  try {
    const response = await fetch(`${BASE_URL_JS}/api/faculty/borrowing-history/pagination?page=${page}&limit=${limit}`);
    if (!response.ok) throw new Error('Network response was not ok');
    
    const data = await response.json();

    if (data.success) {
      renderBorrowingTable(data.borrowingHistory);
      renderPagination(data.totalPages, data.currentPage);
      // Update stats only on first page load for efficiency
      if (page === 1) {
        fetchStats();
      }
    } else {
      recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">${data.message || 'Failed to load history.'}</div>`;
    }
  } catch (error) {
    recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">Network error. Please try again.</div>`;
    console.error('Fetch error:', error);
  }
}

async function fetchStats() {
    try {
        const response = await fetch(`${BASE_URL_JS}/api/faculty/borrowing-history/stats`);
        const data = await response.json();
        if(data.success && data.stats) {
            statTotal.textContent = data.stats.total_borrowed;
            statCurrent.textContent = data.stats.currently_borrowed;
            statOverdue.textContent = data.stats.total_overdue;
            statReturned.textContent = data.stats.total_returned;
        }
    } catch (error) {
        console.error('Failed to fetch stats:', error);
    }
}

// --- Event Listeners ---
document.addEventListener('DOMContentLoaded', () => {
  fetchBorrowingData(currentPage);

  paginationContainer.addEventListener('click', (e) => {
    e.preventDefault();
    const link = e.target.closest('.pagination-link');
    if (link && !link.classList.contains('cursor-not-allowed')) {
      const page = parseInt(link.dataset.page, 10);
      if (!isNaN(page)) {
        fetchBorrowingData(page);
      }
    }
  });
});
