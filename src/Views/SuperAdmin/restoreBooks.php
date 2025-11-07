<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Generate CSRF token if it doesn’t exist
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
// var_dump($csrf_token, $_SESSION['csrf_token']); 
?>
<main class="min-h-screen">
  <!-- Header -->
  <div class="flex items-center gap-3 mb-6">
    <i class="ph-fill ph-book-open-text text-3xl text-gray-700"></i>
    <div>
      <h2 class="text-2xl font-bold mb-1">Restore Books</h2>
      <p class="text-gray-500">View, restore, or permanently archive soft-deleted book records.</p>
    </div>
  </div>

  <!-- Search & Filter Section -->
  <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
    <div class="flex items-center gap-2 mb-4">
      <i class="ph ph-funnel text-xl text-orange-700"></i>
      <h3 class="text-lg font-semibold text-orange-700">Search & Filter</h3>
    </div>
    <div class="flex items-center gap-4">
      <!-- Search Bar -->
      <div class="relative flex-grow">
        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="bookSearchInput" placeholder="Search by title, author, or accession number..."
          class="bg-orange-50 border border-orange-200 rounded-lg pl-11 pr-4 py-2.5 w-full text-sm outline-none transition">
      </div>
      <!-- Filter Dropdown -->
      <div class="relative">
        <button id="bookFilterDropdownBtn"
          class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 text-sm text-gray-700 flex items-center gap-2 w-44 justify-between">
          <span>Default Order</span>
          <i class="ph ph-caret-down"></i>
        </button>

        <!-- Dropdown menu -->
        <div id="bookFilterDropdownMenu"
          class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden z-10">
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
            data-value="Default Order">Default Order</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
            data-value="Title (A-Z)">Title (A-Z)</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
            data-value="Title (Z-A)">Title (Z-A)</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
            data-value="Deleted Date (Newest)">Deleted Date (Newest)</a>
          <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50"
            data-value="Deleted Date (Oldest)">Deleted Date (Oldest)</a>
        </div>
      </div>

      <!-- Calendar Filter -->
      <div class="relative">
        <input type="date" id="deletedBookDateFilter"
          class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 outline-none transition text-sm text-gray-700 w-40 focus:ring-1 focus:ring-orange-400">
      </div>
    </div>
  </div>

  <!-- Deleted Books Table Section -->
  <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
    <div class="flex items-center gap-2 mb-4">
      <i class="ph ph-book-bookmark text-xl text-orange-700"></i>
      <h3 class="text-lg font-semibold text-orange-700">Deleted Books (<span id="deletedBooksCount">0</span>)</h3>
    </div>
    <p class="text-gray-600 mb-6">Books that have been soft-deleted can be restored or permanently archived.</p>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-orange-100">
          <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[30%]">
              Book Title
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
              Author
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
              Accession No.
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
              Deleted Date
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%]">
              Deleted By
            </th>
            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">
              Actions
            </th>
          </tr>
        </thead>
        <tbody id="deletedBooksTableBody" class="bg-white divide-y divide-gray-200"></tbody>
      </table>
      <div id="noDeletedBooksFound" class="hidden flex items-center justify-center h-60 w-full text-gray-500">
        <div class="flex flex-col items-center justify-center">
          <i class="ph ph-book-open-text text-4xl mb-2 text-gray-400"></i>
          <p>No deleted books found.</p>
        </div>
      </div>
    </div>

    <!-- Pagination Controls -->
    <div id="pagination-container" class="flex justify-center items-center mt-6 hidden">
      <nav class="bg-white px-6 py-2 rounded-full shadow-sm border border-gray-200">
        <ul class="flex items-center gap-2 text-sm">
          <li>
            <a href="#" id="prev-page" class="flex items-center gap-1 text-gray-500 hover:text-gray-800 transition p-2">
              <i class="ph ph-caret-left"></i>
              <span>Previous</span>
            </a>
          </li>
          <div id="pagination-numbers" class="flex items-center gap-1">
          </div>
          <li>
            <a href="#" id="next-page" class="flex items-center gap-1 text-gray-500 hover:text-gray-800 transition p-2">
              <span>Next</span>
              <i class="ph ph-caret-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</main>


<template id="deleted-book-row-template">
  <tr class="hover:bg-orange-50/50 cursor-pointer deleted-book-row">
    <td class="px-4 py-3 align-top">
      <p class="font-medium text-gray-800 break-words book-title"></p>
      <p class="text-xs text-gray-500 book-isbn"></p>
    </td>
    <td class="px-4 py-3 align-top text-gray-600 break-words book-author"></td>
    <td class="px-4 py-3 align-top text-gray-600 book-accession-number"></td>
    <td class="px-4 py-3 align-top text-gray-600 book-deleted-date"></td>
    <td class="px-4 py-3 align-top text-gray-600 book-deleted-by"></td>
    <td class="px-4 py-3 align-top text-center inline-flex">
      <button
        class="restore-btn inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 mr-2">
        <i class="ph ph-arrow-counter-clockwise text-lg mr-1"></i> Restore
      </button>
      <button
        class="archive-btn inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
        <i class="ph ph-archive text-lg mr-1"></i> Archive
      </button>
    </td>
  </tr>
</template>
<script src="<?= BASE_URL ?>/js/superadmin/restoreBooks.js"></script>

<!-- Book Details Modal -->
<div id="bookDetailsModal"
  class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-lg flex flex-col">
    <!-- Header -->
    <div class="px-5 py-3 border-b border-gray-200 flex justify-between items-center">
      <div>
        <h3 class="text-xl font-semibold text-orange-600">Deleted Book Details</h3>
        <p class="text-sm text-gray-500">Information about this deleted book</p>
      </div>
      <button id="closeBookDetailsModalBtn" class="text-gray-400 hover:text-gray-600 transition">
        <i class="ph ph-x text-xl"></i>
      </button>
    </div>

    <!-- Content -->
    <div class="p-5 space-y-4 overflow-y-auto max-h-[80vh]">
      <!-- Book Info -->
      <div class="border border-gray-200 rounded-lg p-3 shadow-sm">
        <h4 class="text-xs font-semibold text-orange-500 mb-2 uppercase tracking-wider">Book Information</h4>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
          <div>
            <p class="text-gray-500">Accession No.</p>
            <p id="modalBookAccessionNumber" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-gray-500">Call No.</p>
            <p id="modalBookCallNumber" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-gray-500">ISBN</p>
            <p id="modalBookIsbn" class="font-medium text-gray-800"></p>
          </div>
          <div class="col-span-2">
            <p class="text-gray-500">Title</p>
            <p id="modalBookTitle" class="font-medium text-gray-800"></p>
          </div>
          <div class="col-span-2">
            <p class="text-gray-500">Author(s)</p>
            <p id="modalBookAuthor" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-gray-500">Publisher</p>
            <p id="modalBookPublisher" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-gray-500">Year</p>
            <p id="modalBookYear" class="font-medium text-gray-800"></p>
          </div>

          <div class="col-span-2">
            <p class="text-gray-500">Subject</p>
            <p id="modalBookSubject" class="font-medium text-gray-800"></p>
          </div>
          <div class="col-span-2">
            <p class="text-gray-500">Place of Publication</p>
            <p id="modalBookPlace" class="font-medium text-gray-800"></p>
          </div>
        </div>
      </div>

      <!-- Deletion Info -->
      <div class="border border-gray-200 rounded-lg p-3 shadow-sm">
        <h4 class="text-xs font-semibold text-orange-500 mb-2 uppercase tracking-wider">Deletion Details</h4>
        <div class="grid grid-cols-2 gap-3 text-xs">
          <div>
            <p class="text-gray-500">Created Date</p>
            <p id="modalBookCreatedDate" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-gray-500">Deleted Date</p>
            <p id="modalBookDeletedDate" class="font-medium text-gray-800"></p>
          </div>
          <div class="col-span-2">
            <p class="text-gray-500">Deleted By</p>
            <p id="modalBookDeletedBy" class="font-medium text-gray-800"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Idinagdag ang hidden input field para sa CSRF Token -->
<input type="hidden" id="csrf_token" value="<?= $csrf_token ?? '' ?>">