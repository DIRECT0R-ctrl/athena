<?php

class DashboardController {
    public function index() {
        $auth = Auth::getInstance();
        if (!$auth->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        
        require_once __DIR__ . '/../views/dashboard.php';
    }
}