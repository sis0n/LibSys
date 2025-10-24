const scanResultCard = document.getElementById('scanResultCard');
const transactionHistoryTableBody = document.getElementById('transactionHistoryTableBody');
const basePath = '/LibSys/public';
const defaultAvatar = `${basePath}/img/default_avatar.png`;

const searchInput = document.getElementById('transactionSearchInput');
const dateInput = document.getElementById('transactionDate');
const statusBtn = document.getElementById("statusFilterBtn");
const statusMenu = document.getElementById("statusFilterMenu");
const statusValue = document.getElementById("statusFilterValue");

/**
 * NEW FUNCTION: Nagli-clear ng PHP Session at nire-render ang default state.
 */
function clearLastScan() {
  Swal.fire({
    title: 'Clear Scan Result?',
    text: "The current scanned ticket will be removed from display.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f97316',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, Clear it!'
  }).then((result) => {
    if (result.isConfirmed) {
      // Tumatawag sa PHP Controller para i-unset ang $_SESSION['last_scanned_ticket']
      fetch(`${basePath}/superadmin/qrScanner/clearSession`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
      })
        .then(res => res.json())
        .then(() => {
          // I-reset ang display at i-load ang history
          renderScanResult(null);
          fetchTransactionHistory();
          document.getElementById('scannerInput').focus();
        })
        .catch(() => {
          Swal.fire('Error', 'Failed to communicate with server.', 'error');
        });
    }
  });
}

function renderScanResult(data) {

  if (!data || !data.isValid) {
    // Ito ang magre-render ng default state, na tumutugma sa placeholder HTML
    scanResultCard.innerHTML = `
            <div>
                <h2 class="text-xl font-semibold mb-2">Scan Result</h2>
                <p class="text-gray-500 mb-6">Review ticket details and process transaction</p>
                <div class="text-center py-16" id="initialState">
                    <div class="flex justify-center items-center mb-4">
                        <div class="bg-orange-100 rounded-full w-20 h-20 flex items-center justify-center">
                            <i class="ph ph-x text-4xl text-orange-500"></i>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-700">${data ? data.message : 'No ticket scanned yet'}</p>
                    <p class="text-sm text-gray-500">Present QR code or enter ticket ID manually</p>
                </div>
            </div>
        `;
    return;
  }

  const isBorrowed = data.ticket.status.toLowerCase() === 'borrowed';

  // Gumamit ng buong path na galing sa DB
  let profilePicPath = defaultAvatar;
  if (data.student.profilePicture) {
    // Tinitiyak na may leading slash ang path (kung wala)
    profilePicPath = data.student.profilePicture.startsWith('/')
      ? data.student.profilePicture
      : `/${data.student.profilePicture}`;
  }

  const actionButton = isBorrowed
    ? `<button id="processReturnBtn" data-code="${data.ticket.id}" data-action="return"
              class="w-full bg-green-500 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-green-600 transition">
              Process Return (${data.items.length} Items)
           </button>`
    : `<button id="processBorrowBtn" data-code="${data.ticket.id}" data-action="borrow"
              class="w-full bg-orange-500 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-orange-600 transition">
              Confirm Borrow (${data.items.length} Items)
           </button>`;

  const itemsHtml = data.items.map((item, index) => `
        <li class="mb-3 flex items-start gap-3">
            <span class="text-sm font-semibold text-gray-700 w-6 text-right">${index + 1}.</span>
            <div class="flex-1">
                <p class="font-medium text-gray-800 leading-snug">${item.title}</p>
                <p class="text-sm text-gray-700">${item.author}</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">Accession No: ${item.accessionNumber}</span>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">Call No: ${item.callNumber}</span>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">ISBN: ${item.isbn}</span>
                </div>
            </div>
        </li>
    `).join('');

  scanResultCard.innerHTML = `
        <div class="flex flex-col flex-grow">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Scan Result</h2>
                <button id="clearScanBtn" class="text-red-600 hover:text-red-800 transition px-2 py-1">
                    <i class="ph ph-x-circle text-lg mr-1"></i> Clear Scan
                </button>
            </div>
            <p class="text-gray-500 mb-6">Review ticket details and process transaction</p> 
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-4">
                <i class="ph ph-check-circle text-xl"></i>
                <span>Valid ticket scanned (${isBorrowed ? 'For Return' : 'For Borrow'})</span>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 flex-shrink-0">
                        <img src="${profilePicPath}" alt="Student Avatar" 
                             class="w-full h-full object-cover rounded-full border border-orange-300">
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">${data.student.name}</p> 
                        <p class="text-sm text-gray-600">
                            Student Number: <span class="font-medium text-gray-700">${data.student.id}</span> 
                        </p>
                    </div>
                </div>

                <h2 class="font-semibold text-gray-700 mb-2">Details:</h2>
                <div class="space-y-1 text-sm text-gray-700">
                    <div class="flex justify-between"><span>Course:</span><span class="font-medium text-right">${data.student.course}</span></div>
                    <div class="flex justify-between"><span>Year & Section:</span><span class="font-medium text-right">${data.student.yearsection}</span></div>
                    <div class="flex justify-between"><span>Ticket:</span><span class="font-medium text-right">${data.ticket.id}</span></div>
                    <div class="flex justify-between"><span>Status:</span><span class="font-medium text-right uppercase">${data.ticket.status}</span></div>
                    <div class="flex justify-between"><span>Generated:</span><span class="font-medium text-right">${data.ticket.generated}</span></div>
                    ${isBorrowed ? `<div class="flex justify-between"><span>Due Date:</span><span class="font-medium text-right text-red-600">${data.ticket.dueDate}</span></div>` : ''}
                </div>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 overflow-y-auto max-h-64 flex-grow mb-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="ph ph-book-open text-lg text-gray-700"></i>
                    <h3 class="font-semibold text-gray-700">Items (${data.items.length})</h3>
                </div>
                <hr class="border-t border-orange-200 mb-3">
                <ul class="list-none pl-0 text-gray-700">${itemsHtml}</ul>
            </div>

            <div class="mt-auto">
                ${actionButton}
            </div>
        </div>
    `;

  const processBorrowBtn = document.getElementById('processBorrowBtn');
  const processReturnBtn = document.getElementById('processReturnBtn');
  const clearBtn = document.getElementById('clearScanBtn');

  if (processBorrowBtn) {
    processBorrowBtn.addEventListener('click', () => processTransaction(data.ticket.id, 'borrow'));
  } else if (processReturnBtn) {
    processReturnBtn.addEventListener('click', () => processTransaction(data.ticket.id, 'return'));
  }

  // I-attach ang handler sa bagong Clear Button
  if (clearBtn) {
    clearBtn.addEventListener('click', clearLastScan);
  }
}

function renderTransactionHistory(transactions) {
  if (!transactionHistoryTableBody) return;

  if (!transactions || transactions.length === 0) {
    transactionHistoryTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center gap-3 mt-6 mb-6">
                        <div class="bg-orange-100 rounded-full w-16 h-16 flex items-center justify-center">
                            <i class="ph ph-clock text-3xl text-orange-500"></i>
                        </div>
                        <p class="text-base font-semibold text-gray-700">No transactions found</p>
                        <p class="text-sm text-gray-500">There are no recent borrowing or return activities matching the filters.</p>
                    </div>
                </td>
            </tr>
        `;
    return;
  }

  let tableRowsHtml = '';
  transactions.forEach(transaction => {
    const statusClass = transaction.status === 'Borrowed'
      ? 'bg-orange-100 text-orange-800'
      : transaction.status === 'Returned'
        ? 'bg-green-100 text-green-800'
        : 'bg-gray-100 text-gray-800';

    tableRowsHtml += `
            <tr class="hover:bg-orange-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${transaction.studentName}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.studentId}</td>
                <td class="px-6 py-4 max-w-[240px] break-words text-sm text-gray-500">${transaction.itemsBorrowed}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.borrowedDateTime}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.returnedDateTime}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${transaction.status}
                    </span>
                </td>
            </tr>
        `;
  });

  transactionHistoryTableBody.innerHTML = tableRowsHtml;
}

function processTransaction(transactionCode, action) {
  Swal.fire({
    title: `${action.charAt(0).toUpperCase() + action.slice(1)} Transaction?`,
    text: `Are you sure you want to ${action} the items for ticket ${transactionCode}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: action === 'borrow' ? '#f97316' : '#22c55e',
    cancelButtonColor: '#6b7280',
    confirmButtonText: `Yes, Process ${action.charAt(0).toUpperCase() + action.slice(1)}!`,
  }).then((result) => {
    if (result.isConfirmed) {
      const url = `${basePath}/superadmin/qrScanner/${action}Transaction`;
      const formData = `transaction_code=${encodeURIComponent(transactionCode)}`;

      fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
      })
        .then(res => res.json())
        .then(res => {
          if (res.success) {
            Swal.fire('Success!', res.message, 'success');
            renderScanResult(null);
            loadLastScan();
            document.getElementById('scannerInput').focus();
          } else {
            Swal.fire({ icon: 'error', title: 'Transaction Failed', text: res.message });
          }
        })
        .catch(() => {
          Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to the server.' });
        });
    }
  });
}

function fetchTransactionHistory(status = statusValue.textContent, search = searchInput.value.trim(), date = dateInput.value) {
  const params = new URLSearchParams({ status, search, date });

  fetch(`${basePath}/superadmin/qrScanner/transactionHistory?${params.toString()}`)
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        const formattedData = res.transactions.map(h => ({
          studentName: h.student_number,
          studentId: h.student_id,
          itemsBorrowed: h.items_borrowed,
          status: h.status.charAt(0).toUpperCase() + h.status.slice(1),
          borrowedDateTime: h.borrowed_at,
          returnedDateTime: h.returned_at || 'Not yet returned'
        }));
        renderTransactionHistory(formattedData);
      } else {
        renderTransactionHistory([]);
      }
    });
}

function scanQRCode(transactionCode) {
  fetch(`${basePath}/superadmin/qrScanner/scanTicket`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `transaction_code=${encodeURIComponent(transactionCode)}`
  })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        renderScanResult({ isValid: true, ...res.data });
        document.getElementById('manualTicketInput').value = '';
      } else {
        renderScanResult({ isValid: false, message: res.message });
        Swal.fire({
          icon: 'error',
          title: 'Invalid Ticket',
          text: res.message,
        });
      }
    });
}

function loadLastScan() {
  fetch(`${basePath}/superadmin/qrScanner/lookup`)
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        renderScanResult({ isValid: true, ...res.data });
      } else {
        fetchTransactionHistory();
      }
    })
    .catch(() => {
      fetchTransactionHistory();
    });
}


document.addEventListener('DOMContentLoaded', () => {
  loadLastScan();

  if (statusBtn && statusMenu && statusValue) {
    statusBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      statusMenu.classList.toggle("hidden");
    });

    statusMenu.querySelectorAll(".dropdown-item").forEach(item => {
      item.addEventListener("click", () => {
        statusValue.textContent = item.dataset.value;
        statusMenu.classList.add("hidden");
        fetchTransactionHistory();
      });
    });

    document.addEventListener("click", e => {
      if (!statusBtn.contains(e.target) && !statusMenu.contains(e.target)) {
        statusMenu.classList.add("hidden");
      }
    });
  }

  searchInput.addEventListener('input', () => fetchTransactionHistory());
  dateInput.addEventListener('change', () => fetchTransactionHistory());

  const scannerInput = document.getElementById('scannerInput');
  const scannerBox = document.getElementById('scannerBox');
  const manualBtn = document.getElementById('manualTicketBtn');
  const manualInput = document.getElementById('manualTicketInput');

  if (scannerInput && scannerBox) {
    scannerBox.addEventListener('click', () => scannerInput.focus());
    scannerInput.focus();
  }

  manualBtn.addEventListener('click', () => {
    const code = manualInput.value.trim();
    if (code) scanQRCode(code);
  });
});