<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../models/SalarioPorRol.php';

class EmpleadoController extends Controller {
    private function baseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . '/ZIGMA';
    }
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Mostrar dashboard de empleados con roles
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAllWithRoles();
        
        // Procesar roles para cada empleado
        foreach ($empleados as &$empleado) {
            $empleado['rol'] = $empleado['rol_nombre'] ?? 'Sin rol';
            $empleado['roles'] = $empleado['todos_los_roles'] ?? '';
        }
        
        $this->view('empleado/index', ['empleados' => $empleados]);
    }
    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Mostrar formulario de registro con roles
        $rolModel = $this->model('Rol');
        $roles = $rolModel->getAll();
        $this->view('empleado/create', ['roles' => $roles]);
    }

    public function store() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombres'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $rol_especifico = $_POST['rol'] ?? 'empleado';
            $salario_manual = isset($_POST['sueldo_actual']) && !empty($_POST['sueldo_actual']) ? floatval($_POST['sueldo_actual']) : null;

            // Determinar el salario a usar
            $salario_final = $salario_manual;
            
            if (!$salario_final) {
                // Si no hay salario manual, obtener salario automático según el rol
                $salarioModel = $this->model('SalarioPorRol');
                $salario_final = $salarioModel->getSalarioByRol($rol_especifico);
                
                // Si no se encuentra salario para el rol, usar salario de empleado base
                if (!$salario_final) {
                    $salario_final = $salarioModel->getSalarioByRol('empleado');
                }
            }

            $empleadoModel = $this->model('Empleado');
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'sueldo_actual' => $salario_final
            ];
            $resultado = $empleadoModel->create($data);

            if ($resultado) {
                // Obtener el id del empleado recién insertado
                $empleado_id = $empleadoModel->getLastInsertId();

                // Crear usuario automáticamente
                $userModel = $this->model('User');
                $rolModel = $this->model('Rol');
                $rolHasUserModel = $this->model('RolHasUser');
                
                $username = strtolower(explode(' ', $nombre)[0]) . $empleado_id;
                $password = password_hash('123456', PASSWORD_DEFAULT);
                $user_id = $userModel->create($username, $password, $empleado_id);

                // Siempre asignar rol empleado primero
                $rolEmpleado = $rolModel->getByName('empleado');
                if ($rolEmpleado) {
                    $rolHasUserModel->assign($user_id, $rolEmpleado['id_rol']);
                }

                // Si seleccionó un rol específico diferente a empleado, asignarlo también
                if ($rol_especifico && $rol_especifico !== 'empleado') {
                    $rolEspecifico = $rolModel->getByName($rol_especifico);
                    if ($rolEspecifico) {
                        $rolHasUserModel->assign($user_id, $rolEspecifico['id_rol']);
                    }
                }

                // Redirigir al dashboard de empleados
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
                exit();
            } else {
                // Error al crear empleado
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/create&error=1');
                exit();
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if (!isset($_GET['id'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
            exit;
        }
        
        $id = intval($_GET['id']);
        $empleadoModel = $this->model('Empleado');
        $empleado = $empleadoModel->getById($id);
        
        if (!$empleado) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=notfound');
            exit;
        }
        
        // Obtener roles disponibles
        $rolModel = $this->model('Rol');
        $roles = $rolModel->getAll();
        
        // Obtener rol actual del empleado
        $empleadoConRol = $empleadoModel->getByIdWithRoles($id);
        
        $this->view('empleado/edit', [
            'empleado' => $empleado,
            'empleadoConRol' => $empleadoConRol,
            'roles' => $roles
        ]);
    }

    public function update() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $nombre = $_POST['nombres'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $rol_especifico = $_POST['rol'] ?? 'empleado';
            $salario_manual = isset($_POST['sueldo_actual']) && !empty($_POST['sueldo_actual']) ? floatval($_POST['sueldo_actual']) : null;

            // Determinar el salario a usar
            $salario_final = $salario_manual;
            
            if (!$salario_final) {
                // Si no hay salario manual, obtener salario automático según el rol
                $salarioModel = $this->model('SalarioPorRol');
                $salario_final = $salarioModel->getSalarioByRol($rol_especifico);
                
                // Si no se encuentra salario para el rol, usar salario de empleado base
                if (!$salario_final) {
                    $salario_final = $salarioModel->getSalarioByRol('empleado');
                }
            }

            $empleadoModel = $this->model('Empleado');
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'sueldo_actual' => $salario_final
            ];
            
            $resultado = $empleadoModel->update($id, $data);

            if ($resultado) {
                // Actualizar roles del usuario asociado
                $userModel = $this->model('User');
                $rolModel = $this->model('Rol');
                $rolHasUserModel = $this->model('RolHasUser');
                
                // Obtener el usuario asociado al empleado
                $usuario = $userModel->getByEmpleadoId($id);
                
                if ($usuario) {
                    $user_id = $usuario['id_doc'];
                    
                    // Eliminar roles actuales
                    $rolHasUserModel->removeAllByUserId($user_id);
                    
                    // Siempre asignar rol empleado primero
                    $rolEmpleado = $rolModel->getByName('empleado');
                    if ($rolEmpleado) {
                        $rolHasUserModel->assign($user_id, $rolEmpleado['id_rol']);
                    }

                    // Si seleccionó un rol específico diferente a empleado, asignarlo también
                    if ($rol_especifico && $rol_especifico !== 'empleado') {
                        $rolEspecifico = $rolModel->getByName($rol_especifico);
                        if ($rolEspecifico) {
                            $rolHasUserModel->assign($user_id, $rolEspecifico['id_rol']);
                        }
                    }
                }

                // Redirigir al dashboard de empleados
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&success=update');
                exit();
            } else {
                // Error al actualizar empleado
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/edit&id=' . $id . '&error=update');
                exit();
            }
        }
        
        // Si no es POST, redirigir al index
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
        exit();
    }

    public function updateSalario() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $empleado_id = intval($_POST['empleado_id']);
                $nuevo_sueldo = floatval($_POST['sueldo_actual']);
                
                $empleadoModel = $this->model('Empleado');
                $resultado = $empleadoModel->updateSalario($empleado_id, $nuevo_sueldo);
                
                if ($resultado) {
                    // Redirigir con mensaje de éxito
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&success=salario');
                    exit();
                } else {
                    // Redirigir con mensaje de error
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=salario');
                    exit();
                }
            } catch (Exception $e) {
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=exception');
                exit();
            }
        }
        
        // Si no es POST, redirigir al index
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
        exit();
    }

    public function delete() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $empleadoModel = $this->model('Empleado');
            $empleadoModel->delete($id);
        }
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
        exit();
    }
}
