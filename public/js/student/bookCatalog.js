window.addEventListener("DOMContentLoaded", () => {
  const grid = document.getElementById("booksGrid");
  const skeletons = document.getElementById("loadingSkeletons");
  const searchInput = document.querySelector("input[placeholder*='Search']");
  const cartCount = document.getElementById("cart-count");
  const noBooksFound = document.getElementById("noBooksFound");
  const resultsIndicator = document.getElementById("resultsIndicator");

  const statusBtn = document.getElementById("statusDropdownBtn");
  const statusMenu = document.getElementById("statusDropdownMenu");
  const statusValue = document.getElementById("statusDropdownValue");
  const sortBtn = document.getElementById("sortDropdownBtn");
  const sortMenu = document.getElementById("sortDropdownMenu");
  const sortValue = document.getElementById("sortDropdownValue");

  // Modal Elements
  const modal = document.getElementById("bookModal");
  const modalContent = document.getElementById("bookModalContent");
  const closeModalBtn = document.getElementById("closeModal");
  const modalImg = document.getElementById("modalImg");
  const modalTitle = document.getElementById("modalTitle");
  const modalAuthor = document.getElementById("modalAuthor");
  const modalCallNumber = document.getElementById("modalCallNumber");
  const modalAccessionNumber = document.getElementById("modalAccessionNumber");
  const modalIsbn = document.getElementById("modalIsbn");
  const modalSubject = document.getElementById("modalSubject");
  const modalDescription = document.getElementById("modalDescription");
  const modalPlace = document.getElementById("modalPlace");
  const modalPublisher = document.getElementById("modalPublisher");
  const modalYear = document.getElementById("modalYear");
  const modalEdition = document.getElementById("modalEdition");
  const modalSupplementary = document.getElementById("modalSupplementary");
  const modalStatus = document.getElementById("modalStatus");
  const addToCartBtn = document.getElementById("addToCartBtn");


  const paginationControls = document.getElementById("paginationControls");
  const paginationList = document.getElementById("paginationList");

  const limit = 30;
  let totalPages = 1;
  let totalCount = 0;
  let isLoading = false;
  let searchValue = "";
  let statusValueFilter = "All Status";
  let sortValueFilter = "default";
  let cart = [];

  let currentPage = 1;
  try {
    const savedPage = sessionStorage.getItem('bookCatalogPage');
    if (savedPage) {
      const parsedPage = parseInt(savedPage, 10);
      if (!isNaN(parsedPage) && parsedPage > 0) currentPage = parsedPage;
      else sessionStorage.removeItem('bookCatalogPage');
    }
  } catch (e) {
    console.error("SessionStorage Error:", e);
    currentPage = 1;
  }

  if (statusBtn && statusMenu && statusValue) {
    statusBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      if (sortMenu) sortMenu.classList.add("hidden");
      statusMenu.classList.toggle("hidden");
    });
    window.selectStatus = function (el, value) {
      statusValue.textContent = value;
      statusValueFilter = value;
      document.querySelectorAll("#statusDropdownMenu .status-item").forEach(i => i.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));
      if (el) el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
      statusMenu.classList.add("hidden");
      currentPage = 1;
      try {
        sessionStorage.removeItem('bookCatalogPage');
      } catch (e) { }
      loadBooks(1);
    }
    const defaultStatus = statusMenu.querySelector(".status-item");
    if (defaultStatus) defaultStatus.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
  } else console.warn("Status dropdown missing");

  if (sortBtn && sortMenu && sortValue) {
    sortBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      if (statusMenu) statusMenu.classList.add("hidden");
      sortMenu.classList.toggle("hidden");
    });
    window.selectSort = function (el, value, text) {
      sortValue.textContent = text;
      sortValueFilter = value;
      document.querySelectorAll("#sortDropdownMenu .sort-item").forEach(i => i.classList.remove("bg-[var(--color-orange-200)]", "font-semibold"));
      if (el) el.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
      sortMenu.classList.add("hidden");
      currentPage = 1;
      try {
        sessionStorage.removeItem('bookCatalogPage');
      } catch (e) { }
      loadBooks(1);
    }
    const defaultSort = sortMenu.querySelector(".sort-item");
    if (defaultSort) defaultSort.classList.add("bg-[var(--color-orange-200)]", "font-semibold");
  } else console.warn("Sort dropdown missing");

  document.addEventListener("click", (e) => {
    if (statusMenu && statusBtn && !statusBtn.contains(e.target) && !statusMenu.contains(e.target)) statusMenu.classList.add("hidden");
    if (sortMenu && sortBtn && !sortBtn.contains(e.target) && !sortMenu.contains(e.target)) sortMenu.classList.add("hidden");
  });

  async function loadCart() {
    try {
      const r = await fetch("/libsys/public/student/cart/json");
      if (!r.ok) throw Error();
      cart = await r.json();
      updateCartBadge();
    } catch (e) {
      cart = [];
      updateCartBadge();
    }
  }

  async function updateCartBadge() {
    if (!cartCount) return;
    while (cartCount.firstChild) cartCount.removeChild(cartCount.firstChild);
    const i = document.createElement("i");
    i.className = "ph ph-shopping-cart text-xs mr-1";
    cartCount.appendChild(i);
    const c = (cart?.length) ? cart.length : 0;
    cartCount.appendChild(document.createTextNode(`${c} item(s)`));
  }

  async function addToCart(id) {
    if (!id) return;
    try {
      const r = await fetch(`/libsys/public/student/cart/add/${id}`);
      if (!r.ok) throw Error((await r.json()).message || `Err ${r.status}`);
      const d = await r.json();
      if (d.success) {
        cart = d.cart || [];
        updateCartBadge();
      }
      if (typeof Swal != 'undefined') Swal.fire({
        toast: !0,
        position: "bottom-end",
        icon: d.success ? "success" : "warning",
        title: d.message || (d.success ? "Added" : "Already in Cart"),
        showConfirmButton: !1,
        timer: 2500,
        timerProgressBar: !0
      });
      else alert(d.message || (d.success ? "Added" : "Already in Cart"));
    } catch (e) {
      console.error("Add cart err:", e);
    }
  }
  async function removeFromCart(id) {
    if (!id) return;
    try {
      const r = await fetch(`/libsys/public/student/cart/remove/${id}`, {
        method: "POST"
      });
      if (!r.ok) throw Error(`Err ${r.status}`);
      cart = cart.filter(i => i.cart_id != id);
      updateCartBadge();
      if (typeof Swal != 'undefined') Swal.fire({
        toast: !0,
        position: "bottom-end",
        icon: "success",
        title: "Removed",
        showConfirmButton: !1,
        timer: 2000,
        timerProgressBar: !0
      });
      else alert("Removed.");
    } catch (e) {
      console.error("Remove cart err:", e);
    }
  }
  async function clearCart() {
    if (typeof Swal != 'undefined') Swal.fire({
      title: 'Clear Cart?',
      text: "Remove all?",
      icon: 'warning',
      showCancelButton: !0,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes'
    }).then(async (r) => {
      if (r.isConfirmed) await performClearCart();
    });
    else {
      if (confirm("Clear cart?")) await performClearCart();
    }
  }
  async function performClearCart() {
    try {
      const r = await fetch("/libsys/public/student/cart/clear", {
        method: "POST"
      });
      if (!r.ok) throw Error("Failed");
      cart = [];
      updateCartBadge();
      if (typeof Swal != 'undefined') Swal.fire({
        toast: !0,
        position: 'bottom-end',
        icon: 'success',
        title: 'Cleared!',
        showConfirmButton: !1,
        timer: 2000
      });
      else alert("Cleared!");
    } catch (e) {
      console.error("Clear cart err:", e);
    }
  }
  if (addToCartBtn) addToCartBtn.addEventListener("click", () => {
    const id = addToCartBtn.dataset.id;
    if (id) addToCart(id);
  });

  async function loadBooks(page = 1) {
    if (isLoading || typeof page !== 'number' || page < 1) return;
    isLoading = true;
    currentPage = page;
    grid.innerHTML = "";
    noBooksFound.classList.add("hidden");
    skeletons.style.display = "grid";
    paginationControls.style.display = "none";
    resultsIndicator.textContent = 'Loading...';
    const start = Date.now();
    const offset = (page - 1) * limit;
    try {
      const params = new URLSearchParams({
        limit,
        offset,
        search: searchValue
      });
      if (statusValueFilter !== "All Status") params.set('status', statusValueFilter.toLowerCase());
      if (sortValueFilter !== "default") params.set('sort', sortValueFilter); // Send sort
      const res = await fetch(`/libsys/public/student/bookCatalog/fetch?${params.toString()}`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      let books;
      if (data?.books && Array.isArray(data.books) && typeof data.totalCount === 'number') {
        books = data.books;
        totalCount = data.totalCount;
      } else {
        console.error("Bad response:", data);
        books = [];
        totalCount = 0;
        noBooksFound.textContent = "Invalid data.";
        noBooksFound.classList.remove("hidden");
      }
      totalPages = Math.ceil(totalCount / limit) || 1;
      if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;
      const elapsed = Date.now() - start;
      if (elapsed < 300) await new Promise(r => setTimeout(r, 300 - elapsed));
      skeletons.style.display = "none";
      if (!books || books.length === 0) {
        noBooksFound.classList.remove("hidden");
        updateResultsIndicator(0, totalCount);
      } else {
        noBooksFound.classList.add("hidden");
        renderBooks(books);
        updateResultsIndicator(books.length, totalCount);
      }
      renderPagination(totalPages, currentPage);
      try {
        sessionStorage.setItem('bookCatalogPage', currentPage);
      } catch (e) { }
    } catch (err) {
      console.error("LoadBooks error:", err);
      skeletons.style.display = "none";
      noBooksFound.classList.remove("hidden");
      noBooksFound.textContent = "Error loading.";
      resultsIndicator.textContent = 'Error!';
      try {
        sessionStorage.removeItem('bookCatalogPage');
      } catch (e) { }
    } finally {
      isLoading = false;
    }
  }

  function updateResultsIndicator(booksLength, currentTotal) {
    if (typeof currentTotal !== 'number') currentTotal = 0;
    totalPages = Math.ceil(currentTotal / limit) || 1;
    if (currentTotal === 0) {
      resultsIndicator.textContent = 'No books found.';
      return;
    }
    const startItem = Math.max(1, (currentPage - 1) * limit + 1);
    const endItem = (currentPage - 1) * limit + booksLength;
    const totalFormatted = currentTotal.toLocaleString('en-US');
    if (booksLength === 0 && currentTotal > 0) resultsIndicator.innerHTML = `Page ${currentPage}. No results. (Total: <span class="font-bold">${totalFormatted}</span>)`;
    else resultsIndicator.innerHTML = `Results: <span class="font-bold">${startItem}-${endItem}</span> of <span class="font-bold">${totalFormatted}</span>`;
  }

  function renderPagination(totalPages, page) {
    if (totalPages <= 1) {
      paginationControls.style.display = "none";
      return;
    }
    paginationControls.style.display = "flex";
    paginationList.innerHTML = '';
    const createPageLink = (type, text, pageNum, isDisabled = false, isActive = false) => {
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = '#';
      a.setAttribute('data-page', String(pageNum));
      let baseClasses = `flex items-center justify-center px-3 h-8 leading-tight transition-colors duration-200`;
      if (type === 'prev' || type === 'next') {
        a.innerHTML = text;
        baseClasses += ` ml-0 rounded-l-lg border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700`;
        if (type === 'next') baseClasses = baseClasses.replace('rounded-l-lg', 'rounded-r-lg');
      } else if (type === 'ellipsis') {
        a.textContent = text;
        baseClasses += ` border border-gray-300 bg-white text-gray-500 cursor-default`;
      } else {
        a.textContent = text;
        baseClasses += ` border border-gray-300`;
        if (isActive) baseClasses += ` z-10 text-orange-600 border-orange-300 bg-orange-100 hover:bg-orange-200 hover:text-orange-700 font-semibold`;
        else baseClasses += ` bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700`;
      }
      a.className = baseClasses;
      if (isDisabled) {
        a.className += ` text-gray-400 bg-gray-100 cursor-not-allowed hover:bg-gray-100 hover:text-gray-400`;
        a.setAttribute('tabindex', '-1');
        a.dataset.page = '...';
      } else if (type === 'ellipsis') {
        a.setAttribute('tabindex', '-1');
        a.dataset.page = '...';
      }
      li.appendChild(a);
      paginationList.appendChild(li);
    };
    createPageLink('prev', `<i class="ph ph-caret-left pointer-events-none"></i>`, page - 1, page === 1);
    const window = 2;
    let pagesToShow = new Set([1, totalPages, page]);
    for (let i = 1; i <= window; i++) {
      if (page - i > 0) pagesToShow.add(page - i);
      if (page + i <= totalPages) pagesToShow.add(page + i);
    }
    let sortedPages = [...pagesToShow].filter(p => p > 0 && p <= totalPages).sort((a, b) => a - b);
    let lastPage = 0;
    for (const p of sortedPages) {
      if (p > lastPage + 1) createPageLink('ellipsis', '...', '...', true);
      createPageLink('number', p, p, false, p === page);
      lastPage = p;
    }
    if (lastPage < totalPages - 1) createPageLink('ellipsis', '...', '...', true);
    if (lastPage < totalPages && !sortedPages.includes(totalPages)) createPageLink('number', totalPages, totalPages, false, false);
    createPageLink('next', `<i class="ph ph-caret-right pointer-events-none"></i>`, page + 1, page === totalPages);
  }
  paginationList.addEventListener('click', (e) => {
    e.preventDefault();
    if (isLoading) return;
    const target = e.target.closest('a[data-page]');
    if (!target) return;
    const pageStr = target.dataset.page;
    if (pageStr === '...') return;
    const page = parseInt(pageStr, 10);
    if (!isNaN(page) && page !== currentPage) loadBooks(page);
  });

  async function loadAvailableCount() {
    try {
      const r = await fetch("/libsys/public/student/bookCatalog/availableCount");
      if (!r.ok) throw Error();
      const d = await r.json();
      const el = document.getElementById("availableCount");
      if (el) {
        while (el.firstChild) el.removeChild(el.firstChild);
        const i = document.createElement('i');
        i.className = 'ph ph-check-circle mr-1';
        el.appendChild(i);
        el.appendChild(document.createTextNode(` Available: ${d.available || 0}`));
      }
    } catch (e) {
      console.error("Err count:", e);
      const el = document.getElementById("availableCount");
      if (el) el.textContent = 'Available: Error';
    }
  }

  function renderBooks(books) {
    grid.innerHTML = '';
    if (!books || books.length === 0) return;
    books.forEach(book => {
      const card = document.createElement("div");
      card.className = "book-card relative bg-[var(--color-card)] shadow-sm rounded-xl overflow-hidden group transform transition duration-400 hover:-translate-y-1 hover:shadow-lg max-w-[230px] cursor-pointer";
      try {
        card.dataset.book = JSON.stringify(book);
      } catch (e) {
        console.error("Strf err:", book, e);
        return;
      }
      const imgWrap = document.createElement("div");
      imgWrap.className = "w-full aspect-[2/3] bg-white flex items-center justify-center overflow-hidden";
      const coverUrl = book.cover || null;
      if (coverUrl) {
        const img = document.createElement("img");
        img.src = coverUrl;
        img.alt = book.title || 'Cover';
        img.loading = 'lazy';
        img.className = "h-full w-auto object-contain group-hover:scale-105 transition duration-300";
        img.onerror = () => {
          imgWrap.innerHTML = `<i class="ph ph-img-brkn text-5xl text-gray-300"></i>`;
        };
        imgWrap.appendChild(img);
      } else {
        imgWrap.innerHTML = `<i class="ph ph-book text-5xl text-gray-400"></i>`;
      }
      const statusBadge = document.createElement("span");
      const isAvailable = book.availability === "available";
      statusBadge.className = `absolute top-2 left-2 ${isAvailable ? "bg-[var(--color-orange-500)]" : "bg-red-500"} text-white text-xs px-2 py-1 rounded-full shadow`;
      statusBadge.textContent = isAvailable ? "Available" : "Borrowed";
      const info = document.createElement("div");
      info.className = "p-2 group-hover:bg-gray-100 transition";
      const titleText = book.title || 'Untitled';
      const authorText = book.author || 'Unknown';
      const subjectText = book.subject || '';
      info.innerHTML = ` <h4 class="text-xs font-semibold mb-0.5 truncate w-full group-hover:text-[var(--color-primary)]" title="${titleText}">${titleText}</h4> <p class="text-[10px] text-gray-500 truncate w-full" title="${authorText}">by ${authorText}</p> <p class="text-[10px] font-medium text-[var(--color-primary)] mt-0.5 truncate w-full" title="${subjectText}"> ${subjectText} </p> `;
      card.appendChild(imgWrap);
      card.appendChild(statusBadge);
      card.appendChild(info);
      if (cart?.some(c => c.book_id == book.book_id)) {
        const badge = document.createElement("span");
        badge.className = "absolute bottom-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full shadow";
        badge.textContent = "In Cart";
        card.appendChild(badge);
      }
      grid.appendChild(card);
    });
  }

  function openModal(book) {
    if (!book || typeof book !== 'object') {
      console.error("Invalid book data");
      return;
    }
    if (!modal || !modalContent || !closeModalBtn || !modalImg || !modalTitle || !modalAuthor || !modalCallNumber || !modalAccessionNumber || !modalIsbn || !modalSubject || !modalPlace || !modalPublisher || !modalYear || !modalEdition || !modalSupplementary || !modalStatus || !addToCartBtn) {
      console.error("Modal elements missing!");
      return;
    }
    addToCartBtn.dataset.id = book.book_id || '';
    const coverUrl = book.cover || null;
    if (coverUrl) {
      modalImg.src = coverUrl;
      modalImg.alt = book.title || 'Cover';
      modalImg.classList.remove("hidden");
    } else {
      modalImg.classList.add("hidden");
      modalImg.src = '';
      modalImg.alt = '';
    }
    modalTitle.textContent = book.title || 'No Title';
    modalAuthor.textContent = "by " + (book.author || "Unknown");
    modalCallNumber.textContent = book.call_number || "N/A";
    modalAccessionNumber.textContent = book.accession_number || "N/A";
    modalIsbn.textContent = book.book_isbn || "N/A";
    modalSubject.textContent = book.subject || "N/A";
    modalPlace.textContent = book.book_place || "N/A";
    modalPublisher.textContent = book.book_publisher || "N/A";
    modalYear.textContent = book.year || "N/A";
    modalEdition.textContent = book.book_edition || "N/A";
    modalSupplementary.textContent = book.book_supplementary || "N/A";
    modalDescription.textContent = book.description || "No description.";
    const availabilityText = (book.availability || "unknown").toUpperCase();
    modalStatus.textContent = availabilityText;
    modalStatus.className = `font-semibold text-xs ${availabilityText === "AVAILABLE" ? "text-green-600" : "text-orange-600"}`;
    addToCartBtn.disabled = availabilityText !== "AVAILABLE";
    modal.classList.remove("hidden");
    requestAnimationFrame(() => {
      modal.classList.remove("opacity-0");
      modalContent.classList.remove("scale-95");
      modal.classList.add("opacity-100");
      modalContent.classList.add("scale-100");
    });
  }

  function closeModal() {
    modal.classList.remove("opacity-100");
    modalContent.classList.remove("scale-100");
    modal.classList.add("opacity-0");
    modalContent.classList.add("scale-95");
    setTimeout(() => {
      modal.classList.add("hidden");
      modalImg.src = '';
      modalImg.alt = '';
    }, 300);
  }

  if (grid) grid.addEventListener("click", e => {
    const c = e.target.closest(".book-card");
    if (!c) return;
    try {
      const b = JSON.parse(c.dataset.book);
      openModal(b);
    } catch (p) {
      console.error("Parse err:", p);
    }
  });
  if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
  if (modal) modal.addEventListener("click", e => {
    if (e.target === modal) closeModal();
  });
  document.addEventListener("keydown", e => {
    if (e.key === "Escape" && modal && !modal.classList.contains("hidden")) closeModal();
  });

  let searchTimeout;
  if (searchInput) {
    searchInput.addEventListener("input", e => {
      searchValue = e.target.value.trim();
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        currentPage = 1;
        try {
          sessionStorage.removeItem('bookCatalogPage');
        } catch (e) { }
        loadBooks(1);
      }, 500);
    });
  } else {
    console.error("nawawala yung search input");
  }

  loadAvailableCount();
  loadCart().then(() => {
    loadBooks(currentPage);
  });
});