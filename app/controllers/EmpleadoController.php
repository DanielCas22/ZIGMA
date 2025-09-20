
<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Rol.php';

class EmpleadoController extends Controller {
    public function index() {
        // Mostrar dashboard de empleados
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAll();
        require __DIR__ . '/../views/empleado/index.php';
    }
    public function create() {
        // Mostrar formulario de registro con roles
        $rolModel = new Rol();
        $roles = $rolModel->getAll();
        require __DIR__ . '/../views/empleado/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombres'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $rol = $_POST['rol'] ?? '';

            $empleado = new Empleado();
            $empleado->nombre = $nombre;
            $empleado->apellidos = $apellidos;
            $empleado->save();

            // Redirigir al dashboard de empleados
            header('Location: index.php?url=Empleado/index');
            exit();
        }
    }
}
