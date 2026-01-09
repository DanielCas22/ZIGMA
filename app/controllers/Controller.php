<?php
namespace App\Controllers;

class Controller {
    protected function baseUrl() {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $base = explode('/public', $scriptName)[0];
        return $base;
    }
    
    protected function requireAuth($allowedRoles = []) {
        // Verificar que la sesión existe y tiene usuario
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Login');
            exit;
        }
        
        // Si se especifican roles permitidos, verificar que el usuario tiene uno de ellos
        if (!empty($allowedRoles)) {
            $userRole = $_SESSION['user']['rol'] ?? null;
            if (!in_array($userRole, (array)$allowedRoles)) {
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Dashboard');
                exit;
            }
        }
    }
    
    public function model($model) {
        $modelClass = "App\\Models\\$model";
        return new $modelClass();
    }
    public function view($view, $data = []) {
        // Extraer variables del array $data para que estén disponibles en la vista
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
