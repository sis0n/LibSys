<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Verify OTP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/phosphor-icons@1.4.1/src/css/phosphor.css">
</head>

<body class="bg-gradient-to-br from-orange-50 to-green-50 font-sans flex items-center justify-center min-h-screen p-4">
  <div class="bg-white/95 backdrop-blur-sm border border-orange-200/60 shadow-xl shadow-orange-200/40 rounded-2xl p-8 w-full max-w-md">

    <div class="text-center mb-4">
      <h1 class="text-2xl font-bold text-gray-800">Verify OTP</h1>
      <p class="text-sm text-gray-600 mt-1">We sent a 6-digit verification code to your email</p>
    </div>

    <div id="infoBox" class="hidden mt-4 bg-orange-100 border border-orange-300 text-orange-800 text-sm rounded-lg p-3 transition duration-300 opacity-0">
      <div class="flex items-start gap-2">
        <i class="ph ph-info text-lg"></i>
        <div>
          <div id="infoText" class="font-medium">OTP sent to your email.</div>
          <div class="text-xs opacity-80">Check Spam/Promotions folder if you don't see it.</div>
        </div>
      </div>
    </div>

    <form id="otpForm" action="#" method="POST" class="mt-6">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">

      <div id="otpRow" class="flex justify-center gap-3">
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code" autofocus
          id="d1" name="d1" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code"
          id="d2" name="d2" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code"
          id="d3" name="d3" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code"
          id="d4" name="d4" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code"
          id="d5" name="d5" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
        <input inputmode="numeric" pattern="\d*" maxlength="1" autocomplete="one-time-code"
          id="d6" name="d6" class="otp-input w-14 h-14 rounded-md border border-orange-300 bg-orange-50 px-2 py-1 text-2xl focus:outline-none focus:ring-2 focus:ring-orange-400 transition" />
      </div>

      <p id="otpError" class="text-red-600 text-sm mt-3 text-center hidden">Invalid code. Please check and try again.</p>

      <div class="mt-5">
        <button type="submit" id="verifyBtn" class="w-full bg-orange-600 text-white py-2.5 rounded-lg font-medium hover:bg-orange-700 transition duration-150 ease-in-out">
          Verify Code
        </button>
      </div>

      <div class="mt-4 text-center">
        <button id="resendLink" type="button" class="text-sm text-orange-700 hover:underline disabled:text-gray-400" aria-disabled="false">
          Didn't receive a code? <span id="resendText" class="font-medium">Resend</span>
        </button>
      </div>
    </form>

    <div class="mt-6 text-center">
      <a href="forgotPassword" class="text-sm text-gray-600 hover:underline">Change email</a>
    </div>
  </div>

  <script>
    // Inject necessary CSS not available in pure Tailwind
    const style = document.createElement('style');
    style.innerHTML = `
    /* OTP input tweaks: -moz-appearance, caret-color and text-align/font-weight not available in TW */
    .otp-input {
      -moz-appearance: textfield;
      caret-color: transparent;
      text-align: center;
      font-weight: 600; /* semi-bold */
      letter-spacing: .12rem;
    }
    .otp-input::-webkit-outer-spin-button,
    .otp-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* small shake animation for invalid */
    @keyframes shake {
      0% { transform: translateX(0) }
      25% { transform: translateX(-6px) }
      50% { transform: translateX(6px) }
      75% { transform: translateX(-4px) }
      100% { transform: translateX(0) }
    }
    .shake { animation: shake .35s ease; }
  `;
    document.head.appendChild(style);


    // Elements
    const inputs = Array.from(document.querySelectorAll('#d1,#d2,#d3,#d4,#d5,#d6'));
    const otpForm = document.getElementById('otpForm');
    const resendLink = document.getElementById('resendLink');
    const resendText = document.getElementById('resendText');
    const otpError = document.getElementById('otpError');
    const verifyBtn = document.getElementById('verifyBtn');
    const otpRow = document.getElementById('otpRow');

    const csrfToken = document.querySelector('input[name="csrf_token"]').value;

    const BASE_URL = "<?= BASE_URL ?>"

    let resendTimer = null;
    const RESEND_DURATION = 60; // seconds
    let resendSeconds = RESEND_DURATION;

    function showInfo(msg) {
      infoText.textContent = msg;
      infoBox.classList.remove('hidden');
      setTimeout(() => {
        infoBox.classList.add('opacity-100');
      }, 20);
    }

    // Focus management & auto move to next input
    inputs.forEach((input, idx) => {
      input.addEventListener('input', (e) => {
        const val = e.target.value.replace(/\D/g, '');
        e.target.value = val;
        if (val.length === 1) {
          if (idx < inputs.length - 1) inputs[idx + 1].focus();
        }
      });
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && idx > 0) {
          inputs[idx - 1].focus();
        } else if (e.key === 'ArrowLeft' && idx > 0) {
          inputs[idx - 1].focus();
        } else if (e.key === 'ArrowRight' && idx < inputs.length - 1) {
          inputs[idx + 1].focus();
        }
      });
      input.addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/\D/g, '');
        if (!paste) return;
        const chars = paste.split('').slice(0, inputs.length);
        inputs.forEach((el, i) => {
          el.value = chars[i] || '';
        });
        const firstEmpty = inputs.find(i => !i.value);
        (firstEmpty || inputs[inputs.length - 1]).focus();
      });
    });

    // Resend logic (with countdown)
    function startResendCountdown(sec = RESEND_DURATION) {
      clearInterval(resendTimer);
      resendSeconds = sec;
      resendLink.disabled = true;
      resendLink.setAttribute('aria-disabled', 'true');
      updateResendText();
      resendTimer = setInterval(() => {
        resendSeconds--;
        updateResendText();
        if (resendSeconds <= 0) {
          clearInterval(resendTimer);
          resendLink.disabled = false;
          resendLink.setAttribute('aria-disabled', 'false');
          resendText.textContent = 'Resend';
        }
      }, 1000);
    }

    function updateResendText() {
      resendText.textContent = `Resend in ${resendSeconds}s`;
    }

    resendLink.addEventListener('click', () => {
      if (resendLink.disabled) return;

      fetch(`${BASE_URL}/verify-otp/resend`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            // Mags-start na lang 'yung countdown
            showInfo(data.message);
            startResendCountdown(RESEND_DURATION);
          } else {
            otpError.textContent = data.message;
            otpError.classList.remove('hidden');
          }
        })
        .catch(err => {
          console.error(err);
          otpError.textContent = 'Failed to resend. Check connection.';
          otpError.classList.remove('hidden');
        });
    });

    showInfo('OTP sent to your email.');
    startResendCountdown(RESEND_DURATION);

    otpForm.addEventListener('submit', (e) => {
      e.preventDefault();
      otpError.classList.add('hidden');
      otpRow.classList.remove('shake');

      const code = inputs.map(i => i.value || '').join('');
      if (code.length !== inputs.length) {
        otpError.textContent = 'Please enter the full 6-digit code.';
        otpError.classList.remove('hidden');
        otpRow.classList.add('shake');
        setTimeout(() => otpRow.classList.remove('shake'), 350);
        return;
      }

      verifyBtn.disabled = true;
      verifyBtn.textContent = 'Verifying...';

      const formData = new FormData();
      formData.append('otp', code);

      fetch(`${BASE_URL}/verify-otp/check`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            verifyBtn.textContent = 'Verified';
            verifyBtn.classList.remove('bg-orange-600', 'hover:bg-orange-700');
            verifyBtn.classList.add('bg-green-500', 'hover:bg-green-600');

            setTimeout(() => {
              window.location.href = `${BASE_URL}/resetPassword`;
            }, 1000);

          } else {
            // Failed
            otpError.textContent = data.message;
            otpError.classList.remove('hidden');
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify Code';
            otpRow.classList.add('shake');
            setTimeout(() => otpRow.classList.remove('shake'), 350);
          }
        })
        .catch(err => {
          console.error(err);
          otpError.textContent = 'An error occurred. Please check connection.';
          otpError.classList.remove('hidden');
          verifyBtn.disabled = false;
          verifyBtn.textContent = 'Verify Code';
          S
        });
    });

    window.addEventListener('load', () => {
      inputs[0].focus();
    });
  </script>
</body>

</html>