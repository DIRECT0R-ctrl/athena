<?php

require_once __DIR__ . '/Session.php';

class Auth {
    private $session;
    private static $instance = null;
    
    private function __construct() {
        $this->session = new Session();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function login($user) {
        $this->session->set('user_id', $user['id']);
        $this->session->set('user_role', $user['role_id']);
        $this->session->set('user_name', $user['fullname']);
        $this->session->set('user_email', $user['email']);
        $this->session->set('logged_in', true);
    }
    
    public function logout() {
        $this->session->destroy();
    }
    
    public function isLoggedIn() {
        return $this->session->get('logged_in', false);
    }
    
    public function user() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $this->session->get('user_id'),
            'role_id' => $this->session->get('user_role'),
            'fullname' => $this->session->get('user_name'),
            'email' => $this->session->get('user_email')
        ];
    }
    
    public function isAdmin() {
        $user = $this->user();
        return $user && $user['role_id'] == ROLE_ADMIN;
    }
    
    public function isChefProjet() {
        $user = $this->user();
        return $user && $user['role_id'] == ROLE_CHEF_PROJET;
    }
    
    public function isMembre() {
        $user = $this->user();
        return $user && $user['role_id'] == ROLE_MEMBRE;
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function requireRole($role) {
        $this->requireAuth();
        
        $userRole = $this->session->get('user_role');
        if ($userRole != $role) {
            header('Location: /dashboard');
            exit;
        }
    }
}
