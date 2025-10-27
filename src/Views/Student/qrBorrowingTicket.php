<body class="min-h-screen p-6">
    <h2 class="text-2xl font-bold mb-4">QR Borrowing Ticket</h2>
    <p class="text-gray-700">Your QR code for book borrowing and library access.</p>

    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6 justify-center items-stretch">
        <!-- QR Ticket Card -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col justify-between text-center">
            <div>
                <h3 class="text-[var(--font-size-lg)] font-semibold mb-1">Your QR Ticket</h3>
                <p class="text-[var(--font-size-sm)] text-[var(--color-gray-600)] mb-6">Present this to the librarian</p>

                <div class="w-full p-4 border border-gray-300 rounded-lg bg-white flex justify-center items-center relative">
                    <p id="ticket-message" class="text-red-500 font-semibold text-lg">
                        <?php if (!empty($isExpired) && $isExpired): ?>
                            QR Code Ticket Expired
                        <?php elseif (!empty($isBorrowed) && $isBorrowed): ?>
                            QR Code Successfully Scanned!
                        <?php elseif (empty($qrPath)): ?>
                            No QR code available
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($qrPath) && empty($isExpired) && empty($isBorrowed)): ?>
                        <img id="qr-image" src="<?= $qrPath ?>" alt="QR Code" class="w-56 h-56 object-contain" />
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-6">
                <div
                    class="text-[var(--font-size-sm)] text-[var(--color-gray-700)] border-t border-[var(--color-border)] pt-4">
                    <p class="font-medium">
                        Ticket Code:
                        <span class="text-[var(--color-primary)]"><?= $transaction_code ?? 'N/A' ?></span>
                    </p>
                    <p class="text-[var(--font-size-xs)] text-[var(--color-gray-500)]">
                        Generated:
                        <?= !empty($borrowed_at) ? date("m/d/Y, h:i:s A", strtotime($borrowed_at)) : "N/A" ?>
                    </p>
                    <p class="text-[var(--font-size-xs)] text-[var(--color-gray-500)]">
                        Due Date: <?= !empty($due_date) ? date("m/d/Y", strtotime($due_date)) : 'N/A' ?>
                    </p>
                </div>

                <a id="download-button" href="<?= $qrPath ?? '#' ?>" download="<?= $transaction_code ?>.png"
                    class="mt-4 flex items-center justify-center gap-2 px-4 py-2 rounded-[var(--radius-md)] bg-orange-500 text-[var(--color-primary-foreground)] font-medium shadow hover:bg-orange-600 transition <?= (!empty($isExpired) && $isExpired) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <i class="ph ph-download-simple text-xl"></i>
                    Download
                </a>
            </div>
        </div>

        <!-- Ticket Details Card -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col">
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

            <div
                class="mt-6 p-4 rounded-[var(--radius-md)] bg-[var(--color-blue-50,#eff6ff)] border border-[var(--color-blue-200,#bfdbfe)]">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function resetToDefault() {
                const qrImage = document.getElementById('qr-image');
                const ticketMessageDiv = document.getElementById('ticket-message');
                const downloadButton = document.getElementById('download-button');

                if (qrImage) qrImage.style.display = 'none';
                if (ticketMessageDiv) {
                    ticketMessageDiv.innerText = "You do not currently have an active borrowing ticket.";
                    ticketMessageDiv.classList.remove('text-red-500');
                    ticketMessageDiv.classList.add('text-gray-700');
                }
                if (downloadButton) {
                    downloadButton.style.display = 'none';
                    downloadButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            function checkTicketStatus() {
                fetch('/libsys/public/student/qrBorrowingTicket/checkStatus')
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) return;

                        if (data.status === 'borrowed') {
                            alert('QR code successfully scanned!');
                            resetToDefault();
                        } else if (data.status === 'expired') {
                            alert('QR code expired. Please request a new one.');
                            resetToDefault();
                        }
                    })
                    .catch(err => console.error('Error checking ticket status:', err));
            }

            // ✅ Check immediately on page load
            checkTicketStatus();
        });
    </script>
</body>