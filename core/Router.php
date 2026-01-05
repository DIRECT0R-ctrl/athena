<?php

require_once __DIR__ . '/Auth.php';

class Router {
    private $routes = [];
    private $auth;
    
    public function __construct() {
        $this->routes = require __DIR__ . '/../config/routes.php';
        $this->auth = Auth::getInstance();
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($basePath, '', $uri);
        
        
        if ($uri === '') {
            $uri = '/';
        }
        
        
        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            
            
            if (isset($this->routes['protected'][$uri])) {
                $this->auth->requireAuth();
            }
            
            $this->callHandler($handler);
            
        } else {
            
            http_response_code(404);
            echo "404 - Page Not Found";
        }
    }
    
    private function callHandler($handler) {
        // For now,  later an9adha for better
        // In next steps, i'll improve this
        
        if ($handler === 'HomeController@index') {
            $this->showHome();
        } elseif ($handler === 'AuthController@showLogin') {
            $this->showLogin();
        } elseif ($handler === 'AuthController@showRegister') {
            $this->showRegister();
        } elseif ($handler === 'AuthController@login') {
            $this->handleLogin();
        } elseif ($handler === 'AuthController@register') {
            $this->handleRegister();
        } elseif ($handler === 'AuthController@logout') {
            $this->handleLogout();
        } else {
            echo "Handler not implemented yet: {$handler}";
        }
    }
    
    // Temporary handler methods (i'll move to controllers later)
    private function showHome() {
        require __DIR__ . '/../views/home.php';
    }
    
    private function showLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }
    
    private function showRegister() {
        require __DIR__ . '/../views/auth/register.php';
    }
    
    private function handleLogin() {
        // i'll implement this in next step
        echo "Login handler will be here";
    }
    
    private function handleRegister() {
        // i'll implement this in next step
        echo "Register handler will be here";
    }
    
    private function handleLogout() {
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
}
