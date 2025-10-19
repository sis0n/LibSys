// Function to initialize the entire script, called on DOMContentLoaded
function initializeUserManagement() {
  // DOM Elements
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

  // Check if essential elements exist
  if (!modal || !searchInput || !userTableBody || !addUserModal || !editUserModal) {
    console.error("UserManagement Error: Core components (modals, table, search) not found. Script initialization failed.");
    return; // Stop execution if core elements are missing
  }

  // State Variables
  let allUsers = [];
  let users = [];
  let selectedRole = "All Roles";
  let selectedStatus = "All Status";
  let currentEditingUserId = null;

  // --- FILTER AND SEARCH LOGIC ---
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

  async function searchUsers(query) {
    // Use a placeholder row for loading
    if (userTableBody) userTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Searching...</td></tr>`;

    if (!query) {
      await loadUsers(); // Reload all if query is empty
      return;
    }
    try {
      // Note: Ensure this URL is correct and matches your RouteConfig
      const res = await fetch(`/LibSys/public/superadmin/userManagement/search?q=${encodeURIComponent(query)}`);
      if (!res.ok) throw new Error(`Search request failed with status ${res.status}`);

      const data = await res.json();

      if (data.success && Array.isArray(data.users)) {
        allUsers = data.users
          .filter(u => u.role.toLowerCase() !== "superadmin") // Filter here too
          .map(u => ({
            user_id: u.user_id,
            name: u.full_name,
            username: u.username,
            email: u.email,
            role: u.role,
            status: u.is_active == 1 ? "Active" : "Inactive",
            joinDate: new Date(u.created_at).toLocaleDateString() // Original date format
          }));
      } else {
        console.warn("Search did not return successful data.", data);
        allUsers = []; // Clear users on failed search
      }
    } catch (err) {
      console.error("Search error:", err);
      allUsers = []; // Clear users on fetch error
      if (userTableBody) userTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-10">Error during search: ${err.message}</td></tr>`;
    }
    applyFilters(); // Render results (even if empty)
  }

  // Add debouncing to search input
  let searchTimeout;
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      const query = e.target.value.trim();
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        searchUsers(query);
      }, 500); // Wait 500ms after user stops typing
    });
  }

  // --- MODAL CONTROLS ---
  function closeModal(modalEl) {
    if (modalEl) {
      modalEl.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }
  }

  // Bulk Import Modal
  if (openBtn) openBtn.addEventListener("click", () => { modal.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); });
  [closeBtn, cancelBtn].forEach(btn => btn?.addEventListener("click", () => closeModal(modal)));
  modal?.addEventListener("click", e => { if (e.target === modal) closeModal(modal); });

  // Add User Modal
  function closeAddUserModal() { closeModal(addUserModal); }
  if (openAddUserBtn) openAddUserBtn.addEventListener("click", () => { addUserModal.classList.remove("hidden"); document.body.classList.add("overflow-hidden"); });
  [closeAddUserBtn, cancelAddUserBtn].forEach(btn => btn?.addEventListener("click", closeAddUserModal));
  addUserModal?.addEventListener("click", e => { if (e.target === addUserModal) closeAddUserModal(); });

  // Edit User Modal
  function closeEditUserModal() { closeModal(editUserModal); currentEditingUserId = null; }
  [closeEditUserBtn, cancelEditUserBtn].forEach(btn => btn?.addEventListener("click", closeEditUserModal));
  editUserModal?.addEventListener("click", e => { if (e.target === editUserModal) closeEditUserModal(); });

  // --- DROPDOWN LOGIC ---
  function setupDropdownToggle(buttonId, menuId) {
    const btn = document.getElementById(buttonId);
    const menu = document.getElementById(menuId);
    if (!btn || !menu) {
      console.warn(`Dropdown elements missing: ${buttonId} or ${menuId}`);
      return;
    }

    // Function to close all dropdowns
    const closeAllDropdowns = () => {
      document.querySelectorAll('.absolute.mt-1.z-20').forEach(m => m.classList.add('hidden'));
    }

    btn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isHidden = menu.classList.contains('hidden');
      closeAllDropdowns(); // Close all first
      if (isHidden) menu.classList.remove("hidden"); // Open this one if it was closed
    });
  }

  setupDropdownToggle("roleDropdownBtn", "roleDropdownMenu");
  setupDropdownToggle("statusDropdownBtn", "statusDropdownMenu");
  setupDropdownToggle("userRoleDropdownBtn", "userRoleDropdownMenu");
  setupDropdownToggle("editRoleDropdownBtn", "editRoleDropdownMenu");
  setupDropdownToggle("editStatusDropdownBtn", "editStatusDropdownMenu");

  // Global click listener to close all dropdowns
  document.addEventListener("click", () => {
    document.querySelectorAll('.absolute.mt-1.z-20').forEach(menu => menu.classList.add('hidden'));
  });

  // Make functions global for onclick="" attributes
  window.selectRole = (el, val) => {
    const valueEl = document.getElementById('roleDropdownValue');
    if (valueEl) valueEl.textContent = val;
    selectedRole = val;
    applyFilters();
  };
  window.selectStatus = (el, val) => {
    const valueEl = document.getElementById('statusDropdownValue');
    if (valueEl) valueEl.textContent = val;
    selectedStatus = val;
    applyFilters();
  };
  window.selectUserRole = (el, val) => {
    const valueEl = document.getElementById('userRoleDropdownValue');
    if (valueEl) valueEl.textContent = val;
  };
  window.selectEditRole = (el, val) => {
    const valueEl = document.getElementById('editRoleDropdownValue');
    if (valueEl) valueEl.textContent = val;
  };
  window.selectEditStatus = (el, val) => {
    const valueEl = document.getElementById('editStatusDropdownValue');
    if (valueEl) valueEl.textContent = val;
  };

  // --- DATA FETCHING AND RENDERING ---
  async function loadUsers() {
    if (userTableBody) userTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-gray-500 py-10"><i class="ph ph-spinner animate-spin text-2xl"></i> Loading users...</td></tr>`;
    try {
      // Note: Make sure this path is correct based on your routing
      const res = await fetch('userManagement/getAll');
      if (!res.ok) throw new Error(`Fetch failed: ${res.statusText}`);
      const data = await res.json();
      if (!data.success || !Array.isArray(data.users)) throw new Error(data.message || "Invalid data format");

      allUsers = data.users
        .filter(u => u.role.toLowerCase() !== "superadmin")
        .map(u => ({
          user_id: u.user_id,
          name: u.full_name,
          username: u.username,
          email: u.email,
          role: u.role,
          status: u.is_active == 1 ? "Active" : "Inactive",
          joinDate: new Date(u.created_at).toLocaleDateString() // Original date format
        }));
      applyFilters();
    } catch (err) {
      console.error("Fetch users error:", err);
      if (userTableBody) userTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-10">Error loading users: ${err.message}</td></tr>`;
    }
  }

  function renderTable(usersToRender) {
    if (!userTableBody) return;
    userTableBody.innerHTML = "";

    if (usersToRender.length === 0) {
      userTableBody.innerHTML = `<tr data-placeholder="true"><td colspan="6" class="text-center text-gray-500 py-10">No users found.</td></tr>`;
      return;
    }

    usersToRender.forEach((user) => {
      const row = document.createElement("tr");
      row.className = user.status === "Inactive" ? "bg-gray-100 text-gray-500" : "bg-white"; // Original classes

      // Basic sanitization
      const name = user.name ? user.name.replace(/</g, "&lt;") : 'N/A';
      const username = user.username ? user.username.replace(/</g, "&lt;") : 'N/A';
      const email = user.email ? user.email.replace(/</g, "&lt;") : 'N/A';

      row.innerHTML = `
                <td class="px-4 py-3"><p class="font-medium text-gray-800">${name}</p><p class="text-gray-500 text-xs">${username}</p></td>
                <td class="px-4 py-3">${email || 'N/A'}</td>
                <td class="px-4 py-3">${getRoleBadge(user.role)}</td>
                <td class="px-4 py-3"><span class="status-badge cursor-pointer toggle-status-btn">${getStatusBadge(user.status)}</span></td>
                <td class="px-4 py-3 text-gray-700">${user.joinDate}</td>
                <td class="px-4 py-3"><div class="flex items-center gap-2">
                    <button class="editUserBtn flex items-center gap-1 border border-orange-200 text-gray-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-orange-50 transition"><i class="ph ph-note-pencil text-base"></i><span>Edit</span></button>
                    <button class="deleteUserBtn flex items-center gap-1 bg-red-600 text-white px-2 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition"><i class="ph ph-trash text-base"></i><span>Delete</span></button>
                </div></td>`;
      userTableBody.appendChild(row);
    });
  }

  // --- ACTIONS (ADD, EDIT, DELETE, TOGGLE) ---
  const confirmAddUserBtn = document.getElementById("confirmAddUser");
  if (confirmAddUserBtn) {
    confirmAddUserBtn.addEventListener("click", async () => {
      const nameInput = addUserModal.querySelector('input[placeholder="Enter full name"]');
      const usernameInput = addUserModal.querySelector('input[placeholder="username"]');
      const full_name = nameInput.value.trim();
      const username = usernameInput.value.trim();
      const role = document.getElementById("userRoleDropdownValue").textContent.trim();

      if (!full_name || !username || role === "Select Role") {
        // Use SweetAlert if available, otherwise fallback
        if (typeof Swal !== 'undefined') Swal.fire('Missing Info', 'Please fill in all required fields.', 'warning');
        else alert("Please fill in all required fields.");
        return;
      }

      try {
        const res = await fetch("/LibSys/public/superadmin/userManagement/add", {
          method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" },
          body: JSON.stringify({ full_name, username, role })
        });
        const data = await res.json();
        if (data.success) {
          if (typeof Swal !== 'undefined') Swal.fire('Success!', 'User added successfully!', 'success');
          else alert("User added successfully!");
          closeAddUserModal();
          await loadUsers();
        } else {
          if (typeof Swal !== 'undefined') Swal.fire('Error', data.message || 'Failed to add user.', 'error');
          else alert("Error: " + (data.message || 'Failed to add user.'));
        }
      } catch (err) {
        console.error("Add user error:", err);
        if (typeof Swal !== 'undefined') Swal.fire('Error', 'An error occurred while adding the user.', 'error');
        else alert("An error occurred while adding the user.");
      }
    });
  }

  // Table Click Actions (Edit, Delete, Toggle)
  if (userTableBody) {
    userTableBody.addEventListener("click", async (e) => {
      const row = e.target.closest("tr");
      if (!row || row.dataset.placeholder) return;

      const validRows = Array.from(userTableBody.querySelectorAll("tr:not([data-placeholder='true'])"));
      const index = validRows.indexOf(row);
      if (index < 0) return;

      const user = users[index];
      if (!user) return;

      // Edit Button
      if (e.target.closest(".editUserBtn")) {
        currentEditingUserId = user.user_id;
        editUserModal.querySelector("#editName").value = user.name;
        editUserModal.querySelector("#editEmail").value = user.email;
        editUserModal.querySelector("#editRoleDropdownValue").textContent = user.role;
        editUserModal.querySelector("#editStatusDropdownValue").textContent = user.status;
        editUserModal.querySelector("#editUserTitle span").textContent = user.name;
        editUserModal.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
      }

      // Delete Button
      if (e.target.closest(".deleteUserBtn")) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Are you sure?', text: `Delete user "${user.name}" (${user.role})?`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, delete it!'
          }).then(async (result) => {
            if (result.isConfirmed) await deleteUserAction(user.user_id);
          });
        } else {
          if (confirm(`Delete user "${user.name}" (${user.role})?`)) {
            await deleteUserAction(user.user_id);
          }
        }
      }

      // Toggle Status
      if (e.target.closest(".toggle-status-btn")) {
        if (user.role.toLowerCase() === 'superadmin') {
          if (typeof Swal !== 'undefined') Swal.fire('Action Denied', 'Superadmin status cannot be changed.', 'error');
          else alert("Superadmin status cannot be changed.");
          return;
        }
        const confirmMsg = user.status === 'Active' ? `Deactivate ${user.name}?` : `Activate ${user.name}?`;
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Confirm Status Change', text: confirmMsg, icon: 'question',
            showCancelButton: true, confirmButtonColor: '#ea580c',
            cancelButtonColor: '#6b7280', confirmButtonText: 'Yes, change it!'
          }).then(async (result) => {
            if (result.isConfirmed) await toggleStatusAction(user.user_id, user.name);
          });
        } else {
          if (confirm(confirmMsg)) {
            await toggleStatusAction(user.user_id, user.name);
          }
        }
      }
    });
  }

  // Delete Action Helper
  async function deleteUserAction(userId) {
    try {
      const res = await fetch(`/LibSys/public/superadmin/userManagement/delete/${userId}`, { method: "POST" });
      const data = await res.json();
      if (data.success) {
        if (typeof Swal !== 'undefined') Swal.fire('Deleted!', 'User has been deleted.', 'success');
        else alert("User deleted successfully!");
        await loadUsers();
      }
      else {
        if (typeof Swal !== 'undefined') Swal.fire('Error', data.message || 'Failed to delete.', 'error');
        else alert("Error: " + (data.message || "Failed to delete."));
      }
    } catch (err) { console.error("Delete error:", err); if (typeof Swal !== 'undefined') Swal.fire('Error', 'An error occurred.', 'error'); else alert("An error occurred."); }
  }

  // Toggle Status Action Helper
  async function toggleStatusAction(userId, userName) {
    try {
      const res = await fetch(`/LibSys/public/superadmin/userManagement/toggleStatus/${userId}`, { method: "POST" });
      const data = await res.json();
      if (data.success) {
        if (typeof Swal !== 'undefined') Swal.fire('Status Updated', `${userName} is now ${data.newStatus}.`, 'success');
        else alert(`${userName} is now ${data.newStatus}.`);
        await loadUsers();
      }
      else {
        if (typeof Swal !== 'undefined') Swal.fire('Error', data.message || 'Failed to update status.', 'error');
        else alert("Error: " + (data.message || "Failed to update status."));
      }
    } catch (err) { console.error("Toggle status error:", err); if (typeof Swal !== 'undefined') Swal.fire('Error', 'An error occurred.', 'error'); else alert("An error occurred."); }
  }

  // Save Edit Modal Action
  const saveEditBtn = document.getElementById("saveEditUser");
  if (saveEditBtn) {
    saveEditBtn.addEventListener("click", async () => {
      if (!currentEditingUserId) return;
      const payload = {
        full_name: editUserModal.querySelector("#editName").value.trim(),
        email: editUserModal.querySelector("#editEmail").value.trim(),
        role: editUserModal.querySelector("#editRoleDropdownValue").textContent.trim(),
        is_active: editUserModal.querySelector("#editStatusDropdownValue").textContent.trim().toLowerCase() === 'active' ? 1 : 0
      };
      if (!payload.full_name || !payload.email) { if (typeof Swal !== 'undefined') Swal.fire('Missing Info', 'Full Name and Email are required.', 'warning'); else alert("Full Name and Email are required."); return; }

      const changePasswordCheckbox = editUserModal.querySelector("#togglePassword");
      if (changePasswordCheckbox.checked) {
        const newPassword = editUserModal.querySelector("#editPassword").value;
        const confirmPassword = editUserModal.querySelector("#confirmPassword").value;
        if (newPassword.length < 8) { if (typeof Swal !== 'undefined') Swal.fire('Weak Password', 'Password must be at least 8 characters.', 'warning'); else alert("Password must be at least 8 characters."); return; }
        if (newPassword !== confirmPassword) { if (typeof Swal !== 'undefined') Swal.fire('Mismatch', 'Passwords do not match.', 'warning'); else alert("Passwords do not match."); return; }
        payload.password = newPassword;
      }

      try {
        const res = await fetch(`/LibSys/public/superadmin/userManagement/update/${currentEditingUserId}`, {
          method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" },
          body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
          if (typeof Swal !== 'undefined') Swal.fire('Success!', 'User updated successfully!', 'success');
          else alert("User updated successfully!");
          closeEditUserModal(); await loadUsers();
        } else { if (typeof Swal !== 'undefined') Swal.fire('Error', data.message || 'Failed to update user.', 'error'); else alert("Error: " + (data.message || "Failed to update user.")); }
      } catch (err) {
        console.error("Update user error:", err); if (typeof Swal !== 'undefined') Swal.fire('Error', 'An error occurred.', 'error'); else alert("An error occurred.");
      } finally {
        if (changePasswordCheckbox) changePasswordCheckbox.checked = false;
        const passFields = editUserModal.querySelector('#passwordFields'); if (passFields) passFields.classList.add('hidden');
        const editPass = editUserModal.querySelector('#editPassword'); if (editPass) editPass.value = '';
        const confirmPass = editUserModal.querySelector('#confirmPassword'); if (confirmPass) confirmPass.value = '';
      }
    });
  }

  // Toggle Password Visibility in Edit Modal
  const togglePasswordCheckbox = document.getElementById('togglePassword');
  if (togglePasswordCheckbox) {
    togglePasswordCheckbox.addEventListener('change', () => {
      const passFields = editUserModal.querySelector('#passwordFields');
      if (passFields) passFields.classList.toggle('hidden', !togglePasswordCheckbox.checked);
    });
  }

  // Password eye toggles (using ID)
  const toggleNewPass = document.getElementById('toggleNewPass');
  if (toggleNewPass) toggleNewPass.addEventListener('click', () => togglePassword('editPassword', toggleNewPass));

  const toggleConfirmPass = document.getElementById('toggleConfirmPass');
  if (toggleConfirmPass) toggleConfirmPass.addEventListener('click', () => togglePassword('confirmPassword', toggleConfirmPass));

  // Make togglePassword global (same function as before)
  window.togglePassword = (id, btn) => {
    const input = document.getElementById(id); if (!input) return;
    const icon = btn.querySelector("i"); if (!icon) return;
    if (input.type === "password") { input.type = "text"; icon.classList.remove("ph-eye"); icon.classList.add("ph-eye-slash"); }
    else { input.type = "password"; icon.classList.remove("ph-eye-slash"); icon.classList.add("ph-eye"); }
  };

  // --- BADGE HELPER FUNCTIONS ---
  function getRoleBadge(role) {
    const base = "px-2 py-1 text-xs rounded-md font-medium";
    switch (role.toLowerCase()) {
      case "student": return `<span class="${base} bg-green-500 text-white">${role}</span>`;
      case "librarian": return `<span class="${base} bg-amber-500 text-white">${role}</span>`;
      case "admin": return `<span class="${base} bg-orange-600 text-white">${role}</span>`;
      case "superadmin": return `<span class="${base} bg-red-600 text-white">${role}</span>`;
      default: return `<span class="${base} bg-gray-300 text-gray-800">${role}</span>`;
    }
  }
  function getStatusBadge(status) {
    const base = "px-2 py-1 text-xs rounded-md font-medium";
    return status.toLowerCase() === "active" ? `<span class="${base} bg-green-500 text-white">Active</span>` : `<span class="${base} bg-gray-300 text-gray-700">Inactive</span>`;
  }

  // --- INITIAL LOAD ---
  loadUsers();
}

// Run the initialization function when the DOM is ready
document.addEventListener("DOMContentLoaded", initializeUserManagement);