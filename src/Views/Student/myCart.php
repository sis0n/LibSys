 <body class="min-h-screen p-6">

     <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-4">My Cart</h2>
            <p class="text-gray-700">Review and checkout your selected books.</p>
        </div>
         <span
             class="px-[var(--spacing-3)] py-[var(--spacing-1)] rounded-md border text-[var(--font-size-sm)] text-[var(--color-foreground)] border-[var(--color-border)] bg-white shadow-sm flex items-center gap-[var(--spacing-1)]">
             <i class="ph ph-shopping-cart text-xs"></i>
             0 total items
         </span>
     </div>

     <!-- Empty State -->
     <div
         class="mt-2 border rounded-[var(--radius-lg)] border-[var(--color-border)] bg-white shadow-sm flex flex-col items-center justify-center py-9">
         <i
             class="ph ph-shopping-cart text-6xl text-amber-700 mb-3"></i>
         <p class="text-[var(--color-foreground)] font-medium text-[var(--font-size-base)]">Your cart is empty</p>
         <p class="text-amber-700 text-[var(--font-size-sm)]">
             Add books or equipment from the catalog to get started.
         </p>
     </div>