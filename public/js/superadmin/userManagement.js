window.addEventListener("DOMContentLoaded", () => {
  // --- DOM Elements ---
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
  const paginationControls = document.getElementById("paginationControls");
  const paginationList = document.getElementById("paginationList");
  const resultsIndicator = document.getElementById("resultsIndicator");

  // --- State ---
  let users = [];
  let selectedRole = "All Roles";
  let selectedStatus = "All Status";
  let currentEditingUserId = null;
  let currentSearch = '';
  let searchTimeout;

  // --- Pagination State (Restored) ---
  let totalUsers = 0;
  const limit = 10; // Records per page
  let currentPage = 1;
  let totalPages = 1;
  let isLoading = false;

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
    } else if (normalizedRole === 'faculty' || normalizedRole === 'staff') {
      label.innerHTML = 'Department <span class="text-red-500">*</span>';
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
        if (selectedValue) select.value = selectedValue;
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
        if (selectedValue) select.value = selectedValue;
      }
    } catch (err) {
      console.error("Error loading colleges:", err);
      select.innerHTML = '<option value="">Error loading colleges</option>';
    }
  }

  toggleModules(modulesSection, userRoleValueEl.textContent || "");

  // --- Modals and Dropdowns Setup ---
  if (openBtn) openBtn.addEventListener("click", () => { modal?.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); });
  if (closeBtn) closeBtn.addEventListener("click", () => closeModal(modal));
  if (cancelBtn) cancelBtn.addEventListener("click", () => closeModal(modal));
  modal?.addEventListener("click", e => { if (e.target === modal) closeModal(modal); });

  fileInput?.addEventListener("change", () => { if (fileInput.files.length) bulkImportForm?.requestSubmit(); });

  bulkImportForm?.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!fileInput || !fileInput.files.length) return alert("Please pick a CSV file.");
    const formData = new FormData();
    formData.append("csv_file", fileInput.files[0]);
    try {
      const res = await fetch("api/superadmin/userManagement/bulkImport", { method: "POST", body: formData });
      const data = await res.json();
      if (data.success) {
        if (importMessage) {
          importMessage.textContent = `Imported: ${data.imported} rows successfully!`;
          importMessage.classList.remove("hidden");
          setTimeout(() => importMessage.classList.add("hidden"), 5000);
        }
        fileInput.value = "";
        closeModal(modal);
        await loadUsers(1);
      } else {
        alert("Error: " + (data.message || "Failed to import CSV."));
      }
    } catch (err) {
      console.error("Error importing CSV:", err);
      alert("Error importing CSV.");
    }
  });

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
    btn.addEventListener("click", (e) => { e.stopPropagation(); menu.classList.toggle("hidden"); });
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
    const items = document.querySelectorAll(`#${containerId} .dropdown-item, #${containerId} .role-item, #${containerId} .status-item, #${containerId} .user-role-item, #${containerId} .edit-role-item, #${containerId} .edit-status-item`);
    items.forEach(item => item.classList.remove("bg-orange-50", "font-semibold", "text-orange-700"));
    if (selectedElement && selectedElement.classList) {
      selectedElement.classList.add("bg-orange-50", "font-semibold", "text-orange-700");
    }
  }

  // --- Search & Filters (Connected to Pagination) ---
  searchInput?.addEventListener("input", e => {
    currentSearch = e.target.value.trim();
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      loadUsers(1);
    }, 500);
  });

  window.selectRole = (el, val) => {
    const valueEl = document.getElementById("roleDropdownValue");
    if (valueEl) valueEl.textContent = val;
    setActiveOption("roleDropdownMenu", el);
    selectedRole = val;
    loadUsers(1);
  };

  window.selectStatus = (el, val) => {
    const valueEl = document.getElementById("statusDropdownValue");
    if (valueEl) valueEl.textContent = val;
    setActiveOption("statusDropdownMenu", el);
    selectedStatus = val;
    loadUsers(1);
  };

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

  window.selectEditStatus = (el, val) => {
    const valueEl = document.getElementById("editStatusDropdownValue");
    if (valueEl) valueEl.textContent = val;
    setActiveOption("editStatusDropdownMenu", el);
  };

  // --- Data Loading (Restored Pagination Logic) ---
  async function loadUsers(page = 1) {
    if (isLoading) return;
    isLoading = true;
    currentPage = page;

    userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Loading users...</td></tr>`;
    paginationControls.classList.add('hidden');
    resultsIndicator.textContent = 'Loading...';

    const offset = (page - 1) * limit;

    try {
      const params = new URLSearchParams({
        search: currentSearch,
        role: selectedRole,
        status: selectedStatus,
        limit: limit,
        offset: offset
      });

      const res = await fetch(`api/superadmin/userManagement/fetch?${params.toString()}`);
      const data = await res.json();

      if (!data.success) throw new Error(data.message || "Failed to fetch users");

      totalUsers = data.totalCount;
      totalPages = Math.ceil(totalUsers / limit) || 1;

      if (page > totalPages && totalPages > 0) {
        loadUsers(totalPages);
        return;
      }

      users = data.users.map(u => ({
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

      renderTable(users);
      renderPagination(totalPages, currentPage);
      updateUserCounts(users.length, totalUsers, page, limit);

    } catch (err) {
      console.error("Fetch users error:", err);
      userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-red-500 py-10">Error loading users.</td></tr>`;
      resultsIndicator.textContent = 'Error loading data.';
    } finally {
      isLoading = false;
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

  // --- Pagination Rendering (Restored) ---
  function renderPagination(totalPages, page) {
    if (!paginationControls || !paginationList) return;
    if (totalPages <= 1) {
      paginationControls.classList.add("hidden");
      return;
    }
    paginationControls.classList.remove("hidden");
    paginationList.innerHTML = '';

    const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
      const li = document.createElement("li");
      const a = document.createElement("a");
      a.href = "#";
      a.setAttribute("data-page", String(pageNum));
      let baseClasses = `flex items-center justify-center min-w-[32px] h-9 text-sm font-medium transition-all duration-200`;
      if (type === "prev" || type === "next") {
        a.innerHTML = text;
        baseClasses += ` text-gray-700 hover:text-orange-600 px-3`;
        if (isDisabled) baseClasses += ` opacity-50 cursor-not-allowed pointer-events-none`;
      } else if (type === "ellipsis") {
        a.textContent = text;
        baseClasses += ` text-gray-400 cursor-default px-2`;
      } else {
        a.textContent = text;
        if (isActive) {
          baseClasses += ` text-white bg-orange-600 rounded-full shadow-sm px-3`;
        } else {
          baseClasses += ` text-gray-700 hover:text-orange-600 hover:bg-orange-100 rounded-full px-3`;
        }
      }
      a.className = baseClasses;
      li.appendChild(a);
      paginationList.appendChild(li);
    };

    createPageLink("prev", `<i class="flex ph ph-caret-left text-lg"></i> Previous`, page - 1, page === 1);
    const windowSize = 1;
    let pagesToShow = new Set([1, totalPages, page]);
    for (let i = 1; i <= windowSize; i++) {
      if (page - i > 0) pagesToShow.add(page - i);
      if (page + i <= totalPages) pagesToShow.add(page + i);
    }
    const sortedPages = [...pagesToShow].sort((a, b) => a - b);
    let lastPage = 0;
    for (const p of sortedPages) {
      if (p > lastPage + 1) createPageLink("ellipsis", "…", "...", true);
      createPageLink("number", p, p, false, p === page);
      lastPage = p;
    }
    createPageLink("next", `Next <i class="flex ph ph-caret-right text-lg"></i>`, page + 1, page === totalPages);
  }

  paginationList.addEventListener('click', (e) => {
    e.preventDefault();
    if (isLoading) return;
    const target = e.target.closest('a[data-page]');
    if (!target) return;
    const pageStr = target.dataset.page;
    if (pageStr === '...') return;
    const pageNum = parseInt(pageStr, 10);
    if (!isNaN(pageNum) && pageNum !== currentPage) {
      loadUsers(pageNum);
    }
  });

  function updateUserCounts(usersLength, totalCountNum, page, perPage) {
    if (resultsIndicator) {
      if (totalCountNum === 0) {
        resultsIndicator.innerHTML = `Showing <span class="font-medium">0</span> of <span class="font-medium">0</span> users`;
      } else {
        const startItem = (page - 1) * perPage + 1;
        const endItem = (page - 1) * perPage + usersLength;
        resultsIndicator.innerHTML = `Showing <span class="font-medium">${startItem}-${endItem}</span> of <span class="font-medium">${totalCountNum.toLocaleString()}</span> users`;
      }
    }
  }

  // --- CRUD Actions ---
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
        return alert("Please fill in all required fields (First Name, Last Name, Username, Role).");
      }

      if (selectWrapper && !selectWrapper.classList.contains('hidden')) {
        const selectedValue = selectField.value;
        if (!selectedValue) {
          const fieldName = role.toLowerCase() === 'student' ? 'Course/Program' : 'College/Department';
          return alert(`Please select a ${fieldName}.`);
        }
        if (role.toLowerCase() === 'student') payloadData.course_id = selectedValue;
        else if (role.toLowerCase() === 'faculty' || role.toLowerCase() === 'staff') payloadData.college_id = selectedValue;
      }

      const checkedModules = Array.from(document.querySelectorAll('input[name="modules[]"]:checked')).map(cb => cb.value);

      try {
        const res = await fetch("api/superadmin/userManagement/add", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ first_name, middle_name: middle_name || null, last_name, username, role, ...(payloadData.course_id && { course_id: payloadData.course_id }), ...(payloadData.college_id && { college_id: payloadData.college_id }), modules: checkedModules })
        });
        const data = await res.json();
        if (data.success) {
          alert("User added successfully!");
          closeAddUserModal();
          await loadUsers(1);
        } else {
          alert("Error: " + data.message);
        }
      } catch (err) {
        console.error("Add user error:", err);
        alert("An error occurred while adding the user.");
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
              cb.checked = user.modules?.some(m => m.toLowerCase().trim() === cb.value.toLowerCase().trim()) || false;
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
        if (!confirm(`Delete user "${user.name}" (${user.role})?`)) return;
        try {
          const res = await fetch(`api/superadmin/userManagement/delete/${user.user_id}`, { method: "POST" });
          const data = await res.json();
          if (data.success) {
            alert("User deleted successfully!");
            await loadUsers(currentPage);
          } else {
            alert("Error: " + (data.message || "Failed to delete."));
          }
        } catch (err) {
          console.error("Delete error:", err);
          alert("An error occurred while deleting the user.");
        }
      }

      if (e.target.closest(".toggle-status-btn")) {
        if (user.role.toLowerCase() === 'superadmin') return alert("Superadmin status cannot be changed!");
        const confirmMsg = user.status === 'Active' ? `Deactivate ${user.name}?` : `Activate ${user.name}?`;
        if (!confirm(confirmMsg)) return;
        try {
          const res = await fetch(`api/superadmin/userManagement/toggleStatus/${user.user_id}`, { method: "POST" });
          const data = await res.json();
          if (data.success) {
            alert(`${user.name} is now ${data.newStatus}.`);
            await loadUsers(currentPage);
          } else {
            alert("Error: " + (data.message || "Failed to update status."));
          }
        } catch (err) {
          console.error("Toggle status error:", err);
          alert("An error occurred while updating user status.");
        }
      }

      if (e.target.closest(".allow-edit-btn")) {
        if (!confirm(`Allow "${user.name}" to edit their profile?`)) return;
        try {
          const res = await fetch(`api/superadmin/userManagement/allowEdit/${user.user_id}`, { method: "POST", headers: { "Content-Type": "application/json" } });
          const data = await res.json();
          if (data.success) {
            alert(data.message || "User can now edit their profile.");
          } else {
            alert("Error: " + (data.message || "Failed to allow edit."));
          }
        } catch (err) {
          console.error("Allow Edit error:", err);
          alert("An error occurred while updating the user.");
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
        return alert("First Name, Last Name, Username, and Email are required.");
      }
      const changePasswordCheckbox = document.getElementById("togglePassword");
      if (changePasswordCheckbox && changePasswordCheckbox.checked) {
        const newPassword = document.getElementById("editPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;
        if (newPassword.length < 8) return alert("Password must be at least 8 characters long.");
        if (newPassword !== confirmPassword) return alert("Passwords do not match.");
        payload.password = newPassword;
      }
      const permContainer = document.getElementById("editPermissionsContainer");
      if (permContainer && !permContainer.classList.contains("hidden")) {
        payload.modules = Array.from(document.querySelectorAll('input[name="editModules[]"]:checked')).map(cb => cb.value);
      }
      try {
        const res = await fetch(`api/superadmin/userManagement/update/${currentEditingUserId}`, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload) });
        const data = await res.json();
        if (data.success) {
          alert("User updated successfully!");
          closeEditUserModal();
          await loadUsers(currentPage);
        } else {
          alert("Error: " + (data.message || "Failed to update user."));
        }
      } catch (err) {
        console.error("Update user error:", err);
        alert("An error occurred while updating the user.");
      } finally {
        closeEditUserModal();
      }
    });
  }

  // --- Password Toggle ---
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

  // --- Badge Helpers ---
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

  // --- Initial Load ---
  loadUsers();
});
