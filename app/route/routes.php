<?php

@require_once('core/router.php');

Router::get('/users', 'UserController@index');

Router::get('/tickets', 'TicketController@index');
Router::get('/ticket/{id}', 'TicketController@show');

?>
