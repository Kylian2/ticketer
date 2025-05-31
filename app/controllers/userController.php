<?php 

@require_once('models/user.php');
@require_once('core/sessionGuard.php');

class UserController {

    public static function index() {
        echo "<h1> User controller - index </h1>";
    }

    public static function settings() {
        $user = SessionGuard::getUser();
        require_once('views/settings.php');
    }

    public static function patch() {

        parse_str(file_get_contents("php://input"), $body);

        if(isset($_GET['type']) && $_GET['type'] === 'password') {
           
            $user = SessionGuard::getUser();
            $oldPassword = $body['old-password'] ?? '';
            $newPassword = $body['new-password'] ?? '';
            $confirmPassword = $body['confirm-password'] ?? '';

            if ($newPassword !== $confirmPassword) {
                echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Les mots de passe ne correspondent pas</p>';
                return;
            }

            if (password_verify($oldPassword, $user->password)) {
                $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
                
                try {
                    if ($user->update()) {
                        echo '<p class="message message--success"><span class="material-symbols-rounded">celebration</span>Mot de passe mis à jour avec succès</p>';
                        SessionGuard::start($user);
                    } else {
                        echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Echec de la mise à jour du mot de passe</p>';
                    }
                } catch (Exception $e) {
                    echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Erreur lors de la mise à jour du mot de passe :'. $e->getMessage().'</p>';
                }

            } else {
                echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Ancien mot de passe incorrecte</p>';
            }

        } elseif (isset($_GET['type']) && $_GET['type'] === 'email') {
            $user = SessionGuard::getUser();
            $newEmail = $body['email'] ?? '';
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Format d\'email incorrecte</p>';
                return;
            }

            if ($user->email === $newEmail) {
                echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>L\'email est déjà reconnu</p>';
                return;
            }

            $user->email = $newEmail;

            try {

                if ($user->update()) {
                    echo '<p class="message message--success"><span class="material-symbols-rounded">celebration</span>Email mis à jour avec succès</p>';
                    SessionGuard::start($user);
                } else {
                    echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Echec de la mise à jour de l\'email</p>';
                }
                
            } catch (Exception $e) {
                echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Erreur lors de la mise à jour de l\'email :'. $e->getMessage().'</p>';
            }

        } else {
            echo '<p class="message message--error"><span class="material-symbols-rounded">error</span>Type de mise à jour non reconnue</p>';
        }

    }
}

?>