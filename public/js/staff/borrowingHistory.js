const recordsContainer = document.getElementById('recordsContainer');

const statTotal = document.getElementById('statTotal');
const statCurrent = document.getElementById('statCurrent');
const statOverdue = document.getElementById('statOverdue');
const statReturned = document.getElementById('statReturned');

function renderRecords(records) {
  if (records.length === 0) {
    recordsContainer.innerHTML = `<div class="text-center py-10 text-gray-500">No borrowing records found.</div>`;
    return;
  }

  let html = records.map(record => {

    let returnedBoxClass = (record.statusText === 'Returned') ? 'bg-[var(--color-green-50)]' : 'bg-[var(--color-gray-100)]';
    let returnedIconClass = (record.returnedDate !== 'Not returned') ? 'text-[var(--color-green-600)]' : 'text-gray-400';

    if (record.isOverdue) returnedBoxClass = 'bg-[var(--color-gray-100)]';

    return `
      <div class="relative rounded-lg p-4 border border-[var(--color-border)] bg-[var(--color-card)] shadow-sm">
          <span class="absolute top-3 right-3 ${record.statusBgClass} text-xs font-medium px-3 py-1 rounded-full">
              ${record.statusText}
          </span>

          <h4 class="font-semibold text-[var(--color-foreground)]">${record.title}</h4>
          <p class="text-sm text-gray-600 mb-3">by ${record.author}</p>

          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
              <div class="bg-[var(--color-orange-50)] p-2 rounded text-sm">
                  <i class="ph ph-book text-[var(--color-orange-600)] mr-1"></i>
                  Borrowed<br><span class="font-medium">${record.borrowedDate}</span>
              </div>
              <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                  <i class="ph ph-calendar-blank text-gray-600 mr-1"></i>
                  Due Date<br><span class="font-medium">${record.dueDate}</span>
              </div>
              <div class="${returnedBoxClass} p-2 rounded text-sm">
                  <i class="ph ph-check-circle ${returnedIconClass} mr-1"></i>
                  Returned<br><span class="font-medium">${record.returnedDate}</span>
              </div>
              <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                  <i class="ph ph-user text-gray-600 mr-1"></i>
                  Librarian<br><span class="font-medium">${record.librarianName || 'N/A'}</span>
              </div>
          </div>
      </div>
    `;
  }).join('');

  recordsContainer.innerHTML = html;
}

function fetchBorrowingHistory() {

  if (typeof BASE_URL_JS === 'undefined' || typeof CURRENT_STAFF_ID === 'undefined' || CURRENT_STAFF_ID === 0) {
    recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">Error: Staff user session not found. Please log in again.</div>`;
    return;
  }

  recordsContainer.innerHTML = `<div class="text-center py-10 text-gray-500">Loading history...</div>`;

  const url = `${BASE_URL_JS}api/staff/borrowingHistory/fetch?staff_id=${CURRENT_STAFF_ID}`;

  fetch(url)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        statTotal.textContent = data.stats.total_borrowed;
        statCurrent.textContent = data.stats.currently_borrowed;
        statOverdue.textContent = data.stats.total_overdue;
        statReturned.textContent = data.stats.total_returned;

        renderRecords(data.history);
      } else {
        recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">${data.message || 'Failed to load borrowing history.'}</div>`;
      }
    })
    .catch(() => {
      recordsContainer.innerHTML = `<div class="text-center py-10 text-red-500">Network error. Could not connect to the server.</div>`;
    });
}

document.addEventListener('DOMContentLoaded', () => {
  fetchBorrowingHistory();
});
