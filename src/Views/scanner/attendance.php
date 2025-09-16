<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library | Login</title>
    <!-- Tailwind CSS -->
    <link href="/LibSys/public/css/output.css" rel="stylesheet">
    <!-- PHOSPHOR ICONS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" />
</head>

<body class="bg-orange-50 font-sans flex flex-col min-h-screen">
    <!-- Header -->
    <header class="flex justify-between items-center px-6 py-4 border-b border-orange-200">
        <h1 class="flex items-center text-lg font-semibold text-orange-700">
            UCC Library Attendance
        </h1>
        <form method="POST" action="/LibSys/public/logout">
            <button type="submit" class="p-2 rounded hover:bg-gray-100">
                <i class="ph ph-sign-out"></i>
            </button>
        </form>
    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-5xl mx-auto py-10 space-y-8">

        <!-- QR Code Scanner -->
        <section class="bg-white shadow rounded-lg border border-orange-200 p-4 text-center">
            <h2 class="text-2xl font-semibold text-orange-700 flex items-center justify-center mb-2">
                QR Code Scanner
            </h2>
            <p class="text-md text-gray-600 mb-6"> Students can scan their QR in student ID, for automated attendance
            </p>

            <!-- QR Box -->
            <div
                class="w-40 h-40 mx-auto border-2 border-dashed border-orange-300 rounded-lg flex items-center justify-center bg-orange-50 ">
                <i class="ph ph-qr-code text-[var(--color-primary)] text-9xl"></i>
            </div>
        </section>

        <!-- Manual Attendance Entry -->
        <section class="bg-white shadow rounded-lg border border-orange-200 p-6">
            <h2 class="text-lg font-semibold text-orange-700 flex items-center">
                Manual Attendance Entry
            </h2>
            <p class="text-sm text-gray-600 mb-6">Manually record student attendance when you don't have ID or QR
                scanning is not available
            </p>

            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Student Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Number</label>
                    <input id="studentNumber" type="text" placeholder="Enter student number"
                        class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-2 focus:ring-orange-400 bg-orange-50"
                        oninput="validateForm()">
                </div>
                <!-- Student Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
                    <input id="studentName" type="text" placeholder="Enter student name"
                        class="w-full px-3 py-2 border border-orange-200 rounded-md focus:ring-2 focus:ring-orange-400 bg-orange-50"
                        oninput="validateForm()">
                </div>
                <!-- Submit -->
                <div class="md:col-span-2 flex justify-center mt-4">
                    <button id="submitBtn" type="button" onclick="showToast()"
                        class="px-5 py-2 bg-orange-500 text-white font-medium rounded-md hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        Record Attendance
                    </button>
                </div>
            </form>
        </section>
    </main>
    <!-- Footer -->
    <footer class="mt-auto p-6 border-t border-green-200 bg-green-50 shadow-inner">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i class="ph ph-info text-green-600"></i>
            Instructions
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <!-- QR Code Scanning -->
            <div>
                <h4 class="font-medium flex items-center gap-2 text-orange-600">
                    <i class="ph ph-qr-code"></i> QR Code Scanning:
                </h4>
                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-700">
                    <li>Students should use the "Scan QR Code" button</li>
                    <li>System will automatically record attendance</li>
                    <li>Fast and efficient for regular use</li>
                </ul>
            </div>

            <!-- Manual Entry -->
            <div>
                <h4 class="font-medium flex items-center gap-2 text-green-600">
                    <i class="ph ph-pencil-simple"></i> Manual Entry:
                </h4>
                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-700">
                    <li>Use when QR scanning is unavailable</li>
                    <li>Enter student number or name</li>
                    <li>Date and time filled automatically</li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- Toast Container -->
    <div id="toast" class="fixed top-5 right-5 z-50 hidden">
        <div
            class="flex items-center w-full max-w-xs p-4 text-gray-700 bg-green-100 rounded-lg shadow-lg border border-green-300">
            <i class="ph ph-check-circle text-green-600 text-xl mr-3"></i>
            <div>
                <p class="font-semibold">Attendance Recorded</p>
                <!-- dito ilalagay dynamic message -->
                <p id="toastMessage" class="text-sm"></p>
            </div>
        </div>
    </div>

    <script>

        // pang validate ng form
        function validateForm() {
            const studentNumber = document.getElementById("studentNumber").value.trim();
            const studentName = document.getElementById("studentName").value.trim();
            const submitBtn = document.getElementById("submitBtn");

            // enable button kung may laman pareho
            if (studentNumber !== "" || studentName !== "") {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
        // para sa toast notif
        function showToast() {
            const studentNumber = document.getElementById("studentNumber").value.trim();
            const studentName = document.getElementById("studentName").value.trim();

            const message = `${studentName} ${studentNumber} – Successful Attendance`;
            document.getElementById("toastMessage").textContent = message;

            const toast = document.getElementById("toast");
            toast.classList.remove("hidden");

            // auto-hide after 3s
            setTimeout(() => {
                toast.classList.add("hidden");
            }, 3000);

            // clear fields + disable ulit button
            document.getElementById("studentNumber").value = "";
            document.getElementById("studentName").value = "";
            validateForm();
        }

    </script>
</body>

</html>