<?php 

@require_once('models/user.php');
@require_once('core/sessionGuard.php');

class AuthController {

    public static function form(){
        @require_once('views/layout.php');
        @require_once('views/login.php');
    }

    public static function register(){
        parse_str(file_get_contents("php://input"), $body);

        if(!isset($body['email']) || !isset($body['password']) || !isset($body['name']) || empty($body['email']) || empty($body['password']) || empty($body['name'])) {
            http_response_code(400);
            echo "Bad Request: Missing required fields.";
            return;
        }
        
        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo "Bad Request: Invalid email format.";
            return;
        }

        $email = $body['email'];
        $password = $body['password'];
        $name = $body['name'];
        $role = 'tester';

        if(isset($body['role']) && in_array($body['role'], ['tester', 'admin'])) {
            $role = $body['role'];
        }

        if (User::getByEmail($email)) {
            http_response_code(400);
            echo "Bad Request: Email already exists.";
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User();
        $user->email = $email;
        $user->password = $hashedPassword;
        $user->name = $name;
        $user->role = $role;

        if ($user->save(true)) {
            //SessionGuard::start($user); // Uncomment this line if you want to start a session immediately after registration (when a registration page will be implemented)
            echo "Registration successful. Welcome, " . htmlspecialchars($name) . "!"; 
            return;
        } else {
            http_response_code(500);
            echo "Registration failed. Please try again.";
        }
    }

    public static function login(){
        parse_str(file_get_contents("php://input"), $body);

        $email = $body['email'];
        $password = $body['password'];

        $user = SessionGuard::verifyCredentials($email, $password);

        if ($user) {
            SessionGuard::start($user);
            header('Location: /tickets');
        } else {
            header('Location: /login?error=invalid_credentials');
        }
    }

    public static function logout(){
        SessionGuard::stop();
        header('HX-Redirect: /login'); 
    }

}