<?php
require_once __DIR__ . '/../core/Autoloader.php';
Autoloader::register();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Router.php';
$session = new Session();
$router = new Router();
$router->dispatch();
