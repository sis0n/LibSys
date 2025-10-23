<div class="flex items-center mb-4">
    <i class="ph ph-qr-code text-4xl text-gray-700 mr-2"></i>
    <h1 class="text-2xl font-bold">QR Code Scanner</h1>
</div>
<p class="mb-8 text-gray-500">Scan student QR tickets for borrowing and returning books & equipment.</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">

    <!-- Left Column: Scanner -->
    <div class="bg-white p-6 rounded-lg shadow-md flex flex-col">
        <div class="flex flex-col flex-grow">
            <h2 class="text-xl font-semibold mb-2">Scanner</h2>
            <p class="text-gray-500 mb-6">Present QR code to scan or enter manually</p>

            <div
                class="bg-orange-50 border border-orange-200 rounded-lg text-center p-8 mb-6 flex flex-col justify-center flex-grow transition-all duration-300">
                <div class="flex justify-center items-center mb-4 flex-grow">
                    <div class="bg-white shadow-md rounded-full inline-flex items-center justify-center p-4 sm:p-6">
                        <i class="ph ph-scan text-7xl sm:text-9xl text-orange-400"></i>
                    </div>
                </div>
                <p class="font-semibold text-gray-700">Present your QR code to librarian</p>
                <p class="text-sm text-gray-500">Position the QR code within the scanning area</p>

                <!-- Simulate button -->
                <button id="simulateScanBtn"
                    class="mt-6 bg-orange-500 text-white py-2 px-6 rounded-lg hover:bg-orange-600 transition">
                    Simulate Scan (Demo)
                </button>
            </div>
        </div>

        <!-- Manual Ticket Entry always stays at bottom -->
        <div class="mt-auto">
            <h3 class="font-semibold text-gray-700 mb-2">Manual Ticket Entry</h3>
            <div class="flex gap-2">
                <input type="text" placeholder="Enter ticket ID"
                    class="flex-grow p-2 bg-orange-50 border border-orange-200 rounded-lg text-sm">
                <button
                    class="bg-white border rounded-lg border-orange-200 px-4 py-2 text-gray-700 font-semibold text-sm hover:bg-orange-400 hover:text-white transition">
                    Verify
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Scan Result -->
    <div id="scanResultCard" class="bg-white p-6 rounded-lg shadow-md h-full flex flex-col">

        <div>
            <h2 class="text-xl font-semibold mb-2">Scan Result</h2>
            <p class="text-gray-500 mb-6">Review ticket details and process transaction</p>

            <div class="text-center py-16">
                <div class="flex justify-center items-center mb-4">
                    <div class="bg-orange-100 rounded-full w-20 h-20 flex items-center justify-center">
                        <i class="ph ph-x text-4xl text-orange-500"></i>
                    </div>
                </div>
                <p class="font-semibold text-gray-700">No ticket scanned yet</p>
                <p class="text-sm text-gray-500">Present QR code or enter ticket ID manually</p>
            </div>
        </div>
    </div>
</div>


<!-- transaction History  -->
<section class="bg-white shadow-md rounded-lg border border-gray-200 p-6 mb-6 mt-6">
    <!-- Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <h4 class="text-base font-semibold text-gray-800">Transaction History</h4>
            <p class="text-sm text-gray-600">View and filter recent borrowing and return transactions</p>
        </div>

        <!-- Right-side filters -->
        <div class="flex items-center gap-2 text-sm flex-wrap sm:flex-nowrap">

            <!-- Search Input -->
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="transactionSearchInput" placeholder="Search by student..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm w-48 focus:ring-1 focus:ring-orange-400">
            </div>

            <!-- Date Picker -->
            <div class="relative">
                <input type="date" id="transactionDate" name="transactionDate" value="<?= date('Y-m-d') ?>"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-700 w-36 focus:ring-1 focus:ring-orange-400">
            </div>

            <!-- Status Filter -->
            <div class="relative inline-block text-left">
                <button id="statusFilterBtn"
                    class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                    <span id="statusFilterValue">All Status</span>
                    <i class="ph ph-caret-down text-xs"></i>
                </button>
                <div id="statusFilterMenu"
                    class="filter-dropdown-menu absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20 text-sm">
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="All Status">All
                        Status</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Borrowed">
                        Borrowed</div>
                    <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer" data-value="Returned">
                        Returned</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Transaction Table -->
    <div class="overflow-x-auto rounded-lg border border-orange-200">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Student
                        ID</th>
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
                <!-- dynamic data goes here -->
            </tbody>
        </table>
    </div>
</section>


<script src="/libsys/public/js/librarian/qrScanner.js" defer></script>