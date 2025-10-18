<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold mb-4">Book Management</h2>
        <p class="text-gray-700">Manage library books, availability, and inventory.</p>
    </div>
    <div class="flex gap-2 text-sm">
        <button
            class="inline-flex items-center bg-white font-medium border border-orange-200 justify-center px-4 py-2 rounded-lg hover:bg-gray-100 px-4 gap-2"
            id="bulkImportBtn">
            <i class="ph ph-upload-simple"></i>
            Bulk Import
        </button>
        <!-- BULK IMPORT -->
        <div id="importModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <!-- Modal Card -->
            <div
                class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md p-6 animate-fadeIn">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold">Bulk Import Users</h2>
                    <button id="closeImportModal" class="text-gray-500 hover:text-red-700 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4">
                    Import multiple books from a CSV file or use sample data.
                </p>

                <!-- Drop Zone -->
                <label for="csvFile"
                    class="block border-2 border-dashed border-[var(--color-border)] rounded-lg p-8 text-center cursor-pointer hover:border-[var(--color-ring)]/60 transition">
                    <i class="ph ph-upload text-[var(--color-ring)] text-3xl mb-2 block"></i>
                    <p class="font-medium text-[var(--color-ring)]">Drop CSV file here or click to browse</p>
                    <p class="text-xs text-gray-500 mt-1">Expected format: accession_number,call_number,title</p>
                    <input type="file" id="csvFile" accept=".csv" class="hidden" />
                </label>

                <!-- Cancel -->
                <div class="text-center mt-4">
                    <button id="cancelImport"
                        class="mt-2 border border-[var(--color-border)] px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <button
            class="px-4 py-2 bg-orange-500 text-white font-medium rounded-lg border hover:bg-orange-600 gap-2 inline-flex items-center"
            id="openAddBookBtn">
            <i class="ph ph-plus"></i>
            Add New Book
        </button>
        <!-- ADD BOOK MODAL -->
        <div id="addBookModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <!-- Modal Card -->
            <div
                class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md h-[85vh] flex flex-col animate-fadeIn">

                <!-- Header -->
                <div class="flex justify-between items-start p-6 border-b border-[var(--color-border)] flex-shrink-0">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Add New Book</h2>
                        <p class="text-sm text-gray-500 mt-1">Add a new book to the library catalog.</p>
                    </div>
                    <button id="closeAddBookModal" class="text-gray-500 hover:text-red-700 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>

               <!-- FORM -->
                <form id="addBookForm" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
                    <!-- Book ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Book ID <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="book_id" required
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Accession Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Accession Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="accession_number" required
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Call Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Call Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="call_number" required
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" required
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Author -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Author <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="author" required
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Place -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Place of Publication
                        </label>
                        <input type="text" id="book_place"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Publisher
                        </label>
                        <input type="text" id="book_publisher"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Year Published
                        </label>
                        <input type="number" id="year" min="0"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Edition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Edition
                        </label>
                        <input type="text" id="book_edition"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" rows="3"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition"></textarea>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ISBN
                        </label>
                        <input type="text" id="book_isbn"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Supplementary -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Supplementary Info
                        </label>
                        <input type="text" id="book_supplementary"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Subject
                        </label>
                        <input type="text" id="subject"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                </form>
                <!-- Footer Buttons -->
                <div class="flex justify-end gap-3 p-6 border-t border-[var(--color-border)] flex-shrink-0">
                    <button type="button" id="cancelAddBook"
                        class="border border-[var(--color-border)] px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        Cancel
                    </button>
                    <button type="submit" form="addBookForm"
                        class="px-5 py-2 bg-[var(--color-ring)] text-white font-medium rounded-md hover:opacity-90 transition">
                        Add Book
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-[var(--color-card)] border border-orange-200 rounded-xl shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Book Management</h3>
            <p class="text-sm text-gray-600">Registered Books in the system</p>
        </div>
        <!-- Filters -->
        <div class="flex items-center text-sm">
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="userSearchInput" placeholder="Search users..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm">
            </div>
            <!-- STATUS DROPDOWN -->
            <div class="relative inline-block text-left ml-3">
                <button id="statusDropdownBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                    <span id="statusDropdownValue">All Status</span>
                    <i class="ph ph-caret-down text-xs"></i>
                </button>
                <div id="statusDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                    <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectStatus(this, 'All Status')">All Status</div>
                    <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectStatus(this, 'Available')">Available</div>
                    <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectStatus(this, 'Borrowed')">Borrowed</div>
                </div>
            </div>
        </div>
    </div>
    <h4 class="text-sm text-gray-600 mb-3">
        Showing <span id="bookCount">0</span> of <span id="bookTotal">0</span> books
    </h4>
    <div class="overflow-hidden border border-orange-200 rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-orange-100 text-left text-gray-800">
                <tr>
                    <th class="py-3 px-4 font-medium">Book Details</th>
                    <th class="py-3 px-4 font-medium">Call Number</th>
                    <th class="py-3 px-4 font-medium">ISBN</th>
                    <th class="py-3 px-4 font-medium">Year</th>
                    <th class="py-3 px-4 font-medium">Status</th>
                    <th class="py-3 px-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody" class="divide-y divide-orange-100 bg-white"></tbody>
        </table>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
    // ==========================
    // DOM ELEMENTS
    // ==========================
    // BULK MODAL
    const modal = document.getElementById("importModal");
    const openBtn = document.getElementById("bulkImportBtn");
    const closeBtn = document.getElementById("closeImportModal");
    const cancelBtn = document.getElementById("cancelImport");

    // ADD BOOK MODAL
    const addBookModal = document.getElementById("addBookModal");
    const openAddBookBtn = document.getElementById("openAddBookBtn");
    const closeAddBookBtn = document.getElementById("closeAddBookModal");
    const cancelAddBookBtn = document.getElementById("cancelAddBook");
    const addBookForm = document.getElementById("addBookForm");

    // EDIT BOOK MODAL
    const editBookModal = document.getElementById("editBookModal");
    const closeEditBookBtn = document.getElementById("closeEditBookBtn");
    const cancelEditBookBtn = document.getElementById("cancelEditBookBtn");

    let currentEditingBookId = null;


    // ==========================
    // MODAL CONTROLS
    // ==========================
    function closeModal(modalEl) {
        if (modalEl) {
            modalEl.classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }
    }

    // BULK MODAL CONTROLS
    if (openBtn)
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        });

    [closeBtn, cancelBtn].forEach((btn) =>
        btn?.addEventListener("click", () => closeModal(modal))
    );

    modal?.addEventListener("click", (e) => {
        if (e.target === modal) closeModal(modal);
    });

    // ADD BOOK CONTROLS
    function closeAddBookModal() {
        closeModal(addBookModal);
    }

    if (openAddBookBtn)
        openAddBookBtn.addEventListener("click", () => {
            addBookModal.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        });

    [closeAddBookBtn, cancelAddBookBtn].forEach((btn) =>
        btn?.addEventListener("click", closeAddBookModal)
    );

    addBookModal?.addEventListener("click", (e) => {
        if (e.target === addBookModal) closeAddBookModal();
    });

    // EDIT BOOK CONTROLS
    function closeEditBookModal() {
        closeModal(editBookModal);
        currentEditingBookId = null;
    }

    [closeEditBookBtn, cancelEditBookBtn].forEach((btn) =>
        btn?.addEventListener("click", closeEditBookModal)
    );

    editBookModal?.addEventListener("click", (e) => {
        if (e.target === editBookModal) closeEditBookModal();
    });


    // ==========================
    // DROPDOWN LOGIC
    // ==========================
    function setupDropdownToggle(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            menu.classList.toggle("hidden");
        });
    }

    setupDropdownToggle("statusDropdownBtn", "statusDropdownMenu");
    setupDropdownToggle("editStatusDropdownBtn", "editStatusDropdownMenu");

    // Close dropdown when clicking outside
    document.addEventListener("click", () => {
        document.querySelectorAll(".absolute.mt-1, .absolute.w-full").forEach((menu) =>
            menu.classList.add("hidden")
        );
    });

    // Highlight active option
    function setActiveOption(containerId, selectedElement) {
        const items = document.querySelectorAll(`#${containerId} .status-item`);
        items.forEach((item) =>
            item.classList.remove("bg-orange-50", "font-semibold", "text-orange-700")
        );

        if (selectedElement && selectedElement.classList) {
            selectedElement.classList.add(
                "bg-orange-50",
                "font-semibold",
                "text-orange-700"
            );
        }
    }

    // STATUS
    window.selectStatus = (el, val) => {
        const valueEl = document.getElementById("statusDropdownValue");
        if (valueEl) valueEl.textContent = val;
        setActiveOption("statusDropdownMenu", el);
        if (typeof applyFilters === "function") applyFilters();
    };

    // EDIT STATUS
    window.selectEditStatus = (el, val) => {
        const valueEl = document.getElementById("editStatusDropdownValue");
        if (valueEl) valueEl.textContent = val;
        setActiveOption("editStatusDropdownMenu", el);
    };

    // DEFAULT ACTIVE STATUS
    const firstStatus = document.querySelector(
        "#statusDropdownMenu .status-item:first-child"
    );
    if (firstStatus) {
        setActiveOption("statusDropdownMenu", firstStatus);
        const textVal = firstStatus.textContent?.trim();
        const displayEl = document.getElementById("statusDropdownValue");
        if (displayEl) displayEl.textContent = textVal;
    }


    // ==========================
    // SAMPLE BOOK DATA
    // ==========================
    let books = [
        {
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "111-111-111-11",
            callnumber: "",
            ibsn: "123123123123",
            year: 2004,
            status: "available"
        },
        {
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "111-111-111-11",
            callnumber: "123123",
            ibsn: "",
            year: 2004,
            status: "undefined"
        },
        {
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "111-111-111-11",
            callnumber: "123123",
            ibsn: "123123123123",
            year: "",
            status: "borrowed"
        }
    ];


    // ==========================
    // RENDER FUNCTION
    // ==========================
    const renderBooks = () => {
        const tbody = document.getElementById("bookTableBody");
        tbody.innerHTML = "";

        books.forEach((book, index) => {
            const statusColor =
                book.status === "available"
                    ? "bg-green-600"
                    : book.status === "borrowed"
                    ? "bg-orange-500"
                    : "bg-gray-600";

            const statusText =
                book.status.charAt(0).toUpperCase() + book.status.slice(1);

            const row = `
                <tr>
                    <td class="py-3 px-4">
                        <div>
                            <p class="font-medium text-gray-800">${book.title || 'N/A'}</p>
                            <p class="text-sm text-gray-600">by ${book.author || 'N/A'}</p>
                            <p class="text-xs text-gray-400">Accession number: ${book.accessionnumber || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4">${book.callnumber || 'N/A'}</td>
                    <td class="py-3 px-4">${book.ibsn || 'N/A'}</td>
                    <td class="py-3 px-4">${book.year || 'N/A'}</td>
                    <td class="py-3 px-4">
                        <span class="text-white text-xs px-3 py-1 rounded-full ${statusColor}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center space-x-2">
                        <button onclick="editBook(${index})"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-pencil'></i>
                        </button>
                        <button onclick="deleteBook(${index})"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-trash'></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        document.getElementById("bookCount").textContent = books.length;
        document.getElementById("bookTotal").textContent = books.length;
    };


    // ==========================
    // ADD BOOK FUNCTIONALITY
    // ==========================
    addBookForm?.addEventListener("submit", (e) => {
        e.preventDefault();

        const newBook = {
            title: document.getElementById("bookTitle").value.trim() || "Untitled",
            author: document.getElementById("bookAuthor").value.trim() || "Unknown",
            ibsn: document.getElementById("bookISBN").value.trim(),
            category: document.getElementById("bookCategory").value.trim() || "N/A",
            location: document.getElementById("bookLocation").value.trim() || "N/A",
            copies: document.getElementById("bookCopies").value.trim() || 1,
            description:
                document.getElementById("bookDescription").value.trim() ||
                "No description provided",
            year: new Date().getFullYear(),
            accessionnumber: Math.floor(Math.random() * 999999999),
            status: "available",
        };

        books.push(newBook);
        renderBooks();
        closeAddBookModal();
        addBookForm.reset();
    });


    // ==========================
    // INITIAL RENDER
    // ==========================
    renderBooks();
});
</script>