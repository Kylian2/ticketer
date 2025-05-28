<?php 

@require_once('models/model.php');

class User extends Model {

    public string $email;
    public string $password;
    public string $name;
    public string $role;

    public static function getAll() {
        $request = "SELECT * FROM users;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "user");
        $users = $result->fetchAll();
        return $users;
    }

}