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
  <!-- Sweet Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-orange-50 font-sans flex flex-col min-h-screen">

  <!-- Header -->
  <header
    class="sticky top-0 z-10 bg-white border-b border-orange-200 px-4 sm:px-6 flex flex-wrap justify-between items-center">
    <div class="flex items-center gap-3 sm:gap-4">
      <img src="/LibSys/assets/library-icons/apple-touch-icon.png" alt="Logo" class="h-15 m-0">
      <span class="font-semibold text-lg sm:text-xl text-orange-700 whitespace-nowrap">
        Library Online Software
      </span>
    </div>

    <div class="mt-2 sm:mt-0 flex items-center gap-4">
      <!-- Logout -->
      <form method="POST" action="/LibSys/public/logout">
        <button type="submit" class="p-2 rounded hover:bg-gray-100">
          <i class="ph ph-sign-out"></i>
        </button>
      </form>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow w-full max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-10 space-y-8">

    <!-- QR Code Scanner Form -->
    <form action="QRController.php" method="POST"
      class="bg-white shadow rounded-lg border border-orange-200 p-4 sm:p-6 text-center">

      <h2 class="text-xl sm:text-2xl font-semibold text-orange-700 mb-2">
        Attendance Scanner
      </h2>
      <p class="text-sm sm:text-md text-gray-600 mb-6">
        Students can scan their QR in student ID, for automated attendance
      </p>

      <!-- QR Box -->
      <div
        class="w-32 h-32 sm:w-40 sm:h-40 mx-auto border-2 border-dashed border-orange-300 rounded-lg flex items-center justify-center bg-orange-50">
        <i class="ph ph-qr-code text-[var(--color-primary)] text-7xl sm:text-9xl"></i>
      </div>

      <!-- Hidden input para sa scanned value -->
      <input type="hidden" id="qrCodeValue" name="qrCodeValue" value="">
    </form>

    <!-- Manual Attendance Entry -->
    <section class="bg-white shadow rounded-lg border border-orange-200 p-4 sm:p-6">
      <h2 class="text-lg sm:text-xl font-semibold text-orange-700">
        Manual Attendance Entry
      </h2>
      <p class="text-sm text-gray-600 mb-6">
        Manually record student attendance when you don't have ID or QR scanning is not available
      </p>

      <form onsubmit="event.preventDefault(); recordAttendance();" class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Student Number -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Student Number</label>
          <input type="text" id="studentNumber" placeholder="Enter student number" class="text-sm text-gray-700 w-full px-3 py-2 border border-orange-200 rounded-md 
            focus:ring-2 focus:ring-orange-400 focus:outline-none bg-orange-50">
        </div>

        <!-- Student Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
          <input type="text" id="studentName" placeholder="Enter student name" class="text-sm text-gray-700 w-full px-3 py-2 border border-orange-200 rounded-md 
            focus:ring-2 focus:ring-orange-400 focus:outline-none bg-orange-50">
        </div>

        <!-- Submit -->
        <div class="md:col-span-2 flex justify-center mt-4">
          <button type="submit"
            class="px-4 sm:px-5 py-2 bg-orange-500 text-white text-sm sm:text-base font-medium rounded-md hover:bg-orange-600 transition">
            Record Attendance
          </button>
        </div>
      </form>
    </section>
  </main>

  <!-- Footer -->
  <footer class="mt-auto p-4 sm:p-6 border-t border-green-200 bg-green-50 shadow-inner">
    <h3 class="font-semibold mb-3 sm:mb-4 flex items-center gap-2">
      <i class="ph ph-info text-green-600"></i>
      Instructions
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-sm">
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

  <script>
    function recordAttendance() {
      const studentNumber = document.getElementById("studentNumber").value.trim();
      const studentName = document.getElementById("studentName").value.trim();

      if (studentNumber === "" || studentName === "" || studentNumber === "0000") {
        Swal.fire({
          icon: "error",
          title: "Attendance Failed!",
          html: `
            <div style="text-align:left; font-size:14px; line-height:1.6;">
              <p><b>Reason:</b> Student not found in records</p>
              <p><b>Entered Number:</b> ${studentNumber || "N/A"}</p>
              <p><b>Entered Name:</b> ${studentName || "N/A"}</p>
            </div>
          `,
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          position: "center",
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "Attendance Recorded!",
          html: `
            <div style="text-align:left; font-size:14px; line-height:1.6;">
              <p><b>Student Name:</b> ${studentName}</p>
              <p><b>Student Number:</b> ${studentNumber}</p>
              <p><b>Course:</b> BSCS</p>
              <p><b>Year & Section:</b> 3-A</p>
            </div>
          `,
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          position: "center",
        });
      }
    }
  </script>
</body>

</html>