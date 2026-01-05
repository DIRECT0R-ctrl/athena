<?php

class Session {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public function remove($key) {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }
    
    public function flash($key, $value = null) {
        if ($value === null) {
            $message = $this->get('flash_' . $key);
            $this->remove('flash_' . $key);
            return $message;
        } else {
            $this->set('flash_' . $key, $value);
        }
    }
    
    public function destroy() {
        session_destroy();
    }
}
