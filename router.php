<?php
// Router for PHP built-in server - routes all requests through public/index.php

if (php_sapi_name() === 'cli-server') {
    // Get the requested resource path
    $requested = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // List of static file extensions to serve directly
    $staticExtensions = ['.js', '.css', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.ico', '.woff', '.woff2', '.ttf'];
    
    // Check if the requested resource is a static file
    foreach ($staticExtensions as $ext) {
        if (substr($requested, -strlen($ext)) === $ext) {
            // Check if file exists in public directory
            $file = __DIR__ . $requested;
            if (is_file($file)) {
                return false; // Serve the static file
            }
        }
    }
    
    // If it's not a static file or directory, route through index.php
    if ($requested !== '/' && is_dir(__DIR__ . $requested)) {
        return false; // Serve directory listing
    }
    
    // Route everything else through public/index.php
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    require __DIR__ . '/public/index.php';
    return true;
}
