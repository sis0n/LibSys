window.addEventListener('DOMContentLoaded', () => {
  // Tiyakin na ang CURRENT_USER_ROLE ay na-define sa PHP file bago nito
  const isManagedRole = (CURRENT_USER_ROLE === 'admin' || CURRENT_USER_ROLE === 'librarian');

  const bookSearchInput = document.getElementById('bookSearchInput');
  const bookFilterDropdownBtn = document.getElementById('bookFilterDropdownBtn');
  const bookFilterDropdownMenu = document.getElementById('bookFilterDropdownMenu');
  const bookFilterDropdownSpan = bookFilterDropdownBtn.querySelector('span');
  let currentFilterOption = 'Deleted Date (Newest)';

  const deletedBooksTableBody = document.getElementById('deletedBooksTableBody');
  const deletedBookRowTemplate = document.getElementById('deleted-book-row-template').content;
  const deletedBooksCount = document.getElementById('deletedBooksCount');
  const noDeletedBooksFound = document.getElementById('noDeletedBooksFound');

  const paginationContainer = document.getElementById('pagination-container');
  const paginationNumbersDiv = document.getElementById('pagination-numbers');
  const prevPageBtn = document.getElementById('prev-page');
  const nextPageBtn = document.getElementById('next-page');

  const csrfToken = document.getElementById('csrf_token')?.value || '';

  let allDeletedBooks = [];
  let currentSearchTerm = '';
  let currentDateFilter = '';
  let filteredBooks = [];
  const itemsPerPage = 10;
  let currentPage = 1;

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

  function showLoadingState() {
    deletedBooksTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">Loading...</td></tr>';
    noDeletedBooksFound.classList.add('hidden');
    paginationContainer.classList.add('hidden');
  }

  function showErrorState(message) {
    deletedBooksTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-10 text-red-500">${message}</td></tr>`;
    noDeletedBooksFound.classList.remove('hidden');
    paginationContainer.classList.add('hidden');
    deletedBooksCount.textContent = 0;
  }

  // --- Core Filtering Logic ---
  function applySorting() {
    if (currentFilterOption === 'Title (A-Z)') {
      filteredBooks.sort((a, b) => a.title.localeCompare(b.title));
    } else if (currentFilterOption === 'Title (Z-A)') {
      filteredBooks.sort((a, b) => b.title.localeCompare(a.title));
    } else if (currentFilterOption === 'Deleted Date (Newest)') {
      filteredBooks.sort((a, b) => new Date(b.deleted_at) - new Date(a.deleted_at));
    } else if (currentFilterOption === 'Deleted Date (Oldest)') {
      filteredBooks.sort((a, b) => new Date(a.deleted_at) - new Date(b.deleted_at));
    } else {
      filteredBooks.sort((a, b) => a.id - b.id);
    }
    goToPage(1);
  }

  function filterAndSearchBooks() {
    let filtered = allDeletedBooks.filter(book => {
      const searchLower = currentSearchTerm.toLowerCase();
      const matchesSearch = !currentSearchTerm ||
        (book.title && book.title.toLowerCase().includes(searchLower)) ||
        (book.author && book.author.toLowerCase().includes(searchLower)) ||
        (book.book_isbn && book.book_isbn.toLowerCase().includes(searchLower)) ||
        (book.accession_number && book.accession_number.toLowerCase().includes(searchLower));

      let matchesDate = true;
      if (currentDateFilter && book.deleted_at) {
        try {
          const deletedDate = new Date(book.deleted_at);
          const filterDate = new Date(currentDateFilter + "T00:00:00");
          matchesDate = deletedDate.toDateString() === filterDate.toDateString();
        } catch (e) {
          matchesDate = false;
        }
      } else if (currentDateFilter && !book.deleted_at) {
        matchesDate = false;
      }

      return matchesSearch && matchesDate;
    });
    filteredBooks = filtered;
    applySorting();
  }

  function createPageLink(pageNumber, isActive = false) {
    const link = document.createElement('a');
    link.href = '#';
    link.textContent = pageNumber;
    link.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'rounded-full', 'text-gray-700', 'hover:bg-orange-50', 'hover:text-orange-600', 'transition');
    if (isActive) {
      link.classList.add('bg-orange-500', 'text-white', 'font-semibold');
      link.classList.remove('hover:bg-orange-50', 'hover:text-orange-600', 'text-gray-700');
    }
    link.addEventListener('click', (e) => { e.preventDefault(); goToPage(pageNumber); });
    paginationNumbersDiv.appendChild(link);
  }

  function createEllipsis() {
    const ellipsisSpan = document.createElement('span');
    ellipsisSpan.textContent = '...';
    ellipsisSpan.classList.add('flex', 'items-center', 'justify-center', 'w-8', 'h-8', 'text-gray-500');
    paginationNumbersDiv.appendChild(ellipsisSpan);
  }

  async function fetchDeletedBooks() {
    showLoadingState();
    try {
      const response = await fetch('restoreBooks/fetch');
      const data = await response.json();

      if (data.success && Array.isArray(data.books)) {
        allDeletedBooks = data.books;
        filterAndSearchBooks();
      } else {
        console.error('Error fetching deleted books:', data.message);
        showErrorState(`Failed to load deleted books: ${data.message}`);
        allDeletedBooks = [];
        filterAndSearchBooks();
      }
    } catch (error) {
      console.error('Network error fetching deleted books:', error);
      showErrorState(`Network error. Please check the console for details.`);
      allDeletedBooks = [];
      filterAndSearchBooks();
    }
  }

  function renderPagination(totalItems, itemsPerPage, currentPage) {
    paginationNumbersDiv.innerHTML = '';
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    if (totalItems <= itemsPerPage) {
      paginationContainer.classList.add('hidden');
      return;
    }

    paginationContainer.classList.remove('hidden');

    prevPageBtn.classList.toggle('opacity-50', currentPage === 1);
    prevPageBtn.classList.toggle('cursor-not-allowed', currentPage === 1);
    nextPageBtn.classList.toggle('opacity-50', currentPage === totalPages);
    nextPageBtn.classList.toggle('cursor-not-allowed', currentPage === totalPages);

    const maxPagesToShow = 3;
    let startPage = Math.max(1, currentPage - 1);
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

    if (endPage - startPage + 1 < maxPagesToShow) {
      startPage = Math.max(1, totalPages - maxPagesToShow + 1);
    }
    startPage = Math.max(1, startPage);

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

  function goToPage(page) {
    const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
    currentPage = Math.max(1, Math.min(page, totalPages));

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const booksToRender = filteredBooks.slice(startIndex, endIndex);
    renderDeletedBooks(booksToRender);
    renderPagination(filteredBooks.length, itemsPerPage, currentPage);
  }

  function renderDeletedBooks(booksToRender) {
    deletedBooksTableBody.innerHTML = '';
    deletedBooksCount.textContent = filteredBooks.length;

    if (booksToRender.length === 0) {
      if (filteredBooks.length === 0) {
        noDeletedBooksFound.textContent = 'No deleted books found.';
        noDeletedBooksFound.classList.remove('hidden');
      } else {
        deletedBooksTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-gray-500">No books found on this page.</td></tr>';
        noDeletedBooksFound.classList.add('hidden');
      }
      paginationContainer.classList.add('hidden');
      return;
    }
    noDeletedBooksFound.classList.add('hidden');
    paginationContainer.classList.remove('hidden');

    booksToRender.forEach(book => {
      const newRow = deletedBookRowTemplate.cloneNode(true);
      const tr = newRow.querySelector('tr');
      tr.dataset.bookId = book.id;

      newRow.querySelector('.book-title').textContent = book.title || 'N/A';
      newRow.querySelector('.book-isbn').textContent = `ISBN: ${book.book_isbn || 'N/A'}`;
      newRow.querySelector('.book-author').textContent = book.author || 'N/A';
      newRow.querySelector('.book-accession-number').textContent = book.accession_number || 'N/A';

      newRow.querySelector('.book-deleted-date').textContent = formatDate(book.deleted_at);
      newRow.querySelector('.book-deleted-by').textContent = book.deleted_by_name || 'N/A';

      const restoreBtn = newRow.querySelector('.restore-btn');
      restoreBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        handleRestore(book.id, book.title, restoreBtn);
      });

      const archiveBtn = newRow.querySelector('.archive-btn');
      // --- ARCHIVE BUTTON DISABLING LOGIC ---
      if (isManagedRole) {
        archiveBtn.disabled = true;
        archiveBtn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-300', 'hover:bg-gray-400');
        archiveBtn.title = 'Only Superadmin can permanently archive records.';
      } else {
        archiveBtn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          handleArchive(book.id, book.title, archiveBtn);
        });
      }
      // ----------------------------------------

      deletedBooksTableBody.appendChild(newRow);
    });
  }

  async function handleRestore(bookId, title, buttonEl) {
    if (!confirm(`Are you sure you want to restore "${title}"?`)) return;

    buttonEl.disabled = true;
    buttonEl.classList.add('opacity-50');

    try {
      const response = await fetch('restoreBooks/restore', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ book_id: bookId })
      });
      const result = await response.json();

      if (result.success) {
        alert(result.message);
        fetchDeletedBooks();
      } else {
        alert(`Restore failed: ${result.message}`);
      }
    } catch (error) {
      alert('An error occurred during restoration.');
    } finally {
      buttonEl.disabled = false;
      buttonEl.classList.remove('opacity-50');
    }
  }

  async function handleArchive(bookId, title, buttonEl) {
    if (!confirm(`ARCHIVE "${title}"? This action is permanent and cannot be undone.`)) return;

    buttonEl.disabled = true;
    buttonEl.classList.add('opacity-50');

    try {
      const response = await fetch(`restoreBooks/archive/${bookId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      });
      const result = await response.json();

      if (result.success) {
        alert(result.message);
        fetchDeletedBooks();
      } else {
        alert(`Archive failed: ${result.message}`);
      }
    } catch (error) {
      alert('An error occurred during archiving.');
    } finally {
      buttonEl.disabled = false;
      buttonEl.classList.remove('opacity-50');
    }
  }

  function openBookDetailsModal(book) {
    modalBookAccessionNumber.textContent = book.accession_number || 'N/A';
    modalBookCallNumber.textContent = book.call_number || 'N/A';
    modalBookIsbn.textContent = book.book_isbn || 'N/A';
    modalBookTitle.textContent = book.title || 'N/A';
    modalBookAuthor.textContent = book.author || 'N/A';
    modalBookPublisher.textContent = book.book_publisher || 'N/A';
    modalBookYear.textContent = book.year || 'N/A';
    modalBookSubject.textContent = book.subject || 'N/A';
    modalBookPlace.textContent = book.book_place || 'N/A';
    modalBookCreatedDate.textContent = formatDate(book.created_at);
    modalBookDeletedDate.textContent = formatDate(book.deleted_at);
    modalBookDeletedBy.textContent = book.deleted_by_name || 'N/A';
    bookDetailsModal.classList.remove('hidden');
  }

  function closeBookDetailsModal() {
    bookDetailsModal.classList.add('hidden');
  }


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
    if (currentPage > 1) {
      goToPage(currentPage - 1);
    }
  });

  nextPageBtn.addEventListener('click', (e) => {
    e.preventDefault();
    const totalPages = Math.ceil(filteredBooks.length / itemsPerPage);
    if (currentPage < totalPages) {
      goToPage(currentPage + 1);
    }
  });

  deletedBooksTableBody.addEventListener('click', (e) => {
    const row = e.target.closest('.deleted-book-row');
    if (!row) return;

    if (e.target.closest('.restore-btn') || e.target.closest('.archive-btn')) {
      return;
    }

    const bookId = parseInt(row.dataset.bookId, 10);
    const book = allDeletedBooks.find(b => b.id === bookId);
    if (book) {
      openBookDetailsModal(book);
    }
  });

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

  fetchDeletedBooks();
});
