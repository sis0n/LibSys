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
    const sortBtn = document.getElementById("sortDropdownBtn");
    const sortMenu = document.getElementById("sortDropdownMenu");
    const sortValue = document.getElementById("sortDropdownValue");

    // Modal Elements
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
    let sortValueFilter = "default";
    let cart = [];

    let currentPage = 1;
    try {
        const savedPage = sessionStorage.getItem('bookCatalogPage');
        if (savedPage) {
            const parsedPage = parseInt(savedPage, 10);
            if (!isNaN(parsedPage) && parsedPage > 0) currentPage = parsedPage;
            else sessionStorage.removeItem('bookCatalogPage');
        }
    } catch (e) {
        console.error("SessionStorage Error:", e);
        currentPage = 1;
    }

    if (statusBtn && statusMenu && statusValue) {
        statusBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            if (sortMenu) sortMenu.classList.add("hidden");
            statusMenu.classList.toggle("hidden");
        });
        window.selectStatus = function (el, value) {
            statusValue.textContent = value;
            statusValueFilter = value;
            document.querySelectorAll("#statusDropdownMenu .status-item").forEach(i => i.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));
            if (el) el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
            statusMenu.classList.add("hidden");
            currentPage = 1;
            try {
                sessionStorage.removeItem('bookCatalogPage');
            } catch (e) { }
            loadBooks(1);
        }
        const defaultStatus = statusMenu.querySelector(".status-item");
        if (defaultStatus) defaultStatus.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
    } else console.warn("Status dropdown missing");

    if (sortBtn && sortMenu && sortValue) {
        sortBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            if (statusMenu) statusMenu.classList.add("hidden");
            sortMenu.classList.toggle("hidden");
        });
        window.selectSort = function (el, value, text) {
            sortValue.textContent = text;
            sortValueFilter = value;
            document.querySelectorAll("#sortDropdownMenu .sort-item").forEach(i => i.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));
            if (el) el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
            sortMenu.classList.add("hidden");
            currentPage = 1;
            try {
                sessionStorage.removeItem('bookCatalogPage');
            } catch (e) { }
            loadBooks(1);
        }
        const defaultSort = sortMenu.querySelector(".sort-item");
        if (defaultSort) defaultSort.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
    } else console.warn("Sort dropdown missing");

    document.addEventListener("click", (e) => {
        if (statusMenu && statusBtn && !statusBtn.contains(e.target) && !statusMenu.contains(e.target)) statusMenu.classList.add("hidden");
        if (sortMenu && sortBtn && !sortBtn.contains(e.target) && !sortMenu.contains(e.target)) sortMenu.classList.add("hidden");
    });

    async function loadCart() {
        try {
            const r = await fetch("/libsys/public/student/cart/json");
            if (!r.ok) throw Error();
            cart = await r.json();
            updateCartBadge();
        } catch (e) {
            cart = [];
            updateCartBadge();
        }
    }

    async function updateCartBadge() {
        if (!cartCount) return;
        while (cartCount.firstChild) cartCount.removeChild(cartCount.firstChild);
        const i = document.createElement("i");
        i.className = "ph ph-shopping-cart text-xs mr-1";
        cartCount.appendChild(i);
        const c = (cart?.length) ? cart.length : 0;
        cartCount.appendChild(document.createTextNode(`${c} item(s)`));
    }

    async function addToCart(id) {
        if (!id) return;
        try {
            const r = await fetch(`/libsys/public/student/cart/add/${id}`);
            if (!r.ok) throw Error((await r.json()).message || `Err ${r.status}`);
            const d = await r.json();

            if (d.success) {
                cart = d.cart || [];
                updateCartBadge();
            }

            if (typeof Swal != "undefined") {
                const isSuccess = d.success;
                const mainTitle = isSuccess ? "Added to Cart" : "Already in Cart";
                const bodyText = isSuccess
                    ? "The book has been successfully added to your request cart."
                    : "This book is already in your cart or not available for request.";
                const icon = isSuccess ? "ph-check-circle" : "ph-warning";

                const accentColor = isSuccess ? "text-green-600" : "text-orange-600";
                const accentBg = isSuccess ? "bg-green-100" : "bg-orange-100";
                
                // --- CUSTOM BORDER/SHADOW LOGIC (SUCCESS vs WARNING) ---
                const borderColor = isSuccess ? "!border-green-400" : "!border-orange-400";
                const popupBgGradient = isSuccess ? "!to-[#f0fff5]" : "!to-[#fff6ef]";
                const shadowColor = isSuccess ? "shadow-[0_0_8px_#22c55e70]" : "shadow-[0_0_8px_#ffb34770]";
                // --------------------------------------------------------

                const duration = 3000;

                // 🟢/🟠 Custom Toast Alert 
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: duration,
                    width: "360px", 
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full ${accentBg} ${accentColor}">
                                    <i class="ph ${icon} text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold ${accentColor}">${mainTitle}</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">${bodyText}</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        // Apply all custom classes here
                        popup: `!rounded-xl !shadow-md !border-2 ${borderColor} !p-4 !bg-gradient-to-b !from-[#fffdfb] ${popupBgGradient} backdrop-blur-sm ${shadowColor}`,
                    },
                });

                if (typeof closeModal === "function") closeModal();

            } else {
                alert(d.message || (d.success ? "Added to Cart!" : "Already in Cart / Not Available"));
                if (typeof closeModal === "function") closeModal(); 
            }

        } catch (e) {
            console.error("Add cart err:", e);
            if (typeof Swal != "undefined") {
                // 🔴 Error Toast (Network or generic failure)
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    background: "transparent",
                    width: "360px", 
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Failed to Add Book</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Please try again later or check your connection.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        // --- CUSTOM BORDER/SHADOW LOGIC (ERROR) ---
                        popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        // ------------------------------------------
                    },
                });
            }
        }
    }


    async function removeFromCart(id) {
        if (!id) return;

        // 🔵 Loading Animation
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                    <p class="text-gray-700 text-[14px]">Removing item...<br><span class="text-sm text-gray-500">Just a moment.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
            },
        });

        try {
            const r = await fetch(`/libsys/public/student/cart/remove/${id}`, {
                method: "POST"
            });
            if (!r.ok) throw Error("Failed to remove item");
            cart = cart.filter(i => i.book_id != id);
            updateCartBadge();

            // Slight delay before closing for smooth transition
            await new Promise((resolve) => setTimeout(resolve, 300));
            Swal.close();

            if (typeof Swal != 'undefined') {
                // 🟢 Success Toast 
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "360px", 
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                                    <i class="ph ph-check-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-green-600">Removed!</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">Item removed from cart.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        // --- CUSTOM BORDER/SHADOW LOGIC (SUCCESS) ---
                        popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
                        // ------------------------------------------
                    },
                });
            } else alert("Removed.");
        } catch (e) {
            console.error("Remove cart err:", e);
            Swal.close();
            // 🔴 Error Toast 
            Swal.fire({
                toast: true,
                position: "bottom-end",
                showConfirmButton: false,
                timer: 3000,
                width: "360px", 
                background: "transparent",
                html: `
                    <div class="flex flex-col text-left">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                <i class="ph ph-x-circle text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-red-600">Removal Failed</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while removing the item.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    // --- CUSTOM BORDER/SHADOW LOGIC (ERROR) ---
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    // ------------------------------------------
                },
            });
        }
    }

    async function clearCart() {
        if (typeof Swal != 'undefined') {
            // 🟠 Confirmation Alert (Modal)
            const confirmationResult = await Swal.fire({
                background: "transparent",
                html: `
                    <div class="flex flex-col text-center">
                        <div class="flex justify-center mb-3">
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                                <i class="ph ph-trash text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-[17px] font-semibold text-orange-700">Clear Cart?</h3>
                        <p class="text-[14px] text-gray-700 mt-1">
                            Are you sure you want to remove all items from your cart?
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: "Yes, Clear!",
                cancelButtonText: "Cancel",
                customClass: {
                    popup:
                        "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
                    confirmButton:
                        "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
                    cancelButton:
                        "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
                },
            });

            if (confirmationResult.isConfirmed) await performClearCart();
            else {
                // ❌ Cancel toast 
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "360px", 
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Cancelled</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">The cart was not cleared.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        // --- CUSTOM BORDER/SHADOW LOGIC (CANCELLED) ---
                        popup:
                            "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                        // ----------------------------------------------
                    },
                });
            }
        }
        else {
            if (confirm("Clear cart?")) await performClearCart();
        }
    }

    async function performClearCart() {
        // 🔵 Loading Animation
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                    <p class="text-gray-700 text-[14px]">Clearing cart...<br><span class="text-sm text-gray-500">Just a moment.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup:
                    "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
            },
        });

        try {
            const r = await fetch("/libsys/public/student/cart/clear", {
                method: "POST"
            });
            if (!r.ok) throw Error("Failed to clear cart");

            // Simulate delay
            await new Promise((resolve) => setTimeout(resolve, 300));
            Swal.close();

            cart = [];
            updateCartBadge();

            if (typeof Swal != 'undefined') {
                // 🟢 Success Toast 
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 2000,
                    width: "360px", 
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                                    <i class="ph ph-check-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-green-600">Cart Cleared!</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">All items removed successfully.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        // --- CUSTOM BORDER/SHADOW LOGIC (SUCCESS) ---
                        popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
                        // ------------------------------------------
                    },
                });
            } else alert("Cleared!");
        } catch (e) {
            console.error("Clear cart err:", e);
            Swal.close();
            // 🔴 Error Toast 
            Swal.fire({
                toast: true,
                position: "bottom-end",
                showConfirmButton: false,
                timer: 3000,
                width: "360px", 
                background: "transparent",
                html: `
                    <div class="flex flex-col text-left">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                <i class="ph ph-x-circle text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-red-600">Clear Failed</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">There was an issue clearing the cart.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    // --- CUSTOM BORDER/SHADOW LOGIC (ERROR) ---
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                    // ------------------------------------------
                },
            });
        }
    }

    if (addToCartBtn) addToCartBtn.addEventListener("click", () => {
        const id = addToCartBtn.dataset.id;
        if (id) addToCart(id);
    });

    async function loadBooks(page = 1) {
        if (isLoading || typeof page !== 'number' || page < 1) return;
        isLoading = true;
        currentPage = page;
        grid.innerHTML = "";
        noBooksFound.classList.add("hidden");
        skeletons.style.display = "grid";
        paginationControls.style.display = "none";
        resultsIndicator.textContent = 'Loading...';
        const start = Date.now();
        const offset = (page - 1) * limit;
        try {
            const params = new URLSearchParams({
                limit,
                offset,
                search: searchValue
            });
            if (statusValueFilter !== "All Status") params.set('status', statusValueFilter.toLowerCase());
            if (sortValueFilter !== "default") params.set('sort', sortValueFilter); // Send sort
            const res = await fetch(`/libsys/public/student/bookCatalog/fetch?${params.toString()}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            let books;
            if (data?.books && Array.isArray(data.books) && typeof data.totalCount === 'number') {
                books = data.books;
                totalCount = data.totalCount;
            } else {
                console.error("Bad response:", data);
                books = [];
                totalCount = 0;
                noBooksFound.textContent = "Invalid data.";
                noBooksFound.classList.remove("hidden");
            }
            totalPages = Math.ceil(totalCount / limit) || 1;
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
            const elapsed = Date.now() - start;
            if (elapsed < 300) await new Promise(r => setTimeout(r, 300 - elapsed));
            skeletons.style.display = "none";
            if (!books || books.length === 0) {
                noBooksFound.classList.add("hidden");
                updateResultsIndicator(0, totalCount);
            } else {
                noBooksFound.classList.add("hidden");
                renderBooks(books);
                updateResultsIndicator(books.length, totalCount);
            }
            renderPagination(totalPages, currentPage);
            try {
                sessionStorage.setItem('bookCatalogPage', currentPage);
            } catch (e) { }
        } catch (err) {
            console.error("LoadBooks error:", err);
            skeletons.style.display = "none";
            noBooksFound.classList.remove("hidden");
            noBooksFound.textContent = "Error loading.";
            resultsIndicator.textContent = 'Error!';
            try {
                sessionStorage.removeItem('bookCatalogPage');
            } catch (e) { }
        } finally {
            isLoading = false;
        }
    }

    function updateResultsIndicator(booksLength, currentTotal) {
        if (typeof currentTotal !== 'number') currentTotal = 0;
        totalPages = Math.ceil(currentTotal / limit) || 1;
        if (currentTotal === 0) {
            resultsIndicator.textContent = 'No books found.';
            return;
        }
        const startItem = Math.max(1, (currentPage - 1) * limit + 1);
        const endItem = (currentPage - 1) * limit + booksLength;
        const totalFormatted = currentTotal.toLocaleString('en-US');
        if (booksLength === 0 && currentTotal > 0) resultsIndicator.innerHTML = `Page ${currentPage}. No results. (Total: <span class="font-bold">${totalFormatted}</span>)`;
        else resultsIndicator.innerHTML = `Results: <span class="font-bold text-gray-900">${startItem}-${endItem}</span> of <span class="font-bold text-gray-900">${totalFormatted}</span>`;
    }

    function renderPagination(totalPages, page) {
        if (totalPages <= 1) {
            paginationControls.style.display = "none";
            return;
        }
        paginationControls.style.display = "flex";
        paginationList.innerHTML = "";

        const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
            const li = document.createElement("li");
            const a = document.createElement("a");
            a.href = "#";
            a.setAttribute("data-page", String(pageNum));

            let baseClasses = `flex items-center justify-center min-w-[32px] h-9 text-sm font-medium transition-all duration-200`;

            if (type === "prev" || type === "next") {
                a.innerHTML = text;
                baseClasses += ` text-gray-700 hover:text-orange-600 px-3`;
                if (isDisabled)
                    baseClasses += ` opacity-50 cursor-not-allowed pointer-events-none`;
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

        paginationControls.className = `
                flex items-center justify-center bg-white border border-gray-200 
                    rounded-full shadow-md px-4 py-2 mt-6 w-fit mx-auto gap-3
                `;

        createPageLink("prev", `<i class="flex ph ph-caret-left text-lg gap-2s"></i> Previous`, page - 1, page === 1);

        const window = 2;
        let pagesToShow = new Set([1, totalPages, page]);
        for (let i = 1; i <= window; i++) {
            if (page - i > 0) pagesToShow.add(page - i);
            if (page + i <= totalPages) pagesToShow.add(page + i);
        }

        const sortedPages = [...pagesToShow].sort((a, b) => a - b);
        let lastPage = 0;
        for (const p of sortedPages) {
            if (p > lastPage + 1) createPageLink("ellipsis", "…", "...", true);
            createPageLink("number", p, p, false, p === page);
            lastPage = p;
        }

        createPageLink("next", `Next <i class="flex ph ph-caret-right text-lg gap-2"></i>`, page + 1, page === totalPages);
    }

    paginationList.addEventListener("click", (e) => {
        e.preventDefault();
        const target = e.target.closest("a[data-page]");
        if (!target) return;
        const pageStr = target.dataset.page;
        if (pageStr === "...") return;
        const page = parseInt(pageStr, 10);
        if (!isNaN(page) && page !== currentPage) loadBooks(page);
    });


    async function loadAvailableCount() {
        try {
            const r = await fetch("/libsys/public/student/bookCatalog/availableCount");
            if (!r.ok) throw Error();
            const d = await r.json();
            const el = document.getElementById("availableCount");
            if (el) {
                while (el.firstChild) el.removeChild(el.firstChild);
                const i = document.createElement('i');
                i.className = 'ph ph-check-circle mr-1';
                el.appendChild(i);
                el.appendChild(document.createTextNode(` Available: ${d.available || 0}`));
            }
        } catch (e) {
            console.error("Err count:", e);
            const el = document.getElementById("availableCount");
            if (el) el.textContent = 'Available: Error';
        }
    }

    function renderBooks(books) {
        grid.innerHTML = '';
        if (!books || books.length === 0) return;
        books.forEach(book => {
            const card = document.createElement("div");
            card.className = "book-card relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[230px] cursor-pointer";
            try {
                card.dataset.book = JSON.stringify(book);
            } catch (e) {
                console.error("Strf err:", book, e);
                return;
            }
            const imgWrap = document.createElement("div");
            imgWrap.className = "w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden";
            const coverUrl = book.cover || null;
            if (coverUrl) {
                const img = document.createElement("img");
                img.src = coverUrl;
                img.alt = book.title || 'Cover';
                img.loading = 'lazy';
                img.className = "h-full w-auto object-contain group-hover:scale-105 transition duration-300";
                img.onerror = () => {
                    imgWrap.innerHTML = `<i class="ph ph-img-brkn text-5xl text-gray-300"></i>`;
                };
                imgWrap.appendChild(img);
            } else {
                imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
            }
            const statusBadge = document.createElement("span");
            const isAvailable = book.availability === "available";
            statusBadge.className = `absolute top-2 left-2 ${isAvailable ? "bg-green-500" : "bg-orange-500"} text-white text-xs px-2 py-1 rounded-full shadow`;
            statusBadge.textContent = isAvailable ? "Available" : "Borrowed";
            const info = document.createElement("div");
            info.className = "p-2 group-hover:bg-gray-100 transition";
            const titleText = book.title || 'Untitled';
            const authorText = book.author || 'Unknown';
            const subjectText = book.subject || '';
            info.innerHTML = ` <h4 class="text-xs font-semibold mb-0.5 truncate w-full group-hover:text-[var(--color-primary)]" title="${titleText}">${titleText}</h4> <p class="text-[10px] text-gray-500 truncate w-full" title="${authorText}">by ${authorText}</p> <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5 truncate w-full" title="${subjectText}"> ${subjectText} </p> `;
            card.appendChild(imgWrap);
            card.appendChild(statusBadge);
            card.appendChild(info);
            if (cart?.some(c => c.book_id == book.book_id)) {
                const badge = document.createElement("span");
                badge.className = "absolute bottom-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full shadow";
                badge.textContent = "In Cart";
                card.appendChild(badge);
            }
            grid.appendChild(card);
        });
    }

    function openModal(book) {
        if (!book || typeof book !== 'object') {
            console.error("Invalid book data");
            return;
        }
        if (!modal || !modalContent || !closeModalBtn || !modalImg || !modalTitle || !modalAuthor || !modalCallNumber || !modalAccessionNumber || !modalIsbn || !modalSubject || !modalPlace || !modalPublisher || !modalYear || !modalEdition || !modalSupplementary || !modalStatus || !addToCartBtn) {
            console.error("Modal elements missing!");
            return;
        }
        addToCartBtn.dataset.id = book.book_id || '';
        const coverUrl = book.cover || null;
        if (coverUrl) {
            modalImg.src = coverUrl;
            modalImg.alt = book.title || 'Cover';
            modalImg.classList.remove("hidden");
        } else {
            modalImg.classList.add("hidden");
            modalImg.src = '';
            modalImg.alt = '';
        }
        modalTitle.textContent = book.title || 'No Title';
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
        modalDescription.textContent = book.description || "No description.";
        const availabilityText = (book.availability || "unknown").toUpperCase();
        modalStatus.textContent = availabilityText;
        modalStatus.className = `font-semibold text-xs ${availabilityText === "AVAILABLE" ? "text-green-600" : "text-orange-600"}`;
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

    if (grid) grid.addEventListener("click", e => {
        const c = e.target.closest(".book-card");
        if (!c) return;
        try {
            const b = JSON.parse(c.dataset.book);
            openModal(b);
        } catch (p) {
            console.error("Parse err:", p);
        }
    });
    if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
    if (modal) modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener("keydown", e => {
        if (e.key === "Escape" && modal && !modal.classList.contains("hidden")) closeModal();
    });

    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener("input", e => {
            searchValue = e.target.value.trim();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                try {
                    sessionStorage.removeItem('bookCatalogPage');
                } catch (e) { }
                loadBooks(1);
            }, 500);
        });
    } else {
        console.error("Search input missing");
    }

    loadAvailableCount();
    loadCart().then(() => {
        loadBooks(currentPage);
    });
});