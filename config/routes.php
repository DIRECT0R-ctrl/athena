<?php

return [
    
    'GET' => [
        '/' => 'HomeController@index', // == homecontroler::index
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
	    '/profile' => 'UserController@profile',
	    '/projects' => 'ProjectController@index',
	    '/projects/create'=> 'ProjectController@create',
    ]
];
