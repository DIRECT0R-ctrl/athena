<?php
// core/Autoloader.php

class Autoloader {
    public static function register() {
        spl_autoload_register(function ($className) {
            // Base directory
            $baseDir = realpath(__DIR__ . '/../');
            
            // Try different directories
            $paths = [
                $baseDir . '/core/' . $className . '.php',
                $baseDir . '/entities/' . $className . '.php',
                $baseDir . '/repositories/' . $className . '.php',
                $baseDir . '/controllers/' . $className . '.php',
                $baseDir . '/services/' . $className . '.php',
                $baseDir . '/utils/' . $className . '.php',
            ];
            
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    return true;
                }
            }
            
            // Last resort: try without directory
            if (file_exists($baseDir . '/' . $className . '.php')) {
                require_once $baseDir . '/' . $className . '.php';
                return true;
            }
            
            throw new Exception("Class $className not found. Searched in: " . implode(', ', $paths));
        });
    }
}