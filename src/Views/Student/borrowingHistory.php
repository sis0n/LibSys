<body class="min-h-screen p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">My Borrowing History</h2>
        <p class="text-gray-700">Complete record of your library borrowing activity with detailed information.</p>
    </div>

    <div class="p-6 space-y-6">

        <div class="p-6 space-y-6">

            <!-- Top Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Total Borrowed -->
                <div
                    class="relative bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-md p-4 border-l-4 border-l-[var(--color-primary)]">
                    <p class="text-sm text-gray-600">Total Borrowed</p>
                    <h2 class="text-3xl font-bold text-[var(--color-primary)]">3</h2>
                    <p class="text-xs text-gray-500">All time</p>
                    <i class="ph ph-books absolute top-3 right-3 text-[var(--color-primary)] text-xl"></i>
                </div>

                <!-- Currently Borrowed -->
                <div
                    class="relative bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-md p-4 border-l-4 border-l-[var(--color-green-600)]">
                    <p class="text-sm text-gray-600">Currently Borrowed</p>
                    <h2 class="text-3xl font-bold text-[var(--color-green-600)]">1</h2>
                    <p class="text-xs text-gray-500">Active books</p>
                    <i class="ph ph-eye absolute top-3 right-3 text-[var(--color-green-600)] text-xl"></i>
                </div>

                <!-- Overdue -->
                <div
                    class="relative bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-md p-4 border-l-4 border-l-[var(--color-destructive)]">
                    <p class="text-sm text-gray-600">Overdue</p>
                    <h2 class="text-3xl font-bold text-[var(--color-destructive)]">1</h2>
                    <p class="text-xs text-gray-500">Need attention</p>
                    <i class="ph ph-warning-circle absolute top-3 right-3 text-[var(--color-destructive)] text-xl"></i>
                </div>

                <!-- Returned -->
                <div
                    class="relative bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-md p-4 border-l-4 border-l-[var(--color-accent)]">
                    <p class="text-sm text-gray-600">Returned</p>
                    <h2 class="text-3xl font-bold text-[var(--color-accent)]">1</h2>
                    <p class="text-xs text-gray-500">Completed</p>
                    <i class="ph ph-check-circle absolute top-3 right-3 text-[var(--color-accent)] text-xl"></i>
                </div>

            </div>


            <!-- Borrowing Records -->
            <div class="bg-[var(--color-card)] border border-[var(--color-border)] rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-1 flex items-center gap-2">
                    <i class="ph ph-calendar text-[var(--color-primary)]"></i>
                    Borrowing Records
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Complete history of your book borrowings with detailed information
                </p>

                <!-- Record List -->
                <div class="space-y-6">

                    <!-- Book 1 -->
                    <div
                        class="relative rounded-lg p-4 border border-[var(--color-border)] bg-[var(--color-card)] shadow-sm">
                        <!-- Status -->
                        <span
                            class="absolute top-3 right-3 bg-[var(--color-green-100)] text-[var(--color-green-700)] text-xs font-medium px-3 py-1 rounded-full">
                            Returned
                        </span>

                        <h4 class="font-semibold text-[var(--color-foreground)]">Introduction to Computer Science</h4>
                        <p class="text-sm text-gray-600 mb-3">by John Smith</p>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="bg-[var(--color-orange-50)] p-2 rounded text-sm">
                                <i class="ph ph-book text-[var(--color-orange-600)] mr-1"></i>
                                Borrowed<br><span class="font-medium">Jul 28, 2025</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-calendar-blank text-gray-600 mr-1"></i>
                                Due Date<br><span class="font-medium">Aug 11, 2025</span>
                            </div>
                            <div class="bg-[var(--color-green-50)] p-2 rounded text-sm">
                                <i class="ph ph-check-circle text-[var(--color-green-600)] mr-1"></i>
                                Returned<br><span class="font-medium">Aug 1, 2025</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-user text-gray-600 mr-1"></i>
                                Staff<br><span class="font-medium">Sarah Chen</span>
                            </div>
                        </div>
                    </div>

                    <!-- Book 2 -->
                    <div
                        class="relative rounded-lg p-4 border border-[var(--color-border)] bg-[var(--color-card)] shadow-sm">
                        <!-- Status -->
                        <span
                            class="absolute top-3 right-3 bg-[var(--color-destructive)] text-white text-xs font-medium px-3 py-1 rounded-full">
                            Overdue (+42d)
                        </span>

                        <h4 class="font-semibold text-[var(--color-foreground)]">Data Structures and Algorithms</h4>
                        <p class="text-sm text-gray-600 mb-3">by Jane Doe</p>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="bg-[var(--color-orange-50)] p-2 rounded text-sm">
                                <i class="ph ph-book text-[var(--color-orange-600)] mr-1"></i>
                                Borrowed<br><span class="font-medium">Jul 25, 2025</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-calendar-blank text-gray-600 mr-1"></i>
                                Due Date<br><span class="font-medium">Aug 8, 2025</span>
                            </div>
                            <div class="bg-[var(--color-green-50)] p-2 rounded text-sm">
                                <i class="ph ph-check-circle text-gray-400 mr-1"></i>
                                Returned<br><span class="font-medium">Not returned</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-user text-gray-600 mr-1"></i>
                                Staff<br><span class="font-medium">Sarah Chen</span>
                            </div>
                        </div>
                    </div>

                    <!-- Book 3 -->
                    <div
                        class="relative rounded-lg p-4 border border-[var(--color-border)] bg-[var(--color-card)] shadow-sm">
                        <!-- Status -->
                        <span
                            class="absolute top-3 right-3 bg-[var(--color-amber-100)] text-[var(--color-amber-700)] text-xs font-medium px-3 py-1 rounded-full">
                            Borrowed
                        </span>

                        <h4 class="font-semibold text-[var(--color-foreground)]">Modern Physics</h4>
                        <p class="text-sm text-gray-600 mb-3">by Robert Johnson</p>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="bg-[var(--color-orange-50)] p-2 rounded text-sm">
                                <i class="ph ph-book text-[var(--color-orange-600)] mr-1"></i>
                                Borrowed<br><span class="font-medium">Jul 30, 2025</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-calendar-blank text-gray-600 mr-1"></i>
                                Due Date<br><span class="font-medium">Aug 13, 2025</span>
                            </div>
                            <div class="bg-[var(--color-green-50)] p-2 rounded text-sm">
                                <i class="ph ph-check-circle text-gray-400 mr-1"></i>
                                Returned<br><span class="font-medium">Not returned</span>
                            </div>
                            <div class="bg-[var(--color-gray-100)] p-2 rounded text-sm">
                                <i class="ph ph-user text-gray-600 mr-1"></i>
                                Staff<br><span class="font-medium">Mike Wilson</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


</body>