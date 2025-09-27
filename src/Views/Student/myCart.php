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

    <!-- Cart with Items -->
    <div id="cart-items" class="hidden">
        <!-- Checkout Summary -->
        <div class="mt-4 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm p-4">
            <h3 class="font-semibold text-[var(--color-foreground)] mb-1">Checkout Summary</h3>
            <p id="summary-text" class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                0 book(s) and 0 equipment item(s) selected for borrowing
            </p>
            <div class="mt-4 flex items-center gap-2">
                <button class="flex-1 bg-orange-600 text-white font-semibold rounded-[var(--radius-lg)] py-2 hover:bg-orange-500 transition">
                    Checkout All Items & Generate QR
                </button>
                <button id="clear-cart-btn"
                    class="px-4 py-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] 
                               text-[var(--color-orange-700)] font-medium hover:bg-[var(--color-orange-100)] transition">
                    Clear Cart
                </button>
            </div>
        </div>

        <!-- Selected Items -->
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
                const res = await fetch("/libsys/public/student/cart/clear", {
                    method: "POST"
                });
                if (!res.ok) throw new Error("Failed to clear cart");
                cart = [];
                alert("are u sure?");
                renderCart();
            } catch (err) {
                console.error(err);
            }
        }

        async function removeFromCart(cartId) {
            try {
                const res = await fetch(`/libsys/public/student/cart/remove/${cartId}`, {
                    method: "POST"
                });
                if (!res.ok) throw new Error("Failed to remove item");
                cart = cart.filter(item => item.cart_id !== cartId);
                alert("are u sure?");
                renderCart();
            } catch (err) {
                console.error(err);
            }
        }

        function renderCart() {
            const emptyState = document.getElementById("empty-state");
            const cartItemsDiv = document.getElementById("cart-items");
            const cartCount = document.getElementById("cart-count");
            const summaryText = document.getElementById("summary-text");
            const itemsContainer = document.getElementById("selected-items");

            while (itemsContainer.firstChild) {
                itemsContainer.removeChild(itemsContainer.firstChild);
            }

            if (cart.length > 0) {
                emptyState.classList.add("hidden");
                cartItemsDiv.classList.remove("hidden");

                const bookCount = cart.filter(i => i.type === "book").length;
                const equipmentCount = cart.filter(i => i.type === "equipment").length;

                cartCount.textContent = `${cart.length} total item(s)`;
                const cartIcon = document.createElement("i");
                cartIcon.className = "ph ph-shopping-cart text-xs";
                cartCount.insertBefore(cartIcon, cartCount.firstChild);

                summaryText.textContent = `${bookCount} book(s) and ${equipmentCount} equipment item(s) selected for borrowing`;

                cart.forEach(item => {
                    const itemDiv = document.createElement("div");
                    itemDiv.className = "mt-4 border rounded-lg border-gray-300 bg-white shadow-sm flex items-center justify-between p-4";

                    const leftDiv = document.createElement("div");
                    leftDiv.className = "flex items-center gap-4";

                    const iconDiv = document.createElement("div");
                    iconDiv.className = "w-20 h-20 flex items-center justify-center";
                    const iconEl = document.createElement("i");
                    iconEl.className = `ph ${item.icon || 'ph-book'} text-5xl text-amber-700`;
                    iconDiv.appendChild(iconEl);

                    const infoDiv = document.createElement("div");
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

                    if (item.accessionNumber || item.subject || item.callNumber) {
                        const infoWrap = document.createElement("div");
                        infoWrap.className = "flex flex-wrap gap-6 text-sm text-gray-600 mt-1";

                        if (item.accessionNumber) {
                            const span = document.createElement("span");
                            const strong = document.createElement("span");
                            strong.className = "font-semibold";
                            strong.textContent = "Accession Number: ";
                            span.appendChild(strong);
                            span.appendChild(document.createTextNode(item.accessionNumber));
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
                        if (item.callNumber) {
                            const span = document.createElement("span");
                            const strong = document.createElement("span");
                            strong.className = "font-semibold";
                            strong.textContent = "Call Number: ";
                            span.appendChild(strong);
                            span.appendChild(document.createTextNode(item.callNumber));
                            infoWrap.appendChild(span);
                        }

                        infoDiv.appendChild(infoWrap);
                    }

                    leftDiv.appendChild(iconDiv);
                    leftDiv.appendChild(infoDiv);

                    const removeBtn = document.createElement("button");
                    removeBtn.className = "text-xl text-gray-800 hover:text-orange-700 transition";
                    const removeIcon = document.createElement("i");
                    removeIcon.className = "ph ph-trash";
                    removeBtn.appendChild(removeIcon);
                    removeBtn.addEventListener("click", () => removeFromCart(item.cart_id));

                    itemDiv.appendChild(leftDiv);
                    itemDiv.appendChild(removeBtn);

                    itemsContainer.appendChild(itemDiv);
                });

            } else {
                emptyState.classList.remove("hidden");
                cartItemsDiv.classList.add("hidden");
                cartCount.textContent = "0 total items";
                const cartIcon = document.createElement("i");
                cartIcon.className = "ph ph-shopping-cart text-xs";
                cartCount.insertBefore(cartIcon, cartCount.firstChild);
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadCart();
            document.getElementById("clear-cart-btn").addEventListener("click", clearCart);
        });
    </script>

</body>