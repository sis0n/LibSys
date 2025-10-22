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
</div>