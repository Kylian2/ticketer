<?php 

@require_once('models/model.php');

class Note extends Model {

    public int $id;
    public string $content;
    public string $author;
    public string $author_email;
    public int $ticket;

    public static function getByTicketId(int $ticket) {
        $request = "SELECT n.id, n.content, n.created_at, u.name as author 
                    FROM notes n INNER JOIN users u ON n.user = u.email 
                    WHERE n.ticket = :ticket ORDER BY n.created_at ASC;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':ticket', $ticket, PDO::PARAM_INT);
        $prepare->execute();
        return $prepare->fetchAll(PDO::FETCH_CLASS, "Note");
    }

    public function save() {
        $request = "INSERT INTO notes (content, user, ticket) VALUES (:content, :user, :ticket);";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':content', $this->content, PDO::PARAM_STR);
        $prepare->bindValue(':user', $this->author_email, PDO::PARAM_STR);
        $prepare->bindValue(':ticket', $this->ticket, PDO::PARAM_INT);
        return $prepare->execute();
    }
}