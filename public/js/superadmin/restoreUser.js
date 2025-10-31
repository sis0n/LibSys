document.addEventListener('DOMContentLoaded', () => {
    const userSearchInput = document.getElementById('userSearchInput');
    const roleFilterDropdownBtn = document.getElementById('roleFilterDropdownBtn');
    const roleFilterDropdownMenu = document.getElementById('roleFilterDropdownMenu');
    const roleFilterDropdownSpan = roleFilterDropdownBtn.querySelector('span');
    const deletedUserDateFilter = document.getElementById('deletedUserDateFilter');

    const deletedUsersTableBody = document.getElementById('deletedUsersTableBody');
    const deletedUserRowTemplate = document.getElementById('deleted-user-row-template').content;
    const deletedUsersCount = document.getElementById('deletedUsersCount');
    const noDeletedUsersFound = document.getElementById('noDeletedUsersFound');

    const paginationContainer = document.getElementById('pagination-container');
    const paginationNumbersDiv = document.getElementById('pagination-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const csrfTokenInput = document.getElementById('csrf_token');

    let allDeletedUsers = [];

    let currentSearchTerm = '';
    let currentRoleFilter = 'All Users';
    let currentDateFilter = '';
    let filteredUsers = [];
    const itemsPerPage = 10;
    let currentPage = 1;

    const userDetailsModal = document.getElementById('userDetailsModal');
    const closeUserDetailsModalBtn = document.getElementById('closeUserDetailsModalBtn');
    const modalUserFullName = document.getElementById('modalUserFullName');
    const modalUsername = document.getElementById('modalUsername');
    const modalUserRole = document.getElementById('modalUserRole');
    const modalUserEmail = document.getElementById('modalUserEmail');
    const modalContact = document.getElementById('modalContact');
    const modalUserCreatedDate = document.getElementById('modalUserCreatedDate');
    const modalUserDeletedDate = document.getElementById('modalUserDeletedDate');
    const modalUserDeletedBy = document.getElementById('modalUserDeletedBy');

    async function fetchDeletedUsers() {
        showLoadingState();
        try {
            const response = await fetch('restoreUser/fetch');
            const data = await response.json();

            if (data.success && Array.isArray(data.users)) {
                allDeletedUsers = data.users;
                applyFiltersAndRender();
            } else {
                console.error('Error fetching deleted users:', data.message);
                showErrorState('Failed to load deleted users.');
                allDeletedUsers = [];
                applyFiltersAndRender();
            }
        } catch (error) {
            console.error('Network error fetching deleted users:', error);
            showErrorState('Network error. Could not load deleted users.');
            allDeletedUsers = [];
            applyFiltersAndRender();
        }
    }

    function showLoadingState() {
        deletedUsersTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">Loading...</td></tr>';
        noDeletedUsersFound.classList.add('hidden');
        paginationContainer.classList.add('hidden');
    }

    function showErrorState(message) {
        deletedUsersTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-10 text-red-500">${message}</td></tr>`;
        noDeletedUsersFound.classList.add('hidden');
        paginationContainer.classList.add('hidden');
        deletedUsersCount.textContent = 0;
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
        } catch (e) {
            return dateString;
        }
    }

    function renderDeletedUsers(usersToRender) {
        deletedUsersTableBody.innerHTML = '';
        deletedUsersCount.textContent = filteredUsers.length;

        if (usersToRender.length === 0) {
            if (filteredUsers.length === 0 && !currentSearchTerm && currentRoleFilter === 'All Users' && !currentDateFilter) {
                noDeletedUsersFound.textContent = 'No deleted users found.';
                noDeletedUsersFound.classList.remove('hidden');
            } else if (filteredUsers.length === 0) {
                noDeletedUsersFound.textContent = 'No deleted users found matching filters.';
                noDeletedUsersFound.classList.remove('hidden');
            } else {
                deletedUsersTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">No users found on this page.</td></tr>';
                noDeletedUsersFound.classList.add('hidden');
            }
            paginationContainer.classList.add('hidden');
            return;
        }
        noDeletedUsersFound.classList.add('hidden');
        paginationContainer.classList.remove('hidden');

        usersToRender.forEach(user => {
            const newRow = deletedUserRowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.userId = user.id;

            newRow.querySelector('.user-fullname').textContent = user.fullname || 'N/A';
            newRow.querySelector('.user-username').textContent = user.username || 'N/A';
            newRow.querySelector('.user-role').textContent = user.role || 'N/A';
            newRow.querySelector('.user-deleted-date').textContent = formatDate(user.deleted_date);
            newRow.querySelector('.user-deleted-by').textContent = user.deleted_by_name || 'Unknown';

            const restoreBtn = newRow.querySelector('.restore-btn');
            restoreBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                if (confirm(`Are you sure you want to restore ${user.fullname || user.username}?`)) {
                    restoreBtn.disabled = true;
                    restoreBtn.classList.add('opacity-50');
                    try {
                        const response = await fetch('restoreUser/restore', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfTokenInput ? csrfTokenInput.value : ''
                            },
                            body: JSON.stringify({ user_id: user.id })
                        });
                        const result = await response.json();
                        if (result.success) {
                            alert(result.message);
                            fetchDeletedUsers();
                        } else {
                            alert(`Restore failed: ${result.message}`);
                            restoreBtn.disabled = false;
                            restoreBtn.classList.remove('opacity-50');
                        }
                    } catch (error) {
                        console.error('Error restoring user:', error);
                        alert('An error occurred during restoration.');
                        restoreBtn.disabled = false;
                        restoreBtn.classList.remove('opacity-50');
                    }
                }
            });

            const archiveBtn = newRow.querySelector('.archive-btn');
            archiveBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                if (confirm(`ARCHIVE ${user.fullname || user.username}? This will copy the record to the archive tables for reporting.`)) {
                    archiveBtn.disabled = true;
                    archiveBtn.classList.add('opacity-50');
                    try {
                        const response = await fetch(`restoreUser/delete/${user.id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfTokenInput ? csrfTokenInput.value : ''
                            }
                        });
                        const result = await response.json();
                        if (result.success) {
                            alert(result.message);
                            fetchDeletedUsers();
                        } else {
                            alert(`Archive failed: ${result.message}`);
                            archiveBtn.disabled = false;
                            archiveBtn.classList.remove('opacity-50');
                        }
                    } catch (error) {
                        console.error('Error archiving user:', error);
                        alert('An error occurred during archiving.');
                        archiveBtn.disabled = false;
                        archiveBtn.classList.remove('opacity-50');
                    }
                }
            });

            deletedUsersTableBody.appendChild(newRow);
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
    }

    function createPageLink(pageNumber, isActive = false) {
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

    function createEllipsis() {
        const ellipsisSpan = document.createElement('span');
        ellipsisSpan.textContent = '...';
        ellipsisSpan.classList.add(
            'flex', 'items-center', 'justify-center',
            'w-8', 'h-8', 'text-gray-500'
        );
        paginationNumbersDiv.appendChild(ellipsisSpan);
    }

    function goToPage(page) {
        const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
        currentPage = Math.max(1, Math.min(page, totalPages));

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const usersToRender = filteredUsers.slice(startIndex, endIndex);
        renderDeletedUsers(usersToRender);
        renderPagination(filteredUsers.length, itemsPerPage, currentPage);
    }

    function applyFiltersAndRender() {
        let tempFiltered = allDeletedUsers.filter(user => {
            const searchLower = currentSearchTerm.toLowerCase();
            const matchesSearch = !currentSearchTerm ||
                (user.fullname && user.fullname.toLowerCase().includes(searchLower)) ||
                (user.username && user.username.toLowerCase().includes(searchLower)) ||
                (user.email && user.email.toLowerCase().includes(searchLower));

            const matchesRole = currentRoleFilter === 'All Users' || (user.role && user.role.toLowerCase() === currentRoleFilter.toLowerCase());

            let matchesDate = true;
            if (currentDateFilter && user.deleted_date) {
                try {
                    const deletedDate = new Date(user.deleted_date);
                    const filterDate = new Date(currentDateFilter + "T00:00:00");
                    matchesDate = deletedDate.getFullYear() === filterDate.getFullYear() &&
                        deletedDate.getMonth() === filterDate.getMonth() &&
                        deletedDate.getDate() === filterDate.getDate();
                } catch (e) {
                    matchesDate = false;
                    console.error("Date comparison error:", e);
                }
            } else if (currentDateFilter && !user.deleted_date) {
                matchesDate = false;
            }

            return matchesSearch && matchesRole && matchesDate;
        });
        filteredUsers = tempFiltered;
        goToPage(1);
    }

    function openUserDetailsModal(user) {
        modalUserFullName.textContent = user.fullname || 'N/A';
        modalUsername.textContent = user.username || 'N/A';
        modalUserRole.textContent = user.role || 'N/A';
        modalUserEmail.textContent = user.email || 'N/A';
        modalContact.textContent = user.contact || 'N/A';
        modalUserCreatedDate.textContent = formatDate(user.created_date);
        modalUserDeletedDate.textContent = formatDate(user.deleted_date);
        modalUserDeletedBy.textContent = user.deleted_by_name || 'Unknown';
        userDetailsModal.classList.remove('hidden');
    }

    function closeUserDetailsModal() {
        userDetailsModal.classList.add('hidden');
    }

    userSearchInput.addEventListener('input', (e) => {
        currentSearchTerm = e.target.value;
        applyFiltersAndRender();
    });

    roleFilterDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        roleFilterDropdownMenu.classList.toggle('hidden');
    });

    roleFilterDropdownMenu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            currentRoleFilter = item.dataset.value;
            roleFilterDropdownSpan.textContent = currentRoleFilter;
            roleFilterDropdownMenu.classList.add('hidden');
            applyFiltersAndRender();
        });
    });

    deletedUserDateFilter.addEventListener('change', (e) => {
        currentDateFilter = e.target.value;
        applyFiltersAndRender();
    });

    document.addEventListener('click', (e) => {
        if (!roleFilterDropdownBtn.contains(e.target) && !roleFilterDropdownMenu.contains(e.target)) {
            roleFilterDropdownMenu.classList.add('hidden');
        }
    });

    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    });

    deletedUsersTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.deleted-user-row');
        if (!row) return;

        if (e.target.closest('.restore-btn') || e.target.closest('.archive-btn')) {
            return;
        }

        const userId = parseInt(row.dataset.userId, 10);
        const user = allDeletedUsers.find(u => u.id === userId);
        if (user) {
            openUserDetailsModal(user);
        } else {
            console.error("Could not find user data for ID:", userId);
        }
    });

    closeUserDetailsModalBtn.addEventListener('click', closeUserDetailsModal);
    userDetailsModal.addEventListener('click', (e) => {
        if (e.target === userDetailsModal) {
            closeUserDetailsModal();
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !userDetailsModal.classList.contains('hidden')) {
            closeUserDetailsModal();
        }
    });

    fetchDeletedUsers();
});

