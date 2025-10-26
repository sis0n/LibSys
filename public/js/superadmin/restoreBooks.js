document.addEventListener('DOMContentLoaded', () => {
    const bookSearchInput = document.getElementById('bookSearchInput');
    // ===== FILTER DROPDOWN =====
    const bookFilterDropdownBtn = document.getElementById('bookFilterDropdownBtn');
    const bookFilterDropdownMenu = document.getElementById('bookFilterDropdownMenu');
    const bookFilterDropdownSpan = bookFilterDropdownBtn.querySelector('span');
    let currentFilterOption = 'Default Order';

    const deletedBooksTableBody = document.getElementById('deletedBooksTableBody');
    const deletedBookRowTemplate = document.getElementById('deleted-book-row-template').content;
    const deletedBooksCount = document.getElementById('deletedBooksCount');
    const noDeletedBooksFound = document.getElementById('noDeletedBooksFound');

    const paginationContainer = document.getElementById('pagination-container');
    const paginationNumbersDiv = document.getElementById('pagination-numbers');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');

    // Dummy Data (Expanded with modal fields)
    const allDeletedBooks = [
        {
            id: 1,
            title: 'Deleted Programming Book',
            author: 'Test Author',
            isbn: '978-0-111111-11-1',
            category: 'Computer Science',
            status: 'available',
            copies: '3 total',
            deletedDate: '8/2/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-12345',
            call_number: 'CN-67890',
            publisher: 'Tech Publishers',
            year: '2020',
            subject: 'Programming, Algorithms',
            place_of_publication: 'New York',
            created_date: '1/1/2020, 10:00:00 AM',
        },
        {
            id: 2,
            title: 'Another Deleted Novel',
            author: 'Jane Doe',
            isbn: '978-1-234567-89-0',
            category: 'Fiction',
            status: 'borrowed',
            copies: '1 total',
            deletedDate: '7/15/2025',
            deletedBy: 'Librarian',
            accession_number: 'AN-54321',
            call_number: 'CN-09876',
            publisher: 'Fiction House',
            year: '2018',
            subject: 'Fantasy, Adventure',
            place_of_publication: 'London',
            created_date: '3/10/2018, 09:00:00 AM',
        },
        {
            id: 3,
            title: 'History of the World',
            author: 'John Smith',
            isbn: '978-9-876543-21-0',
            category: 'History',
            status: 'available',
            copies: '5 total',
            deletedDate: '6/1/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-98765',
            call_number: 'CN-12345',
            publisher: 'History Books Inc.',
            year: '2015',
            subject: 'World History',
            place_of_publication: 'Paris',
            created_date: '5/20/2015, 02:00:00 PM',
        },
        {
            id: 4,
            title: 'Advanced Algorithms',
            author: 'Algo Master',
            isbn: '978-1-111111-11-2',
            category: 'Computer Science',
            status: 'available',
            copies: '2 total',
            deletedDate: '8/10/2025',
            deletedBy: 'SuperAdmin',
            accession_number: 'AN-11223',
            call_number: 'CN-33445',
            publisher: 'Algorithm Press',
            year: '2022',
            subject: 'Algorithms, Data Structures',
            place_of_publication: 'Berlin',
            created_date: '2/15/2022, 11:00:00 AM',
        },
        {
            id: 5,
            title: 'The Art of War',
            author: 'Sun Tzu',
            isbn: '978-0-123456-78-9',
            category: 'Philosophy',
            status: 'borrowed',
            copies: '1 total',
            deletedDate: '7/20/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-55667',
            call_number: 'CN-77889',
            publisher: 'Ancient Texts',
            year: '500 BC',
            subject: 'Strategy, Military Science',
            place_of_publication: 'China',
            created_date: '10/1/2010, 04:00:00 PM',
        },
        {
            id: 6,
            title: 'Introduction to Physics',
            author: 'Physicist Pro',
            isbn: '978-5-555555-55-5',
            category: 'Science',
            status: 'available',
            copies: '4 total',
            deletedDate: '6/15/2025',
            deletedBy: 'Librarian',
            accession_number: 'AN-99001',
            call_number: 'CN-11223',
            publisher: 'Science Books',
            year: '2019',
            subject: 'Physics, Mechanics',
            place_of_publication: 'Cambridge',
            created_date: '7/1/2019, 01:00:00 PM',
        },
        {
            id: 7,
            title: 'Data Structures in C++',
            author: 'Code Guru',
            isbn: '978-2-222222-22-2',
            category: 'Computer Science',
            status: 'available',
            copies: '3 total',
            deletedDate: '8/5/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-22334',
            call_number: 'CN-44556',
            publisher: 'Code Academy',
            year: '2021',
            subject: 'Data Structures, C++',
            place_of_publication: 'San Francisco',
            created_date: '4/1/2021, 10:00:00 AM',
        },
        {
            id: 8,
            title: 'Fantasy Realm',
            author: 'Story Teller',
            isbn: '978-3-333333-33-3',
            category: 'Fiction',
            status: 'available',
            copies: '2 total',
            deletedDate: '7/25/2025',
            deletedBy: 'SuperAdmin',
            accession_number: 'AN-66778',
            call_number: 'CN-88990',
            publisher: 'Mythic Tales',
            year: '2017',
            subject: 'Fantasy, Magic',
            place_of_publication: 'Middle Earth',
            created_date: '9/1/2017, 03:00:00 PM',
        },
        {
            id: 9,
            title: 'World Atlas',
            author: 'Cartographer',
            isbn: '978-4-444444-44-4',
            category: 'Geography',
            status: 'available',
            copies: '1 total',
            deletedDate: '6/10/2025',
            deletedBy: 'Librarian',
            accession_number: 'AN-00112',
            call_number: 'CN-22334',
            publisher: 'Global Maps',
            year: '2023',
            subject: 'Geography, Cartography',
            place_of_publication: 'Geneva',
            created_date: '1/1/2023, 09:00:00 AM',
        },
        {
            id: 10,
            title: 'Machine Learning Basics',
            author: 'AI Expert',
            isbn: '978-6-666666-66-6',
            category: 'Computer Science',
            status: 'available',
            copies: '3 total',
            deletedDate: '8/1/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-33445',
            call_number: 'CN-55667',
            publisher: 'AI Press',
            year: '2024',
            subject: 'Machine Learning, AI',
            place_of_publication: 'Silicon Valley',
            created_date: '2/1/2024, 10:00:00 AM',
        },
        {
            id: 10,
            title: 'Machine Learning Basics',
            author: 'AI Expert',
            isbn: '978-6-666666-66-6',
            category: 'Computer Science',
            status: 'available',
            copies: '3 total',
            deletedDate: '8/1/2025',
            deletedBy: 'Admin',
            accession_number: 'AN-33445',
            call_number: 'CN-55667',
            publisher: 'AI Press',
            year: '2024',
            subject: 'Machine Learning, AI',
            place_of_publication: 'Silicon Valley',
            created_date: '2/1/2024, 10:00:00 AM',
        },
    ];

    let currentSearchTerm = '';
    let currentDateFilter = '';
    let filteredBooks = [...allDeletedBooks];
    const itemsPerPage = 10;
    let currentPage = 1;

    // --- MODAL ELEMENTS ---
    const bookDetailsModal = document.getElementById('bookDetailsModal');
    const closeBookDetailsModalBtn = document.getElementById('closeBookDetailsModalBtn');
    const modalBookAccessionNumber = document.getElementById('modalBookAccessionNumber');
    const modalBookCallNumber = document.getElementById('modalBookCallNumber');
    const modalBookIsbn = document.getElementById('modalBookIsbn');
    const modalBookTitle = document.getElementById('modalBookTitle');
    const modalBookAuthor = document.getElementById('modalBookAuthor');
    const modalBookPublisher = document.getElementById('modalBookPublisher');
    const modalBookYear = document.getElementById('modalBookYear');
    const modalBookSubject = document.getElementById('modalBookSubject');
    const modalBookPlace = document.getElementById('modalBookPlace');
    const modalBookCreatedDate = document.getElementById('modalBookCreatedDate');
    const modalBookDeletedDate = document.getElementById('modalBookDeletedDate');
    const modalBookDeletedBy = document.getElementById('modalBookDeletedBy');

    function renderDeletedBooks(books) {
        deletedBooksTableBody.innerHTML = '';
        deletedBooksCount.textContent = filteredBooks.length;

        if (books.length === 0) {
            noDeletedBooksFound.classList.remove('hidden');
            return;
        }
        noDeletedBooksFound.classList.add('hidden');

        books.forEach(book => {
            const newRow = deletedBookRowTemplate.cloneNode(true);
            const tr = newRow.querySelector('tr');
            tr.dataset.bookId = book.id; 

            newRow.querySelector('.book-title').textContent = book.title;
            newRow.querySelector('.book-author').textContent = book.author;
            newRow.querySelector('.book-accession-number').textContent = book.accession_number;
            
            newRow.querySelector('.book-deleted-date').textContent = book.deletedDate;
            newRow.querySelector('.book-deleted-by').textContent = book.deletedBy;

            const restoreBtn = newRow.querySelector('.restore-btn');
            restoreBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                alert(`Restoring ${book.title} (ID: ${book.id})`);
            });

            const deleteBtn = newRow.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (confirm(`Are you sure you want to permanently delete ${book.title} (ID: ${book.id})?`)) {
                    alert(`Permanently deleting ${book.title}`);
                }
            });

            deletedBooksTableBody.appendChild(newRow);
        });
    }

    function renderPagination(totalItems, itemsPerPage, currentPage) {
        paginationNumbersDiv.innerHTML = '';
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalItems <= itemsPerPage) {
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

        // First page button
        if (startPage > 1) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = '1';
            link.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition');
            if (1 === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(1); });
            paginationNumbersDiv.appendChild(li).appendChild(link);

            if (startPage > 2) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'text-gray-500');
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }
        }

        // Middle page buttons
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = i;
            link.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition');
            if (i === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(i); });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }

        // Last page button
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsisLi = document.createElement('li');
                const ellipsisSpan = document.createElement('span');
                ellipsisSpan.textContent = '...';
                ellipsisSpan.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'text-gray-500');
                paginationNumbersDiv.appendChild(ellipsisLi).appendChild(ellipsisSpan);
            }

            const li = document.createElement('li');
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = totalPages;
            link.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'rounded-full', 'text-gray-700', 'hover:bg-gray-100', 'transition');
            if (totalPages === currentPage) {
                link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
            }
            link.addEventListener('click', (e) => { e.preventDefault(); goToPage(totalPages); });
            paginationNumbersDiv.appendChild(li).appendChild(link);
        }
    }

    function goToPage(page) {
        const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
        currentPage = Math.max(1, Math.min(page, totalPages));

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const booksToRender = filteredBooks.slice(startIndex, endIndex);
        renderDeletedBooks(booksToRender);
        renderPagination(filteredBooks.length, itemsPerPage, currentPage);
    }

    function applySorting() {
        if (currentFilterOption === 'Title (A-Z)') {
            filteredBooks.sort((a, b) => a.title.localeCompare(b.title));
        } else if (currentFilterOption === 'Title (Z-A)') {
            filteredBooks.sort((a, b) => b.title.localeCompare(a.title));
        } else if (currentFilterOption === 'Deleted Date (Newest)') {
            filteredBooks.sort((a, b) => new Date(b.deletedDate) - new Date(a.deletedDate));
        } else if (currentFilterOption === 'Deleted Date (Oldest)') {
            filteredBooks.sort((a, b) => new Date(a.deletedDate) - new Date(b.deletedDate));
        } else {
            filteredBooks.sort((a, b) => a.id - b.id);
        }
        goToPage(1);
    }

    function filterAndSearchBooks() {
        let filtered = allDeletedBooks.filter(book => {
            const matchesSearch = book.title.toLowerCase().includes(currentSearchTerm.toLowerCase()) ||
                                  book.author.toLowerCase().includes(currentSearchTerm.toLowerCase()) ||
                                  book.isbn.toLowerCase().includes(currentSearchTerm.toLowerCase());

            const matchesDate = !currentDateFilter || (
                new Date(book.deletedDate).toDateString() === new Date(currentDateFilter).toDateString()
            );
            
            return matchesSearch && matchesDate;
        });
        filteredBooks = filtered;
        applySorting();
    }

    // --- MODAL MANAGEMENT ---
    function openBookDetailsModal(book) {
        modalBookAccessionNumber.textContent = book.accession_number || 'N/A';
        modalBookCallNumber.textContent = book.call_number || 'N/A';
        modalBookIsbn.textContent = book.isbn || 'N/A';
        modalBookTitle.textContent = book.title || 'N/A';
        modalBookAuthor.textContent = book.author || 'N/A';
        modalBookPublisher.textContent = book.publisher || 'N/A';
        modalBookYear.textContent = book.year || 'N/A';
        modalBookSubject.textContent = book.subject || 'N/A';
        modalBookPlace.textContent = book.place_of_publication || 'N/A';
        modalBookCreatedDate.textContent = book.created_date || 'N/A';
        modalBookDeletedDate.textContent = book.deletedDate || 'N/A';
        modalBookDeletedBy.textContent = book.deletedBy || 'N/A';
        bookDetailsModal.classList.remove('hidden');
    }

    function closeBookDetailsModal() {
        bookDetailsModal.classList.add('hidden');
    }

    // --- EVENT LISTENERS ---
    bookSearchInput.addEventListener('input', (e) => {
        currentSearchTerm = e.target.value;
        filterAndSearchBooks();
    });

    bookFilterDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        bookFilterDropdownMenu.classList.toggle('hidden');
    });

    bookFilterDropdownMenu.querySelectorAll('a').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            currentFilterOption = item.dataset.value;
            bookFilterDropdownSpan.textContent = currentFilterOption;
            bookFilterDropdownMenu.classList.add('hidden');
            applySorting();
        });
    });

    const deletedBookDateFilter = document.getElementById('deletedBookDateFilter');
    deletedBookDateFilter.addEventListener('change', (e) => {
        currentDateFilter = e.target.value;
        filterAndSearchBooks();
    });

    document.addEventListener('click', () => {
        bookFilterDropdownMenu.classList.add('hidden');
    });

    prevPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        goToPage(currentPage - 1);
    });

    nextPageBtn.addEventListener('click', (e) => {
        e.preventDefault();
        goToPage(currentPage + 1);
    });

    // Row click to open modal
    deletedBooksTableBody.addEventListener('click', (e) => {
        const row = e.target.closest('.deleted-book-row');
        if (!row) return;

        // Prevent modal from opening if action buttons are clicked
        if (e.target.closest('.restore-btn') || e.target.closest('.delete-btn')) {
            return;
        }

        const bookId = parseInt(row.dataset.bookId, 10);
        const book = allDeletedBooks.find(b => b.id === bookId);
        if (book) {
            openBookDetailsModal(book);
        }
    });

    // Modal closing events
    closeBookDetailsModalBtn.addEventListener('click', closeBookDetailsModal);
    bookDetailsModal.addEventListener('click', (e) => {
        if (e.target === bookDetailsModal) {
            closeBookDetailsModal();
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !bookDetailsModal.classList.contains('hidden')) {
            closeBookDetailsModal();
        }
    });

    // Initial load
    filterAndSearchBooks();
});