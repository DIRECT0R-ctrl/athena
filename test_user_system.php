<?php
// test_user_system.php

require_once 'config/constants.php';
require_once 'core/Database.php';
require_once 'entities/User.php';
require_once 'repositories/UserRepository.php';

echo "<h1>YARBI IKHDEM (user system)</h1>";

try {
    // Test 1: Create User using Entity methods
    echo "<h3>1. testing user entity</h3>";
    
    $user = new User();
    $user->setFullname("laksimi aymane")
         ->setEmail("dominantvortex@proton.me")
         ->setPassword("typhussama5T!")
         ->setRoleId(ROLE_MEMBRE);
    
    echo "user created in memory: " . $user->getFullname() . "<br>";
    echo "email: " . $user->getEmail() . "<br>";
    echo "role: " . ($user->isMembre() ? "Membre" : "Other") . "<br>";
    
    echo "password verify (correct): " . ($user->verifyPassword("password123") ? "YES" : "NO") . "<br>";
    echo "password verify (wrong): " . ($user->verifyPassword("wrongpass") ? "YES" : "NO") . "<br>";
    

    echo "<h3>2. saving to database</h3>";
    try {
        $savedUser = $user->save();
        echo " (. - .) User saved! ID: " . $savedUser->getId() . "<br>";
        echo "Created at: " . $savedUser->getCreatedAt() . "<br>";
    } catch (Exception $e) {
        echo "(/../) Save failed: " . $e->getMessage() . "<br>";
    }
    
    // Test 3: Find by ID (static method)
    echo "<h3>3. finding user by ID</h3>";
    if ($savedUser) {
        $foundUser = User::find($savedUser->getId());
        if ($foundUser) {
            echo "YES /  found user: " . $foundUser->getFullname() . "<br>";
        } else {
            echo "NOP / user not found<br>";
        }
    }
    
    // Test 4: find by email
    echo "<h3>4. finding user by Email</h3>";
    $foundByEmail = User::findByEmail("john@example.com");
    if ($foundByEmail) {
        echo "YES / Found by email: " . $foundByEmail->getFullname() . "<br>";
    } else {
        echo "NOP User not found by email<br>";
    }
    
    // Test 5: Using Repository
    echo "<h3>5. Testing User Repository</h3>";
    $userRepo = new UserRepository();
    
    // Create another user via repository
    $user2 = new User();
    $user2->setFullname("Jane Smith")
          ->setEmail("jane@example.com")
          ->setPassword("secure456")
          ->setRoleId(ROLE_CHEF_PROJET);
    
    try {
        $createdUser = $userRepo->create($user2);
        echo " User created via repository. ID: " . $createdUser->getId() . "<br>";
    } catch (Exception $e) {
        echo " Repository create failed: " . $e->getMessage() . "<br>";
    }
    
    // Test 6: Get all users
    echo "<h3>6. Getting All Users</h3>";
    $allUsers = $userRepo->findAll();
    echo "Total users in database: " . count($allUsers) . "<br>";
    
    foreach ($allUsers as $u) {
        echo "- " . $u->getFullname() . " (" . $u->getEmail() . ")<br>";
    }
    
    // Test 7: Update user
    echo "<h3>7. Updating User</h3>";
    if ($foundByEmail) {
        $foundByEmail->setFullname("John Updated");
        try {
            $foundByEmail->save();
            echo "YES User updated successfully<br>";
            
            // Verify update
            $updated = User::find($foundByEmail->getId());
            echo "New name: " . $updated->getFullname() . "<br>";
        } catch (Exception $e) {
            echo "NOPE / Update failed: " . $e->getMessage() . "<br>";
        }
    }
    

    echo "<h3>8. Converting to Array</h3>";
    if ($foundByEmail) {
        $userArray = $foundByEmail->toArray();
        echo "<pre>";
        print_r($userArray);
        echo "</pre>";
    }
    
    // Test 9: Count users
    echo "<h3>9. Counting Users</h3>";
    $totalUsers = $userRepo->count();
    echo "Total users: " . $totalUsers . "<br>";
    
    $activeUsers = $userRepo->count(['is_active' => true]);
    echo "Active users: " . $activeUsers . "<br>";
    
    // Test 10: Role checks
    echo "<h3>10. Role Checking</h3>";
    if ($foundByEmail) {
        echo "is admin? " . ($foundByEmail->isAdmin() ? "Yes" : "No") . "<br>";
        echo "is chef Projet? " . ($foundByEmail->isChefProjet() ? "Yes" : "No") . "<br>";
        echo "is membre? " . ($foundByEmail->isMembre() ? "Yes" : "No") . "<br>";
        echo "is active? " . ($foundByEmail->isActive() ? "Yes" : "No") . "<br>";
    }
    
    echo "<hr><h2> All Tests Complete!</h2>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'> Error: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
