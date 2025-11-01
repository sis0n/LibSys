let cart = [];
let checkedMap = {};

async function loadCart() {
    try {
        const res = await fetch("api/faculty/cart/json");
        if (!res.ok) throw new Error("Failed to load cart");
        cart = await res.json();
        renderCart();
    } catch (err) {
        console.error("Error loading cart:", err);
    }
}

async function checkoutCart() {
    console.log("Checkout button clicked");
    const selectedIds = Object.keys(checkedMap).filter(id => checkedMap[id]);
    console.log("Selected IDs for checkout:", selectedIds);

    if (selectedIds.length === 0) {
        alert("Please select at least one item to checkout.");
        return;
    }

    try {
        const res = await fetch("api/faculty/cart/checkout", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ cart_ids: selectedIds })
        });

        const text = await res.text();
        console.log("Raw response:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            console.warn("Response was not JSON, maybe redirected or HTML page.");
            document.open();
            document.write(text);
            document.close();
            return;
        }

        console.log("Parsed data:", data);

        if (data.success) {
            alert("Checkout successful! You can now view your QR Borrowing Ticket in the Borrowing Ticket page.");
            checkedMap = {};
            loadCart();
        } else {
            alert(data.message || "Checkout failed");
        }
    } catch (err) {
        console.error("Checkout error:", err);
        alert("Something went wrong. Please try again.");
    }
}



async function clearCart() {
    try {
        const res = await fetch("api/faculty/cart/clear", {
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
        const res = await fetch(`api/faculty/cart/remove/${cartId}`, {
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

// Inalis ang saveCheckedMap() function

function renderCart() {
    const emptyState = document.getElementById("empty-state");
    const cartItemsDiv = document.getElementById("cart-items");
    const cartCount = document.getElementById("cart-count");
    const itemsContainer = document.getElementById("selected-items");
    const selectAllCheckbox = document.getElementById("select-all");
    const selectedItemsSection = document.getElementById("selected-items-section");

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

            const checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.className = "w-4 h-4 accent-orange-600 cursor-pointer rounded-full";
            checkbox.dataset.type = item.type;
            checkbox.dataset.id = item.cart_id;

            if (checkedMap[item.cart_id]) {
                checkbox.checked = true;
                toggleHighlight(itemDiv, true);
            }

            const iconDiv = document.createElement("div");
            iconDiv.className = "flex-shrink-0 w-20 h-20 flex items-center justify-center";
            const iconEl = document.createElement("i");
            iconEl.className =
                `ph ${item.icon || 'ph-book'} text-5xl text-amber-700 w-10 h-10 leading-[2rem] text-center`;
            iconDiv.appendChild(iconEl);

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

            leftDiv.appendChild(checkbox);
            leftDiv.appendChild(iconDiv);
            leftDiv.appendChild(infoDiv);

            const removeBtn = document.createElement("button");
            removeBtn.className = "text-2xl text-gray-800 hover:text-orange-700 transition ml-4";
            const removeIcon = document.createElement("i");
            removeIcon.className = "ph ph-trash";
            removeBtn.appendChild(removeIcon);
            removeBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                delete checkedMap[item.cart_id];
                removeFromCart(item.cart_id);
            });

            itemDiv.addEventListener("click", () => {
                checkbox.checked = !checkbox.checked;
                checkedMap[item.cart_id] = checkbox.checked;
                toggleHighlight(itemDiv, checkbox.checked);
                updateSummary();
                syncSelectAll();
            });

            checkbox.addEventListener("click", (e) => {
                e.stopPropagation();
                checkedMap[item.cart_id] = checkbox.checked;
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

    if (selectAllCheckbox) {
        selectAllCheckbox.onchange = () => {
            const allItems = document.querySelectorAll("#selected-items input[type='checkbox']");
            allItems.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
                checkedMap[cb.dataset.id] = cb.checked;
                toggleHighlight(cb.closest("div.mt-4"), cb.checked);
            });
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
    document.getElementById("checkout-btn").addEventListener("click", checkoutCart);
});