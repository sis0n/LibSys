<!-- Outside Container -->
<div class="flex flex-col items-center mt-6 min-h-[80vh]">

  <!-- Title -->
  <div>
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
      <i class="ph ph-lock-simple text-2xl"></i>
      Change Password
    </h1>
    <p class="text-sm text-gray-500 mt-1 text-center">
      Update your account password to keep your account secure.
    </p>
  </div>

  <!-- Card/Form -->
  <div class="max-w-[550px] bg-white shadow-md rounded-xl p-6 mt-4">

    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-2 gap-2">
      <i class="ph ph-shield text-2xl"></i>
      Update Password
    </h2>

    <p class="text-sm text-gray-500 text-center mb-5">
      Update your account password to keep your account secure.
    </p>

    <!-- Form -->
    <form id="passwordForm" action="change_password.php" method="POST" class="space-y-4">

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
            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-[#AE400E] placeholder:text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('current_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-600 hover:bg-orange-500 hover:text-white transition">
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
            id="new_password"
            placeholder="Enter new password"
            required
            class="w-full px-3 py-1.5 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-[#AE400E] placeholder:text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('new_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-600 hover:bg-orange-500 hover:text-white transition">
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
            class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-orange-50 placeholder:text-[#AE400E] placeholder:text-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none transition duration-200">
          <button
            type="button"
            onclick="togglePassword('confirm_password', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-600 hover:bg-orange-500 hover:text-white transition">
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
