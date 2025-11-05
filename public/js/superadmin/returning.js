// --- CORE CONFIRMATION FUNCTION (FINAL TEMPLATE - INLINE STYLE) ---
async function showCustomConfirmationModal(title, text, confirmText = "Confirm") {
    if (typeof Swal == "undefined") return confirm(title);
    
    // Inline CSS Style para sa Orange Border, White BG, at Black Shadow
    const inlineStyle = "border: 2px solid #f97316 !important; background: white !important; box-shadow: 0 0 15px #00000030 !important;";

    const result = await Swal.fire({
        background: "transparent",
        buttonsStyling: false, 
        width: '450px', 
        
        html: `
            <div class="flex flex-col text-center">
                <div class="flex justify-center mb-3">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 text-orange-600">
                        <i class="ph ph-warning-circle text-3xl"></i>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">${title}</h3>
                <p class="text-[14px] text-gray-700 mt-1">${text}</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: "Cancel",
        
        customClass: {
            // Tanggalin ang border/shadow/bg classes, hayaan ang rounded corners at padding
            popup: "!rounded-xl !p-6",
            
            // Confirm Button (Orange, Large, Bold)
            confirmButton:
                "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700 !mx-2 !font-semibold !text-base", 
            // Cancel Button (Gray, Large, Bold)
            cancelButton:
                "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300 !mx-2 !font-semibold !text-base", 

            actions: "!mt-4"
        },
        // Gamitin ang didOpen hook para i-inject ang inline style
        didOpen: (popup) => {
            popup.style.cssText = inlineStyle + " " + popup.style.cssText;
        }
    });
    return result.isConfirmed;
}
// ----------------------------------------------------

// Inalis ang mga declarations na hindi ginagamit sa snippet na ito.

// --- SweetAlert Helper Functions for Toasts and Loaders (Shared design) ---

// Utility for showing small, auto-closing toasts (RED BORDER ONLY)
const showLibrarianToast = (title, text, duration = 3000) => {
    
    // HARDCODED RED BORDER THEME (FOR INVALID TICKET/ERROR)
    const iconClass = 'ph-x-circle'; 
    const contentColor = 'text-red-600'; 
    const bgColor = 'bg-red-100'; 
    
    // INLINE CSS STYLE PARA SA RED BORDER (ito ang sa Invalid Ticket)
    const inlineStyle = "border: 2px solid #dc2626 !important;"; // Red 600

    Swal.fire({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: duration,
        width: "360px",
        
        background: "white", 
        backdrop: `transparent`,
        
        customClass: {
            // Tanggalin ang border classes, hayaan ang rounded corners at padding
            popup: `!rounded-xl !p-4 backdrop-blur-sm`, 
        },
        
        html: `
            <div class="flex flex-col text-left">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full ${bgColor} ${contentColor}">
                        <i class="ph ${iconClass} text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-semibold ${contentColor}">${title}</h3>
                        <p class="text-[13px] text-gray-700 mt-0.5">${text}</p>
                    </div>
                </div>
            </div>
        `,
        
        // Gagamitin ang didOpen hook para i-inject ang style pagkatapos ma-render ang toast.
        didOpen: (toast) => {
            // I-apply ang style sa popup element
            const popup = toast;
            popup.style.cssText = inlineStyle + " " + popup.style.cssText;
        },
    });
};

const showProfileToast = (icon, title, text, theme, duration = 3000) => {
    if (typeof Swal == "undefined") return alert(`${title}: ${text}`);

    // Gagamitin ang theme colors para sa icon/text
    const themeMap = {
        'warning': { color: 'text-orange-600', bg: 'bg-orange-100', icon: 'ph-warning' },
        'error': { color: 'text-red-600', bg: 'bg-red-100', icon: 'ph-x-circle' },
        'success': { color: 'text-green-600', bg: 'bg-green-100', icon: 'ph-check-circle' },
    };
    const selectedTheme = themeMap[theme];

    let inlineStyle;

    if (theme === 'warning') {
        // INLINE STYLE para sa ORANGE BORDER at ORANGE SHADOW (para sa Not Found)
        inlineStyle = "border: 2px solid #f97316 !important; box-shadow: 0 0 10px #f9731670 !important;"; // Orange 500 border/shadow
    } else if (theme === 'success') {
        // INLINE STYLE para sa GREEN BORDER at GREEN SHADOW
        inlineStyle = "border: 2px solid #10b981 !important; box-shadow: 0 0 10px #10b98170 !important;"; // Green 500 border/shadow
    } else {
        // Default style (Black/Gray border para sa Error/Other)
        inlineStyle = "border: 2px solid #1f2937 !important; box-shadow: 0 0 10px #00000030 !important;"; // Gray-900 / Black border
    }


    Swal.fire({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: duration,
        width: "360px",
        background: "white", // Explicitly set to white
        
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
            // TOAST MODAL: Tanggalin ang border/shadow classes, hayaan ang rounded corners at padding
            popup: `!rounded-xl !p-4`,
        },
        didOpen: (toast) => {
            // I-apply ang inline style (Orange/Green/Black depende sa theme)
            toast.style.cssText = inlineStyle + " " + toast.style.cssText;
        },
    });
};

const showLoadingModal = (message = "Processing request...", subMessage = "Please wait.") => {
    if (typeof Swal == "undefined") return;
    
    // INLINE STYLE para sa ORANGE BORDER at ORANGE SHADOW (para sa Loading)
    const inlineStyle = "border: 2px solid #f97316 !important; background: white !important; box-shadow: 0 0 8px #f9731670 !important;"; // Orange 500 border/shadow

    Swal.fire({
        background: "transparent",
        html: `
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                <p class="text-gray-700 text-[14px]">${message}<br><span class="text-sm text-gray-500">${subMessage}</span></p>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            // LOADING MODAL: Tanggalin ang border/shadow classes
            popup: "!rounded-xl !p-6",
        },
        didOpen: (popup) => {
            // I-apply ang inline style (Orange)
            popup.style.cssText = inlineStyle + " " + popup.style.cssText;
        },
    });
};
// --- End Shared SweetAlert Helpers ---


document.addEventListener('DOMContentLoaded', function () {

    // --- Table Elements ---
    const overdueBooksTableBody = document.querySelector('.overdue-books-table tbody');

    // --- Modal Elements ---
    const returnModal = document.getElementById('return-modal');
    const closeButton = document.getElementById('modal-close-button');
    const cancelButton = document.getElementById('modal-cancel-button');
    const modalReturnButton = document.getElementById('modal-return-button');
    const modalExtendButton = document.getElementById('modal-extend-button');

    const availableBookModal = document.getElementById('available-book-modal');
    const availableModalCloseButton = document.getElementById('available-modal-close-button');
    const availableModalCloseAction = document.getElementById('available-modal-close-action');

    const accessionInput = document.getElementById('accession-input');
    const scanButton = document.getElementById('scan-button');
    const qrCodeValueInput = document.getElementById('qrCodeValue');
    
    // --- Close Modal Functions (Needed for Auto-Close) ---
    const closeReturnModal = () => { if (returnModal) returnModal.classList.add('hidden'); };
    const closeAvailableModal = () => { if (availableBookModal) availableBookModal.classList.add('hidden'); };
    // --- End Close Modal Functions ---


    async function fetchTableData() {
        try {
            const response = await fetch('api/superadmin/returning/getTableData');
            if (!response.ok) throw new Error('Network response not ok');
            const result = await response.json();
            if (result.success) {
                renderOverdueBooksTable(result.data.overdue);
            } else showTableError(result.message);
        } catch (error) {
            console.error('Error fetching table data:', error);
           showProfileToast('ph-x-circle', 'List Load Error', 'Could not connect to server to load the overdue list.', 'error');
            showTableError('Could not connect to server.');
        }
    }

    function showTableError(message) {
        const errorRow = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">${message}</td></tr>`;
        overdueBooksTableBody.innerHTML = errorRow;
    }

    function renderOverdueBooksTable(data) {
        overdueBooksTableBody.innerHTML = '';
        if (!data || data.length === 0) {
            overdueBooksTableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No overdue books.</td></tr>`;
            return;
        }
        data.forEach(book => {
            const row = `
        <tr class="align-middle">
            <td class="px-6 py-4 align-middle">
                <div class="font-semibold text-gray-800">${book.user_name}</div>
                <div class="text-gray-500 text-xs">${book.user_id}</div>
                <div class="text-gray-500 text-xs">${book.department_or_course}</div>
            </td>
            <td class="px-6 py-4 align-middle text-gray-800 max-w-[240px] whitespace-normal break-words">${book.item_borrowed}</td>
            <td class="px-6 py-4 align-middle text-gray-800">${book.date_borrowed}</td>
            <td class="px-6 py-4 align-middle text-gray-800">${book.due_date}</td>
            <td class="px-6 py-4 align-middle text-gray-800">${book.contact}</td>
            <td class="px-6 py-4 align-middle">
                <a href="tel:${book.contact}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white">
                    <i class="ph ph-phone text-base mr-1"></i> Contact
                </a>
            </td>
        </tr>`;
            overdueBooksTableBody.insertAdjacentHTML('beforeend', row);
        });
    }


    let scanInProgress = false;


    async function handleBookCheck(accessionNumber) {
        if (!accessionNumber || scanInProgress) return;
        scanInProgress = true;

        showLoadingModal("Checking Book Status...", "Please wait while we verify the Accession Number.");
        
        try {
            const formData = new FormData();
            formData.append('accession_number', accessionNumber);

            const response = await fetch('api/superadmin/returning/checkBook', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Server responded with an unexpected format.' }));
                throw new Error(errorData.message || `Server error: ${response.status}`);
            }

            const result = await response.json();

            await new Promise(r => setTimeout(r, 300));
            Swal.close();

            if (result.success) {
                const data = result.data;

                if (data.status === 'borrowed' && data.details) openReturnModal(data.details);
                else if (data.status === 'available' && data.details) openAvailableModal(data.details, data.status);
                // Gagamitin ang showProfileToast na may ORANGE border (warning)
                else showProfileToast('ph-warning', 'Not Found', 'No book found with that Accession Number.', 'warning');
            } else {
                 // Gagamitin ang showProfileToast na may BLACK/GRAY border (error)
                showProfileToast('ph-x-circle', 'Error', result.message || 'An error occurred.', 'error');
            }
        } catch (error) {
            Swal.close();
            console.error('Error checking book:', error);
             // Gagamitin ang showProfileToast na may BLACK/GRAY border (error)
            showProfileToast('ph-x-circle', 'Error', error.message || 'Could not connect to the server.', 'error');
        }

        if (accessionInput) accessionInput.value = '';
        if (qrCodeValueInput) qrCodeValueInput.value = '';
        if (qrCodeValueInput) qrCodeValueInput.focus();

        scanInProgress = false;
    }

    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value || 'N/A';
    };

    const openReturnModal = (bookData) => {
        const type = bookData.borrower_type || 'student';
        const isStudent = type === 'student';
        const isFaculty = type === 'faculty';
        const isStaff = type === 'staff';

        let borrowerId = 'N/A';
        let idLabel = 'Student Number:';

        if (isStudent) {
            borrowerId = bookData.student_number || bookData.id_number || 'N/A';
        } else if (isFaculty) {
            borrowerId = bookData.unique_faculty_id || bookData.id_number || bookData.faculty_id || 'N/A';
            idLabel = 'Faculty ID:';
        } else if (isStaff) {
            borrowerId = bookData.employee_id || bookData.id_number || 'N/A';
            idLabel = 'Employee ID:';
        } else {
            borrowerId = bookData.id_number || 'N/A';
            idLabel = 'Guest ID:';
        }

        let courseOrDepartment = bookData.course_or_department || 'N/A';
        let yearSectionLabel = 'Year & Section:';
        let yearSectionValue = 'N/A';

        if (isStudent) {
            yearSectionValue = bookData.student_year_section || 'N/A';
            yearSectionLabel = 'Year & Section:';

        } else if (isFaculty) {
            const facultyDeptString = bookData.course_or_department || ' - ';
            const parts = facultyDeptString.split(' - ');

            courseOrDepartment = parts[0] || 'N/A';
            yearSectionLabel = 'College Name:';
            yearSectionValue = parts[1] || 'N/A';
        } else if (isStaff) {
            courseOrDepartment = bookData.course_or_department || 'N/A';
            yearSectionLabel = 'Position:';
            yearSectionValue = bookData.borrower_type?.toUpperCase() || 'N/A';
        } else {
            yearSectionLabel = 'Borrower Type:';
            yearSectionValue = bookData.borrower_type?.toUpperCase() || 'Guest';
        }

        setText('modal-book-title', bookData.book_title || bookData.title);
        setText('modal-book-author', bookData.author);
        setText('modal-book-status', bookData.availability);
        setText('modal-book-isbn', bookData.book_isbn);
        setText('modal-book-accessionnumber', bookData.accession_number);
        setText('modal-book-callnumber', bookData.call_number);

        setText('modal-borrower-name', bookData.borrower_name);

        const idLabelEl = document.getElementById('modal-student-id-label');
        const yearLabelEl = document.getElementById('modal-year-section-label');

        if (idLabelEl) idLabelEl.textContent = idLabel;
        if (yearLabelEl) yearLabelEl.textContent = yearSectionLabel;

        setText('modal-student-id', borrowerId);
        setText('modal-borrower-course', courseOrDepartment);
        setText('modal-borrower-year-section', yearSectionValue);

        setText('modal-borrower-email', bookData.email || 'N/A');
        setText('modal-borrower-contact', bookData.contact || 'N/A');
        setText('modal-borrow-date', bookData.date_borrowed);
        setText('modal-due-date', bookData.due_date);

        if (modalReturnButton) modalReturnButton.dataset.borrowingId = bookData.borrowing_id;
        if (modalExtendButton) modalExtendButton.dataset.borrowingId = bookData.borrowing_id;

        // Show modal
        if (returnModal) returnModal.classList.remove('hidden');
    };

    if (modalExtendButton) {
        modalExtendButton.addEventListener('click', async () => {
            const borrowingId = modalExtendButton.dataset.borrowingId;
            if (!borrowingId) return;
            
            // NEW CODE: Isara ang Return Modal bago magpakita ng confirmation
            closeReturnModal();

            const { value: days } = await Swal.fire({
                title: 'Extend Due Date',
                input: 'number',
                inputLabel: 'Enter number of days to extend',
                inputPlaceholder: 'e.g., 7',
                showCancelButton: true,
                confirmButtonText: 'Extend',
                inputValidator: (value) => {
                    if (!value || value <= 0) {
                        return 'Please enter a valid number of days (1 or more).';
                    }
                }
            });

            if (days) {
                try {
                    const formData = new FormData();
                    formData.append('borrowing_id', borrowingId);
                    formData.append('days', days); 

                    const response = await fetch('api/superadmin/returning/extend', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success', 'Due date extended successfully.', 'success');

                        setText('modal-due-date', result.new_due_date);

                        fetchTableData();
                    } else {
                        Swal.fire('Error', result.message || 'Could not extend due date.', 'error');
                    }
                } catch (error) {
                    console.error('Error extending due date:', error);
                    Swal.fire('Error', 'Could not connect to server.', 'error');
                }
            }
        });
    }


    const openAvailableModal = (bookData, status) => {
        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value || 'N/A';
        };

        // Book basic info
        setText('available-modal-title', bookData.book_title || bookData.title);
        setText('available-modal-author', bookData.author);
        setText('available-modal-isbn', bookData.book_isbn);
        setText('available-modal-accession', bookData.accession_number);
        setText('available-modal-call-number', bookData.call_number);

        // Status
        const statusEl = document.getElementById('available-modal-status');
        const displayStatus = status || bookData.availability || 'Unknown';
        if (statusEl) {
            statusEl.textContent = displayStatus;
            statusEl.className = displayStatus.toLowerCase() === 'available'
                ? 'bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-full'
                : 'bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full';
        }

        // Borrower info (Ito ay malinis dahil ito ay available book)
        setText('available-modal-borrower-name', bookData.borrower_name || 'N/A');
        setText('available-modal-borrower-id', bookData.id_number || 'N/A');
        setText('available-modal-borrower-course', bookData.course_or_department || 'N/A');
        setText('available-modal-borrower-contact', bookData.contact || 'N/A');
        setText('available-modal-date-borrowed', bookData.date_borrowed || 'N/A');
        setText('available-modal-due-date', bookData.due_date || 'N/A');

        // Additional Book Details
        setText('available-modal-subject', bookData.subject || 'N/A');
        setText('available-modal-place', bookData.book_place || 'N/A');
        setText('available-modal-publisher', bookData.book_publisher || 'N/A');
        setText('available-modal-year', bookData.year || 'N/A');
        setText('available-modal-edition', bookData.book_edition || 'N/A');
        setText('available-modal-supplementary', bookData.book_supplementary || 'N/A');
        setText('available-modal-description', bookData.description || 'N/A');

        // Show modal
        if (availableBookModal) availableBookModal.classList.remove('hidden');
    };





    // const closeReturnModal = () => { if (returnModal) returnModal.classList.add('hidden'); }; // Moved above for access
    // const closeAvailableModal = () => { if (availableBookModal) availableBookModal.classList.add('hidden'); }; // Moved above for access

    fetchTableData();

    // --- QR Scanner input ---
    if (qrCodeValueInput) {
        let debounceTimer;
        qrCodeValueInput.removeAttribute('readonly');
        qrCodeValueInput.focus();

        const processQRCode = () => {
            const qrValue = qrCodeValueInput.value.trim();
            if (qrValue) handleBookCheck(qrValue);
            qrCodeValueInput.value = '';
        };

        qrCodeValueInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(processQRCode, 150);
        });

        qrCodeValueInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(debounceTimer);
                processQRCode();
            }
        });

        // document.addEventListener('click', (e) => {
        //      if (e.target !== accessionInput) {
        //          setTimeout(() => qrCodeValueInput.focus(), 10);
        //      }
        // });
    }


    if (accessionInput) {
        accessionInput.removeAttribute('readonly');
        accessionInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleBookCheck(accessionInput.value.trim());
            }
        });
    }

    if (modalReturnButton) {
        modalReturnButton.addEventListener('click', async () => {
            const borrowingId = modalReturnButton.dataset.borrowingId;
            if (!borrowingId) return;
            
            // NEW CODE: Isara ang Return Modal bago magpakita ng confirmation
            closeReturnModal();
            // Isara rin ang available modal kung sakaling bukas (just in case)
            closeAvailableModal();

            // 1. CONFIRMATION
            const isConfirmed = await showCustomConfirmationModal(
                'Confirm Return',
                'Are you sure you want to mark this book as returned?',
                'Yes, Return Book!'
            );
            if (!isConfirmed) return;

            showLoadingModal("Marking as Returned...", "Processing book return transaction.");

            try {
                const formData = new FormData();
                formData.append('borrowing_id', borrowingId);

                const response = await fetch('api/superadmin/returning/markReturned', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                await new Promise(r => setTimeout(r, 300));
                Swal.close();

                if (result.success) {
                    showProfileToast('ph-check-circle', 'Success', 'Book marked as returned successfully.', 'success');
                    // Kung may error sa confirmation, baka kailangan i-close ulit:
                    closeReturnModal(); 
                    fetchTableData();
                } else {
                    showProfileToast('ph-x-circle', 'Error', result.message || 'Could not mark as returned.', 'error');
                }
            } catch (error) {
                Swal.close();
                console.error('Error marking book as returned:', error);
                showProfileToast('ph-x-circle', 'Error', 'Could not connect to server.', 'error');
            }
        });
    }


    if (scanButton) scanButton.addEventListener('click', () => handleBookCheck(accessionInput.value.trim()));

    if (closeButton) closeButton.addEventListener('click', closeReturnModal);
    if (cancelButton) cancelButton.addEventListener('click', closeReturnModal);
    if (returnModal) returnModal.addEventListener('click', e => { if (e.target === returnModal) closeReturnModal(); });
    if (availableModalCloseButton) availableModalCloseButton.addEventListener('click', closeAvailableModal);
    if (availableModalCloseAction) availableModalCloseAction.addEventListener('click', closeAvailableModal);
    if (availableBookModal) availableBookModal.addEventListener('click', e => { if (e.target === availableBookModal) closeAvailableModal(); });

});