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

    public static function getAll() {
        $request = "SELECT * FROM tickets;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "Ticket");
        $tickets = $result->fetchAll();
        return $tickets;
    }

}