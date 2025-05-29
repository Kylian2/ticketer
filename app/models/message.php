<?php 

@require_once('models/model.php');

class Message extends Model {

    public int $id;
    public string $content;
    public string $author;
    public int $ticket_id;

    public static function getByTicketId(int $ticket_id) {
        $request = "SELECT m.id, m.content, m.created_at, u.name as author 
                    FROM messages m INNER JOIN users u ON m.user = u.email 
                    WHERE m.ticket = :ticket_id ORDER BY m.created_at ASC;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll(PDO::FETCH_CLASS, "Message");
    }
}