<?php
$role = $_SESSION['role'] ?? 'guest';
$fullname = $_SESSION['fullname'] ?? 'Guest User';
$username = $_SESSION['username'] ?? '0000';

// Role title
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
        break;
}
?>
<header class="sticky top-0 z-10 bg-white border-b border-orange-200 px-6 py-2 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <!-- Hamburger (visible only on small screens) -->
        <button id="hamburgerBtn"
            class="lg:hidden flex items-center justify-center text-orange-700 hover:bg-orange-50 rounded-md h-9 w-9">
            <i class="ph ph-list text-2xl"></i>
        </button>

        <!-- Dynamic Page Title -->
        <h1 class="text-lg font-semibold text-gray-800 leading-none">
            <?= $roleTitle ?>
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <!-- Profile -->
        <div class="flex justify-end items-center gap-2">
            <div class="leading-tight">
                <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($fullname) ?></p>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($username) ?></p>
            </div>
        </div>

        <!-- Logout -->
        <form method="POST" action="/LibSys/public/logout">
            <button type="submit" class="p-2 rounded hover:bg-gray-100">
                <i class="ph ph-sign-out"></i>
            </button>
        </form>
    </div>
</header>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.getElementById("sidebar");
        const btn = document.getElementById("hamburgerBtn");

        function toggleSidebar(forceClose = false) {
            const isHidden = sidebar.classList.contains("-translate-x-full");
            const body = document.body;

            if (forceClose) {
                sidebar.classList.add("-translate-x-full");
                sidebar.classList.remove("translate-x-0");
                body.classList.remove("overflow-hidden");
            } else {
                sidebar.classList.toggle("-translate-x-full", !isHidden);
                sidebar.classList.toggle("translate-x-0", isHidden);
                body.classList.toggle("overflow-hidden", isHidden); // disables scroll when open
            }
        }

        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            toggleSidebar();
        });

        document.addEventListener("click", (e) => {
            const isMobile = window.innerWidth < 1024;
            const clickedInsideSidebar = sidebar.contains(e.target);
            const clickedButton = btn.contains(e.target);

            if (isMobile && !clickedInsideSidebar && !clickedButton) {
                toggleSidebar(true);
            }
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove("-translate-x-full");
                sidebar.classList.add("translate-x-0");
                document.body.classList.remove("overflow-hidden");
            }
        });
    });
</script>