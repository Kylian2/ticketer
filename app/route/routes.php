<?php

@require_once('core/router.php');

Router::get('/users', 'UserController@index');

Router::get('/tickets', 'TicketController@index');
Router::get('/ticket/{id}', 'TicketController@show');
Router::patch('/ticket/{id}', 'TicketController@update');
Router::post('/ticket/{id}/message', 'TicketController@postMessage');
Router::post('/ticket/{id}/note', 'TicketController@postNote');

?>
