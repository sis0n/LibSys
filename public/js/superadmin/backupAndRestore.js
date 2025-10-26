document.addEventListener('DOMContentLoaded', () => {
    // Tabs
    const backupTabBtn = document.getElementById('backupTabBtn');
    const restoreTabBtn = document.getElementById('restoreTabBtn');
    const backupContent = document.getElementById('backupContent');
    const restoreContent = document.getElementById('restoreContent');

    // Search and Filter
    const searchInput = document.getElementById('searchInput');
    const typeDropdownBtn = document.getElementById('typeDropdownBtn');
    const typeDropdownMenu = document.getElementById('typeDropdownMenu');
    const typeDropdownSpan = typeDropdownBtn.querySelector('span');

    // Table
    const deletedItemsTableBody = document.getElementById('deletedItemsTableBody');
    const itemRowTemplate = document.getElementById('item-row-template').content;

    // Modals
    const userDetailsModal = document.getElementById('userDetailsModal');
    const closeUserDetailsModalBtn = document.getElementById('closeUserDetailsModalBtn');
    const bookDetailsModal = document.getElementById('bookDetailsModal');
    const closeBookDetailsModalBtn = document.getElementById('closeBookDetailsModalBtn');

    let currentFilter = 'Users';

    // --- DUMMY DATA ---
    const deletedUsers = [{
            id: 1,
            type: 'user',
            fullName: 'Michael Johnson',
            username: '20231234-S',
            role: 'student',
            email: 'michael.johnson@student.university.edu',
            createdDate: '8/15/2024, 8:00:00 AM',
            deleted_date: '8/1/2025, 8:00:00 AM',
            deleted_by: 'Admin',
        },
        {
            id: 2,
            type: 'user',
            fullName: 'Lisa Wang',
            username: 'Librarian1',
            role: 'librarian',
            email: 'lisa.wang@university.edu',
            createdDate: '7/20/2024, 9:30:00 AM',
            deleted_date: '7/30/2025, 10:00:00 AM',
            deleted_by: 'Dr. Maria Santos',
        },
    ];

    const deletedBooks = [{
            id: 1,
            type: 'book',
            title: "The Hitchhiker's Guide to the Galaxy",
            author: 'Douglas Adams',
            accession_number: 'AN-12345',
            call_number: 'CN-67890',
            publisher: 'Megadodo Publications',
            year: '1979',
            isbn: '0-345-39180-2',
            subject: 'Science Fiction, Comedy',
            place_of_publication: 'Ursa Minor Beta',
            created_date: '10/10/2020, 11:00:00 AM',
            deleted_date: '10/25/2025, 5:00:00 PM',
            deleted_by: 'SuperAdmin'
        },
        {
            id: 2,
            type: 'book',
            title: 'A Brief History of Time',
            author: 'Stephen Hawking',
            accession_number: 'AN-54321',
            call_number: 'CN-09876',
            publisher: 'Bantam Dell Publishing Group',
            year: '1988',
            isbn: '0-553-10953-7',
            subject: 'Cosmology, Physics',
            place_of_publication: 'United States',
            created_date: '01/15/2018, 10:00:00 AM',
            deleted_date: '09/15/2025, 3:15:00 PM',
            deleted_by: 'Admin'
        },
    ];

    // --- TAB MANAGEMENT ---
    function setActiveTab(tab) {
        const isRestore = tab === 'restore';

        restoreTabBtn.classList.toggle('bg-white', isRestore);
        restoreTabBtn.classList.toggle('shadow-sm', isRestore);
        restoreTabBtn.classList.toggle('font-semibold', isRestore);
        restoreTabBtn.classList.toggle('text-gray-800', isRestore);
        restoreTabBtn.classList.toggle('font-medium', !isRestore);
        restoreTabBtn.classList.toggle('text-gray-500', !isRestore);
        restoreContent.classList.toggle('hidden', !isRestore);

        backupTabBtn.classList.toggle('bg-white', !isRestore);
        backupTabBtn.classList.toggle('shadow-sm', !isRestore);
        backupTabBtn.classList.toggle('font-semibold', !isRestore);
        backupTabBtn.classList.toggle('text-gray-800', !isRestore);
        backupTabBtn.classList.toggle('font-medium', isRestore);
        backupTabBtn.classList.toggle('text-gray-500', isRestore);
        backupContent.classList.toggle('hidden', isRestore);

        if (isRestore) {
            filterAndRender();
        }
    }

    // --- TABLE RENDERING ---
    function renderTable(items) {
        deletedItemsTableBody.innerHTML = ''; // Clear existing rows
        if (items.length === 0) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 6;
            td.className = 'text-center py-10 text-gray-500';
            td.textContent = 'No items found.';
            tr.appendChild(td);
            deletedItemsTableBody.appendChild(tr);
            return;
        }

        items.forEach(item => {
            const newRow = itemRowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.itemId = item.id;
            tr.dataset.itemType = item.type;

            const badge = newRow.querySelector('.item-type-badge');
            badge.textContent = item.type;
            if (item.type === 'user') {
                badge.classList.add('bg-blue-100', 'text-blue-800');
                newRow.querySelector('.item-main-identifier').textContent = item.fullName;
                newRow.querySelector('.item-secondary-identifier').textContent = item.username;
            } else { // book
                badge.classList.add('bg-green-100', 'text-green-800');
                newRow.querySelector('.item-main-identifier').textContent = item.title;
                newRow.querySelector('.item-secondary-identifier').textContent = item.author;
            }

            newRow.querySelector('.item-deleted-date').textContent = item.deleted_date;
            newRow.querySelector('.item-deleted-by').textContent = item.deleted_by;

            deletedItemsTableBody.appendChild(newRow);
        });
    }

    // --- FILTER AND SEARCH ---
    function filterAndRender() {
        const searchTerm = searchInput.value.toLowerCase();
        let data = [];

        if (currentFilter === 'Users') {
            data = deletedUsers.filter(u =>
                u.fullName.toLowerCase().includes(searchTerm) ||
                u.username.toLowerCase().includes(searchTerm)
            );
        } else { // Books
            data = deletedBooks.filter(b =>
                b.title.toLowerCase().includes(searchTerm) ||
                b.author.toLowerCase().includes(searchTerm)
            );
        }
        renderTable(data);
    }

    // --- MODAL MANAGEMENT ---
    function populateUserDetailsModal(user) {
        document.getElementById('modalUserFullName').textContent = user.fullName;
        document.getElementById('modalUsername').textContent = user.username;
        document.getElementById('modalUserRole').textContent = user.role;
        document.getElementById('modalUserEmail').textContent = user.email;
        document.getElementById('modalUserCreatedDate').textContent = user.createdDate;
        document.getElementById('modalUserDeletedDate').textContent = user.deleted_date;
        document.getElementById('modalUserDeletedBy').textContent = user.deleted_by;
        userDetailsModal.classList.remove('hidden');
    }

    function populateBookDetailsModal(book) {
        document.getElementById('modalBookAccessionNumber').textContent = book.accession_number;
        document.getElementById('modalBookCallNumber').textContent = book.call_number;
        document.getElementById('modalBookIsbn').textContent = book.isbn;
        document.getElementById('modalBookTitle').textContent = book.title;
        document.getElementById('modalBookAuthor').textContent = book.author;
        document.getElementById('modalBookPublisher').textContent = book.publisher;
        document.getElementById('modalBookYear').textContent = book.year;
        document.getElementById('modalBookSubject').textContent = book.subject;
        document.getElementById('modalBookPlace').textContent = book.place_of_publication;
        document.getElementById('modalBookCreatedDate').textContent = book.created_date;
        document.getElementById('modalBookDeletedDate').textContent = book.deleted_date;
        document.getElementById('modalBookDeletedBy').textContent = book.deleted_by;
        bookDetailsModal.classList.remove('hidden');
    }

    function closeModal(modal) {
        modal.classList.add('hidden');
    }

    // --- EVENT LISTENERS ---
    restoreTabBtn.addEventListener('click', () => setActiveTab('restore'));
    backupTabBtn.addEventListener('click', () => setActiveTab('backup'));

    searchInput.addEventListener('input', filterAndRender);

    typeDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        typeDropdownMenu.classList.toggle('hidden');
    });

    typeDropdownMenu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            currentFilter = item.dataset.value;
            typeDropdownSpan.textContent = currentFilter;
            searchInput.placeholder = `Search in ${currentFilter}...`;
            typeDropdownMenu.classList.add('hidden');
            filterAndRender();
        });
    });

    deletedItemsTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.item-row');
        if (!row) return;

        // Check if a button was clicked inside the row
        if (e.target.closest('.restore-btn') || e.target.closest('.delete-btn')) {
            return;
        }

        const id = parseInt(row.dataset.itemId, 10);
        const type = row.dataset.itemType;

        if (type === 'user') {
            const user = deletedUsers.find(u => u.id === id);
            if (user) populateUserDetailsModal(user);
        } else if (type === 'book') {
            const book = deletedBooks.find(b => b.id === id);
            if (book) populateBookDetailsModal(book);
        }
    });

    // Modal closing events
    closeUserDetailsModalBtn.addEventListener('click', () => closeModal(userDetailsModal));
    userDetailsModal.addEventListener('click', (e) => e.target === userDetailsModal && closeModal(userDetailsModal));
    closeBookDetailsModalBtn.addEventListener('click', () => closeModal(bookDetailsModal));
    bookDetailsModal.addEventListener('click', (e) => e.target === bookDetailsModal && closeModal(bookDetailsModal));

    document.addEventListener('click', () => {
        typeDropdownMenu.classList.add('hidden');
    });

    // --- INITIALIZATION ---
    setActiveTab('restore');
    searchInput.placeholder = `Search in ${currentFilter}...`;
});