<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Rol.php';
require_once __DIR__ . '/../models/SalarioPorRol.php';
require_once __DIR__ . '/../models/ARLModel.php';
require_once __DIR__ . '/../models/RolePermissions.php';

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
        
        // Verificar permisos de lectura
        RolePermissions::redirectIfNoPermission('empleados', 'read');
        
        // Mostrar dashboard de empleados con roles
        $empleadoModel = $this->model('Empleado');
        // Obtener empleados válidos (excluye roles)
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
        
        // Verificar permisos de creación
        RolePermissions::redirectIfNoPermission('empleados', 'create');
        
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
        
        // Verificar permisos de creación
        RolePermissions::redirectIfNoPermission('empleados', 'create');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombres'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $rol_especifico = $_POST['rol'] ?? 'empleado';
            $riesgo_arl = isset($_POST['riesgo_arl']) ? intval($_POST['riesgo_arl']) : 2; // Por defecto Clase II
            $salario_manual = isset($_POST['sueldo_actual']) && !empty($_POST['sueldo_actual']) ? floatval($_POST['sueldo_actual']) : null;
            $usuario = $_POST['usuario'] ?? '';
            $contrasena = $_POST['password'] ?? '';
            //$tipo_documento = $_POST['tipo_documento'] ?? '';
            //$numero_documento = $_POST['numero_documento'] ?? '';

            // Eliminar validación y uso de tipo_documento y numero_documento
            //if (empty($tipo_documento) || empty($numero_documento)) {
            //    header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/create&error=1');
            //    exit();
            //}

            // Determinar el salario a usar
            $salario_final = $salario_manual;
            if (!$salario_final) {
                // Si no hay salario manual, obtener salario automático según el rol
                $salarioModel = $this->model('SalarioPorRol');
                $salario_final = $salarioModel->getSalarioByRol($rol_especifico);
                if (!$salario_final) {
                    $salario_final = $salarioModel->getSalarioByRol('empleado');
                }
            }

            $empleadoModel = $this->model('Empleado');
            $data = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'sueldo_actual' => $salario_final,
                'usuario' => $usuario,
                'contrasena' => $contrasena,
                'rol' => $rol_especifico
            ];
            
            try {
                $resultado = $empleadoModel->create($data);

                if ($resultado) {
                    // Obtener el id del empleado recién insertado
                    $empleado_id = $empleadoModel->getLastInsertId();

                    // Asignar riesgo ARL al empleado
                    try {
                        $arlModel = $this->model('ARLModel');
                        $arlModel->asignarRiesgoEmpleado($empleado_id, $riesgo_arl);
                    } catch (Exception $e) {
                        error_log("Error al asignar riesgo ARL: " . $e->getMessage());
                    }

                    // Redirigir al dashboard de empleados
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
                    exit();
                } else {
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/create&error=1');
                    exit();
                }
            } catch (Exception $e) {
                // Capturar error de usuario duplicado u otros errores
                $errorMessage = urlencode($e->getMessage());
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/create&error=usuario_duplicado&message=' . $errorMessage);
                exit();
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos de actualización
        RolePermissions::redirectIfNoPermission('empleados', 'update');
        
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
        
        // Obtener riesgo ARL actual del empleado
        try {
            $arlModel = $this->model('ARLModel');
            $riesgoEmpleado = $arlModel->getRiesgoEmpleado($id);
            if ($riesgoEmpleado) {
                $empleado['riesgo_arl'] = $riesgoEmpleado['codigo_riesgo'];
            }
        } catch (Exception $e) {
            // Si hay error, continuar sin riesgo asignado
            error_log("Error al obtener riesgo ARL: " . $e->getMessage());
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
        
        // Verificar permisos de actualización
        RolePermissions::redirectIfNoPermission('empleados', 'update');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $nombre = $_POST['nombres'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $rol_especifico = $_POST['rol'] ?? 'empleado';
            $riesgo_arl = isset($_POST['riesgo_arl']) ? intval($_POST['riesgo_arl']) : 2; // Por defecto Clase II
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
                // Actualizar riesgo ARL del empleado
                try {
                    $arlModel = $this->model('ARLModel');
                    $arlModel->asignarRiesgoEmpleado($id, $riesgo_arl);
                } catch (Exception $e) {
                    // Si hay error al asignar ARL, continuar
                    error_log("Error al actualizar riesgo ARL: " . $e->getMessage());
                }

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
                $nuevo_sueldo = floatval($_POST['salario']);
                
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
        
        // Verificar permisos de eliminación (solo admin)
        RolePermissions::redirectIfNoPermission('empleados', 'delete');
        
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $empleadoModel = $this->model('Empleado');
            $empleadoModel->delete($id);
        }
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index');
        exit();
    }

    public function detalle() {
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
        $empleado = $empleadoModel->getByIdWithRoles($id);
        
        if (!$empleado) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=notfound');
            exit;
        }
        
        // Obtener horas extras del empleado si existen
        $horasExtrasModel = $this->model('HorasExtras');
        $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($id);
        
        // Calcular totales de horas extras
        $total_horas_extras = 0;
        $total_valor_extras = 0;
        if ($horasExtras) {
            foreach ($horasExtras as $he) {
                $total_horas_extras += floatval($he['cantidad'] ?? 0);
                $total_valor_extras += floatval($he['valor'] ?? 0);
            }
        }
        
        $this->view('empleado/detalle', [
            'empleado' => $empleado,
            'horasExtras' => $horasExtras,
            'total_horas_extras' => $total_horas_extras,
            'total_valor_extras' => $total_valor_extras
        ]);
    }
}
