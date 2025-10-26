<!-- Header -->
<div class="flex items-center gap-3 mb-6">
    <i class="ph-fill ph-database text-3xl text-gray-700"></i>
    <div>
        <h2 class="text-2xl font-bold mb-4">Backup & Restore</h2>
        <p class="text-gray-500">Create system backups and restore deleted records.</p>
    </div>
</div>

<!-- Tabs -->
<div class="bg-orange-100 rounded-full p-1 mb-6 shadow-sm">
    <div class="flex items-center gap-2">
        <button id="restoreTabBtn" data-tab="restore"
            class="flex-1 bg-white rounded-full shadow-sm py-2.5 px-4 flex items-center justify-center gap-2 text-sm font-semibold text-gray-800">
            <i class="ph ph-upload-simple text-lg"></i>
            <span>Restore</span>
        </button>
        <button id="backupTabBtn" data-tab="backup"
            class="flex-1 py-2.5 px-4 flex items-center rounded-full justify-center gap-2 text-sm font-medium text-gray-500">
            <i class="ph ph-download-simple text-lg"></i>
            <span>Backup</span>
        </button>
    </div>
</div>

<!-- Restore Content -->
<div id="restoreContent">
    <!-- Search and Filter -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-funnel text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Search & Filter</h3>
        </div>
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative flex-grow">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Search..."
                    class="bg-orange-50 border border-orange-200 rounded-lg pl-11 pr-4 py-2.5 w-full text-sm outline-none transition">
            </div>
            <!-- Type Dropdown -->
            <div class="relative">
                <button id="typeDropdownBtn"
                    class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex items-center gap-2 w-40 justify-between">
                    <span>Users</span>
                    <i class="ph ph-caret-down"></i>
                </button>
                <div id="typeDropdownMenu"
                    class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Users">Users</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
                        data-value="Books">Books</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Table -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-4">Deleted Items</h4>
        <div class="overflow-x-auto rounded-lg border border-orange-200">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Type
                        </th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">User
                            / Title</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Username / Author</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Deleted Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Deleted By</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody id="deletedItemsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Rows will be inserted here by JavaScript -->
                </tbody>
            </table>
            <template id="item-row-template">
                <tr class="hover:bg-gray-50 item-row cursor-pointer" data-item-id="" data-item-type="">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-3 py-1 rounded-md font-medium text-xs item-type-badge"></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-md font-medium text-gray-800 item-main-identifier"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 item-secondary-identifier"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 item-deleted-date"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 item-deleted-by"></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            <button
                                class="px-2 py-1 border border-orange-300 rounded-md font-medium text-xs hover:bg-orange-50 cursor-pointer shadow-sm restore-btn">
                                <i class="ph ph-arrow-counter-clockwise"></i> Restore
                            </button>
                            <button
                                class="px-2 py-1 rounded-md font-medium text-xs bg-red-600 text-white cursor-pointer hover:bg-red-700 delete-btn">
                                <i class="ph ph-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </div>
    </div>
</div>

<!-- Backup Content (hidden by default) -->
<div id="backupContent" class="hidden">
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-4">Create System Backup</h4>
        <p class="text-gray-500">This feature is not yet available.</p>
    </div>
</div>


<!-- User Details Modal -->
<div id="userDetailsModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg flex flex-col">
        <div class="p-6 border-b rounded-t-lg border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-orange-600">Deleted User Details</h3>
                <p class="text-sm text-gray-500">Complete information about this deleted user</p>
            </div>
            <button id="closeUserDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        <div class="p-8 space-y-6 overflow-y-auto">
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

<!-- Book Details Modal -->
<div id="bookDetailsModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl flex flex-col">
        <div class="p-6 border-b rounded-t-lg border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-orange-600">Deleted Book Details</h3>
                <p class="text-sm text-gray-500">Complete information about this deleted book</p>
            </div>
            <button id="closeBookDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        <div class="p-8 space-y-6 overflow-y-auto" style="max-height: 70vh;">
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm font-semibold text-orange-500 mb-4 uppercase tracking-wider">Book Information</h4>
                <div class="grid grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Accession No.:</p>
                        <p id="modalBookAccessionNumber" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Call No.:</p>
                        <p id="modalBookCallNumber" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">ISBN:</p>
                        <p id="modalBookIsbn" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-3">
                        <p class="text-gray-500">Title:</p>
                        <p id="modalBookTitle" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-3">
                        <p class="text-gray-500">Author(s):</p>
                        <p id="modalBookAuthor" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Publisher:</p>
                        <p id="modalBookPublisher" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Year:</p>
                        <p id="modalBookYear" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-3">
                        <p class="text-gray-500">Subject:</p>
                        <p id="modalBookSubject" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-3">
                        <p class="text-gray-500">Place of Publication:</p>
                        <p id="modalBookPlace" class="font-medium text-gray-800 break-words"></p>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <h4 class="text-sm font-semibold text-orange-500 mb-4 uppercase tracking-wider">Deletion Details</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Created Date:</p>
                        <p id="modalBookCreatedDate" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div>
                        <p class="text-gray-500">Deleted Date:</p>
                        <p id="modalBookDeletedDate" class="font-medium text-gray-800 break-words"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Deleted By:</p>
                        <p id="modalBookDeletedBy" class="font-medium text-gray-800 break-words"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/libsys/public/js/superadmin/backupAndRestore.js"></script>