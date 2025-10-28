<main class="min-h-screen">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-4">Library Reports</h2>
            <p class="text-gray-700">Comprehensive library statistics and analytics dashboard</p>
        </div>
        <div class="flex gap-2 text-sm">
            <button
                class="inline-flex items-center bg-white font-medium border border-orange-200 justify-center px-4 py-2 rounded-lg hover:bg-gray-100 px-4 gap-2"
                id="download-report-btn">
                <i class="ph ph-download-simple"></i>
                Download Report
            </button>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Top Visitors Chart -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-800 flex items-center gap-2">
                    <i class="ph ph-chart-bar text-green-500"></i>
                    Top Visitors
                </h3>
                <span class="text-sm bg-orange-100 text-orange-600 font-medium px-3 py-1 rounded-md">This Month</span>
            </div>
            <canvas id="topVisitorsChart"></canvas>
        </div>

        <!-- Weekly Activity Chart -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-800 flex items-center gap-2">
                    <i class="ph ph-chart-line text-blue-500"></i>
                    Weekly Activity
                </h3>
                <p class="text-sm text-gray-500">Daily visitors and book checkouts</p>
            </div>
            <canvas id="weeklyActivityChart"></canvas>
        </div>
    </div>

    <!-- First Row of Tables -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Library Resources Table -->
        <div class="bg-white border border-orange-200 rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium mb-4">Library Resources</h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-center">YEAR</th>
                            <th scope="col" class="px-4 py-3 text-center">TITLE</th>
                            <th scope="col" class="px-4 py-3 text-center">VOLUME</th>
                            <th scope="col" class="px-4 py-3 text-center">PROCESSED</th>
                        </tr>
                    </thead>
                    <tbody id="library-resources-tbody"></tbody>
                </table>
            </div>
        </div>

        <!-- Circulated Books Table -->
        <div class="bg-white border border-orange-200 rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium mb-4 flex items-center justify-between">
                Circulated Books
                <div class="relative">
                    <select
                        class="block appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-2 pr-6 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm"
                        id="circulatedBooksFilter">
                        <option value="month" selected>Monthly</option>
                        <option value="year">Yearly</option> 
                        <option value="custom">Custom Date</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                        <i class="ph ph-caret-down"></i>
                    </div>
                </div>
            </h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Category</th>
                            <th scope="col" class="px-4 py-3 text-center">Today</th>
                            <th scope="col" class="px-4 py-3 text-center">Weekly</th>
                            <th scope="col" class="px-4 py-3 text-center">Monthly</th>
                        </tr>
                    </thead>
                    <tbody id="circulated-books-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Second Row of Tables -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Library Visit (by Course) Table -->
        <div class="bg-white border border-orange-200 rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium mb-4 flex items-center justify-between">
                Library Visit (by Course)
                <div class="relative">
                    <select
                        class="block appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-2 pr-6 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm"
                        id="libraryVisitFilter">
                        <option value="month"selected>Monthly</option>
                        <option value="year">Yearly</option>
                        <option value="custom">Custom Date</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                        <i class="ph ph-caret-down"></i>
                    </div>
                </div>
            </h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Course</th>
                            <th scope="col" class="px-4 py-3 text-center">Today</th>
                            <th scope="col" class="px-4 py-3 text-center">Weekly</th>
                            <th scope="col" class="px-4 py-3 text-center">Monthly</th>
                        </tr>
                    </thead>
                    <tbody id="library-visit-tbody"></tbody>
                </table>
            </div>
        </div>

        <!-- Top 10 Visitors Table -->
        <div class="bg-white border border-orange-200 rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium mb-4">Top 10 Visitors (By School Year)</h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Rank</th>
                            <th scope="col" class="px-4 py-3 text-left">Name</th>
                            <th scope="col" class="px-4 py-3 text-center">Student ID</th>
                            <th scope="col" class="px-4 py-3 text-center">Course</th>
                            <th scope="col" class="px-4 py-3 text-center">Visits</th>
                        </tr>
                    </thead>
                    <tbody id="top-visitors-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom Date Modal -->
    <div id="customDateModal"
        class="fixed inset-0 bg-black-400/20 backdrop-blur-md overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-40 mx-auto p-5 w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Select Custom Date Range</h3>
                <div class="mt-4 px-7 py-3">
                    <div class="mb-4">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 text-left">Start
                            Date</label>
                        <input type="date" id="startDate" name="startDate"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 text-left">End Date</label>
                        <input type="date" id="endDate" name="endDate"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDateRange"
                        class="px-4 py-2 bg-orange-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        Confirm
                    </button>
                    <button id="cancelDateRange"
                        class="mt-2 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="/LibSys/public/js/superadmin/reports.js"></script>