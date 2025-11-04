document.addEventListener('DOMContentLoaded', () => {
    const setupDropdown = (btnId, menuId, valueId, inputId, itemClass, callback) => {
        const dropdownBtn = document.getElementById(btnId);
        const dropdownMenu = document.getElementById(menuId);
        const dropdownValue = document.getElementById(valueId);
        const hiddenInput = document.getElementById(inputId);
        if (!dropdownBtn || !dropdownMenu || !dropdownValue || !hiddenInput) return;

        dropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.absolute.z-10, .absolute.z-20').forEach(menu => {
                if (menu.id !== menuId) menu.classList.add('hidden');
            });
            dropdownMenu.classList.toggle('hidden');
        });

        dropdownMenu.addEventListener('click', (e) => {
            const target = e.target.closest(`.${itemClass}`);
            if (target) {
                const val = target.dataset.value;
                dropdownValue.textContent = val;
                hiddenInput.value = val;
                dropdownMenu.classList.add('hidden');
                if (callback) callback(val);
            }
        });
    };

    const itemIcon = document.getElementById('item_icon');
    const itemIdWrapper = document.getElementById('item_id').parentElement;
    const itemNameWrapper = document.getElementById('item_name').parentElement;
    const accessionWrapper = document.getElementById('accession_number_wrapper');
    const bookTitleWrapper = document.getElementById('book_title_wrapper');

    const handleItemTypeChange = (type) => {
        if (!itemIcon) return;

        if (type === 'Book') {
            itemIcon.className = 'ph ph-book-open text-3xl text-emerald-600';
            itemIdWrapper.style.display = 'none';
            itemNameWrapper.style.display = 'none';
            accessionWrapper.style.display = 'block';
            bookTitleWrapper.style.display = 'block';
        } else {
            itemIcon.className = 'ph ph-desktop text-3xl text-emerald-600';
            itemIdWrapper.style.display = 'block';
            itemNameWrapper.style.display = 'block';
            accessionWrapper.style.display = 'none';
            bookTitleWrapper.style.display = 'none';
            document.getElementById('accession_number').value = '';
            document.getElementById('book_title').value = '';
        }
    };

    setupDropdown('itemTypeDropdownBtn', 'itemTypeDropdownMenu', 'itemTypeDropdownValue', 'item_type', 'item-type-item', handleItemTypeChange);
    setupDropdown('roleDropdownBtn', 'roleDropdownMenu', 'roleDropdownValue', 'role', 'role-item');

    document.getElementById('clear-btn').addEventListener('click', () => {
        const form = document.getElementById('main-borrow-form');
        form.reset();
        document.getElementById('roleDropdownValue').textContent = 'Select Role';
        document.getElementById('itemTypeDropdownValue').textContent = 'Equipment';
        document.getElementById('role').value = '';
        document.getElementById('item_type').value = 'Equipment';
        handleItemTypeChange('Equipment');
    });

    // check user button
    document.getElementById('check-btn').addEventListener('click', async () => {
        const userId = document.getElementById('input_user_id').value.trim();
        if (!userId) return alert('Please enter a User ID');

        try {
            const res = await fetch(`api/superadmin/borrowingForm/checkUser`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ input_user_id: userId })
            });
            const data = await res.json();

            if (data.exists) {
                const fill = confirm('USER ID ALREADY EXISTS! Fill the remaining input fields?');
                if (fill) {
                    document.querySelector('input[name="first_name"]').value = data.data.first_name;
                    document.querySelector('input[name="middle_name"]').value = data.data.middle_name || '';
                    document.querySelector('input[name="last_name"]').value = data.data.last_name;
                    document.querySelector('input[name="suffix"]').value = data.data.suffix || '';
                    document.querySelector('input[name="email"]').value = data.data.email || '';
                    document.querySelector('input[name="contact"]').value = data.data.contact || '';

                    const roleDropdown = document.getElementById('roleDropdownValue');
                    const roleHiddenInput = document.getElementById('role');
                    if (roleDropdown && roleHiddenInput) {
                        roleDropdown.textContent = data.data.role.charAt(0).toUpperCase() + data.data.role.slice(1);
                        roleHiddenInput.value = data.data.role;
                    }
                }
            } else {
                alert('User ID not found. Please fill the form manually for Guest.');
            }
        } catch (err) {
            console.error(err);
            alert('Error checking user.');
        }
    });


    // submit
    document.getElementById('main-borrow-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        if (!formData.get('role')) formData.set('role', '');
        if (!formData.get('equipment_type')) formData.set('equipment_type', formData.get('item_type'));

        try {
            const res = await fetch(`api/superadmin/borrowingForm/create`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            alert(data.message);
            if (data.success) form.reset();
        } catch (err) {
            console.error(err);
            alert('Error submitting form.');
        }
    });

    document.addEventListener('click', (e) => {
        document.querySelectorAll('.absolute.z-10, .absolute.z-20').forEach(menu => {
            const btn = document.getElementById(menu.id.replace('Menu', 'Btn'));
            if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });

    handleItemTypeChange('Equipment');

   // ✅ ComboBox logic for Equipment Name (click-to-open, stays open when empty)
    const itemNameInput = document.getElementById('item_name');
    const itemNameSuggestions = document.getElementById('item_name_suggestions');
    const itemNameSuggestionsList = document.getElementById('item_name_suggestions_list');
    const itemNameDropdownArrow = document.getElementById('item_name_dropdown_arrow');

    const suggestions = [
    'Laptop',
    'Projector',
    'HDMI Cable',
    'Extension Cord',
    'Microphone'
    ];

    let highlightedIndex = -1;
    let wasPointerDownOnInput = false;

    // --- Helper: highlight list item for keyboard nav
    const updateHighlight = () => {
    const items = itemNameSuggestionsList.querySelectorAll('li');
    items.forEach((item, index) => {
        item.classList.toggle('bg-emerald-100', index === highlightedIndex);
    });
    };

    // --- Show dropdown ---
    const showSuggestions = (filter = true) => {
    const value = itemNameInput.value.toLowerCase();
    itemNameSuggestionsList.innerHTML = '';
    highlightedIndex = -1;

    let filtered = suggestions;

    // Only filter if value isn't empty
    if (filter && value.trim() !== '') {
        filtered = suggestions.filter(s => s.toLowerCase().includes(value));
    }

    // Don't hide if empty but no filter
    if (filtered.length === 0 && value.trim() !== '') {
        itemNameSuggestions.classList.add('hidden');
        return;
    }

    filtered.forEach(suggestion => {
        const li = document.createElement('li');
        li.className = 'px-4 py-2 text-sm hover:bg-emerald-50 cursor-pointer';
        li.textContent = suggestion;
        li.addEventListener('mousedown', e => {
        e.preventDefault();
        itemNameInput.value = suggestion;
        hideSuggestions();
        });
        itemNameSuggestionsList.appendChild(li);
    });

    itemNameSuggestions.classList.remove('hidden');
    };

    // --- Hide dropdown ---
    const hideSuggestions = () => {
    itemNameSuggestions.classList.add('hidden');
    itemNameSuggestionsList.innerHTML = '';
    highlightedIndex = -1;
    wasPointerDownOnInput = false;
    };

    // --- Event wiring ---
    itemNameInput.addEventListener('pointerdown', () => {
    wasPointerDownOnInput = true;
    });

    itemNameInput.addEventListener('focus', () => {
    if (wasPointerDownOnInput) {
        showSuggestions(false);
    }
    });

    itemNameInput.addEventListener('input', () => {
    showSuggestions(true);
    });

    itemNameDropdownArrow.addEventListener('pointerdown', () => {
    wasPointerDownOnInput = true;
    });

    itemNameDropdownArrow.addEventListener('click', e => {
    e.preventDefault();
    e.stopPropagation();
    if (itemNameSuggestions.classList.contains('hidden')) {
        showSuggestions(false);
        itemNameInput.focus();
    } else {
        hideSuggestions();
    }
    });

    itemNameInput.addEventListener('keydown', e => {
    const items = itemNameSuggestionsList.querySelectorAll('li');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        highlightedIndex = (highlightedIndex + 1) % items.length;
        updateHighlight();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        highlightedIndex = (highlightedIndex - 1 + items.length) % items.length;
        updateHighlight();
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (highlightedIndex > -1) {
        itemNameInput.value = items[highlightedIndex].textContent;
        hideSuggestions();
        }
    } else if (e.key === 'Escape') {
        hideSuggestions();
    }
    });

    // --- Click outside to hide ---
    document.addEventListener('click', (e) => {
    if (
        !itemNameInput.contains(e.target) &&
        !itemNameDropdownArrow.contains(e.target) &&
        !itemNameSuggestions.contains(e.target)
    ) {
        hideSuggestions();
    }
    });



});