<!-- Custom placeholder style -->


<!-- Outside Container -->
<div class="flex flex-col items-center mt-6 mb-6">

  <!-- Title (same container wdth para align with form) -->
  <div class="w-full max-w-[550px]">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
      Change Password
    </h1>
    <p class="text-sm text-gray-500 mt-1">
      Update your account password to keep your account secure.
    </p>
  </div>

  <!-- Card/Form -->
  <div class="w-full max-w-[550px] bg-white shadow-md rounded-xl p-6 mt-4">
    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-2 gap-2">
      <i class="ph ph-shield text-2xl"></i>
      Update Password
    </h2>
    <p class="text-sm text-gray-500 mb-5">
      Update your account password to keep your account secure.
    </p>

    <!-- Form -->
    <form id="passwordForm" action="/libsys/public/change-password" method="POST" class="space-y-4">


      <!-- Current Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">
          Current Password *
        </label>
        <div class="relative">
          <input
            type="password"
            name="current_password"
            id="current_password"
            placeholder="Enter current password"
            required
            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-orange-500 placeholder:text-sm
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('current_password', this)"
            class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-2 rounded-lg text-gray-600 hover:bg-orange-500 hover:text-white transition">
            <i class="ph ph-eye text-sm"></i>
          </button>
        </div>
      </div>

      <!-- New Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">
          New Password *
        </label>
        <div class="relative">
          <input
            type="password"
            name="new_password"
            placeholder="Enter new password"
            id="new_password"
            required
            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-orange-500 placeholder:text-sm
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('new_password', this)"
            class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-2 rounded-lg text-gray-600 hover:bg-orange-500 hover:text-white transition">
            <i class="ph ph-eye text-sm"></i>
          </button>
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">
          Confirm New Password *
        </label>
        <div class="relative">
          <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            placeholder="Confirm new password"
            required
            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-orange-500 placeholder:text-sm
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('confirm_password', this)"
            class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-2 rounded-lg text-gray-600 hover:bg-orange-500 hover:text-white transition">
            <i class="ph ph-eye text-sm"></i>
          </button>
        </div>

        <!-- Error Message -->
        <p id="errorMessage" class="text-red-600 text-sm mt-1 hidden">
          Passwords do not match!
        </p>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
        Change Password
      </button>
    </form>
  </div>
</div>

<!-- Security Section -->
<div class="flex justify-center px-4 mt-0">
  <div class="w-full max-w-[550px] bg-white shadow-md rounded-xl p-4">

    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2 mb-5">
      <i class="ph ph-shield-check text-2xl text-orange-500"></i>
      Password Security
    </h2>

    <!-- Two columns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- Column 1: Security Tips -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2 p-2 rounded ">
          <i class="ph ph-check-circle text-green-600 text-2xl"></i>
          Security Tips
        </h3>
        <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
          <li>At least 6 characters long</li>
          <li>Mix of letters, numbers, and symbols</li>
          <li>Avoid common words or phrases</li>
          <li>Don't use personal information</li>
        </ul>
      </div>

      <!-- Column 2: Security Reminders -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2 p-2 rounded ">
          <i class="ph ph-info text-orange-500 text-2xl"></i>
          Security Reminders
        </h3>
        <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
          <li>Never share your password</li>
          <li>Log out from public computers</li>
          <li>Change password if compromised</li>
          <li>Use different passwords for different accounts</li>
        </ul>
      </div>

    </div>
  </div>
</div>



<script>
  // Toggle Password Function
  function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("ph-eye");
      icon.classList.add("ph-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("ph-eye-slash");
      icon.classList.add("ph-eye");
    }
  }

  // Validate Passwords
  document.getElementById("passwordForm").addEventListener("submit", function(event) {
    const newPass = document.getElementById("new_password").value;
    const confirmPass = document.getElementById("confirm_password").value;
    const errorMsg = document.getElementById("errorMessage");

    if (newPass !== confirmPass) {
      event.preventDefault(); // stop form submit
      errorMsg.classList.remove("hidden"); // show error
    } else {
      errorMsg.classList.add("hidden"); // hide error if valid
    }
  });

  
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById("passwordForm").addEventListener("submit", function(event) {
    event.preventDefault(); // stop page reload

    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Password Changed",
          text: data.message,
          width: 500,                // maliit na box gaya ng sample
          padding: "1em",
          background: "#d4edda",     // light green background (same family ng sample)
          color: "#155724",          // dark green text
          iconColor: "#28a745",      // success green
          confirmButtonText: "OK",
          confirmButtonColor: "#28a745",
          customClass: {
            popup: "rounded-lg shadow-md border border-green-300",
            title: "text-base font-bold",
            confirmButton: "px-4 py-1 text-sm rounded-md"
          }
        });
        form.reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Update Failed",
          text: data.message,
          width: 320,
          padding: "1em",
          background: "#f8d7da",     // light red background
          color: "#721c24",          // dark red text
          iconColor: "#dc3545",      // error red
          confirmButtonText: "OK",
          confirmButtonColor: "#dc3545",
          customClass: {
            popup: "rounded-lg shadow-md border border-red-300",
            title: "text-base font-bold",
            confirmButton: "px-4 py-1 text-sm rounded-md"
          }
        });
      }
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong!",
        width: 320,
        padding: "1em",
        background: "#f8d7da",
        color: "#721c24",
        iconColor: "#dc3545",
        confirmButtonColor: "#dc3545",
        customClass: {
          popup: "rounded-lg shadow-md border border-red-300",
          title: "text-base font-bold"
        }
      });
    });
  });
</script>





