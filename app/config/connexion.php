<?php

require 'vendor/autoload.php'; // Chargez l'autoloader de Composer

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

class Connexion {
    static private $hostname;
    static private $database;
    static private $login;
    static private $password;

    

    static public function init(){

        self::$hostname = $_ENV['DB_HOST'];
        self::$database = $_ENV['DB_NAME'];
        self::$login = $_ENV['DB_USER'];
        self::$password = $_ENV['DB_PASS'];
    }

    static private $tabUTF8 = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    static private $pdo; //Materialise la connexion

    static public function pdo() {
        return self::$pdo;
    }

    static public function connect(){

        self::init();

        $h = self::$hostname;
        $d = self::$database;
        $l = self::$login;
        $p = self::$password;
        $t = self::$tabUTF8;

        try{
            self::$pdo = new PDO("mysql:host=$h;dbname=$d",$l,$p,$t);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Connexion error : ".$e->getMessage()."<br>";
        }
    }
}

?>