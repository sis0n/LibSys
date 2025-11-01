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
document.getElementById("passwordForm").addEventListener("submit", function (event) {
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

document.getElementById("passwordForm").addEventListener("submit", async function (event) {
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