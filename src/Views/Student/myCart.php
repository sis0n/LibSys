<body class="min-h-screen p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-1">My Cart</h2>
            <p class="text-gray-700">Review and checkout your selected items.</p>
        </div>
        <span id="cart-count"
            class="px-[var(--spacing-3)] py-[var(--spacing-1)] rounded-md border text-[var(--font-size-sm)] text-[var(--color-foreground)] border-[var(--color-border)] bg-white shadow-sm flex items-center gap-[var(--spacing-1)]">
            <i class="ph ph-shopping-cart text-xs"></i>
            0 total items
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
            <!-- hardcoded pa -->
            <p class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                0 book(s) and 1 equipment item(s) selected for borrowing
            </p>
            <div class="mt-4 flex items-center gap-2">
                <button
                    class="flex-1 bg-[var(--color-primary)] text-white font-semibold rounded-[var(--radius-lg)] py-2 bg-orange-600 transition hover:bg-orange-500">
                    Checkout All Items & Generate QR
                </button>
                <button
                    class="px-4 py-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] text-[var(--color-orange-700)] font-medium hover:bg-[var(--color-orange-100)] transition">
                    Clear Cart
                </button>
            </div>
        </div>

        <!-- Selected Items -->
        <h3 class="mt-6 font-semibold text-[var(--color-foreground)]">Selected Items</h3>
        <!-- sample lang ng book Item -->
        <div
            class="mt-4 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm flex items-center justify-between p-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 flex items-center justify-center">
                    <i class="ph ph-book text-5xl text-amber-700"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-[var(--color-foreground)]">
                        Introduction to Computer Science
                    </h4>
                    <p class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                        by John Smith
                    </p>
                    <p class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                        ISBN: 978-0-123456-78-9
                    </p>
                    <p class="text-[var(--color-foreground)] text-[var(--font-size-sm)]">
                        <span class="font-semibold">Category:</span> Computer Science
                    </p>
                    <p class="text-[var(--color-foreground)] text-[var(--font-size-sm)]">
                        <span class="font-semibold">Location:</span> Section A, Shelf 2
                    </p>
                </div>
            </div>
            <button class="text-[var(--color-foreground)] hover:text-[var(--color-orange-700)] transition">
                <i class="ph ph-trash"></i>
            </button>
        </div>
        <!-- sample lang ng equipment Item -->
        <div
            class="mt-4 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm flex items-center justify-between p-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 flex items-center justify-center">
                    <i class="ph ph-desktop-tower text-5xl text-amber-700"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-[var(--color-foreground)]">Desktop 1</h4>
                    <p class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                        Desktop
                    </p>
                    <p class="flex items-center text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                        <i class="ph ph-map-pin mr-1"></i>Station
                    </p>
                    <p class="text-[var(--color-foreground)] text-[var(--font-size-sm)]">
                        <span class="font-semibold">Description:</span> Acer Aspire TC-895-UA92 Desktop
                    </p>
                </div>
            </div>
            <!-- Delete button -->
            <button class="text-[var(--color-foreground)] hover:text-[var(--color-orange-700)] transition">
                <i class="ph ph-trash"></i>
            </button>
        </div>
        <script>
        function toggleCart(hasItems) {
            const emptyState = document.getElementById("empty-state");
            const cartItems = document.getElementById("cart-items");
            const cartCount = document.getElementById("cart-count");

            if (hasItems) {
                emptyState.classList.add("hidden");
                cartItems.classList.remove("hidden");
                cartCount.innerHTML = `<i class="ph ph-shopping-cart text-xs"></i> 1 total item`;
            } else {
                emptyState.classList.remove("hidden");
                cartItems.classList.add("hidden");
                cartCount.innerHTML = `<i class="ph ph-shopping-cart text-xs"></i> 0 total items`;
            }
        }

        // Demo: switch to "with items" after 2s
        setTimeout(() => toggleCart(true), 2000);
        </script>
</body>