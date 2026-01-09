<?php

namespace Athena\Controllers;

use Athena\Core\Auth;

class UserController {
    public function profile() {
        $auth = Auth::getInstance();
        if (!$auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        $user = $auth->user();
        echo "<h1>Profile</h1>";
        echo "<p>Name: " . htmlspecialchars($user['fullname']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
        echo "<a href='/dashboard'>Back to Dashboard</a>";
    }
}