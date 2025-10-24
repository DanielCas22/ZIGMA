<?php
require_once 'Controller_nuevo.php';

class UsuarioController_nuevo extends Controller {
    private function ensureAuth() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
    }

    public function index() {
        $this->ensureAuth();
        $model = $this->model('UserAdminModel');
        $usuarios = $model->listarUsuariosConRol();
        $roles = $model->rolesDisponibles();
        $this->view('usuarios/index', ['usuarios' => $usuarios, 'roles' => $roles]);
    }

    public function asignar_rol() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /ZIGMA/public_nuevo/index.php?url=usuario'); exit; }
        $this->ensureAuth();
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $rolId = isset($_POST['rol_id']) ? (int)$_POST['rol_id'] : 0;
        if (!$userId || !$rolId) {
            $this->flash('error', 'Datos inválidos');
            header('Location: /ZIGMA/public_nuevo/index.php?url=usuario');
            exit;
        }
        $model = $this->model('UserAdminModel');
        if ($model->asignarRol($userId, $rolId)) {
            $this->flash('success', 'Rol actualizado');
        } else {
            $this->flash('error', 'No fue posible actualizar el rol');
        }
        header('Location: /ZIGMA/public_nuevo/index.php?url=usuario');
        exit;
    }
}
