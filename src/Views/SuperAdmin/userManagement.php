 <div class="flex items-center justify-between mb-6">
     <div>
         <h2 class="text-2xl font-bold mb-4">User Management</h2>
         <p class="text-gray-700">Manage students, librarians, and system access.</p>
     </div>
     <div class="flex gap-2 text-sm">
         <button
             class="inline-flex items-center bg-white font-medium border border-orange-200 justify-center px-4 py-2 rounded-lg hover:bg-gray-100 px-4 gap-2"
             id="bulkImportBtn">
             <i class="ph ph-upload-simple"></i>
             Bulk Import
         </button>
         <button
             class="px-4 py-2 bg-orange-500 text-white font-medium rounded-lg border hover:bg-orange-600 gap-2 inline-flex items-center"
             id="addUserBtn">
             <i class="ph ph-plus"></i>
             Add User
         </button>
     </div>
 </div>

 <!-- User Database Section -->
 <div class="bg-[var(--color-card)] border border-orange-200 rounded-xl shadow-sm p-6 mt-6">
     <div class="flex items-center justify-between mb-4">
         <div>
             <h3 class="text-lg font-semibold text-gray-800">User Management</h3>
             <p class="text-sm text-gray-600">Registered users in the system</p>
         </div>
         <!-- Filters -->
         <div class="flex items-center gap-2 text-sm">
             <div class="relative">
                 <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2"></i>
                 <input type="text" placeholder="Search users..."
                     class="bg-orange-50 border border-orange-200 rounded-lg pl-9 pr-3 py-2 outline-none transition text-sm">
             </div>
             <!-- ROLE DROPDOWN -->
             <div class="relative inline-block text-left">
                 <button id="roleDropdownBtn"
                     class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                     <span id="roleDropdownValue">All Roles</span>
                     <i class="ph ph-caret-down text-xs"></i>
                 </button>
                 <div id="roleDropdownMenu"
                     class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                     <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectRole(this, 'All Roles')">All Roles</div>
                     <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectRole(this, 'Student')">Student</div>
                     <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectRole(this, 'Librarian')">Librarian</div>
                     <div class="dropdown-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectRole(this, 'Admin')">Admin</div>
                 </div>
             </div>

             <!-- STATUS DROPDOWN -->
             <div class="relative inline-block text-left ml-3">
                 <button id="statusDropdownBtn"
                     class="border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between gap-2 w-36 hover:bg-orange-50 transition">
                     <span id="statusDropdownValue">All Status</span>
                     <i class="ph ph-caret-down text-xs"></i>
                 </button>
                 <div id="statusDropdownMenu"
                     class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                     <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectStatus(this, 'All Status')">All Status</div>
                     <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectStatus(this, 'Active')">Active</div>
                     <div class="status-item px-3 py-2 hover:bg-orange-100 cursor-pointer"
                         onclick="selectStatus(this, 'Inactive')">Inactive</div>
                 </div>
             </div>
         </div>
     </div>

     <!-- Table -->
     <div class="overflow-x-auto rounded-lg border border-orange-200">
         <table class="w-full text-sm border-collapse">
             <thead class="bg-orange-50 text-gray-700 border border-orange-100">
                 <tr>
                     <th class="text-left px-4 py-3 font-medium">User</th>
                     <th class="text-left px-4 py-3 font-medium">Email</th>
                     <th class="text-left px-4 py-3 font-medium">Role</th>
                     <th class="text-left px-4 py-3 font-medium">Status</th>
                     <th class="text-left px-4 py-3 font-medium">Date Registered</th>
                     <th class="text-left px-4 py-3 font-medium">Actions</th>
                 </tr>
             </thead>
             <tbody id="userTableBody" class="divide-y divide-orange-100">

             </tbody>

         </table>
     </div>
 </div>

 <!-- Bulk Import Modal Background -->
 <div id="importModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
     <!-- Modal Card -->
     <div
         class="bg-[var(--color-card)] rounded-xl shadow-lg border border-[var(--color-border)] w-full max-w-md p-6 animate-fadeIn">
         <!-- Header -->
         <div class="flex justify-between items-start mb-4">
             <h2 class="text-lg font-semibold">Bulk Import Users</h2>
             <button id="closeImportModal" class="text-gray-500 hover:text-red-700 transition">
                 <i class="ph ph-x text-2xl"></i>
             </button>
         </div>

         <!-- Description -->
         <p class="text-sm text-gray-600 mb-4">
             Import multiple users from a CSV file or use sample data.
         </p>

         <!-- Drop Zone -->
         <label for="csvFile"
             class="block border-2 border-dashed border-[var(--color-border)] rounded-lg p-8 text-center cursor-pointer hover:border-[var(--color-ring)]/60 transition">
             <i class="ph ph-upload text-[var(--color-ring)] text-3xl mb-2 block"></i>
             <p class="font-medium text-[var(--color-ring)]">Drop CSV file here or click to browse</p>
             <p class="text-xs text-gray-500 mt-1">Expected format: Name, Username, Role</p>
             <input type="file" id="csvFile" accept=".csv" class="hidden" />
         </label>

         <!-- Cancel -->
         <div class="text-center mt-4">
             <button id="cancelImport"
                 class="mt-2 border border-[var(--color-border)] px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                 Cancel
             </button>
         </div>
     </div>
 </div>

 <!-- Add User Modal Background -->
 <div id="addUserModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
     <!-- Modal Card -->
     <div
         class="rounded-xl overflow-hidden shadow-lg border border-[var(--color-border)] bg-[var(--color-card)] w-full max-w-lg animate-fadeIn">
         <div class="p-6 max-h-[90vh] overflow-y-auto">

             <!-- Header -->
             <div class="flex justify-between items-start mb-4">
                 <h2 class="text-lg font-semibold flex items-center gap-2">
                     <i class="ph ph-user-plus text-[var(--color-ring)] text-xl"></i>
                     Add New User
                 </h2>
                 <button id="closeAddUserModal" class="text-gray-500 hover:text-red-700 transition">
                     <i class="ph ph-x text-2xl"></i>
                 </button>
             </div>

             <!-- Description -->
             <p class="text-sm text-gray-600 mb-4">
                 Create a new user account with specific permissions.
             </p>

             <!-- Basic Info -->
             <div class="space-y-4 mb-6">
                 <h3 class="font-medium text-[var(--color-ring)]">Basic Information</h3>

                 <div class="grid grid-cols-2 gap-4">
                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span
                                 class="text-red-500">*</span></label>
                         <input type="text" placeholder="Enter full name"
                             class="w-full border border-[var(--color-border)] rounded-md px-3 py-2 text-sm focus-visible:ring-[var(--color-ring)] focus-visible:border-[var(--color-ring)] outline-none">
                     </div>

                     <div>
                         <label class="block text-sm font-medium text-gray-700 mb-1">Username <span
                                 class="text-red-500">*</span></label>
                         <input type="email" placeholder="user@university.edu"
                             class="w-full border border-[var(--color-border)] rounded-md px-3 py-2 text-sm focus-visible:ring-[var(--color-ring)] focus-visible:border-[var(--color-ring)] outline-none">
                     </div>
                 </div>

                 <div class="relative w-full">
                     <label class="block text-sm text-gray-700 mb-1">Role</label>
                     <button id="userRoleDropdownBtn"
                         class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between hover:bg-orange-50 transition">
                         <span id="userRoleDropdownValue">Select Role</span>
                         <i class="ph ph-caret-down text-xs"></i>
                     </button>
                     <div id="userRoleDropdownMenu"
                         class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                         <div class="user-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                             onclick="selectUserRole(this, 'Student')">Student</div>
                         <div class="user-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                             onclick="selectUserRole(this, 'Librarian')">Librarian</div>
                         <div class="user-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                             onclick="selectUserRole(this, 'Admin')">Admin</div>
                     </div>
                 </div>
             </div>

             <!-- Permissions -->
             <div>
                 <h3 class="font-medium text-[var(--color-ring)] mb-2 flex items-center gap-2">
                     <i class="ph ph-shield-check text-[var(--color-ring)]"></i> Permissions
                 </h3>
                 <p class="text-sm text-gray-600 mb-3">
                     Select the functions this user should have access to.
                 </p>

                 <div class="grid grid-cols-2 gap-3 max-h-[70vh] overflow-y-auto rounded-xl p-2">
                     <!-- Archives -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Archives
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 archives</label>
                         </div>
                     </div>

                     <!-- Inventory -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Inventory
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 items</label>
                         </div>
                     </div>

                     <!-- Borrowing System -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Borrowing
                             System</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Borrow
                                 item</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Return
                                 item</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View borrowed
                                 history</label>
                         </div>
                     </div>

                     <!-- Reports -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Reports
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Generate
                                 report</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Export
                                 report</label>
                         </div>
                     </div>

                     <!-- User Management -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">User
                             Management</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Manage
                                 roles</label>
                         </div>
                     </div>

                     <!-- System Settings -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">System
                             Settings</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 settings</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Modify
                                 settings</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Manage
                                 backup</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Restore
                                 data</label>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- Actions -->
             <div class="flex justify-end gap-2 mt-6">
                 <button
                     class="flex-1 bg-orange-600 text-white font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-700 transition">
                     Add User
                 </button>
                 <button id="cancelAddUser"
                     class="border border-orange-200 text-gray-800 font-medium px-4 py-2.5 text-sm rounded-md hover:bg-orange-50 transition">
                     Cancel
                 </button>
             </div>

         </div>
     </div>
 </div>

 <!-- Edit User Modal -->
 <div id="editUserModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
     <div
         class="rounded-xl overflow-hidden shadow-lg border border-[var(--color-border)] bg-[var(--color-card)] w-full max-w-lg animate-fadeIn">
         <div class="p-6 max-h-[90vh] overflow-y-auto">

             <!-- Header -->
             <div class="flex justify-between items-start mb-4">
                 <div>
                     <h2 id="editUserTitle" class="text-lg font-semibold flex items-center gap-2">
                         <i class="ph ph-user-gear text-orange-500 text-xl"></i>
                         Edit User: <span class="font-semibold text-gray-800">Full Name</span>
                     </h2>
                     <p class="text-sm text-gray-500 mt-1">
                         Update user information and permissions.
                     </p>
                 </div>
                 <button id="closeEditUserModal" class="text-gray-500 hover:text-red-600 transition">
                     <i class="ph ph-x text-2xl"></i>
                 </button>
             </div>

             <!-- Basic Information -->
             <div class="space-y-4 mb-6">
                 <h3 class="font-medium text-orange-600">Basic Information</h3>

                 <div class="grid grid-cols-2 gap-4">

                     <!-- Full Name -->
                     <div>
                         <label class="block text-sm text-gray-700 mb-1 font-medium">Full Name <span
                                 class="text-red-500">*</span></label>
                         <input id="editName" type="text"
                             class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none"
                             placeholder="Enter full name">
                     </div>

                     <!-- Email -->
                     <div>
                         <label class="block text-sm text-gray-700 mb-1 font-medium">Email <span
                                 class="text-red-500">*</span></label>
                         <input id="editEmail" type="email"
                             class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none"
                             placeholder="user@university.edu">
                     </div>

                     <!-- Role Dropdown -->
                     <div>
                         <label class="block text-sm text-gray-700 mb-1 font-medium">Role</label>
                         <div class="relative w-full">
                             <button id="editRoleDropdownBtn"
                                 class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between hover:bg-orange-50 transition">
                                 <span id="editRoleDropdownValue">Select Role</span>
                                 <i class="ph ph-caret-down text-xs"></i>
                             </button>
                             <div id="editRoleDropdownMenu"
                                 class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                                 <div class="edit-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                                     onclick="selectEditRole(this, 'Student')">Student</div>
                                 <div class="edit-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                                     onclick="selectEditRole(this, 'Librarian')">Librarian</div>
                                 <div class="edit-role-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                                     onclick="selectEditRole(this, 'Admin')">Admin</div>
                             </div>
                         </div>
                     </div>

                     <!-- Status Dropdown -->
                     <div>
                         <label class="block text-sm text-gray-700 mb-1 font-medium">Status</label>
                         <div class="relative w-full">
                             <button id="editStatusDropdownBtn"
                                 class="w-full border border-orange-200 rounded-lg px-3 py-2 text-sm text-gray-700 flex items-center justify-between hover:bg-orange-50 transition">
                                 <span id="editStatusDropdownValue">Select Status</span>
                                 <i class="ph ph-caret-down text-xs"></i>
                             </button>
                             <div id="editStatusDropdownMenu"
                                 class="absolute mt-1 w-full bg-white border border-orange-200 rounded-lg shadow-md hidden z-20">
                                 <div class="edit-status-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                                     onclick="selectEditStatus(this, 'Active')">Active</div>
                                 <div class="edit-status-item px-3 py-2 hover:bg-orange-100 cursor-pointer text-sm"
                                     onclick="selectEditStatus(this, 'Disable')">Disable</div>
                             </div>
                         </div>
                     </div>

                 </div>
             </div>


             <!-- Change Password -->
             <div class="border-t pt-4 mb-6 border-orange-200">
                 <h3 class="font-medium text-orange-600 mb-2 flex items-center gap-2">
                     <i class="ph ph-lock-key text-orange-500"></i> Change Password
                 </h3>

                 <!-- Toggle -->
                 <label class="flex items-center gap-2 text-sm text-gray-700 mb-3">
                     <input type="checkbox" id="togglePassword" class="accent-orange-500">
                     Change this user's password
                 </label>

                 <!-- Password Fields (hidden by default) -->
                 <div id="passwordFields" class="hidden bg-orange-50/30 border border-orange-200 rounded-lg p-4">
                     <div class="grid grid-cols-2 gap-4">
                         <!-- New Password -->
                         <div>
                             <label class="block text-sm text-gray-700 mb-1 font-medium">New Password <span
                                     class="text-red-500">*</span></label>
                             <div class="relative">
                                 <input id="editPassword" type="password"
                                     class="w-full bg-orange-50 border border-orange-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none pr-10"
                                     placeholder="Enter new password" minlength="8">
                                 <button type="button" id="toggleNewPass"
                                     class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500">
                                     <i class="ph ph-eye"></i>
                                 </button>
                             </div>
                         </div>

                         <!-- Confirm Password -->
                         <div>
                             <label class="block text-sm text-gray-700 mb-1 font-medium">Confirm Password <span
                                     class="text-red-500">*</span></label>
                             <div class="relative">
                                 <input id="confirmPassword" type="password"
                                     class="w-full bg-orange-50 border border-orange-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none pr-10"
                                     placeholder="Confirm new password">
                                 <button type="button" id="toggleConfirmPass"
                                     class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500">
                                     <i class="ph ph-eye"></i>
                                 </button>
                             </div>
                         </div>
                     </div>
                     <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long</p>
                     <!-- Note -->
                     <div class="mt-4 bg-amber-50 border border-orange-200 rounded-md p-3 text-sm text-amber-700">
                         <strong>Note:</strong> Changing the password will require the user to sign in with the new
                         password.
                         They will be notified via email about this change.
                     </div>
                 </div>
             </div>

             <!-- Permissions -->
             <div>
                 <h3 class="font-medium text-[var(--color-ring)] mb-2 flex items-center gap-2">
                     <i class="ph ph-shield-check text-[var(--color-ring)]"></i> Permissions
                 </h3>
                 <p class="text-sm text-gray-600 mb-3">
                     Select the functions this user should have access to.
                 </p>

                 <div class="grid grid-cols-2 gap-3 max-h-[70vh] overflow-y-auto rounded-xl p-2">
                     <!-- Archives -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Archives
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 archives</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 archives</label>
                         </div>
                     </div>

                     <!-- Inventory -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Inventory
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 items</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 items</label>
                         </div>
                     </div>

                     <!-- Borrowing System -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Borrowing
                             System</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Borrow
                                 item</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Return
                                 item</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View borrowed
                                 history</label>
                         </div>
                     </div>

                     <!-- Reports -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">Reports
                         </h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Generate
                                 report</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Export
                                 report</label>
                         </div>
                     </div>

                     <!-- User Management -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">User
                             Management</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Add
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Edit
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Delete
                                 users</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Manage
                                 roles</label>
                         </div>
                     </div>

                     <!-- System Settings -->
                     <div class="border rounded-md p-3 bg-orange-50/50 border-orange-200">
                         <h4 class="font-medium mb-2 text-orange-700 text-sm border-b border-orange-200 pb-1">System
                             Settings</h4>
                         <div class="space-y-1 text-sm text-gray-700">
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">View
                                 settings</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Modify
                                 settings</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Manage
                                 backup</label>
                             <label class="block"><input type="checkbox" class="mr-1 accent-orange-500">Restore
                                 data</label>
                         </div>
                     </div>
                 </div>
             </div>
             <!-- Actions -->
             <div class="flex justify-end gap-3 mt-6">
                 <button id="saveEditUser"
                     class="flex-1 bg-orange-600 text-white font-medium text-sm px-6 py-2.5 rounded-md hover:bg-orange-700 transition">
                     Save Changes
                 </button>
                 <button id="cancelEditUser"
                     class="border border-orange-200 text-gray-800 font-medium text-sm px-6 py-2.5 rounded-md hover:bg-orange-50 transition">
                     Cancel
                 </button>
             </div>
         </div>
     </div>
 </div>

<script>
const modal = document.getElementById("importModal");
const openBtn = document.getElementById("bulkImportBtn");
const closeBtn = document.getElementById("closeImportModal");
const cancelBtn = document.getElementById("cancelImport");
let selectedRole = "All Roles";
let selectedStatus = "All Status";

function applyFilters(){
    let filtered = users;
    
    if(selectedRole !== "All Roles"){
        filtered = filtered.filter(u => u.role.toLowerCase() === selectedRole.toLocaleLowerCase());
    }
    if(selectedStatus !== "All Status"){
        filtered = filtered.filter(u => u.status.toLowerCase() === selectedStatus.toLocaleLowerCase());
    }

    renderTable(filtered);
}

function selectRole(el, val) {
    selectedRole = val;
    roleDropdown.selectItem(el, val);
    applyFilters();
}

function selectStatus(el, val) {
    selectedStatus = val;
    statusDropdown.selectItem(el, val);
    applyFilters();
}

function closeModal() {
    modal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
}

if (openBtn) openBtn.addEventListener("click", () => {
    modal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
});
if (closeBtn) closeBtn.addEventListener("click", closeModal);
if (cancelBtn) cancelBtn.addEventListener("click", closeModal);
if (modal) modal.addEventListener("click", e => {
    if (e.target === modal) closeModal();
});

const addUserModal = document.getElementById("addUserModal");
const openAddUserBtn = document.getElementById("addUserBtn");
const closeAddUserBtn = document.getElementById("closeAddUserModal");
const cancelAddUserBtn = document.getElementById("cancelAddUser");

function closeAddUserModal() {
    addUserModal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
}

if (openAddUserBtn) openAddUserBtn.addEventListener("click", () => {
    addUserModal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
});
[closeAddUserBtn, cancelAddUserBtn].forEach(btn => {
    if (btn) btn.addEventListener("click", closeAddUserModal);
});
if (addUserModal) addUserModal.addEventListener("click", e => {
    if (e.target === addUserModal) closeAddUserModal();
});

function setupDropdown(buttonId, menuId, valueId, itemClass) {
    const btn = document.getElementById(buttonId);
    const menu = document.getElementById(menuId);
    const valueEl = document.getElementById(valueId);

    if (!btn || !menu || !valueEl) return;

    btn.addEventListener("click", () => menu.classList.toggle("hidden"));

    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add("hidden");
        }
    });

    function selectItem(el, val) {
        valueEl.textContent = val;
        document.querySelectorAll(`#${menuId} .${itemClass}`)
            .forEach(i => i.classList.remove("bg-orange-100", "font-semibold"));
        el.classList.add("bg-orange-100", "font-semibold");
        menu.classList.add("hidden");
    }

    return { selectItem };
}

const roleDropdown = setupDropdown("roleDropdownBtn", "roleDropdownMenu", "roleDropdownValue", "dropdown-item");
const statusDropdown = setupDropdown("statusDropdownBtn", "statusDropdownMenu", "statusDropdownValue", "status-item");
const userRoleDropdown = setupDropdown("userRoleDropdownBtn", "userRoleDropdownMenu", "userRoleDropdownValue", "user-role-item");
const editRoleDropdown = setupDropdown("editRoleDropdownBtn", "editRoleDropdownMenu", "editRoleDropdownValue", "edit-role-item");
const editStatusDropdown = setupDropdown("editStatusDropdownBtn", "editStatusDropdownMenu", "editStatusDropdownValue", "edit-status-item");

window.addEventListener("DOMContentLoaded", () => {
    applyFilters();
    const defaults = [
        "#roleDropdownMenu .dropdown-item",
        "#statusDropdownMenu .status-item",
        "#userRoleDropdownMenu .user-role-item"
    ];
    defaults.forEach(sel => {
        const el = document.querySelector(sel);
        if (el) el.classList.add("bg-orange-100", "font-semibold");
    });
});

const userTableBody = document.getElementById("userTableBody");
let users = [];

function getRoleBadge(role) {
    const base = "px-2 py-1 text-xs rounded-md font-medium";
    const normalized = role.toLowerCase();
    switch(normalized){
        case "student": return `<span class="bg-green-500 text-white ${base}">${role}</span>`;
        case "librarian": return `<span class="bg-amber-500 text-white ${base}">${role}</span>`;
        case "admin": return `<span class="bg-orange-600 text-white ${base}">${role}</span>`;
        case "superadmin": return `<span class="bg-red-600 text-white ${base}">${role}</span>`;
        default: return `<span class="bg-gray-300 text-gray-800 ${base}">${role}</span>`;
    }
}

function getStatusBadge(status) {
    const base = "px-2 py-1 text-xs rounded-md font-medium cursor-pointer transition";
    switch(status.toLowerCase()){
        case "active": return `<span class="bg-orange-500 text-white ${base}">${status}</span>`;
        case "inactive":
        case "disable": return `<span class="bg-gray-300 text-gray-700 ${base}">${status}</span>`;
        default: return `<span class="bg-gray-200 text-gray-700 ${base}">${status}</span>`;
    }
}

async function loadUsers() {
    try {
        const res = await fetch('userManagement/getAll');
        const data = await res.json();
        if (!data.success) throw new Error(data.message || "Failed to fetch users");

        users = data.users
        .filter(u => u.role.toLowerCase() !== "superadmin")
        .map(u => ({
            name: u.full_name,
            username: u.username,
            email: u.email,
            role: u.role,
            status: u.is_active == 1 ? "Active" : "Disable",
            joinDate: new Date(u.created_at).toLocaleDateString()
        }));

        renderTable(users);
    } catch(err) {
        console.error("Fetch users error:", err);
    }
}

function renderTable(users) {
    if (!userTableBody) return;
    userTableBody.innerHTML = "";

    users.forEach((user, index) => {
        const row = document.createElement("tr");
        row.className = user.status === "Disable" ? "bg-gray-100 text-gray-500" : "bg-white";

        row.innerHTML = `
            <td class="px-4 py-3">
                <p class="font-medium text-gray-800">${user.name}</p>
                <p class="text-gray-500 text-xs">${user.username}</p>
            </td>
            <td class="px-4 py-3">
                <p class="font-medium text-gray-800">${user.email}</p>
            </td>
            <td class="px-4 py-3">${getRoleBadge(user.role)}</td>
            <td class="px-4 py-3">
                <span class="status-badge cursor-pointer">${getStatusBadge(user.status)}</span>
            </td>
            <td class="px-4 py-3 text-gray-700">${user.joinDate}</td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                    <button class="editUserBtn flex items-center gap-1 border border-orange-200 text-gray-600 px-2 py-1.5 rounded-md text-xs font-medium hover:bg-orange-50 transition">
                        <i class="ph ph-note-pencil text-base"></i><span>Edit</span>
                    </button>
                    <button class="deleteUserBtn flex items-center gap-1 bg-red-600 text-white px-2 py-1.5 rounded-md text-xs font-medium hover:bg-red-700 transition">
                        <i class="ph ph-trash text-base"></i><span>Delete</span>
                    </button>
                </div>
            </td>
        `;

        const statusEl = row.querySelector(".status-badge");
        if (statusEl) {
            statusEl.addEventListener("click", () => {
                if (user.role.toLowerCase() === "superadmin") {
                    alert("Superadmin status cannot be changed!");
                }
            });
        }

        userTableBody.appendChild(row);
    });
}

const editUserModal = document.getElementById("editUserModal");
const closeEditUserBtn = document.getElementById("closeEditUserModal");
const cancelEditUserBtn = document.getElementById("cancelEditUser");
let currentEditingIndex = null;

function closeEditUserModal() {
    if (!editUserModal) return;
    editUserModal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
    currentEditingIndex = null;
}

function openEditUserModal(user, index) {
    if (!editUserModal || !user) return;
    currentEditingIndex = index;
    document.getElementById("editName").value = user.name;
    document.getElementById("editEmail").value = user.email;
    document.getElementById("editRoleDropdownValue").textContent = user.role;
    document.getElementById("editStatusDropdownValue").textContent = user.status;

    const modalTitle = document.querySelector("#editUserTitle span");
    if (modalTitle) modalTitle.textContent = user.name;

    editUserModal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
}

[closeEditUserBtn, cancelEditUserBtn].forEach(btn => {
    if (btn) btn.addEventListener("click", closeEditUserModal);
});
if (editUserModal) editUserModal.addEventListener("click", e => {
    if (e.target === editUserModal) closeEditUserModal();
});

if (userTableBody) {
    userTableBody.addEventListener("click", e => {
        const editBtn = e.target.closest(".editUserBtn");
        if (!editBtn) return;

        const row = e.target.closest("tr");
        const index = Array.from(userTableBody.children).indexOf(row);
        const user = users[index];
        openEditUserModal(user, index);
    });
}

loadUsers();
</script>
