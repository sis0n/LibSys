const scanResultCard = document.getElementById('scanResultCard');
const transactionHistoryTableBody = document.getElementById('transactionHistoryTableBody');
const defaultAvatar = `/LibSys/public/img/default_avatar.png`;

const searchInput = document.getElementById('transactionSearchInput');
const dateInput = document.getElementById('transactionDate');
const statusBtn = document.getElementById("statusFilterBtn");
const statusMenu = document.getElementById("statusFilterMenu");
const statusValue = document.getElementById("statusFilterValue");

// ==========================================================
// SWEETALERT UTILITY FUNCTIONS
// ==========================================================

// Utility for showing small, auto-closing toasts (Consistent Design)
const showLibrarianToast = (title, text, theme, duration = 3000) => {
    const themeMap = {
        'warning': { color: 'text-orange-600', bg: 'bg-orange-100', border: 'border-orange-400', icon: 'ph-warning' },
        'error': { color: 'text-red-600', bg: 'bg-red-100', border: 'border-red-400', icon: 'ph-x-circle' },
        'success': { color: 'text-green-600', bg: 'bg-green-100', border: 'border-green-400', icon: 'ph-check-circle' },
    };
    const selectedTheme = themeMap[theme];

    Swal.fire({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: duration,
        width: "360px",
        background: "transparent",
        html: `
            <div class="flex flex-col text-left">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full ${selectedTheme.bg} ${selectedTheme.color}">
                        <i class="ph ${selectedTheme.icon} text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-semibold ${selectedTheme.color}">${title}</h3>
                        <p class="text-[13px] text-gray-700 mt-0.5">${text}</p>
                    </div>
                </div>
            </div>
        `,
        customClass: {
            popup: `!rounded-xl !shadow-md !border-2 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm ${selectedTheme.border}`,
        },
    });
};

// Custom Modal for Final Success/Error (with Progress Bar, auto-close, and fixed size)
const showFinalLibrarianModal = (isSuccess, title, message) => {
    const duration = 3000;
    let timerInterval;

    const theme = isSuccess ? {
        bg: 'bg-green-50',
        border: 'border-green-300',
        text: 'text-green-700',
        iconBg: 'bg-green-100',
        iconColor: 'text-green-600',
        iconClass: 'ph-check-circle',
        progressBarColor: 'bg-green-500',
    } : {
        bg: 'bg-red-50',
        border: 'border-red-300',
        text: 'text-red-700',
        iconBg: 'bg-red-100',
        iconColor: 'text-red-600',
        iconClass: 'ph-x-circle',
        progressBarColor: 'bg-red-500',
    };

    Swal.fire({
        showConfirmButton: false, 
        showCancelButton: false,
        buttonsStyling: false,
        
        backdrop: `rgba(0,0,0,0.3) backdrop-filter: blur(6px)`,
        timer: duration, 
        
        didOpen: () => {
            const progressBar = Swal.getHtmlContainer().querySelector("#progress-bar");
            let width = 100;
            timerInterval = setInterval(() => {
                width -= 100 / (duration / 100); 
                if (progressBar) {
                    progressBar.style.width = width + "%";
                }
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        },

        html: `
            <div class="w-[450px] ${theme.bg} border-2 ${theme.border} rounded-2xl p-8 shadow-xl text-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-full ${theme.iconBg} mx-auto mb-4">
                    <i class="ph ${theme.iconClass} ${theme.iconColor} text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold ${theme.text}">${title}</h3>
                <p class="text-base ${theme.text} mt-3 mb-4">
                    ${message}
                </p>
                <div class="w-full bg-gray-200 h-2 rounded mt-4 overflow-hidden">
                    <div id="progress-bar" class="${theme.progressBarColor} h-2 w-full transition-all duration-100 ease-linear"></div>
                </div>
            </div>
        `,
        customClass: {
            popup: "!block !bg-transparent !shadow-none !p-0 !border-0 !w-auto !min-w-0 !max-w-none",
        },
    });
};

// ==========================================================

function renderScanResult(data) {
    if (!data || !data.isValid) {
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

    const user = data.user;
    const isBorrowed = data.ticket.status.toLowerCase() === 'borrowed';
    const profilePicPath = user.profilePicture || defaultAvatar;

    const actionButton = isBorrowed ?
        `<p class="text-sm text-green-600 font-semibold py-3">This ticket has already been processed.</p>` :
        `<button id="processBorrowBtn" data-code="${data.ticket.id}" data-action="borrow"
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

    let extraInfoHtml = '';
    if (user.type === 'student') {
        extraInfoHtml = `
            <div class="flex justify-between"><span>Course:</span><span class="font-medium text-right">${user.course}</span></div>
            <div class="flex justify-between"><span>Year & Section:</span><span class="font-medium text-right">${user.yearsection}</span></div>
        `;
    } else if (user.type === 'faculty') {
        extraInfoHtml = `<div class="flex justify-between"><span>College/Dept:</span><span class="font-medium text-right">${user.department}</span></div>`;
    } else if (user.type === 'staff') {
        extraInfoHtml = `
            <div class="flex justify-between"><span>Position:</span><span class="font-medium text-right">${user.position}</span></div>
            <div class="flex justify-between"><span>Contact:</span><span class="font-medium text-right">${user.contact}</span></div>
        `;
    }

    scanResultCard.innerHTML = `
        <div class="flex flex-col flex-grow">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Scan Result</h2>
            </div>
            <p class="text-gray-500 mb-6">Review ticket details and process transaction</p> 
            
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-4">
                <i class="ph ph-check-circle text-xl"></i>
                <span>Valid ticket scanned (${isBorrowed ? 'Already Borrowed' : 'For Borrow'})</span>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-16 h-16 flex-shrink-0">
                        <img src="${profilePicPath}" alt="User Avatar" 
                            class="w-full h-full object-cover rounded-full border border-orange-300">
                    </div>
                    <div class="self-center">
                        <p class="font-bold text-lg text-gray-800">${user.name}</p> 
                        <p class="text-md text-gray-600">
                            ID: <span class="font-medium text-gray-700">${user.id}</span> 
                        </p>
                    </div>
                </div>

                <h2 class="font-semibold text-gray-700 mb-2">Details:</h2>
                <div class="space-y-1 text-sm text-gray-700">
                    ${extraInfoHtml}
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
    if (processBorrowBtn) {
        processBorrowBtn.addEventListener('click', () => processTransaction(data.ticket.id, 'borrow'));
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.studentNumber}</td>
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
    const actionText = 'finalize this borrowing transaction';

    // 🟠 Confirmation Modal (Aligned to Orange theme)
    Swal.fire({
        title: `Borrow Transaction?`,
        text: `Are you sure you want to ${actionText} for ticket ${transactionCode}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Yes, Process Borrow!`,
        cancelButtonText: `Cancel`,

        // Use custom buttons and styling
        buttonsStyling: false, 
        customClass: {
            // Orange Border, Shadow, and Background Gradient (Same as your Confirmation Modals)
            popup: 
                "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
            
            // Custom Orange Confirm Button
            confirmButton: 
                "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700 !mx-2",
            
            // Custom Gray Cancel Button
            cancelButton: 
                "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300 !mx-2",
            
            // Ensure Title and Content font/size are correct
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-base text-gray-700',
        },
        // Ensure icon is visible and buttons are centered (overriding default Swalert margins)
        didOpen: (popup) => {
            const icon = popup.querySelector('.swal2-icon');
            if (icon) icon.style.margin = '0 auto 10px';
            const actions = popup.querySelector('.swal2-actions');
            if (actions) actions.style.marginTop = '1rem';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // 🟠 Loading Animation
            Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                        <p class="text-gray-700 text-[14px]">Processing transaction...<br><span class="text-sm text-gray-500">Just a moment.</span></p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
                },
            });

            const url = `api/superadmin/qrScanner/borrowTransaction`;
            const formData = `transaction_code=${encodeURIComponent(transactionCode)}`;

            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                Swal.close(); // Close loading animation

                if (res.success) {
                    // 🟢 Success Modal (Custom size/style with timer)
                    showFinalLibrarianModal(true, 'Success!', res.message);
                    renderScanResult(null); 
                    document.getElementById('scannerInput').focus();
                } else {
                    // 🔴 Error Modal (Custom size/style with timer)
                    showFinalLibrarianModal(false, 'Transaction Failed', res.message);
                }
            })
            .catch(() => {
                Swal.close();
                // 🔴 Network Error Modal (Custom size/style with timer)
                showFinalLibrarianModal(false, 'Network Error', 'Could not connect to the server.');
            });
        }
    });
}


function scanQRCode(transactionCode) {
    fetch(`api/superadmin/qrScanner/scanTicket`, {
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
                // 🔴 Invalid Ticket Toast
                showLibrarianToast('Invalid Ticket', res.message, 'error', 4000);
            }
        });
}

document.addEventListener('DOMContentLoaded', () => {

    if (statusBtn && statusMenu && statusValue) {
        statusBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            statusMenu.classList.toggle("hidden");
        });

        statusMenu.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", () => {
                statusValue.textContent = item.dataset.value;
                statusMenu.classList.add("hidden");

            });
        });

        document.addEventListener("click", e => {
            if (!statusBtn.contains(e.target) && !statusMenu.contains(e.target)) {
                statusMenu.classList.add("hidden");
            }
        });
    }

    // Assume fetchTransactionHistory is defined elsewhere
    // searchInput.addEventListener('input', () => fetchTransactionHistory());
    // dateInput.addEventListener('change', () => fetchTransactionHistory());

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