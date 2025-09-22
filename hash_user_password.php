<?php
/**
 * hash_user_password.php
 * 
 * Small helper tool para gumawa ng secure hashed password
 * at mag-generate ng UPDATE SQL para sa phpMyAdmin.
 * 
 * Usage:
 * 1. CLI: php hash_user_password.php myPassword user123
 * 2. Browser: open in http://localhost/hash_user_password.php,
 *    fill the form with password + username/id
 */

// === CONFIG ===
$table          = 'users';          // pangalan ng table
$idColumn       = 'username';       // column para kilalanin ang user (pwede 'id' kung numeric)
$passwordColumn = 'password';       // column kung saan naka-save ang password hash
// ==============

// detect kung CLI or browser
$fromCli = (php_sapi_name() === 'cli');

if ($fromCli) {
    if ($argc < 3) {
        echo "Usage: php {$argv[0]} <plaintext-password> <identifier>\n";
        exit(1);
    }
    $plain = $argv[1];
    $ident = $argv[2];
} else {
    $plain = $_POST['password'] ?? '';
    $ident = $_POST['identifier'] ?? '';
    if ($plain === '' || $ident === '') {
        // simple form kung browser ang gamit
        echo '<h2>Password Hash Generator</h2>';
        echo '<form method="post">
                Password: <input type="text" name="password"><br><br>
                Identifier (username or id): <input type="text" name="identifier"><br><br>
                <button type="submit">Generate Hash + SQL</button>
              </form>';
        exit;
    }
}

// generate hash
$hash = password_hash($plain, PASSWORD_DEFAULT);

// escape para safe sa SQL copy-paste
$escapedHash  = addslashes($hash);
$escapedIdent = addslashes($ident);

// kung number ang identifier, no quotes sa SQL
$whereClause = is_numeric($ident)
    ? "{$idColumn} = {$escapedIdent}"
    : "{$idColumn} = '{$escapedIdent}'";

// build SQL
$sql = "UPDATE `{$table}` SET `{$passwordColumn}` = '{$escapedHash}' WHERE {$whereClause};";

// output
if ($fromCli) {
    echo "=== HASH GENERATED ===\n$hash\n\n";
    echo "=== SQL to run in phpMyAdmin ===\n$sql\n";
} else {
    echo "<h3>Hash generated:</h3>";
    echo "<code>$hash</code><br><br>";
    echo "<h3>SQL (copy & run in phpMyAdmin → SQL tab):</h3>";
    echo "<pre>$sql</pre>";
}
