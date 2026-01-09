<?php

require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/../controllers/AuthController.php';

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
        
        // Remove project directory from URI if needed
        $basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($basePath, '', $uri);
        
        // Default to home if empty
        if ($uri === '') {
            $uri = '/';
        }

        $matchedHandler = null;
        $matchedParams = [];

        $routesForMethod = $this->routes[$method] ?? [];

        // Try to find a matching route (support placeholders like [id])
        foreach ($routesForMethod as $routePattern => $handler) {
            $paramNames = [];
            $regex = preg_replace_callback('/\[([a-zA-Z_][a-zA-Z0-9_]*)\]/', function($m) use (&$paramNames) {
                $paramNames[] = $m[1];
                return '([^\\/]+)';
            }, $routePattern);

            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // drop full match
                $params = [];
                foreach ($matches as $i => $value) {
                    $params[$paramNames[$i] ?? $i] = $value;
                }
                $matchedHandler = $handler;
                $matchedParams = $params;
                break;
            }
        }

        if ($matchedHandler) {
            // Check protected routes (match patterns similarly)
            foreach ($this->routes['protected'] ?? [] as $protPattern => $protHandler) {
                $protRegex = preg_replace_callback('/\[([a-zA-Z_][a-zA-Z0-9_]*)\]/', function($m) {
                    return '([^\\/]+)';
                }, $protPattern);
                $protRegex = '#^' . $protRegex . '$#';
                if (preg_match($protRegex, $uri)) {
                    $this->auth->requireAuth();
                    break;
                }
            }

            $this->callHandler($matchedHandler, $matchedParams);
        } else {
            http_response_code(404);
            echo "404 - Page Not Found: $uri";
        }
    }
    
    private function callHandler($handler, $params = []) {
        list($controllerName, $methodName) = explode('@', $handler);

        $controllerClass = $controllerName;
        if (!class_exists($controllerClass)) {
            throw new Exception("Controller $controllerClass not found");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method $methodName not found in $controllerClass");
        }

        // Call method with extracted params (if any)
        if (!empty($params)) {
            call_user_func_array([$controller, $methodName], array_values($params));
        } else {
            $controller->$methodName();
        }
    }
}
}
    // private function callHandler($handler) {

        // For now,  later an9adha for better
