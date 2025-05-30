<?php

@require_once('core/router.php');

Router::get('/', 'TicketController@create');

Router::get('/users', 'UserController@index');

Router::get('/tickets', 'TicketController@index', true);
Router::get('/ticket/{id}', 'TicketController@show', true);
Router::patch('/ticket/{id}', 'TicketController@update');
Router::post('/ticket/{id}/message', 'TicketController@postMessage');
Router::post('/ticket/{id}/note', 'TicketController@postNote');
Router::post('/ticket', 'TicketController@store');

Router::get('/login', 'AuthController@form');
Router::post('/login', 'AuthController@login');
Router::post('/register', 'AuthController@register');

?>
