<?php

class HomeController {
    private $session;
    private $auth;
    
    public function __construct() {
        $this->session = new Session();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Home page
     */
    public function index() {
        $user = null;
        if ($this->auth->isLoggedIn()) {
            $user = $this->auth->user();
        }
        
        require_once __DIR__ . '/../views/home.php';
    }
}