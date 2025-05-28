<?php

@require_once('core/router.php');

Router::get('/users', 'UserController@index');

Router::get('/tickets', 'TicketController@index');

?>
