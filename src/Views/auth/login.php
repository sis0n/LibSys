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

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 to-green-50 font-sans">
    <div class="w-full max-w-md p-6">
        <div class="bg-white shadow-lg rounded-xl p-8">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="/LibSys/assets/ucc-icons/apple-touch-icon.png" alt="University Logo" class="h-24 w-24">
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-semibold text-center text-gray-800">
                Library Online Software
            </h2>
            <p class="text-sm text-center text-gray-600 mb-6">
                Sign in to access your dashboard
            </p>

            <!-- Login Form -->
            <form method="POST" action="/libsys/public/index.php?url=login_post" class="space-y-4">
                <div>
                    <label for="username" class="flex items-center gap-x-1 text-sm font-medium text-gray-700">
                        <i class="ph ph-user"></i>
                        Username
                    </label>
                    <input type="text" id="username" name="username"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="Enter your student number" required>
                </div>

                <div>
                    <label for="password" class="flex items-center gap-x-1 block text-sm font-medium text-gray-700">
                        <i class="ph ph-key"></i>
                        Password
                    </label>
                    <input type="password" id="password" name="password"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="Enter your password" required>
                </div>

                <button type="submit"
                    class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg shadow hover:bg-orange-700 transition">
                    login
                </button>
            </form>

            <!-- Extra Links -->
            <div class="mt-3 text-center space-y-2">
                <a href="#" class="text-sm text-orange-600 hover:underline">
                    Forgot Password?
                </a>
            </div>
        </div>
    </div>
</body>

</html>