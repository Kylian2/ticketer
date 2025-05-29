<?php 

@require_once('models/ticket.php');
@require_once('models/message.php');
@require_once('models/note.php');
@require_once('core/sessionGuard.php');

class TicketController {

    public static function index() {
        $tickets = Ticket::getAll();
        require_once('views/tickets.php');
    }

    public static function show(array $params) {
        try {
            $ticket = Ticket::getById($params[0]);

            $messages = Message::getByTicketId($ticket->id) ?? [];
            $notes = Note::getByTicketId($ticket->id) ?? [];

            require_once('views/ticket.php');

        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
            return;
        }
    }
    
}

?>