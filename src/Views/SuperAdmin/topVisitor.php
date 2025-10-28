<main class="min-h-screen">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-4">Library Reports</h2>
            <p class="text-gray-700">Comprehensive library statistics and analytics dashboard</p>
        </div>
        <div class="flex gap-2 text-sm">
            <button
                class="inline-flex items-center bg-white font-medium border border-orange-200 justify-center px-4 py-2 rounded-lg hover:bg-gray-100 gap-2"
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
                    <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-2 pr-6 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm" id="circulatedBooksFilter">
                        <option value="month">Month</option>
                        <option value="semester">Semester</option>
                        <option value="year" selected>Year</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Category</th>
                            <th scope="col" class="px-4 py-3 text-center">2025</th>
                            <th scope="col" class="px-4 py-3 text-center">2026</th>
                            <th scope="col" class="px-4 py-3 text-center">2027</th>
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
                    <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-1 px-2 pr-6 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-sm" id="libraryVisitFilter">
                        <option value="month">Month</option>
                        <option value="semester">Semester</option>
                        <option value="year" selected>Year</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </h3>
            <div class="overflow-x-auto rounded-lg border border-orange-200">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-orange-50 text-gray-700 border-b border-orange-100">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left">Course</th>
                            <th scope="col" class="px-4 py-3 text-center">2025</th>
                            <th scope="col" class="px-4 py-3 text-center">2026</th>
                            <th scope="col" class="px-4 py-3 text-center">2027</th>
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
</main>

<!-- Download Report Modal -->
<div id="downloadReportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <!-- Close button -->
        <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <i class="ph ph-x-bold text-lg"></i>
        </button>

        <h3 class="text-xl font-semibold mb-4">Download Library Report</h3>
        <p class="text-gray-600 text-sm mb-4">Select the date range for your report.</p>

        <form id="downloadReportForm" class="flex flex-col gap-4">
            <div>
                <label for="startDate" class="block text-gray-700 mb-1 font-medium">Start Date</label>
                <input type="date" id="startDate" name="start_date"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-500">
            </div>
            <div>
                <label for="endDate" class="block text-gray-700 mb-1 font-medium">End Date</label>
                <input type="date" id="endDate" name="end_date"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-500">
            </div>

            <button type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 rounded-lg transition-colors duration-200">
                Download
            </button>
        </form>
    </div>
</div>

<script src="/LibSys/public/js/superadmin/reports.js"></script>