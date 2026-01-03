<?php

return [
    
    'GET' => [
        '/' => 'HomeController@index',
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
    ],
    
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
    ],
    
    // protected routes (i will add later)
    'protected' => [
        '/dashboard' => 'DashboardController@index',
        '/projects' => 'ProjectController@index',
    ]
];
