<div class="min-h-screen">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Returning</h2>
        <p class="text-gray-500 mt-1">Scan book barcode to process returns and manage due dates</p>
    </div>

    <div class="bg-white border border-orange-200/80 rounded-lg p-6 shadow-sm min-w-3xl">
        <div class="flex items-center gap-3">
            <i class="ph ph-barcode text-2xl text-gray-700"></i>
            <h3 class="text-xl font-semibold text-gray-800">Scan Book Barcode</h3>
        </div>
        <p class="text-gray-600 mt-1 mb-6 ml-9">
            Enter or scan the book's barcode to view details and process return
        </p>
        <!-- Scanner -->
        <div class="flex justify-center">
            <div class="w-full max-w-4xl bg-white border border-orange-200 rounded-lg p-8 shadow-sm text-center">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <i class="ph ph-qr-code text-3xl text-orange-600"></i>
                    <h4 class="text-xl font-semibold text-gray-800">Book QR Scanner</h4>
                </div>

                <p class="text-gray-600 mb-6">
                    Scan the book’s barcode or QR code to retrieve its details automatically.
                </p>

                <!-- QR Code Scanner Box -->
                <form action="/libsys/public/scanner/scan" method="POST" class="inline-block">
                    <div id="qrBox"
                        class="w-40 h-40 sm:w-48 sm:h-48 mx-auto border-2 border-dashed border-orange-300 rounded-lg flex items-center justify-center bg-orange-50">
                        <i class="ph ph-qr-code text-orange-500 text-8xl sm:text-9xl"></i>
                    </div>

                    <!-- Hidden QR input -->
                    <input type="text" id="qrCodeValue" name="qrCodeValue"
                        class="absolute opacity-0 pointer-events-none" tabindex="-1" autocomplete="off">
                </form>
            </div>
        </div>

        <!-- Manual -->
        <div class="flex justify-center mt-6">
            <div class="flex items-center w-full max-w-4xl gap-3">
                <input type="text" placeholder="Enter book barcode or Accession Number"
                    class="flex-grow p-3 border  border-orange-200/80 rounded-md bg-orange-50/40 placeholder-orange-800/40 text-gray-800">
                <button
                    class="bg-orange-500 text-white px-5 py-3 rounded-md flex items-center gap-2 hover:bg-orange-600 border-orange-500">
                    <i class="ph ph-barcode"></i>
                    <span>Scan</span>
                </button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3 text-center">
            Tip: Use a barcode scanner or manually enter the Barcode or Accession Number
        </p>

    </div>

    <!-- Books Near Due Date -->
    <div class="mt-8 bg-amber-50/60 border border-amber-200/80 rounded-lg p-6 shadow-sm min-w-3xl">
        <div class="flex items-center gap-3 mb-4">
            <i class="ph ph-warning text-2xl text-amber-500"></i>
            <h3 class="text-xl font-semibold text-gray-800">Books Near Due Date</h3>
        </div>
        <p class="text-gray-600 -mt-2 ml-9">Books that will be due soon (within 7 days)</p>

        <div class="mt-4 bg-white border border-gray-200/80 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-stone-50">
                    <tr class="border-b border-gray-200/80">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Student Info</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Item Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Days Until Due</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/80">
                    <tr>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-gray-800">Alex Johnson</div>
                            <div class="text-gray-500 text-xs">ST-2024-001</div>
                            <div class="text-gray-500 text-xs">BS Computer Science - 3rd A</div>
                        </td>
                        <td class="px-6 py-4 align-top text-gray-800">Introduction to Algorithms</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-09-15</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-25</td>
                        <td class="px-6 py-4 align-top text-gray-800">+63 912 345 6789</td>
                        <td class="px-6 py-4 align-top">
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full">4 days</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-gray-800">Sarah Lee</div>
                            <div class="text-gray-500 text-xs">ST-2024-007</div>
                            <div class="text-gray-500 text-xs">BS Civil Engineering - 4th C</div>
                        </td>
                        <td class="px-6 py-4 align-top text-gray-800">Structural Analysis</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-05</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-22</td>
                        <td class="px-6 py-4 align-top text-gray-800">+63 915 234 5678</td>
                        <td class="px-6 py-4 align-top">
                            <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full">1 day</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overdue Books -->
    <div class="mt-8 bg-red-50/60 border border-red-200/80 rounded-lg p-6 shadow-sm min-w-3xl">
        <div class="flex items-center gap-3 mb-4">
            <i class="ph ph-warning text-2xl text-red-500"></i>
            <h3 class="text-xl font-semibold text-gray-800">Overdue Books</h3>
        </div>
        <p class="text-gray-600 -mt-2 ml-9">Books that are past their due date</p>

        <div class="mt-4 bg-white border border-gray-200/80 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-stone-50">
                    <tr class="border-b border-gray-200/80">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Student Info</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Item Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Days Overdue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/80">
                    <!-- Row 1 -->
                    <tr>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-gray-800">David Chen</div>
                            <div class="text-gray-500 text-xs">ST-2024-003</div>
                            <div class="text-gray-500 text-xs">BS Accountancy - 2nd B</div>
                        </td>
                        <td class="px-6 py-4 align-top text-gray-800">Financial Accounting Principles</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-09-10</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-10</td>
                        <td class="px-6 py-4 align-top text-gray-800">+63 917 654 3210</td>
                        <td class="px-6 py-4 align-top">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">11 days</span>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-gray-800">Emma Wilson</div>
                            <div class="text-gray-500 text-xs">ST-2024-009</div>
                            <div class="text-gray-500 text-xs">BS Psychology - 3rd A</div>
                        </td>
                        <td class="px-6 py-4 align-top text-gray-800">Cognitive Psychology</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-09-25</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-15</td>
                        <td class="px-6 py-4 align-top text-gray-800">+63 920 123 4567</td>
                        <td class="px-6 py-4 align-top">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">6 days</span>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-gray-800">James Rodriguez</div>
                            <div class="text-gray-500 text-xs">ST-2024-012</div>
                            <div class="text-gray-500 text-xs">BS Marketing - 1st D</div>
                        </td>
                        <td class="px-6 py-4 align-top text-gray-800">Marketing Management</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-09-20</td>
                        <td class="px-6 py-4 align-top text-gray-800">2025-10-12</td>
                        <td class="px-6 py-4 align-top text-gray-800">+63 922 987 6543</td>
                        <td class="px-6 py-4 align-top">
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">9 days</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>