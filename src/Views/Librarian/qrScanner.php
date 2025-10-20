<div class="flex items-center mb-4">
    <i class="ph ph-qr-code text-4xl text-gray-700 mr-2"></i>
    <h1 class="text-2xl font-bold">QR Code Scanner</h1>
</div>
<p class="mb-8 text-gray-500">Scan student QR tickets for borrowing and returning books & equipment.</p>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Left Column: Scanner -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-2">Scanner</h2>
        <p class="text-gray-500 mb-6">Present QR code to scan or enter manually</p>

        <div class="bg-orange-50 border border-orange-200 rounded-lg text-center p-8 mb-6">
            <div class="flex justify-center items-center mb-4">
                <i class="ph ph-scan text-8xl text-orange-400"></i>
            </div>
            <p class="font-semibold text-gray-700">Present your QR code to librarian</p>
            <p class="text-sm text-gray-500">Position the QR code within the scanning area</p>
            <!-- Remove mo to wyn pang simulate lang ulet -->
            <button class="mt-6 bg-orange-500 text-white py-2 px-6 rounded-lg hover:bg-orange-600 transition">
                Simulate Scan (Demo)
            </button>
        </div>

        <div>
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
    <div class="bg-white p-6 rounded-lg shadow-md">
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