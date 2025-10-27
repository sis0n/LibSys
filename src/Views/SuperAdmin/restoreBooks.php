<main class="min-h-screen">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <i class="ph-fill ph-book-open-text text-3xl text-gray-700"></i>
        <div>
            <h2 class="text-2xl font-bold mb-1">Restore Books</h2>
            <p class="text-gray-500">Recover book records from your previous backups and restore them to the system.</p>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-funnel text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Search & Filter</h3>
        </div>
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="bookSearchInput" placeholder="Search by title, author, or accession number..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-11 pr-4 py-2.5 w-full text-sm outline-none transition">
            </div>
            <!-- Filter Dropdown -->
            <div class="relative">
                <button id="bookFilterDropdownBtn"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex items-center gap-2 w-44 justify-between">
                    <span>Default Order</span>
                    <i class="ph ph-caret-down"></i>
                </button>

                <!-- Dropdown menu -->
                <div id="bookFilterDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Default Order">Default Order</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Title (A-Z)">Title (A-Z)</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Title (Z-A)">Title (Z-A)</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Deleted Date (Newest)">Deleted Date (Newest)</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Deleted Date (Oldest)">Deleted Date (Oldest)</a>
                </div>
            </div>

            <!-- Calendar Filter -->
            <div class="relative">
                <input type="date" id="deletedBookDateFilter"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-700 w-40 focus:ring-1 focus:ring-orange-400">
            </div>
        </div>
    </div>

    <!-- Deleted Books Table Section -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-book-bookmark text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Deleted Books (<span id="deletedBooksCount">0</span>)</h3>
        </div>
        <p class="text-gray-600 mb-6">Books that have been deleted can be restored or permanently removed.</p>

        <div class="overflow-x-auto rounded-lg border border-orange-200">
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-orange-100">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[240px]">
                                Book Title
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[180px]">
                                Author
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                Accession Number
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                Deleted Date
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[140px]">
                                Deleted By
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="deletedBooksTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
            <p id="noDeletedBooksFound" class="text-gray-500 text-center w-full py-10 hidden">
                <i class="ph ph-book-open-text text-4xl block mb-2 text-gray-400"></i>
                No deleted books found.
            </p>
        </div>

        <!-- Pagination Controls -->
        <div id="pagination-container" class="flex justify-center items-center mt-6 hidden">
            <nav class="bg-white px-8 py-3 rounded-full shadow-md border border-gray-200">
                <ul class="flex items-center gap-4 text-sm">
                    <!-- Previous -->
                    <li>
                        <a href="#" id="prev-page"
                            class="flex items-center gap-1 text-gray-400 hover:text-gray-600 transition">
                            <i class="ph ph-caret-left"></i>
                            <span>Previous</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <div id="pagination-numbers" class="flex items-center gap-3">
                        <!-- JS will insert page numbers here -->
                    </div>

                    <!-- Next -->
                    <li>
                        <a href="#" id="next-page"
                            class="flex items-center gap-1 text-gray-400 hover:text-gray-600 transition">
                            <span>Next</span>
                            <i class="ph ph-caret-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</main>

<template id="deleted-book-row-template">
    <tr class="hover:bg-orange-50 cursor-pointer deleted-book-row">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900 book-title"></div>
            <div class="text-xs text-gray-500 book-isbn"></div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 book-author"></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 book-accession-number"></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 book-deleted-date"></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 book-deleted-by"></td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button
                class="restore-btn inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-2">
                <i class="ph ph-arrow-counter-clockwise text-lg mr-1"></i> Restore
            </button>
            <button
                class="delete-btn inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <i class="ph ph-trash text-lg mr-1"></i> Delete
            </button>
        </td>
    </tr>
</template>

<script src="/libsys/public/js/superadmin/restoreBooks.js"></script>

<!-- Book Details Modal -->
<div id="bookDetailsModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg flex flex-col">
        <!-- Header -->
        <div class="px-5 py-3 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-orange-600">Deleted Book Details</h3>
                <p class="text-sm text-gray-500">Information about this deleted book</p>
            </div>
            <button id="closeBookDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="p-5 space-y-4 overflow-y-auto max-h-[80vh]">
            <!-- Book Info -->
            <div class="border border-gray-200 rounded-lg p-3 shadow-sm">
                <h4 class="text-xs font-semibold text-orange-500 mb-2 uppercase tracking-wider">Book Information</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                    <div>
                        <p class="text-gray-500">Accession No.</p>
                        <p id="modalBookAccessionNumber" class="font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Call No.</p>
                        <p id="modalBookCallNumber" class="font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">ISBN</p>
                        <p id="modalBookIsbn" class="font-medium text-gray-800"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Title</p>
                        <p id="modalBookTitle" class="font-medium text-gray-800"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Author(s)</p>
                        <p id="modalBookAuthor" class="font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Publisher</p>
                        <p id="modalBookPublisher" class="font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Year</p>
                        <p id="modalBookYear" class="font-medium text-gray-800"></p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-gray-500">Subject</p>
                        <p id="modalBookSubject" class="font-medium text-gray-800"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Place of Publication</p>
                        <p id="modalBookPlace" class="font-medium text-gray-800"></p>
                    </div>
                </div>
            </div>

            <!-- Deletion Info -->
            <div class="border border-gray-200 rounded-lg p-3 shadow-sm">
                <h4 class="text-xs font-semibold text-orange-500 mb-2 uppercase tracking-wider">Deletion Details</h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <p class="text-gray-500">Created Date</p>
                        <p id="modalBookCreatedDate" class="font-medium text-gray-800"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Deleted Date</p>
                        <p id="modalBookDeletedDate" class="font-medium text-gray-800"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Deleted By</p>
                        <p id="modalBookDeletedBy" class="font-medium text-gray-800"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>