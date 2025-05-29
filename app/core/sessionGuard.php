<?php 

@require_once('models/user.php');

define("DUREE_SESSION", 1200);

class SessionGuard{

    /**
     * Initialise une session utilisateur avec l'ajout de l'entité "user" dans les variables de session
     * et crée une variable de session "last_activity" égale à la date de dernière activité de l'utilisateur
     * 
     * Une session qui ne possède pas ces deux variables est invalide
     */
    public static function start($user){
        $_SESSION["user"] = $user;
        $_SESSION["last_activity"] = time();
    }

    /**
     * Verifie le couple email/mot de passe
     * 
     * @param string $email
     * @param string $password
     * 
     * @return mixed l'entite user si le couple est bon, false sinon
     */
    public static function verifyCredentials($email, $password){
        $user = User::getByEmail($email);
        if(!$user){
            return false;
        }
        $verification = password_verify($password, $user->get('password'));
        return $verification ? $user : false;
    }

    /**
     * Verifie la validité de la session et relance le timer d'activité ou mets fin à la session
     * 
     * @return bool true si valide, false sinon
     */
    public static function checkSessionValidity(){
        if(isset($_SESSION["user"]) && isset($_SESSION["last_activity"])){
            if(time() - $_SESSION["last_activity"] < DUREE_SESSION){
                $_SESSION["last_activity"] = time();
                return true;
            }else{
                self::stop();
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * Relance le timer d'activité
     */
    public static function updateLastActivity(){
        if(isset($_SESSION["user"]) && isset($_SESSION["last_activity"])){
            $_SESSION["last_activity"] = time();
        }
    }

    public static function getUser(){
        if(isset($_SESSION["user"]) && isset($_SESSION["last_activity"])){
            $_SESSION["last_activity"] = time();
            return $_SESSION["user"];
        }
    }

    public static function getUserId(){
        if(isset($_SESSION["user"]) && isset($_SESSION["last_activity"])){
            $_SESSION["last_activity"] = time();
            $user = $_SESSION["user"];
            return $user->get('id');
        }
    }

    public static function isAdmin(){
        if(isset($_SESSION["user"]) && isset($_SESSION["last_activity"])){
            $_SESSION["last_activity"] = time();
            $user = $_SESSION["user"];
            return $user->get('role') === 'admin';
        }
        return false;
    }

    /**
     * Détruit la session
     */
    public static function stop(){
        session_destroy();
    }
}

?>