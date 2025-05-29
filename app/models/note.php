<?php 

@require_once('models/model.php');

class Note extends Model {

    public int $id;
    public string $content;
    public string $author;
    public int $ticket_id;

    public static function getByTicketId(int $ticket_id) {
        $request = "SELECT n.id, n.content, n.created_at, u.name as author 
                    FROM notes n INNER JOIN users u ON n.user = u.email 
                    WHERE n.ticket = :ticket_id ORDER BY n.created_at ASC;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll(PDO::FETCH_CLASS, "Note");
    }
}