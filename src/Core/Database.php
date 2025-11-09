<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $driver = 'mysql';
        $host = '127.0.0.1';            
        $db   = 'u896434489_library_system';     
        $user = 'u896434489_libsys';  
        $pass = ':8kC!zfaEX8w';             

        try {
            $this->connection = new PDO(
                "$driver:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
