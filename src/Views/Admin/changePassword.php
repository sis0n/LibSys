<div class="flex justify-center items-start">
  <!-- Column 1: Change Password -->
  <div class="w-full md:w-[650px] flex flex-col items-center">

    <!-- Title -->
    <div class="w-full text-center md:text-left">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-center md:justify-start gap-2">
        Change Password
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        Update your account password to keep your account secure.
      </p>
    </div>

    <!-- Card/Form -->
    <div class="w-full bg-white shadow-md rounded-xl p-6 mt-4">
      <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-2 gap-2">
        Update Password
      </h2>
      <p class="text-sm text-gray-500 mb-5">
        Update your account password to keep your account secure.
      </p>

      <form id="passwordForm" action="<?= BASE_URL ?>/change-password" method="POST" class="space-y-4">

        <!-- Current Password -->
        <div>
          <label class="block text-gray-700 text-sm font-medium mb-1">
            Current Password <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              name="current_password"
              id="current_password"
              placeholder="Enter current password"
              required
              class="w-full bg-orange-50 border border-orange-300 rounded-md px-3 py-2 text-sm 
                     placeholder:text-orange-500 placeholder:text-sm 
                     focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none 
                     hover:border-orange-400 transition-colors duration-200 pr-10">
            <button
              type="button"
              onclick="togglePassword('current_password', this)"
              class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500 transition-colors duration-200">
              <i class="ph ph-eye"></i>
            </button>
          </div>
        </div>

        <!-- New Password -->
        <div>
          <label class="block text-gray-700 text-sm font-medium mb-1">
            New Password  <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              name="new_password"
              id="new_password"
              placeholder="Enter new password"
              required
              class="w-full bg-orange-50 border border-orange-300 rounded-md px-3 py-2 text-sm 
                     placeholder:text-orange-500 placeholder:text-sm 
                     focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none 
                     hover:border-orange-400 transition-colors duration-200 pr-10">
            <button
              type="button"
              onclick="togglePassword('new_password', this)"
              class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500 
                     transition-colors duration-300 ease-in-out">
              <i class="ph ph-eye text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-gray-700 text-sm font-medium mb-1">
            Confirm New Password  <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input
              type="password"
              name="confirm_password"
              id="confirm_password"
              placeholder="Confirm new password"
              required
              class="w-full bg-orange-50 border border-orange-300 rounded-md px-3 py-2 text-sm 
                     placeholder:text-orange-500 placeholder:text-sm 
                     focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none 
                     hover:border-orange-400 transition-colors duration-200 pr-10">
            <button
              type="button"
              onclick="togglePassword('confirm_password', this)"
              class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500 
                     transition-colors duration-300 ease-in-out">
              <i class="ph ph-eye text-sm"></i>
            </button>
          </div>
        </div>

        <!-- Error Message -->
        <p id="errorMessage" class="text-red-600 text-sm mt-1 hidden text-center md:text-left">
          Passwords do not match!
        </p>

        <!-- Submit -->
        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
          Change Password
        </button>

        <!-- Tips Section -->
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mt-6">
          <h2 class="text-xl font-semibold flex items-center gap-2 mb-5 text-amber-600 text-center md:text-left">
            Password Security
          </h2>

          <!-- Responsive grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Column 1: Security Tips -->
            <div>
              <h3 class="text-base font-semibold mb-2 flex items-center gap-2 text-orange-700 text-center sm:text-left">
                Security Tips
              </h3>
              <ul class="list-disc list-inside text-amber-600 space-y-1 text-xs text-center sm:text-left">
                <li>At least 6 characters long</li>
                <li>Mix of letters, numbers, and symbols</li>
                <li>Avoid common words or phrases</li>
                <li>Don't use personal information</li>
              </ul>
            </div>

            <!-- Column 2: Security Reminders -->
            <div>
              <h3 class="text-base font-semibold mb-2 flex items-center gap-2 text-orange-700 text-center sm:text-left">
                Security Reminders
              </h3>
              <ul class="list-disc list-inside text-amber-600 space-y-1 text-xs text-center sm:text-left">
                <li>Never share your password</li>
                <li>Log out from public computers</li>
                <li>Change password if compromised</li>
                <li>Use different passwords for different accounts</li>
              </ul>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>

</div>

<!-- JS: Toggle Password Visibility -->
<script>
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

  // Validate Password Match
  document.getElementById("passwordForm").addEventListener("submit", function(event) {
    const newPass = document.getElementById("new_password").value;
    const confirmPass = document.getElementById("confirm_password").value;
    const errorMsg = document.getElementById("errorMessage");

    if (newPass !== confirmPass) {
      event.preventDefault();
      errorMsg.classList.remove("hidden");
    } else {
      errorMsg.classList.add("hidden");
    }
  });
</script>

<!-- SweetAlert Script -->
<script>
  document.getElementById("passwordForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const form = this;
    const formData = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: "POST",
        body: formData
      });

      const data = await res.json();
      let timerInterval;

      const showAlert = (isSuccess) => {
        Swal.fire({
          position: "center",
          showConfirmButton: false,
          backdrop: `
            rgba(0,0,0,0.3)
            backdrop-filter: blur(6px)
          `,
          timer: 2000,
          didOpen: () => {
            const progressBar = Swal.getHtmlContainer().querySelector("#progress-bar");
            let width = 100;
            timerInterval = setInterval(() => {
              width -= 100 / 20;
              if (progressBar) {
                progressBar.style.width = width + "%";
              }
            }, 100);
          },
          willClose: () => {
            clearInterval(timerInterval);
            form.reset();
          },
          html: isSuccess ? `
            <div class="w-[450px] bg-orange-50 border-2 border-orange-300 rounded-2xl p-8 shadow-lg text-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mx-auto mb-4">
                <i class="ph ph-user-check text-orange-600 text-3xl"></i>
              </div>
              <h3 class="text-2xl font-bold text-orange-700">Password Changed</h3>
              <div class="text-base text-orange-700 mt-3 space-y-1">
                <p>${data.message}</p>
              </div>
              <div class="w-full bg-orange-100 h-2 rounded mt-4 overflow-hidden">
                <div id="progress-bar" class="bg-orange-500 h-2 w-full transition-all"></div>
              </div>
            </div>
          ` : `
            <div class="w-[450px] bg-red-50 border-2 border-red-300 rounded-2xl p-8 shadow-lg text-center">
              <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mx-auto mb-4">
                <i class="ph ph-x-circle text-red-600 text-3xl"></i>
              </div>
              <h3 class="text-2xl font-bold text-red-700">Update Failed</h3>
              <div class="text-base text-red-700 mt-3 space-y-1">
                <p>${data.message}</p>
              </div>
              <div class="w-full bg-red-100 h-2 rounded mt-4 overflow-hidden">
                <div id="progress-bar" class="bg-red-500 h-2 w-full transition-all"></div>
              </div>
            </div>
          `,
          customClass: {
            popup: "block !bg-transparent !shadow-none !p-0 !border-0 !m-0 !w-auto !min-w-0 !max-w-none",
          }
        });
      };

      if (data.status === "success") {
        showAlert(true);
      } else {
        showAlert(false);
      }

    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong while changing your password.",
        confirmButtonColor: "#dc3545",
      });
    }
  });
</script>