<body class="min-h-screen p-6 bg-gray-50">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
            <i class="ph ph-user-circle text-gray-700"></i> My Profile
        </h2>
        <p class="text-gray-700">Manage your account information and view your activity</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <h3 class="text-lg font-semibold mb-2">Profile Information</h3>
        <p class="text-gray-600 mb-6">Your personal details</p>

        <div class="flex flex-col items-center mb-6">
            <div class="w-24 h-24 rounded-full bg-emerald-500 flex items-center justify-center text-white text-4xl font-bold mb-3">
                SC
            </div>
            <p class="text-xl font-semibold text-gray-800">Sarah Chen</p>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center gap-1 mt-1">
                <i class="ph ph-identification-badge text-sm"></i> Librarian
            </span>
        </div>

        <div class="space-y-4 mb-6">
            <div class="flex items-center gap-3">
                <i class="ph ph-identification-card text-xl text-gray-600"></i>
                <div>
                    <p class="text-sm text-gray-500">User ID</p>
                    <p class="font-medium text-gray-800">3</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="ph ph-envelope-simple text-xl text-gray-600"></i>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">sarah.chen@university.edu</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="ph ph-calendar-blank text-xl text-gray-600"></i>
                <div>
                    <p class="text-sm text-gray-500">Member Since</p>
                    <p class="font-medium text-gray-800">March 10, 2024</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <i class="ph ph-activity text-xl text-gray-600"></i>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6 space-y-3">
            <button class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-gray-700 font-medium">
                <i class="ph ph-pencil-simple text-lg"></i> Edit Profile
            </button>
            <button class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-gray-700 font-medium">
                <i class="ph ph-key text-lg"></i> Change Password
            </button>
        </div>
    </div>
</body>