<?php 

@require_once('models/ticket.php');
@require_once('models/message.php');
@require_once('models/note.php');
@require_once('core/sessionGuard.php');

class TicketController {

    public static function index() {
        $tickets = Ticket::getAll();
        require_once('views/layout.php');
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

    public static function update(array $params) {

        parse_str(file_get_contents("php://input"), $body);

        if (!isset($params[0]) || !is_numeric($params[0])) {
            http_response_code(400);
            echo "Bad Request: Missing or incorrect parameters.";
            return;
        }

        // TODO Check if the user is authorized to update the ticket

        if(isset($body['status']) && ($body['status'] !== 'new' && $body['status'] !== 'in_progress' && $body['status'] !== 'closed')) {
            http_response_code(400);
            echo "Bad Request: Invalid status value.";
            return;
        }

        if(isset($body['priority']) && ($body['priority'] !== 'low' && $body['priority'] !== 'medium' && $body['priority'] !== 'high')) {
            http_response_code(400);
            echo "Bad Request: Invalid priority value.";
            return;
        }

        try {
            $ticket = Ticket::getById($params[0]);
            if(isset($body['status'])) {
                $ticket->status = $body['status'];
            }
            if(isset($body['priority'])) {
                $ticket->priority = $body['priority'];
            }
            $ticket->update();
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public static function postMessage(array $params) {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            http_response_code(400);
            echo "Bad Request: Missing or incorrect parameters.";
            return;
        }

        parse_str(file_get_contents("php://input"), $body);
        if (!isset($body['message']) || empty($body['message'])) {
            http_response_code(400);
            return;
        }

        try {
            $message = new Message();
            $message->ticket = $params[0];
            $message->content = $body['message'];
            $message->author_email = 'asilverlock2@about.com'; // replace with the actual user email from session
            $message->author = 'Mela Lancastle'; // replace with the actual user from session
            $message->save();
            $message->created_at = date('Y-m-d H:i:s');
            echo '<div class="ticket__response">
                    <div class="ticket__response-header">
                        <span class="ticket__response-author">' . htmlspecialchars($message->author) . '</span>
                        <span class="ticket__response-date">' . htmlspecialchars($message->created_at) . '</span>
                    </div>
                    <p class="ticket__response-content">' . nl2br(htmlspecialchars($message->content)) . '</p>
                </div>';

        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }

    public static function postNote(array $params) {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            http_response_code(400);
            echo "Bad Request: Missing or incorrect parameters.";
            return;
        }

        parse_str(file_get_contents("php://input"), $body);
        if (!isset($body['note']) || empty($body['note'])) {
            http_response_code(400);
            return;
        }

        try {
            $note = new note();
            $note->ticket = $params[0];
            $note->content = $body['note'];
            $note->author_email = 'asilverlock2@about.com'; // replace with the actual user email from session
            $note->author = 'Mela Lancastle'; // replace with the actual user from session
            $note->save();
            $note->created_at = date('Y-m-d H:i:s');
            echo '<div class="ticket__note">
                    <div class="ticket__note-header">
                        <span class="ticket__note-author">' . htmlspecialchars($note->author) . '</span>
                        <span class="ticket__note-date">' . htmlspecialchars($note->created_at) . '</span>
                    </div>
                    <p class="ticket__note-content">' . nl2br(htmlspecialchars($note->content)) . '</p>
                </div>';

        } catch (Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
    
}

?>