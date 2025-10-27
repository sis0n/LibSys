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
            <h3 class="text-lg font-medium mb-4">Circulated Books</h3>
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
            <h3 class="text-lg font-medium mb-4">Library Visit (by Course)</h3>
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
                            <th scope="col" class="px-4 py-3 text-center">Visits</th>
                        </tr>
                    </thead>
                    <tbody id="top-visitors-tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script src="/LibSys/public/js/superadmin/reports.js"></script>