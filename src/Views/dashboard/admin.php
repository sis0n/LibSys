<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: /libsys/public/index.php?url=login");
    exit;
}
echo "Welcome Admin: " . $_SESSION['username'];
