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
            const res = await fetch(`api/admin/borrowingForm/checkUser`, {
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
            const res = await fetch(`api/admin/borrowingForm/create`, {
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

    const setupCombobox = (inputId, suggestionsId, listId, arrowId, fetchUrl, fallbackData = [], highlightClass, hoverClass) => {
        const input = document.getElementById(inputId);
        const suggestionsContainer = document.getElementById(suggestionsId);
        const suggestionsList = document.getElementById(listId);
        const dropdownArrow = document.getElementById(arrowId);

        if (!input || !suggestionsContainer || !suggestionsList || !dropdownArrow) {
            // console.error(`Combobox elements not found for ${inputId}`);
            return;
        }

        let suggestions = [];
        let highlightedIndex = -1;
        let wasPointerDownOnInput = false;

        const fetchSuggestionsData = async () => {
            try {
                const response = await fetch(fetchUrl);
                if (response.ok) {
                    suggestions = await response.json();
                } else {
                    console.error(`Failed to fetch suggestions from ${fetchUrl}. Using fallback data.`);
                    suggestions = fallbackData;
                }
            } catch (error) {
                console.error(`Error fetching suggestions from ${fetchUrl}:`, error);
                suggestions = fallbackData;
            }
        };

        if (fetchUrl) {
            fetchSuggestionsData();
        } else {
            suggestions = fallbackData;
        }

        const updateHighlight = () => {
            const items = suggestionsList.querySelectorAll('li');
            items.forEach(item => {
                item.classList.remove(highlightClass);
            });
            if (items[highlightedIndex]) {
                items[highlightedIndex].classList.add(highlightClass);
            }
        };

        const showSuggestions = (filter = true) => {
            const value = input.value.toLowerCase();
            suggestionsList.innerHTML = '';
            highlightedIndex = -1;

            let filtered = suggestions;
            if (filter && value.trim() !== '') {
                filtered = suggestions.filter(s => s.toLowerCase().includes(value));
            }

            if (filtered.length === 0 && value.trim() !== '') {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            filtered.forEach(suggestion => {
                const li = document.createElement('li');
                li.className = `px-4 py-2 text-sm cursor-pointer ${hoverClass}`;
                li.textContent = suggestion;
                li.addEventListener('mousedown', e => {
                    e.preventDefault();
                    input.value = suggestion;
                    hideSuggestions();
                });
                suggestionsList.appendChild(li);
            });

            suggestionsContainer.classList.remove('hidden');
        };

        const hideSuggestions = () => {
            suggestionsContainer.classList.add('hidden');
            highlightedIndex = -1;
            wasPointerDownOnInput = false;
        };

        input.addEventListener('pointerdown', () => {
            wasPointerDownOnInput = true;
        });

        input.addEventListener('focus', () => {
            if (wasPointerDownOnInput) {
                showSuggestions(false);
            }
        });

        input.addEventListener('input', () => showSuggestions(true));

        dropdownArrow.addEventListener('pointerdown', () => {
            wasPointerDownOnInput = true;
        });

        dropdownArrow.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            if (suggestionsContainer.classList.contains('hidden')) {
                showSuggestions(false);
                input.focus();
            } else {
                hideSuggestions();
            }
        });

        input.addEventListener('keydown', e => {
            const items = suggestionsList.querySelectorAll('li');
            if (items.length === 0) return;

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    highlightedIndex = (highlightedIndex + 1) % items.length;
                    updateHighlight();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    highlightedIndex = (highlightedIndex - 1 + items.length) % items.length;
                    updateHighlight();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (highlightedIndex > -1) {
                        input.value = items[highlightedIndex].textContent;
                        hideSuggestions();
                    }
                    break;
                case 'Escape':
                    hideSuggestions();
                    break;
            }
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !dropdownArrow.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                hideSuggestions();
            }
        });
    };

    setupCombobox(
        'item_name',
        'item_name_suggestions',
        'item_name_suggestions_list',
        'item_name_dropdown_arrow',
        'api/admin/borrowingForm/getEquipments',
        ['Computer', 'Table', 'Extension Cord', 'Whiteboard', 'HDMI Cable', 'Chess', 'Scrabble', 'Domino', 'Connect 4'],
        'bg-emerald-100',
        'hover:bg-emerald-50'
    );

    setupCombobox(
        'collateral_id',
        'collateral_id_suggestions',
        'collateral_id_suggestions_list',
        'collateral_id_dropdown_arrow',
        'api/admin/borrowingForm/getCollaterals',
        ['School ID', 'Library ID','National ID'],
        'bg-amber-100',
        'hover:bg-amber-50'
    );



});