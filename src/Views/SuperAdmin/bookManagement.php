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
        <div id="importModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <div
                class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md p-6 animate-fadeIn">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold">Bulk Import Users</h2>
                    <button id="closeImportModal" class="text-gray-500 hover:text-red-700 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-4">
                    Import multiple books from a CSV file or use sample data.
                </p>
                <form id="bulkImportForm" enctype="multipart/form-data">
                    <label for="csvFile"
                        class="block border-2 border-dashed border-[var(--color-border)] rounded-lg p-8 text-center cursor-pointer hover:border-[var(--color-ring)]/60 transition">
                        <i class="ph ph-upload text-[var(--color-ring)] text-3xl mb-2 block"></i>
                        <p class="font-medium text-[var(--color-ring)]">Drop CSV file here or click to browse</p>
                        <p class="text-xs text-gray-500 mt-1">Expected format: accession_number,call_number,title</p>
                        <input type="file" id="csvFile" accept=".csv" class="hidden" />
                    </label>
                </form>
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
        <div id="addBookModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
            <div
                class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md h-[85vh] flex flex-col animate-fadeIn">
                <div class="flex justify-between items-start p-6 border-b border-[var(--color-border)] flex-shrink-0">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Add New Book</h2>
                        <p class="text-sm text-gray-500 mt-1">Add a new book to the library catalog.</p>
                    </div>
                    <button id="closeAddBookModal" class="text-gray-500 hover:text-red-700 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="addBookForm" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Accession Number <span class="text-red-500">*</span> </label>
                        <input type="text" name="accession_number" required class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Call Number <span class="text-red-500">*</span> </label>
                        <input type="text" name="call_number" required class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Title <span class="text-red-500">*</span> </label>
                        <input type="text" name="title" required class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Author <span class="text-red-500">*</span> </label>
                        <input type="text" name="author" required class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> ISBN <span class="text-red-500">*</span> </label>
                        <input type="text" name="book_isbn" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Place of Publication </label>
                        <input type="text" name="book_place" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Publisher </label>
                        <input type="text" name="book_publisher" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Year Published </label>
                        <input type="number" name="year" min="0" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Edition </label>
                        <input type="text" name="book_edition" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Supplementary Info </label>
                        <input type="text" name="book_supplementary" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Subject </label>
                        <input type="text" name="subject" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"> Description </label>
                        <textarea name="description" rows="3" class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Book Image</label>
                        <label for="book_image" class="cursor-pointer flex items-center justify-center gap-2 w-full text-orange-700 border border-orange-200 rounded-md px-3 py-2 text-sm font-medium hover:bg-orange-100 transition">
                            <i class="ph ph-image-square text-lg"></i>
                            <span id="uploadText">Upload Image</span>
                        </label>
                        <input type="file" id="book_image" name="book_image" accept="image/*" class="hidden">
                        <div id="previewContainer" class="mt-2 hidden">
                            <img id="previewImage" class="w-32 h-48 object-cover rounded-lg border border-orange-200 shadow-sm" />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Supported file types: JPG, PNG. <br> Recommended: 400×600 (2:3 ratio) </p>
                    </div>
                </form>
                <div class="flex justify-end gap-3 p-6 border-t border-[var(--color-border)] flex-shrink-0">
                    <button type="submit" form="addBookForm" class="flex-1 bg-orange-600 text-white font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-700 transition">
                        Add Book
                    </button>
                    <button type="button" id="cancelAddBook" class="border border-orange-200 text-gray-800 font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-50 transition">
                        Cancel
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
        <div class="flex items-center text-sm">
            <div class="relative w-[330px]">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="bookSearchInput" placeholder="Search by title, author, isbn..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-full focus:ring-1 focus:ring-orange-300">
            </div>
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
                        onclick="selectSort(this, 'default')">Default Order</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'title_asc')">Title (A-Z)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'title_desc')">Title (Z-A)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'year_desc')">Year (newest)</div>
                    <div class="sort-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                        onclick="selectSort(this, 'year_asc')">Year (oldest)</div>
                </div>
            </div>
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

    <h4 id="resultsIndicator" class="text-sm text-gray-600 mb-3">
        Loading...
    </h4>

    <div class="overflow-hidden border border-orange-200 rounded-lg shadow-sm">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-orange-100 text-left text-gray-800">
                <tr>
                    <th class="py-3 px-4 font-medium">Book Title</th>
                    <th class="py-3 px-4 font-medium">Author</th>
                    <th class="py-3 px-4 font-medium">Accession Number</th>
                    <th class="py-3 px-4 font-medium">Call Number</th>
                    <th class="py-3 px-4 font-medium">ISBN</th>
                    <th class="py-3 px-4 font-medium">Status</th>
                    <th class="py-3 px-4 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="bookTableBody" class="divide-y divide-orange-100 bg-white">
                <tr data-placeholder="true">
                    <td colspan="7" class="py-10 text-center text-gray-500">
                        <i class="ph ph-spinner animate-spin text-2xl"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <nav id="paginationControls" aria-label="Page navigation"
        class="flex items-center justify-center bg-white border border-gray-200 rounded-full shadow-md px-4 py-2 mt-6 w-fit mx-auto gap-3 hidden">
        <ul id="paginationList" class="flex items-center h-9 text-sm gap-3">
        </ul>
    </nav>
</div>

<div id="editBookModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
    <div
        class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md h-[85vh] flex flex-col animate-fadeIn">
        <div class="flex justify-between items-start p-6 border-b border-[var(--color-border)] flex-shrink-0">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit Book Details</h2>
                <p class="text-sm text-gray-500 mt-1">Modify book information below.</p>
            </div>
            <button id="closeEditBookModal" class="text-gray-500 hover:text-red-600 transition">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        <form id="editBookForm" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
            <input type="hidden" id="edit_book_id" name="book_id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Accession Number <span class="text-red-500">*</span></label>
                <input type="text" id="edit_accession_number" name="accession_number" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Call Number <span class="text-red-500">*</span></label>
                <input type="text" id="edit_call_number" name="call_number" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" id="edit_title" name="title" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author <span class="text-red-500">*</span></label>
                <input type="text" id="edit_author" name="author" required
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN <span class="text-red-500">*</span></label>
                <input type="text" id="edit_book_isbn" name="book_isbn"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Place of Publication</label>
                <input type="text" id="edit_book_place" name="book_place"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                <input type="text" id="edit_book_publisher" name="book_publisher"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Published</label>
                <input type="number" id="edit_year" name="year" min="0"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edition</label>
                <input type="text" id="edit_book_edition" name="book_edition"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplementary Info</label>
                <input type="text" id="edit_book_supplementary" name="book_supplementary"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="edit_subject" name="subject"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="edit_description" name="description" rows="3"
                    class="w-full bg-[var(--color-input)] border border-[var(--color-border)] rounded-md px-3 py-2 focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition"></textarea>
            </div>
            <div class="flex flex-col">
                <label class="block text-sm font-medium text-gray-700 mb-1">Book Image</label>
                <label for="edit_book_image"
                    class="cursor-pointer flex items-center justify-center gap-2 w-full text-orange-700 border border-orange-200 rounded-md px-3 py-2 text-sm font-medium hover:bg-orange-100 transition">
                    <i class="ph ph-image-square text-lg"></i>
                    <span id="editUploadText">Change Image</span>
                </label>
                <input type="file" id="edit_book_image" name="book_image" accept="image/*" class="hidden">
                <div id="editPreviewContainer" class="mt-2 hidden">
                    <img id="editPreviewImage"
                        class="w-32 h-48 object-cover rounded-lg border border-orange-200 shadow-sm" />
                </div>
                <p class="text-xs text-gray-500 mt-1">Recommended image size: 400×600 (2:3 ratio)</p>
            </div>
        </form>
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

<div id="viewBookModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300 ease-out p-4">
    <div id="viewBookModalContent" class="bg-[var(--color-card)] w-full max-w-lg rounded-2xl shadow-lg overflow-hidden transform scale-95 transition-transform duration-300 ease-out max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 p-4 text-white flex-shrink-0 flex justify-between items-center">
            <div class="flex items-center gap-3 overflow-hidden">
                <img id="viewModalImg" src="" alt="Book Cover" class="w-12 h-16 object-cover rounded-md bg-white flex-shrink-0 hidden" />
                <div class="overflow-hidden">
                    <h2 id="viewModalTitle" class="text-lg font-bold text-white truncate">Book Title</h2>
                    <p id="viewModalAuthor" class="text-sm truncate">by Author</p>
                </div>
            </div>
            <button id="closeViewModal" class="text-white text-3xl hover:text-red-500 transition-colors duration-200 flex-shrink-0 ml-2">
                <i class="ph ph-x-circle"></i>
            </button>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto">
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 shadow-sm border border-orange-100 bg-orange-50/50 rounded flex flex-col items-start">
                    <p class="text-xs text-orange-500 font-semibold mb-1">STATUS</p>
                    <p id="viewModalStatus" class="font-semibold text-sm">AVAILABLE</p>
                </div>
                <div class="p-3 shadow-sm border border-orange-100 bg-orange-50/50 rounded flex flex-col items-start">
                    <p class="text-xs text-orange-500 font-semibold mb-1">CALL NUMBER</p>
                    <p id="viewModalCallNumber" class="text-sm font-semibold">N/A</p>
                </div>
            </div>
            <div class="text-sm bg-white rounded-lg border border-gray-200 p-3 space-y-1.5">
                <p class="font-semibold text-gray-700 text-sm mb-1">Book Information</p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Accession #:</span> <span id="viewModalAccessionNumber" class="font-mono text-sm font-semibold text-orange-600 break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">ISBN:</span> <span id="viewModalIsbn" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Subject:</span> <span id="viewModalSubject" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Place:</span> <span id="viewModalPlace" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Publisher:</span> <span id="viewModalPublisher" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Year:</span> <span id="viewModalYear" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Edition:</span> <span id="viewModalEdition" class="break-words"></span></p>
                <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Supplementary:</span> <span id="viewModalSupplementary" class="break-words"></span></p>
            </div>
            <div class="bg-orange-50/30 rounded-lg p-3 border border-orange-100">
                <p class="font-semibold text-orange-800 mb-1 text-sm">Description</p>
                <p class="text-gray-700 text-sm" id="viewModalDescription"></p>
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 bg-gray-50 border-t border-gray-200 mt-auto flex-shrink-0">
            <button type="button" id="closeViewModalBtn" class="border border-gray-300 text-gray-800 font-medium px-4 py-2 text-sm rounded-md hover:bg-gray-100 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            try {
                const res = await fetch("/LibSys/public/superadmin/booksmanagement/bulkImport", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    if (importMessage) {
                        importMessage.textContent = `Imported: ${data.imported} rows successfully!`;
                        importMessage.classList.remove("hidden");
                        setTimeout(() => importMessage.classList.add("hidden"), 5000);
                    }

                    fileInput.value = "";
                    closeModal(document.getElementById("importModal"));

                    if (typeof loadUsers === "function") await loadUsers();
                } else {
                    alert("Error: " + (data.message || "Failed to import CSV."));
                }
            } catch (err) {
                console.error("Error importing CSV:", err);
                alert("Error importing CSV.");
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
                alert('Invalid file type! Please upload only JPG or PNG files.');
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
                alert('Invalid file type! Please upload only JPG or PNG files.');
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

            try {
                const params = new URLSearchParams({
                    search: currentSearch,
                    status: currentStatus === 'All Status' ? '' : currentStatus,
                    sort: currentSort,
                    limit: limit,
                    offset: offset
                });

                const res = await fetch(`${BASE_URL}/booksmanagement/fetch?${params.toString()}`);
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
                Swal.fire('Missing Info', 'Please fill in all required fields (*).', 'warning');
                return;
            }
            try {
                const res = await fetch(`${BASED_URL}/booksmanagement/store`, {
                    method: "POST",
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    Swal.fire('Success!', result.message || 'Book added!', 'success');
                    closeModal(addBookModal);
                    addBookForm.reset();
                    previewContainer.classList.add('hidden');
                    uploadText.textContent = 'Upload Image';
                    loadBooks(1);
                } else {
                    Swal.fire('Error', result.message || 'Failed to add book.', 'error');
                }
            } catch (err) {
                console.error("Add book error:", err);
                Swal.fire('Error', 'An error occurred.', 'error');
            }
        });

        window.viewBook = async (bookId) => {
            if (!bookId) return;
            try {
                const res = await fetch(`${BASED_URL}/booksmanagement/get/${bookId}`);
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
                    Swal.fire('Error', data.message || 'Could not find book details.', 'error');
                }
            } catch (err) {
                console.error("View book fetch error:", err);
                Swal.fire('Error', 'An error occurred while fetching book data.', 'error');
            }
        };

        window.editBook = async (bookId) => {
            if (!bookId) return;
            currentEditingBookId = bookId;
            try {
                const res = await fetch(`${BASED_URL}/booksmanagement/get/${bookId}`);
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
                    Swal.fire('Error', data.message || 'Could not find book details.', 'error');
                }
            } catch (err) {
                console.error("Edit book fetch error:", err);
                Swal.fire('Error', 'Error fetching book data.', 'error');
            }
        };

        editBookForm?.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (!currentEditingBookId) return;
            const formData = new FormData(editBookForm);
            if (!formData.get('accession_number') || !formData.get('call_number') || !formData.get('title') || !formData.get('author')) {
                Swal.fire('Missing Info', 'Please fill in all required fields (*).', 'warning');
                return;
            }
            try {
                const res = await fetch(`${BASE_URL}/booksmanagement/update/${currentEditingBookId}`, {
                    method: "POST",
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    Swal.fire('Success!', result.message || 'Book updated!', 'success');
                    closeModal(editBookModal);
                    loadBooks(currentPage);
                } else {
                    Swal.fire('Error', result.message || 'Failed to update book.', 'error');
                }
            } catch (err) {
                console.error("Update book error:", err);
                Swal.fire('Error', 'An error occurred.', 'error');
            }
        });

        window.deleteBook = async (bookId, title) => {
            if (!bookId) return;
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: `Delete "${title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            });
            if (result.isConfirmed) {
                try {
                    const res = await fetch(`${BASE_URL}/booksmanagement/delete/${bookId}`, {
                        method: "POST"
                    });
                    const result = await res.json();
                    if (result.success) {
                        Swal.fire('Deleted!', result.message || 'Book deleted.', 'success');
                        loadBooks(currentPage);
                    } else {
                        Swal.fire('Error', result.message || 'Failed to delete.', 'error');
                    }
                } catch (err) {
                    console.error("Delete book error:", err);
                    Swal.fire('Error', 'An error occurred.', 'error');
                }
            }
        };

        // ==========================
        // INIT
        // ==========================
        loadBooks(currentPage); // Initial load
    });
</script>