document.addEventListener('DOMContentLoaded', () => {
    // --- Generic Dropdown Handler ---
    const setupDropdown = (btnId, menuId, valueId, inputId, itemClass, callback) => {
        const dropdownBtn = document.getElementById(btnId);
        const dropdownMenu = document.getElementById(menuId);
        const dropdownValue = document.getElementById(valueId);
        const hiddenInput = document.getElementById(inputId);

        if (!dropdownBtn || !dropdownMenu || !dropdownValue || !hiddenInput) {
            console.warn(`Dropdown elements not found for ${btnId}`);
            return;
        }

        dropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Hide other dropdowns
            document.querySelectorAll('.absolute.z-10').forEach(menu => {
                if (menu.id !== menuId) menu.classList.add('hidden');
            });
            dropdownMenu.classList.toggle('hidden');
        });

        dropdownMenu.addEventListener('click', (e) => {
            const target = e.target.closest('.' + itemClass);
            if (target) {
                const selectedValue = target.dataset.value;
                dropdownValue.textContent = selectedValue;
                hiddenInput.value = selectedValue;
                dropdownMenu.classList.add('hidden');
                if (callback) callback(selectedValue); // Fire callback with the new value
            }
        });
    };

    // --- Role Dropdown Logic ---
const userIdLabel = document.querySelector('label[for="user_id"]');
const userIdInput = document.querySelector('#user_id');
const roleSelect = document.querySelector('#role');

const handleRoleChange = (role) => {
        if (userIdLabel) {
            if (role === 'Guest') {
                userIdLabel.innerHTML = 'ID Number <span class="text-red-500">*</span>';
            } else if (role === 'Student') {
                userIdLabel.innerHTML = 'Student ID <span class="text-red-500">*</span>';
            } else {
                userIdLabel.innerHTML = 'User ID <span class="text-red-500">*</span>';
            }
        }
        if (userIdInput) {
            userIdInput.value = '';
        }
        };
        if (roleSelect) {
            roleSelect.addEventListener('change', (e) => {
                handleRoleChange(e.target.value);
            });
        }
    setupDropdown('roleDropdownBtn', 'roleDropdownMenu', 'roleDropdownValue', 'role', 'role-item', handleRoleChange);

    // --- Item Type Dropdown Logic ---
    const itemNameLabel = document.getElementById('item_name_label');
    const itemNameInput = document.getElementById('item_name');
    const itemIdLabel = document.getElementById('item_id_label');
    const itemIdInput = document.getElementById('item_id');
    const itemIcon = document.getElementById('item_icon');

    const handleItemTypeChange = (type) => {
        if (!itemNameLabel || !itemNameInput || !itemIdLabel || !itemIdInput || !itemIcon) return;
        if (type === 'Book') {
            itemIcon.className = 'ph ph-book-open text-3xl text-emerald-600';
            itemNameLabel.innerHTML = 'Book Title <span class="text-red-500">*</span>';
            itemNameInput.placeholder = 'Enter book title';
            itemIdLabel.innerHTML = 'Accession Number <span class="text-red-500">*</span>';
            itemIdInput.placeholder = 'Enter accession number';
        } else { // Equipment
            itemIcon.className = 'ph ph-desktop text-3xl text-emerald-600';
            itemNameLabel.innerHTML = 'Equipment Name <span class="text-red-500">*</span>';
            itemNameInput.placeholder = 'Enter equipment name';
            itemIdLabel.innerHTML = 'Equipment ID <span class="text-red-500">*</span>';
            itemIdInput.placeholder = 'Enter equipment ID';
        }
    };
    setupDropdown('itemTypeDropdownBtn', 'itemTypeDropdownMenu', 'itemTypeDropdownValue', 'item_type', 'item-type-item', handleItemTypeChange);

    // --- General Click Listener to close dropdowns ---
    document.addEventListener('click', (e) => {
        const allMenus = document.querySelectorAll('.absolute.z-10, .absolute.z-20');
        allMenus.forEach(menu => {
            const btn = document.getElementById(menu.id.replace('Menu', 'Btn'));
            if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });
});