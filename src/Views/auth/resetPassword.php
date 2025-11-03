<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gradient-to-br from-orange-50 to-green-50 font-sans flex items-center justify-center min-h-screen">

    <div class="bg-white/95 backdrop-blur-sm border border-orange-200/60 shadow-xl shadow-orange-200/40 rounded-2xl p-8 w-full max-w-md">
      
        
            <div class="flex justify-center mt-2">
                <img src="<?= BASE_URL ?>/assets/library-icons/apple-touch-icon.png" alt="Library Logo" class="w-32">
            </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New Password</h1>
            <p class="text-sm text-gray-600 mt-1">Enter a new password below</p>
        </div>

        <form id="resetForm" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="newPass" 
                        name="newPass"
                        required 
                        class="w-full rounded-lg border border-orange-300 bg-orange-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10">
                    <button 
                        type="button" 
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500 transition-colors duration-200" 
                        onclick="togglePassword('newPass', this)">
                        <i class="ph ph-eye"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="confirmPass" 
                        name="confirmPass"
                        required 
                        class="w-full rounded-lg border border-orange-300 bg-orange-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10">
                    <button 
                        type="button" 
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-orange-500 transition-colors duration-200" 
                        onclick="togglePassword('confirmPass', this)">
                        <i class="ph ph-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" id="btnChange" class="w-full bg-orange-600 text-white py-2.5 rounded-lg font-medium hover:bg-orange-700 active:scale-[.98] transition">
                Change Password
            </button>

        </form>

        <div id="successBox" class="hidden mt-5 bg-green-50 border border-green-300 text-green-800 text-sm rounded-lg p-3 space-y-1">
    <p id="successMsg" class="font-medium">
        Password successfully changed! Redirecting to login in <span id="sec">3</span>s...
    </p>
    <p class="text-xs opacity-80">
        Didn’t receive anything? Check Spam/Promotions folder.
    </p>
</div>

    </div>

    <script>
   
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');

        if (input.type === "password") {
            // Ipakita ang password
            input.type = "text";
            // Palitan ang icon sa 'eye-slash' (nakatago)
            icon.classList.remove('ph-eye');
            icon.classList.add('ph-eye-slash');
        } else {
            // Itago ang password
            input.type = "password";
            // Palitan ang icon sa 'eye' (nakikita)
            icon.classList.remove('ph-eye-slash');
            icon.classList.add('ph-eye');
        }
    }

    const form = document.getElementById("resetForm");
    const box = document.getElementById("successBox");
    const secText = document.getElementById("sec");
    const btn = document.getElementById("btnChange");

    form.addEventListener("submit", (e)=>{
        e.preventDefault();

        const p1 = document.getElementById("newPass").value;
        const p2 = document.getElementById("confirmPass").value;

        if(p1 !== p2){
            alert("Passwords do not match!");
            return;
        }

        btn.disabled = true;
        btn.textContent = "Updating...";

        setTimeout(()=>{
            // I-imagine na successful ang pag-update dito (dapat may AJAX call)
            box.classList.remove("hidden");

            let s = 3;
            const timer = setInterval(()=>{
                s--;
                secText.textContent = s;
                if(s <= 0){
                    clearInterval(timer);
                    // Baguhin ang "login.html" sa tamang login page URL mo
                    window.location.href="login"; 
                }
            },1000);
        },1000);

    });
    </script>

</body>
</html>