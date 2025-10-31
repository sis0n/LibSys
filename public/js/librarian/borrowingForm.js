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

    // check user button erp
    document.getElementById('check-btn').addEventListener('click', async () => {
        const userId = document.getElementById('input_user_id').value.trim();
        
        if (!userId) {
            // 🟠 Warning Toast: Missing User ID
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
                                <h3 class="text-[15px] font-semibold text-orange-600">Missing ID</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">Please enter a User ID to check.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
                },
            });
            return;
        }

        // 🔵 Loading Alert
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                    <p class="text-gray-700 text-[14px]">Checking user data...<br><span class="text-sm text-gray-500">Communicating with ERP.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
            },
        });
        
        try {
            const res = await fetch(`/LibSys/public/librarian/borrowingForm/checkUser`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ input_user_id: userId })
            });
            const data = await res.json();
            
            Swal.close(); // Close loading

            if (data.exists) {
                // 🟠 Confirmation Modal: User Found
                const fill = await Swal.fire({
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-center">
                            <div class="flex justify-center mb-3">
                                <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                                    <i class="ph ph-user-check text-2xl"></i>
                                </div>
                            </div>
                            <h3 class="text-[17px] font-semibold text-orange-700">User Found</h3>
                            <p class="text-[14px] text-gray-700 mt-1">
                                User ID <span class="font-semibold">${userId}</span> exists. Fill the remaining fields with saved details?
                            </p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Yes, Fill Fields",
                    cancelButtonText: "Cancel",
                    customClass: {
                        popup: "!rounded-xl !shadow-md !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] !border-2 !border-orange-400 shadow-[0_0_8px_#ffb34770]",
                        confirmButton: "!bg-orange-600 !text-white !px-5 !py-2.5 !rounded-lg hover:!bg-orange-700",
                        cancelButton: "!bg-gray-200 !text-gray-800 !px-5 !py-2.5 !rounded-lg hover:!bg-gray-300",
                    },
                });

                if (fill.isConfirmed) {
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
                // 🟠 Warning Toast: User Not Found
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 4000,
                    width: "360px",
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-600">
                                    <i class="ph ph-warning text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-orange-600">User Not Found</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">User ID not found. Please fill the form manually for Guest.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
                    },
                });
            }
        } catch (err) {
            console.error(err);
            Swal.close();
            // 🔴 Error Toast: Network Error
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
                                <h3 class="text-[15px] font-semibold text-red-600">Connection Error</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">An error occurred while checking the user ID.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
        }
    });


    // submit
    document.getElementById('main-borrow-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        // 1. INPUT VALIDATION CHECK (Detailed Check)
        const requiredFields = ['input_user_id', 'first_name', 'last_name', 'email', 'contact'];
        let isFormValid = true;

        for (const fieldName of requiredFields) {
            if (!formData.get(fieldName) || formData.get(fieldName).trim() === '') {
                isFormValid = false;
                break; 
            }
        }

        // Check item-specific fields
        const itemType = formData.get('item_type');
        if (itemType === 'Book') {
            if (!formData.get('accession_number') || formData.get('accession_number').trim() === '' || 
                !formData.get('book_title') || formData.get('book_title').trim() === '') {
                isFormValid = false;
            }
        } else { // Equipment
            if (!formData.get('item_id') || formData.get('item_id').trim() === '' || 
                !formData.get('item_name') || formData.get('item_name').trim() === '') {
                isFormValid = false;
            }
        }

        if (!isFormValid) {
            // 🟠 Warning Toast: Missing Fields
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
                                <h3 class="text-[15px] font-semibold text-orange-600">Missing Information</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">Please fill in all required fields to proceed with borrowing.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
                },
            });
            return;
        }


        // 2. PROCEED TO SUBMISSION
        
        if (!formData.get('role')) formData.set('role', '');
        if (!formData.get('equipment_type')) formData.set('equipment_type', formData.get('item_type'));

        // 🔵 Loading Alert
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-600"></div>
                    <p class="text-gray-700 text-[14px]">Processing borrowing request...<br><span class="text-sm text-gray-500">Do not close the page.</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-blue-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#eef6ff] shadow-[0_0_8px_#3b82f670]",
            },
        });

        try {
            const res = await fetch(`/LibSys/public/librarian/borrowingForm/create`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            
            Swal.close(); // Close loading

            if (data.success) {
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
                                    <h3 class="text-[15px] font-semibold text-green-600">Borrow Success!</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">${data.message || 'The item was successfully borrowed.'}</p>
                                </div>
                            </div>
                        </div>
                    `,
                    customClass: {
                        popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
                    },
                });
                form.reset();
            } else {
                 // 🔴 Error Toast: Submission Failed
                Swal.fire({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 4000,
                    width: "360px",
                    background: "transparent",
                    html: `
                        <div class="flex flex-col text-left">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600">
                                    <i class="ph ph-x-circle text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-[15px] font-semibold text-red-600">Submission Failed</h3>
                                    <p class="text-[13px] text-gray-700 mt-0.5">${data.message || 'Error submitting form.'}</p>
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
            console.error(err);
            Swal.close();
            // 🔴 Error Toast: Network Error
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
                                <h3 class="text-[15px] font-semibold text-red-600">Connection Error</h3>
                                <p class="text-[13px] text-gray-700 mt-0.5">Error connecting to the server for submission.</p>
                            </div>
                        </div>
                    </div>
                `,
                customClass: {
                    popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
                },
            });
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
    

});     