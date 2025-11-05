window.addEventListener("DOMContentLoaded", () => {
    // --- SweetAlert Helper Functions (Galing sa Book Catalog Design) ---

    // 1. SUCCESS/ADD/UPDATE Toast (Green Theme)
    function showSuccessToast(title, body = "") {
        if (typeof Swal == "undefined") return alert(title);
        Swal.fire({
            toast: true,
            position: "bottom-end",
            showConfirmButton: false,
            timer: 3000,
            width: "360px",
            background: "transparent",
            html: `<div class="flex flex-col text-left"><div class="flex items-center gap-3 mb-2"><div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600"><i class="ph ph-check-circle text-lg"></i></div><div><h3 class="text-[15px] font-semibold text-green-600">${title}</h3><p class="text-[13px] text-gray-700 mt-0.5">${body}</p></div></div></div>`,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-green-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#f0fff5] shadow-[0_0_8px_#22c55e70]",
            },
        });
    }

    // 2. ERROR/VALIDATION Toast (Red Theme)
    function showErrorToast(title, body = "Please check the input details.") {
        if (typeof Swal == "undefined") return alert(title);
        Swal.fire({
            toast: true,
            position: "bottom-end",
            showConfirmButton: false,
            timer: 4000,
            width: "360px",
            background: "transparent",
            html: `<div class="flex flex-col text-left"><div class="flex items-center gap-3 mb-2"><div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-600"><i class="ph ph-x-circle text-lg"></i></div><div><h3 class="text-[15px] font-semibold text-red-600">${title}</h3><p class="text-[13px] text-gray-700 mt-0.5">${body}</p></div></div></div>`,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-red-400 !p-4 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ff6b6b70]",
            },
        });
    }

    // 3. LOADING Modal (Orange Theme)
    function showLoadingModal(message = "Processing request...", subMessage = "Please wait.") {
        if (typeof Swal == "undefined") return;
        Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600"></div>
                    <p class="text-gray-700 text-[14px]">${message}<br><span class="text-sm text-gray-500">${subMessage}</span></p>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                popup: "!rounded-xl !shadow-md !border-2 !border-orange-400 !p-6 !bg-gradient-to-b !from-[#fffdfb] !to-[#fff6ef] shadow-[0_0_8px_#ffb34770]",
            },
        });
    }

    // 4. CONFIRMATION Modal (Orange Theme)
    async function showConfirmationModal(title, text, confirmText = "Confirm") {
        if (typeof Swal == "undefined") return confirm(title);
        const result = await Swal.fire({
            background: "transparent",
            html: `
                <div class="flex flex-col text-center">
                    <div class="flex justify-center mb-3">
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-orange-100 text-orange-600">
                            <i class="ph ph-warning-circle text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-[17px] font-semibold text-orange-700">${title}</h3>
                    <p class="text-[14px] text-gray-700 mt-1">${text}</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: confirmText,
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
        return result.isConfirmed;
    }
    // --- End SweetAlert Helper Functions ---

    const programs = {};

    const departments = [];

    // --- DOM Elements (may optional chaining kung wala) ---
    const modal = document.getElementById("importModal");
    const openBtn = document.getElementById("bulkImportBtn");
    const closeBtn = document.getElementById("closeImportModal");
    const cancelBtn = document.getElementById("cancelImport");
    const searchInput = document.getElementById("userSearchInput");
    const userTableBody = document.getElementById("userTableBody");
    const addUserModal = document.getElementById("addUserModal");
    const openAddUserBtn = document.getElementById("addUserBtn");
    const closeAddUserBtn = document.getElementById("closeAddUserModal");
    const cancelAddUserBtn = document.getElementById("cancelAddUser");
    const editUserModal = document.getElementById("editUserModal");
    const closeEditUserBtn = document.getElementById("closeEditUserModal");
    const cancelEditUserBtn = document.getElementById("cancelEditUser");
    const bulkImportForm = document.getElementById("bulkImportForm");
    const fileInput = document.getElementById("csvFile");
    const importMessage = document.getElementById("importMessage");
    const modulesSection = document.getElementById("modulesSection");
    const userRoleValueEl = document.getElementById("userRoleDropdownValue");

    // --- State ---
    let allUsers = [];
    let users = [];
    let selectedRole = "All Roles";
    let selectedStatus = "All Status";
    let currentEditingUserId = null;

    function updateProgramDepartmentDropdown(role, selectedValue = null) {
        const wrapper = document.getElementById('addUserSingleSelectWrapper');
        const label = document.getElementById('addUserSelectLabel');

        if (!wrapper || !label) return;

        const normalizedRole = (role || "").trim().toLowerCase();

        wrapper.classList.add('hidden');

        if (normalizedRole === 'student') {
            label.innerHTML = 'Course/Program <span class="text-red-500">*</span>';
            wrapper.classList.remove('hidden');
            loadCoursesForStudent(selectedValue); 

        } else if (normalizedRole === 'faculty') { 
            label.innerHTML = 'College/Department <span class="text-red-500">*</span>';
            wrapper.classList.remove('hidden');
            loadDepartments(selectedValue); 

        } else { 
            wrapper.classList.add('hidden');
            const select = document.getElementById('addUserSelectField');
            if (select) select.innerHTML = '';
        }
    }

    function closeModal(modalEl) {
        if (!modalEl) return;
        modalEl.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
    }

    function buildFullName(firstName, middleName, lastName) {
        return [firstName, middleName, lastName].filter(Boolean).join(' ');
    }

    function toggleModules(container, role, userModules = []) {
        if (!container) return;
        const normalizedRole = (role || "").trim().toLowerCase();
        console.log("toggleModules:", normalizedRole, userModules);

        if (normalizedRole === "admin" || normalizedRole === "librarian") {
            container.classList.remove("hidden");
            container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = userModules.some(m => m.toLowerCase().trim() === cb.value.toLowerCase().trim()) || false;
            });
        } else {
            container.classList.add("hidden");
            container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    }


    async function loadCoursesForStudent(selectedValue = null) {
        const select = document.getElementById('addUserSelectField');
        if (!select) return;

        select.innerHTML = '<option value="">Loading Courses...</option>';

        try {
            const res = await fetch('api/superadmin/userManagement/getAllCourses');
            const data = await res.json();

            select.innerHTML = '<option value="">Select Course/Program</option>';

            if (data.success && Array.isArray(data.courses) && data.courses.length > 0) {
                data.courses.forEach(course => {
                    const option = new Option(`${course.course_code} - ${course.course_title}`, course.course_id);
                    select.add(option);
                });

                if (selectedValue) {
                    select.value = selectedValue;
                }
            } else {
                select.innerHTML = '<option value="">No Courses Found</option>';
            }

        } catch (err) {
            console.error("Error loading courses:", err);
            select.innerHTML = '<option value="">Error loading courses</option>';
        }
    }

    async function loadDepartments(selectedValue = null) {
        const select = document.getElementById('addUserSelectField');
        if (!select) return;

        select.innerHTML = '<option value="">Loading Colleges...</option>';

        try {
            const res = await fetch('api/superadmin/userManagement/getColleges');
            const data = await res.json();

            select.innerHTML = '<option value="">Select College/Department</option>';

            if (data.success && Array.isArray(data.colleges) && data.colleges.length > 0) {
                data.colleges.forEach(college => {
                    const option = new Option(`${college.college_code} - ${college.college_name}`, college.college_id);
                    select.add(option);
                });

                if (selectedValue) {
                    select.value = selectedValue;
                }
            } else {
                select.innerHTML = '<option value="">No Colleges Found</option>';
            }

    } catch (err) {
      console.error("Error loading colleges for faculty:", err);
      select.innerHTML = '<option value="">Error loading colleges</option>';
    }
  }

    toggleModules(modulesSection, userRoleValueEl.textContent || "");

    window.selectUserRole = (el, val) => {
        if (userRoleValueEl) userRoleValueEl.textContent = val;
        setActiveOption("userRoleDropdownMenu", el);
        toggleModules(modulesSection, val.trim());
        updateProgramDepartmentDropdown(val);
    };

    window.selectEditRole = (el, val) => {
        const valueEl = document.getElementById("editRoleDropdownValue");
        if (valueEl) valueEl.textContent = val;
        const editModulesContainer = document.getElementById("editPermissionsContainer");
        const user = users.find(u => u.user_id === currentEditingUserId);
        toggleModules(editModulesContainer, user.role, user?.modules || []);
    };

    if (openBtn) openBtn.addEventListener("click", () => {
        modal?.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    });
    if (closeBtn) closeBtn.addEventListener("click", () => closeModal(modal));
    if (cancelBtn) cancelBtn.addEventListener("click", () => closeModal(modal));
    modal?.addEventListener("click", e => { if (e.target === modal) closeModal(modal); });

    fileInput?.addEventListener("change", () => {
        if (fileInput.files.length) {
            bulkImportForm?.requestSubmit();
        }
    });

    bulkImportForm?.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        if (!fileInput) return showErrorToast("Import Error", "No file input found for bulk import.");
        if (!fileInput.files.length) return showErrorToast("Import Error", "Please pick a CSV file for bulk import.");

        showLoadingModal("Importing Users...", "Uploading and processing CSV file.");

        const formData = new FormData();
        formData.append("csv_file", fileInput.files[0]);

        try {
            const res = await fetch("api/superadmin/userManagement/bulkImport", {
                method: "POST",
                body: formData
            });
            const data = await res.json();
            
            await new Promise(r => setTimeout(r, 300));
            Swal.close();

            if (data.success) {
                if (importMessage) {
                    importMessage.textContent = `Imported: ${data.imported} rows successfully!`;
                    importMessage.classList.remove("hidden");
                    setTimeout(() => importMessage.classList.add("hidden"), 5000);
                }
                showSuccessToast("Import Successful", `Successfully imported ${data.imported} rows!`);
                fileInput.value = "";
                closeModal(modal);
                await loadUsers();
            } else {
                showErrorToast("Import Failed", data.message || "Failed to import CSV.");
            }
        } catch (err) {
            Swal.close();
            console.error("Error importing CSV:", err);
            showErrorToast("Import Failed", "An error occurred while importing the CSV file.");
        }
    });

    // --- Search + filters ---
    async function searchUsers(query) {
        if (userTableBody) userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Searching...</td></tr>`;
        if (!query) {
            await loadUsers();
            return;
        }
        try {
            const res = await fetch(`api/superadmin/userManagement/search?q=${encodeURIComponent(query)}`);
            if (!res.ok) throw new Error(`Search request failed with status ${res.status}`);
            const data = await res.json();

            if (data.success && Array.isArray(data.users)) {
                allUsers = data.users
                    .filter(u => u.role.toLowerCase() !== "superadmin")
                    .filter(u => u.role.toLowerCase() !== "scanner")
                    .map(u => ({
                        user_id: u.user_id,
                        first_name: u.first_name,
                        middle_name: u.middle_name,
                        last_name: u.last_name,
                        name: buildFullName(u.first_name, u.middle_name, u.last_name),
                        username: u.username,
                        email: u.email,
                        role: u.role,
                        program_department: u.program_department || null,
                        status: u.is_active == 1 ? "Active" : "Inactive",
                        joinDate: new Date(u.created_at).toLocaleDateString(),
                        modules: u.modules || []
                    }));
            } else {
                allUsers = [];
            }
        } catch (err) {
            console.error("Search error:", err);
            allUsers = [];
            if (userTableBody) userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-red-500 py-10">Error during search: ${err.message}</td></tr>`;
        }
        applyFilters();
    }

    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener("input", e => {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchUsers(query);
            }, 500);
        });
    }

    function applyFilters() {
        let filtered = [...allUsers];
        if (selectedRole !== "All Roles") {
            filtered = filtered.filter(u => u.role.toLowerCase() === selectedRole.toLowerCase());
        }
        if (selectedStatus !== "All Status") {
            filtered = filtered.filter(u => u.status.toLowerCase() === selectedStatus.toLowerCase());
        }
        users = filtered;
        renderTable(users);
    }

    // --- Modal helpers for Add/Edit ---
    function closeAddUserModal() {
        closeModal(addUserModal);
        document.getElementById("addFirstName") && (document.getElementById("addFirstName").value = "");
        document.getElementById("addMiddleName") && (document.getElementById("addMiddleName").value = "");
        document.getElementById("addLastName") && (document.getElementById("addLastName").value = "");
        document.getElementById("addUsername") && (document.getElementById("addUsername").value = "");
        if (userRoleValueEl) userRoleValueEl.textContent = "Select Role";
    }
    if (openAddUserBtn) openAddUserBtn.addEventListener("click", () => { addUserModal?.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); });
    [closeAddUserBtn, cancelAddUserBtn].forEach(btn => btn?.addEventListener("click", closeAddUserModal));
    addUserModal?.addEventListener("click", e => { if (e.target === addUserModal) closeAddUserModal(); });

    function closeEditUserModal() {
        closeModal(editUserModal);
        currentEditingUserId = null;
        const changePasswordCheckbox = document.getElementById("togglePassword");
        if (changePasswordCheckbox) changePasswordCheckbox.checked = false;
        document.getElementById('passwordFields')?.classList.add('hidden');
        document.getElementById('editPassword') && (document.getElementById('editPassword').value = '');
        document.getElementById('confirmPassword') && (document.getElementById('confirmPassword').value = '');
    }
    [closeEditUserBtn, cancelEditUserBtn].forEach(btn => btn?.addEventListener("click", closeEditUserModal));
    editUserModal?.addEventListener("click", e => { if (e.target === editUserModal) closeEditUserModal(); });

    function setupDropdownToggle(buttonId, menuId) {
        const btn = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            menu.classList.toggle("hidden");
        });
    }

    setupDropdownToggle("roleDropdownBtn", "roleDropdownMenu");
    setupDropdownToggle("statusDropdownBtn", "statusDropdownMenu");
    setupDropdownToggle("userRoleDropdownBtn", "userRoleDropdownMenu");
    setupDropdownToggle("editRoleDropdownBtn", "editRoleDropdownMenu");
    setupDropdownToggle("editStatusDropdownBtn", "editStatusDropdownMenu");

    document.addEventListener("click", () => {
        document.querySelectorAll(".absolute.mt-1, .absolute.mt-1.w-full, .absolute.w-full").forEach(menu => menu.classList.add("hidden"));
    });

    function setActiveOption(containerId, selectedElement) {
        const items = document.querySelectorAll(
            `#${containerId} .dropdown-item, #${containerId} .role-item, #${containerId} .status-item, #${containerId} .user-role-item, #${containerId} .edit-role-item, #${containerId} .edit-status-item`
        );
        items.forEach(item => item.classList.remove("bg-orange-50", "font-semibold", "text-orange-700"));
        if (selectedElement && selectedElement.classList) {
            selectedElement.classList.add("bg-orange-50", "font-semibold", "text-orange-700");
        }
    }

    window.selectRole = (el, val) => {
        const valueEl = document.getElementById("roleDropdownValue");
        if (valueEl) valueEl.textContent = val;
        setActiveOption("roleDropdownMenu", el);
        selectedRole = val;
        applyFilters();
    };

    window.selectStatus = (el, val) => {
        const valueEl = document.getElementById("statusDropdownValue");
        if (valueEl) valueEl.textContent = val;
        setActiveOption("statusDropdownMenu", el);
        selectedStatus = val;
        applyFilters();
    };

    window.selectEditStatus = (el, val) => {
        const valueEl = document.getElementById("editStatusDropdownValue");
        if (valueEl) valueEl.textContent = val;
        setActiveOption("editStatusDropdownMenu", el);
    };

    const allRolesFirst = document.querySelector("#roleDropdownMenu .dropdown-item");
    if (allRolesFirst) {
        setActiveOption("roleDropdownMenu", allRolesFirst);
        const roleVal = allRolesFirst.textContent?.trim();
        if (roleVal) {
            const roleValueEl = document.getElementById("roleDropdownValue");
            if (roleValueEl) roleValueEl.textContent = roleVal;
            selectedRole = roleVal;
        }
    }
    const allStatusFirst = document.querySelector("#statusDropdownMenu .status-item");
    if (allStatusFirst) {
        setActiveOption("statusDropdownMenu", allStatusFirst);
        const statusVal = allStatusFirst.textContent?.trim();
        if (statusVal) {
            const statusValueEl = document.getElementById("statusDropdownValue");
            if (statusValueEl) statusValueEl.textContent = statusVal;
            selectedStatus = statusVal;
        }
    }

    async function loadUsers() {
        if (userTableBody) userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Loading users...</td></tr>`;
        
        // Hindi gagamit ng loading modal dito para hindi mag-overlay sa buong page load.
        // Gagamitin lang ang table spinner.

        try {
            const res = await fetch('api/superadmin/userManagement/getAll');
            const data = await res.json();
            if (!data.success) throw new Error(data.message || "Failed to fetch users");

            allUsers = data.users
                .filter(u => u.role.toLowerCase() !== "superadmin")
                .map(u => ({
                    user_id: u.user_id,
                    first_name: u.first_name,
                    middle_name: u.middle_name,
                    last_name: u.last_name,
                    name: buildFullName(u.first_name, u.middle_name, u.last_name),
                    username: u.username,
                    email: u.email,
                    role: u.role,
                    program_department: u.program_department || null,
                    status: u.is_active == 1 ? "Active" : "Inactive",
                    joinDate: new Date(u.created_at).toLocaleDateString(),
                    modules: u.modules || []
                }));
            applyFilters();
        } catch (err) {
            console.error("Fetch users error:", err);
            if (userTableBody) userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-red-500 py-10">Error loading users.</td></tr>`;
            showErrorToast("Data Load Failed", "Could not retrieve user list data.");
        }
    }

    function renderTable(usersToRender) {
        if (!userTableBody) return;
        userTableBody.innerHTML = "";

        if (!usersToRender.length) {
            userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-gray-500 py-10">No users found.</td></tr>`;
            return;
        }

        usersToRender.forEach((user) => {
            const row = document.createElement("tr");
            row.className = user.status === "Inactive" ? "bg-gray-50 text-gray-500" : "bg-white";

            let actions = `
                <button class="editUserBtn flex items-center gap-1 border border-orange-200 text-gray-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-orange-50 transition">
                    <i class="ph ph-note-pencil text-base"></i><span>Edit</span>
                </button>
                <button class="deleteUserBtn flex items-center gap-1 bg-red-600 text-white px-2 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition">
                    <i class="ph ph-trash text-base"></i><span>Delete</span>
                </button>
            `;

            if (user.role.toLowerCase() === 'student') {
                actions += `
                    <button class="allow-edit-btn flex items-center gap-1 border border-blue-500 text-blue-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-blue-50 transition" data-id="${user.user_id}">
                        Allow Edit
                    </button>
                `;
            }

            row.innerHTML = `
                <td class="px-4 py-3"><p class="font-medium text-gray-800">${user.name}</p><p class="text-gray-500 text-xs">${user.username}</p></td>
                <td class="px-4 py-3">${user.email || 'N/A'}</td>
                <td class="px-4 py-3">${getRoleBadge(user.role)}</td>
                <td class="px-4 py-3"><span class="status-badge cursor-pointer toggle-status-btn">${getStatusBadge(user.status)}</span></td>
                <td class="px-4 py-3 text-gray-700">${user.joinDate}</td>
                <td class="px-4 py-3"><div class="flex items-center gap-2">${actions}</div></td>
            `;
            userTableBody.appendChild(row);
        });
    }

    const confirmAddUserBtn = document.getElementById("confirmAddUser");
    if (confirmAddUserBtn) {
        confirmAddUserBtn.addEventListener("click", async () => {
            const first_name = document.getElementById("addFirstName").value.trim();
            const middle_name = document.getElementById("addMiddleName").value.trim();
            const last_name = document.getElementById("addLastName").value.trim();
            const username = document.getElementById("addUsername").value.trim();
            const role = document.getElementById("userRoleDropdownValue").textContent.trim();

            const selectWrapper = document.getElementById('addUserSingleSelectWrapper');
            const selectField = document.getElementById('addUserSelectField');

            let payloadData = {};

            if (!first_name || !last_name || !username || role === "Select Role") {
                return showErrorToast("Required Fields Missing", "Please fill in all required fields (First Name, Last Name, Username, Role).");
            }

            if (selectWrapper && !selectWrapper.classList.contains('hidden')) {
                const selectedValue = selectField.value;

                if (!selectedValue) {
                    const fieldName = role.toLowerCase() === 'student' ? 'Course/Program' : 'College/Department';
                    return showErrorToast("Required Field Missing", `Please select a ${fieldName}.`);
                }

                if (role.toLowerCase() === 'student') {
                    payloadData.course_id = selectedValue;
                } else if (role.toLowerCase() === 'faculty' || role.toLowerCase() === 'staff') {
                    payloadData.college_id = selectedValue;
                }
            }

            const checkedModules = Array.from(document.querySelectorAll('input[name="modules[]"]:checked'))
                .map(cb => cb.value);

            showLoadingModal("Adding New User...", "Creating user account and setting default password.");

            try {
                const res = await fetch("api/superadmin/userManagement/add", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        first_name: first_name,
                        middle_name: middle_name || null,
                        last_name: last_name,
                        username: username,
                        role: role,
                        ...(payloadData.course_id && { course_id: payloadData.course_id }),
                        ...(payloadData.college_id && { college_id: payloadData.college_id }),
                        modules: checkedModules
                    })
                });
                const data = await res.json();
                
                await new Promise(r => setTimeout(r, 300));
                Swal.close();

                if (data.success) {
                    showSuccessToast("User Added Successfully!", `Account for ${first_name} ${last_name} created.`);
                    closeAddUserModal();
                    await loadUsers();
                } else {
                    showErrorToast("User Add Failed", data.message || "Failed to add the user.");
                }
            } catch (err) {
                Swal.close();
                console.error("Add user error:", err);
                showErrorToast("User Add Failed", "An error occurred while attempting to add the user.");
            }
        });
    }

    if (userTableBody) {
        userTableBody.addEventListener("click", async (e) => {
            const row = e.target.closest("tr");
            if (!row || row.dataset.placeholder) return;

            const validRows = Array.from(userTableBody.querySelectorAll("tr:not([data-placeholder='true'])"));
            const index = validRows.indexOf(row);
            if (index < 0) return;

            const user = users[index];
            if (!user) return;

            if (e.target.closest(".editUserBtn")) {
                currentEditingUserId = user.user_id;

                document.getElementById("editFirstName").value = user.first_name || '';
                document.getElementById("editMiddleName").value = user.middle_name || '';
                document.getElementById("editLastName").value = user.last_name || '';
                document.getElementById("editUsername").value = user.username || '';
                document.getElementById("editEmail").value = user.email || '';
                document.getElementById("editRoleDropdownValue").textContent = user.role;
                document.getElementById("editStatusDropdownValue").textContent = user.status;
                document.querySelector("#editUserTitle span").textContent = user.name;

                const editModulesContainer = document.getElementById("editPermissionsContainer");
                if (editModulesContainer) {
                    if (user.role.toLowerCase() === 'admin' || user.role.toLowerCase() === 'librarian') {
                        editModulesContainer.classList.remove("hidden");

                        editModulesContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.checked = user.modules?.some(
                                m => m.toLowerCase().trim() === cb.value.toLowerCase().trim()
                            ) || false;
                        });
                    } else {
                        editModulesContainer.classList.add("hidden");
                        editModulesContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    }
                }

                editUserModal.classList.remove("hidden");
                document.body.classList.add("overflow-hidden");
            }

            if (e.target.closest(".deleteUserBtn")) {
                const isConfirmed = await showConfirmationModal(
                    "Confirm Deletion", 
                    `Are you sure you want to delete user: ${user.name} (${user.role})? This action cannot be undone.`,
                    "Yes, Delete!"
                );
                if (!isConfirmed) return;

                showLoadingModal("Deleting user...", `Deleting ${user.name} from the system.`);

                try {
                    const res = await fetch(`api/superadmin/userManagement/delete/${user.user_id}`, {
                        method: "POST"
                    });
                    const data = await res.json();
                    
                    await new Promise(r => setTimeout(r, 300));
                    Swal.close(); 

                    if (data.success) {
                        showSuccessToast("User Deleted!", `User ${user.name} was successfully removed.`);
                        await loadUsers();
                    } else {
                        showErrorToast("Deletion Failed", data.message || "Failed to delete the user.");
                    }
                } catch (err) {
                    Swal.close();
                    console.error("Delete error:", err);
                    showErrorToast("Deletion Failed", "An error occurred while deleting the user.");
                }
            }

            // TOGGLE STATUS
            if (e.target.closest(".toggle-status-btn")) {
                if (user.role.toLowerCase() === 'superadmin') return showErrorToast("Action Denied", "Superadmin status cannot be changed!");

                const newStatus = user.status === 'Active' ? 'Inactive' : 'Active';
                const confirmMsg = user.status === 'Active' ? `Deactivate ${user.name}?` : `Activate ${user.name}?`;
                const isConfirmed = await showConfirmationModal(
                    "Confirm Status Change", 
                    `Are you sure you want to change the status of ${user.name} to **${newStatus}**?`,
                    `Yes, ${newStatus}`
                );
                if (!isConfirmed) return;

                showLoadingModal("Updating Status...", `Setting status to ${newStatus}.`);

                try {
                    const res = await fetch(`api/superadmin/userManagement/toggleStatus/${user.user_id}`, {
                        method: "POST"
                    });
                    const data = await res.json();
                    
                    await new Promise(r => setTimeout(r, 300));
                    Swal.close();

                    if (data.success) {
                        showSuccessToast("Status Updated", `${user.name} is now ${data.newStatus}.`);
                        await loadUsers();
                    } else {
                        showErrorToast("Status Update Failed", data.message || "Failed to update user status.");
                    }
                } catch (err) {
                    Swal.close();
                    console.error("Toggle status error:", err);
                    showErrorToast("Status Update Failed", "An error occurred while updating user status.");
                }
            }

            if (e.target.closest(".allow-edit-btn")) {
                const userId = user.user_id;
                const isConfirmed = await showConfirmationModal(
                    "Allow Profile Edit?",
                    `This will temporarily allow "${user.name}" (Student) to edit their profile details once. Continue?`,
                    "Yes, Allow Edit"
                );
                if (!isConfirmed) return;

                showLoadingModal("Granting Permission...", "Sending temporary edit token.");

                try {
                    const res = await fetch(`api/superadmin/userManagement/allowEdit/${userId}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" }
                    });
                    const data = await res.json();
                    
                    await new Promise(r => setTimeout(r, 300));
                    Swal.close();

                    if (data.success) {
                        showSuccessToast("Permission Granted", data.message || "User can now edit their profile.");
                        await loadUsers();
                    } else {
                        showErrorToast("Permission Failed", data.message || "Failed to allow edit for the user.");
                    }
                } catch (err) {
                    Swal.close();
                    console.error("Allow Edit error:", err);
                    showErrorToast("Permission Failed", "An error occurred while updating the user's permission.");
                }
            }
        });
    }

    const saveEditBtn = document.getElementById("saveEditUser");
    if (saveEditBtn) {
        saveEditBtn.addEventListener("click", async () => {
            if (!currentEditingUserId) return;

            const role = document.getElementById("editRoleDropdownValue").textContent.trim();

            const payload = {
                first_name: document.getElementById("editFirstName").value.trim(),
                middle_name: document.getElementById("editMiddleName").value.trim() || null,
                last_name: document.getElementById("editLastName").value.trim(),
                username: document.getElementById("editUsername").value.trim(),
                email: document.getElementById("editEmail").value.trim(),
                role: role,
                is_active: document.getElementById("editStatusDropdownValue").textContent.trim().toLowerCase() === 'active' ? 1 : 0
            };

            if (!payload.first_name || !payload.last_name || !payload.email || !payload.username) {
                return showErrorToast("Required Fields Missing", "First Name, Last Name, Username, and Email are required.");
            }

            const changePasswordCheckbox = document.getElementById("togglePassword");
            if (changePasswordCheckbox && changePasswordCheckbox.checked) {
                const newPassword = document.getElementById("editPassword").value;
                const confirmPassword = document.getElementById("confirmPassword").value;
                if (newPassword.length < 8) return showErrorToast("Password Error", "Password must be at least 8 characters long.");
                if (newPassword !== confirmPassword) return showErrorToast("Password Error", "New password and confirmation password do not match.");
                payload.password = newPassword;
            }

            const permContainer = document.getElementById("editPermissionsContainer");
            if (permContainer && !permContainer.classList.contains("hidden")) {
                payload.modules = Array.from(document.querySelectorAll('input[name="editModules[]"]:checked')).map(cb => cb.value);
            }

            showLoadingModal("Saving Changes...", `Updating user details for ${payload.first_name} ${payload.last_name}.`);
            
            try {
                const res = await fetch(`api/superadmin/userManagement/update/${currentEditingUserId}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                
                await new Promise(r => setTimeout(r, 300));
                Swal.close();

                if (data.success) {
                    showSuccessToast("User Updated!", "User information saved successfully.");
                    closeEditUserModal();
                    await loadUsers();
                } else {
                    showErrorToast("Update Failed", data.message || "Failed to update user information.");
                }
            } catch (err) {
                Swal.close();
                console.error("Update user error:", err);
                showErrorToast("Update Failed", "An error occurred while updating the user.");
            } finally {
                closeEditUserModal();
            }
        });
    }

    const togglePasswordCheckbox = document.getElementById('togglePassword');
    if (togglePasswordCheckbox) {
        togglePasswordCheckbox.addEventListener('change', () => {
            document.getElementById('passwordFields')?.classList.toggle('hidden', !togglePasswordCheckbox.checked);
        });
    }

    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        if (!input || !icon) return;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        }
    }

    const toggleNewPass = document.getElementById('toggleNewPass');
    if (toggleNewPass) toggleNewPass.addEventListener('click', () => togglePassword('editPassword', toggleNewPass));
    const toggleConfirmPass = document.getElementById('toggleConfirmPass');
    if (toggleConfirmPass) toggleConfirmPass.addEventListener('click', () => togglePassword('confirmPassword', toggleConfirmPass));

    // --- Badge helpers ---
    function getRoleBadge(role) {
        const base = "px-2 py-1 text-xs rounded-md font-medium";
        switch (role.toLowerCase()) {
            case "student": return `<span class="bg-green-500 text-white ${base}">${role}</span>`;
            case "librarian": return `<span class="bg-amber-500 text-white ${base}">${role}</span>`;
            case "admin": return `<span class="bg-orange-600 text-white ${base}">${role}</span>`;
            case "faculty": return `<span class="bg-emerald-600 text-white ${base}">${role}</span>`;
            case "staff": return `<span class="bg-teal-600 text-white ${base}">${role}</span>`;
            case "superadmin": return `<span class="bg-purple-600 text-white ${base}">${role}</span>`;
            default: return `<span class="bg-gray-300 text-gray-800 ${base}">${role}</span>`;
        }
    }
    function getStatusBadge(status) {
        const base = "px-2 py-1 text-xs rounded-md font-medium";
        return status.toLowerCase() === "active" ? `<span class="bg-green-500 text-white ${base}">Active</span>` : `<span class="bg-gray-300 text-gray-700 ${base}">Inactive</span>`;
    }

    loadUsers();
});