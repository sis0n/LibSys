<?php
require_once __DIR__ . '/vendor/autoload.php'; 

use App\Core\Database;


//pang-reset ng borrow transactions at items
try {
    $db = Database::getInstance()->getConnection();

    $db->exec("SET FOREIGN_KEY_CHECKS=0");

    $db->exec("TRUNCATE TABLE borrow_transaction_items");
    $db->exec("TRUNCATE TABLE borrow_transactions");

    $db->exec("SET FOREIGN_KEY_CHECKS=1");

    echo "All borrow transactions and items have been cleared successfully!";
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
