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

<body class="min-h-screen bg-gradient-to-br from-orange-50 to-green-50 font-sans flex flex-col">

    <main class="flex-grow p-6">
        <h2 class="text-2xl font-bold mb-">Attendance Scanner</h2>
        <p class="text-gray-700">Record student attendance using QR scan or manual entry.</p>

        <!-- QR Code Scanner -->
        <div class="p-6 mt-6 border border-[var(--color-border)] rounded-lg shadow-md text-center mb-6">
            <!-- Header -->
            <div class="flex items-center justify-center gap-2 mb-2">
                <i class="ph ph-qr-code text-[var(--color-primary)] text-4xl"></i>
                <h2 class="text-4xl font-semibold">QR Code Scanner</h2>
            </div>
            <p class="text-xl text-[var(--color-gray-500)] mb-6">
                Students can scan their QR codes for automated attendance
            </p>
        </div>

        <!-- Manual Attendance Entry Card -->
        <div class="p-6 border border-orange-200 rounded-lg shadow-sm bg-white mb-6">
            <!-- Header -->
            <div class="flex items-center gap-2 mb-2">
                <h2 class="text-lg font-semibold">Manual Attendance Entry</h2>
            </div>
            <p class="text-sm text-gray-600 mb-6">
                Manually record student attendance when QR scanning is not available
            </p>

            <!-- Form -->
            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Student Number -->
                <div>
                    <label class="block text-sm font-medium mb-1">Student Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" placeholder="Enter student number"
                        class="w-full rounded-md bg-orange-50 border border-orange-200 p-2 focus:outline-none focus:ring-2 focus:ring-orange-400" />
                </div>


            <!-- Button -->
            <div class="mt-6 flex justify-center">
                <button
                    class="flex items-center gap-2 px-6 py-2 rounded-md bg-orange-500 text-white font-medium shadow hover:bg-orange-600 transition">
                    <i class="ph ph-sign-in"></i> Record Attendance
                </button>
            </div>
        </div>
    </main>

    <!-- footer -->
    <footer class="p-6 border-t border-green-200 bg-green-50 shadow-inner">
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
                    <li>Enter student number and name</li>
                    <li>Date and time filled automatically</li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>