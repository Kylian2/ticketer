<?php 

@require_once('models/model.php');

class Ticket extends Model {

    public int $id;
    public string $title;
    public string $description;
    public string $status;
    public string $priority;
    public string $category;
    public string $user;
    public User $userObject;

    public static function getAll() {
        $request = "SELECT id, title, description, status, priority, category, t.created_at, t.updated_at, u.name as user 
                    FROM tickets t INNER JOIN users u ON u.email = t.user;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "Ticket");
        $tickets = $result->fetchAll();
        return $tickets;
    }

    public static function getById(int $id) {
        $request = "SELECT id, title, description, status, priority, category, t.created_at, t.updated_at
                    FROM tickets t WHERE id = :id;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':id', $id, PDO::PARAM_INT);
        $prepare->execute();
        $prepare->setFetchmode(PDO::FETCH_CLASS, "Ticket");
        $ticket = $prepare->fetch();
        
        if (!$ticket) {
            throw new Exception("Ticket not found");
        }

        $userRequest = "SELECT u.email, u.name, u.role FROM users u INNER JOIN tickets t ON u.email = t.user WHERE t.id = :id;";
        $userPrepare = connexion::pdo()->prepare($userRequest);
        $userPrepare->bindValue(':id', $id, PDO::PARAM_INT);
        $userPrepare->execute();
        $userPrepare->setFetchmode(PDO::FETCH_CLASS, "User");
        $user = $userPrepare->fetch();

        if (!$user) {
            throw new Exception("User not found for this ticket");
        }

        $ticket->userObject = $user;
        $ticket->user = $user->name;
        
        return $ticket;
    }

}