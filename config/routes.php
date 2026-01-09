<?php

return [
    
    'GET' => [
        '/' => 'HomeController@index', // == homecontroler::index
        '/login' => 'AuthController@showLogin',
        '/register' => 'AuthController@showRegister',
        '/logout' => 'AuthController@logout',
        '/projects' => 'ProjectController@index',
        '/projects/create' => 'ProjectController@create',
        '/projects/[id]' => 'ProjectController@show',
        '/projects/[id]/edit' => 'ProjectController@edit',
        '/sprints/[id]' => 'SprintController@show',
        '/sprints/create' => 'SprintController@create',
        '/tasks/[id]' => 'TaskController@show',
        '/tasks/[id]/edit' => 'TaskController@edit',
    ],
    
    'POST' => [
        '/login' => 'AuthController@login',
        '/register' => 'AuthController@register',
        '/projects/store' => 'ProjectController@store',
        '/projects/[id]/update' => 'ProjectController@update',
        '/projects/[id]/add-member' => 'ProjectController@addMember',
        '/sprints/store' => 'SprintController@store',
        '/sprints/[id]/update' => 'SprintController@update',
        '/tasks/store' => 'TaskController@store',
        '/tasks/[id]/update' => 'TaskController@update',
        '/tasks/[id]/delete' => 'TaskController@delete',
        '/comments/store' => 'CommentController@store',
    ],
    
    // protected routes (i will add later)
    'protected' => [
	    '/dashboard' => 'DashboardController@index',
	    '/profile' => 'UserController@profile',
	    '/projects' => 'ProjectController@index',
    ]
];
