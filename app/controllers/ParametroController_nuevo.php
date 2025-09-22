<?php
require_once 'Controller_nuevo.php';

class ParametroController_nuevo extends Controller {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $m = $this->model('ParametroModel');
        $data = [
            'SMLV' => $m->get('SMLV', 1300000),
            'AUXILIO_TRANSPORTE' => $m->get('AUXILIO_TRANSPORTE', 162000),
            'UVT' => $m->get('UVT', 47065),
        ];
        $this->view('parametros/index', ['p' => $data]);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
            if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
            $m = $this->model('ParametroModel');
            $errors = [];
            foreach (['SMLV','AUXILIO_TRANSPORTE','UVT'] as $k) {
                if (!isset($_POST[$k]) || (int)$_POST[$k] <= 0) $errors[] = "{$k} debe ser un entero positivo";
            }
            if (!empty($errors)) {
                $_SESSION['flash']['error'] = implode('\n', $errors);
                header('Location: /ZIGMA/public_nuevo/index.php?url=parametro');
                exit;
            }
            foreach (['SMLV','AUXILIO_TRANSPORTE','UVT'] as $k) {
                $m->set($k, (int)$_POST[$k]);
            }
            $_SESSION['flash']['success'] = 'Parámetros guardados';
            header('Location: /ZIGMA/public_nuevo/index.php?url=parametro');
            exit;
        }
        $this->index();
    }
}
