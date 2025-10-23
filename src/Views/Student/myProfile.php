<div class="min-h-screen ">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
            My Profile
        </h2>
        <p class="text-gray-700">Manage your account information and view your activity</p>
    </div>

    <div class="bg-white px-6 py-4 max-w-max mx-auto rounded-lg shadow-md border border-gray-200">
        <h3 class="text-lg font-semibold mb-2">
            <i class="ph ph-user-circle"></i>
            Profile Information
        </h3>
        <div class="flex flex-col items-center mb-6">
            <div
                class="w-32 h-32 rounded-full bg-emerald-500 flex items-center justify-center text-white text-4xl font-bold mb-3 overflow-hidden">
                <i class="ph ph-user-circle text-5xl"></i>
                <img id="profilePreview" src="" alt="Profile" class="hidden w-full h-full object-cover">
            </div>

            <p class="text-xl font-semibold text-gray-800">STUDENT NAME</p>
            <span
                class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center gap-1 mt-1 mb-3">
                2023####-S
            </span>

            <!-- Upload Button -->
            <label for="uploadProfile" class="cursor-pointer text-sm text-green-600 font-medium hover:underline">
                Upload Image
            </label>
            <input id="uploadProfile" type="file" accept="image/*" class="hidden" required>

            <p class="text-xs text-gray-500 mt-1 inline-flex items-center gap-1">
                <i class="ph ph-info text-gray-400 text-sm relative"></i>
                Only images below 1 MB are accepted.
            </p>

        </div>


        <!-- Edit Modal -->
        <div id="cropModal" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-4 max-w-lg w-full shadow-lg">
                <h3 class="text-lg font-semibold mb-3 text-gray-700">Adjust your profile picture</h3>

                <!-- Crop area -->
                <div
                    class="w-full h-80 flex items-center justify-center border border-gray-200 mb-4 overflow-hidden rounded-lg bg-gray-50">
                    <img id="cropImage" src="" alt="To crop" class="max-h-full select-none">
                </div>

                <!-- Manual Zoom Buttons -->
                <div class="flex justify-center gap-3 mb-4">
                    <button id="zoomIn" class="px-3 py-1 border rounded text-sm hover:bg-gray-100">Zoom In</button>
                    <button id="zoomOut" class="px-3 py-1 border rounded text-sm hover:bg-gray-100">Zoom Out</button>
                    <button id="resetCrop" class="px-3 py-1 border rounded text-sm hover:bg-gray-100">Reset</button>
                </div>

                <div class="flex justify-end gap-3">
                    <button id="cancelCrop"
                        class="px-4 py-2 text-sm rounded-md border border-gray-300 hover:bg-gray-100">Cancel</button>
                    <button id="saveCrop"
                        class="px-4 py-2 text-sm rounded-md bg-orange-600 text-white hover:bg-orange-700">Save</button>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <section class="mt-6 border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                <i class="ph ph-identification-card"></i>
                Basic Information
            </h3>
            <div class="flex flex-wrap ml-9 items-start gap-x-10 gap-y-6">
                <div class="min-w-[200px]">
                    <p class="text-sm text-gray-500">Last Name</p>
                    <p class="font-medium text-gray-800">Lname</p>
                </div>

                <div class="min-w-[200px]">
                    <p class="text-sm text-gray-500">First Name</p>
                    <p class="font-medium text-gray-800">Fname</p>
                </div>

                <div class="min-w-[200px]">
                    <p class="text-sm text-gray-500">Middle Name</p>
                    <p class="font-medium text-gray-800">Mname</p>
                </div>

                <div class="min-w-[200px]">
                    <p class="text-sm text-gray-500">Suffix</p>
                    <p class="font-medium text-gray-800">N/A</p>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="mt-6 border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                <i class="ph ph-student"></i>
                Student Details
            </h3>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-6 ml-9">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">User ID</p>
                        <p class="font-medium text-gray-800">2023####-S</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Course</p>
                        <p class="font-medium text-gray-800">BSCS</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Year</p>
                        <p class="font-medium text-gray-800">3</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Section</p>
                        <p class="font-medium text-gray-800">A</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-800">student@university.edu</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Contact</p>
                        <p class="font-medium text-gray-800">09063559010</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Registration Documents -->
        <section class="mt-6 border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="ph ph-folder-notch-open text-gray-600"></i>
                Registration Documents
            </h3>

            <div class="space-y-3">
                <!-- Registration Form -->
                <div
                    class="bg-orange-50/50 border border-orange-200/80 rounded-lg p-3 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="ph ph-file-text text-2xl text-blue-500"></i>
                        <div>
                            <p class="font-medium text-gray-800">Registration Form</p>
                            <p class="text-sm text-gray-500">
                                Please upload a copy of your current semester registration form (PDF only).
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 text-green-600">
                        <input type="file" id="regFormUpload" accept="application/pdf" class="hidden" />
                        <button id="uploadBtn" onclick="document.getElementById('regFormUpload').click()"
                            class="px-4 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-100 transition">
                            Upload
                        </button>
                        <a id="viewRegForm" href="#" target="_blank"
                            class="px-4 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-100 transition hidden">
                            View
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <div class="flex justify-end gap-2 mt-3">
            <button id="saveRegForm"
                class="px-4 py-1.5 text-sm rounded-md border border-green-600 bg-green-50 text-green-700 hover:bg-green-100 active:scale-95 transition-all duration-200 hidden shadow-sm">
                Save
            </button>
            <button id="removeRegForm"
                class="px-4 py-1.5 text-sm rounded-md border border-red-600 bg-red-50 text-red-700 hover:bg-red-100 active:scale-95 transition-all duration-200 hidden shadow-sm">
                Remove
            </button>
        </div>

    </div>
</div>

<script src="/libsys/public/js/student/myProfile.js"></script>