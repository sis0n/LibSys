// ===============================
// ELEMENT REFERENCES
// ===============================
const qrInput = document.getElementById("manualTicketInput"); // input box for QR/manual entry
const scanResultCard = document.getElementById("scanResultCard");

manualScanBtn.addEventListener('click', function() {
    console.log('Scan button clicked! Value:', manualTicketInput.value);
});

// ===============================
// PROCESS QR FUNCTION
// ===============================
function processQR(qrValue) {
  if (!qrValue) return;

  fetch("/libsys/public/scanner/scan", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({ ticketId: qrValue })
  })
    .then(res => res.json())
    .then(data => renderScanResult(data))
    .catch(err => {
      console.error(err);
      alert("Something went wrong while scanning QR.");
    });
}

// ===============================
// SCAN RESULT RENDERING
// ===============================
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
                <p class="font-semibold text-gray-700">Invalid or no ticket scanned</p>
                <p class="text-sm text-gray-500">Present QR code or enter ticket ID manually</p>
            </div>
        </div>`;
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
        </li>`).join('');

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
    </div>`;
}

// ===============================
// EVENT LISTENERS
// ===============================

// Handle Enter key (for hardware scanner or manual input)
qrInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    const qrValue = qrInput.value.trim();
    processQR(qrValue);
    qrInput.value = ""; // reset input
  }
});

// Optional: focus input on page load
window.addEventListener("load", () => {
  if (qrInput) qrInput.focus();
});
