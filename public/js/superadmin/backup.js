document.addEventListener('DOMContentLoaded', () => {
    const backupFilesTableBody = document.getElementById('backupFilesTableBody');
    const backupFileRowTemplate = document.getElementById('backup-file-row-template').content;
    const backupFilesCount = document.getElementById('backupFilesCount');
    const noBackupFilesFound = document.getElementById('noBackupFilesFound');
    const paginationContainer = document.getElementById('pagination-container');
    const paginationNumbersDiv = document.getElementById('pagination-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');

    // Dummy data for backup files (expanded for pagination demo)
    const allBackupFiles = [
        {
            id: 1,
            fileName: 'full_backup_2025_10_20.zip',
            type: 'Full',
            size: '24.5 MB',
            createdDate: '10/20/2025, 2:30:00 PM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 2,
            fileName: 'users_backup_2025_10_18.zip',
            type: 'Users',
            size: '8.2 MB',
            createdDate: '10/18/2025, 9:15:00 AM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 3,
            fileName: 'books_backup_2025_10_15.zip',
            type: 'Books',
            size: '15.7 MB',
            createdDate: '10/15/2025, 4:45:00 PM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 4,
            fileName: 'equipment_backup_2025_10_15.zip',
            type: 'Equipment',
            size: '67.7 MB',
            createdDate: '10/15/2025, 4:45:00 PM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 5,
            fileName: 'full_backup_2025_10_19.zip',
            type: 'Full',
            size: '23.1 MB',
            createdDate: '10/19/2025, 1:00:00 PM',
            createdBy: 'Admin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 6,
            fileName: 'users_backup_2025_10_17.zip',
            type: 'Users',
            size: '7.9 MB',
            createdDate: '10/17/2025, 10:00:00 AM',
            createdBy: 'Admin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 7,
            fileName: 'books_backup_2025_10_14.zip',
            type: 'Books',
            size: '14.2 MB',
            createdDate: '10/14/2025, 3:30:00 PM',
            createdBy: 'Dr. Maria Santos',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 8,
            fileName: 'equipment_backup_2025_10_14.zip',
            type: 'Equipment',
            size: '65.0 MB',
            createdDate: '10/14/2025, 3:30:00 PM',
            createdBy: 'Dr. Maria Santos',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 9,
            fileName: 'full_backup_2025_10_18.zip',
            type: 'Full',
            size: '22.8 MB',
            createdDate: '10/18/2025, 11:00:00 AM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 10,
            fileName: 'users_backup_2025_10_16.zip',
            type: 'Users',
            size: '8.0 MB',
            createdDate: '10/16/2025, 9:00:00 AM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 11,
            fileName: 'books_backup_2025_10_13.zip',
            type: 'Books',
            size: '15.0 MB',
            createdDate: '10/13/2025, 2:00:00 PM',
            createdBy: 'Admin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 12,
            fileName: 'equipment_backup_2025_10_13.zip',
            type: 'Equipment',
            size: '66.5 MB',
            createdDate: '10/13/2025, 2:00:00 PM',
            createdBy: 'Admin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 13,
            fileName: 'full_backup_2025_10_17.zip',
            type: 'Full',
            size: '24.0 MB',
            createdDate: '10/17/2025, 10:30:00 AM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 14,
            fileName: 'users_backup_2025_10_15.zip',
            type: 'Users',
            size: '8.1 MB',
            createdDate: '10/15/2025, 8:30:00 AM',
            createdBy: 'SuperAdmin',
            downloadLink: '#',
            deleteLink: '#'
        },
        {
            id: 15,
            fileName: 'books_backup_2025_10_12.zip',
            type: 'Books',
            size: '14.8 MB',
            createdDate: '10/12/2025, 1:00:00 PM',
            createdBy: 'Dr. Maria Santos',
            downloadLink: '#',
            deleteLink: '#'
        },
    ];

    const itemsPerPage = 10;
    let currentPage = 1;

    function renderBackupFiles(files) {
        backupFilesTableBody.innerHTML = ''; // Clear existing rows
        backupFilesCount.textContent = allBackupFiles.length; // Total count, not just current page

        if (files.length === 0) {
            noBackupFilesFound.classList.remove('hidden');
            return;
        }
        noBackupFilesFound.classList.add('hidden');

        files.forEach(file => {
            const newRow = backupFileRowTemplate.cloneNode(true);
            newRow.querySelector('.file-name').textContent = file.fileName;
            newRow.querySelector('.file-size').textContent = file.size;
            newRow.querySelector('.created-date').textContent = file.createdDate;
            newRow.querySelector('.created-by').textContent = file.createdBy;

            const typeBadge = newRow.querySelector('.file-type-badge');
            typeBadge.textContent = file.type;
            // Apply color based on type
            if (file.type === 'Full') {
                typeBadge.classList.add('bg-purple-100', 'text-purple-800');
            } else if (file.type === 'Users') {
                typeBadge.classList.add('bg-green-100', 'text-green-800');
            } else if (file.type === 'Books') {
                typeBadge.classList.add('bg-blue-100', 'text-blue-800');
            } else if (file.type === 'Equipment') {
                typeBadge.classList.add('bg-red-100', 'text-red-800');
            } else { // Default or other types
                typeBadge.classList.add('bg-gray-100', 'text-gray-800');
            }

            const downloadBtn = newRow.querySelector('.download-btn');
            downloadBtn.href = file.downloadLink;
            downloadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                alert(`Downloading ${file.fileName}`);
                // Implement actual download logic here
            });

            const deleteBtn = newRow.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm(`Are you sure you want to delete ${file.fileName}?`)) {
                    alert(`Deleting ${file.fileName}`);
                    // Implement actual delete logic here
                }
            });

            backupFilesTableBody.appendChild(newRow);
        });
    }

    function renderPagination(totalItems, itemsPerPage, currentPage) {
        paginationNumbersDiv.innerHTML = ''; 
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalItems <= itemsPerPage || totalPages <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }
        paginationContainer.classList.remove('hidden');

        // Update Previous button state
        if (currentPage === 1) {
            prevPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        // Update Next button state
        if (currentPage === totalPages) {
            nextPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        // Page numbers
        const maxPagesToShow = 3; 
        let startPage = Math.max(1, currentPage - 1);
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, totalPages - maxPagesToShow + 1);
        }
        startPage = Math.max(1, startPage);

        if (startPage > 1) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = '1';
            link.classList.add(
                'flex', 'items-center', 'justify-center', 'w-8', 'h-8',
                'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition'
            );
            if (1 === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(1); });
            paginationNumbersDiv.appendChild(li).appendChild(link);

            if (startPage > 2) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add(
                    'flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'text-gray-500'
                );
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }
        }

        // Middle pages
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = i;
            link.classList.add(
                'flex', 'items-center', 'justify-center', 'w-8', 'h-8',
                'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition'
            );
            if (i === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(i); });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add(
                    'flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'text-gray-500'
                );
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = totalPages;
            link.classList.add(
                'flex', 'items-center', 'justify-center', 'w-8', 'h-8',
                'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition'
            );
            if (totalPages === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(totalPages); });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }
    }

    function goToPage(page) {
        currentPage = page;
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const filesToRender = allBackupFiles.slice(startIndex, endIndex);
        renderBackupFiles(filesToRender);
        renderPagination(allBackupFiles.length, itemsPerPage, currentPage);
    }

    // Event listeners for Previous/Next buttons
    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < Math.ceil(allBackupFiles.length / itemsPerPage)) {
            goToPage(currentPage + 1);
        }
    });

    // Initial load of backup files and pagination
    goToPage(currentPage);
});