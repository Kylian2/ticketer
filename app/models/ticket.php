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

    public static function getAll($orderby = 'date') {
        $request = "SELECT t.id, title, description, status, priority, category, t.created_at, t.updated_at, u.name as user 
                    FROM tickets t INNER JOIN users u ON u.id = t.user";

        switch ($orderby) {
            case 'date':
                $request .= " ORDER BY t.created_at DESC;";
                break;
            case 'priority':
                $request .= " ORDER BY t.priority DESC, t.created_at DESC;";
                break;
            case 'status':
                $request .= " ORDER BY t.status, t.created_at DESC;";
                break;
            default:
                $request .= ";";
        }
        
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

        $userRequest = "SELECT u.email, u.name, u.role FROM users u INNER JOIN tickets t ON u.id = t.user WHERE t.id = :id;";
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

    public function save() {
        $request = "INSERT INTO tickets (title, description, status, category, user) 
                    VALUES (:title, :description, :status, :category, :user);";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':title', $this->title, PDO::PARAM_STR);
        $prepare->bindValue(':description', $this->description, PDO::PARAM_STR);
        $prepare->bindValue(':status', $this->status, PDO::PARAM_STR);
        $prepare->bindValue(':category', $this->category, PDO::PARAM_STR);
        $prepare->bindValue(':user', $this->user, PDO::PARAM_STR);
        $prepare->execute();
        $this->id = connexion::pdo()->lastInsertId();
        return $this;
    }

    public function update() {
        $request = "UPDATE tickets SET status = :status, priority = :priority WHERE id = :id;";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindValue(':status', $this->status, PDO::PARAM_STR);
        $prepare->bindValue(':priority', $this->priority, PDO::PARAM_STR);
        $prepare->bindValue(':id', $this->id, PDO::PARAM_INT);
        $prepare->execute();
    }

}