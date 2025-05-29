<?php 

@require_once('models/user.php');
@require_once('core/sessionGuard.php');

class AuthController {

    public static function form(){
        @require_once('views/layout.php');
        @require_once('views/login.php');
    }

}