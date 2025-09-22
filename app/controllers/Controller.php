<?php
class Controller {
    public function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
    public function view($view, $data = []) {
        // Extraer variables del array $data para que estén disponibles en la vista
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
