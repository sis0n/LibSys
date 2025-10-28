document.addEventListener('DOMContentLoaded', () => {
    const backupFilesTableBody = document.getElementById('backupFilesTableBody');
    const backupFileRowTemplate = document.getElementById('backup-file-row-template')?.content;
    const backupFilesCount = document.getElementById('backupFilesCount');
    const noBackupFilesFound = document.getElementById('noBackupFilesFound');
    const paginationContainer = document.getElementById('pagination-container');
    const paginationNumbersDiv = document.getElementById('pagination-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const backupButtons = document.querySelectorAll('.backup-btn');

    if (!backupFilesTableBody || !backupFileRowTemplate) {
        console.error("Required table elements not found.");
        return;
    }

    let allBackupFiles = [];
    const itemsPerPage = 10;
    let currentPage = 1;

    // ====== HELPERS ======
    const showLoadingState = () => {
        backupFilesTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">Loading...</td></tr>';
        noBackupFilesFound.classList.add('hidden');
        paginationContainer.classList.add('hidden');
    };

    const showErrorState = (message) => {
        backupFilesTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-10 text-red-500">${message}</td></tr>`;
        backupFilesCount.textContent = 0;
        paginationContainer.classList.add('hidden');
        noBackupFilesFound.classList.remove('hidden');
    };

    const renderBackupFiles = (files) => {
        backupFilesTableBody.innerHTML = '';
        backupFilesCount.textContent = allBackupFiles.length;

        if (files.length === 0) {
            noBackupFilesFound.classList.remove('hidden');
            paginationContainer.classList.add('hidden');
            return;
        }
        noBackupFilesFound.classList.add('hidden');

        files.forEach(file => {
            const newRow = backupFileRowTemplate.cloneNode(true);
            newRow.querySelector('.file-name').textContent = file.fileName;
            newRow.querySelector('.file-size').textContent = file.size;
            newRow.querySelector('.created-date').textContent = file.createdDate;

            // *** AYOS DITO: Gamitin ang pangalan imbis na ID ***
            newRow.querySelector('.created-by').textContent = file.createdByName || 'Unknown User'; // Display name

            const typeBadge = newRow.querySelector('.file-type-badge');
            typeBadge.textContent = file.type;

            const badgeColors = {
                SQL: ['bg-purple-100', 'text-purple-800'],
                ZIP: ['bg-blue-100', 'text-blue-800'],
                Default: ['bg-gray-100', 'text-gray-800']
            };

            const color = badgeColors[file.type] || badgeColors.Default;
            typeBadge.classList.add(...color);

            const downloadBtn = newRow.querySelector('.download-btn');
            downloadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                secureDownload(file.downloadLink, file.fileName);
            });

            backupFilesTableBody.appendChild(newRow);
        });
    };

    const renderPagination = (totalItems, itemsPerPage, currentPage) => {
        paginationNumbersDiv.innerHTML = '';
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalPages <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }
        paginationContainer.classList.remove('hidden');

        const makeLink = (page, isActive) => {
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = page;
            link.className = `flex items-center justify-center w-8 h-8 rounded-full text-gray-700 transition ${isActive ? 'bg-orange-500 text-white font-semibold' : 'hover:bg-orange-50 hover:text-orange-600'}`;
            link.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(page);
            });
            paginationNumbersDiv.appendChild(link);
        };

        for (let i = 1; i <= totalPages; i++) makeLink(i, i === currentPage);

        prevPageBtn.classList.toggle('opacity-50', currentPage === 1);
        nextPageBtn.classList.toggle('opacity-50', currentPage === totalPages);
    };

    const goToPage = (page) => {
        const totalPages = Math.ceil(allBackupFiles.length / itemsPerPage);
        currentPage = Math.min(Math.max(1, page), totalPages);
        const start = (currentPage - 1) * itemsPerPage;
        const filesToShow = allBackupFiles.slice(start, start + itemsPerPage);
        renderBackupFiles(filesToShow);
        renderPagination(allBackupFiles.length, itemsPerPage, currentPage);
    };

    const fetchBackupFiles = async () => {
        showLoadingState();
        try {
            const res = await fetch(`${BASE_URL}/superadmin/backup/logs`);
            const data = await res.json();

            if (data.success && Array.isArray(data.logs)) {
                allBackupFiles = data.logs.map(log => ({
                    fileName: log.file_name,
                    type: log.file_type,
                    size: log.size || 'N/A',
                    createdDate: log.created_at,
                    // *** AYOS DITO: Kunin ang pangalan mula sa response ***
                    createdByName: log.created_by_name, // Assume backend sends this
                    // createdBy: log.created_by, // Keep ID if needed elsewhere
                    downloadLink: `/libsys/public/superadmin/backup/secure_download/${encodeURIComponent(log.file_name)}`
                }));

                goToPage(1);
            } else {
                showErrorState('Failed to load backup logs.');
            }
        } catch (err) {
            console.error('Network error fetching logs:', err);
            showErrorState('Network error while fetching logs.');
        }
    };


    const secureDownload = async (url, filename) => {
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Failed to download file.');

            const blob = await res.blob();
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(a.href);
        } catch (err) {
            console.error('Download error:', err);
            alert('Failed to download backup file.');
        }
    };

    backupButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const type = btn.dataset.type;
            if (!type) return;

            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-circle-notch animate-spin"></i> Generating...';
            btn.disabled = true;
            btn.classList.add('opacity-70');

            const exportUrl = type === 'full_sql'
                ? `${BASE_URL}/superadmin/backup/database/full`
                : `${BASE_URL}/superadmin/backup/export/zip/${type}`;

            try {
                const exportRes = await fetch(exportUrl);
                if (!exportRes.ok) throw new Error('Server error during backup.');

                const exportData = await exportRes.json();
                if (!exportData.success || !exportData.filename) throw new Error(exportData.message || 'Failed to create backup.');

                const downloadUrl = `${BASE_URL}/superadmin/backup/secure_download/${encodeURIComponent(exportData.filename)}`;
                await secureDownload(downloadUrl, exportData.filename);


                alert(`Backup for ${type.toUpperCase()} downloaded successfully.`);
                await fetchBackupFiles();
            } catch (err) {
                console.error('Backup Error:', err);
                alert(`Backup failed: ${err.message}`);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-70');
            }
        });
    });

    prevPageBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) goToPage(currentPage - 1);
    });
    nextPageBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < Math.ceil(allBackupFiles.length / itemsPerPage)) goToPage(currentPage + 1);
    });

    fetchBackupFiles();
});