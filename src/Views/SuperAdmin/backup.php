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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <button type="button" data-type="full_sql"
                    class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
                            hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                    <i class="ph ph-database text-3xl text-purple-500 group-hover:text-white transition-colors"></i>
                    <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Full Backup</span>
                    <span class="text-sm text-gray-700 group-hover:text-white">All Tables</span>
                </button>

                <button type="button" data-type="users"
                    class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
                            hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                    <i class="ph ph-users text-3xl text-orange-500 group-hover:text-white transition-colors"></i>
                    <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Users Only</span>
                    <span class="text-sm text-gray-700 group-hover:text-white">User accounts</span>
                </button>

                <button type="button" data-type="books"
                    class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
                            hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                    <i class="ph ph-book text-3xl text-green-500 group-hover:text-white transition-colors"></i>
                    <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Books Only</span>
                    <span class="text-sm text-gray-700 group-hover:text-white">Book catalog</span>
                </button>

                <button type="button" data-type="equipment"
                    class="backup-btn group flex flex-col items-center justify-center p-4 border border-gray-200 rounded-lg shadow-sm 
                            hover:bg-amber-500 hover:border-orange-500 hover:shadow-md transition-all duration-200 cursor-pointer">
                    <i class="ph ph-desktop-tower text-3xl text-red-500 group-hover:text-white transition-colors"></i>
                    <span class="text-base font-semibold text-gray-700 group-hover:text-white mt-2">Equipment Only</span>
                    <span class="text-sm text-gray-700 group-hover:text-white">Equipment data</span>
                </button>
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
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[220px]">
                                File Name
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                Type
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[90px]">
                                Size
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[160px]">
                                Created Date
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[140px]">
                                Created By
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="backupFilesTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
            <p id="noBackupFilesFound" class="text-gray-500 text-center w-full py-10 hidden">
                <i class="ph ph-folder-simple-x text-4xl block mb-2 text-gray-400"></i>
                No backup files found.
            </p>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="flex justify-center items-center mt-6">
            <nav class="bg-white px-8 py-3 rounded-full shadow-md border border-gray-200">
                <ul class="flex items-center gap-4 text-sm">
                    <!-- Previous -->
                    <li>
                        <a href="#" id="prev-page"
                            class="flex items-center gap-1 text-gray-600 hover:text-gray-700 transition">
                            <i class="ph ph-caret-left"></i>
                            <span>Previous</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <div id="pagination-numbers" class="flex items-center gap-3">
                        <!-- JS will insert page numbers here -->
                    </div>

                    <!-- Next -->
                    <li>
                        <a href="#" id="next-page"
                            class="flex items-center gap-1 text-gray-600 hover:text-gray-700 transition">
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
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            <i class="ph ph-file-zip text-lg text-gray-500 mr-2"></i><span class="file-name"></span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="file-type-badge px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 file-size"></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            <i class="ph ph-calendar text-base mr-1"></i><span class="created-date"></span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 created-by"></td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button
                class="download-btn inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 mr-2">
                <i class="ph ph-download-simple text-lg mr-1"></i> Download
            </button>
            <button
                class="delete-btn inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="ph ph-trash text-lg mr-1"></i> Delete
            </button>
        </td>
    </tr>
</template>