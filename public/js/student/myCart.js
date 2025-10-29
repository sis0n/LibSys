let cart = [];
let checkedMap = {};

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
async function checkoutCart() {
    console.log("Checkout button clicked");
    const selectedIds = Object.keys(checkedMap).filter(id => checkedMap[id]);
    console.log("Selected IDs for checkout:", selectedIds);

    // 🟠 No item selected
    if (selectedIds.length === 0) {
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
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600">
                            <i class="ph ph-warning text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-semibold text-orange-600">No Item Selected</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">Please select at least one item before checking out.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] backdrop-blur-sm shadow-[0_0_8px_#ffb34770]",
            },
        });
        return;
    }

    // 🟡 Confirmation Alert
    const confirmationResult = await Swal.fire({
        background: "transparent",
        html: `
            <div class="flex flex-col text-center">
                <div class="flex justify-center mb-3">
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                        <i class="ph ph-question text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-[17px] font-semibold text-orange-700">Confirm Checkout?</h3>
                <p class="text-[14px] text-gray-700 mt-1">
                    You are about to check out <span class="font-semibold">${selectedIds.length}</span> item(s). Proceed?
                </p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Yes, Checkout!",
        cancelButtonText: "Cancel",
        customClass: {
            popup: "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#ffb34770] !border-2 !border-orange-400",
            confirmButton: "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
            cancelButton: "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
        },
    });

    if (!confirmationResult.isConfirmed) {
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
                            <p class="text-[13px] text-gray-700 mt-0.5">Your checkout was cancelled.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
            },
        });
        return;
    }

    // 🔵 Loading
    Swal.fire({
        background: "transparent",
        html: `
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                <p class="text-gray-700 text-[14px]">Processing...<br><span class="text-sm text-gray-500">Do not close the page.</span></p>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
        },
    });

    // 🟢 Proceed with checkout logic
    try {
        const res = await fetch("/libsys/public/student/cart/checkout", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ cart_ids: selectedIds }),
        });

        const text = await res.text();
        Swal.close();

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            document.open();
            document.write(text);
            document.close();
            return;
        }

        if (data.success) {
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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                                <i class="ph ph-check-circle text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-semibold text-green-600">Checkout Successful!</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">
                                    You can now view your QR Borrowing Ticket on the Borrowing Ticket page.
                                </p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] backdrop-blur-sm shadow-[0_0_8px_#22c55e70]",
                },
            });

            checkedMap = {};
            loadCart();

        } else {
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
                                <h3 class="text-[15px] font-semibold text-red-600">Checkout Failed</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">${data.message || "Checkout failed due to an unknown error."}</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
        }
    } catch (err) {
        console.error("Checkout error:", err);
        Swal.close();
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
                            <h3 class="text-[15px] font-semibold text-red-600">Network Error</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">There was a connection issue. Please try again.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
            },
        });
    }
}




async function clearCart() {
    try {
        // 🟠 SweetAlert Confirmation
        const confirmation = await Swal.fire({
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

        if (!confirmation.isConfirmed) {
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
                    popup:
                        "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
            return;
        }

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

        const res = await fetch("/libsys/public/student/cart/clear", { method: "POST" });
        if (!res.ok) throw new Error("Failed to clear cart");
        cart = [];

        // Simulate loading delay (optional)
        await new Promise((resolve) => setTimeout(resolve, 800));

        await Swal.close();

        // 🟢 Success Toast
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
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                            <i class="ph ph-check-circle text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-semibold text-green-600">Cart Cleared!</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">All items have been successfully removed from your cart.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup:
                    "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
            },
        });

        renderCart();
    } catch (err) {
        console.error(err);

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
                            <h3 class="text-[15px] font-semibold text-red-600">Error</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">There was an issue clearing the cart.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup:
                    "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
            },
        });
    }
}


async function removeFromCart(cartId) {
    try {
        // 🟠 SweetAlert Confirmation
        const confirmation = await Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col text-center">
                    <div class="flex justify-center mb-3">
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                            <i class="ph ph-trash text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-[17px] font-semibold text-orange-700">Remove Item?</h3>
                    <p class="text-[14px] text-gray-700 mt-1">
                        Are you sure you want to remove this item from your cart?
                    </p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Yes, Remove!",
            cancelButtonText: "Cancel",
            customClass: {
                popup: "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
                confirmButton: "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
                cancelButton: "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
            },
        });

        if (!confirmation.isConfirmed) {
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
                                <p class="text-[13px] text-gray-700 mt-0.5">The item was not removed from the cart.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
            return;
        }

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

        const res = await fetch(`/libsys/public/student/cart/remove/${cartId}`, {
            method: "POST"
        });
        if (!res.ok) throw new Error("Failed to remove item");

        cart = cart.filter(item => item.cart_id !== cartId);

        // simulate slight delay
        await new Promise((resolve) => setTimeout(resolve, 800));
        await Swal.close();

        // 🟢 Success Toast
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
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                            <i class="ph ph-check-circle text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-semibold text-green-600">Item Removed!</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">The item has been successfully removed from your cart.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
            },
        });

        renderCart();

    } catch (err) {
        console.error(err);
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
                            <h3 class="text-[15px] font-semibold text-red-600">Error</h3>
                            <p class="text-[13px] text-gray-700 mt-0.5">There was an issue removing the item.</p>
                        </div>
                    </div>
                </div>
            `,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
            },
        });
    }
}


// Removed the saveCheckedMap() function

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
            syncSelectAll();
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