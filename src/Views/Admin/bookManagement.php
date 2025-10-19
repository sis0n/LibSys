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

                    <!-- ISBN -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ISBN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="book_isbn"
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

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" rows="3"
                            class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition"></textarea>
                    </div>
                </form>
                <!-- Footer Buttons -->
                <div class="flex justify-end gap-3 p-6 border-t border-[var(--color-border)] flex-shrink-0">
                    <button type="submit" form="addBookForm"
                        class="flex-1 bg-orange-600 text-white font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-700 transition">
                        Add Book
                    </button>
                    <button type="button" id="cancelAddBook"
                        class="border border-orange-200 text-gray-800 font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-50 transition">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- TABLE BODY -->
<div class="bg-[var(--color-card)] border border-orange-200 rounded-xl shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Book Management</h3>
            <p class="text-sm text-gray-600">Registered Books in the system</p>
        </div>
        <!-- SEARCHBAR -->
        <div class="flex items-center text-sm">
            <div class="relative w-[330px]">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="userSearchInput" placeholder="Search by title, author, ISBN, Accession #..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-full focus:ring-1 focus:ring-orange-300">
            </div>

            <!-- FILTERING -->
            <div class="relative inline-block text-left ml-3">
                <button id="sortDropdownBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-44 hover:bg-orange-50 transition">
                    <span class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-sort-ascending text-gray-500"></i>
                        <span id="sortDropdownValue">Default Order</span>
                    </span>
                    <i class="ph ph-caret-down text-xs"></i>
                </button>

                <div id="sortDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'Default Order')">Default Order</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'Title (ascending)')">Title (A-Z)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'Title (descending)')">Title (Z-A)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'Year (newest)')">Year (newest)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'Year (oldest)')">Year (oldest)</div>

                </div>
            </div>
            <!-- STATUS DROPDOWN -->
            <div class="relative inline-block text-left ml-3">
                <button id="statusDropdownBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                    <span>
                        <i class="ph ph-check-circle text-gray-500"></i>
                        <span id="statusDropdownValue">All Status</span>
                    </span>
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
                    <th class="py-3 px-4 font-medium">Book Title</th>
                    <th class="py-3 px-4 font-medium">Author</th>
                    <th class="py-3 px-4 font-medium">Accession Number</th>
                    <th class="py-3 px-4 font-medium">Call Number</th>
                    <th class="py-3 px-4 font-medium">IBSN</th>
                    <th class="py-3 px-4 font-medium">Status</th>
                    <th class="py-3 px-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody" class="divide-y divide-orange-100 bg-white"></tbody>
        </table>
    </div>
</div>

<!-- EDIT BOOK MODAL -->
<div id="editBookModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <!-- Modal Card -->
    <div
        class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md h-[85vh] flex flex-col animate-fadeIn">

        <!-- Header -->
        <div class="flex justify-between items-start p-6 border-b border-[var(--color-border)] flex-shrink-0">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit Book Details</h2>
                <p class="text-sm text-gray-500 mt-1">Modify book information below.</p>
            </div>
            <button id="closeEditBookModal" class="text-gray-500 hover:text-red-700 transition">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <!-- FORM -->
        <form id="editBookForm" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Accession Number <span
                        class="text-red-500">*</span></label>
                <input type="text" id="edit_accession_number" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Call Number <span
                        class="text-red-500">*</span></label>
                <input type="text" id="edit_call_number" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span
                        class="text-red-500">*</span></label>
                <input type="text" id="edit_title" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author <span
                        class="text-red-500">*</span></label>
                <input type="text" id="edit_author" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN <span
                        class="text-red-500">*</span></label>
                <input type="text" id="edit_book_isbn"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Place of Publication</label>
                <input type="text" id="edit_book_place"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                <input type="text" id="edit_book_publisher"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Published</label>
                <input type="number" id="edit_year" min="0"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edition</label>
                <input type="text" id="edit_book_edition"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplementary Info</label>
                <input type="text" id="edit_book_supplementary"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="edit_subject"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="edit_description" rows="3"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition"></textarea>
            </div>
        </form>

        <!-- Footer Buttons -->
        <div class="flex justify-end gap-3 p-6 border-t border-[var(--color-border)] flex-shrink-0">
            <button type="submit" form="editBookForm"
                class="flex-1 bg-orange-600 text-white font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-700 transition">
                Save Changes
            </button>
            <button type="button" id="cancelEditBook"
                class="border border-orange-200 text-gray-800 font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-50 transition">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
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

    const editBookModal = document.getElementById("editBookModal");
    const closeEditBookModal = document.getElementById("closeEditBookModal");
    const cancelEditBook = document.getElementById("cancelEditBook");
    const editBookForm = document.getElementById("editBookForm");

    // ==========================
    // UNIVERSAL MODAL HELPERS
    // ==========================
    function openModal(modal) {
        modal.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    }

    function closeModal(modal) {
        modal.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
    }

    // ==========================
    // BULK IMPORT
    // ==========================
    bulkImportBtn?.addEventListener("click", () => openModal(importModal));
    closeImportModal?.addEventListener("click", () => closeModal(importModal));
    cancelImport?.addEventListener("click", () => closeModal(importModal));
    importModal?.addEventListener("click", e => {
        if (e.target === importModal) closeModal(importModal);
    });

    // ==========================
    // ADD BOOK
    // ==========================
    openAddBookBtn?.addEventListener("click", () => openModal(addBookModal));
    closeAddBookModal?.addEventListener("click", () => closeModal(addBookModal));
    cancelAddBook?.addEventListener("click", () => closeModal(addBookModal));
    addBookModal?.addEventListener("click", e => {
        if (e.target === addBookModal) closeModal(addBookModal);
    });

    // ==========================
    // EDIT BOOK
    // ==========================
    closeEditBookModal?.addEventListener("click", () => closeModal(editBookModal));
    cancelEditBook?.addEventListener("click", () => closeModal(editBookModal));

    // ==========================
    // DROPDOWN LOGIC
    // ==========================
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;

        btn.addEventListener("click", e => {
            e.stopPropagation();
            menu.classList.toggle("hidden");
        });

        document.addEventListener("click", () => menu.classList.add("hidden"));
    }

    // Initialize both dropdowns
    setupDropdown("statusDropdownBtn", "statusDropdownMenu");
    setupDropdown("sortDropdownBtn", "sortDropdownMenu");

    // ==========================
    // SAMPLE BOOK DATA
    // ==========================
    let books = [{
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "1714",
            callnumber: "A 536 M4661 1908",
            ibsn: "1561343455",
            year: 2004,
            status: "available"
        },
        {
            title: "asfddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd",
            author: "By: asfdddddddddd ddddddddddddddd ddddddddddddddddddddddd dddddddddddddddddddddddd ddddddddddddddd",
            accessionnumber: "1714",
            callnumber: "A 536 M4661 1908",
            ibsn: "1561343455",
            year: 2004,
            status: "available"
        },
        {
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "1714",
            callnumber: "A 536 M4661 1908",
            ibsn: "1561343455",
            year: 2004,
            status: "available"
        },
        {
            title: "Book Title",
            author: "By: Author",
            accessionnumber: "1714",
            callnumber: "A 536 M4661 1908",
            ibsn: "1561343455",
            year: 2004,
            status: "available"
        },
    ];

    let editingIndex = null;

    // ==========================
    // RENDER FUNCTION
    // ==========================
    const renderBooks = () => {
        const tbody = document.getElementById("bookTableBody");
        tbody.innerHTML = "";
        if (books.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="ph ph-books text-5xl mb-3"></i>
                            <p class="font-medium text-gray-700">No books found</p>
                            <p class="text-sm text-gray-500">There are no books available in the library record.</p>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById("bookCount").textContent = 0;
            document.getElementById("bookTotal").textContent = 0;
            return;
        }

        books.forEach((book, index) => {
            const statusColor =
                book.status === "available" ?
                "bg-green-600" :
                book.status === "borrowed" ?
                "bg-orange-500" :
                "bg-gray-600";

            tbody.innerHTML += `
                <tr>
                    <td class="py-3 px-4">
                        <div class="max-w-[240px] ">
                            <p class="font-medium text-gray-800 whitespace-normal break-words"">${book.title || 'N/A'}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4 truncate max-w-[240px] whitespace-normal break-words"">${book.author || 'N/A'}</td>
                    <td class="py-3 px-4">${book.accessionnumber || 'N/A'}</td>
                    <td class="py-3 px-4">${book.callnumber || 'N/A'}</td>
                    <td class="py-3 px-4">${book.ibsn || 'N/A'}</td>
                    <td class="py-3 px-4">
                        <span class="text-white text-xs px-3 py-1 rounded-full ${statusColor}">
                            ${book.status || 'N/A'}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="editBook(${index})"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-pencil'></i>
                        </button>
                        <button onclick="deleteBook(${index})"
                            class="border border-orange-300 text-orange-700 px-2 py-1 rounded hover:bg-orange-100">
                            <i class='ph ph-trash'></i>
                        </button>
                    </td>
                </tr>`;

        });

        document.getElementById("bookCount").textContent = books.length;
        document.getElementById("bookTotal").textContent = books.length;
    };

    // ==========================
    // EDIT FUNCTION
    // ==========================
    window.editBook = index => {
        const book = books[index];
        editingIndex = index;

        document.getElementById("edit_accession_number").value = book.accessionnumber || "";
        document.getElementById("edit_call_number").value = book.callnumber || "";
        document.getElementById("edit_title").value = book.title || "";
        document.getElementById("edit_author").value = book.author || "";
        document.getElementById("edit_book_isbn").value = book.ibsn || "";
        document.getElementById("edit_year").value = book.year || "";


        openModal(editBookModal);
    };

    editBookForm?.addEventListener("submit", e => {
        e.preventDefault();
        if (editingIndex === null) return;

        books[editingIndex].title = document.getElementById("edit_title").value.trim();
        books[editingIndex].author = document.getElementById("edit_author").value.trim();
        books[editingIndex].year = document.getElementById("edit_year").value.trim();
        closeModal(editBookModal);
        renderBooks();
    });

    // ==========================
    // INIT
    // ==========================
    renderBooks();
});
</script>