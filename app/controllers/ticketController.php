<?php 

@require_once('models/ticket.php');
@require_once('core/sessionGuard.php');

class TicketController {

    public static function index() {
        $tickets = Ticket::getAll();
        require_once('views/tickets.php');
    }
    
}

?>