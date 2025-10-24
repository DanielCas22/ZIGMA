<?php
require_once 'Controller_nuevo.php';

class ReportesController_nuevo extends Controller {
    private function ensureAuth() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        return $rol;
    }

    public function index() {
        $this->ensureAuth();
        $model = $this->model('ReporteModel');
        $reportes = $model->listar();
        $this->view('reportes/index', ['reportes' => $reportes]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /ZIGMA/public_nuevo/index.php?url=reportes'); exit; }
        $this->ensureAuth();
        $nombre = trim($_POST['nombre'] ?? '');
        $anio = $_POST['anio'] ?? date('Y');
        $mes = $_POST['mes'] ?? date('m');
        if ($nombre === '') {
            $this->flash('error', 'El nombre del reporte es obligatorio');
            header('Location: /ZIGMA/public_nuevo/index.php?url=reportes');
            exit;
        }
        $model = $this->model('ReporteModel');
        if ($model->crear($nombre, $anio, $mes, null)) {
            $this->flash('success', 'Reporte creado');
        } else {
            $this->flash('error', 'No fue posible crear el reporte');
        }
        header('Location: /ZIGMA/public_nuevo/index.php?url=reportes');
        exit;
    }
}
