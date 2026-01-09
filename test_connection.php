<?php
// test_connection.php
require_once 'config/database.php';
require_once 'core/Database.php';

echo "Testing database connection...<br>";

try {
    $db = Database::getInstance();
    echo " Database connection successful!<br>";
    
    $result = $db->fetch("SELECT COUNT(*) as count FROM users");
    echo " Users table exists!<br>";
    echo "Total users: " . $result['count'] . "<br>";
    
} catch (Exception $e) {
    echo " Error: " . $e->getMessage() . "<br>";
}
