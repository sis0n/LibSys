document.addEventListener('DOMContentLoaded', () => {
    const usersTabBtn = document.getElementById('usersTabBtn');
    const booksTabBtn = document.getElementById('booksTabBtn');
    const usersContent = document.getElementById('usersContent');
    const booksContent = document.getElementById('booksContent');

    const deletedUsersTableBody = document.getElementById('deletedUsersTableBody');
    const userRowTemplate = document.getElementById('user-row-template').content;

    const userDetailsModal = document.getElementById('userDetailsModal');
    const closeUserDetailsModalBtn = document.getElementById('closeUserDetailsModalBtn');

    // Dummy data for deleted users
    const deletedUsers = [{
            id: 1,
            fullName: 'Michael Johnson',
            username: '20231234-S',
            role: 'student',
            email: 'michael.johnson@student.university.edu',
            createdDate: '8/15/2024, 8:00:00 AM',
            deletedDate: '8/1/2025, 8:00:00 AM',
            deletedBy: 'Admin',
            status: 'active'
        },
        {
            id: 2,
            fullName: 'Lisa Wang',
            username: 'Librarian1',
            role: 'librarian',
            email: 'lisa.wang@university.edu',
            createdDate: '7/20/2024, 9:30:00 AM',
            deletedDate: '7/30/2025, 10:00:00 AM',
            deletedBy: 'Dr. Maria Santos',
            status: 'active'
        },
        {
            id: 3,
            fullName: 'David Kim',
            username: 'AdminUser',
            role: 'admin',
            email: 'lisa.wang@university.edu',
            createdDate: '7/20/2024, 9:30:00 AM',
            deletedDate: '7/30/2025, 10:00:00 AM',
            deletedBy: 'Dr. Maria Santos',
            status: 'active'
        }
    ];

    // Function to set active tab styling
    function setActiveTab(activeTab) {
        // Reset all tab buttons to inactive state
        usersTabBtn.classList.remove('bg-white', 'shadow-sm', 'font-semibold', 'text-gray-800');
        usersTabBtn.classList.add('font-medium', 'text-gray-500');
        booksTabBtn.classList.remove('bg-white', 'shadow-sm', 'font-semibold', 'text-gray-800');
        booksTabBtn.classList.add('font-medium', 'text-gray-500');

        // Set active tab styling
        if (activeTab === 'users') {
            usersTabBtn.classList.add('bg-white', 'shadow-sm', 'font-semibold', 'text-gray-800');
            usersTabBtn.classList.remove('font-medium', 'text-gray-500');
            usersContent.classList.remove('hidden');
            booksContent.classList.add('hidden');
            renderUsersTable(deletedUsers); // Render users table when users tab is active
        } else if (activeTab === 'books') {
            booksTabBtn.classList.add('bg-white', 'shadow-sm', 'font-semibold', 'text-gray-800');
            booksTabBtn.classList.remove('font-medium', 'text-gray-500');
            booksContent.classList.remove('hidden');
            usersContent.classList.add('hidden');
            // renderBooksTable(deletedBooks); // Call a function to render books table when books tab is active
        }
    }

    // Function to render the users table
    function renderUsersTable(users) {
        deletedUsersTableBody.innerHTML = ''; // Clear existing rows
        users.forEach(user => {
            const newRow = userRowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.userId = user.id;

            newRow.querySelector('.user-full-name').textContent = user.fullName;
            newRow.querySelector('.user-username').textContent = user.username;
            newRow.querySelector('.user-role').textContent = user.role;
            newRow.querySelector('.user-status').textContent = user.status;
            newRow.querySelector('.user-deleted-date').textContent = user.deletedDate;
            newRow.querySelector('.user-deleted-by').textContent = user.deletedBy;

            // Apply role-based styling
            const roleSpan = newRow.querySelector('.user-role');
            if (user.role === 'admin') {
                roleSpan.classList.add('bg-orange-500', 'text-white');
            } else if (user.role === 'librarian') {
                roleSpan.classList.add('bg-amber-500', 'text-white');
            } else if (user.role === 'student') {
                roleSpan.classList.add('bg-green-500', 'text-white');
            }

            // Apply status-based styling
            const statusSpan = newRow.querySelector('.user-status');
            if (user.status === 'active') {
                statusSpan.classList.add('bg-green-500', 'text-white');
            } else if (user.status === 'inactive') {
                statusSpan.classList.add('bg-orange-500', 'text-white');
            }

            deletedUsersTableBody.appendChild(newRow);
        });
    }

    // Function to populate and show user details modal
    function populateUserDetailsModal(user) {
        document.getElementById('modalUserFullName').textContent = user.fullName;
        document.getElementById('modalUsername').textContent = user.username;
        document.getElementById('modalUserRole').textContent = user.role;
        document.getElementById('modalUserEmail').textContent = user.email;
        document.getElementById('modalUserCreatedDate').textContent = user.createdDate;
        document.getElementById('modalUserDeletedDate').textContent = user.deletedDate;
        document.getElementById('modalUserDeletedBy').textContent = user.deletedBy;
        userDetailsModal.classList.remove('hidden');
    }

    // Event Listeners for tab buttons
    usersTabBtn.addEventListener('click', () => setActiveTab('users'));
    booksTabBtn.addEventListener('click', () => setActiveTab('books'));

    // Event listener for user table rows (to open modal)
    deletedUsersTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.user-row');
        if (row) {
            const userId = parseInt(row.dataset.userId, 10);
            const user = deletedUsers.find(u => u.id === userId);
            if (user) {
                populateUserDetailsModal(user);
            }
        }
    });

    // Event listeners for closing user details modal
    closeUserDetailsModalBtn.addEventListener('click', () => {
        userDetailsModal.classList.add('hidden');
    });

    userDetailsModal.addEventListener('click', (e) => {
        if (e.target === userDetailsModal) {
            userDetailsModal.classList.add('hidden');
        }
    });

    // Initial active tab (default to users)
    setActiveTab('users');

    // Dropdown logic for User Roles
    const userRoleDropdownBtn = document.getElementById('userRoleDropdownBtn');
    const userRoleDropdownMenu = document.getElementById('userRoleDropdownMenu');
    const userRoleDropdownSpan = userRoleDropdownBtn.querySelector('span');

    userRoleDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userRoleDropdownMenu.classList.toggle('hidden');
    });

    userRoleDropdownMenu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            userRoleDropdownSpan.textContent = item.dataset.value;
            userRoleDropdownMenu.classList.add('hidden');
        });
    });

    // Dropdown logic for Book Categories
    const bookCategoryDropdownBtn = document.getElementById('bookCategoryDropdownBtn');
    const bookCategoryDropdownMenu = document.getElementById('bookCategoryDropdownMenu');
    const bookCategoryDropdownSpan = bookCategoryDropdownBtn.querySelector('span');

    bookCategoryDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        bookCategoryDropdownMenu.classList.toggle('hidden');
    });

    bookCategoryDropdownMenu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            bookCategoryDropdownSpan.textContent = item.dataset.value;
            bookCategoryDropdownMenu.classList.add('hidden');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        userRoleDropdownMenu.classList.add('hidden');
        bookCategoryDropdownMenu.classList.add('hidden');
    });
});