    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Library | Guest</title>
        <!-- Tailwind CSS -->
        <link href="/LibSys/public/css/output.css" rel="stylesheet">
        <!-- PHOSPHOR ICONS -->
        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" />
        <!-- SWEETALERT2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body class="bg-orange-50/50 text-gray-700 text-md">
        <!-- HERO -->
        <section class="max-w-6xl mx-auto mt-10">
            <div
                class="bg-gradient-to-r from-orange-700 to-amber-500 rounded-2xl px-8 py-6 flex justify-between items-center text-white shadow-lg">

                <!-- Left Section -->
                <div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-3 rounded-lg flex items-center justify-center">
                            <i class="ph ph-books text-xl"></i>
                        </div>
                        <h1 class="text-4xl text-white font-bold">Book Collections</h1>
                    </div>

                    <p class="text-base mt-2 opacity-90"><i class="ph ph-sparkle"></i> Discover your next great read</p>
                    <p class="text-sm mt-3 text-orange-50 max-w-lg leading-relaxed">
                        Browse our comprehensive collection of academic resources. Find books, check availability,
                        and seamlessly add them to your borrowing cart.
                    </p>
                </div>

                <!-- Sign In Button -->
                <button onclick="window.location.href='/LibSys/public/login'"
                    class="self-center bg-white text-orange-700 flex items-center gap-2 px-6 py-4 rounded-md text-base text-sm font-medium hover:bg-orange-50 hover:shadow-md transition">
                    <i class="ph ph-sign-in text-lg"></i>
                    Sign In to Borrow
                </button>
            </div>
        </section>

        <!-- SEARCH SECTION -->
        <section class="max-w-6xl mx-auto mt-8">
            <div class="bg-white border border-orange-100 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="px-2 py-2 bg-gradient-to-br from-orange-500 to-amber-500 rounded-md flex items-center justify-center">
                        <i class="ph ph-magnifying-glass text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-600">Search & Discover</h2>
                </div>
                <p class="text-md text-gray-500 mb-6">
                    Find exactly what you're looking for with our advanced search tools.
                </p>
                <div class="flex flex-wrap gap-3 items-center">
                    <!-- SEARCH INPUT -->
                    <div class="flex-1 min-w-[300px] relative">
                        <i
                            class="ph ph-magnifying-glass text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 text-base"></i>
                        <input type="text" placeholder="Search by title, author, ISBN, Book ID..."
                            class="w-full border border-orange-200 rounded-lg pl-9 pr-3 py-3 text-sm outline-none transition focus:border-orange-400">
                    </div>

                    <!-- STATUS DROPDOWN -->
                    <div class="relative">
                        <button id="statusDropdownBtn"
                            class="w-40 flex items-center justify-between px-4 py-3 rounded-lg border border-orange-200 bg-white hover:bg-orange-50 transition text-sm focus:outline-none align-middle">
                            <span class="flex items-center gap-2 text-gray-700">
                                <i class="ph ph-check-circle text-gray-500"></i>
                                <span id="statusDropdownValue">All Status</span>
                            </span>
                            <i class="ph ph-caret-down text-gray-500 transition-transform duration-200"></i>
                        </button>

                        <!-- MENU -->
                        <div id="statusDropdownMenu"
                            class="absolute mt-2 w-40 bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                            <ul class="py-1 text-sm text-gray-700">
                                <li>
                                    <button
                                        class="status-item w-full text-left px-4 py-2  bg-orange-100 text-orange-700 font-medium"
                                        onclick="selectStatus(this, 'All Status')">All Status</button>
                                </li>
                                <li>
                                    <button class="status-item w-full text-left px-4 py-2 hover:bg-orange-100"
                                        onclick="selectStatus(this, 'Available')">Available</button>
                                </li>
                                <li>
                                    <button class="status-item w-full text-left px-4 py-2 hover:bg-orange-100"
                                        onclick="selectStatus(this, 'Borrowed')">Borrowed</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- DISPLAY BOOKS -->
        <section class="max-w-6xl mx-auto mt-10 mb-20">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Book Collections</h2>

            <!-- BOOK STATS HEADER -->
            <?php
            $totalBooks = count($books);
            $availableBooks = count(array_filter($books, fn($b) => strtolower($b['availability']) === 'available'));
            $totalCopies = array_sum(array_map(fn($b) => $b['copies'] ?? 1, $books));
            ?>
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="text-white px-2 py-2 bg-gradient-to-br from-green-500 to-teal-500 rounded-md flex items-center justify-center">
                        <i class="ph ph-book text-xl"></i>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-700"><?= $totalBooks ?> Books Found</p>
                        <p class="text-sm text-gray-500">Total collection: <?= $totalBooks ?> books</p>
                    </div>
                </div>

                <div class="flex gap-4 flex-wrap">
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Available: <?= $availableBooks ?>
                    </span>
                    <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Total Copies: <?= $totalCopies ?>
                    </span>
                </div>
            </div>

            <!-- BOOK CARD DISPLAY -->
            <div id="bookGrid"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 place-items-center">
                <?php if (!empty($books)): ?>
                    <?php $initialBooks = array_slice($books, 0, 30); ?>
                    <?php foreach ($initialBooks as $index => $book): ?>
                        <div class="book-card relative bg-white border border-orange-100 rounded-2xl overflow-hidden shadow-sm group transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer max-w-[230px] w-full flex flex-col"
                            data-book='<?= htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8') ?>'>

                            <div
                                class="w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden flex-shrink-0">
                                <?php if (!empty($book['image'])): ?>
                                    <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>"
                                        class="h-full w-auto object-contain group-hover:scale-105 transition duration-300">
                                <?php else: ?>
                                    <i class="ph ph-book-open text-gray-400 text-5xl"></i>
                                <?php endif; ?>
                            </div>

                            <span class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full text-white shadow-sm
                    <?= strtolower($book['availability']) === 'available' ? 'bg-orange-500' : 'bg-red-500' ?>">
                                <?= htmlspecialchars(ucfirst($book['availability'])) ?>
                            </span>

                            <div
                                class="p-3 bg-white group-hover:bg-gray-100 transition-colors duration-300 flex flex-col flex-grow">

                                <h4
                                    class="text-sm font-semibold text-gray-700 group-hover:text-orange-600 line-clamp-2 min-h-[2.5rem]">
                                    <?= htmlspecialchars($book['title']) ?>
                                </h4>

                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    by <?= htmlspecialchars($book['author'] ?: 'Unknown Author') ?>
                                </p>

                                <p class="text-[11px] font-medium text-orange-600 mt-1 truncate flex-grow">
                                    <?= htmlspecialchars($book['subject'] ?? '') ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-gray-500 py-10 col-span-full">No books available right now.</p>
                <?php endif; ?>
            </div>

            <div id="loadingIndicator" class="text-center text-gray-500 py-6 hidden">
                <i class="ph ph-spinner animate-spin text-xl"></i> Loading more books...
            </div>

        </section>
        <!-- MODAL -->
        <div id="bookModal"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300 ease-out">
            <div id="bookModalContent"
                class="bg-[var(--color-card)] w-full max-w-lg rounded-2xl shadow-lg overflow-hidden transform scale-95 transition-transform duration-300 ease-out">
                <div
                    class="bg-gradient-to-r from-orange-500 to-amber-500 p-4 text-white flex-shrink-0 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div id="modalCoverContainer"
                            class="w-12 h-16 rounded-md flex items-center justify-center bg-white/20">
                            <i id="modalIcon" class="ph ph-book-open text-3xl text-white"></i>
                            <img id="modalImg" src="" alt="Book Cover"
                                class="w-full h-full object-cover rounded-md bg-white hidden" />
                        </div>
                        <div>
                            <h2 id="modalTitle" class="text-lg font-bold text-white">Book Title</h2>
                            <p id="modalAuthor" class="text-sm">by Author</p>
                        </div>
                    </div>
                    <button id="closeModal"
                        class="text-white text-3xl hover:text-red-500 transition-colors duration-200">
                        <i class="ph ph-x-circle"></i>
                    </button>
                </div>

                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="p-3 shadow-md border border-orange-200 bg-orange-50 rounded flex flex-col items-start">
                            <p class="text-sm text-orange-500 font-semibold">STATUS</p>
                            <p id="modalStatus" class="font-semibold text-xs text-green-600">AVAILABLE</p>
                        </div>

                        <div
                            class="p-3 shadow-md border border-orange-200 bg-orange-50 rounded flex flex-col items-start">
                            <p class="text-sm text-orange-500 font-semibold">CALL NUMBER</p>
                            <p id="modalCallNumber" class="text-xs font-semibold">N/A</p>
                        </div>
                    </div>

                    <div class="text-sm bg-white rounded-lg border border-gray-200 p-3 space-y-1">
                        <p class="font-semibold text-foreground text-sm">Book Information</p>
                        <p><span class="text-amber-700">Accession Number:</span> <span id="modalAccessionNumber"
                                class="font-mono text-sm font-semibold text-orange-600"></span></p>
                        <p><span class="text-amber-700">ISBN:</span> <span id="modalIsbn"></span></p>
                        <p><span class="text-amber-700">Subject:</span> <span id="modalSubject"></span></p>
                        <p><span class="text-amber-700">Book Place:</span> <span id="modalPlace"></span></p>
                        <p><span class="text-amber-700">Book Publisher:</span> <span id="modalPublisher"></span></p>
                        <p><span class="text-amber-700">Year:</span> <span id="modalYear"></span></p>
                        <p><span class="text-amber-700">Book Edition:</span> <span id="modalEdition"></span></p>
                        <p><span class="text-amber-700">Book Supplementary:</span> <span id="modalSupplementary"></span>
                        </p>
                    </div>

                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg p-3 border border-orange-200">
                        <p class="font-semibold text-orange-900 mb-2 text-sm">Description</p>
                        <p class="text-amber-700 text-sm" id="modalDescription"></p>
                    </div>
                </div>

                <div class="px-3 py-4 bg-gray-50">
                    <button id="signInToBorrowBtn"
                        class="inline-flex items-center justify-center whitespace-nowrap text-sm w-full gap-3 h-11 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white shadow-lg hover:shadow-xl transition-all duration-200 rounded-xl font-semibold"
                        onclick="window.location.href='/LibSys/public/login'">
                        <span><i class="ph ph-sign-in"></i></span> Sign In to Borrow
                    </button>
                </div>
            </div>
        </div>

        <script>
            // --- Data and Pagination Setup ---
            // Ensure $books array is available from PHP
            const allBooks = <?php echo json_encode($books); ?>;
            const booksPerPage = 30;
            const bookGrid = document.getElementById('bookGrid');
            const loadingIndicator = document.getElementById('loadingIndicator');
            let currentPage = 1; // 1st batch already loaded by PHP
            let displayedBooks = allBooks;

            // --- Modal Elements ---
            const modal = document.getElementById("bookModal");
            const modalContent = document.getElementById("bookModalContent");
            const closeModalBtn = document.getElementById("closeModal");
            const modalImg = document.getElementById("modalImg");
            const modalIcon = document.getElementById("modalIcon");
            const modalTitle = document.getElementById("modalTitle");
            const modalAuthor = document.getElementById("modalAuthor");
            const modalCallNumber = document.getElementById("modalCallNumber");
            const modalAccessionNumber = document.getElementById("modalAccessionNumber");
            const modalIsbn = document.getElementById("modalIsbn");
            const modalSubject = document.getElementById("modalSubject");
            const modalDescription = document.getElementById("modalDescription");
            const modalPlace = document.getElementById("modalPlace");
            const modalPublisher = document.getElementById("modalPublisher");
            const modalYear = document.getElementById("modalYear");
            const modalEdition = document.getElementById("modalEdition");
            const modalSupplementary = document.getElementById("modalSupplementary");
            const modalStatus = document.getElementById("modalStatus");

            // --- Utility Functions ---

            function openModal(book) {
                // Set book data (assuming the book object passed has keys matching the modal IDs/needs)
                // Set cover image or icon
                if (book.image) { // Changed from book.cover to book.image to match PHP code
                    modalImg.src = book.image;
                    modalImg.classList.remove("hidden");
                    modalIcon.classList.add("hidden");
                    document.getElementById("modalCoverContainer").classList.remove("bg-white/20");
                } else {
                    modalImg.classList.add("hidden");
                    modalIcon.classList.remove("hidden");
                    document.getElementById("modalCoverContainer").classList.add("bg-white/20");
                }

                modalTitle.textContent = book.title;
                modalAuthor.textContent = "by " + (book.author || "Unknown");
                modalCallNumber.textContent = book.call_number || "N/A";
                modalAccessionNumber.textContent = book.accession_number || "";
                modalIsbn.textContent = book.book_isbn || "";
                modalSubject.textContent = book.subject || "";
                modalPlace.textContent = book.book_place || "";
                modalPublisher.textContent = book.book_publisher || "";
                modalYear.textContent = book.year || "";
                modalEdition.textContent = book.book_edition || "";
                modalSupplementary.textContent = book.book_supplementary || "";
                modalDescription.textContent = book.description || "No description available.";

                // Status styling
                modalStatus.textContent = (book.availability || "").toUpperCase();
                if ((book.availability || "").toUpperCase() === "AVAILABLE") {
                    modalStatus.classList.add("text-green-600");
                    modalStatus.classList.remove("text-orange-600");
                } else {
                    modalStatus.classList.add("text-orange-600");
                    modalStatus.classList.remove("text-green-600");
                }

                // Show Modal
                modal.classList.remove("hidden", "opacity-100");
                modal.classList.add("opacity-0");
                modalContent.classList.remove("scale-100");
                modalContent.classList.add("scale-95");
                requestAnimationFrame(() => {
                    modal.classList.remove("opacity-0");
                    modal.classList.add("opacity-100");
                    modalContent.classList.remove("scale-95");
                    modalContent.classList.add("scale-100");
                });
            }

            function closeModal() {
                modal.classList.remove("opacity-100");
                modal.classList.add("opacity-0");
                modalContent.classList.remove("scale-100");
                modalContent.classList.add("scale-95");
                setTimeout(() => modal.classList.add("hidden"), 300);
            }

            // Function to create a book card for the infinite scroll
            function createBookCard(book) {
                const div = document.createElement('div');
                // IMPORTANT: Add 'book-card' class and data-book attribute for the click listener
                div.className =
                    "book-card relative bg-white border border-orange-100 rounded-2xl overflow-hidden shadow-sm group transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer max-w-[230px] w-full";

                div.setAttribute('data-book', JSON.stringify(book));

                div.innerHTML = `
            <div class="w-full aspect-[2/3] bg-gray-50 flex items-center justify-center overflow-hidden">
                ${book.image 
                    ? `<img src="${book.image}" alt="${book.title}" class="h-full w-auto object-contain group-hover:scale-105 transition duration-300">` 
                    : `<i class="ph ph-book-open text-gray-400 text-5xl"></i>`}
            </div>
            <span class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full text-white shadow-sm ${book.availability.toLowerCase() === 'available' ? 'bg-orange-500' : 'bg-red-500'}">
                ${book.availability.charAt(0).toUpperCase() + book.availability.slice(1)}
            </span>
            <div class="p-3 bg-white group-hover:bg-orange-50 transition-colors duration-300">
                <h4 class="text-sm font-semibold text-gray-700 truncate group-hover:text-orange-600">${book.title}</h4>
                <p class="text-xs text-gray-500 truncate">by ${book.author || 'Unknown Author'}</p>
                <p class="text-[11px] font-medium text-orange-600 mt-1 truncate">${book.subject || ''}</p>
            </div>
        `;
                return div;
            }

            function loadNextPage() {
                const start = currentPage * booksPerPage;
                const end = start + booksPerPage;
                const nextBooks = displayedBooks.slice(start, end);
                if (nextBooks.length === 0) return;
                loadingIndicator.classList.remove('hidden');

                setTimeout(() => {
                    nextBooks.forEach(book => bookGrid.appendChild(createBookCard(book)));
                    currentPage++;
                    loadingIndicator.classList.add('hidden');
                }, 500);
            }

            // --- Event Listeners ---

            // 1. Modal Open Listener (Delegated to the grid)
            bookGrid.addEventListener("click", e => {
                const card = e.target.closest(".book-card");
                if (!card) return;

                // This line attempts to parse data-book from the clicked card
                try {
                    const book = JSON.parse(card.dataset.book);
                    openModal(book);
                } catch (error) {
                    console.error("Error parsing book data from card:", error);
                }
            });

            // 2. Modal Close Listeners
            closeModalBtn.addEventListener("click", closeModal);
            modal.addEventListener("click", e => {
                if (e.target === modal) closeModal();
            });
            document.addEventListener("keydown", e => {
                if (e.key === "Escape" && !modal.classList.contains("hidden")) closeModal();
            });

            window.addEventListener('scroll', () => {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
                    loadNextPage();
                }
            });

            // Dropdown functions (kept as is)
            function setupDropdownToggle(buttonId, menuId) {
                const btn = document.getElementById(buttonId);
                const menu = document.getElementById(menuId);
                const caret = btn.querySelector(".ph-caret-down");

                btn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    document.querySelectorAll(".absolute.mt-2").forEach((m) => {
                        if (m !== menu) m.classList.add("hidden");
                    });
                    const isHidden = menu.classList.toggle("hidden");
                    caret.style.transform = isHidden ? "rotate(0deg)" : "rotate(180deg)";
                });
            }

            setupDropdownToggle("statusDropdownBtn", "statusDropdownMenu");

            // Close dropdown when clicking outside
            document.addEventListener("click", () => {
                document.querySelectorAll(".absolute.mt-2").forEach(menu => menu.classList.add("hidden"));
                document.querySelectorAll(".ph-caret-down").forEach(icon => icon.style.transform = "rotate(0deg)");
            });

            // Highlight and select logic
            function selectStatus(el, val) {
                document.getElementById("statusDropdownValue").textContent = val;
                document.getElementById("statusDropdownMenu").classList.add("hidden");
                document.querySelector("#statusDropdownBtn .ph-caret-down").style.transform = "rotate(0deg)";

                // Remove highlight from all options
                document.querySelectorAll(".status-item").forEach(btn => {
                    btn.classList.remove("bg-orange-100", "text-orange-700", "font-medium");
                });

                // Highlight selected
                el.classList.add("bg-orange-100", "text-orange-700", "font-medium");
                filterBooksByStatus(val);
            }

            function filterBooksByStatus(status) {
                // Get the book grid container
                const bookGrid = document.getElementById("bookGrid");
                bookGrid.innerHTML = ""; // Clear all current cards

                // Filter logic
                let filteredBooks = [];
                if (status === "All Status") {
                    filteredBooks = allBooks;
                } else {
                    filteredBooks = allBooks.filter(b =>
                        b.availability.toLowerCase() === status.toLowerCase()
                    );
                }

                displayedBooks = filteredBooks;

                // Display filtered books
                if (filteredBooks.length > 0) {
                    filteredBooks.slice(0, 30).forEach(book => {
                        bookGrid.appendChild(createBookCard(book));
                    });
                } else {
                    bookGrid.innerHTML = `<p class="text-center text-gray-500 py-10 col-span-full">No books found with status "${status}".</p>`;
                }

                // Reset pagination to handle filtered results
                currentPage = 1;
                window.scrollTo({ top: 0, behavior: "smooth" });
            }
        </script>

    </body>

    </html>