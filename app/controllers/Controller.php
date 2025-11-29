<?php
namespace App\Controllers;

class Controller {
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
