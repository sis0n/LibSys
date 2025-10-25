<!--Header -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center gap-3">
            <div>
                <h2 class="text-2xl font-bold mb-4">Transaction History</h2>
                <p class="text-gray-700">Complete log of all book borrowing transactions with detailed tracking.</p>
            </div>
        </div>
        <button
            class="border border-orange-100 shadow-sm bg-white rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i class="ph ph-download-simple"></i>
            Export Data
        </button>
    </div>
</div>

<!-- Filter and Search -->
<div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
    <div class="flex items-center gap-3 mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Filter & Search</h3>
            <p class="text-sm text-amber-700">Refine your search to find specific borrowing records</p>
        </div>
    </div>

    <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
        <!-- Search Bar -->
        <div class="relative flex-grow">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="transactionSearchInput" placeholder="Search"
                class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-full">
        </div>

        <!-- Date Picker -->
        <div class="relative">
            <input type="date" id="transactionDate" name="transactionDate"
                class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-400 w-40">
        </div>

        <!-- Status Dropdown -->
        <div class="relative inline-block text-left">
            <button id="statusFilterBtn"
                class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-500 flex items-center justify-between gap-2 w-40 hover:bg-orange-50 transition">
                <span id="statusFilterValue">All Status</span>
                <i class="ph ph-caret-down text-xs"></i>
            </button>
            <div id="statusFilterMenu"
                class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20 text-sm">
                <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="All Status">All
                    Status</div>
                <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Borrowed">Borrowed
                </div>
                <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Returned">Returned
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History Table -->
<section class="bg-white shadow-md rounded-lg border border-gray-200 p-6 mb-6 mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h4 class="text-base font-semibold text-gray-800">Transaction History</h4>
            <p class="text-sm text-gray-600">View recent borrowing and return transactions</p>
        </div>


    </div>

    <div class="overflow-x-auto rounded-lg border border-orange-200">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        Number</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Items
                        Borrowed</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Borrowed
                        Date/Time</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Returned
                        Date/Time</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Status
                    </th>
                </tr>
            </thead>
            <tbody id="transactionHistoryTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Default state -->
                <tr id="no-transactions-found">
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <div class="p-4 bg-orange-50 rounded-full mb-4">
                                <i class="ph ph-clock text-4xl text-orange-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">No transactions found</h3>
                            <p class="text-sm">There are no recent borrowing or return activities matching the filters.
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <template id="transaction-row-template">
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="px-3 py-1 rounded-full font-medium text-xs"></span>
                </td>
            </tr>
        </template>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="flex justify-center items-center mt-6">
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
</section>

<script src="/libsys/public/js/SuperAdmin/transactionHistory.js"></script>