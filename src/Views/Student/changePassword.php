<div class="flex justify-center items-center min-h-[80vh]">
    <div class="w-full max-w-md bg-white shadow-md rounded-xl p-6">
        <!-- Header -->
        <h2 class="text-xl font-semibold text-gray-800 flex items-center justify-center mb-2 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#000000" viewBox="0 0 256 256"><path d="M208,80H176V56a48,48,0,0,0-96,0V80H48A16,16,0,0,0,32,96V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V96A16,16,0,0,0,208,80ZM96,56a32,32,0,0,1,64,0V80H96ZM208,208H48V96H208V208Zm-68-56a12,12,0,1,1-12-12A12,12,0,0,1,140,152Z"></path></svg> 
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <button type="button" onclick="togglePassword('current_password', this)"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                        <!-- Default Eye Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.34c18.83-18.83,27.3-37.61,27.65-38.4A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.46,133.46,0,0,1,231.05,128C223.84,141.46,192.43,192,128,192Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">New Password *</label>
                <div class="relative">
                    <input type="password" name="new_password" id="new_password"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <button type="button" onclick="togglePassword('new_password', this)"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.34c18.83-18.83,27.3-37.61,27.65-38.4A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.46,133.46,0,0,1,231.05,128C223.84,141.46,192.43,192,128,192Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Confirm New Password *</label>
                <div class="relative">
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <button type="button" onclick="togglePassword('confirm_password', this)"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.34c18.83-18.83,27.3-37.61,27.65-38.4A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.46,133.46,0,0,1,231.05,128C223.84,141.46,192.43,192,128,192Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"></path></svg>
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

<div class="flex justify-center items-center min-h-[60vh] px-4">
<div class="max-w-[550px] bg-white shadow-md rounded-xl p-4">


    <!-- Header -->
    <h2 class="text-xl font-semibold text-gray-800 flex items-center justify-center gap-2 mb-5">
      <!-- Lock SVG -->
      <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#000000" viewBox="0 0 256 256 ">
        <path d="M208,40H48A16,16,0,0,0,32,56v56c0,52.72,25.52,84.67,46.93,102.19,23.06,18.86,46,25.27,47,25.53a8,8,0,0,0,4.2,0c1-.26,23.91-6.67,47-25.53C198.48,196.67,224,164.72,224,112V56A16,16,0,0,0,208,40Zm0,72c0,37.07-13.66,67.16-40.6,89.42A129.3,129.3,0,0,1,128,223.62a128.25,128.25,0,0,1-38.92-21.81C61.82,179.51,48,149.3,48,112l0-56,160,0Z"></path></svg>
      Password Security
    </h2>
    <br>

    <!-- Two columns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Column 1: Security Tips -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
          <!-- Check SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ff1eff" viewBox="0 0 256 256">
            <path d="M229.66,77.66l-128,128a8,8,0,0,1-11.32,0l-56-56a8,8,0,0,1,11.32-11.32L96,188.69,218.34,66.34a8,8,0,0,1,11.32,11.32Z"></path></svg>
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
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
          <!-- Info SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#455bffff" viewBox="0 0 256 256">
            <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm16-40a8,8,0,0,1-8,8,16,16,0,0,1-16-16V128a8,8,0,0,1,0-16,16,16,0,0,1,16,16v40A8,8,0,0,1,144,176ZM112,84a12,12,0,1,1,12,12A12,12,0,0,1,112,84Z"></path></svg>
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




<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M247.31,124.76c-.35-.79-8.82-19.58-27.65-38.41C194.57,61.26,162.88,48,128,48S61.43,61.26,36.34,86.35C17.51,105.18,9,124,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208s66.57-13.26,91.66-38.34c18.83-18.83,27.3-37.61,27.65-38.4A8,8,0,0,0,247.31,124.76ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.47,133.47,0,0,1,25,128,133.33,133.33,0,0,1,48.07,97.25C70.33,75.19,97.22,64,128,64s57.67,11.19,79.93,33.25A133.46,133.46,0,0,1,231.05,128C223.84,141.46,192.43,192,128,192Zm0-112a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160Z"></path></svg>`;

    const eyeSlash = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M53.92,34.62A8,8,0,1,0,42.08,45.38L61.32,66.55C25,88.84,9.38,123.2,8.69,124.76a8,8,0,0,0,0,6.5c.35.79,8.82,19.57,27.65,38.4C61.43,194.74,93.12,208,128,208a127.11,127.11,0,0,0,52.07-10.83l22,24.21a8,8,0,1,0,11.84-10.76Zm47.33,75.84,41.67,45.85a32,32,0,0,1-41.67-45.85ZM128,192c-30.78,0-57.67-11.19-79.93-33.25A133.16,133.16,0,0,1,25,128c4.69-8.79,19.66-33.39,47.35-49.38l18,19.75a48,48,0,0,0,63.66,70l14.73,16.2A112,112,0,0,1,128,192Zm6-95.43a8,8,0,0,1,3-15.72,48.16,48.16,0,0,1,38.77,42.64,8,8,0,0,1-7.22,8.71,6.39,6.39,0,0,1-.75,0,8,8,0,0,1-8-7.26A32.09,32.09,0,0,0,134,96.57Zm113.28,34.69c-.42.94-10.55,23.37-33.36,43.8a8,8,0,1,1-10.67-11.92A132.77,132.77,0,0,0,231.05,128a133.15,133.15,0,0,0-23.12-30.77C185.67,75.19,158.78,64,128,64a118.37,118.37,0,0,0-19.36,1.57A8,8,0,1,1,106,49.79,134,134,0,0,1,128,48c34.88,0,66.57,13.26,91.66,38.35,18.83,18.83,27.3,37.62,27.65,38.41A8,8,0,0,1,247.31,131.26Z"></path></svg>`;

    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";
        btn.innerHTML = isPassword ? eyeSlash : eyeOpen;
    }

    document.getElementById("passwordForm").addEventListener("submit", function(event) {
        const newPass = document.getElementById("new_password").value;
        const confirmPass = document.getElementById("confirm_password").value;

        if (newPass !== confirmPass) {
            event.preventDefault();
            Swal.fire({
    icon: 'error',
    title: 'Passwords do not match!',
    text: 'Please make sure both new passwords are the same.',
    background: '#fef2f2', // soft red/pink background for less harshness
    color: '#b91c1c', // red text
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    },
    confirmButtonText: 'Try Again',
    confirmButtonColor: '#ef4444', // slightly softer red
    customClass: {
        popup: 'rounded-2xl shadow-xl p-6 max-w-sm mx-auto border border-red-200',
        title: 'text-red-600 font-bold text-xl text-center',
        content: 'text-red-700 text-sm text-center mt-2',
        confirmButton: 'px-6 py-2 rounded-lg font-semibold bg-red-500 hover:bg-red-600 transition-colors text-white shadow-md'
    },
    buttonsStyling: false,
    reverseButtons: true // nicer UX: confirm button on the right
});


        }
    });
</script>
