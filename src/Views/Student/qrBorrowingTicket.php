<main class="min-h-screen">
    <h2 class="text-2xl font-bold mb-4">QR Borrowing Ticket</h2>
    <p class="text-gray-700">Your QR code for book borrowing and library access.</p>

    <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row gap-6 justify-center items-stretch">
        <!-- QR Ticket Card -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col justify-between text-center">
            <div>
                <h3 class="text-[var(--font-size-lg)] font-semibold mb-1">Your QR Ticket</h3>
                <p class="text-[var(--font-size-sm)] text-[var(--color-gray-600)] mb-6">Present this to the librarian
                </p>

                <div
                    class="w-full p-4 border border-gray-300 rounded-lg bg-white flex justify-center items-center relative min-h-[250px]">

                    <div id="ticket-message-container" class="flex items-center justify-center p-4">

                        <?php if (!empty($isExpired) && $isExpired): ?>
                            <p id="ticket-message"
                                class="text-red-500 font-semibold text-lg flex items-center justify-center gap-2">
                                <i class="ph ph-x-circle text-2xl"></i>
                                QR Code Ticket Expired
                            </p>
                        <?php elseif (!empty($isBorrowed) && $isBorrowed): ?>
                            <p id="ticket-message"
                                class="text-green-600 font-semibold text-lg flex items-center justify-center gap-2">
                                <i class="ph ph-check-circle text-2xl"></i>
                                QR Code Successfully Scanned!
                            </p>
                        <?php elseif (empty($qrPath) && empty($isBorrowed) && empty($isExpired)): ?>
                            <p id="ticket-message"
                                class="text-gray-500 font-semibold text-lg flex items-center justify-center gap-2">
                                <i class="ph ph-info text-2xl"></i>
                                No active borrowing ticket.
                            </p>
                        <?php else: ?>
                            <p id="ticket-message" class="hidden"></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($qrPath) && empty($isExpired) && empty($isBorrowed)): ?>
                        <img id="qr-image" src="<?= $qrPath ?>" alt="QR Code" class="w-56 h-56 object-contain" />
                    <?php else: ?>
                        <img id="qr-image" src="" alt="QR Code" class="w-56 h-56 object-contain hidden" />
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-6">
                <div
                    class="text-[var(--font-size-sm)] text-[var(--color-gray-700)] border-t border-[var(--color-border)] pt-4">
                    <p class="font-medium" id="ticket_code">
                        Ticket Code:
                        <span class="text-[var(--color-primary)]"><?= $transaction_code ?? 'N/A' ?></span>
                    </p>
                    <p id="generated_date"
                        class="text-[var(--font-size-xs)] text-[var(--color-gray-500)] <?= (empty($generated_at) || !empty($isBorrowed) || !empty($isExpired)) ? 'hidden' : '' ?>">
                        Generated Time:
                        <?= !empty($generated_at) ? date("h:i:s A", strtotime($generated_at)) : "N/A" ?>
                    </p>
                    <p id="due_date"
                        class="text-[var(--font-size-xs)] text-red-500 font-medium <?= (empty($expires_at) || !empty($isBorrowed) || !empty($isExpired)) ? 'hidden' : '' ?>">
                        Expiration:
                        <?= !empty($expires_at) ? 'Expires at: ' . date("h:i:s A", strtotime($expires_at)) : 'N/A' ?>
                    </p>
                </div>

                <a id="download-button" href="<?= $qrPath ?? '#' ?>"
                    download="<?= ($transaction_code ?? 'qrcode') ?>.png"
                    class="mt-4 flex items-center justify-center gap-2 px-4 py-2 rounded-[var(--radius-md)] bg-orange-500 text-[var(--color-primary-foreground)] font-medium shadow hover:bg-orange-600 transition <?= (empty($qrPath) || !empty($isExpired) || !empty($isBorrowed)) ? 'hidden opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <i class="ph ph-download-simple text-xl"></i>
                    Download
                </a>
            </div>
        </div>

        <!-- Ticket Details Card (Student Version with IDs) -->
        <div
            class="flex-1 bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 flex flex-col">
            <h3 class="text-md font-medium mb-1">Ticket Details</h3>
            <p class="text-sm text-amber-700 mb-5">Information encoded in your QR ticket</p>

            <!-- *** NILAGYAN NG IDs ANG MGA <dd> *** -->
            <dl class="space-y-3 text-sm flex-1">
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Student Number:</dt>
                    <dd id="detailsStudentNumber" class="text-right">
                        <?= htmlspecialchars($student["student_number"] ?? "N/A") ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Name:</dt>
                    <dd id="detailsStudentName" class="text-right">
                        <?= htmlspecialchars($student["name"] ?? "Student Name") ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Year & Section:</dt>
                    <dd id="detailsStudentYrSec" class="text-right">
                        <?= !empty($student["year_level"]) ? htmlspecialchars($student["year_level"] . ($student["section"] ?? '')) : "N/A" ?>
                    </dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Course:</dt>
                    <dd id="detailsStudentCourse" class="text-right">
                        <?= htmlspecialchars($student["course"] ?? "N/A") ?></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-amber-700 font-medium">Books:</dt>
                    <!-- Ginamit ang $books variable -->
                    <dd id="detailsBookCount" class="text-right"><?= !empty($books) ? count($books) : 0 ?> Book(s)</dd>
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

    <!-- Checked Out Items Section  -->
    <div id="checkedOutSection" class="space-y-6 mt-6">
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

            <div class="border-t border-x border-green-300 bg-green-50 rounded-xl">
                <div class="p-4 flex items-center justify-between border-b border-green-200">
                    <h4 class="font-medium text-green-700 flex items-center gap-2">
                        <i class="ph ph-book text-lg"></i> Books (<?= count($books) ?>)
                    </h4>
                </div>

                <?php foreach ($books as $index => $book): ?>
                    <div class="bg-white p-4 flex gap-3 rounded-b-lg border-b border-green-300 ">
                        <div class="flex items-center">
                            <i class="ph ph-book-open text-3xl text-green-500"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium"><?= htmlspecialchars($book["title"]) ?></p>
                            <p class="text-sm text-gray-600">by <?= htmlspecialchars($book["author"]) ?></p>
                            <div class="flex flex-wrap gap-2 mt-2 text-xs">
                                <span class="px-2 py-1 bg-gray-100 rounded">Accession #:
                                    <?= htmlspecialchars($book["accession_number"] ?? "N/A") ?></span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Call #:
                                    <?= htmlspecialchars($book["call_number"] ?? "N/A") ?></span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Subject:
                                    <?= htmlspecialchars($book["subject"] ?? "N/A") ?></span>
                            </div>
                        </div>
                        <span
                            class="w-6 h-6 flex items-center justify-center rounded-full bg-green-600 text-white text-xs font-bold"><?= $index + 1 ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- *** INAYOS NA JAVASCRIPT PARA SA STUDENT *** -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const qrImage = document.getElementById('qr-image');
    const ticketMessageContainer = document.getElementById('ticket-message-container');
    const downloadButton = document.getElementById('download-button');
    const ticketCodeSpan = document.querySelector('#ticket_code span');
    const generatedDateP = document.getElementById('generated_date');
    const dueDateP = document.getElementById('due_date');
    const detailsStudentNumber = document.getElementById('detailsStudentNumber');
    const detailsStudentName = document.getElementById('detailsStudentName');
    const detailsStudentYrSec = document.getElementById('detailsStudentYrSec');
    const detailsStudentCourse = document.getElementById('detailsStudentCourse');
    const detailsBookCount = document.getElementById('detailsBookCount');
    const checkedOutSection = document.getElementById('checkedOutSection');

    function createMessageElement(text, classes, iconClass) {
        const p = document.createElement('p');
        p.id = 'ticket-message';
        p.className = `font-semibold text-lg flex items-center justify-center gap-2 ${classes}`;
        if (iconClass) {
            const i = document.createElement('i');
            i.className = `${iconClass} text-2xl`;
            p.appendChild(i);
        }
        p.appendChild(document.createTextNode(text));
        return p;
    }

    function displayMessage(text, type = 'info') {
        ticketMessageContainer.innerHTML = '';
        let el;
        if (type === 'error' || type === 'expired') {
            el = createMessageElement(text, 'text-red-500', 'ph ph-x-circle');
        } else if (type === 'success' || type === 'borrowed') {
            el = createMessageElement(text, 'text-green-600', 'ph ph-check-circle');
        } else {
            el = createMessageElement(text, 'text-gray-500', 'ph ph-info');
        }
        ticketMessageContainer.appendChild(el);
        if (qrImage) qrImage.classList.add('hidden');
        if (downloadButton) downloadButton.classList.add('hidden', 'opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        if (generatedDateP) generatedDateP.classList.add('hidden');
        if (dueDateP) dueDateP.classList.add('hidden');
        if (detailsStudentNumber) detailsStudentNumber.textContent = 'N/A';
        if (detailsStudentName) detailsStudentName.textContent = 'N/A';
        if (detailsStudentYrSec) detailsStudentYrSec.textContent = 'N/A';
        if (detailsStudentCourse) detailsStudentCourse.textContent = 'N/A';
        if (detailsBookCount) detailsBookCount.textContent = '0 Book(s)';
        if (checkedOutSection) checkedOutSection.classList.add('hidden');
    }

    function showQR(ticket) {
        ticketMessageContainer.innerHTML = '';
        if (qrImage) {
            qrImage.src = `<?= BASE_URL ?>/qrcodes/${ticket.transaction_code}.png?t=${Date.now()}`;
            qrImage.classList.remove('hidden');
        }
        if (downloadButton) {
            downloadButton.href = `<?= BASE_URL ?>/qrcodes/${ticket.transaction_code}.png`;
            downloadButton.download = `${ticket.transaction_code}.png`;
            downloadButton.classList.remove('hidden', 'opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        }
        if (ticketCodeSpan) ticketCodeSpan.textContent = ticket.transaction_code || 'N/A';
        if (generatedDateP) {
            generatedDateP.textContent = `Generated Time: ${ticket.generated_at ? new Date(ticket.generated_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true }) : 'N/A'}`;
            generatedDateP.classList.remove('hidden');
        }
        if (dueDateP && ticket.expires_at) {
            dueDateP.textContent = `Expiration: Expires at ${new Date(ticket.expires_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true })}`;
            dueDateP.classList.remove('hidden');
        } else if (dueDateP) {
            dueDateP.classList.add('hidden');
        }

        if (ticket.student) {
            detailsStudentNumber.textContent = ticket.student.student_number || 'N/A';
            detailsStudentName.textContent = ticket.student.name || 'N/A';
            detailsStudentYrSec.textContent = `${ticket.student.year_level ?? 'N/A'}-${ticket.student.section ?? ''}`;
            detailsStudentCourse.textContent = ticket.student.course || 'N/A';
        } else {
            detailsStudentNumber.textContent = 'N/A';
            detailsStudentName.textContent = 'N/A';
            detailsStudentYrSec.textContent = 'N/A';
            detailsStudentCourse.textContent = 'N/A';
        }
        detailsBookCount.textContent = ticket.books ? `${ticket.books.length} Book(s)` : '0 Book(s)';

        if (ticket.books && ticket.books.length > 0 && checkedOutSection) {
            checkedOutSection.innerHTML = `
                <div class="p-4 border border-amber-200 bg-amber-50 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-qr-code text-2xl text-amber-600"></i>
                        <div>
                            <h3 class="font-medium text-amber-900">Checked Out Items</h3>
                            <p class="text-sm text-amber-700">Items included in this QR checkout ticket</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-amber-700">${ticket.books.length}</p>
                        <p class="text-xs text-amber-600">Total Item(s)</p>
                    </div>
                </div>
                <div class="border-t border-x border-green-300 bg-green-50 rounded-xl">
                    <div class="p-4 flex items-center justify-between border-b border-green-200">
                        <h4 class="font-medium text-green-700 flex items-center gap-2">
                            <i class="ph ph-book text-lg"></i> Books (${ticket.books.length})
                        </h4>
                    </div>
                    ${ticket.books.map((book, index) => `
                        <div class="bg-white p-4 flex gap-3 rounded-b-lg border-b border-green-300">
                            <div class="flex items-center">
                                <i class="ph ph-book-open text-3xl text-green-500"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">${book.title}</p>
                                <p class="text-sm text-gray-600">by ${book.author}</p>
                                <div class="flex flex-wrap gap-2 mt-2 text-xs">
                                    <span class="px-2 py-1 bg-gray-100 rounded">Accession #: ${book.accession_number ?? 'N/A'}</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">Call #: ${book.call_number ?? 'N/A'}</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Subject: ${book.subject ?? 'N/A'}</span>
                                </div>
                            </div>
                            <span class="w-6 h-6 flex items-center justify-center rounded-full bg-green-600 text-white text-xs font-bold">${index + 1}</span>
                        </div>
                    `).join('')}
                </div>
            `;
            checkedOutSection.classList.remove('hidden');
        } else if (checkedOutSection) {
            checkedOutSection.classList.add('hidden');
        }
    }

    function resetToDefault(message = "No active borrowing ticket.") {
        displayMessage(message, 'info');
    }

    let isChecking = false;

    async function checkTicketStatus() {
        if (isChecking) return;
        isChecking = true;

        try {
            const res = await fetch(`${BASE_URL_JS}/api/student/qrBorrowingTicket/checkStatus`);
            const data = await res.json();
            if (!data.success) {
                isChecking = false;
                return;
            }

            const lastStatus = sessionStorage.getItem('studentLastStatus');

            if (data.status === 'pending') {
                showQR({
                    transaction_code: data.transaction_code,
                    generated_at: data.generated_at,
                    expires_at: data.expires_at,
                    student: data.student,
                    books: data.books
                });
                if (lastStatus !== 'pending') {
                    sessionStorage.setItem('studentLastStatus', 'pending');
                    sessionStorage.setItem('studentLastTransactionCode', data.transaction_code);
                }
            } else if (data.status === 'borrowed') {
                displayMessage('QR Code Successfully Scanned!', 'success');
                if (checkedOutSection) checkedOutSection.classList.add('hidden');
                if (lastStatus !== 'borrowed') {
                    sessionStorage.setItem('studentLastStatus', 'borrowed');
                    sessionStorage.removeItem('studentLastTransactionCode');
                }
            } else if (data.status === 'expired') {
                displayMessage('QR Code Ticket Expired', 'expired');
                if (checkedOutSection) checkedOutSection.classList.add('hidden');
                if (lastStatus !== 'expired') {
                    sessionStorage.setItem('studentLastStatus', 'expired');
                    sessionStorage.removeItem('studentLastTransactionCode');
                }
            } else {
                resetToDefault();
                if (checkedOutSection) checkedOutSection.classList.add('hidden');
                if (lastStatus !== 'none') {
                    sessionStorage.setItem('studentLastStatus', 'none');
                    sessionStorage.removeItem('studentLastTransactionCode');
                }
            }
        } catch (err) {
            console.error('Error checking ticket status:', err);
        } finally {
            isChecking = false;
        }
    }

    setInterval(checkTicketStatus, 2000);
    checkTicketStatus();
});
</script>


</main>