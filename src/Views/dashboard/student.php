<?php
if ($_SESSION['role'] !== 'student') {
    header("Location: /libsys/public/index.php?url=login");
    exit;
}
echo "Welcome student: " . $_SESSION['fullname'];
?>