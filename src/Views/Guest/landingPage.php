<?php
// Tandaan: Ang BASE_URL at Session ay dapat na-load na ng iyong main index.php

if (isset($_SESSION['user_id'])) {

    // I-redirect ang lahat ng naka-login sa generic na /dashboard URL.
    $redirect_path = BASE_URL . '/dashboard';

    header('Location: ' . $redirect_path);
    exit;
}
// ... [Iba pang HTML/Content ng landing page] ...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library | Guest</title>
    <!-- Tailwind CSS -->
    <link href="<?= BASE_URL ?>/css/output.css" rel="stylesheet">
    <!-- PHOSPHOR ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_URL_JS = '<?= BASE_URL ?>';
    </script>
</head>

<body class="bg-orange-50/50 text-gray-700 text-md">
    <!-- HERO -->
    <section class="max-w-6xl mx-auto mt-4 sm:mt-10 px-4">
        <div
            class="bg-gradient-to-r from-orange-700 to-amber-500 rounded-2xl px-6 sm:px-8 py-8 flex flex-col lg:flex-row justify-between items-center text-white shadow-lg gap-6 text-center lg:text-left">

            <!-- Left Section -->
            <div class="w-full">
                <div class="flex items-center justify-center lg:justify-start gap-3">
                    <div class="bg-white/20 p-3 rounded-lg flex items-center justify-center">
                        <i class="ph ph-books text-xl"></i>
                    </div>
                    <h1 class="text-3xl sm:text-4xl text-white font-bold">Book Collections</h1>
                </div>

                <p class="text-base mt-2 opacity-90"><i class="ph ph-sparkle"></i> Discover your next great read</p>
                <p class="text-sm mt-3 text-orange-50 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Browse our comprehensive collection of academic resources. Find books, check availability,
                    and seamlessly add them to your borrowing cart.
                </p>
            </div>

            <!-- Sign In Button -->
            <button onclick="window.location.href='<?= BASE_URL ?>/login'"
                class="self-center bg-white text-orange-700 flex items-center gap-2 px-6 py-3 rounded-md text-base text-sm font-medium hover:bg-orange-50 hover:shadow-md transition w-full sm:w-auto flex-shrink-0">
                <i class="ph ph-sign-in text-lg"></i>
                Sign In to Borrow
            </button>
        </div>
    </section>

    <!-- SEARCH SECTION -->
    <section class="max-w-6xl mx-auto mt-8 px-4">
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
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 items-center">
                <!-- SEARCH INPUT -->
                <div class="flex-1 w-full sm:min-w-[300px] relative">
                    <i
                        class="ph ph-magnifying-glass text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 text-base"></i>
                    <input type="text" id="searchInput" placeholder="Search by title, author, ISBN..."
                        class="w-full border border-orange-200 rounded-lg pl-9 pr-3 py-3 text-sm outline-none transition focus:border-orange-400">
                </div>

                <!-- STATUS DROPDOWN -->
                <div class="relative w-full sm:w-auto">
                    <button id="statusDropdownBtn"
                        class="w-full sm:w-40 flex items-center justify-between px-4 py-3 rounded-lg border border-orange-200 bg-white hover:bg-orange-50 transition text-sm focus:outline-none align-middle">
                        <span class="flex items-center gap-2 text-gray-700">
                            <i class="ph ph-check-circle text-gray-500"></i>
                            <span id="statusDropdownValue">All Status</span>
                        </span>
                        <i class="ph ph-caret-down text-gray-500 transition-transform duration-200"></i>
                    </button>

                    <!-- MENU -->
                    <div id="statusDropdownMenu"
                        class="absolute mt-2 w-full sm:w-40 bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
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
    <section class="max-w-6xl mx-auto mt-10 mb-20 px-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 flex-shrink-0">Book Collections</h2>
            <p id="resultsIndicator" class="text-sm text-gray-600 w-full sm:w-auto sm:text-right">Loading...</p>
        </div>

        <!-- BOOK CARD DISPLAY -->
        <div id="bookGrid"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6 place-items-stretch">
            <!-- Book cards will be injected here by JavaScript -->
        </div>

        <!-- PAGINATION CONTROLS -->
        <nav id="paginationControls" aria-label="Page navigation"
            class="flex items-center justify-center bg-white border border-gray-200 rounded-full shadow-md px-4 py-2 mt-8 w-fit mx-auto gap-3 hidden">
            <ul id="paginationList" class="flex items-center h-9 text-sm gap-3">
                <!-- Pagination links will be injected here -->
            </ul>
        </nav>
    </section>

    <!-- MODAL -->
    <div id="bookModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300 ease-out p-4">
        <div id="bookModalContent"
            class="bg-white w-full max-w-lg rounded-2xl shadow-lg overflow-hidden transform scale-95 transition-transform duration-300 ease-out max-h-[90vh] flex flex-col">
            <div
                class="bg-gradient-to-r from-orange-500 to-amber-500 p-4 text-white flex-shrink-0 flex justify-between items-center">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div id="modalCoverContainer"
                        class="w-12 h-16 rounded-md flex items-center justify-center bg-white/20 flex-shrink-0">
                        <i id="modalIcon" class="ph ph-book-open text-3xl text-white"></i>
                        <img id="modalImg" src="" alt="Book Cover"
                            class="w-full h-full object-cover rounded-md bg-white hidden" />
                    </div>
                    <div class="overflow-hidden">
                        <h2 id="modalTitle" class="text-lg font-bold text-white truncate">Book Title</h2>
                        <p id="modalAuthor" class="text-sm truncate">by Author</p>
                    </div>
                </div>
                <button id="closeModal"
                    class="text-white text-3xl hover:text-red-500 transition-colors duration-200 flex-shrink-0 ml-2">
                    <i class="ph ph-x-circle"></i>
                </button>
            </div>

            <div class="p-4 space-y-4 overflow-y-auto">
                <div class="grid grid-cols-2 gap-4">
                    <div
                        class="p-3 shadow-sm border border-orange-100 bg-orange-50/50 rounded flex flex-col items-start">
                        <p class="text-xs text-orange-500 font-semibold mb-1">STATUS</p>
                        <p id="modalStatus" class="font-semibold text-sm">AVAILABLE</p>
                    </div>

                    <div
                        class="p-3 shadow-sm border border-orange-100 bg-orange-50/50 rounded flex flex-col items-start">
                        <p class="text-xs text-orange-500 font-semibold mb-1">CALL NUMBER</p>
                        <p id="modalCallNumber" class="text-sm font-semibold">N/A</p>
                    </div>
                </div>

                <div class="text-sm bg-white rounded-lg border border-gray-200 p-3 space-y-1.5">
                    <p class="font-semibold text-gray-700 text-sm mb-1">Book Information</p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Accession #:</span> <span id="modalAccessionNumber"
                            class="font-mono text-sm font-semibold text-orange-600 break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">ISBN:</span> <span id="modalIsbn" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Subject:</span> <span id="modalSubject" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Place:</span> <span id="modalPlace" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Publisher:</span> <span id="modalPublisher" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Year:</span> <span id="modalYear" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Edition:</span> <span id="modalEdition" class="break-words"></span></p>
                    <p><span class="text-gray-500 w-28 inline-block flex-shrink-0">Supplementary:</span> <span id="modalSupplementary" class="break-words"></span></p>
                </div>

                <div class="bg-orange-50/30 rounded-lg p-3 border border-orange-100">
                    <p class="font-semibold text-orange-800 mb-1 text-sm">Description</p>
                    <p class="text-gray-700 text-sm" id="modalDescription"></p>
                </div>
            </div>

            <div class="px-3 py-4 bg-gray-50 border-t border-gray-200 mt-auto flex-shrink-0">
                <button id="signInToBorrowBtn"
                    class="inline-flex items-center justify-center whitespace-nowrap text-sm w-full gap-3 h-11 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white shadow-lg hover:shadow-xl transition-all duration-200 rounded-xl font-semibold"
                    onclick="window.location.href='<?= BASE_URL ?>/login'">
                    <span><i class="ph ph-sign-in"></i></span> Sign In to Borrow
                </button>
            </div>
        </div>
    </div>

   <script>
    // --- SweetAlert Helper Functions (Para sa Loading Modal) ---
    // (Galing ito sa SweetAlert design na ginamit mo sa nakaraang query)
    function showLoadingModal(message = "Processing request...", subMessage = "Please wait.") {
        if (typeof Swal == "undefined") return;
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                    <p class="text-gray-700 text-[14px]">${message}<br><span class="text-sm text-gray-500">${subMessage}</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
            },
        });
    }

    // Isama na rin natin ang isang dummy toast para hindi mag-error ang ibang SweetAlert calls (kahit hindi ginamit dito)
    function showErrorToast(title, body = "Error occurred.") {
        if (typeof Swal == "undefined") return alert(title);
        Swal.fire({ toast: true, position: "bottom-end", showConfirmButton: false, timer: 3000, title: title, text: body, icon: 'error' });
    }

    // --- DOM Content Loaded ---
    document.addEventListener("DOMContentLoaded", () => {
        // --- DOM Elements ---
        const bookGrid = document.getElementById('bookGrid');
        const searchInput = document.getElementById('searchInput');
        const resultsIndicator = document.getElementById('resultsIndicator');
        const paginationControls = document.getElementById("paginationControls");
        const paginationList = document.getElementById("paginationList");

        // --- State ---
        let currentPage = 1;
        // --- Page Memory ---
        try {
            const savedPage = sessionStorage.getItem('guestLandingPage');
            if (savedPage) {
                const parsedPage = parseInt(savedPage, 10);
                if (!isNaN(parsedPage) && parsedPage > 0) {
                    currentPage = parsedPage;
                } else {
                    sessionStorage.removeItem('guestLandingPage');
                }
            }
        } catch (e) {
            console.error("SessionStorage Error:", e);
            currentPage = 1;
        }
        const limit = 30;
        let totalBooks = 0;
        let totalPages = 1;
        let currentSearch = '';
        let currentStatus = 'All Status';
        let isLoading = false;
        let searchDebounce;

        // --- Data Fetching (BINAGO PARA TANGGAPIN ANG isShowLoadingModal) ---
        async function loadBooks(page = 1, isShowLoadingModal = true) {
            if (isLoading) return;
            isLoading = true;
            currentPage = page;

            // --- 1. SET START TIME FOR DELAY CHECK ---
            const startTime = Date.now();

            // --- Page Memory ---
            try {
                sessionStorage.setItem('guestLandingPage', currentPage);
            } catch (e) {
                console.error("SessionStorage Error:", e);
            }

            // Show loading state sa page/table
            bookGrid.innerHTML = `<div class="col-span-full text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-3xl"></i><p class="mt-2">Loading Books...</p></div>`;
            paginationControls.classList.add('hidden');
            resultsIndicator.textContent = 'Loading...';

            // --- 2. SHOW SWEETALERT LOADING MODAL (KUNG KAILANGAN) ---
            if (isShowLoadingModal) {
                showLoadingModal("Loading Books...", "Fetching the current book list.");
            }
            
            const offset = (page - 1) * limit;
            const params = new URLSearchParams({
                search: currentSearch,
                status: currentStatus === 'All Status' ? '' : currentStatus,
                limit: limit,
                offset: offset,
                sort: 'default'
            });

            try {
                const res = await fetch(`${BASE_URL_JS}/api/guest/fetchBooks?${params.toString()}`);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                const data = await res.json();
                
                // --- 3. CLOSE LOADING MODAL (SUCCESS PATH) ---
                if (isShowLoadingModal) { // Kung nagpakita ng modal, isara ito
                    const elapsed = Date.now() - startTime;
                    const minDelay = 500; // Minimum 500ms display
                    if (elapsed < minDelay) await new Promise(r => setTimeout(r, minDelay - elapsed));
                    if (typeof Swal != "undefined") Swal.close();
                }

                if (data.books) {
                    totalBooks = data.totalCount;
                    totalPages = Math.ceil(totalBooks / limit) || 1;
                    
                    if (page > totalPages && totalPages > 0) {
                        // Tawagin ulit ang loadBooks, pero gamitin ang loading modal (true)
                        loadBooks(totalPages, isShowLoadingModal); 
                        return;
                    }

                    renderBooks(data.books);
                    renderPagination(totalPages, currentPage);
                    updateResultsIndicator(data.books.length, totalBooks, page, limit);
                } else {
                    throw new Error(data.message || "Invalid data format from server.");
                }
            } catch (err) {
                // --- 4. CLOSE LOADING MODAL (ERROR PATH) ---
                if (isShowLoadingModal && typeof Swal != "undefined") Swal.close(); 
                
                console.error("Fetch books error:", err);
                bookGrid.innerHTML = `<div class="col-span-full text-center text-red-500 py-10"><i class="ph ph-warning-circle text-3xl"></i><p class="mt-2">Error loading books.</p></div>`;
                updateResultsIndicator(0, 0, 1, limit);
                // Optionally show error toast if you have the helper function defined
                // showErrorToast("Loading Failed", "Could not retrieve book list. Check your connection.");
            } finally {
                isLoading = false;
            }
        }

        // --- Rendering ---
        // ... (Omitted renderBooks, createBookCard, renderPagination, updateResultsIndicator - Walang pagbabago dito)
        function renderBooks(books) {
            bookGrid.innerHTML = ""; // Clear loading/previous state
            if (!books || books.length === 0) {
                bookGrid.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-10">
                        <i class="ph ph-books text-5xl mb-3"></i>
                        <p class="font-medium text-gray-700">No books found</p>
                        <p class="text-sm text-gray-500">No books match your current filters.</p>
                    </div>`;
                return;
            }
            books.forEach(book => {
                bookGrid.appendChild(createBookCard(book));
            });
        }

        function createBookCard(book) {
            const div = document.createElement('div');
            div.className = "book-card relative bg-white border border-orange-100 rounded-2xl overflow-hidden shadow-sm group transform transition duration-300 hover:-translate-y-1 hover:shadow-lg cursor-pointer w-full flex flex-col";
            div.setAttribute('data-book', JSON.stringify(book));

            const availabilityClass = (book.availability || '').toLowerCase() === 'available' ? 'bg-orange-500' : 'bg-red-500';
            const availabilityText = book.availability ? book.availability.charAt(0).toUpperCase() + book.availability.slice(1) : 'Unknown';

            div.innerHTML = `
                <div class="w-full aspect-[2/3] bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                    ${book.cover 
                        ? `<img src="${book.cover}" alt="${book.title}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">` 
                        : `<i class="ph ph-book-open text-gray-400 text-5xl"></i>`}
                </div>
                <span class="absolute top-2 left-2 px-3 py-1 text-xs font-medium rounded-full text-white shadow-sm ${availabilityClass}">
                    ${availabilityText}
                </span>
                <div class="p-3 bg-white group-hover:bg-gray-50 transition-colors duration-300 flex flex-col flex-grow">
                    <h4 class="text-sm font-semibold text-gray-700 group-hover:text-orange-600 line-clamp-2 min-h-[2.5rem]">
                        ${book.title || 'No Title'}
                    </h4>
                    <p class="text-xs text-gray-500 truncate mt-0.5">
                        by ${book.author || 'Unknown Author'}
                    </p>
                    <p class="text-[11px] font-medium text-orange-600 mt-1 truncate flex-grow">
                        ${book.subject || ''}
                    </p>
                </div>
            `;
            return div;
        }

        function renderPagination(totalPages, page) {
            if (totalPages <= 1) {
                paginationControls.classList.add("hidden");
                return;
            }
            paginationControls.classList.remove("hidden");
            paginationList.innerHTML = '';

            const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
                const li = document.createElement("li");
                const a = document.createElement("a");
                a.href = "#";
                a.setAttribute("data-page", String(pageNum));
                let baseClasses = `flex items-center justify-center min-w-[32px] h-9 text-sm font-medium transition-all duration-200`;
                if (type === "prev" || type === "next") {
                    a.innerHTML = text;
                    baseClasses += ` text-gray-700 hover:text-orange-600 px-3`;
                    if (isDisabled) baseClasses += ` opacity-50 cursor-not-allowed pointer-events-none`;
                } else if (type === "ellipsis") {
                    a.textContent = text;
                    baseClasses += ` text-gray-400 cursor-default px-2`;
                } else {
                    a.textContent = text;
                    if (isActive) {
                        baseClasses += ` text-white bg-orange-600 rounded-full shadow-sm px-3`;
                    } else {
                        baseClasses += ` text-gray-700 hover:text-orange-600 hover:bg-orange-100 rounded-full px-3`;
                    }
                }
                a.className = baseClasses;
                li.appendChild(a);
                paginationList.appendChild(li);
            };

            const isMobile = window.innerWidth < 640; 
            let pagesToShow = new Set();

            if (isMobile) {
              for (let i = -1; i <= 1; i++) {
                const p = page + i;
                if (p > 0 && p <= totalPages) pagesToShow.add(p);
              }
            } else {
              pagesToShow.add(1);
              pagesToShow.add(totalPages);
              for (let i = -2; i <= 2; i++) {
                const p = page + i;
                if (p > 0 && p <= totalPages) pagesToShow.add(p);
              }
            }

            const sortedPages = [...pagesToShow].sort((a, b) => a - b);
            let lastPage = 0;
            for (const p of sortedPages) {
              if (!isMobile && p > lastPage + 1)
                createPageLink("ellipsis", "…", "...", true);
              createPageLink("number", p, p, false, p === page);
              lastPage = p;
            }
            createPageLink("next",`Next <i class="flex ph ph-caret-right text-lg"></i>`, page + 1, page === totalPages);
        }

        function updateResultsIndicator(booksLength, totalCount, page, perPage) {
            if (totalCount === 0) {
                resultsIndicator.innerHTML = `Showing <span class="font-medium text-gray-800">0</span> of <span class="font-medium text-gray-800">0</span> books`;
            } else {
                const startItem = (page - 1) * perPage + 1;
                const endItem = (page - 1) * perPage + booksLength;
                resultsIndicator.innerHTML = `Showing <span class="font-medium text-gray-800">${startItem}-${endItem}</span> of <span class="font-medium text-gray-800">${totalCount.toLocaleString()}</span> books`;
            }
        }

        // --- Event Listeners (Gawin nating walang loading modal ang search at filter) ---
        searchInput.addEventListener("input", e => {
            currentSearch = e.target.value.trim();
            clearTimeout(searchDebounce);
            searchDebounce = setTimeout(() => {
                try {
                    sessionStorage.removeItem('guestLandingPage');
                } catch (e) {}
                // Load nang walang loading modal
                loadBooks(1, false); 
            }, 500);
        });

        paginationList.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoading) return;
            const target = e.target.closest('a[data-page]');
            if (!target) return;
            const pageStr = target.dataset.page;
            if (pageStr === '...') return;
            const pageNum = parseInt(pageStr, 10);
            if (!isNaN(pageNum) && pageNum !== currentPage) {
                // Load nang walang loading modal
                loadBooks(pageNum, false); 
                window.scrollTo({ top: bookGrid.offsetTop - 80, behavior: 'smooth' });
            }
        });

        // --- Modal Logic ---
        const modal = document.getElementById("bookModal");
        const modalContent = document.getElementById("bookModalContent");
        const closeModalBtn = document.getElementById("closeModal");

        function openModal(book) {
            const modalImg = document.getElementById("modalImg");
            const modalIcon = document.getElementById("modalIcon");
            
            if (book.cover) {
                modalImg.src = book.cover;
                modalImg.classList.remove("hidden");
                modalIcon.classList.add("hidden");
            } else {
                modalImg.classList.add("hidden");
                modalIcon.classList.remove("hidden");
            }

            document.getElementById("modalTitle").textContent = book.title || 'No Title';
            document.getElementById("modalAuthor").textContent = "by " + (book.author || "Unknown");
            document.getElementById("modalCallNumber").textContent = book.call_number || "N/A";
            document.getElementById("modalAccessionNumber").textContent = book.accession_number || "N/A";
            document.getElementById("modalIsbn").textContent = book.book_isbn || "N/A";
            document.getElementById("modalSubject").textContent = book.subject || "N/A";
            document.getElementById("modalPlace").textContent = book.book_place || "N/A";
            document.getElementById("modalPublisher").textContent = book.book_publisher || "N/A";
            document.getElementById("modalYear").textContent = book.year || "N/A";
            document.getElementById("modalEdition").textContent = book.book_edition || "N/A";
            document.getElementById("modalSupplementary").textContent = book.book_supplementary || "N/A";
            document.getElementById("modalDescription").textContent = book.description || "No description available.";
            
            const statusEl = document.getElementById("modalStatus");
            statusEl.textContent = (book.availability || "").toUpperCase();
            statusEl.className = `font-semibold text-sm ${(book.availability || '').toLowerCase() === "available" ? "text-green-600" : "text-orange-600"}`;

            modal.classList.remove("hidden");
            requestAnimationFrame(() => {
                modal.classList.remove("opacity-0");
                modalContent.classList.remove("scale-95");
            });
        }

        function closeModal() {
            modal.classList.add("opacity-0");
            modalContent.classList.add("scale-95");
            setTimeout(() => modal.classList.add("hidden"), 300);
        }

        bookGrid.addEventListener("click", e => {
            const card = e.target.closest(".book-card");
            if (card && card.dataset.book) {
                try {
                    openModal(JSON.parse(card.dataset.book));
                } catch (err) { console.error("Failed to parse book data:", err); }
            }
        });

        closeModalBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", e => { if (e.target === modal) closeModal(); });
        document.addEventListener("keydown", e => { if (e.key === "Escape") closeModal(); });

        // --- Dropdown Logic (Gawin nating walang loading modal ang filter) ---
        window.selectStatus = (el, val) => {
            document.getElementById("statusDropdownValue").textContent = val;
            document.getElementById("statusDropdownMenu").classList.add("hidden");
            document.querySelectorAll(".status-item").forEach(btn => btn.classList.remove("bg-orange-100", "text-orange-700", "font-medium"));
            el.classList.add("bg-orange-100", "text-orange-700", "font-medium");
            currentStatus = val;
            try {
                sessionStorage.removeItem('guestLandingPage');
            } catch (e) {}
            // Load nang walang loading modal
            loadBooks(1, false); 
        };

        const statusBtn = document.getElementById("statusDropdownBtn");
        const statusMenu = document.getElementById("statusDropdownMenu");
        statusBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            statusMenu.classList.toggle("hidden");
        });
        document.addEventListener("click", () => statusMenu.classList.add("hidden"));

        // --- Initial Load ---
        // Ito ang magpapakita ng loading modal (default parameter is true)
        loadBooks(currentPage);
    });
    </script>

</body>

</html>