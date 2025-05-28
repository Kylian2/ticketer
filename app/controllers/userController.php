<?php 

@require_once('models/user.php');
@require_once('core/sessionGuard.php');

class UserController {

    public static function index() {
        echo "<h1> User controller - index </h1>";
    }
}

?>