document.addEventListener('DOMContentLoaded', function () {

    // --- Table Elements ---
    const booksNearDueTableBody = document.querySelector('.books-near-due-table tbody');
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

    async function fetchTableData() {
        try {
            const response = await fetch('api/libarian/returning/getTableData');
            if (!response.ok) throw new Error('Network response not ok');
            const result = await response.json();
            if (result.success) {
                renderBooksNearDueTable(result.data.nearDue);
                renderOverdueBooksTable(result.data.overdue);
            } else showTableError(result.message);
        } catch (error) {
            console.error('Error fetching table data:', error);
            showTableError('Could not connect to server.');
        }
    }

    function showTableError(message) {
        const errorRow = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">${message}</td></tr>`;
        booksNearDueTableBody.innerHTML = errorRow;
        overdueBooksTableBody.innerHTML = errorRow;
    }

    function renderBooksNearDueTable(data) {
        booksNearDueTableBody.innerHTML = '';
        if (!data || data.length === 0) {
            booksNearDueTableBody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No books near due date.</td></tr>`;
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
                <a href="tel:${book.contact}" class="inline-flex items-center px-3 py-1.5 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100">
                    <i class="ph ph-phone text-base mr-1"></i> Contact
                </a>
            </td>
        </tr>`;
            booksNearDueTableBody.insertAdjacentHTML('beforeend', row);
        });
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

        // 🔵 Loading Alert for Book Check
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                    <p class="text-gray-700 text-[14px]">Checking book status...<br><span class="text-sm text-gray-500">Processing request.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
            },
        });

        try {
            const formData = new FormData();
            formData.append('accession_number', accessionNumber);

            const response = await fetch('api/librarian/returning/checkBook', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Server responded with an unexpected format.' }));
                throw new Error(errorData.message || `Server error: ${response.status}`);
            }

            const result = await response.json();
            Swal.close(); // Close loading after response

            if (result.success) {
                const data = result.data;

                console.log('Book Data Received:', result.data);

                if (data.status === 'borrowed' && data.details) openReturnModal(data.details);
                else if (data.status === 'available' && data.details) openAvailableModal(data.details, data.status);
                else {
                    // 🟠 Warning Toast for Not Found/Invalid Status
                    Swal.fire({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        width: "360px",
                        background: "transparent",
                        html: `
                            <div class="flex flex-col text-left">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600">
                                        <i class="ph ph-warning text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-[15px] font-semibold text-orange-600">Book Not Found</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">No borrowed book found with that Accession Number.</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
                        },
                    });
                }
            } else {
                // 🔴 Error Toast for Server Message
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    width: "360px",
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Scan Failed</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'An unknown error occurred on the server.'}</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });
            }
        } catch (error) {
            console.error('Error checking book:', error);
            Swal.close();
            // 🔴 Error Toast for Network Error
            Swal.fire({
                toast: true,
                position: "bottom-end",
                showConfirmButton: false,
                timer: 3000,
                width: "360px",
                background: "transparent",
                html: `
                    <div class="flex flex-col text-left">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                <i class="ph ph-x-circle text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-red-600">Network Error</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">Could not connect to server.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
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





    const closeReturnModal = () => { if (returnModal) returnModal.classList.add('hidden'); };
    const closeAvailableModal = () => { if (availableBookModal) availableBookModal.classList.add('hidden'); };

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

        document.addEventListener('click', (e) => {
            if (e.target !== accessionInput) {
                setTimeout(() => qrCodeValueInput.focus(), 10);
            }
        });
    }


    // --- Manual input / Enter key ---
    if (accessionInput) {
        accessionInput.removeAttribute('readonly');
        accessionInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleBookCheck(accessionInput.value.trim());
            }
        });
    }

    // --- Handle "Mark as Returned" ---
    if (modalReturnButton) {
        modalReturnButton.addEventListener('click', async () => {
            const borrowingId = modalReturnButton.dataset.borrowingId;
            if (!borrowingId) return;

            // 🔵 Loading Alert for Return Processing
            Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                        <p class="text-gray-700 text-[14px]">Marking book as returned...<br><span class="text-sm text-gray-500">Processing request.</span></p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
                },
            });

            try {
                const formData = new FormData();
                formData.append('borrowing_id', borrowingId);

                const response = await fetch('api/librarian/returning/markReturned', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                Swal.close(); // Close loading

                if (result.success) {
                    // 🟢 Success Toast
                    Swal.fire({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        width: "360px",
                        background: "transparent",
                        html: `
                            <div class="flex flex-col text-left">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                                        <i class="ph ph-check-circle text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-[15px] font-semibold text-green-600">Return Successful!</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">Book marked as returned successfully.</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
                        },
                    });
                    closeReturnModal();
                    fetchTableData(); // refresh the tables
                } else {
                    // 🔴 Error Toast
                    Swal.fire({
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        width: "360px",
                        background: "transparent",
                        html: `
                            <div class="flex flex-col text-left">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                        <i class="ph ph-x-circle text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-[15px] font-semibold text-red-600">Return Failed</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Could not mark as returned.'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (error) {
                console.error('Error marking book as returned:', error);
                Swal.close();
                // 🔴 Error Toast for Network Error
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    width: "360px",
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Network Error</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Could not connect to server during return.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });
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