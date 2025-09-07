<?php
if ($_SESSION['role'] !== 'superadmin') {
    header("Location: /libsys/public/index.php?url=login");
    exit;
}
echo "Welcome Super Admin: " . $_SESSION['username'];
