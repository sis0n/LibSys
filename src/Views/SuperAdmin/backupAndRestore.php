<!-- Header -->
<div class="flex items-center gap-3 mb-6">
    <i class="ph-fill ph-database text-3xl text-gray-700"></i>
    <div>
        <h2 class="text-2xl font-bold mb-4">Backup & Restore</h2>
        <p class="text-gray-500">Restore deleted users and books back to the active system.</p>
    </div>
</div>

<!-- Tabs -->
<div class="bg-orange-100 rounded-full p-1 mb-6 shadow-sm">
    <div class="flex items-center gap-2">
        <button id="usersTabBtn" data-tab="users"
            class="flex-1 bg-white rounded-full shadow-sm py-2.5 px-4 flex items-center justify-center gap-2 text-sm font-semibold text-gray-800">
            <i class="ph ph-users text-lg"></i>
            <span>Deleted Users</span>
        </button>
        <button id="booksTabBtn" data-tab="books"
            class="flex-1 py-2.5 px-4 flex items-center rounded-full justify-center gap-2 text-sm font-medium text-gray-500">
            <i class="ph ph-book-open text-lg"></i>
            <span>Deleted Books</span>
        </button>
    </div>
</div>

<!-- Users Table Content -->
<div id="usersContent">
    <!-- Search and Filter for Users -->
    <div class=" bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-funnel text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Search & Filter</h3>
        </div>
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="userSearchInput" placeholder="Search by name or username..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-11 pr-4 py-2.5 w-full text-sm outline-none transition">
            </div>
            <!-- Role Dropdown -->
            <div class="relative">
                <button id="userRoleDropdownBtn"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex items-center gap-2 w-40 justify-between">
                    <span>All Roles</span>
                    <i class="ph ph-caret-down"></i>
                </button>
                <!-- Dropdown menu (hidden by default) -->
                <div id="userRoleDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="All Roles">All Roles</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Admin">Admin</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Librarian">Librarian</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Student">Student</a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Table/Content -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-4">Deleted Users List</h4>
        <div class="overflow-x-auto rounded-lg border border-orange-200">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">User
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Role
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Deleted Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Deleted By</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="deletedUsersTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- User rows will be inserted here by JavaScript -->
                </tbody>
            </table>
            <template id="user-row-template">
                <tr class="hover:bg-gray-50 user-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-md font-medium text-gray-800 user-username"></div>
                        <div class="text-sm text-gray-800 user-full-name"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-3 py-1 rounded-md font-medium text-xs user-role"></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-3 py-1 rounded-md font-medium text-xs user-status"></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 user-deleted-date"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 user-deleted-by"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            <button
                                class="px-2 py-1 border border-orange-300 rounded-md font-medium text-xs hover:bg-orange-50 cursor-pointer shadow-sm restore-btn">
                                <i class="ph ph-arrow-counter-clockwise"></i> Restore
                            </button>
                            <button
                                class="px-2 py-1 rounded-md font-medium text-xs bg-red-600 text-white  cursor-pointer hover:bg-red-700 delete-btn">
                                <i class="ph ph-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl border border-gray-100  max-w-lg flex flex-col">

        <!-- Modal Header -->
        <div class="p-6 border-b rounded-t-lg border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
            <div>
                <h3 class="text-xl font-semibold text-orange-600">Deleted User Details</h3>
                <p class="text-sm text-gray-500">Complete information about this deleted user</p>
            </div>
            <button id="closeUserDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable if overflow) -->
        <div class="p-8 space-y-6 overflow-y-auto">
            <!-- User Information -->
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm font-semibold text-orange-500 mb-4 uppercase tracking-wider">User Information</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Full Name:</p>
                        <p id="modalUserFullName" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Username:</p>
                        <p id="modalUsername" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Role:</p>
                        <p id="modalUserRole" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email:</p>
                        <p id="modalUserEmail" class="font-medium text-gray-800 break-words"></p>
                    </div>
                </div>
            </div>

            <!-- Deletion Details -->
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm font-semibold text-orange-500 mb-4 uppercase tracking-wider">Deletion Details</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Created Date:</p>
                        <p id="modalUserCreatedDate" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Deleted Date:</p>
                        <p id="modalUserDeletedDate" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Deleted By:</p>
                        <p id="modalUserDeletedBy" class="font-medium text-gray-800 break-words"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Books Table Content (hidden by default) -->
<div id="booksContent" class="hidden">
    <!-- Search and Filter for Books -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-funnel text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Search & Filter</h3>
        </div>
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="bookSearchInput" placeholder="Search by title, author, or accession number..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-11 pr-4 py-2.5 w-full text-sm outline-none focus:ring-1 focus:ring-orange-400 transition">
            </div>
            <!-- Category Dropdown -->
            <div class="relative">
                <button id="bookCategoryDropdownBtn"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex items-center gap-2 w-40 justify-between">
                    <span>All Categories</span>
                    <i class="ph ph-caret-down"></i>
                </button>
                <!-- Dropdown menu (hidden by default) -->
                <div id="bookCategoryDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="All Categories">All Categories</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Fiction">Fiction</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Non-Fiction">Non-Fiction</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Science">Science</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Book Table/Content will go here -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-4">Deleted Books List</h4>
        <!-- Placeholder for book data table -->
        <p class="text-gray-500">Book data table will be displayed here.</p>
    </div>
</div>



<script src="/libsys/public/js/superadmin/backupAndRestore.js"></script>