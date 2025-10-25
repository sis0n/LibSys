<body class="min-h-screen p-6">
    <h2 class="text-2xl font-bold mb-4">QR Borrowing Ticket</h2>
    <p class="text-gray-700">Your QR code for book borrowing and library access.</p>

    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6 justify-center items-stretch">
        <!-- QR Ticket Card -->
        <div class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col justify-between text-center">
            <div>
                <h3 class="text-[var(--font-size-lg)] font-semibold mb-1">Your QR Ticket</h3>
                <p class="text-[var(--font-size-sm)] text-[var(--color-gray-600)] mb-6">Present this to the librarian</p>

                <div class="w-full p-4 border border-gray-300 rounded-lg bg-white flex justify-center items-center relative">
                    <?php if (!empty($isExpired) && $isExpired): ?>
                        <p class="text-red-500 font-semibold text-lg">QR Code Ticket Expired</p>
                    <?php elseif (!empty($qrPath)): ?>
                        <img src="<?= $qrPath ?>" alt="QR Code" class="w-56 h-56 object-contain" />
                    <?php else: ?>
                        <p class="text-red-500 font-semibold">No QR code available</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-6">
                <div class="text-[var(--font-size-sm)] text-[var(--color-gray-700)] border-t border-[var(--color-border)] pt-4">
                    <p class="font-medium">Ticket Code: <span class="text-[var(--color-primary)]"><?= $transaction_code ?? 'N/A' ?></span></p>
                    <p class="text-[var(--font-size-xs)] text-[var(--color-gray-500)]">Generated: <?= !empty($borrowed_at) ? date("m/d/Y, h:i:s A", strtotime($borrowed_at)) : "N/A" ?></p>
                    <p class="text-[var(--font-size-xs)] text-[var(--color-gray-500)]">Due Date: <?= !empty($due_date) ? date("m/d/Y", strtotime($due_date)) : 'N/A' ?></p>
                </div>

                <!-- Download Button -->
                <a href="<?= $qrPath ?? '#' ?>" download="<?= $transaction_code ?>.png"
                    class="mt-4 flex items-center justify-center gap-2 px-4 py-2 rounded-[var(--radius-md)] bg-orange-500 text-[var(--color-primary-foreground)] font-medium shadow hover:bg-orange-600 transition <?= (!empty($isExpired) && $isExpired) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <i class="ph ph-download-simple text-xl"></i>
                    Download
                </a>
            </div>
        </div>

        <!-- Ticket Details Card -->
        <div class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col">
            <h3 class="text-md font-medium mb-1">Ticket Details</h3>
            <p class="text-sm text-amber-700 mb-5">Information encoded in your QR ticket</p>

            <dl class="space-y-3 text-sm flex-1">
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Student Number:</dt>
                    <dd class="text-right"><?= $student["student_number"] ?? "N/A" ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Name:</dt>
                    <dd class="text-right"><?= $student["name"] ?? "Student Name" ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Year & Section:</dt>
                    <dd class="text-right"><?= $student["year_level"] ?? "N/A" ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Course:</dt>
                    <dd class="text-right"><?= $student["course"] ?? "N/A" ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Books:</dt>
                    <dd class="text-right"><?= !empty($books) ? count($books) : 0 ?> Book(s)</dd>
                </div>
            </dl>

            <div class="mt-6 p-4 rounded-[var(--radius-md)] bg-[var(--color-blue-50,#eff6ff)] border border-[var(--color-blue-200,#bfdbfe)]">
                <h4 class="font-medium text-md mb-2">How to use:</h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-amber-700">
                    <li>Show this QR code to the librarian</li>
                    <li>They will scan it to verify your identity</li>
                    <li>Proceed with book borrowing or return</li>
                    <li>Use cart checkout for specific book borrowing</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Checked Out Items Section -->
    <div class="space-y-6 mt-6">
        <?php if (!empty($books) && count($books) > 0): ?>
            <div class="p-4 border border-amber-200 bg-amber-50 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="ph ph-qr-code text-2xl text-amber-600"></i>
                    <div>
                        <h3 class="font-medium text-amber-900">Checked Out Items</h3>
                        <p class="text-sm text-amber-700">Items included in this QR checkout ticket</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-amber-700"><?= count($books) ?></p>
                    <p class="text-xs text-amber-600">Total Item(s)</p>
                </div>
            </div>

            <div class="border border-green-300 bg-green-50 rounded-xl">
                <div class="p-4 flex items-center justify-between border-b border-green-200">
                    <h4 class="font-medium text-green-700 flex items-center gap-2">
                        <i class="ph ph-book text-lg"></i> Books (<?= count($books) ?>)
                    </h4>
                </div>

                <?php foreach ($books as $index => $book): ?>
                    <div class="bg-white p-4 flex gap-3 border-t border-green-100">
                        <div class="flex items-center">
                            <i class="ph ph-book-open text-3xl text-green-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium"><?= htmlspecialchars($book["title"]) ?></p>
                            <p class="text-sm text-gray-600">by <?= htmlspecialchars($book["author"]) ?></p>
                            <div class="flex flex-wrap gap-2 mt-2 text-xs">
                                <span class="px-2 py-1 bg-gray-100 rounded">Accession #: <?= htmlspecialchars($book["accession_number"] ?? "N/A") ?></span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Call #: <?= htmlspecialchars($book["call_number"] ?? "N/A") ?></span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Subject: <?= htmlspecialchars($book["subject"] ?? "N/A") ?></span>
                            </div>
                        </div>
                        <span class="w-6 h-6 flex items-center justify-center rounded-full bg-green-600 text-white text-xs font-bold"><?= $index + 1 ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInterval = 5000;
            const ticketMessageDiv = document.getElementById('ticket-message');
            const downloadButton = document.querySelector('.btn-download');

            function checkTicketStatus() {
                fetch('/libsys/public/ticket/checkStatus')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.status === 'expired') {
                                if (ticketMessageDiv) ticketMessageDiv.innerText = "Your borrowing ticket has expired.";
                                if (downloadButton) {
                                    downloadButton.classList.add('opacity-50', 'cursor-not-allowed');
                                    downloadButton.setAttribute('disabled', 'disabled');
                                }
                            } else {
                                if (ticketMessageDiv) ticketMessageDiv.innerText = "";
                                if (downloadButton) {
                                    downloadButton.classList.remove('opacity-50', 'cursor-not-allowed');
                                    downloadButton.removeAttribute('disabled');
                                }
                            }
                        }
                    })
                    .catch(err => console.error('Error checking ticket status:', err));
            }

            setInterval(checkTicketStatus, checkInterval);
        });
    </script>
</body>