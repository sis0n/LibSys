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

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Records -->
    <div class="bg-white border-l-4 border-orange-400 rounded-lg shadow-sm p-5 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Total Records</p>
            <p class="text-2xl font-bold text-gray-800">3</p>
        </div>
        <div>
            <i class="ph ph-clock-counter-clockwise text-2xl text-orange-500"></i>
        </div>
    </div>
    <!-- Currently Borrowed -->
    <div class="bg-white border-l-4 border-green-400 rounded-lg shadow-sm p-5 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Currently Borrowed</p>
            <p class="text-2xl font-bold text-gray-800">1</p>
        </div>
        <div>
            <i class="ph ph-book-open text-2xl text-green-500"></i>
        </div>
    </div>
    <!-- Returned -->
    <div class="bg-white border-l-4 border-blue-400 rounded-lg shadow-sm p-5 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Returned</p>
            <p class="text-2xl font-bold text-gray-800">1</p>
        </div>
        <div>
            <i class="ph ph-calendar-check text-2xl text-blue-500"></i>
        </div>
    </div>
    <!-- Overdue -->
    <div class="bg-white border-l-4 border-red-400 rounded-lg shadow-sm p-5 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500">Overdue</p>
            <p class="text-2xl font-bold text-gray-800">1</p>
        </div>
        <div>
            <i class="ph ph-timer text-2xl text-red-500"></i>
        </div>
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
                class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-full focus:ring-1 focus:ring-orange-400">
        </div>

        <!-- Date Picker -->
        <div class="relative">
            <input type="date" id="transactionDate" name="transactionDate"
                class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-400 w-40 focus:ring-1 focus:ring-orange-400">
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
            </tbody>
        </table>
    </div>
</section>

<script src="/libsys/public/js/SuperAdmin/transactionHistory.js"></script>