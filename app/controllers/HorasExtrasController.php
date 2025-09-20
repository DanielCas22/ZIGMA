<?php
class HorasExtrasController extends Controller {
    private function baseUrl() {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $base = explode('/public', $scriptName)[0];
        return $base;
    }
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        $empleado_id = isset($_GET['empleado_id']) ? $_GET['empleado_id'] : '';
        if ($empleado_id) {
            $horas = $horasExtrasModel->getByEmpleado($empleado_id);
        } else {
            $horas = $horasExtrasModel->getAllWithEmpleado();
        }
        $this->view('horas_extras/index', ['horas' => $horas, 'empleados' => $empleados, 'empleado_id' => $empleado_id]);
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'empleado_id' => $_POST['empleado_id'],
                'valor' => $_POST['valor'],
                'cantidad' => $_POST['cantidad'],
                'tipo' => $_POST['tipo'],
                'porcentaje' => $_POST['porcentaje'],
                'dia' => $_POST['dia'],
                'mes' => $_POST['mes'],
                'año' => $_POST['año']
            ];
            $horasExtrasModel = $this->model('HorasExtras');
            $horasExtrasModel->create($data);
            header('Location: /ZIGMA/public/index.php?url=HorasExtras');
            exit;
        }
        $this->view('horas_extras/create', ['empleados' => $empleados]);
    }

    public function edit($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'empleado_id' => $_POST['empleado_id'],
                'valor' => $_POST['valor'],
                'cantidad' => $_POST['cantidad'],
                'tipo' => $_POST['tipo'],
                'porcentaje' => $_POST['porcentaje'],
                'dia' => $_POST['dia'],
                'mes' => $_POST['mes'],
                'año' => $_POST['año']
            ];
            $horasExtrasModel->update($id, $data);
            header('Location: /ZIGMA/public/index.php?url=HorasExtras');
            exit;
        }
        $hora = $horasExtrasModel->find($id);
        $this->view('horas_extras/edit', ['hora' => $hora, 'empleados' => $empleados]);
    }

    public function delete($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $horasExtrasModel->delete($id);
        header('Location: /ZIGMA/horas_extras');
        exit;
    }
}
