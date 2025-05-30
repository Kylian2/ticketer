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

    public static function create() {
        require_once('views/layout.php');
        require_once('views/index.php');
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

    public static function store() {
        parse_str(file_get_contents("php://input"), $body);

        if (!isset($body['user_name']) || empty($body['user_name'])) {
            http_response_code(400);
            echo "Bad Request: Name is required.";
            return;
        }

        if (!isset($body['user_email']) || empty($body['user_email'])) {
            http_response_code(400);
            echo "Bad Request: Email is required.";
            return;
        }

        if (!isset($body['ticket_title']) || empty($body['ticket_title'])) {
            http_response_code(400);
            echo "Bad Request: Title is required.";
            return;
        }

        if (!isset($body['ticket_description']) || empty($body['ticket_description'])) {
            http_response_code(400);
            echo "Bad Request: Description is required.";
            return;
        }

        if (!isset($body['ticket_category']) || (!in_array($body['ticket_category'], ['bug', 'upgrade', 'feedback', 'feature_request']))) {
            http_response_code(400);
            echo "Bad Request: Missing or incorrect category.";
            return;
        }

        try {
            $user = User::getByEmail($body['user_email']);

            if(!$user){
                $user = new User();
                $user->name = $body['user_name'];
                $user->email = $body['user_email'];
                $user->save();
            }

            $ticket = new Ticket();
            $ticket->title = $body['ticket_title'];
            $ticket->description = $body['ticket_description'];
            $ticket->user = $user->email;
            $ticket->status = 'new';
            $ticket->category = $body['ticket_category'];
            $ticket->save();
            http_response_code(200);
            Header('Location: /?success=1');
        } catch (Exception $e) {
            http_response_code(500);
            Header('Location: /?error='.$e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public static function update(array $params) {

        parse_str(file_get_contents("php://input"), $body);

        if (!isset($params[0]) || !is_numeric($params[0])) {
            http_response_code(400);
            echo "Bad Request: Missing or incorrect parameters.";
            return;
        }

        if(!SessionGuard::isAdmin()){
            http_response_code(403);
            echo "Forbidden: You do not have permission to update this ticket.";
            return;
        }

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
            $message->author_email = SessionGuard::getUser()->email;
            $message->author = SessionGuard::getUser()->name;
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
            $note->author_email = SessionGuard::getUser()->email;
            $note->author = SessionGuard::getUser()->name;
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