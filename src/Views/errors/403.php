<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-white text-gray-800 h-screen flex flex-col relative overflow-hidden font-sans">
    <main class="flex-grow flex flex-col items-center justify-center px-4 text-center">
        <h1 class="text-9xl font-bold text-orange-500 tracking-tighter">403</h1>
        <h2 class="mt-4 text-2xl md:text-3xl font-semibold text-gray-900">Forbidden Access</h2>
        <p class="mt-3 text-gray-500 max-w-md mx-auto">You don't have permission to view this page.</p>
        
        <div class="mt-8 flex justify-center">
            <a href="/" class="flex items-center justify-center px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-sm">
                <i class="ph ph-arrow-left text-xl mr-2"></i>
                Back to Homepage
            </a>
        </div>
    </main>
    
    <div class="absolute bottom-6 right-6">
        <button class="bg-gray-900 text-white p-3 rounded-full shadow-lg hover:bg-gray-800 transition-transform hover:scale-105">
            <i class="ph ph-question text-xl"></i>
        </button>
    </div>
</body>
</html>