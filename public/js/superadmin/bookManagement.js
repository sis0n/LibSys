
    document.addEventListener("DOMContentLoaded", () => {
        // ==========================
        // ELEMENT REFERENCES
        // ==========================
        const importModal = document.getElementById("importModal");
        const bulkImportBtn = document.getElementById("bulkImportBtn");
        const closeImportModal = document.getElementById("closeImportModal");
        const cancelImport = document.getElementById("cancelImport");

        const addBookModal = document.getElementById("addBookModal");
        const openAddBookBtn = document.getElementById("openAddBookBtn");
        const closeAddBookModal = document.getElementById("closeAddBookModal");
        const cancelAddBook = document.getElementById("cancelAddBook");
        const addBookForm = document.getElementById("addBookForm");
        const input = document.getElementById('book_image');
        const uploadText = document.getElementById('uploadText');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');

        const editBookModal = document.getElementById("editBookModal");
        const closeEditBookModal = document.getElementById("closeEditBookModal");
        const cancelEditBook = document.getElementById("cancelEditBook");
        const editBookForm = document.getElementById("editBookForm");
        const editInput = document.getElementById('edit_book_image');
        const editUploadText = document.getElementById('editUploadText');
        const editPreviewContainer = document.getElementById('editPreviewContainer');
        const editPreviewImage = document.getElementById('editPreviewImage');

        // --- View Modal Elements ---
        const viewBookModal = document.getElementById("viewBookModal");
        const viewBookModalContent = document.getElementById("viewBookModalContent");
        const closeViewModal = document.getElementById("closeViewModal");
        const closeViewModalBtn = document.getElementById("closeViewModalBtn");
        const viewModalImg = document.getElementById("viewModalImg");
        const viewModalTitle = document.getElementById("viewModalTitle");
        const viewModalAuthor = document.getElementById("viewModalAuthor");
        const viewModalStatus = document.getElementById("viewModalStatus");
        const viewModalCallNumber = document.getElementById("viewModalCallNumber");
        const viewModalAccessionNumber = document.getElementById("viewModalAccessionNumber");
        const viewModalIsbn = document.getElementById("viewModalIsbn");
        const viewModalSubject = document.getElementById("viewModalSubject");
        const viewModalPlace = document.getElementById("viewModalPlace");
        const viewModalPublisher = document.getElementById("viewModalPublisher");
        const viewModalYear = document.getElementById("viewModalYear");
        const viewModalEdition = document.getElementById("viewModalEdition");
        const viewModalSupplementary = document.getElementById("viewModalSupplementary");
        const viewModalDescription = document.getElementById("viewModalDescription");

        const searchInput = document.getElementById("bookSearchInput");
        const bookTableBody = document.getElementById("bookTableBody");
        const bookCountSpan = document.getElementById("bookCount");
        const bookTotalSpan = document.getElementById("bookTotal");
        const resultsIndicator = document.getElementById("resultsIndicator");

        const paginationControls = document.getElementById("paginationControls");
        const paginationList = document.getElementById("paginationList");
        const bulkImportForm = document.getElementById("bulkImportForm");
        const fileInput = document.getElementById("csvFile");
        const importMessage = document.getElementById("importMessage");

        if (!bookTableBody || !addBookModal || !editBookModal || !importModal || !searchInput || !paginationList || !resultsIndicator || !viewBookModal) {
            console.error("BookManagement Error: Core components missing.");
            if (bookTableBody) bookTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="7" class="text-center text-red-500 py-10">Page Error: Components missing.</td></tr>`;
            return;
        }

        // ==========================
        // STATE VARIABLES
        // ==========================
        let totalBooks = 0;
        let currentEditingBookId = null;
        let currentSort = 'default';
        let currentStatus = 'All Status';
        let currentSearch = '';
        let isLoading = false;
        let searchDebounce;
        const limit = 30;
        let currentPage = 1;
        let totalPages = 1;
        let currentApiBaseUrl = '';


        fileInput.addEventListener("change", () => {
            if (fileInput.files.length) {
                bulkImportForm.requestSubmit();
            }
        });

        bulkImportForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            console.log("Submit fired!");
            console.log("Files:", fileInput.files);
            if (!fileInput.files.length) return;

            const formData = new FormData();
            formData.append("csv_file", fileInput.files[0]);
            console.log("Uploading file:", fileInput.files[0].name);

            // 🔵 Loading Animation for Bulk Import
            Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                        <p class="text-gray-700 text-[14px]">Importing books...<br><span class="text-sm text-gray-500">Do not close the page.</span></p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
                },
            });


            try {
                const res = await fetch(`${getApiBaseUrl()}/booksmanagement/bulkImport`, {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();

                Swal.close(); // Close loading animation

                if (data.success) {
                    // 🟢 Success Toast for Bulk Import
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
                                        <h3 class="text-[15px] font-semibold text-green-600">Import Successful!</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">Imported: ${data.imported} rows successfully!</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] backdrop-blur-sm shadow-[0_0_8px_#22c55e70]",
                        },
                    });

                    fileInput.value = "";
                    closeModal(document.getElementById("importModal"));

                    // Assumed loadUsers is loadBooks based on context, so I changed it.
                    if (typeof loadBooks === "function") await loadBooks(currentPage);
                } else {
                    // 🔴 Error Toast for Bulk Import
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
                                        <h3 class="text-[15px] font-semibold text-red-600">Import Failed</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${data.message || "Failed to import CSV."}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (err) {
                console.error("Error importing CSV:", err);
                Swal.close();
                // 🔴 Error Toast for Network/Fetch Error
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
                                    <p class="text-[13px] text-gray-700 mt-0.5">There was a connection issue while importing.</p>
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

        // --- Page Memory ---
        try {
            const savedPage = sessionStorage.getItem('bookManagementPage');
            if (savedPage) {
                const parsedPage = parseInt(savedPage, 10);
                if (!isNaN(parsedPage) && parsedPage > 0) currentPage = parsedPage;
                else sessionStorage.removeItem('bookManagementPage');
            }
        } catch (e) {
            console.error("SessionStorage Error:", e);
            currentPage = 1;
        }

        // ==========================
        // DYNAMIC URL HELPER
        // ==========================
        function getApiBaseUrl() {
            if (currentApiBaseUrl) return currentApiBaseUrl;

            const path = window.location.pathname;

            if (path.includes('/superadmin/')) {
                currentApiBaseUrl = '/libsys/public/superadmin';
            } else if (path.includes('/admin/')) {
                currentApiBaseUrl = '/libsys/public/admin';
            } else if (path.includes('/librarian/')) {
                currentApiBaseUrl = '/libsys/public/librarian';
            } else {
                console.error("CRITICAL: Cannot determine user role from URL path.");
                currentApiBaseUrl = '/libsys/public/superadmin'; // Fallback
            }
            return currentApiBaseUrl;
        }

        // ==========================
        // MODAL HELPERS & LISTENERS
        // ==========================
        function openModal(modal) {
            if (modal) {
                modal.classList.remove("hidden");
                document.body.classList.add("overflow-hidden");
            }
        }

        function closeModal(modal) {
            if (modal) {
                modal.classList.add("hidden");
                document.body.classList.remove("overflow-hidden");
            }
        }

        bulkImportBtn?.addEventListener("click", () => openModal(importModal));
        closeImportModal?.addEventListener("click", () => closeModal(importModal));
        cancelImport?.addEventListener("click", () => closeModal(importModal));
        importModal?.addEventListener("click", e => {
            if (e.target === importModal) closeModal(importModal);
        });

        openAddBookBtn?.addEventListener("click", () => {
            addBookForm.reset();
            previewContainer.classList.add('hidden');
            uploadText.textContent = 'Upload Image';
            openModal(addBookModal);
        });
        closeAddBookModal?.addEventListener("click", () => closeModal(addBookModal));
        cancelAddBook?.addEventListener("click", () => closeModal(addBookModal));
        addBookModal?.addEventListener("click", e => {
            if (e.target === addBookModal) closeModal(addBookModal);
        });

        closeEditBookModal?.addEventListener("click", () => closeModal(editBookModal));
        cancelEditBook?.addEventListener("click", () => closeModal(editBookModal));
        editBookModal?.addEventListener("click", e => {
            if (e.target === editBookModal) closeModal(editBookModal);
        });

        closeViewModal?.addEventListener("click", () => closeModal(viewBookModal));
        closeViewModalBtn?.addEventListener("click", () => closeModal(viewBookModal));
        viewBookModal?.addEventListener("click", e => {
            if (e.target === viewBookModal) closeModal(viewBookModal);
        });

        input?.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                // 🟠 Warning Toast for Invalid file type (instead of alert)
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
                                    <h3 class="text-[15px] font-semibold text-orange-600">Invalid File Type</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Please upload only JPG or PNG files.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
                    },
                });
                input.value = '';
                uploadText.textContent = 'Upload Image';
                previewContainer.classList.add('hidden');
                previewImage.src = '';
                return;
            }
            uploadText.textContent = file.name;
            if (file.type.startsWith('image/')) {
                previewContainer.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = (event) => (previewImage.src = event.target.result);
                reader.readAsDataURL(file);
            }
        });

        editInput?.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                // 🟠 Warning Toast for Invalid file type (instead of alert)
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
                                    <h3 class="text-[15px] font-semibold text-orange-600">Invalid File Type</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Please upload only JPG or PNG files.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
                    },
                });
                editInput.value = '';
                editUploadText.textContent = 'Upload Image';
                editPreviewContainer.classList.add('hidden');
                editPreviewImage.src = '';
                return;
            }
            editUploadText.textContent = file.name;
            if (file.type.startsWith('image/')) {
                editPreviewContainer.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = (event) => (editPreviewImage.src = event.target.result);
                reader.readAsDataURL(file);
            }
        });

        // ==========================
        // DROPDOWN LOGIC
        // ==========================
        function setupDropdown(btnId, menuId) {
            const btn = document.getElementById(btnId);
            const menu = document.getElementById(menuId);
            if (!btn || !menu) return;
            const closeAllDropdowns = () => {
                document.querySelectorAll('.absolute.mt-1.z-20').forEach(m => m.classList.add('hidden'));
            }
            btn.addEventListener("click", e => {
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                closeAllDropdowns();
                if (isHidden) menu.classList.toggle("hidden");
            });
        }
        setupDropdown("statusDropdownBtn", "statusDropdownMenu");
        setupDropdown("sortDropdownBtn", "sortDropdownMenu");
        document.addEventListener("click", () => {
            document.querySelectorAll('.absolute.mt-1.z-20').forEach(menu => menu.classList.add('hidden'));
        });

        window.selectSort = (el, val) => {
            const valueEl = document.getElementById("sortDropdownValue");
            if (valueEl) valueEl.textContent = el.textContent;
            document.querySelectorAll("#sortDropdownMenu .sort-item").forEach(i => i.classList.remove("bg-orange-50", "font-semibold"));
            if (el) el.classList.add("bg-orange-50", "font-semibold");
            currentSort = val;
            currentPage = 1;
            try {
                sessionStorage.removeItem('bookManagementPage');
            } catch (e) {}
            loadBooks(currentPage);
        };
        window.selectStatus = (el, val) => {
            const valueEl = document.getElementById("statusDropdownValue");
            if (valueEl) valueEl.textContent = val;
            document.querySelectorAll("#statusDropdownMenu .status-item").forEach(i => i.classList.remove("bg-orange-50", "font-semibold"));
            if (el) el.classList.add("bg-orange-50", "font-semibold");
            currentStatus = val;
            currentPage = 1;
            try {
                sessionStorage.removeItem('bookManagementPage');
            } catch (e) {}
            loadBooks(currentPage);
        };

        const defaultSort = document.querySelector("#sortDropdownMenu .sort-item");
        if (defaultSort) defaultSort.classList.add("bg-orange-50", "font-semibold");
        const defaultStatus = document.querySelector("#statusDropdownMenu .status-item");
        if (defaultStatus) defaultStatus.classList.add("bg-orange-50", "font-semibold");

        searchInput.addEventListener("input", e => {
            currentSearch = e.target.value.trim();
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(() => {
                currentPage = 1;
                try {
                    sessionStorage.removeItem('bookManagementPage');
                } catch (e) {}
                loadBooks(currentPage);
            }, 500);
        });

        // ==========================
        // DATA FETCHING (AJAX)
        // ==========================
        async function loadBooks(page = 1) {
            if (isLoading) return;
            isLoading = true;
            currentPage = page;
            bookTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="7" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Loading books...</td></tr>`;
            paginationControls.classList.add('hidden');
            resultsIndicator.textContent = 'Loading...';
            const offset = (page - 1) * limit;
            const apiBaseUrl = getApiBaseUrl(); // Kunin ang tamang role prefix

            try {
                const params = new URLSearchParams({
                    search: currentSearch,
                    status: currentStatus === 'All Status' ? '' : currentStatus,
                    sort: currentSort,
                    limit: limit,
                    offset: offset
                });

                const res = await fetch(`${apiBaseUrl}/booksmanagement/fetch?${params.toString()}`);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                const data = await res.json();

                if (data.success && Array.isArray(data.books)) {
                    totalBooks = data.totalCount;
                    totalPages = Math.ceil(totalBooks / limit) || 1;

                    if (page > totalPages && totalPages > 0) {
                        loadBooks(totalPages);
                        return;
                    }
                    renderBooks(data.books);
                    renderPagination(totalPages, currentPage);
                    updateBookCounts(data.books.length, totalBooks, page, limit);
                    try {
                        sessionStorage.setItem('bookManagementPage', currentPage);
                    } catch (e) {}
                } else {
                    throw new Error(data.message || "Invalid data format from server.");
                }
            } catch (err) {
                console.error("Fetch books error:", err);
                bookTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="7" class="text-center text-red-500 py-10">Error loading books: ${err.message}</td></tr>`;
                updateBookCounts(0, 0, 1, limit);
                try {
                    sessionStorage.removeItem('bookManagementPage');
                } catch (e) {}
            } finally {
                isLoading = false;
            }
        }

        // ==========================
        // RENDER TABLE FUNCTION
        // ==========================
        const renderBooks = (booksToRender) => {
            bookTableBody.innerHTML = "";

            if (!booksToRender || booksToRender.length === 0) {
                bookTableBody.innerHTML = `
                <tr data-placeholder="true">
                    <td colspan="7" class="py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="ph ph-books text-5xl mb-3"></i>
                            <p class="font-medium text-gray-700">No books found</p>
                            <p class="text-sm text-gray-500">No books match your current filters.</p>
                        </div>
                    </td>
                </tr>
            `;
                return;
            }

            let rowsHtml = "";
            booksToRender.forEach((book) => {
                const statusColor = book.availability === "available" ? "bg-green-600" : book.availability === "borrowed" ? "bg-orange-500" : "bg-gray-600";
                const title = book.title ? String(book.title).replace(/</g, "&lt;") : 'N/A';
                const author = book.author ? String(book.author).replace(/</g, "&lt;") : 'N/A';
                const accession = book.accession_number ? String(book.accession_number).replace(/</g, "&lt;") : 'N/A';
                const call = book.call_number ? String(book.call_number).replace(/</g, "&lt;") : 'N/A';
                const isbn = book.book_isbn ? String(book.book_isbn).replace(/</g, "&lt;") : 'N/A';
                const status = book.availability ? String(book.availability).replace(/</g, "&lt;") : 'N/A';
                const safeTitle = title.replace(/'/g, "\\'").replace(/"/g, "&quot;");

                rowsHtml += `
                <tr>
                    <td class="py-3 px-4">
                        <div class="max-w-[240px] ">
                            <p class="font-medium text-gray-800 whitespace-normal break-words">${title}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4 truncate max-w-[240px] whitespace-normal break-words">${author}</td>
                    <td class="px-4 py-3">${accession}</td>
                    <td class="px-4 py-3">${call}</td>
                    <td class="px-4 py-3">${isbn}</td>
                    <td class="py-3 px-4">
                        <span class="text-white text-xs px-3 py-1 rounded-full ${statusColor}">
                            ${status}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="editBook(${book.book_id})"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-pencil pointer-events-none'></i>
                        </button>
                        <button onclick="deleteBook(${book.book_id}, '${safeTitle}')"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-trash pointer-events-none'></i>
                        </button>
                    </td>
                </tr>`;
            });
            bookTableBody.innerHTML = rowsHtml;
        };

        // ==========================
        // PAGINATION RENDER (GAYA NG SA BOOKCATALOG.JS)
        // ==========================
        function renderPagination(totalPages, page) {
            if (totalPages <= 1) {
                paginationControls.className = "flex justify-center mt-8 hidden";
                return;
            }

            paginationList.innerHTML = '';

            const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
                const li = document.createElement("li");
                const a = document.createElement("a");
                a.href = "#";
                a.setAttribute("data-page", String(pageNum));
                let baseClasses = `flex items-center justify-center min-w-[32px] h-9 text-sm font-medium transition-all duration-200`;
                if (type === "prev" || type === "next") {
                    a.innerHTML = text;
                    baseClasses += ` text-gray-700 hover:text-orange-600 px-3`;
                    if (isDisabled) baseClasses += ` opacity-50 cursor-not-allowed pointer-events-none`;
                } else if (type === "ellipsis") {
                    a.textContent = text;
                    baseClasses += ` text-gray-400 cursor-default px-2`;
                } else {
                    a.textContent = text;
                    if (isActive) {
                        baseClasses += ` text-white bg-orange-600 rounded-full shadow-sm px-3`;
                    } else {
                        baseClasses += ` text-gray-700 hover:text-orange-600 hover:bg-orange-100 rounded-full px-3`;
                    }
                }
                a.className = baseClasses;
                li.appendChild(a);
                paginationList.appendChild(li);
            };

            paginationControls.className = `flex items-center justify-center bg-white border border-gray-200 rounded-full shadow-md px-4 py-2 mt-6 w-fit mx-auto gap-3`;

            createPageLink("prev", `<i class="flex ph ph-caret-left text-lg"></i> Previous`, page - 1, page === 1);
            const window = 2;
            let pagesToShow = new Set([1, totalPages, page]);
            for (let i = 1; i <= window; i++) {
                if (page - i > 0) pagesToShow.add(page - i);
                if (page + i <= totalPages) pagesToShow.add(page + i);
            }
            const sortedPages = [...pagesToShow].sort((a, b) => a - b);
            let lastPage = 0;
            for (const p of sortedPages) {
                if (p > lastPage + 1) createPageLink("ellipsis", "…", "...", true);
                createPageLink("number", p, p, false, p === page);
                lastPage = p;
            }
            createPageLink("next", `Next <i class="flex ph ph-caret-right text-lg"></i>`, page + 1, page === totalPages);
        }

        // PAGINATION CLICK
        paginationList.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoading) return;
            const target = e.target.closest('a[data-page]');
            if (!target) return;
            const pageStr = target.dataset.page;
            if (pageStr === '...') return;
            const pageNum = parseInt(pageStr, 10);
            if (!isNaN(pageNum) && pageNum !== currentPage) {
                loadBooks(pageNum);
            }
        });

        // ==========================
        // COUNT UPDATE FUNCTION
        // ==========================
        function updateBookCounts(booksLength, totalCountNum, page, perPage) {
            if (resultsIndicator) {
                if (totalCountNum === 0) {
                    resultsIndicator.innerHTML = `Showing <span id="bookCount" class="font-medium text-gray-800">0</span> of <span id="bookTotal" class="font-medium text-gray-800">0</span> books`;
                } else {
                    const startItem = (page - 1) * perPage + 1;
                    const endItem = (page - 1) * perPage + booksLength;
                    resultsIndicator.innerHTML = `Showing <span id="bookCount" class="font-medium text-gray-800">${startItem}-${endItem}</span> of <span id="bookTotal" class="font-medium text-gray-800">${totalCountNum.toLocaleString()}</span> books`;
                }
            }
        }

        // ==========================
        // ACTIONS (ADD, EDIT, DELETE, VIEW)
        // ==========================
        addBookForm?.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(addBookForm);
            
            if (!formData.get('accession_number') || !formData.get('call_number') || !formData.get('title') || !formData.get('author')) {
                // 🟠 Warning Toast for Missing Info
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
                                    <h3 class="text-[15px] font-semibold text-orange-600">Missing Information</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Please fill in all required fields (*).</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
                    },
                });
                return;
            }

            // 🔵 Loading Animation for Add Book
            Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                        <p class="text-gray-700 text-[14px]">Adding book...<br><span class="text-sm text-gray-500">Processing request.</span></p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
                },
            });

            try {
                const res = await fetch(`${getApiBaseUrl()}/booksmanagement/store`, {
                    method: "POST",
                    body: formData
                });
                
                const result = await res.json();
                
                Swal.close();

                if (result.success) {
                    // 🟢 Success Toast for Add Book
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
                                        <h3 class="text-[15px] font-semibold text-green-600">Book Added!</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Book added successfully!'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] backdrop-blur-sm shadow-[0_0_8px_#22c55e70]",
                        },
                    });
                    closeModal(addBookModal);
                    addBookForm.reset();
                    previewContainer.classList.add('hidden');
                    uploadText.textContent = 'Upload Image';
                    loadBooks(1);
                } else {
                    // 🔴 Error Toast for Add Book Failure
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
                                        <h3 class="text-[15px] font-semibold text-red-600">Add Book Failed</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Failed to add book.'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (err) {
                console.error("Add book error:", err);
                Swal.close();
                // 🔴 Error Toast for Network/Fetch Error
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
                                    <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while adding the book.</p>
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

        window.viewBook = async (bookId) => {
            if (!bookId) return;
            // No SweetAlert needed, since a custom modal is used for viewing.
            try {
                const res = await fetch(`${getApiBaseUrl()}/booksmanagement/get/${bookId}`);
                if (!res.ok) throw new Error("Failed to fetch book details.");

                const data = await res.json();
                if (data.success && data.book) {
                    const book = data.book;
                    if (book.cover) {
                        viewModalImg.src = book.cover;
                        viewModalImg.classList.remove("hidden");
                    } else {
                        viewModalImg.classList.add("hidden");
                        viewModalImg.src = '';
                    }
                    viewModalTitle.textContent = book.title || 'No Title';
                    viewModalAuthor.textContent = "by " + (book.author || "Unknown");
                    const availabilityText = (book.availability || "unknown").toUpperCase();
                    viewModalStatus.textContent = availabilityText;
                    viewModalStatus.className = `font-semibold text-sm ${availabilityText === "AVAILABLE" ? "text-green-600" : "text-orange-600"}`;
                    viewModalCallNumber.textContent = book.call_number || "N/A";
                    viewModalAccessionNumber.textContent = book.accession_number || "N/A";
                    viewModalIsbn.textContent = book.book_isbn || "N/A";
                    viewModalSubject.textContent = book.subject || "N/A";
                    viewModalPlace.textContent = book.book_place || "N/A";
                    viewModalPublisher.textContent = book.book_publisher || "N/A";
                    viewModalYear.textContent = book.year || "N/A";
                    viewModalEdition.textContent = book.book_edition || "N/A";
                    viewModalSupplementary.textContent = book.book_supplementary || "N/A";
                    viewModalDescription.textContent = book.description || "No description available.";
                    openModal(viewBookModal);
                } else {
                    // 🔴 Error Toast for View Book Failure
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
                                        <h3 class="text-[15px] font-semibold text-red-600">Error</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${data.message || 'Could not find book details.'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (err) {
                console.error("View book fetch error:", err);
                // 🔴 Error Toast for Network/Fetch Error
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
                                    <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while fetching book data.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });
            }
        };

        window.editBook = async (bookId) => {
            if (!bookId) return;
            currentEditingBookId = bookId;
            try {
                const res = await fetch(`${getApiBaseUrl()}/booksmanagement/get/${bookId}`);
                if (!res.ok) throw new Error("Failed to fetch book details.");
                const data = await res.json();
                if (data.success && data.book) {
                    const book = data.book;
                    document.getElementById("edit_book_id").value = book.book_id || '';
                    document.getElementById("edit_accession_number").value = book.accession_number || '';
                    document.getElementById("edit_call_number").value = book.call_number || '';
                    document.getElementById("edit_title").value = book.title || '';
                    document.getElementById("edit_author").value = book.author || '';
                    document.getElementById("edit_book_isbn").value = book.book_isbn || '';
                    document.getElementById("edit_book_place").value = book.book_place || '';
                    document.getElementById("edit_book_publisher").value = book.book_publisher || '';
                    document.getElementById("edit_year").value = book.year || '';
                    document.getElementById("edit_book_edition").value = book.book_edition || '';
                    document.getElementById("edit_book_supplementary").value = book.book_supplementary || '';
                    document.getElementById("edit_subject").value = book.subject || '';
                    document.getElementById("edit_description").value = book.description || '';
                    editUploadText.textContent = 'Change Image';
                    editInput.value = '';
                    if (book.cover) {
                        editPreviewImage.src = book.cover;
                        editPreviewContainer.classList.remove('hidden');
                    } else {
                        editPreviewContainer.classList.add('hidden');
                        editPreviewImage.src = '';
                    }
                    openModal(editBookModal);
                } else {
                    // 🔴 Error Toast for Edit Book Fetch Failure
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
                                        <h3 class="text-[15px] font-semibold text-red-600">Error</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${data.message || 'Could not find book details for editing.'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (err) {
                console.error("Edit book fetch error:", err);
                // 🔴 Error Toast for Network/Fetch Error
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
                                    <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while fetching book data for editing.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });
            }
        };

        editBookForm?.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (!currentEditingBookId) return;
            const formData = new FormData(editBookForm);

            if (!formData.get('accession_number') || !formData.get('call_number') || !formData.get('title') || !formData.get('author')) {
                 // 🟠 Warning Toast for Missing Info
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
                                    <h3 class="text-[15px] font-semibold text-orange-600">Missing Information</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Please fill in all required fields (*).</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
                    },
                });
                return;
            }

            // 🔵 Loading Animation for Edit Book
            Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                        <p class="text-gray-700 text-[14px]">Updating book...<br><span class="text-sm text-gray-500">Processing request.</span></p>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
                },
            });

            try {
                const res = await fetch(`${getApiBaseUrl()}/booksmanagement/update/${currentEditingBookId}`, {
                    method: "POST",
                    body: formData
                });

                const result = await res.json();
                
                Swal.close();

                if (result.success) {
                    // 🟢 Success Toast for Edit Book
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
                                        <h3 class="text-[15px] font-semibold text-green-600">Book Updated!</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Book updated successfully!'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] backdrop-blur-sm shadow-[0_0_8px_#22c55e70]",
                        },
                    });
                    closeModal(editBookModal);
                    loadBooks(currentPage);
                } else {
                    // 🔴 Error Toast for Edit Book Failure
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
                                        <h3 class="text-[15px] font-semibold text-red-600">Update Failed</h3>
                                        <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Failed to update book.'}</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } catch (err) {
                console.error("Update book error:", err);
                Swal.close();
                // 🔴 Error Toast for Network/Fetch Error
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
                                    <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while updating the book.</p>
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

        window.deleteBook = async (bookId, title) => {
            if (!bookId) return;

            // 🟠 Confirmation Alert for Delete (ORANGE Confirmation Button)
            const result = await Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col text-center">
                        <div class="flex justify-center mb-3">
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                                <i class="ph ph-trash text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-[17px] font-semibold text-orange-700">Delete Book?</h3>
                        <p class="text-[14px] text-gray-700 mt-1">
                            Are you sure you want to permanently delete: <span class="font-semibold">"${title}"</span>?
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "Cancel",
                customClass: {
                    popup:
                        "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
                    confirmButton: // ORANGE BUTTON AS REQUESTED
                        "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
                    cancelButton:
                        "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
                },
            });

            if (result.isConfirmed) {

                // 🔵 Loading Animation (RED theme for deletion process border/spinner)
                Swal.fire({
                    background: "transparent",
                    html: `
                        <div class="flex flex-col items-center justify-center gap-2">
                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-red-200 border-t-red-600"></div>
                            <p class="text-gray-700 text-[14px]">Deleting book...<br><span class="text-sm text-gray-500">Just a moment.</span></p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: {
                        // RED THEME ang loading para ipakita ang deletion
                        popup:
                            "!rounded-xl !shadow-md !border-2 !border-red-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });

                try {
                    const res = await fetch(`${getApiBaseUrl()}/booksmanagement/delete/${bookId}`, {
                        method: "POST"
                    });
                    
                    const result = await res.json();
                    
                    Swal.close();

                    if (result.success) {
                        // 🟢 Success Toast for Delete
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
                                            <h3 class="text-[15px] font-semibold text-green-600">Deleted!</h3>
                                            <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Book deleted successfully!'}</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            customClass: {
                                popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
                            },
                        });
                        loadBooks(currentPage);
                    } else {
                        // 🔴 Error Toast for Delete Failure
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
                                            <h3 class="text-[15px] font-semibold text-red-600">Deletion Failed</h3>
                                            <p class="text-[13px] text-gray-700 mt-0.5">${result.message || 'Failed to delete book.'}</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            customClass: {
                                popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                            },
                        });
                    }
                } catch (err) {
                    console.error("Delete book error:", err);
                    Swal.close();
                    // 🔴 Error Toast for Network/Fetch Error
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
                                        <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while deleting the book.</p>
                                    </div>
                                </div>
                            </div>
                        `,
                        customClass: {
                            popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        },
                    });
                }
            } else {
                // ❌ Cancel toast for Delete
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "360px",
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Cancelled</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">The book was not deleted.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup:
                            "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    },
                });
            }
        };

        // ==========================
        // INIT
        // ==========================
        loadBooks(currentPage); // Initial load
    });
