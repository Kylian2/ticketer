<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer{
    
    /**
     * Initialise un mail avec les paramètres smtp, l'encodage utf8.
     * L'envoyeur du mail est celui de l'utilisateur spécifié par les variables d'environnement
     * 
     * @return PHPMailer une instance de mail PHPMailer
     */
    public static function init() {

        $mail = new PHPMailer(true);
    
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com '; 
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->setFrom($_ENV['EMAIL_USER'], 'VoteRight');

        return $mail;
    }
    
    /**
     * Envoie le mail passé en paramètre
     * 
     * @param PHPMailer une instance de mail PHPMailer
     */
    public static function send(PHPMailer $mail){
        return $mail->send();
    }
}

?>
