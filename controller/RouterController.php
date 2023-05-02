<?php

require_once _ROOT_CONTROLLER . 'handleSanitize.php';
require_once _ROOT_CONTROLLER . 'ViewRenderer.php';
class Router extends handleSanitize{

    public $secure;
    private $renderView;
    protected $routes = [];

    public function __construct()
    {
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ){
            $this->secure = true;
            $this->renderView = new ViewRenderer();
        }else{
            $this->secure = false;
        }
    }

    protected function addRoute ($method, $pattern, $handler)
    {
        $this->routes[] = [$method, $pattern, $handler];
    }

    public function loadRoutesFromJson()
    {       
        try{
            $jsonFile = file_get_contents( _ROOT_MODEL . 'routes.json');
            if(!$jsonFile){
                $this->renderView->render('ErrorView', '', false);
                throw new Exception('Archivo routes.json no existe en el directorio model/');
            }
        }catch(Exception $e){
            $this->handlerError($e);
            die;
        }

        $routes = json_decode($jsonFile, true);

        foreach ($routes['routes'] as $route) {
            $this->addRoute($route['method'], $route['pattern'], $route['handler']);
        }
    }

    public function handleRequest() 
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = $this->SanitizeVar($_SERVER['REQUEST_URI']);
        $requestUrl = parse_url($url, PHP_URL_PATH);

        foreach ($this->routes as [$method, $pattern, $handler]) {
            if ($method !== $requestMethod) {
                continue;
            }
            $matches = [];
            if (preg_match($this->compileRouteRegex($pattern), $requestUrl, $matches)) {
                array_shift($matches); 
                $matches = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }

                list($controllerName, $methodName) = explode('@', $handler);
                require_once _ROOT_CONTROLLER . $controllerName . '.php'; 
                if (strpos($controllerName, 'admin/') !== false) {
                    session_start();
                    if (isset($_SESSION['username']) && isset($_SESSION['tipoUser'])) {
                        $controllerName = str_replace('admin/', '', $controllerName);
                    } else {
                        session_destroy();
                        die;
                    }    
                }
                $controller = new $controllerName();
                $controller->$methodName(...$matches);
                return;
            }
        }

        $this->renderView->render('ErrorView', '', false);
    }

    protected function compileRouteRegex($pattern) {
        $regex = '#^' . preg_replace_callback('#{(\w+)}#', function($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $pattern) . '/?$#';
        return $regex;
    }
        
    protected function SanitizeVar($var)
    {
        $var = filter_var( $var, FILTER_SANITIZE_URL);
        $var = htmlspecialchars($var, ENT_QUOTES);
        $var = strtolower($var);
        $var = preg_replace('/[^a-zA-Z0-9\/.=~-]/', '', $var);
        return $var;
    }
}