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

          <div id="ticket-message-container" class="absolute inset-0 flex items-center justify-center p-4">

            <?php if (!empty($isExpired) && $isExpired): ?>
              <p id="ticket-message" class="text-red-500 font-semibold text-lg flex items-center justify-center gap-2">
                <i class="ph ph-x-circle text-2xl"></i>
                QR Code Ticket Expired
              </p>
            <?php elseif (!empty($isBorrowed) && $isBorrowed): ?>
              <p id="ticket-message" class="text-green-600 font-semibold text-lg flex items-center justify-center gap-2">
                <i class="ph ph-check-circle text-2xl"></i>
                QR Code Successfully Scanned!
              </p>
            <?php elseif (empty($qrPath) && empty($isBorrowed) && empty($isExpired)): ?>
              <p id="ticket-message" class="text-gray-500 font-semibold text-lg flex items-center justify-center gap-2">
                <i class="ph ph-info text-2xl"></i>
                No active borrowing ticket.
              </p>
            <?php else: ?>
              <p id="ticket-message" class="hidden"></p>
            <?php endif; ?>
          </div>

          <?php if (!empty($qrPath) && empty($isExpired) && empty($isBorrowed)): ?>
            <img id="qr-image" src="<?= $qrPath ?>" alt="QR Code" class="w-56 h-56 object-contain relative z-10" />
          <?php else: ?>
            <img id="qr-image" src="" alt="QR Code" class="w-56 h-56 object-contain relative z-10 hidden" />
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
          <p id="generated_date" class="text-[var(--font-size-xs)] text-[var(--color-gray-500)] <?= (empty($generated_at) || !empty($isBorrowed) || !empty($isExpired)) ? 'hidden' : '' ?>">
            Generated Time:
            <?= !empty($generated_at) ? date("h:i:s A", strtotime($generated_at)) : "N/A" ?>
          </p>
          <p id="due_date" class="text-[var(--font-size-xs)] text-red-500 font-medium <?= (empty($expires_at) || !empty($isBorrowed) || !empty($isExpired)) ? 'hidden' : '' ?>">
            Expiration:
            <?= !empty($expires_at) ? 'Expires at: ' . date("h:i:s A", strtotime($expires_at)) : 'N/A' ?>
          </p>
        </div>

        <a id="download-button" href="<?= $qrPath ?? '#' ?>" download="<?= ($transaction_code ?? 'qrcode') ?>.png"
          class="mt-4 flex items-center justify-center gap-2 px-4 py-2 rounded-[var(--radius-md)] bg-orange-500 text-[var(--color-primary-foreground)] font-medium shadow hover:bg-orange-600 transition <?= (empty($qrPath) || !empty($isExpired) || !empty($isBorrowed)) ? 'hidden opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
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

      <!-- *** NILAGYAN NG IDs ANG MGA <dd> *** -->
      <dl class="space-y-3 text-sm flex-1">
        <div class="flex justify-between items-center">
          <dt class="text-amber-700 font-medium">Faculty ID:</dt>
          <dd id="detailsFacultyId" class="text-right"><?= htmlspecialchars($faculty["faculty_id"] ?? "N/A") ?></dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-amber-700 font-medium">Name:</dt>
          <dd id="detailsFacultyName" class="text-right"><?= htmlspecialchars($faculty["name"] ?? "Faculty Name") ?></dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-amber-700 font-medium">Department:</dt>
          <dd id="detailsFacultyDept" class="text-right"><?= htmlspecialchars($faculty["department"] ?? "N/A") ?></dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-amber-700 font-medium">Books:</dt>
          <dd id="detailsBookCount" class="text-right"><?= !empty($items) ? count($items) : 0 ?> Book(s)</dd>
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

  <!-- Book List Section -->
  <div id="bookListSection" class="w-full max-w-4xl mx-auto bg-[var(--color-card)] rounded-[var(--radius-lg)] shadow-md border border-[var(--color-border)] p-6 mt-6 <?= (empty($items)) ? 'hidden' : '' ?>">
    <h3 class="text-lg font-semibold mb-4">Books Included in this Ticket (<span id="bookListCount"><?= !empty($items) ? count($items) : 0 ?></span>)</h3>

    <?php if (!empty($items)): ?>
      <ul id="bookListUL" class="divide-y divide-[var(--color-border)]">
        <?php foreach ($items as $item): ?>
          <li class="py-3 flex justify-between items-center text-sm">
            <div>
              <p class="font-medium text-gray-800">
                <?= htmlspecialchars($item['title'] ?? 'Title Missing') ?>
              </p>
              <p class="text-xs text-gray-600">
                by <?= htmlspecialchars($item['author'] ?? 'Author Unknown') ?>
              </p>
            </div>
            <div class="text-right">
              <p class="font-mono text-xs text-amber-700">
                Accession No.: <?= htmlspecialchars($item['accession_number'] ?? 'N/A') ?>
              </p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <div id="noBooksMessage" class="p-3 text-sm text-center text-gray-500 bg-gray-50 rounded">
        No books currently associated with this ticket.
      </div>
    <?php endif; ?>
    <!-- Placeholder for JS to potentially add list if needed -->
    <?php if (empty($items)): ?>
      <ul id="bookListUL" class="divide-y divide-[var(--color-border)] hidden"></ul>
    <?php endif; ?>
  </div>

  <!-- *** INAYOS NA JAVASCRIPT *** -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const qrImage = document.getElementById('qr-image');
      const ticketMessageContainer = document.getElementById('ticket-message-container');
      const downloadButton = document.getElementById('download-button');
      const ticketCodeSpan = document.querySelector('#ticket_code span');
      const generatedDateP = document.getElementById('generated_date');
      const dueDateP = document.getElementById('due_date');

      // --- References sa Ticket Details Card Elements ---
      const detailsFacultyId = document.getElementById('detailsFacultyId');
      const detailsFacultyName = document.getElementById('detailsFacultyName');
      const detailsFacultyDept = document.getElementById('detailsFacultyDept');
      const detailsBookCount = document.getElementById('detailsBookCount');

      // --- References sa Book List Section ---
      const bookListSection = document.getElementById('bookListSection');
      const bookListCount = document.getElementById('bookListCount');
      const bookListUL = document.getElementById('bookListUL');
      const noBooksMessage = document.getElementById('noBooksMessage');


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

        // --- Reset Ticket Details kapag may message ---
        if (detailsFacultyId) detailsFacultyId.textContent = 'N/A';
        if (detailsFacultyName) detailsFacultyName.textContent = 'N/A';
        if (detailsFacultyDept) detailsFacultyDept.textContent = 'N/A';
        if (detailsBookCount) detailsBookCount.textContent = '0 Book(s)';

        // --- Hide Book List Section kapag may message ---
        if (bookListSection) bookListSection.classList.add('hidden');
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

        // --- Restore Ticket Details (Assuming details are passed from PHP or another source) ---
        // Note: The AJAX 'checkStatus' might need to be updated to return faculty details 
        // if we want to dynamically update them here. For now, we assume PHP rendered them correctly.
        // If PHP rendered N/A, they will remain N/A unless updated elsewhere.

        // --- Restore Book List (Assuming books are passed from PHP) ---
        // Show the section if PHP rendered items
        const hasInitialItems = <?= json_encode(!empty($items)) ?>;
        if (hasInitialItems && bookListSection) {
          bookListSection.classList.remove('hidden');
        } else if (bookListSection) {
          bookListSection.classList.add('hidden'); // Ensure it's hidden if no items
        }
      }

      function resetToDefault(message = "No active borrowing ticket.") {
        displayMessage(message, 'info'); // displayMessage now handles resetting details and book list visibility
      }


      let isChecking = false;

      async function checkTicketStatus() {
        if (isChecking) return;
        isChecking = true;

        try {
          const res = await fetch('<?= BASE_URL ?>/api/faculty/qrBorrowingTicket/checkStatus');
          const data = await res.json();

          if (!data.success) {
            isChecking = false;
            return;
          }

          const lastStatus = sessionStorage.getItem('facultyLastStatus');

          console.log(`[Check] Fetched Status: ${data.status}, Last Stored: ${lastStatus}`);

          if (data.status === 'pending') {
            showQR({
              transaction_code: data.transaction_code,
              generated_at: data.generated_at,
              expires_at: data.expires_at
            });
            if (lastStatus !== 'pending') {
              sessionStorage.setItem('facultyLastStatus', 'pending');
              sessionStorage.setItem('facultyLastTransactionCode', data.transaction_code);
            }
          } else if (data.status === 'borrowed') {
            displayMessage('QR Code Successfully Scanned!', 'success');
            if (lastStatus !== 'borrowed') {
              sessionStorage.setItem('facultyLastStatus', 'borrowed');
              sessionStorage.removeItem('facultyLastTransactionCode');
            }
          } else if (data.status === 'expired') {
            displayMessage('QR Code Ticket Expired', 'expired');
            if (lastStatus !== 'expired') {
              sessionStorage.setItem('facultyLastStatus', 'expired');
              sessionStorage.removeItem('facultyLastTransactionCode');
            }
          } else { // 'none'
            resetToDefault();
            if (lastStatus !== 'none') {
              sessionStorage.setItem('facultyLastStatus', 'none');
              sessionStorage.removeItem('facultyLastTransactionCode');
            }
          }

        } catch (err) {
          console.error('Error checking ticket status:', err);
        } finally {
          isChecking = false;
        }
      }

      // --- SIMPLIFIED INITIAL STATE ---
      // Let the first AJAX call determine the correct initial display

      setInterval(checkTicketStatus, 2000);
      checkTicketStatus(); // Initial check on load

    });
  </script>
</main>