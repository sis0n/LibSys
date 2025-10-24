<div class="min-h-screen">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Returning</h2>
        <p class="text-gray-500 mt-4">Scan book barcode to process returns and manage due dates</p>
    </div>

    <!-- Scanner and Manual Input -->
    <div class="bg-white rounded-lg p-6 shadow-sm max-w-7xl mx-auto">
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
                    class="flex-grow p-3 border  border-orange-200/80 rounded-md bg-orange-50/40  placeholder-orange-800/40 text-gray-800">
                <button id="scan-button"
                    class="bg-orange-500 text-white px-5 py-3 rounded-md flex items-center gap-2 hover:bg-orange-600  border border-orange-500">
                    <i class="ph ph-barcode"></i>
                    <span>Scan</span>
                </button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-3 text-center">
            <i class="ph ph-info"></i>
            Use a barcode scanner or manually enter the Barcode or Accession Number
        </p>

    </div>

    <!-- Books Near Due Date -->
    <div class="mt-8 bg-amber-50/60 border border-amber-200/80 rounded-lg p-6 shadow-sm max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-4">
            <i class="ph ph-warning text-2xl text-amber-500"></i>
            <h3 class="text-xl font-semibold text-gray-800">Books Near Due Date</h3>
        </div>
        <p class="text-gray-600 -mt-2 ml-9">Books that will be due soon (within 7 days)</p>

        <div class="mt-4 bg-white border border-gray-200/80 rounded-lg overflow-hidden">
            <table class="w-full books-near-due-table">
                <thead class="bg-stone-50">
                    <tr class="border-b border-gray-200/80">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Student Info</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Item Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/80">

                </tbody>
            </table>
        </div>
    </div>

    <!-- Overdue Books -->
    <div class="mt-8 bg-red-50/60 border border-red-200/80 rounded-lg p-6 shadow-sm max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-4">
            <i class="ph ph-warning text-2xl text-red-500"></i>
            <h3 class="text-xl font-semibold text-gray-800">Overdue Books</h3>
        </div>
        <p class="text-gray-600 -mt-2 ml-9">Books that are past their due date</p>

        <div class="mt-4 bg-white border border-gray-200/80 rounded-lg overflow-hidden">
            <table class="w-full overdue-books-table">
                <thead class="bg-stone-50">
                    <tr class="border-b border-gray-200/80">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Student Info</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Item Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date Borrowed</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/80">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Borrowed Modal -->
<div id="return-modal" class="fixed inset-0 backdrop-blur-sm bg-black/30 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 mx-4">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Book Barcode Scanned Successfully</h3>
                    <p class="text-gray-500 text-sm">Book information retrieved from the system</p>
                </div>
            </div>
            <button id="modal-close-button" class="text-gray-400 hover:text-gray-600">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <!-- Book Information -->
        <div class="bg-stone-50 border border-stone-200 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-xs text-gray-500">Title</p>
                    <h4 id="modal-book-title" class="font-bold text-gray-800 text-lg"></h4>
                    <p id="modal-book-author" class="text-sm text-gray-600 mt-0.5"></p>
                </div>
                <span id="modal-book-status"
                    class="bg-orange-200 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full"></span>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-3">
                <div>
                    <p class="text-xs text-gray-500">ISBN</p>
                    <p id="modal-book-isbn" class="text-sm font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Book ID</p>
                    <p id="modal-book-id" class="text-sm font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Call Number</p>
                    <p id="modal-book-callnumber" class="text-sm font-medium text-gray-700"></p>
                </div>
            </div>
        </div>

        <!-- Borrower Information -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-3">
                <i class="ph ph-user text-gray-600"></i>
                <h5 class="font-semibold text-gray-800">Borrower Information</h5>
            </div>
            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                <div>
                    <p class="text-xs text-gray-500">Name</p>
                    <p id="modal-borrower-name" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Student ID</p>
                    <p id="modal-student-id" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Course</p>
                    <p id="modal-borrower-course" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Year & Section</p>
                    <p id="modal-borrower-year-section" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Email</p>
                    <p id="modal-borrower-email" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Contact</p>
                    <p id="modal-borrower-contact" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Borrow Date</p>
                    <p id="modal-borrow-date" class="font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Due Date</p>
                    <p id="modal-due-date" class="font-medium text-gray-700"></p>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex justify-end items-center gap-3 mt-6">
            <button id="modal-cancel-button"
                class="text-xs px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 flex items-center gap-2">
                Cancel
            </button>
            <button
                class="text-xs px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 flex items-center gap-2">
                <i class="ph ph-calendar"></i> Extend Due Date
            </button>
            <button
                class="text-xs px-6 py-2.5 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 flex items-center gap-2">
                <i class="ph ph-check-circle"></i> Mark as Returned
            </button>
        </div>
    </div>
</div>

<!-- Available Modal -->
<div id="available-book-modal"
    class="fixed inset-0 backdrop-blur-sm bg-black/30 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 mx-4">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Book Barcode Scanned Successfully</h3>
                    <p class="text-gray-500 text-sm">Book information retrieved from the system</p>
                </div>
            </div>
            <button id="available-modal-close-button" class="text-gray-400 hover:text-gray-600">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <!-- Book Information -->
        <div class="bg-stone-50 border border-stone-200 rounded-lg p-4 mb-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-xs text-gray-500">Title</p>
                    <h4 id="available-modal-title" class="font-bold text-gray-800 text-lg"></h4>
                    <p id="available-modal-author" class="text-sm text-gray-600 mt-0.5"></p>
                </div>
                <span id="available-modal-status" 
                    class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-full"></span>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-3">
                <div>
                    <p class="text-xs text-gray-500">ISBN</p>
                    <p id="available-modal-isbn" class="text-sm font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Accession No.</p>
                    <p id="available-modal-accession" class="text-sm font-medium text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Call Number</p>
                    <p id="available-modal-call-number" class="text-sm font-medium text-gray-700">
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Book Information -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-3">
                <i class="ph ph-book text-gray-600"></i>
                <h5 class="font-semibold text-gray-800">Book Details</h5>
            </div>
            <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                <div>
                    <p class="text-xs text-gray-500">Subject</p>
                    <p id="available-modal-subject" class="font-medium text-gray-700">Biographies--Biography...</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Place</p>
                    <p id="available-modal-place" class="font-medium text-gray-700">Quezon City</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Publisher</p>
                    <p id="available-modal-publisher" class="font-medium text-gray-700">C & E Publishing, Inc.</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Year</p>
                    <p id="available-modal-year" class="font-medium text-gray-700">2018</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Edition</p>
                    <p id="available-modal-edition" class="font-medium text-gray-700">N/A</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Supplementary</p>
                    <p id="available-modal-supplementary" class="font-medium text-gray-700">N/A</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">Description</p>
                    <p id="available-modal-description" class="font-medium text-gray-700 leading-relaxed">181 pages ;
                        illustrations ; 24 cm</p>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="flex justify-end items-center mt-6">
            <button id="available-modal-close-action"
                class="text-xs px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100 flex items-center gap-2">
                Close
            </button>
        </div>
    </div>
</div>

<script src="/libsys/public/js/superadmin/returning.js"></script>