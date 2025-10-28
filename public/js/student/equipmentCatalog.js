
let currentPage = 1;
let currentSearch = '';
let currentStatus = 'Available'; // Default as per user request
let debounceTimer;

// This function will be called from the inline 'onclick' attribute in the HTML
function selectStatus(element, status) {
    const statusDropdownValue = document.getElementById('statusDropdownValue');
    const statusDropdownMenu = document.getElementById('statusDropdownMenu');
    
    currentStatus = status;
    currentPage = 1;
    statusDropdownValue.textContent = status;
    
    // Optional: Highlight selected item
    document.querySelectorAll('.status-item').forEach(item => item.classList.remove('font-bold'));
    element.classList.add('font-bold');
    
    statusDropdownMenu.classList.add('hidden');
    
    fetchAndRenderEquipment();
}

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const statusDropdownBtn = document.getElementById('statusDropdownBtn');
    const statusDropdownMenu = document.getElementById('statusDropdownMenu');
    const modal = document.getElementById('equipmentModal');
    const modalContent = document.getElementById('equipmentModalContent');
    const closeModalBtn = document.getElementById('closeModal');
    const addToCartBtn = document.getElementById('addToCartBtn');

    // Make selectStatus globally available
    window.selectStatus = selectStatus;

    // Initial Fetch
    fetchAndRenderEquipment();
    updateCartCount(); // Initial cart count

    // Event Listeners
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentSearch = searchInput.value;
            currentPage = 1;
            fetchAndRenderEquipment();
        }, 300);
    });

    statusDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        statusDropdownMenu.classList.toggle('hidden');
    });

    closeModalBtn.addEventListener('click', closeModal);

    // Close dropdown/modal when clicking outside
    document.addEventListener('click', (e) => {
        if (!statusDropdownBtn.contains(e.target)) {
            statusDropdownMenu.classList.add('hidden');
        }
        if (modal.classList.contains('flex') && !modalContent.contains(e.target) && e.target !== modal) {
             closeModal();
        }
    });
    
    addToCartBtn.addEventListener('click', () => {
        const equipmentId = addToCartBtn.dataset.id;
        if (equipmentId) {
            addToCart(equipmentId);
        }
    });
});

function fetchAndRenderEquipment() {
    const equipmentGrid = document.getElementById('equipmentGrid');
    const noEquipmentFound = document.getElementById('noEquipmentFound');
    const loadingSkeletons = document.getElementById('loadingSkeletons');
    const resultsIndicator = document.getElementById('resultsIndicator');
    
    showLoading(true);

    // This is a mock implementation. In a real application, you would fetch from a server.
    // The endpoint would be something like `/libsys/api/student/equipment?page=${currentPage}&search=${currentSearch}&status=${currentStatus}`
    mockApiCall(currentPage, currentSearch, currentStatus)
        .then(data => {
            showLoading(false);
            
            // Render equipment cards
            equipmentGrid.innerHTML = '';
            if (data.equipments.length === 0) {
                noEquipmentFound.classList.remove('hidden');
            } else {
                noEquipmentFound.classList.add('hidden');
                data.equipments.forEach(equipment => {
                    const card = createEquipmentCard(equipment);
                    equipmentGrid.appendChild(card);
                });
            }

            // Update pagination
            updatePagination(data.pagination.current_page, data.pagination.total_pages);

            // Update indicators
            updateIndicators(data.indicators);
            updateAvailableCount(data.available_count);
        })
        .catch(error => {
            console.error("Failed to fetch equipment:", error);
            showLoading(false);
            equipmentGrid.innerHTML = '';
            noEquipmentFound.classList.remove('hidden');
            resultsIndicator.textContent = 'Error loading equipment.';
        });
}

function createEquipmentCard(equipment) {
    const card = document.createElement('div');
    card.className = 'bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden border border-[var(--color-border)] cursor-pointer hover:shadow-lg transition-shadow duration-200 group';
    
    // Using a generic icon as placeholder for equipment image
    const icon = equipment.type === 'Computer' ? 'ph-desktop' : 'ph-game-controller';

    card.innerHTML = `
        <div class="w-full aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">
             <i class="ph ${icon} text-5xl text-gray-400 group-hover:scale-110 transition-transform"></i>
        </div>
        <div class="p-3">
            <h3 class="font-semibold text-sm text-gray-800 truncate" title="${equipment.name}">${equipment.name}</h3>
            <p class="text-xs text-gray-500">${equipment.type}</p>
        </div>
    `;
    card.addEventListener('click', () => openModal(equipment));
    return card;
}

function updatePagination(current_page, total_pages) {
    const paginationList = document.getElementById('paginationList');
    paginationList.innerHTML = '';
    if (total_pages <= 1) return;

    const createLink = (page, text, enabled = true, active = false) => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = '#';
        a.innerHTML = text;
        a.className = `flex items-center justify-center px-4 h-10 leading-tight border ${
            active 
                ? 'text-white bg-[var(--color-primary)] border-[var(--color-primary)]' 
                : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700'
        } ${!enabled ? 'opacity-50 cursor-not-allowed' : ''}`;

        if (enabled && !active) {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = page;
                fetchAndRenderEquipment();
            });
        }
        li.appendChild(a);
        return li;
    };

    paginationList.appendChild(createLink(current_page - 1, '&laquo;', current_page > 1));
    for (let i = 1; i <= total_pages; i++) {
        paginationList.appendChild(createLink(i, i, true, i === current_page));
    }
    paginationList.appendChild(createLink(current_page + 1, '&raquo;', current_page < total_pages));
}

function updateIndicators({ total_results, from, to }) {
    const resultsIndicator = document.getElementById('resultsIndicator');
    if (total_results === 0) {
        resultsIndicator.textContent = 'No equipment found.';
    } else {
        resultsIndicator.textContent = `Showing ${from}-${to} of ${total_results} results`;
    }
}

function updateAvailableCount(count) {
    const availableCountEl = document.getElementById('availableCount');
    availableCountEl.innerHTML = `<i class="ph ph-check-circle"></i> Available: ${count}`;
}

function showLoading(isLoading) {
    const loadingSkeletons = document.getElementById('loadingSkeletons');
    const equipmentGrid = document.getElementById('equipmentGrid');
    const noEquipmentFound = document.getElementById('noEquipmentFound');
    if (isLoading) {
        equipmentGrid.innerHTML = '';
        noEquipmentFound.classList.add('hidden');
        loadingSkeletons.classList.remove('hidden');
    } else {
        loadingSkeletons.classList.add('hidden');
    }
}

function openModal(equipment) {
    const modal = document.getElementById('equipmentModal');
    const modalContent = document.getElementById('equipmentModalContent');
    const addToCartBtn = document.getElementById('addToCartBtn');

    document.getElementById('modalName').textContent = equipment.name;
    const statusEl = document.getElementById('modalStatus');
    statusEl.textContent = equipment.status;
    statusEl.className = `font-semibold text-xs px-2 py-1 rounded-full ${
        equipment.status === 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
    }`;

    document.getElementById('infoName').textContent = equipment.name;
    document.getElementById('infoType').textContent = equipment.type;
    document.getElementById('infoSerial').textContent = equipment.serial;
    document.getElementById('infoCategory').textContent = equipment.category;
    document.getElementById('modalNote').textContent = equipment.note;

    addToCartBtn.dataset.id = equipment.id;
    addToCartBtn.disabled = equipment.status !== 'Available';
    if (addToCartBtn.disabled) {
        addToCartBtn.innerHTML = '<span><i class="ph ph-x-circle"></i></span> Not Available';
    } else {
        addToCartBtn.innerHTML = '<span><i class="ph ph-shopping-cart-simple"></i></span> Add to Cart';
    }

    modal.classList.remove('hidden');
    modal.classList.remove('opacity-0');
    modalContent.classList.remove('scale-95');
}

function closeModal() {
    const modal = document.getElementById('equipmentModal');
    const modalContent = document.getElementById('equipmentModalContent');
    modal.classList.add('opacity-0');
    modalContent.classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

async function addToCart(equipmentId) {
    const addToCartBtn = document.getElementById('addToCartBtn');
    console.log(`Adding equipment ${equipmentId} to cart.`);
    addToCartBtn.disabled = true;
    addToCartBtn.innerHTML = '<span><i class="ph ph-spinner animate-spin"></i></span> Adding...';

    // Mock API call for adding to cart
    await new Promise(resolve => setTimeout(resolve, 800)); 
    
    console.log('Added to cart!');
    addToCartBtn.innerHTML = '<span><i class="ph ph-check"></i></span> Added!';
    
    // After a delay, revert button to allow adding another item or re-adding
    setTimeout(() => {
        addToCartBtn.disabled = false; // Or keep it disabled if item is now in cart
        addToCartBtn.innerHTML = '<span><i class="ph ph-shopping-cart-simple"></i></span> Add to Cart';
        closeModal();
    }, 1200);

    updateCartCount();
}

async function updateCartCount() {
    const cartCountEl = document.getElementById('cart-count');
    // Mock API call for cart count
    const currentCountText = cartCountEl.innerText;
    const currentCount = parseInt(currentCountText.match(/\d+/)[0] || '0');
    // This is a fake update. A real app would fetch the count.
    if (document.querySelector('#addToCartBtn[disabled]')) { // only increment if we just added something
        const newCount = currentCount + 1;
        cartCountEl.innerHTML = `<i class="ph ph-shopping-cart text-xs"></i> ${newCount} total items`;
    }
}

// --- MOCK API ---
function mockApiCall(page, search, status) {
    return new Promise(resolve => {
        setTimeout(() => {
            const allEquipment = [
                { id: 1, name: 'Desktop 1', type: 'Computer', serial: 'DESK-001', category: 'Desktop', note: 'For educational use only.', status: 'Available' },
                { id: 2, name: 'Projector A', type: 'AV Equipment', serial: 'PROJ-A01', category: 'Projector', note: 'Includes HDMI cable and remote.', status: 'Available' },
                { id: 3, name: 'Settlers of Catan', type: 'Board Game', serial: 'BG-SOC-1', category: 'Strategy', note: 'Missing one sheep resource card. Handle with care.', status: 'Borrowed' },
                { id: 4, name: 'Laptop 5', type: 'Computer', serial: 'LAP-005', category: 'Laptop', note: 'Charger included. Return to front desk.', status: 'Available' },
                { id: 5, name: 'Chess Set (Wooden)', type: 'Board Game', serial: 'BG-CHS-3', category: 'Classic', note: 'High quality wooden pieces. Please ensure all pieces are returned.', status: 'Available' },
                { id: 6, name: 'Desktop 2', type: 'Computer', serial: 'DESK-002', category: 'Desktop', note: 'For educational use only.', status: 'Borrowed' },
                { id: 7, name: 'Ticket to Ride', type: 'Board Game', serial: 'BG-TTR-2', category: 'Strategy', note: 'Fun for all ages.', status: 'Available' },
                { id: 8, name: 'MacBook Pro 13"', type: 'Computer', serial: 'MAC-001', category: 'Laptop', note: 'For in-library use only.', status: 'Borrowed' },
                { id: 9, name: 'Raspberry Pi 4 Kit', type: 'Computer', serial: 'RPI-004', category: 'DIY Electronics', note: 'Includes power supply, case, and SD card.', status: 'Available' },
                { id: 10, name: 'Dungeons & Dragons Starter Set', type: 'Board Game', serial: 'BG-DND-1', category: 'RPG', note: 'Embark on an adventure!', status: 'Available' },
                { id: 11, name: 'VR Headset', type: 'AV Equipment', serial: 'VR-001', category: 'Virtual Reality', note: 'Requires booking in advance.', status: 'Borrowed' },
                { id: 12, name: 'Arduino Uno Kit', type: 'Computer', serial: 'ARD-002', category: 'DIY Electronics', note: 'Great for learning programming and electronics.', status: 'Available' },
                { id: 13, name: 'Desktop 3', type: 'Computer', serial: 'DESK-003', category: 'Desktop', note: 'For educational use only.', status: 'Available' },
            ];

            let filtered = allEquipment;
            if (status !== 'All Status') { // The user requested only two options, but a filter usually has an "all" option. I'll handle it.
                filtered = filtered.filter(e => e.status === status);
            }
            if (search) {
                const lowercasedSearch = search.toLowerCase();
                filtered = filtered.filter(e => 
                    e.name.toLowerCase().includes(lowercasedSearch) || 
                    e.type.toLowerCase().includes(lowercasedSearch) ||
                    e.category.toLowerCase().includes(lowercasedSearch)
                );
            }

            const total_results = filtered.length;
            const items_per_page = 12;
            const total_pages = Math.ceil(total_results / items_per_page);
            const start = (page - 1) * items_per_page;
            const end = start + items_per_page;
            const paginated = filtered.slice(start, end);

            resolve({
                equipments: paginated,
                pagination: {
                    current_page: page,
                    total_pages: total_pages,
                },
                indicators: {
                    total_results: total_results,
                    from: total_results > 0 ? start + 1 : 0,
                    to: Math.min(end, total_results),
                },
                available_count: allEquipment.filter(e => e.status === 'Available').length
            });
        }, 500);
    });
}
