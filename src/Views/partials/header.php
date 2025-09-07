<?php
$role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'Guest User';
$usernumber = $_SESSION['user_number'] ?? '0000';

// Kondisyon para sa header title
$roleTitle = '';
switch ($role) {
    case 'admin':
        $roleTitle = 'Admin Access Module';
        break;
    case 'librarian':
        $roleTitle = 'Librarian Access Module';
        break;
    case 'student':
        $roleTitle = 'Student Access Module';
        break;
    case 'superadmin':
        $roleTitle = 'Super Admin Access Module';
        break;
    default:
        $roleTitle = 'Guest Access';
}
?>

<!-- Header -->
<header class="sticky top-0 z-10 bg-white border-b border-orange-200 px-6 py-2 flex justify-between items-center">
    <h1 class="text-lg font-semibold text-gray-800">
        <?= $roleTitle ?>
    </h1>
    <div class="flex items-center gap-4">
        <!-- Profile -->
        <div class="flex justify-end items-center gap-2">
            <div>
                <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($username) ?></p>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($usernumber) ?></p>
            </div>
        </div>
        <!-- Logout -->
        <form method="POST" action="/libsys/public/index.php?url=logout">
            <button type="submit" class="p-2 rounded hover:bg-gray-100">
                <i class="ph ph-sign-out"></i>
            </button>
        </form>
    </div>
</header>
