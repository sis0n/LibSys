const simulateScanBtn = document.getElementById('simulateScanBtn');
const scanResultCard = document.getElementById('scanResultCard');
const transactionHistoryTableBody = document.getElementById('transactionHistoryTableBody');

/* ===============================
    SCAN RESULT CARD RENDERING
================================*/
function renderScanResult(data) {
    if (!data || !data.isValid) {
        scanResultCard.innerHTML = `
      <div>
        <h2 class="text-xl font-semibold mb-2">Scan Result</h2>
        <p class="text-gray-500 mb-6">Review ticket details and process transaction</p>
        <div class="text-center py-16">
          <div class="flex justify-center items-center mb-4">
            <div class="bg-orange-100 rounded-full w-20 h-20 flex items-center justify-center">
              <i class="ph ph-x text-4xl text-orange-500"></i>
            </div>
          </div>
          <p class="font-semibold text-gray-700">No ticket scanned yet</p>
          <p class="text-sm text-gray-500">Present QR code or enter ticket ID manually</p>
        </div>
      </div>
    `;
        return;
    }

    const itemsHtml = data.items.map((item, index) => `
    <li class="mb-3 flex items-start gap-3">
      <span class="text-sm font-semibold text-gray-700 w-6 text-right">${index + 1}.</span>
      <div class="flex-1">
        <p class="font-medium text-gray-800 leading-snug">${item.title}</p>
        <p class="text-sm text-gray-700">${item.author}</p>

        <div class="mt-2 flex flex-wrap gap-2">
          <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">Accession No: ${item.accessionNumber}</span>
          <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">Call No: ${item.callNumber}</span>
          <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-xs font-medium">IBSN: ${item.ibsn}</span>
        </div>
      </div>
    </li>
  `).join('');

    scanResultCard.innerHTML = `
    <div class="flex flex-col flex-grow">
      <h2 class="text-xl font-semibold mb-2">Scan Result</h2>
      <p class="text-gray-500 mb-6">Review ticket details and process transaction</p>

      <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-4">
        <i class="ph ph-check-circle text-xl"></i>
        <span>Valid ticket scanned</span>
      </div>

      <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
        <div class="flex items-center gap-3 mb-2">
          <div class="bg-orange-200 rounded-full w-10 h-10 flex items-center justify-center">
            <i class="ph ph-user text-xl text-orange-700"></i>
          </div>
          <div>
            <p class="font-semibold text-gray-800">${data.student.name}</p>
            <p class="text-sm text-gray-600">
              Student ID: <span class="font-medium text-gray-700">${data.student.id}</span>
            </p>
          </div>
        </div>

        <h2 class="font-semibold text-gray-700 mb-2">Details:</h2>
        <div class="space-y-1 text-sm text-gray-700">
          <div class="flex justify-between"><span>Course:</span><span class="font-medium text-right">${data.student.course}</span></div>
          <div class="flex justify-between"><span>Year & Section:</span><span class="font-medium text-right">${data.student.yearsection}</span></div>
          <div class="flex justify-between"><span>Ticket:</span><span class="font-medium text-right">${data.ticket.id}</span></div>
          <div class="flex justify-between"><span>Generated:</span><span class="font-medium text-right">${data.ticket.generated}</span></div>
        </div>
      </div>

      <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex-grow">
        <div class="flex items-center gap-2 mb-3">
          <i class="ph ph-book-open text-lg text-gray-700"></i>
          <h3 class="font-semibold text-gray-700">Items (${data.items.length})</h3>
        </div>
        <hr class="border-t border-orange-200 mb-3">
        <ul class="list-none pl-0 text-gray-700">${itemsHtml}</ul>
      </div>
    </div>
  `;
}

/* ===============================
    TRANSACTION HISTORY RENDER
================================*/
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
            <p class="text-sm text-gray-500">There are no recent borrowing or return activities.</p>
          </div>
        </td>
      </tr>
    `;
        return;
    }

    let tableRowsHtml = '';
    transactions.forEach(transaction => {
        const statusClass =
            transaction.status === 'Borrowed' ?
            'bg-orange-100 text-orange-800' :
            transaction.status === 'Returned' ?
            'bg-green-100 text-green-800' :
            'bg-gray-100 text-gray-800';

        tableRowsHtml += `
      <tr>
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


/* ===============================
    DROPDOWN FILTER LOGIC
================================*/
const statusBtn = document.getElementById("statusFilterBtn");
const statusMenu = document.getElementById("statusFilterMenu");
const statusValue = document.getElementById("statusFilterValue");

if (statusBtn && statusMenu && statusValue) {
    statusBtn.addEventListener("click", () => {
        statusMenu.classList.toggle("hidden");
    });

    statusMenu.querySelectorAll(".dropdown-item").forEach(item => {
        item.addEventListener("click", () => {
            statusValue.textContent = item.dataset.value;
            statusMenu.classList.add("hidden");
        });
    });

    document.addEventListener("click", e => {
        if (!statusBtn.contains(e.target)) statusMenu.classList.add("hidden");
    });
}

/* ===============================
    SAMPLE DATA (SIMULATION)
================================*/
const sampleTicketData = {
    isValid: true,
    student: { name: "Student Name", id: "Student Number", course: "BSCS", yearsection: "3-A" },
    ticket: { id: "TICKET-NUMBER", generated: "MM/DD/YYYY, 12:00 PM/AM" },
    items: [{
        title: "Children with disabilities : towards the realization of their right to education",
        author: "by Aguiling-Pangalanan, Elizabeth & Barredo, Joel Mark & Luthra, Abhay & et al.",
        accessionNumber: "4442",
        callNumber: "K 639 A282 2018 c.2",
        ibsn: "9.78971E+12"
    }]
};

const sampleTransactionHistoryData = [
    { studentName: "STUDENT NAME", studentId: "20230114-S", itemsBorrowed: "Children with disabilities : towards the realization of their right to education", status: "Borrowed", borrowedDateTime: "2025-08-01 10:30:00", returnedDateTime: "Not yet returned" },
    { studentName: "STUDENT NAME", studentId: "STUDENT NUMBER", itemsBorrowed: "Book Title", status: "Returned", borrowedDateTime: "2025-08-01 10:30:00", returnedDateTime: "2025-08-01 10:30:00" },
    { studentName: "STUDENT NAME", studentId: "STUDENT NUMBER", itemsBorrowed: "Computer", status: "Undefined", borrowedDateTime: "2025-08-01 10:30:00", returnedDateTime: "2025-08-01 10:30:00" },
];

/* ===============================
    INITIALIZE
================================*/
simulateScanBtn.addEventListener('click', function() {
    renderScanResult(sampleTicketData);
});

document.addEventListener('DOMContentLoaded', function() {
    renderTransactionHistory(sampleTransactionHistoryData);
});