<?php
require_once 'Controller_nuevo.php';

class EmpleadoController_nuevo extends Controller {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $model = $this->model('EmpleadoModel');
        $empleados = $model->all();
        $this->view('empleados/index', ['empleados' => $empleados]);
    }

    public function create() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $this->view('empleados/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
            $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
            if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? null,
                'usuario' => $_POST['usuario'] ?? null,
                'contrasena' => $_POST['contrasena'] ?? null,
                'sueldo_actual' => $_POST['sueldo_actual'] ?? null,
                'tipo_doc' => $_POST['tipo_doc'] ?? null,
                'num_doc' => $_POST['num_doc'] ?? null,
            ];
            // Validación
            $errors = [];
            if (trim($data['nombre']) === '') $errors[] = 'El nombre es obligatorio';
            if ($data['sueldo_actual'] !== null && (!is_numeric($data['sueldo_actual']) || (int)$data['sueldo_actual'] < 0)) $errors[] = 'El sueldo debe ser numérico y positivo';
            if (!empty($data['usuario']) && strlen($data['usuario']) < 3) $errors[] = 'El usuario debe tener al menos 3 caracteres';
            if (!empty($errors)) {
                $this->setOld($data);
                $this->flash('error', implode('\n', $errors));
                header('Location: /ZIGMA/public_nuevo/index.php?url=empleado/create');
                exit;
            }
            $model = $this->model('EmpleadoModel');
            $model->create($data);
            $this->flash('success', 'Empleado creado correctamente');
            header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
            exit;
        }
        $this->create();
    }

    public function edit($id) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $model = $this->model('EmpleadoModel');
        $empleado = $model->find($id);
        $this->view('empleados/edit', ['empleado' => $empleado]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() == PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
            $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
            if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? null,
                'usuario' => $_POST['usuario'] ?? null,
                'contrasena' => $_POST['contrasena'] ?? null,
                'sueldo_actual' => $_POST['sueldo_actual'] ?? null,
                'tipo_doc' => $_POST['tipo_doc'] ?? null,
                'num_doc' => $_POST['num_doc'] ?? null,
            ];
            $errors = [];
            if (trim($data['nombre']) === '') $errors[] = 'El nombre es obligatorio';
            if ($data['sueldo_actual'] !== null && (!is_numeric($data['sueldo_actual']) || (int)$data['sueldo_actual'] < 0)) $errors[] = 'El sueldo debe ser numérico y positivo';
            if (!empty($errors)) {
                $this->setOld($data);
                $this->flash('error', implode('\n', $errors));
                header('Location: /ZIGMA/public_nuevo/index.php?url=empleado/edit/' . (int)$id);
                exit;
            }
            $model = $this->model('EmpleadoModel');
            $model->update($id, $data);
            $this->flash('success', 'Empleado actualizado');
            header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
            exit;
        }
        $this->edit($id);
    }

    public function delete($id) {
    if (session_status() == PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
    $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
    if (!in_array($rol, ['admin','rrhh'])) { header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard'); exit; }
        $model = $this->model('EmpleadoModel');
        $model->delete($id);
        header('Location: /ZIGMA/public_nuevo/index.php?url=empleado');
        exit;
    }
}
