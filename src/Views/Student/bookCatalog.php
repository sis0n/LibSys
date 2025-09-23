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

            // ✅ Default highlight sa "All Categories" at "All Status"
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
    <div id="booksGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <script>
        const books = [{
                title: "Introduction to Computer Science",
                author: "John Smith",
                category: "Computer Science",
                img: "/Libsys/assets/books-img/intro-to-comscie.png",
                status: "Available",
                left: 3
            },
            {
                title: "Physics Fundamentals",
                author: "Jane Doe",
                category: "Physics",
                img: "/Libsys/assets/books-img/physics.png",
                status: "Borrowed",
                left: 0
            },
            {
                title: "Organic Chemistry Essentials",
                author: "Mary Brown",
                category: "Chemistry",
                left: 1,
                status: "Available",
                img: "/Libsys/assets/books-img/organic-chemistry.png"
            },
            {
                title: "Calculus Made Easy",
                author: "Thomas Stewart",
                category: "Mathematics",
                left: 2,
                status: "Available",
                img: "/Libsys/assets/books-img/calculus.png"
            },
            {
                title: "Sample Book Without Image",
                author: "Yours Truly",
                category: "Random",
                left: 1,
                status: "Available",
                img: "Wala" // This will trigger the fallback icon
            },
            {
                title: "Philippine History",
                author: "Jose Cruz",
                category: "History",
                left: 1,
                status: "Available",
                img: "/Libsys/assets/books-img/philippine-history.png"
            },
            {
                title: "Philippine History",
                author: "Jose Cruz",
                category: "History",
                left: 1,
                status: "Available",
                img: "/Libsys/assets/books-img/philippine-history.png"
            }
            
        ];

        const grid = document.getElementById("booksGrid");

        books.forEach(book => {
            grid.innerHTML += `
            <div class="relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[200px]">
                
                <!-- Book Image / Fallback Icon -->
                <div class="w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden">
                    ${
                        book.img
                        ? `<img src="${book.img}" alt="${book.title}" 
                            class="h-full w-auto object-contain group-hover:scale-105 transition duration-300" 
                            onerror="this.outerHTML='<i class=&quot;ph ph-book text-5xl text-gray-400&quot;></i>'" />`
                        : `<i class="ph ph-book text-5xl text-gray-400"></i>`
                    }
                </div>

                <!-- Status Badges -->
                <span class="absolute top-2 left-2 ${book.left > 0 ? 'bg-[var(--color-green-500)]' : 'bg-gray-400'} text-white text-xs px-2 py-1 rounded-full shadow flex items-center gap-1">
                    <span>${book.left} left</span>
                </span>

                <span class="absolute top-2 right-2 ${book.status === 'Available' ? 'bg-[var(--color-orange-500)]' : 'bg-gray-500'} text-white text-xs px-2 py-1 rounded-full shadow flex items-center gap-1">
                    <span>${book.status}</span>
                </span>

                <!-- Info -->
                <div class="p-2">
                    <h4 class="text-xs font-semibold mb-0.5">${book.title}</h4>
                    <p class="text-[10px] text-gray-500">by ${book.author}</p>
                    <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5">${book.category}</p>
                </div>
            </div>
            `;
        });
        </script>
    </div>
</body>