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