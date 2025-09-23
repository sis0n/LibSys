pang test lang to kung nag iinsert sa db

<?php
require 'src/core/Database.php'; // import your DB connection class
require __DIR__ . '/vendor/autoload.php';


$db = \App\Core\Database::getInstance()->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $db->prepare("
        INSERT INTO attendance_logs 
        (user_id, student_number, full_name, year_level, course, method, timestamp)
        VALUES (:user_id, :student_number, :full_name, :year_level, :course, :method, :timestamp)
    ");

    $stmt->execute([
        ':user_id' => 1,
        ':student_number' => '20234321-s',
        ':full_name' => 'Test User',
        ':year_level' => '1',
        ':course' => 'CS',
        ':method' => 'qr',
        ':timestamp' => date('Y-m-d H:i:s')
    ]);

    echo "Insert successful! ID: " . $db->lastInsertId();
} catch (\PDOException $e) {
    echo "Insert failed: " . $e->getMessage();
}
