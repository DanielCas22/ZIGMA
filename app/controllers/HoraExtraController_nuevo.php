<?php
require_once 'Controller_nuevo.php';

class HoraextraController_nuevo extends Controller {
    public function registro() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $this->view('horas_extras/registro');
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
            $user = $_SESSION['user'];
            $model = $this->model('HoraExtraModel');

            $data = [
                'cantidad' => (int)($_POST['cantidad'] ?? 0),
                'tipo' => $_POST['tipo'] ?? 'HED',
                'dia' => $_POST['dia'] ?? date('d'),
                'mes' => $_POST['mes'] ?? date('m'),
                'anio' => $_POST['anio'] ?? date('Y'),
                'empleado_id' => (int)$user['empleado_id'],
                'valor' => 0,
            ];
            $errors = [];
            if ($data['cantidad'] <= 0) $errors[] = 'La cantidad de horas debe ser mayor a 0';
            if (!in_array($data['tipo'], ['HED','HEN','HEFD','HEFN'])) $errors[] = 'Tipo de hora extra inválido';
            if (!empty($errors)) {
                $_SESSION['flash']['error'] = implode("\n", $errors);
                header('Location: /ZIGMA/public_nuevo/index.php?url=horaextra/registro');
                exit;
            }
            $model->registrar($data);
            $_SESSION['flash']['success'] = 'Hora extra registrada y pendiente de aprobación';
            header('Location: /ZIGMA/public_nuevo/index.php?url=horaextra/registro');
            exit;
        }
        $this->registro();
    }

    public function aprobacion() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $model = $this->model('HoraExtraModel');
        $pendientes = $model->listarPendientes();
        $this->view('horas_extras/aprobacion', ['pendientes' => $pendientes]);
    }

    public function aprobar($id) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $model = $this->model('HoraExtraModel');
        $model->aprobar((int)$id);
        $_SESSION['flash']['success'] = 'Hora extra aprobada';
        header('Location: /ZIGMA/public_nuevo/index.php?url=horaextra/aprobacion');
        exit;
    }
}
