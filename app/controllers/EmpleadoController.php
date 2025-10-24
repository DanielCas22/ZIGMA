<?php
require_once 'Controller_nuevo.php';

class EmpleadoController extends Controller {
    public function index() {
        $model = $this->model('EmpleadoModel');
        $empleados = $model->all();
        $this->view('empleados/index', ['empleados' => $empleados]);
    }

    public function create() {
        $this->view('empleados/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? null,
                'usuario' => $_POST['usuario'] ?? null,
                'contrasena' => $_POST['contrasena'] ?? null,
                'sueldo_actual' => $_POST['sueldo_actual'] ?? null,
                'tipo_doc' => $_POST['tipo_doc'] ?? null,
                'num_doc' => $_POST['num_doc'] ?? null,
            ];
            $model = $this->model('EmpleadoModel');
            $model->create($data);
            header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
            exit;
        }
        $this->create();
    }

    public function edit($id) {
        $model = $this->model('EmpleadoModel');
        $empleado = $model->find($id);
        $this->view('empleados/edit', ['empleado' => $empleado]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? null,
                'usuario' => $_POST['usuario'] ?? null,
                'contrasena' => $_POST['contrasena'] ?? null,
                'sueldo_actual' => $_POST['sueldo_actual'] ?? null,
                'tipo_doc' => $_POST['tipo_doc'] ?? null,
                'num_doc' => $_POST['num_doc'] ?? null,
            ];
            $model = $this->model('EmpleadoModel');
            $model->update($id, $data);
            header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
            exit;
        }
        $this->edit($id);
    }

    public function delete($id) {
        $model = $this->model('EmpleadoModel');
        $model->delete($id);
        header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
        exit;
    }
}
