<?php 

@require_once('models/model.php');

class Message extends Model {

    public int $id;
    public string $content;
    public string $author;
    public string $author_email;
    public int $ticket;

    public static function getByTicketId(int $ticket) {
        $request = "SELECT m.id, m.content, m.created_at, u.name as author 
                    FROM messages m INNER JOIN users u ON m.user = u.email 
                    WHERE m.ticket = :ticket ORDER BY m.created_at ASC;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':ticket', $ticket, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll(PDO::FETCH_CLASS, "Message");
    }

    public function save() {
        $request = "INSERT INTO messages (content, user, ticket) VALUES (:content, :user, :ticket);";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':content', $this->content, PDO::PARAM_STR);
        $prepare->bindValue(':user', $this->author_email, PDO::PARAM_STR);
        $prepare->bindValue(':ticket', $this->ticket, PDO::PARAM_INT);
        return $prepare->execute();
    }
}