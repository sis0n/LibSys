<body class="min-h-screen p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-1">My Cart</h2>
            <p class="text-gray-700">Review and checkout your selected items.</p>
        </div>
        <span id="cart-count"
            class="px-[var(--spacing-3)] py-[var(--spacing-1)] rounded-md border text-[var(--font-size-sm)] 
                     text-[var(--color-foreground)] border-[var(--color-border)] bg-white shadow-sm flex items-center gap-[var(--spacing-1)]">
            <i class="ph ph-shopping-cart text-xs"></i> 0 total items
        </span>
    </div>

    <!-- Empty State -->
    <div id="empty-state"
        class="mt-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm flex flex-col items-center justify-center py-9">
        <i class="ph ph-shopping-cart text-6xl text-amber-700 mb-3"></i>
        <p class="text-[var(--color-foreground)] font-medium text-[var(--font-size-base)]">Your cart is empty</p>
        <p class="text-amber-700 text-[var(--font-size-sm)]">
            Add books or equipment from the catalog to get started.
        </p>
    </div>

    <div id="cart-items" class="hidden">
        <!-- Checkout Summary -->
        <div class="mt-4 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-[var(--color-foreground)]">Checkout Summary</h3>

                <label class="flex items-center gap-2 cursor-pointer text-sm text-[var(--color-muted-foreground)]">
                    <input type="checkbox" id="select-all" class="w-4 h-4 accent-orange-600 rounded cursor-pointer" />
                    Select All
                </label>
            </div>

            <p id="summary-text" class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                0 book(s) and 0 equipment item(s) selected for borrowing
            </p>

            <div class="mt-4 flex items-center gap-2">
                <button
                    class="flex-1 bg-orange-600 text-white font-semibold rounded-[var(--radius-lg)] py-2 hover:bg-orange-500 transition">
                    Check Out
                </button>
                <button id="clear-cart-btn" class="px-4 py-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] 
                           text-[var(--color-orange-700)] font-medium hover:bg-[var(--color-orange-100)] transition">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>
    <!-- Selected Items -->
    <div id="selected-items-section" class="hidden">
        <h3 class="mt-6 font-semibold text-[var(--color-foreground)]">Selected Items</h3>
        <div id="selected-items"></div>
    </div>


    <script>
    let cart = [];

    async function loadCart() {
        try {
            const res = await fetch("/libsys/public/student/cart/json");
            if (!res.ok) throw new Error("Failed to load cart");
            cart = await res.json();
            renderCart();
        } catch (err) {
            console.error("Error loading cart:", err);
        }
    }

    async function clearCart() {
        try {
            const confirm = await Swal.fire({
                title: "Are you sure?",
                text: "This will remove all items from your cart.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ea580c",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, clear it!",
                cancelButtonText: "Cancel"
            });

            if (!confirm.isConfirmed) return;

            const res = await fetch("/libsys/public/student/cart/clear", {
                method: "POST"
            });

            // allow 200 or 204 (success without body)
            if (![200, 204].includes(res.status)) {
                throw new Error(`Failed to clear cart, status: ${res.status}`);
            }

            cart = [];
            renderCart();

            Swal.fire({
                toast: true,
                position: "bottom-end",
                icon: "success",
                title: "Cart Cleared",
                text: "All items have been removed from your cart.",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        } catch (err) {
            console.error("Error clearing cart:", err);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong while clearing your cart. Please try again.",
                confirmButtonColor: "#ea580c",
                timer: 3000,
                timerProgressBar: true,
            });
        }
    }

    async function removeFromCart(cartId) {
        try {
            const confirm = await Swal.fire({
                title: "Are you sure?",
                text: "This item will be removed from your cart.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ea580c",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "Cancel"
            });

            if (!confirm.isConfirmed) return;

            const res = await fetch(`/libsys/public/student/cart/remove/${cartId}`, {
                method: "POST"
            });

            console.log("Remove status:", res.status); // debug

            // Instead of throwing error agad, check muna
            if (res.status !== 200 && res.status !== 204) {
                throw new Error("Failed to remove item");
            }

            cart = cart.filter(item => item.cart_id !== cartId);
            renderCart();

            Swal.fire({
                toast: true,
                position: "bottom-end",
                icon: "success",
                title: "Item Removed",
                text: "The item has been successfully removed from your cart.",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        } catch (err) {
            console.error("Error removing item:", err);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong while removing the item. Please try again.",
                confirmButtonColor: "#ea580c",
                timer: 3000,
                timerProgressBar: true,
            });
        }
    }



    let checkedMap = JSON.parse(localStorage.getItem("checkedMap")) || {};

    function saveCheckedMap() {
        localStorage.setItem("checkedMap", JSON.stringify(checkedMap));
    }

    function renderCart() {
        const emptyState = document.getElementById("empty-state");
        const cartItemsDiv = document.getElementById("cart-items");
        const cartCount = document.getElementById("cart-count");
        const itemsContainer = document.getElementById("selected-items");
        const selectAllCheckbox = document.getElementById("select-all");
        const selectedItemsSection = document.getElementById("selected-items-section");

        // Clear container
        while (itemsContainer.firstChild) {
            itemsContainer.removeChild(itemsContainer.firstChild);
        }

        if (cart.length > 0) {
            emptyState.classList.add("hidden");
            cartItemsDiv.classList.remove("hidden");
            selectedItemsSection.classList.remove("hidden");

            cartCount.textContent = `${cart.length} total item(s)`;
            const cartIcon = document.createElement("i");
            cartIcon.className = "ph ph-shopping-cart text-xs";
            cartCount.insertBefore(cartIcon, cartCount.firstChild);

            cart.forEach(item => {
                const itemDiv = document.createElement("div");
                itemDiv.className =
                    "mt-4 border rounded-lg border-gray-300 bg-white shadow-sm flex items-center justify-between p-4 transition cursor-pointer";

                const leftDiv = document.createElement("div");
                leftDiv.className = "flex items-center gap-3 w-full";

                // === Checkbox ===
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.className = "w-4 h-4 accent-orange-600 cursor-pointer rounded-full";
                checkbox.dataset.type = item.type;
                checkbox.dataset.id = item.cart_id;

                // Restore state from checkedMap
                if (checkedMap[item.cart_id]) {
                    checkbox.checked = true;
                    toggleHighlight(itemDiv, true);
                }

                // === Icon ===
                const iconDiv = document.createElement("div");
                iconDiv.className = "flex-shrink-0 w-20 h-20 flex items-center justify-center";
                const iconEl = document.createElement("i");
                iconEl.className =
                    `ph ${item.icon || 'ph-book'} text-5xl text-amber-700 w-10 h-10 leading-[2rem] text-center`;
                iconDiv.appendChild(iconEl);

                // === Info ===
                const infoDiv = document.createElement("div");
                infoDiv.className = "flex-1";

                const titleEl = document.createElement("h4");
                titleEl.className = "font-semibold";
                titleEl.textContent = item.title;
                infoDiv.appendChild(titleEl);

                if (item.author) {
                    const authorEl = document.createElement("p");
                    authorEl.className = "text-sm text-gray-600";
                    authorEl.textContent = `by ${item.author}`;
                    infoDiv.appendChild(authorEl);
                }

                if (item.accessionNumber || item.callNumber || item.subject) {
                    const infoWrap = document.createElement("div");
                    infoWrap.className = "flex flex-wrap gap-x-6 text-sm text-gray-600 mt-4";

                    if (item.accessionNumber) {
                        const span = document.createElement("span");
                        const strong = document.createElement("span");
                        strong.className = "font-semibold";
                        strong.textContent = "Accession Number: ";
                        span.appendChild(strong);
                        span.appendChild(document.createTextNode(item.accessionNumber));
                        infoWrap.appendChild(span);
                    }
                    if (item.callNumber) {
                        const span = document.createElement("span");
                        const strong = document.createElement("span");
                        strong.className = "font-semibold";
                        strong.textContent = "Call Number: ";
                        span.appendChild(strong);
                        span.appendChild(document.createTextNode(item.callNumber));
                        infoWrap.appendChild(span);
                    }
                    if (item.subject) {
                        const span = document.createElement("span");
                        const strong = document.createElement("span");
                        strong.className = "font-semibold";
                        strong.textContent = "Subject: ";
                        span.appendChild(strong);
                        span.appendChild(document.createTextNode(item.subject));
                        infoWrap.appendChild(span);
                    }

                    infoDiv.appendChild(infoWrap);
                }

                // === Assemble left side ===
                leftDiv.appendChild(checkbox);
                leftDiv.appendChild(iconDiv);
                leftDiv.appendChild(infoDiv);

                // === Remove Button ===
                const removeBtn = document.createElement("button");
                removeBtn.className = "text-2xl text-gray-800 hover:text-orange-700 transition ml-4";
                const removeIcon = document.createElement("i");
                removeIcon.className = "ph ph-trash";
                removeBtn.appendChild(removeIcon);
                removeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    delete checkedMap[item.cart_id];
                    saveCheckedMap();
                    removeFromCart(item.cart_id);
                });

                // === Card Click Toggle ===
                itemDiv.addEventListener("click", () => {
                    checkbox.checked = !checkbox.checked;
                    checkedMap[item.cart_id] = checkbox.checked;
                    saveCheckedMap();
                    toggleHighlight(itemDiv, checkbox.checked);
                    updateSummary();
                    syncSelectAll();
                });

                // === Checkbox Click ===
                checkbox.addEventListener("click", (e) => {
                    e.stopPropagation();
                    checkedMap[item.cart_id] = checkbox.checked;
                    saveCheckedMap();
                    toggleHighlight(itemDiv, checkbox.checked);
                    updateSummary();
                    syncSelectAll();
                });

                itemDiv.appendChild(leftDiv);
                itemDiv.appendChild(removeBtn);
                itemsContainer.appendChild(itemDiv);
            });

            updateSummary();
            syncSelectAll();

        } else {
            emptyState.classList.remove("hidden");
            cartItemsDiv.classList.add("hidden");
            selectedItemsSection.classList.add("hidden");
            cartCount.textContent = "0 total items";
            const cartIcon = document.createElement("i");
            cartIcon.className = "ph ph-shopping-cart text-xs";
            cartCount.insertBefore(cartIcon, cartCount.firstChild);
        }

        // === Select All handler ===
        if (selectAllCheckbox) {
            selectAllCheckbox.onchange = () => {
                const allItems = document.querySelectorAll("#selected-items input[type='checkbox']");
                allItems.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                    checkedMap[cb.dataset.id] = cb.checked;
                    toggleHighlight(cb.closest("div.mt-4"), cb.checked);
                });
                saveCheckedMap();
                updateSummary();
            };
        }
    }

    function toggleHighlight(itemDiv, checked) {
        itemDiv.classList.toggle("bg-orange-100", checked);
        itemDiv.classList.toggle("border-orange-500", checked);
    }
    

    function updateSummary() {
        const summaryText = document.getElementById("summary-text");
        const allItems = document.querySelectorAll("#selected-items input[type='checkbox']");
        let books = 0,
            equipment = 0;

        allItems.forEach(cb => {
            if (cb.checked) {
                if (cb.dataset.type === "book") books++;
                if (cb.dataset.type === "equipment") equipment++;
            }
        });

        summaryText.textContent =
            `${books} book(s) and ${equipment} equipment item(s) selected for borrowing`;
    }

    function syncSelectAll() {
        const selectAllCheckbox = document.getElementById("select-all");
        const allItems = document.querySelectorAll("#selected-items input[type='checkbox']");
        if (allItems.length > 0) {
            selectAllCheckbox.checked = [...allItems].every(cb => cb.checked);
        } else {
            selectAllCheckbox.checked = false;
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        loadCart();
        document.getElementById("clear-cart-btn").addEventListener("click", clearCart);
    });
    </script>

</body>