<?php

require_once 'config/constants.php';
require_once 'core/Database.php';
require_once 'core/Session.php';
require_once 'core/Auth.php';
require_once 'entities/User.php';
require_once 'repositories/UserRepository.php';
require_once 'controllers/AuthController.php';

echo "<h1>YARBI IKHDEM 3AWD</h1>";

try {
    echo "<h3>1. Creating User</h3>";
    $user = new User();
    $user->setFullname("Test User")
         ->setEmail("test@example.com")
         ->setPassword("test123")
         ->setRoleId(ROLE_MEMBRE);
    
    echo " User created: " . $user->getFullname() . "<br>";
    
    echo "<h3>2. Testing UserRepository</h3>";
    $repo = new UserRepository();
    echo " UserRepository created<br>";
    
    echo "<h3>3. Testing AuthController</h3>";
    $authController = new AuthController();
    echo "AuthController created<br>";
    
    echo "<h2> All tests passed!</h2>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'> Error: " . $e->getMessage() . "</h3>";
    echo "<pre>File: " . $e->getFile() . " Line: " . $e->getLine() . "</pre>";
}