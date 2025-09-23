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
                <input type="text" placeholder="Search by title, author, ISBN, Book ID..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition" />
            </div>

            <!-- CATEGORY DROPDOWN -->
            <div class="relative">
                <button id="categoryDropdownBtn"
                    class="w-48 flex items-center justify-between px-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] hover:bg-[var(--color-orange-50)] transition">
                    <span class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-list text-gray-500"></i>
                        <span id="categoryDropdownValue"
                            class="truncate block max-w-[120px] text-ellipsis whitespace-nowrap">
                            All Categories
                        </span>
                    </span>
                    <i class="ph ph-caret-down text-gray-500"></i>
                </button>

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
            // CATEGORY
            const categoryBtn = document.getElementById("categoryDropdownBtn");
            const categoryMenu = document.getElementById("categoryDropdownMenu");
            const categoryValue = document.getElementById("categoryDropdownValue");

            categoryBtn.addEventListener("click", () => {
                categoryMenu.classList.toggle("hidden");
            });

            function selectCategory(el, value) {
                categoryValue.textContent = value;

                // Clear highlight
                document.querySelectorAll("#categoryDropdownMenu .dropdown-item")
                    .forEach(item => item.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));

                // Highlight selected
                el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");

                categoryMenu.classList.add("hidden");
            }

            // STATUS
            const statusBtn = document.getElementById("statusDropdownBtn");
            const statusMenu = document.getElementById("statusDropdownMenu");
            const statusValue = document.getElementById("statusDropdownValue");

            statusBtn.addEventListener("click", () => {
                statusMenu.classList.toggle("hidden");
            });

            function selectStatus(el, value) {
                statusValue.textContent = value;

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
            <span
                class="bg-[var(--color-green-100)] text-[var(--color-green-700)] px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                <i class="ph ph-check-circle"></i>
                Available:
                <!-- dito ilagay yung mag ccount ng available books -->
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

    <!-- MODAL -->
    <div id="bookModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 
                hidden opacity-0 transition-opacity duration-300 ease-out">

        <div id="bookModalContent" class="bg-[var(--color-card)] w-full max-w-lg rounded-2xl shadow-lg overflow-hidden 
                    transform scale-95 transition-transform duration-300 ease-out">


            <div class="flex justify-between items-center bg-[var(--color-orange-500)] text-white px-4 py-3">
                <div class="flex items-center gap-3">
                    <img id="modalImg" src="" alt="Book Cover" class="w-12 h-16 object-cover rounded bg-white" />
                    <div>
                        <h2 id="modalTitle" class="text-lg font-bold text-white">Book Title</h2>
                        <p id="modalAuthor" class="text-sm">by Author</p>
                    </div>
                </div>
                <button id="closeModal" class="text-white text-xl"><i class="ph ph-x-circle"></i></button>
            </div>


            <div class="p-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 shadow-md bg-orange-50 rounded">
                        <p class="text-xs text-gray-500">Availability</p>
                        <p id="modalAvailability" class="font-semibold text-green-600">0 of 0</p>
                    </div>
                    <div class="p-3 shadow-md bg-orange-50 rounded">
                        <p class="text-xs text-gray-500">Call Number</p>
                        <p id="modalCallNumber" class="font-semibold">N/A</p>
                    </div>
                </div>

                <div class="p-3 shadow-md rounded">
                    <p class="text-xs text-gray-500">Book Information</p>
                    <p><span class="font-semibold">Accession Number:</span> <span id="modalAccessionNumber"></span></p>
                    <p><span class="font-semibold">ISBN:</span> <span id="modalIsbn"></span></p>
                    <p><span class="font-semibold">Subject:</span> <span id="modalSubject"></span></p>
                    <p><span class="font-semibold">Book Place:</span> <span id="modalPlace"></span></p>
                    <p><span class="font-semibold">Book Publisher:</span> <span id="modalPublisher"></span></p>
                    <p><span class="font-semibold">Year:</span> <span id="modalYear"></span></p>
                    <p><span class="font-semibold">Book Edition:</span> <span id="modalEdition"></span></p>
                    <p><span class="font-semibold">Book Suplementary:</span> <span id="modalSupplementary"></span></p>
                </div>

                <div class="p-3 shadow-md rounded bg-[var(--color-green-50)]">
                    <p class="text-xs text-gray-500">Description</p>
                    <p id="modalDescription"></p>
                </div>
            </div>


            <div class="md:col-span-4 flex justify-center mt-4 mb-6">
                <button
                    class="px-4 sm:px-5 py-2 bg-green-500 text-white text-sm sm:text-base font-medium rounded-md hover:bg-green-600 transition">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
    window.addEventListener("DOMContentLoaded", () => {
        const books = [{
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            },
            {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            }, {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            }, {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            }, {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            }, {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "/Libsys/assets/books-img/On-Liberty-Man.png",
                status: "Unavailable",
                left: 0,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            }, {
                title: "On liberty: Man the sate",
                author: "Mayer, Milton",
                Subject: "State, The. Liberty.",
                img: "Wala" //sample lang to test if wala ang image
                    ,
                status: "Available",
                left: 3,
                AccessionNumber: "00007914",
                isbn: "N/A",
                callNumber: "A 536 M4661 1908",
                description: "192 pages ; 21 cm",
                Place: "California",
                Publisher: "The Center for the Study of Democratic Institutions",
                Year: "1908",
                Edition: "13th",
                Supplementary: "N/A"

            },
        ];

        const grid = document.getElementById("booksGrid");
        const modal = document.getElementById("bookModal");
        const modalContent = document.getElementById("bookModalContent");
        const closeModalBtn = document.getElementById("closeModal");

        const modalImg = document.getElementById("modalImg");
        const modalTitle = document.getElementById("modalTitle");
        const modalAuthor = document.getElementById("modalAuthor");
        const modalAvailability = document.getElementById("modalAvailability");
        const modalCallNumber = document.getElementById("modalCallNumber");
        const modalAccessionNumber = document.getElementById("modalAccessionNumber");
        const modalIsbn = document.getElementById("modalIsbn");
        const modalSubject = document.getElementById("modalSubject");
        const modalDescription = document.getElementById("modalDescription");
        const modalPlace = document.getElementById("modalPlace");
        const modalBookPublisher = document.getElementById("modalBookPublisher");
        const modalYear = document.getElementById("modalYear");
        const modalEdition = document.getElementById("modalEdition");
        const modalSupplementary = document.getElementById("modalSupplementary");

        grid.innerHTML = "";

        // Render book cards
        books.forEach((book, index) => {
            const card = document.createElement("div");
            card.className =
                "book-card relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[230px] cursor-pointer";
            card.dataset.index = index;

            const imgWrap = document.createElement("div");
            imgWrap.className =
                "w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden";

            if (book.img && book.img !== "Wala" && book.img !== "wala") {
                const img = document.createElement("img");
                img.src = book.img;
                img.alt = book.title;
                img.className =
                    "h-full w-auto object-contain group-hover:scale-105 transition duration-300";
                img.onerror = () => {
                    img.remove();
                    imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
                };
                imgWrap.appendChild(img);
            } else {
                imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
            }

            const leftBadge = document.createElement("span");
            leftBadge.className =
                `absolute top-2 left-2 ${book.left > 0 ? "bg-[var(--color-green-500)]" : "bg-gray-400"} text-white text-xs px-2 py-1 rounded-full shadow`;
            leftBadge.textContent = `${book.left} left`;

            const statusBadge = document.createElement("span");
            statusBadge.className =
                `absolute top-2 right-2 ${book.status === "Available" ? "bg-[var(--color-orange-500)]" : "bg-gray-500"} text-white text-xs px-2 py-1 rounded-full shadow`;
            statusBadge.textContent = book.status;

            const info = document.createElement("div");
            info.className = "p-2";
            info.innerHTML = `
                <h4 class="text-xs font-semibold mb-0.5">${book.title}</h4>
                <p class="text-[10px] text-gray-500">by ${book.author}</p>
                <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5">${book.Subject}</p>
            `;

            card.appendChild(imgWrap);
            card.appendChild(leftBadge);
            card.appendChild(statusBadge);
            card.appendChild(info);
            grid.appendChild(card);
        });

        // --- Modal animation functions ---
        function openModal() {
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

        function closeModal() {
            modal.classList.remove("opacity-100");
            modal.classList.add("opacity-0");
            modalContent.classList.remove("scale-100");
            modalContent.classList.add("scale-95");

            setTimeout(() => {
                modal.classList.add("hidden");
            }, 300);
        }

        // open on card click
        grid.addEventListener("click", (e) => {
            const card = e.target.closest(".book-card");
            if (!card) return;
            const book = books[card.dataset.index];

            modalImg.src = book.img && book.img !== "Wala" && book.img !== "wala" ? book.img : "";
            modalImg.classList.toggle("hidden", !book.img || book.img === "Wala" || book.img ===
                "wala");
            modalTitle.textContent = book.title;
            modalAuthor.textContent = "by " + book.author;
            modalAvailability.textContent = `${book.left} of ${book.left > 0 ? book.left + 2 : 0}`;
            modalCallNumber.textContent = book.callNumber || "N/A";
            modalAccessionNumber.textContent = book.AccessionNumber || "";
            modalIsbn.textContent = book.isbn || "";
            modalSubject.textContent = book.Subject || "";
            modalPlace.textContent = book.Place || "";
            modalPublisher.textContent = book.Publisher || "";
            modalYear.textContent = book.Year || "";
            modalEdition.textContent = book.Edition || "";
            modalSupplementary.textContent = book.Supplementary || "";
            modalDescription.textContent = book.description || "No description available.";

            openModal();
        });

        // close handlers
        closeModalBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !modal.classList.contains("hidden")) {
                closeModal();
            }
        });
    });
    </script>

</body>