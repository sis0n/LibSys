<?php if (!empty($csrf_token)) : ?>
<input type="hidden" id="csrf_token" value="<?= $csrf_token ?>">
<?php endif; ?>
<main class="min-h-screen">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-3">
        <div>
            <h2 class="text-2xl font-bold mb-4">Backup & Restore</h2>
            <p class="text-gray-500">Manage and secure your system data backups here.</p>
        </div>
    </div>

    <!-- Create New Backup Section -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-folder-plus text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Create New Backup</h3>
        </div>
        <p class="text-gray-600 mb-6">Choose the type and format for the data export.</p>

        <form id="backupForm" method="GET" action="<?= BASE_URL ?>/backup/export/csv">
            <input type="hidden" name="type" id="backupType" value="">

            <div class="flex justify-center">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                    <button type="button" data-type="full_sql" class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
             hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <i class="ph ph-database text-3xl text-purple-500 group-hover:text-white transition-colors"></i>
                        <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Full
                            Backup</span>
                        <span class="text-sm text-gray-700 group-hover:text-white">All Tables</span>
                    </button>

                    <button type="button" data-type="users" class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
             hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <i class="ph ph-users text-3xl text-orange-500 group-hover:text-white transition-colors"></i>
                        <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Users
                            Only</span>
                        <span class="text-sm text-gray-700 group-hover:text-white">User accounts</span>
                    </button>

                    <button type="button" data-type="books" class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
             hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                        <i class="ph ph-book text-3xl text-green-500 group-hover:text-white transition-colors"></i>
                        <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Books
                            Only</span>
                        <span class="text-sm text-gray-700 group-hover:text-white">Book catalog</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph ph-folder-open text-xl text-orange-700"></i>
            <h3 class="text-lg font-semibold text-orange-700">Backup Files (<span id="backupFilesCount">0</span>)</h3>
        </div>
        <p class="text-gray-600 mb-6">Available backup files for download or deletion.</p>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[40%]">
                            File Name
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                            Type
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                            Size
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">
                            Created Date
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                            Created By
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="backupFilesTableBody" class="bg-white divide-y divide-gray-200"></tbody>
            </table>
            <div id="noBackupFilesFound" class="hidden">
                <div class="flex flex-col items-center justify-center text-gray-500 py-10">
                    <i class="ph ph-folder-simple-x text-4xl block mb-2 text-gray-400"></i>
                    <p>No backup files found.</p>
                </div>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div id="pagination-container" class="flex justify-center items-center mt-6 hidden">
            <nav class="bg-white px-6 py-2 rounded-full shadow-sm border border-gray-200">
                <ul class="flex items-center gap-2 text-sm">
                    <li>
                        <a href="#" id="prev-page"
                            class="flex items-center gap-1 text-gray-500 hover:text-gray-800 transition p-2">
                            <i class="ph ph-caret-left"></i>
                            <span>Previous</span>
                        </a>
                    </li>
                    <div id="pagination-numbers" class="flex items-center gap-1">
                    </div>
                    <li>
                        <a href="#" id="next-page"
                            class="flex items-center gap-1 text-gray-500 hover:text-gray-800 transition p-2">
                            <span>Next</span>
                            <i class="ph ph-caret-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</main>

<script src="<?= BASE_URL ?>/js/superadmin/backup.js"></script>

<template id="backup-file-row-template">
    <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 align-center">
            <div class="flex items-center gap-2">
                <i class="ph ph-file-zip text-lg text-gray-500"></i>
                <span class="file-name font-medium text-gray-800 break-all"></span>
            </div>
        </td>
        <td class="px-4 py-3 align-center">
            <span class="file-type-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></span>
        </td>
        <td class="px-4 py-3 align-center text-gray-600 file-size"></td>
        <td class="px-4 py-3 align-center text-gray-600 created-date"></td>
        <td class="px-4 py-3 align-center text-gray-600 created-by"></td>
        <td class="px-4 py-3 align-center text-center">
            <div class="inline-flex items-center justify-center gap-2">
                <button
                    class="download-btn inline-flex items-center gap-2 px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
                    title="Download File">
                    <i class="ph ph-download-simple text-xl"></i>
                    Download
                </button>
                <button
                    class="delete-btn inline-flex items-center gap-2 px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition"
                    title="Delete File">
                    <i class="ph ph-trash text-xl"></i>
                    Delete
                </button>
            </div>
        </td>
    </tr>
</template>