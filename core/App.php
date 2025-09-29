<?php
class App {
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        
        // Si hay URL, procesar el controlador
        if(isset($url[0]) && !empty($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }
        
        // Cargar el controlador
        require_once '../app/controllers/' . $this->controller . '.php';
        $controllerInstance = new $this->controller;
        
        // Verificar si el método existe en el controlador
        if(isset($url[1]) && !empty($url[1]) && method_exists($controllerInstance, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } else {
            // Si no existe el método solicitado, verificar que el método por defecto existe
            if(!method_exists($controllerInstance, $this->method)) {
                // Si el controlador no tiene método index, redirigir al login
                header('Location: /ZIGMA/public/index.php?url=Login');
                exit;
            }
        }
        
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    public function parseUrl() {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
