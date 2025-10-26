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

    // Dummy Data
    const allDeletedUsers = [
        { id: 1, fullname: 'John Doeasdfasdfasd fasdfasdfasfad asdfasdfasdfasdfasdfas', username: 'johndoe', role: 'Student', email: 'john.doe@example.com',contact:'09090909091', status: 'Active', created_date: '2023-01-15', deleted_date: '2025-10-20', deleted_by: 'Admin' },
        { id: 2, fullname: 'Jane Smith', username: 'janesmith', role: 'Faculty', email: 'jane.smith@example.com',contact:'09090909091', status: 'Inactive', created_date: '2022-11-20', deleted_date: '2025-10-22', deleted_by: 'SuperAdmin' },
        { id: 3, fullname: 'Peter Jones', username: 'peterjones', role: 'Staff', email: 'peter.jones@example.com',contact:'09090909091', status: 'Active', created_date: '2023-03-10', deleted_date: '2025-09-15', deleted_by: 'Admin' },
        { id: 4, fullname: 'Mary Jane', username: 'maryjane', role: 'Librarian', email: 'mary.jane@example.com',contact:'09090909091', status: 'Active', created_date: '2021-05-20', deleted_date: '2025-08-20', deleted_by: 'SuperAdmin' },
        { id: 5, fullname: 'Chris Lee', username: 'chrislee', role: 'Admin', email: 'chris.lee@example.com',contact:'09090909091', status: 'Active', created_date: '2020-02-10', deleted_date: '2025-07-11', deleted_by: 'SuperAdmin' },
        { id: 1, fullname: 'John Doe', username: 'johndoe', role: 'Student', email: 'john.doe@example.com',contact:'09090909091', status: 'Active', created_date: '2023-01-15', deleted_date: '2025-10-20', deleted_by: 'Admin' },
        { id: 2, fullname: 'Jane Smith', username: 'janesmith', role: 'Faculty', email: 'jane.smith@example.com',contact:'09090909091', status: 'Inactive', created_date: '2022-11-20', deleted_date: '2025-10-22', deleted_by: 'SuperAdmin' },
        { id: 3, fullname: 'Peter Jones', username: 'peterjones', role: 'Staff', email: 'peter.jones@example.com',contact:'09090909091', status: 'Active', created_date: '2023-03-10', deleted_date: '2025-09-15', deleted_by: 'Admin' },
        { id: 4, fullname: 'Mary Jane', username: 'maryjane', role: 'Librarian', email: 'mary.jane@example.com',contact:'09090909091', status: 'Active', created_date: '2021-05-20', deleted_date: '2025-08-20', deleted_by: 'SuperAdmin' },
        { id: 5, fullname: 'Chris Lee', username: 'chrislee', role: 'Admin', email: 'chris.lee@example.com',contact:'09090909091', status: 'Active', created_date: '2020-02-10', deleted_date: '2025-07-11', deleted_by: 'SuperAdmin' },
        { id: 1, fullname: 'John Doe', username: 'johndoe', role: 'Student', email: 'john.doe@example.com',contact:'09090909091', status: 'Active', created_date: '2023-01-15', deleted_date: '2025-10-20', deleted_by: 'Admin' },
        { id: 2, fullname: 'Jane Smith', username: 'janesmith', role: 'Faculty', email: 'jane.smith@example.com',contact:'09090909091', status: 'Inactive', created_date: '2022-11-20', deleted_date: '2025-10-22', deleted_by: 'SuperAdmin' },
        { id: 3, fullname: 'Peter Jones', username: 'peterjones', role: 'Staff', email: 'peter.jones@example.com',contact:'09090909091', status: 'Active', created_date: '2023-03-10', deleted_date: '2025-09-15', deleted_by: 'Admin' },
        { id: 4, fullname: 'Mary Jane', username: 'maryjane', role: 'Librarian', email: 'mary.jane@example.com',contact:'09090909091', status: 'Active', created_date: '2021-05-20', deleted_date: '2025-08-20', deleted_by: 'SuperAdmin' },
        { id: 5, fullname: 'Chris Lee', username: 'chrislee', role: 'Admin', email: 'chris.lee@example.com',contact:'09090909091', status: 'Active', created_date: '2020-02-10', deleted_date: '2025-07-11', deleted_by: 'SuperAdmin' },
        
    ];

    let currentSearchTerm = '';
    let currentRoleFilter = 'All Users';
    let currentDateFilter = '';
    let filteredUsers = [...allDeletedUsers];
    const itemsPerPage = 10;
    let currentPage = 1;

    // --- MODAL ELEMENTS ---
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

    function renderDeletedUsers(users) {
        deletedUsersTableBody.innerHTML = '';
        deletedUsersCount.textContent = filteredUsers.length;

        if (users.length === 0) {
            noDeletedUsersFound.classList.remove('hidden');
            paginationContainer.classList.add('hidden');
            return;
        }
        noDeletedUsersFound.classList.add('hidden');

        users.forEach(user => {
            const newRow = deletedUserRowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.userId = user.id;

            newRow.querySelector('.user-fullname').textContent = user.fullname;
            newRow.querySelector('.user-username').textContent = user.username;
            newRow.querySelector('.user-role').textContent = user.role;
            newRow.querySelector('.user-deleted-date').textContent = user.deleted_date;
            newRow.querySelector('.user-deleted-by').textContent = user.deleted_by;

            const restoreBtn = newRow.querySelector('.restore-btn');
            restoreBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                alert(`Restoring ${user.fullname} (ID: ${user.id})`);
            });

            const deleteBtn = newRow.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (confirm(`Are you sure you want to permanently delete ${user.fullname} (ID: ${user.id})?`)) {
                    alert(`Permanently deleting ${user.fullname}`);
                }
            });

            deletedUsersTableBody.appendChild(newRow);
        });
    }

    function renderPagination(totalItems, itemsPerPage, currentPage) {
        paginationNumbersDiv.innerHTML = ''; // Clear existing page numbers
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalItems <= itemsPerPage || totalPages <= 1) {
            paginationContainer.classList.add('hidden');
            return;
        }

        paginationContainer.classList.remove('hidden');

        if (currentPage === 1) {
            prevPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        if (currentPage === totalPages) {
            nextPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            nextPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
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
                'flex', 'items-center', 'justify-center',
                'w-8', 'h-8', 'rounded-full', 'text-gray-700',
                'hover:bg-gray-100', 'transition'
            );
            if (1 === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(1);
            });
            paginationNumbersDiv.appendChild(li).appendChild(link);

            if (startPage > 2) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add(
                    'flex', 'items-center', 'justify-center',
                    'w-8', 'h-8', 'text-gray-500'
                );
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = i;
            link.classList.add(
                'flex', 'items-center', 'justify-center',
                'w-8', 'h-8', 'rounded-full', 'text-gray-700',
                'hover:bg-gray-100', 'transition'
            );
            if (i === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(i);
            });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }

        // ✅ Always show last page if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add(
                    'flex', 'items-center', 'justify-center',
                    'w-8', 'h-8', 'text-gray-500'
                );
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }

            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = totalPages;
            link.classList.add(
                'flex', 'items-center', 'justify-center',
                'w-8', 'h-8', 'rounded-full', 'text-gray-700',
                'hover:bg-gray-100', 'transition'
            );
            if (totalPages === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(totalPages);
            });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }
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

    function filterAndSearchUsers() {
        let filtered = allDeletedUsers.filter(user => {
            const matchesSearch = user.fullname.toLowerCase().includes(currentSearchTerm.toLowerCase()) ||
                                  user.username.toLowerCase().includes(currentSearchTerm.toLowerCase()) ||
                                  user.email.toLowerCase().includes(currentSearchTerm.toLowerCase());

            const matchesRole = currentRoleFilter === 'All Users' || user.role === currentRoleFilter;

            const matchesDate = !currentDateFilter || (new Date(user.deleted_date).toDateString() === new Date(currentDateFilter).toDateString());

            return matchesSearch && matchesRole && matchesDate;
        });
        filteredUsers = filtered;
        goToPage(1);
    }

    // --- MODAL MANAGEMENT ---
    function openUserDetailsModal(user) {
        modalUserFullName.textContent = user.fullname || 'N/A';
        modalUsername.textContent = user.username || 'N/A';
        modalUserRole.textContent = user.role || 'N/A';
        modalUserEmail.textContent = user.email || 'N/A';
        modalContact.textContent = user.contact || 'N/A';
        modalUserCreatedDate.textContent = user.created_date || 'N/A';
        modalUserDeletedDate.textContent = user.deleted_date || 'N/A';
        modalUserDeletedBy.textContent = user.deleted_by || 'N/A';
        userDetailsModal.classList.remove('hidden');
    }

    function closeUserDetailsModal() {
        userDetailsModal.classList.add('hidden');
    }

    // --- EVENT LISTENERS ---
    userSearchInput.addEventListener('input', (e) => {
        currentSearchTerm = e.target.value;
        filterAndSearchUsers();
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
            filterAndSearchUsers();
        });
    });

    deletedUserDateFilter.addEventListener('change', (e) => {
        currentDateFilter = e.target.value;
        filterAndSearchUsers();
    });

    document.addEventListener('click', () => {
        roleFilterDropdownMenu.classList.add('hidden');
    });

    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        goToPage(currentPage - 1);
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        goToPage(currentPage + 1);
    });

    deletedUsersTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.deleted-user-row');
        if (!row) return;

        if (e.target.closest('.restore-btn') || e.target.closest('.delete-btn')) {
            return;
        }

        const userId = parseInt(row.dataset.userId, 10);
        const user = allDeletedUsers.find(u => u.id === userId);
        if (user) {
            openUserDetailsModal(user);
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

    // Initial load
    filterAndSearchUsers();
});
