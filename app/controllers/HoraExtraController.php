<?php
require_once 'Controller_nuevo.php';

class HoraExtraController extends Controller {
    public function registro() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /ZIGMA/public_nuevo/index.php'); exit;
        }
        $this->view('horas_extras/registro');
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $user = $_SESSION['user'];
            $model = $this->model('HoraExtraModel');

            $data = [
                'cantidad' => (int)($_POST['cantidad'] ?? 0),
                'tipo' => $_POST['tipo'] ?? 'HED',
                'dia' => $_POST['dia'] ?? date('d'),
                'mes' => $_POST['mes'] ?? date('m'),
                'anio' => $_POST['anio'] ?? date('Y'),
                'empleado_id' => (int)$user['empleado_id'],
                'valor' => 0, // se puede calcular al aprobar
            ];
            $model->registrar($data);
            header('Location: /ZIGMA/public_nuevo/index.php?url=horaextra/registro');
            exit;
        }
        $this->registro();
    }

    public function aprobacion() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $model = $this->model('HoraExtraModel');
        $pendientes = $model->listarPendientes();
        $this->view('horas_extras/aprobacion', ['pendientes' => $pendientes]);
    }

    public function aprobar($id) {
        $model = $this->model('HoraExtraModel');
        $model->aprobar((int)$id);
        header('Location: /ZIGMA/public_nuevo/index.php?url=horaextra/aprobacion');
        exit;
    }
}
