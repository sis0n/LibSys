
<div class="flex justify-center items-center min-h-[80vh]">
  <div class="w-full max-w-md bg-white shadow-md rounded-xl p-6">
    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 flex items-center justify-center mb-2 gap-2">
      <i class="ph ph-lock text-2xl"></i>
      Change Password
    </h2>
    <p class="text-sm text-gray-500 text-center mb-5">
      Update your account password to keep your account secure.
    </p>

    <!-- Form -->
    <form id="passwordForm" action="change_password.php" method="POST" class="space-y-4">

      <!-- Current Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Current Password *</label>
        <div class="relative">
          <input type="password" name="current_password" id="current_password"
            placeholder="Enter current password" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg 
                   bg-orange-50 placeholder:text-gray-400
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">

          <button type="button" onclick="togglePassword('current_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-black hover:text-gray-700">
            <i class="ph ph-eye text-2xl"></i>
          </button>

        </div>
      </div>

      <!-- New Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">New Password *</label>
        <div class="relative">
          <input type="password" name="new_password" id="new_password"
            placeholder="Enter new password" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg 
                   bg-orange-50 placeholder:text-[#AE400E] placeholder:text-sm
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">

          <button type="button" onclick="togglePassword('new_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-black hover:text-gray-700">
            <i class="ph ph-eye text-2xl"></i>
          </button>

        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-gray-700 text-sm font-medium mb-1">Confirm New Password *</label>
        <div class="relative">
          <input type="password" name="confirm_password" id="confirm_password"
            placeholder="Confirm new password" required
            class="w-full px-4 py-2 border border-gray-200 rounded-lg 
                   bg-orange-50 placeholder:text-[#AE400E] placeholder:text-sm
                   focus:border-orange-500 focus:ring-2 focus:ring-orange-500 
                   focus:outline-none transition duration-200">

          <button type="button" onclick="togglePassword('confirm_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-black hover:text-gray-700">
            <i class="ph ph-eye text-2xl"></i>
          </button>

        </div>
      </div>

      <!-- Submit -->
      <button type="submit"
        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
        Change Password
      </button>
    </form>
  </div>
</div>

<br>


<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    if (newPass !== confirmPass) {
      event.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Passwords do not match!',
        text: 'Please make sure both new passwords are the same.',
        background: '#fff7ed',
        color: '#92400e',
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#f97316',
        customClass: {
          popup: 'rounded-2xl shadow-xl p-6 max-w-sm mx-auto border border-orange-200',
          title: 'text-orange-600 font-bold text-xl text-center',
          content: 'text-green-700 text-sm text-center mt-2',
          confirmButton: 'px-6 py-2 rounded-lg font-semibold bg-orange-500 hover:bg-orange-600 transition-colors text-white shadow-md'
        },
        buttonsStyling: false,
        reverseButtons: true
      });
    }
  });
</script>