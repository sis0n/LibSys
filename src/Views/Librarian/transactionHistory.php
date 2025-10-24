<h2 class="text-2xl font-bold mb-4">Transaction History</h2>
<p class="text-gray-700">Complete log of all book borrowing transactions with detailed tracking.</p>


<section class="bg-white shadow-md rounded-lg border border-gray-200 p-6 mb-6 mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h4 class="text-base font-semibold text-gray-800">Transaction History</h4>
            <p class="text-sm text-gray-600">View and filter recent borrowing and return transactions</p>
        </div>

        <div class="flex items-center gap-2 text-sm flex-wrap sm:flex-nowrap">

            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="transactionSearchInput" placeholder="Search by student..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-48 focus:ring-1 focus:ring-orange-400">
            </div>

            <div class="relative">
                <i class="ph ph-calendar absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                <input type="date" id="transactionDate" name="transactionDate" value=""
                    class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 pr-9 outline-none transition text-sm text-gray-700 w-36 focus:ring-1 focus:ring-orange-400">
            </div>

            <div class="relative inline-block text-left">
                <button id="statusFilterBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                    <span id="statusFilterValue">All Status</span>
                    <i class="ph ph-caret-down text-xs"></i>
                </button>
                <div id="statusFilterMenu"
                    class="filter-dropdown-menu absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20 text-sm">
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="All Status">
                        All
                        Status</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Borrowed">
                        Borrowed</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Returned">
                        Returned</div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-orange-200">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        NUMBER</th>
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
