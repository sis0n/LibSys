<body class="min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Book Catalog</h2>
        <p class="text-gray-700">Search and browse available books in our library.</p>
    </div>
    <div class="bg-[var(--color-card)] shadow-md border border-[var(--color-border)] rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
            <i class="ph ph-magnifying-glass text-[var(--color-primary)]"></i>
            Search & Discover
        </h3>
        <p class="text-gray-600 mb-4">Find exactly what you're looking for with our advanced search tools</p>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Search by title, author, ISBN, Accession #..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] focus:ring-2 focus:ring-[var(--color-ring)] outline-none transition" />
            </div>

            <div class="relative">
                <button id="statusDropdownBtn"
                    class="w-40 flex items-center justify-between px-4 py-2 rounded-lg border border-[var(--color-border)] bg-[var(--color-input-background)] hover:bg-[var(--color-orange-50)] transition">
                    <span class="flex items-center gap-2 text-gray-700">
                        <i class="ph ph-check-circle text-gray-500"></i>
                        <span id="statusDropdownValue">All Status</span>
                    </span>
                    <i class="ph ph-caret-down text-gray-500"></i>
                </button>

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

        </div>
    </div>

    <div class="flex justify-between items-center mb-4">

        <p id="resultsIndicator" class="font-medium text-gray-700">
            Loading results...
        </p>

        <div class="flex gap-3 items-center">
            <span id="availableCount"
                class="bg-[var(--color-green-100)] text-[var(--color-green-700)] px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                <i class="ph ph-check-circle"></i>
                Available: <?= isset($available_count) ? $available_count : 0 ?>
            </span>
            <span id="cart-count"
                class="bg-[var(--color-orange-100)] text-[var(--color-orange-700)] px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                <i class="ph ph-shopping-cart text-xs"></i> 0 total items
            </span>
        </div>
    </div>

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

    <nav id="paginationControls" aria-label="Page navigation" class="flex justify-center mt-8">
        <ul id="paginationList" class="flex items-center -space-x-px h-10 text-sm">
        </ul>
    </nav>


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
                <button id="closeModal" class="text-white text-3xl hover:text-red-500 transition-colors duration-200">
                    <i class="ph ph-x-circle"></i>
                </button>
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
                <button id="addToCartBtn" data-id=""
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
        const cartCount = document.getElementById("cart-count");
        const noBooksFound = document.getElementById("noBooksFound");
        const resultsIndicator = document.getElementById("resultsIndicator");

        const statusBtn = document.getElementById("statusDropdownBtn");
        const statusMenu = document.getElementById("statusDropdownMenu");
        const statusValue = document.getElementById("statusDropdownValue");

        const modal = document.getElementById("bookModal");
        const modalContent = document.getElementById("bookModalContent");
        const closeModalBtn = document.getElementById("closeModal");
        const modalImg = document.getElementById("modalImg");
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
        const addToCartBtn = document.getElementById("addToCartBtn");


        const paginationControls = document.getElementById("paginationControls");
        const paginationList = document.getElementById("paginationList");

        const limit = 30;
        let totalPages = 1;
        let totalCount = 0;
        let isLoading = false;
        let searchValue = "";
        let statusValueFilter = "All Status";
        let cart = [];

        let currentPage = 1;
        try {
            const savedPage = sessionStorage.getItem('bookCatalogPage');
            if (savedPage) {
                const parsedPage = parseInt(savedPage, 10);
                if (!isNaN(parsedPage) && parsedPage > 0) {
                    currentPage = parsedPage;
                    console.log("Loaded page from sessionStorage:", currentPage);
                } else {
                     console.log("Invalid page found in sessionStorage, removing:", savedPage);
                     sessionStorage.removeItem('bookCatalogPage');
                }
            } else {
                 console.log("No page found in sessionStorage, using default:", currentPage);
            }
        } catch (e) {
             console.error("Could not access sessionStorage:", e);
             currentPage = 1;
        }

        if (statusBtn) {
            statusBtn.addEventListener("click", () => {
                statusMenu.classList.toggle("hidden");
            });
        }
        window.selectStatus = function(el, value) {
            statusValue.textContent = value;
            statusValueFilter = value;
            document.querySelectorAll("#statusDropdownMenu .status-item")
                .forEach(item => item.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));
            el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
            statusMenu.classList.add("hidden");
            currentPage = 1; 
            try {
                 console.log("Filter selected, removing saved page.");
                 sessionStorage.removeItem('bookCatalogPage');
            } catch (e) { console.error("Could not remove item from sessionStorage:", e); }
            loadBooks(1);
        }
        document.addEventListener("click", (e) => {
            if (statusBtn && !statusBtn.contains(e.target) && !statusMenu.contains(e.target)) {
                statusMenu.classList.add("hidden");
            }
        });
        const defaultStatus = document.querySelector("#statusDropdownMenu .status-item");
        if (defaultStatus) defaultStatus.classList.add("bg-[var(--color-orange-200)]", "font-semibold");


        async function loadCart() {
             try {
                const res = await fetch("/libsys/public/student/cart/json");
                if (!res.ok) throw new Error("Failed to load cart");
                cart = await res.json();
                updateCartBadge();
            } catch (err) {
                console.error("Error loading cart:", err);
                cart = []; 
                updateCartBadge(); 
            }
        }

        async function updateCartBadge() {
            if (!cartCount) return; 
            while (cartCount.firstChild) {
                 cartCount.removeChild(cartCount.firstChild);
            }
            const cartIcon = document.createElement("i");
            cartIcon.className = "ph ph-shopping-cart text-xs mr-1"; 
            cartCount.appendChild(cartIcon);
            const count = (cart && cart.length) ? cart.length : 0;
            cartCount.appendChild(document.createTextNode(`${count} total item(s)`));
        }

        async function addToCart(bookId) {
             try {
                const res = await fetch(`/libsys/public/student/cart/add/${bookId}`);
                 if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                const data = await res.json();
                if (data.success) {
                    cart = data.cart;
                    updateCartBadge();
                }
                 if (typeof Swal !== 'undefined') {
                     Swal.fire({
                         toast: true,
                         position: "bottom-end",
                         icon: data.success ? "success" : "warning",
                         title: data.success ? "Added to Cart" : "Already in Cart",
                         text: data.success ?
                             "The book has been successfully added to your cart!" : "This book is already in your cart.",
                         showConfirmButton: false,
                         timer: 3000,
                         timerProgressBar: true,
                     });
                 } else {
                     alert(data.success ? "Added to Cart" : "Already in Cart"); 
                 }
            } catch (err) {
                console.error("Error adding to cart:", err);
                 if (typeof Swal !== 'undefined') {
                     Swal.fire({
                         icon: "error",
                         title: "Oops...",
                         text: "Something went wrong while adding the book. Please try again.",
                         confirmButtonColor: "#ea580c",
                         timer: 3000,
                         timerProgressBar: true,
                     });
                 } else {
                     alert("Error adding book to cart."); 
                 }
            }
        }

        async function removeFromCart(cartId) {
             try {
                const res = await fetch(`/libsys/public/student/cart/remove/${cartId}`, {
                    method: "POST"
                });
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                 cart = cart.filter(item => item.cart_id !== cartId); 
                updateCartBadge(); 

                if (typeof Swal !== 'undefined') {
                     Swal.fire({
                         toast: true,
                         position: "bottom-end",
                         icon: "success",
                         title: "Removed from Cart",
                         text: "The item has been successfully removed from your cart!",
                         showConfirmButton: false,
                         timer: 3000,
                         timerProgressBar: true,
                     });
                 } else {
                     alert("Removed from cart."); 
                 }
            } catch (err) {
                console.error("Error removing item:", err);
                 if (typeof Swal !== 'undefined') {
                     Swal.fire({
                         icon: "error",
                         title: "Oops...",
                         text: "Something went wrong while removing the item. Please try again.",
                         confirmButtonColor: "#ea580c",
                         timer: 3000,
                         timerProgressBar: true,
                     });
                 } else {
                     alert("Error removing item from cart."); 
                 }
            }
        }

        async function clearCart() {
             if (typeof Swal === 'undefined') { 
                 if(confirm("Do you want to clear your entire cart?")) {
                     try {
                         const res = await fetch("/libsys/public/student/cart/clear", { method: "POST" });
                         if (!res.ok) throw new Error("Failed to clear cart");
                         cart = [];
                         updateCartBadge();
                         alert("Cart cleared!");
                     } catch (err) { console.error("Error clearing cart:", err); alert("Could not clear cart."); }
                 }
                 return;
             }
             Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to clear your entire cart?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, clear it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch("/libsys/public/student/cart/clear", {
                            method: "POST"
                        });
                        if (!res.ok) throw new Error("Failed to clear cart");
                        cart = [];
                        updateCartBadge();
                        Swal.fire({
                             toast: true,
                             position: 'bottom-end',
                             icon: 'success',
                             title: 'Cart Cleared!',
                             showConfirmButton: false,
                             timer: 2000
                        })
                    } catch (err) {
                        console.error("Error clearing cart:", err);
                         Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Could not clear the cart. Please try again.",
                            confirmButtonColor: "#ea580c",
                        });
                    }
                }
            })
        }

        addToCartBtn.addEventListener("click", () => {
            const bookId = addToCartBtn.dataset.id;
            if (bookId) addToCart(bookId);
        });


        async function loadBooks(page = 1) {
            if (isLoading || typeof page !== 'number' || page < 1) {
                 console.warn("Invalid page number requested or already loading:", page);
                 return; 
            }
            isLoading = true;
            currentPage = page; 

            grid.innerHTML = "";
            noBooksFound.classList.add("hidden");
            skeletons.style.display = "grid";
            paginationControls.style.display = "none";
            resultsIndicator.textContent = 'Loading results...';
            const skeletonStart = Date.now();
            const offset = (page - 1) * limit;

            try {
                const params = new URLSearchParams({
                    limit,
                    offset,
                    search: searchValue
                });
                if (statusValueFilter !== "All Status") {
                    params.set('status', statusValueFilter.toLowerCase());
                }

                const res = await fetch(`/libsys/public/student/bookCatalog/fetch?${params.toString()}`);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const data = await res.json();

                let books;
                if (data && typeof data === 'object' && Array.isArray(data.books) && typeof data.totalCount === 'number') {
                    books = data.books;
                    totalCount = data.totalCount; 
                } else {
                     console.error("Unexpected backend response structure:", data);
                     books = [];
                     totalCount = 0; 
                     noBooksFound.textContent = "Received invalid data from server.";
                     noBooksFound.classList.remove("hidden");
                }

                totalPages = Math.ceil(totalCount / limit);
                if (totalPages === 0) totalPages = 1;

                if (currentPage > totalPages) {
                     console.warn(`Requested page ${currentPage} exceeds total pages ${totalPages}. Correcting to ${totalPages}.`);
                     currentPage = totalPages; 
                }

                const elapsed = Date.now() - skeletonStart;
                if (elapsed < 600) await new Promise(r => setTimeout(r, 600 - elapsed));
                skeletons.style.display = "none";

                if (!books || books.length === 0) {
                     noBooksFound.classList.remove("hidden");
                     updateResultsIndicator(0, totalCount); 
                } else {
                    noBooksFound.classList.add("hidden");
                    renderBooks(books);
                    updateResultsIndicator(books.length, totalCount);
                }

                renderPagination(totalPages, currentPage); 

                 try {
                     sessionStorage.setItem('bookCatalogPage', currentPage);
                      console.log("Saved page to sessionStorage:", currentPage); 
                 } catch (e) { console.error("Could not save page to sessionStorage:", e); }

            } catch (err) {
                console.error("Error in loadBooks fetch/processing:", err);
                skeletons.style.display = "none";
                noBooksFound.classList.remove("hidden");
                noBooksFound.textContent = "Error loading books. Please check connection or try again."; 
                resultsIndicator.textContent = 'Error loading results.';
                 try { sessionStorage.removeItem('bookCatalogPage'); } catch(e){} 
            } finally {
                isLoading = false;
            }
        }

        function updateResultsIndicator(booksLength, currentTotal) {
           if (typeof currentTotal !== 'number') {
                currentTotal = 0;
            }
            totalPages = Math.ceil(currentTotal / limit);
            if (totalPages === 0) totalPages = 1;

            if (currentTotal === 0 && booksLength === 0) { 
                 resultsIndicator.textContent = 'No books found matching your criteria.';
                 return;
            }
             const startItem = Math.max(1, (currentPage - 1) * limit + 1); 
             const endItem = (currentPage - 1) * limit + booksLength;
             const totalFormatted = currentTotal.toLocaleString('en-US');

             if (startItem > endItem && currentTotal > 0) { 
                 resultsIndicator.innerHTML = `Showing page ${currentPage}. No results on this page. (Total: <span class="font-bold text-gray-900">${totalFormatted}</span>)`;
             } else {
                 resultsIndicator.innerHTML = `Results: <span class="font-bold text-gray-900">${startItem}-${endItem}</span> of <span class="font-bold text-gray-900">${totalFormatted}</span>`;
             }
        }


        function renderPagination(totalPages, page) {
             // Adjusted check
            if (totalPages <= 1) { 
                paginationControls.style.display = "none";
                return;
            }

            paginationControls.style.display = "flex";
            paginationList.innerHTML = '';

            const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '#';
                a.setAttribute('data-page', String(pageNum)); 

                let baseClasses = `flex items-center justify-center px-3 h-8 leading-tight transition-colors duration-200`; 

                if (type === 'prev' || type === 'next') {
                    a.innerHTML = text; 
                    baseClasses += ` ml-0 rounded-l-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700`; 
                    if (type === 'next') {
                         baseClasses = baseClasses.replace('rounded-l-lg', 'rounded-r-lg');
                    }
                } else if (type === 'ellipsis') {
                    a.textContent = text;
                    baseClasses += ` border border-gray-300 bg-white text-gray-500 cursor-default`; 
                } else {
                    a.textContent = text;
                    baseClasses += ` border border-gray-300`; 
                    if (isActive) {
                        baseClasses += ` z-10 text-orange-600 border-orange-300 bg-orange-100 hover:bg-orange-200 hover:text-orange-700`; 
                    } else {
                        baseClasses += ` bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700`; 
                    }
                }

                a.className = baseClasses;

                if (isDisabled) { 
                    a.className += ` text-gray-400 bg-gray-100 cursor-not-allowed hover:bg-gray-100 hover:text-gray-400`; 
                     a.setAttribute('tabindex', '-1');
                     a.dataset.page = '...';
                } else if (type === 'ellipsis') { 
                     a.setAttribute('tabindex', '-1');
                     a.dataset.page = '...';
                }


                li.appendChild(a);
                paginationList.appendChild(li);
            };

            createPageLink('prev', `<i class="ph ph-caret-left pointer-events-none"></i>`, page - 1, page === 1);

            const window = 2; 
            let pagesToShow = new Set([1, totalPages, page]);
            for(let i = 1; i <= window; i++) {
                 if (page - i > 0) pagesToShow.add(page - i);
                 if (page + i <= totalPages) pagesToShow.add(page + i); 
            }

            let sortedPages = [...pagesToShow].filter(p => p > 0 && p <= totalPages).sort((a, b) => a - b);

            let lastPage = 0;
            for (const p of sortedPages) {
                if (p > lastPage + 1) {
                     createPageLink('ellipsis', '...', '...', true);
                }
                createPageLink('number', p, p, false, p === page);
                lastPage = p;
            }
             if (lastPage < totalPages - 1) { 
                 createPageLink('ellipsis', '...', '...', true);
             }
             if (lastPage < totalPages && !sortedPages.includes(totalPages)) {
                  createPageLink('number', totalPages, totalPages, false, false);
             }


            createPageLink('next', `<i class="ph ph-caret-right pointer-events-none"></i>`, page + 1, page === totalPages); 
        }

        paginationList.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoading) return;
            const target = e.target.closest('a[data-page]'); 
            if (!target) return;

            const pageStr = target.dataset.page;
            if (pageStr === '...') return; 

             const page = parseInt(pageStr, 10);
            if (!isNaN(page) && page !== currentPage) { 
                loadBooks(page);
            }
        });


        async function loadAvailableCount() {
            try {
                const res = await fetch("/libsys/public/student/bookCatalog/availableCount");
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                const data = await res.json();
                const availableCountEl = document.getElementById("availableCount");
                if (availableCountEl) {
                     while(availableCountEl.firstChild) availableCountEl.removeChild(availableCountEl.firstChild);
                     const icon = document.createElement('i');
                     icon.className = 'ph ph-check-circle mr-1'; 
                     availableCountEl.appendChild(icon);
                     availableCountEl.appendChild(document.createTextNode(` Available: ${data.available || 0}`));
                }
            } catch (err) {
                console.error("Error fetching available count:", err);
                 const availableCountEl = document.getElementById("availableCount");
                 if(availableCountEl) availableCountEl.textContent = 'Available: Error';
            }
        }

        function renderBooks(books) {
            grid.innerHTML = '';
            if (!books || books.length === 0) return;

            books.forEach(book => {
                const card = document.createElement("div");
                card.className =
                    "book-card relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[230px] cursor-pointer"; // Original classes
                card.dataset.book = JSON.stringify(book);

                const imgWrap = document.createElement("div");
                imgWrap.className =
                    "w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden"; 
                if (book.cover) {
                    const img = document.createElement("img");
                    img.src = book.cover;
                    img.alt = book.title || 'Book Cover'; 
                    img.loading = 'lazy';
                    img.className =
                        "h-full w-auto object-contain group-hover:scale-105 transition duration-300"; 
                    img.onerror = () => { imgWrap.innerHTML = `<i class="ph ph-image-broken text-5xl text-gray-300"></i>`; };
                    imgWrap.appendChild(img);
                } else {
                     imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
                }


                const statusBadge = document.createElement("span");
                const isAvailable = book.availability === "available";
                // Original badge classes
                statusBadge.className =
                    `absolute top-2 left-2 ${isAvailable ? "bg-[var(--color-orange-500)]" : "bg-red-500"} text-white text-xs px-2 py-1 rounded-full shadow`; 
                statusBadge.textContent = isAvailable ? "Available" : "Borrowed";

                const info = document.createElement("div");
                info.className = "p-2 group-hover:bg-gray-100 transition"; // Original classes
                info.innerHTML = `
                <h4 class="text-xs font-semibold mb-0.5 truncate w-full group-hover:text-[var(--color-primary)]" title="${book.title || ''}">${book.title || 'Untitled'}</h4>
                <p class="text-[10px] text-gray-500 truncate w-full" title="${book.author || ''}">by ${book.author || "Unknown"}</p>
                <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5 truncate w-full" title="${book.subject || ''}">
                ${book.subject || ""}
                </p>
                `; 

                card.appendChild(imgWrap);
                card.appendChild(statusBadge);
                card.appendChild(info);

                if (cart && cart.some(c => c.book_id == book.book_id)) {
                    const badge = document.createElement("span");
                    badge.className =
                        "absolute bottom-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full shadow"; 
                    badge.textContent = "In Cart";
                    card.appendChild(badge);
                }
                grid.appendChild(card);
            });
        }


        function openModal(book) {
            if (!book || typeof book !== 'object') {
                 console.error("Invalid book data passed to openModal");
                 return;
             }
             addToCartBtn.dataset.id = book.book_id || ''; 
             if (book.cover) {
                modalImg.src = book.cover;
                modalImg.alt = book.title || 'Book Cover'; 
                modalImg.classList.remove("hidden");
            } else {
                 modalImg.classList.add("hidden");
                 modalImg.src = ''; 
                 modalImg.alt = '';
            }
            modalTitle.textContent = book.title || 'No Title Available';
            modalAuthor.textContent = "by " + (book.author || "Unknown");
            modalCallNumber.textContent = book.call_number || "N/A";
            modalAccessionNumber.textContent = book.accession_number || "N/A"; 
            modalIsbn.textContent = book.book_isbn || "N/A"; 
            modalSubject.textContent = book.subject || "N/A"; 
            modalPlace.textContent = book.book_place || "N/A"; 
            modalPublisher.textContent = book.book_publisher || "N/A"; 
            modalYear.textContent = book.year || "N/A"; 
            modalEdition.textContent = book.book_edition || "N/A"; 
            modalSupplementary.textContent = book.book_supplementary || "N/A"; 
            modalDescription.textContent = book.description || "No description available.";

             const availabilityText = (book.availability || "unknown").toUpperCase();
             modalStatus.textContent = availabilityText;
             modalStatus.className = `font-semibold text-xs ${
                availabilityText === "AVAILABLE" ? "text-green-600" : "text-orange-600" // Original orange text for borrowed
            }`;
            addToCartBtn.disabled = availabilityText !== "AVAILABLE";

            modal.classList.remove("hidden");
            requestAnimationFrame(() => {
                 modal.classList.remove("opacity-0");
                 modalContent.classList.remove("scale-95");
                 modal.classList.add("opacity-100");
                 modalContent.classList.add("scale-100");
            });
        }

        function closeModal() {
            modal.classList.remove("opacity-100");
            modalContent.classList.remove("scale-100");
            modal.classList.add("opacity-0");
            modalContent.classList.add("scale-95");
            setTimeout(() => {
                 modal.classList.add("hidden");
                 modalImg.src = ''; 
                 modalImg.alt = '';
             }, 300); 
        }

        grid.addEventListener("click", e => {
             const card = e.target.closest(".book-card");
            if (!card) return;
            try {
                 const book = JSON.parse(card.dataset.book);
                 openModal(book);
             } catch (parseError) {
                 console.error("Could not parse book data from card:", parseError);
                 if(typeof Swal !== 'undefined') Swal.fire('Error', 'Could not display book details.', 'error');
                 else alert('Could not display book details.');
             }
        });
        closeModalBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", e => {
            if (e.target === modal) closeModal(); 
        });
        document.addEventListener("keydown", e => {
            if (e.key === "Escape" && !modal.classList.contains("hidden")) closeModal();
        });

        searchInput.addEventListener("input", e => {
            searchValue = e.target.value.trim();
            currentPage = 1; 
            try { 
                console.log("Search input changed, removing saved page."); 
                sessionStorage.removeItem('bookCatalogPage'); 
            } catch(e){}
            loadBooks(1); 
        });

        loadAvailableCount();
        loadCart().then(() => {
             console.log("Initial load, attempting to load page:", currentPage); 
             loadBooks(currentPage); 
        });
    });
    </script>
</body>