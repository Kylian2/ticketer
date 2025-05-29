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

    public static function getByEmail($email) {
        $request = "SELECT * FROM users WHERE USR_email_VC = :email;";
        $result = connexion::pdo()->prepare($request);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        $result->setFetchmode(PDO::FETCH_CLASS, "user");
        $user = $result->fetch();
        return $user;
    }

}