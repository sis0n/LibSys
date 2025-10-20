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

             <div class="bg-orange-50 border border-orange-200 rounded-lg text-center p-8 mb-6 flex flex-col justify-center flex-grow transition-all duration-300">
            <div class="flex justify-center items-center mb-4 flex-grow">
                    <i class="ph ph-scan text-7xl sm:text-9xl    text-orange-400"></i>
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
    <div id="scanResultCard" class="bg-white p-6 rounded-lg shadow-md flex flex-col justify-between">
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

<script>
    const simulateScanBtn = document.getElementById('simulateScanBtn');
    const scanResultCard = document.getElementById('scanResultCard');

    // Function to render the scan result card with dynamic data
    function renderScanResult(data) {
        if (!data || !data.isValid) {
            scanResultCard.innerHTML = `
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
            `;
            return;
        }

        const itemsHtml = data.items.map((item, index) => `
            <li class="mb-1">
                <p class="font-medium">${index + 1}. ${item.title}</p>
                <p class="text-sm text-gray-600 ml-4">by ${item.author}</p>
            </li>
        `).join('');

        scanResultCard.innerHTML = `
            <div>
                <h2 class="text-xl font-semibold mb-2">Scan Result</h2>
                <p class="text-gray-500 mb-6">Review ticket details and process transaction</p>

                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-4">
                    <i class="ph ph-check-circle text-xl"></i>
                    <span>Valid ticket scanned</span>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-orange-200 rounded-full w-10 h-10 flex items-center justify-center">
                            <i class="ph ph-user text-xl text-orange-700"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">${data.student.name}</p>
                            <p class="text-sm text-gray-600">Student ID: ${data.student.id}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 mb-1">Ticket: ${data.ticket.id}</p>
                    <p class="text-sm text-gray-700 mb-2">Generated: ${data.ticket.generated}</p>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="ph ph-book-open text-lg text-gray-700"></i>
                        <h3 class="font-semibold text-gray-700">Items (${data.items.length})</h3>
                    </div>
                    <ul class="list-none pl-0 text-gray-700">
                        ${itemsHtml}
                    </ul>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-700 mb-3">Available Actions</h3>
                    <button class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg flex items-center justify-center gap-2 mb-3 hover:bg-orange-700 transition">
                        <i class="ph ph-book-bookmark text-xl"></i>
                        Complete Checkout (${data.items.length} items)
                    </button>
                    <button class="w-full bg-white border border-orange-300 text-orange-700 py-3 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-orange-50 transition">
                        <i class="ph ph-book-return text-xl"></i>
                        Process Return
                    </button>
                </div>
            </div>
        `;
    }

    // Sample dynamic data (this would come from your backend)
    const sampleTicketData = {
        isValid: true,
        student: {
            name: "Student Name",
            id: "Student Number"
        },
        ticket: {
            id: "TICKET-NUMBER",
            generated: "MM/DD/YYYY, 12:00 PM/AM",
        },
        items: [
            {
                title: "BOOK TITLE",
                author: "Author Name"
            }
        ]
    };

    simulateScanBtn.addEventListener('click', function() {
        renderScanResult(sampleTicketData);
    });
</script>