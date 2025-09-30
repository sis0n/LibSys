<body class="min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">QR Borrowing Ticket</h2>
        <p class="text-gray-700">Your QR code for book borrowing and library access.</p>
    </div>
    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6 justify-center items-stretch">
        <!-- QR Ticket Card -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col items-center text-center">
            <!-- Title -->
            <h3 class="text-[var(--font-size-lg)] font-semibold mb-1">Your QR Ticket</h3>
            <p class="text-[var(--font-size-sm)] text-[var(--color-gray-600)] mb-6">
                Present this to the librarian
            </p>

            <!-- QR Code -->
            <div class="w-full p-4 border border-gray-300 rounded-lg bg-white flex justify-center ">

                <!-- Icon muna ikaw na sa generate ng qr tanggalin mo nalang -->
                <i class="ph ph-qr-code text-[150px] mt-10 mb-10"></i>
                <!-- <img src="" alt="QR Code" class="w-56 h-56 object-contain" /> -->
            </div>

            <!-- Ticket ID + Generated -->
            <div class="mt-4 text-[var(--font-size-sm)] text-[var(--color-gray-700)]">
                <p class="font-medium">
                    Ticket ID:
                    <!-- placeholder muna -->
                    <span class="text-[var(--color-primary)]">Ticket-Number</span>
                </p>
                <p class="text-[var(--font-size-xs)] text-[var(--color-gray-500)]">
                    <!-- eto rin placeholder muna -->
                    Generated: MM/DD/YYYY, HH:MM:SS AM/PM
                </p>
            </div>

            <!-- Download Button -->
            <button
                class="mt-4 flex items-center gap-2 px-4 py-2 rounded-[var(--radius-md)] bg-orange-500 text-[var(--color-primary-foreground)] font-medium shadow hover:bg-orange-600 transition">
                <i class="ph ph-download-simple text-xl"></i>
                Download
            </button>
        </div>

        <!-- Ticket Details Card -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col">
            <!-- Title -->
            <h3 class="text-md font-medium mb-1">Ticket Details</h3>
            <p class="text-sm text-amber-700 mb-5">
                Information encoded in your QR ticket
            </p>

            <!-- Info Grid Inline -->
            <dl class="space-y-3 text-sm flex-1">
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Student ID:</dt>
                    <dd class="text-right">########-S</dd>
                </div>

                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Name:</dt>
                    <dd class="text-right">Student Name</dd>
                </div>

                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Year & Section</dt>
                    <dd class="text-right break-all">Year-Section</dd>
                </div>

                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Course:</dt>
                    <dd class="text-right">BSCS</dd>
                </div>

                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Books:</dt>
                    <dd class="text-right">1 Book(s)</dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Equipments:</dt>
                    <dd class="text-right">0 item(s)</dd>
                </div>
            </dl>
            <!-- How to Use -->
            <div class="mt-6 p-4 rounded-[var(--radius-md)] bg-blue-50 border border-blue-200">
                <h4 class="text-md mb-2">How to use:</h4>
                <ol class="list-decimal list-inside space-y-1 text-xs text-amber-700">
                    <li>Show this QR code to the librarian</li>
                    <li>They will scan it to verify your identity</li>
                    <li>Proceed with book borrowing or return</li>
                    <li>Use cart checkout for specific book borrowing</li>
                </ol>
            </div>
        </div>
    </div>

        <!-- tanggalin mo nalang to pag ipapadisplay mo na,
         pangsumulation lang to
         Simulation Button -->
        <div class="flex justify-center mb-2">
            <button id="showItemsBtn"
                class="px-5 py-2 rounded-lg bg-[var(--color-primary)] text-[var(--color-primary-foreground)] font-medium shadow hover:bg-[var(--color-orange-600)] transition">
                Simulate Checked Out Items
            </button>
        </div>

        <!-- Checked Out Items Section (Hidden by default) -->
        <div id="checkedOutSection" class="hidden space-y-6">

            <div class="p-4 border border-amber-200 bg-amber-50 rounded-lg flex items-center justify-between mt-6">
                <div class="flex items-center gap-3">
                    <i class="ph ph-qr-code text-2xl text-amber-600"></i>
                    <div>
                        <h3 class="font-medium text-amber-900">Checked Out Items</h3>
                        <p class="text-sm text-amber-700">Items included in this QR checkout ticket</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-amber-700">1</p>
                    <p class="text-xs text-amber-600">Total Item(s)</p>
                </div>
            </div>

            <!-- Books Section -->
            <div class="border border-green-300 bg-green-50 rounded-xl">
                <div class="p-4 flex items-center justify-between border-b border-green-200">
                    <h4 class="font-medium text-green-700 flex items-center gap-2">
                        <!-- count ng items -->
                        <i class="ph ph-book text-lg"></i> Books (1)
                    </h4>
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600 font-medium">Book #1</span>
                </div>
                <div class="bg-white p-4 flex gap-3 rounded-xl">
                    <!-- Icon Wrapper -->
                    <div class="flex items-center">
                        <i class="ph ph-book-open text-3xl text-green-500"></i>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <p class="font-medium">Book Title</p>
                        <p class="text-sm text-gray-600">by Author's name</p>
                        <div class="flex flex-wrap gap-2 mt-2 text-xs">
                            <span class="px-2 py-1 bg-gray-100 rounded">AccNo: ####</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Call Number</span>
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Subject</span>
                        </div>
                    </div>

                    <!-- Count -->
                    <span
                        class="w-6 h-6 flex items-center justify-center rounded-full bg-green-600 text-white text-xs font-bold">
                        1
                    </span>
                </div>
            </div>
        </div>


    <script>
    // toggle lang ng simulation button remove mo rin pag ipapadisplay mo na
    document.getElementById("showItemsBtn").addEventListener("click", () => {
        document.getElementById("checkedOutSection").classList.toggle("hidden");
    });
    </script>



</body>