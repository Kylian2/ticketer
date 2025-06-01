<?php

@require_once('core/router.php');

Router::get('/', 'ticketController@create');

Router::get('/users', 'userController@index');
Router::patch('/users', 'userController@patch', true);

Router::get('/tickets', 'ticketController@index', true);
Router::get('/ticket/{id}', 'ticketController@show', true);
Router::patch('/ticket/{id}', 'ticketController@update');
Router::post('/ticket/{id}/message', 'ticketController@postMessage');
Router::post('/ticket/{id}/note', 'ticketController@postNote');
Router::post('/ticket', 'ticketController@store');

Router::get('/login', 'authController@form');
Router::post('/login', 'authController@login');
Router::post('/register', 'authController@register');
Router::delete('/logout', 'authController@logout', true);

Router::get('/settings', 'userController@settings', true);

?>
