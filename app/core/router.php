<?php 

@require_once('core/sessionGuard.php');

class Router{
    public static $routes = array();
    public static $algoroutes = array();

    /**
     * Ajoute une route à la liste
     * 
     * @param string $method Le type de request (GET, POST, etc).
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     * @param bool $algo true si la route est accessible via l'api algorithms, false sinon (par défaut = false).
     */
    public static function addRoute(string $method, string $uri, string $actionController, bool $protected = false, bool $algo = false){
        if($algo){
            self::$algoroutes[$method][trim($uri, '/')] = [
                'uri' => trim($uri, '/'), #trim retire le '/' au début et en fin de chaine
                'actionController' => $actionController,
                'middleware' => $protected,
            ];
        }else{
            self::$routes[$method][trim($uri, '/')] = [
                'uri' => trim($uri, '/'), #trim retire le '/' au début et en fin de chaine
                'actionController' => $actionController,
                'middleware' => $protected,
            ];
        }
    }

    /**
     * Défini une route GET 
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     * @param bool $algo true si la route est accessible via l'api algorithms, false sinon (par défaut = false).
     */
    public static function get(string $uri, string $actionController, bool $protected = false, bool $algo = false){
        self::addRoute('GET', $uri, $actionController, $protected, $algo);
    }

    /**
     * Défini une route POST 
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     * @param bool $algo true si la route est accessible via l'api algorithms, false sinon (par défaut = false).
     */
    public static function post(string $uri, string $actionController, bool $protected = false, bool $algo = false){
        self::addRoute('POST', $uri, $actionController, $protected, $algo);
    }


    /**
     * Défini une route PUT
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     * @param bool $algo true si la route est accessible via l'api algorithms, false sinon (par défaut = false).
     */
    public static function put(string $uri, string $actionController, bool $protected = false, bool $algo = false){
        self::addRoute('PUT', $uri, $actionController, $protected, $algo);
    }

    /**
     * Défini une route PATCH
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     */
    public static function patch(string $uri, string $actionController, bool $protected = false, bool $algo = false){
        self::addRoute('PATCH', $uri, $actionController, $protected, $algo);
    }

    /**
     * Défini une route DELETE
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     * @param bool $protected true si la route doit être protégée, false sinon (par défaut = false).
     * @param bool $algo true si la route est accessible via l'api algorithms, false sinon (par défaut = false).
     */
    public static function delete(string $uri, string $actionController, bool $protected = false, bool $algo = false){
        self::addRoute('DELETE', $uri, $actionController, $protected, $algo);
    }

    /**
     * Lance une action d'un controller
     * 
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     */
    protected static function callAction($actionController, $params = null)
    {
        list($controller, $action) = explode('@', $actionController); // permet de recuperer le controller et l'action dans la chaine controller@action

        @require_once('controllers/' . $controller.'.php');

        $controllerInstance = new $controller; // Instancier permet d'utiliser la fonction method_exists

        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("$action method has not been found in $controllerClass");
        }

        /* Si des paramètres sont passé, on les transmets à l'action */
        if($params){
            return $controllerInstance->$action($params);
        }

        // Appeler la méthode du contrôleur
        return $controllerInstance->$action();
    }

    /**
     * Le point central de l'api, elle joue le rôle de "dispatcheur", en analysant l'URL et la méthode HTTP de la requête pour décider quel contrôleur et quelle méthode appeler
     * 
     * @param string $request $_SERVER['REQUEST_URI']
     * @param string $method $_SERVER['REQUEST_METHOD']
     */
    public static function dispatch($request, $method){

        if(!in_array(strtoupper($method), array_keys(self::$routes))){
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        //On "nettoie" la request 
        $request = trim(parse_url($request, PHP_URL_PATH), '/');

        /* Vérification des routes statiques avant les routes dynamiques*/
        foreach(self::$routes[strtoupper($method)] as $route){
            if($route['uri'] === $request){
                if(!$route['middleware']){

                    require_once('views/layout.php');

                    self::callAction($route['actionController']);
                    return;
                }

                if(SessionGuard::checkSessionValidity()){

                    require_once('views/layout.php');
                    
                    self::callAction($route['actionController']);
                    return;
                }
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }
        }

        /* Vérification des routes dynamiques */
        foreach(self::$routes[strtoupper($method)] as $route){

            // Pattern pour verifier la concordance des routes, en prenant en compte les paramètres dynamiques
            $routePattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route['uri']);  
            // Exemple: /communities/{communityId}/prop/{propId} -> /communities/(\w+)/prop/(\w+)
            $routePattern = "#^$routePattern$#";

            if (preg_match($routePattern, $request, $params)) {

                // Supprimer l'élément $params[0] qui est l'URL entière
                array_shift($params); 
    
                if(!$route['middleware']){
                    self::callAction($route['actionController'], $params);
                    return;
                }

                if(SessionGuard::checkSessionValidity()){
                    self::callAction($route['actionController'], $params);
                    return;
                }

                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    /**
     * Le point central de l'api, elle joue le rôle de "dispatcheur", en analysant l'URL et la méthode HTTP de la requête pour décider quel contrôleur et quelle méthode appeler à propose des routes "algo"
     * 
     * @param string $request $_SERVER['REQUEST_URI']
     * @param string $method $_SERVER['REQUEST_METHOD']
     */
    public static function dispatch_algo($request, $method){

        if(!in_array(strtoupper($method), array_keys(self::$algoroutes))){
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }

        //On "nettoie" la request 
        $request = trim(parse_url($request, PHP_URL_PATH), '/');

        /* Vérification des routes statiques avant les routes dynamiques*/
        foreach(self::$algoroutes[strtoupper($method)] as $route){
            if($route['uri'] === $request){

                self::callAction($route['actionController']);
                return;

                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }
        }

        /* Vérification des routes dynamiques */
        foreach(self::$algoroutes[strtoupper($method)] as $route){

            // Pattern pour verifier la concordance des routes, en prenant en compte les paramètres dynamiques
            $routePattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route['uri']);  
            // Exemple: /communities/{communityId}/prop/{propId} -> /communities/(\w+)/prop/(\w+)
            $routePattern = "#^$routePattern$#";

            if (preg_match($routePattern, $request, $params)) {

                // Supprimer l'élément $params[0] qui est l'URL entière
                array_shift($params); 

                self::callAction($route['actionController'], $params);
                return;

                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}

?>