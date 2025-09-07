<?php
if ($_SESSION['role'] !== 'librarian') {
    header("Location: /libsys/public/index.php?url=login");
    exit;
}
echo "Welcome Librarian: " . $_SESSION['username'];
