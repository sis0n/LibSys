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
            <p id="summary-text" class="text-[var(--color-muted-foreground)] text-[var(--font-size-sm)]">
                0 book(s) and 0 equipment item(s) selected for borrowing
            </p>
            <div class="mt-4 flex items-center gap-2">
                <button
                    class="flex-1 bg-[var(--color-primary)] text-white font-semibold rounded-[var(--radius-lg)] py-2 bg-orange-600 transition hover:bg-orange-500">
                    Checkout All Items & Generate QR
                </button>
                <button onclick="clearCart()"
                    class="px-4 py-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] text-[var(--color-orange-700)] font-medium hover:bg-[var(--color-orange-100)] transition">
                    Clear Cart
                </button>
            </div>
        </div>

        <!-- Selected Items -->
        <h3 class="mt-6 font-semibold text-[var(--color-foreground)]">Selected Items</h3>
        <div id="selected-items"></div>
    </div>

    <!-- Simulation Button pang testing lang pwede mo link to sa add to cart after nun tanggalin mo na to.-->
    <button onclick="simulateAdd()"
        class="mt-4 bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">
        Simulate Add to Cart
    </button>

    <script>
    //sample data for testing
    const sampleBooks = [{
        id: 1,
        title: "Columbia Encylopedia",
        author: "Raimes, James",
        accessionNumber: "978-0-123456-78-9",
        subject: "Encyclopedias",
        callnumber: "AE 5 R1329 1975 v.5",
        icon: "ph-book",
        type: "book"
    }, ];

    let cart = [];

    // Simulation: pang display nung design ikaw nabahala mag link sa add to cart button sa catalog
    function simulateAdd() {
        cart = [...sampleBooks];
        renderCart();
    }
    // pang clear all sa cart
    function clearCart() {
        cart = [];
        renderCart();
    }
    // pang remove individual item sa cart
    function removeFromCart(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function renderCart() {
        const emptyState = document.getElementById("empty-state");
        const cartItems = document.getElementById("cart-items");
        const cartCount = document.getElementById("cart-count");
        const summaryText = document.getElementById("summary-text");
        const itemsContainer = document.getElementById("selected-items");

        if (cart.length > 0) {
            emptyState.classList.add("hidden");
            cartItems.classList.remove("hidden");

            // count books vs equipment
            // wag mo muna pansin to para sa equipment to magagamit sa susunod
            let bookCount = cart.filter(i => i.type === "book").length;
            let equipmentCount = cart.filter(i => i.type === "equipment").length;

            cartCount.innerHTML = `<i class="ph ph-shopping-cart text-xs"></i> ${cart.length} total item(s)`;
            summaryText.innerText =
                `${bookCount} book(s) and ${equipmentCount} equipment item(s) selected for borrowing`;

            itemsContainer.innerHTML = "";
            cart.forEach((item, index) => {
                itemsContainer.innerHTML += `
                <div class="mt-4 border rounded-[var(--radius-lg)] border-[var(--color-border)] 
                            bg-white shadow-sm flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 flex items-center justify-center">
                            <i class="ph ${item.icon} text-5xl text-amber-700"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold">${item.title}</h4>
                            ${item.author ? `<p class="text-sm text-gray-600">by ${item.author}</p>` : ""}
                            ${(item.accessionNumber || item.subject || item.callnumber) ? `
                            <div class="flex flex-wrap gap-6 text-sm text-gray-600 mt-1">
                                ${item.accessionNumber ? `<span><span class="font-semibold">Accession Number:</span> ${item.accessionNumber}</span>` : ""}
                                ${item.subject ? `<span><span class="font-semibold">Subject:</span> ${item.subject}</span>` : ""}
                                ${item.callnumber ? `<span><span class="font-semibold">Call Number:</span> ${item.callnumber}</span>` : ""}
                            </div>
                        ` : ""}
                        </div>
                    </div>
                    <button onclick="removeFromCart(${index})" 
                            class="text-xl text-[var(--color-foreground)] hover:text-[var(--color-orange-700)] transition">
                        <i class="ph ph-trash"></i>
                    </button>
                </div>`;
            });

        } else {
            emptyState.classList.remove("hidden");
            cartItems.classList.add("hidden");
            cartCount.innerHTML = `<i class="ph ph-shopping-cart text-xs"></i> 0 total items`;
        }
    }
    </script>
</body>