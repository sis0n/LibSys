<body class="min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Book Catalog</h2>
        <p class="text-gray-700">Search and browse available books in our library.</p>
    </div>
    <!-- Search & Discover -->
    <div class="bg-[var(--color-card)] shadow-md border border-[var(--color-border)] rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <i class="ph ph-magnifying-glass text-[var(--color-primary)]"></i>
            Search & Discover
        </h3>
        <p class="text-gray-600 mb-4">Find exactly what you're looking for with our advanced search tools</p>

        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search by title, author, ISBN, Accession #..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition" />
            </div>

            <!-- CATEGORY DROPDOWN -->
            <div class="relative">
                <!-- Menu -->
                <div id="categoryDropdownMenu"
                    class="absolute mt-2 w-48 bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-lg hidden z-20">
                    <ul class="py-1">
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'All Categories')">All Categories</button></li>
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'Computer Science')">Computer Science</button></li>
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'Physics')">Physics</button></li>
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'Chemistry')">Chemistry</button></li>
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'Mathematics')">Mathematics</button></li>
                        <li><button class="dropdown-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectCategory(this,'History')">History</button></li>
                    </ul>
                </div>
            </div>

            <!-- STATUS DROPDOWN -->
            <div class="relative">
                <button id="statusDropdownBtn"
                    class="w-40 flex items-center justify-between px-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] hover:bg-[var(--color-orange-50)] transition">
                    <span class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-check-circle text-gray-500"></i>
                        <span id="statusDropdownValue">All Status</span>
                    </span>
                    <i class="ph ph-caret-down text-gray-500"></i>
                </button>

                <!-- Menu -->
                <div id="statusDropdownMenu"
                    class="absolute mt-2 w-40 bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-lg hidden z-20">
                    <ul class="py-1">
                        <li><button class="status-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectStatus(this,'All Status')">All Status</button></li>
                        <li><button class="status-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectStatus(this,'Available')">Available</button></li>
                        <li><button class="status-item w-full text-left px-4 py-2 hover:bg-[var(--color-orange-100)]"
                                onclick="selectStatus(this,'Borrowed')">Borrowed</button></li>
                    </ul>
                </div>
            </div>

            <script>
            // STATUS
            const statusBtn = document.getElementById("statusDropdownBtn");
            const statusMenu = document.getElementById("statusDropdownMenu");
            const statusValue = document.getElementById("statusDropdownValue");

            statusBtn.addEventListener("click", () => {
                statusMenu.classList.toggle("hidden");
            });

            function selectStatus(el, value) {
                statusValue.textContent = value;
                statusValueFilter = value;

                // Clear highlight
                document.querySelectorAll("#statusDropdownMenu .status-item")
                    .forEach(item => item.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));

                // Highlight selected
                el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");

                statusMenu.classList.add("hidden");

            }

            // Close if click outside
            document.addEventListener("click", (e) => {
                if (!categoryBtn.contains(e.target) && !categoryMenu.contains(e.target)) {
                    categoryMenu.classList.add("hidden");
                }
                if (!statusBtn.contains(e.target) && !statusMenu.contains(e.target)) {
                    statusMenu.classList.add("hidden");
                }
            });

            // Default highlight sa "All Categories" at "All Status"
            window.addEventListener("DOMContentLoaded", () => {
                const defaultCategory = document.querySelector("#categoryDropdownMenu .dropdown-item");
                if (defaultCategory) defaultCategory.classList.add("bg-[var(--color-orange-200)]",
                    "font-semibold");

                const defaultStatus = document.querySelector("#statusDropdownMenu .status-item");
                if (defaultStatus) defaultStatus.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
            });
            </script>
        </div>
    </div>

    <!-- Results Header -->
    <div class="flex justify-between items-center mb-4">
        <p class="font-medium">
            <span class="font-bold">Books Found</span>
        </p>
        <div class="flex gap-3 items-center">
            <span id="availableCount"
                class="bg-[var(--color-green-100)] text-[var(--color-green-700)] px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                <i class="ph ph-check-circle"></i>
                Available: <?= isset($available_count) ? $available_count : 0 ?>
            </span>
            <span
                class="bg-[var(--color-orange-100)] text-[var(--color-orange-700)] px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                <i class="ph ph-shopping-cart"></i>
                <!-- condition na mag lalagay ng laman ng cart --> in cart
            </span>
        </div>
    </div>

    <!-- Books Grid -->
    <div id="booksGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4"></div>

    <p id="noBooksFound" class="text-gray-500 text-center w-full hidden">No books found.</p>


    <div id="loadingSkeletons" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 w-full">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="animate-pulse bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden">
            <div class="w-full aspect-[2/3] bg-gray-200"></div>
            <div class="p-2 space-y-2">
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <!-- MODAL -->
    <div id="bookModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden opacity-0 transition-opacity duration-300 ease-out">
        <div id="bookModalContent"
            class="bg-[var(--color-card)] w-full max-w-lg rounded-2xl shadow-lg overflow-hidden transform scale-95 transition-transform duration-300 ease-out">
            <div
                class="bg-gradient-to-r from-orange-500 to-amber-500 p-4 text-white flex-shrink-0 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img id="modalImg" src="" alt="Book Cover"
                        class="w-12 h-16 object-cover rounded-md bg-white hidden" />
                    <div>

                        <h2 id="modalTitle" class="text-lg font-bold text-white">Book Title</h2>
                        <p id="modalAuthor" class="text-sm">by Author</p>
                    </div>
                </div>
                <button id="closeModal" class="text-white text-3xl"><i class="ph ph-x-circle"></i></button>
            </div>

            <div class="p-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 shadow-md border border-orange-200 bg-orange-50 rounded flex flex-col items-start">
                        <p class="text-sm text-orange-500 font-semibold">STATUS</p>
                        <p id="modalStatus" class="font-semibold text-xs text-green-600">AVAILABLE</p>
                    </div>

                    <div class="p-3 shadow-md border border-orange-200 bg-orange-50 rounded flex flex-col items-start">
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
                    <p><span class="text-amber-700">Book Supplementary:</span> <span id="modalSupplementary"></span></p>
                </div>

                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg p-3 border border-orange-200">
                    <p class="font-semibold text-orange-900 mb-2 text-sm">Description</p>
                    <p class="text-amber-700 text-sm" id="modalDescription"></p>
                </div>
            </div>

            <div class="px-3 py-4 bg-gray-50">
                <button data-slot="add-to-cart"
                    class="inline-flex items-center justify-center whitespace-nowrap text-sm disabled:pointer-events-none disabled:opacity-50 w-full gap-3 h-11 bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white shadow-lg hover:shadow-xl transition-all duration-200 rounded-xl font-semibold">
                    <span><i class="ph ph-shopping-cart-simple"></i></span> Add to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
    window.addEventListener("DOMContentLoaded", () => {
        const grid = document.getElementById("booksGrid");
        const skeletons = document.getElementById("loadingSkeletons");
        const searchInput = document.querySelector("input[placeholder*='Search']");

        const modal = document.getElementById("bookModal");
        const modalContent = document.getElementById("bookModalContent");
        const closeModalBtn = document.getElementById("closeModal");
        const modalImg = document.getElementById("modalImg");
        const modalTitle = document.getElementById("modalTitle");
        const modalAuthor = document.getElementById("modalAuthor");
        // const modalAvailability = document.getElementById("modalAvailability");
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

        let offset = 0;
        const limit = 30;
        let isLoading = false;
        let hasMore = true;
        let searchValue = "";

        async function loadBooks(reset = false) {
            if (isLoading || (!hasMore && !reset)) return;
            isLoading = true;

            if (reset) {
                offset = 0;
                grid.innerHTML = "";
                hasMore = true;
                document.getElementById("noBooksFound").classList.add("hidden");
            }

            skeletons.style.display = "grid";

            const skeletonStart = Date.now();

            try {
                const params = new URLSearchParams({
                    limit,
                    offset,
                    search: searchValue
                });

                const res = await fetch(`/libsys/public/student/fetch?${params.toString()}`);
                const data = await res.json();
                const filtered = searchValue ? filterBooks(data, searchValue) : data;

                if (reset) grid.innerHTML = "";

                const elapsed = Date.now() - skeletonStart;
                const minTime = 600;
                if (elapsed < minTime) {
                    await new Promise(resolve => setTimeout(resolve, minTime - elapsed));
                }

                skeletons.style.display = "none";

                if (filtered.length === 0) {
                    document.getElementById("noBooksFound").classList.remove("hidden");
                    hasMore = false;
                } else {
                    document.getElementById("noBooksFound").classList.add("hidden");
                    await renderBooks(filtered);
                    offset += limit;
                    if (data.length < limit) hasMore = false;
                }

            } catch (err) {
                console.error("Error loading books:", err);
                skeletons.style.display = "none";
            } finally {
                isLoading = false;
            }
        }

        function renderBooks(books) {
            books.forEach(book => {
                const card = document.createElement("div");
                card.className =
                    "book-card relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[230px] cursor-pointer";
                card.dataset.book = JSON.stringify(book);

                const imgWrap = document.createElement("div");
                imgWrap.className =
                    "w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden";
                if (book.cover) {
                    const img = document.createElement("img");
                    img.src = book.cover;
                    img.alt = book.title;
                    img.className =
                        "h-full w-auto object-contain group-hover:scale-105 transition duration-300";
                    imgWrap.appendChild(img);
                } else {
                    imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
                }


                const statusBadge = document.createElement("span");
                statusBadge.className =
                    `absolute top-2 right-2 ${book.availability === "available" ? "bg-[var(--color-orange-500)]" : "bg-red-500"} text-white text-xs px-2 py-1 rounded-full shadow`;
                statusBadge.textContent = book.availability === "available" ? "Available" : "Borrowed";

                const info = document.createElement("div");
                info.className = "p-2";
                info.innerHTML = `
                <h4 class="text-xs font-semibold mb-0.5">${book.title}</h4>
                <p class="text-[10px] text-gray-500">by ${book.author || "Unknown"}</p>
                <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5">${book.subject || ""}</p>
            `;

                card.appendChild(imgWrap);
                card.appendChild(statusBadge);
                card.appendChild(info);
                grid.appendChild(card);

            });
        }



        function openModal(book) {
            if (book.cover) {
                modalImg.src = book.cover;
                modalImg.classList.remove("hidden");
            } else {
                modalImg.classList.add("hidden");
            }

            modalTitle.textContent = book.title;
            modalAuthor.textContent = "by " + (book.author || "Unknown");
            // modalAvailability.textContent = `${book.quantity ?? 0} of ${book.quantity ?? 0}`;
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

            modalStatus.innerHTML = `
                                    <span class="text-md ${
                                        (book.availability || "").toUpperCase() === "AVAILABLE"
                                        ? "text-green-600"
                                        : "text-orange-600"
                                    }">${(book.availability || "").toUpperCase()}</span>`;

            modal.classList.remove("hidden");
            modal.classList.add("opacity-0");
            modalContent.classList.add("scale-95");
            requestAnimationFrame(() => {
                modal.classList.remove("opacity-0");
                modal.classList.add("opacity-100");
                modalContent.classList.remove("scale-95");
                modalContent.classList.add("scale-100");
            });
        }

        function filterBooks(books, query) {
            const normalize = str => str.toLowerCase().replace(/[^\w\s]/g, "");
            const q = normalize(query).trim().split(/\s+/);

            return books.filter(book => {
                const text = normalize(`${book.title} ${book.author} ${book.subject}`);
                return q.every(word => text.includes(word));
            });
        }

        function closeModal() {
            modal.classList.remove("opacity-100");
            modal.classList.add("opacity-0");
            modalContent.classList.remove("scale-100");
            modalContent.classList.add("scale-95");
            setTimeout(() => {
                modal.classList.add("hidden");
            }, 300);
        }

        grid.addEventListener("click", (e) => {
            const card = e.target.closest(".book-card");
            if (!card) return;
            const book = JSON.parse(card.dataset.book);
            openModal(book);
        });

        closeModalBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !modal.classList.contains("hidden")) {
                closeModal();
            }
        });

        let searchTimeout;
        searchInput.addEventListener("input", (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchValue = e.target.value.trim();
                loadBooks(true);
            }, 400);
        });

        window.addEventListener("scroll", () => {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
                loadBooks();
            }
        });

        loadBooks();
    });
    </script>


</body>