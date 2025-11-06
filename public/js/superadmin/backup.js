// --- SweetAlert Helper Functions (Global Declarations) ---

function showErrorToast(title, body = "An error occurred during processing.") {
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

// 🟠 LOADING MODAL (ORANGE THEME)
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
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


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

    // --- Page Memory ---
    try {
        const savedPage = sessionStorage.getItem('backupPage');
        if (savedPage) {
            const parsedPage = parseInt(savedPage, 10);
            if (!isNaN(parsedPage) && parsedPage > 0) {
                currentPage = parsedPage;
            } else {
                sessionStorage.removeItem('backupPage');
            }
        }
    } catch (e) {
        console.error("SessionStorage Error:", e);
        currentPage = 1;
    }

    // ====== HELPERS ======
    const showLoadingState = () => {
        // Gagamitin na lang natin ang SweetAlert2, pero iiwan natin ang loading message sa table for safety/fallback
        backupFilesTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">Loading...</td></tr>';
        noBackupFilesFound.classList.add('hidden');
        paginationContainer.classList.add('hidden');
    };

    const showErrorState = (message) => {
        // Tiyakin na magsara ang SweetAlert2 bago mag-display ng error
        if (typeof Swal != 'undefined') Swal.close();
        showErrorToast('Load Failed', message);
        
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

        if (totalItems <= itemsPerPage || totalPages <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }
        paginationContainer.classList.remove('hidden');

        prevPageBtn.classList.toggle('opacity-50', currentPage === 1);
        prevPageBtn.classList.toggle('cursor-not-allowed', currentPage === 1);
        nextPageBtn.classList.toggle('opacity-50', currentPage === totalPages);
        nextPageBtn.classList.toggle('cursor-not-allowed', currentPage === totalPages);

        const maxPagesToShow = 3;
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        if (startPage > 1) {
            createPageLink(1);
            if (startPage > 2) createEllipsis();
        }

        for (let i = startPage; i <= endPage; i++) {
            createPageLink(i, i === currentPage);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) createEllipsis();
            createPageLink(totalPages);
        }
    };

    const createPageLink = (pageNumber, isActive = false) => {
        const link = document.createElement('a');
        link.href = '#';
        link.textContent = pageNumber;
        link.classList.add(
            'flex', 'items-center', 'justify-center',
            'w-8', 'h-8', 'rounded-full', 'text-gray-700',
            'hover:bg-orange-50', 'hover:text-orange-600', 'transition'
        );
        if (isActive) {
            link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            link.classList.remove('hover:bg-orange-50', 'hover:text-orange-600', 'text-gray-700');
        }
        link.addEventListener('click', (e) => {
            e.preventDefault();
            goToPage(pageNumber);
        });
        paginationNumbersDiv.appendChild(link);
    }

    const createEllipsis = () => {
        const ellipsisSpan = document.createElement('span');
        ellipsisSpan.textContent = '...';
        ellipsisSpan.classList.add(
            'flex', 'items-center', 'justify-center',
            'w-8', 'h-8', 'text-gray-500'
        );
        paginationNumbersDiv.appendChild(ellipsisSpan);
    }

    const goToPage = (page) => {
        const totalPages = Math.ceil(allBackupFiles.length / itemsPerPage);
        currentPage = Math.min(Math.max(1, page), totalPages);

        try {
            sessionStorage.setItem('backupPage', currentPage);
        } catch (e) {
            console.error("SessionStorage Error:", e);
        }

        const start = (currentPage - 1) * itemsPerPage;
        const filesToShow = allBackupFiles.slice(start, start + itemsPerPage);
        renderBackupFiles(filesToShow);
        renderPagination(allBackupFiles.length, itemsPerPage, currentPage);
    };

    const fetchBackupFiles = async () => {
        // 1. 🟠 START LOADING SWEETALERT MODAL
        const startTime = Date.now();
        if (typeof showLoadingModal !== 'undefined') {
             showLoadingModal("Loading Backup History...", "Retrieving file list.");
        }
        showLoadingState(); // Keep default table loading temporarily

        try {
            const res = await fetch('api/superadmin/backup/logs');
            const data = await res.json();
            
            // 2. CLOSE LOADING with minimum delay
            const elapsed = Date.now() - startTime;
            const minDelay = 500;
            if (elapsed < minDelay) await new Promise(r => setTimeout(r, minDelay - elapsed));
            if (typeof Swal != 'undefined') Swal.close();
            

            if (data.success && Array.isArray(data.logs)) {
                allBackupFiles = data.logs.map(log => ({
                    fileName: log.file_name,
                    type: log.file_type,
                    size: log.size || 'N/A',
                    createdDate: log.created_at,
                    createdByName: log.created_by_name, // Assume backend sends this
                    downloadLink: `${BASE_URL_JS}/api/superadmin/backup/secure_download/${encodeURIComponent(log.file_name)}`
                }));

                goToPage(currentPage);
            } else {
                showErrorState('Failed to load backup logs.');
            }
        } catch (err) {
            console.error('Network error fetching logs:', err);
            // 3. CLOSE LOADING and SHOW ERROR
            showErrorState('Network error while fetching logs.');
        }
    };


    const secureDownload = async (url, filename) => {
        // 🟠 Loading for download (hindi kasama sa request mo, pero importante ito)
        showLoadingModal("Preparing Download...", `Starting download for ${filename}.`);
        const startTime = Date.now();

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
            
            const elapsed = Date.now() - startTime;
            if (elapsed < 300) await new Promise(r => setTimeout(r, 300 - elapsed));
            if (typeof Swal != 'undefined') Swal.close();

        } catch (err) {
            // Error handling para sa download
            if (typeof Swal != 'undefined') Swal.close();
            console.error('Download error:', err);
            alert('Failed to download backup file.');
        }
    };

    backupButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const type = btn.dataset.type;
            if (!type) return;
            
            // 🟠 Loading modal for generating backup
            showLoadingModal("Generating Backup...", "This may take a moment.");
            const startTime = Date.now();
            
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ph ph-circle-notch animate-spin"></i> Generating...';
            btn.disabled = true;
            btn.classList.add('opacity-70');

            const exportUrl = type === 'full_sql'
                ? `${BASE_URL_JS}/api/superadmin/backup/database/full`
                : `${BASE_URL_JS}/api/superadmin/backup/export/zip/${type}`;

            try {
                const exportRes = await fetch(exportUrl);
                if (!exportRes.ok) throw new Error('Server error during backup.');

                const exportData = await exportRes.json();
                if (!exportData.success || !exportData.filename) throw new Error(exportData.message || 'Failed to create backup.');

                // 4. CLOSE GENERATION LOADING
                const elapsed = Date.now() - startTime;
                if (elapsed < 500) await new Promise(r => setTimeout(r, 500 - elapsed)); // Ensure modal stays briefly
                if (typeof Swal != 'undefined') Swal.close();
                
                const downloadUrl = `${BASE_URL_JS}/api/superadmin/backup/secure_download/${encodeURIComponent(exportData.filename)}`;
                
                // secureDownload will handle its own loading modal
                await secureDownload(downloadUrl, exportData.filename);
                
                alert(`Backup for ${type.toUpperCase()} downloaded successfully.`);
                await fetchBackupFiles();

            } catch (err) {
                // CLOSE LOADING on catch
                if (typeof Swal != 'undefined') Swal.close();
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