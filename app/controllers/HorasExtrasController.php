<?php
namespace App\Controllers;
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/RolePermissions.php';
use App\Controllers\Controller;
use App\Models\RolePermissions;

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
        // Verificar permisos
        if (!RolePermissions::canAccess('horas_extras', 'read') && !RolePermissions::canAccess('horas_extras', 'read_own')) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        $empleadoModel = $this->model('Empleado');
        $horasExtrasModel = $this->model('HorasExtras');
        // Obtener empleados según el rol
        if (RolePermissions::getCurrentUserRole() === 'empleado') {
            $currentEmployeeId = RolePermissions::getCurrentEmployeeId();
            if ($currentEmployeeId) {
                $empleado = $empleadoModel->find($currentEmployeeId);
                $empleados = $empleado ? [$empleado] : [];
            } else {
                $empleados = [];
            }
        } else {
            $empleados = $empleadoModel->getAllWithRoles();
        }
        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'empleado'],
            ['nombre' => 'rrhh']
        ];
        if (!is_array($empleados)) {
            $empleados = [];
        }
        $tipos_disponibles = [
            'Extra diurna',
            'Extra nocturna', 
            'Extra diurna dominical/festiva',
            'Extra nocturna dominical/festiva'
        ];
        foreach ($empleados as &$empleado) {
            $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado['id_empleados']);
            $empleado['total_horas'] = 0;
            $empleado['total_valor'] = 0;
            $tipos = [];
            foreach ($horasExtras as $he) {
                if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                    $empleado['total_horas'] += floatval($he['cantidad']);
                    $empleado['total_valor'] += floatval($he['valor']);
                    $tipos[] = $he['tipo'];
                }
            }
            // Tipo de horas más frecuente (solo de las válidas)
            $empleado['tipo_frecuente'] = !empty($tipos) ? array_keys(array_filter(array_count_values($tipos), function($v) use ($tipos) { return $v == max(array_count_values($tipos)); }))[0] : 'N/A';
            $empleado['rol'] = $empleado['rol_nombre'] ?? 'Sin rol';
            $empleado['canDelete'] = RolePermissions::hasPermission($_SESSION['user']['rol'], 'horas_extras', 'delete');
        }
        $filtro_rol = isset($_GET['filtro_rol']) ? $_GET['filtro_rol'] : '';
        $filtro_horas = isset($_GET['filtro_horas']) ? $_GET['filtro_horas'] : '';
        $filtro_tipo = isset($_GET['filtro_tipo']) ? $_GET['filtro_tipo'] : '';
        $buscar_empleado = isset($_GET['buscar_empleado']) ? trim($_GET['buscar_empleado']) : '';
        $empleados_filtrados = $empleados;
        if ($buscar_empleado) {
            $empleados_filtrados = array_filter($empleados_filtrados, function($emp) use ($buscar_empleado) {
                $nombre_completo = strtolower($emp['nombre'] . ' ' . $emp['apellido']);
                $busqueda = strtolower($buscar_empleado);
                return strpos($nombre_completo, $busqueda) !== false;
            });
        }
        if ($filtro_rol) {
            $empleados_filtrados = array_filter($empleados_filtrados, function($emp) use ($filtro_rol) {
                if ($filtro_rol == 'empleado') {
                    return true;
                } else {
                    return $emp['rol'] == $filtro_rol || 
                           (isset($emp['todos_los_roles']) && strpos($emp['todos_los_roles'], $filtro_rol) !== false);
                }
            });
        }
        if ($filtro_horas) {
            $empleados_filtrados = array_filter($empleados_filtrados, function($emp) use ($filtro_horas) {
                $horas = $emp['total_horas'];
                switch ($filtro_horas) {
                    case '0': return $horas == 0;
                    case '1-10': return $horas >= 1 && $horas <= 10;
                    case '11-20': return $horas >= 11 && $horas <= 20;
                    case '21-40': return $horas >= 21 && $horas <= 40;
                    case '40+': return $horas > 40;
                    default: return true;
                }
            });
        }
        if ($filtro_tipo) {
            $empleados_filtrados = array_filter($empleados_filtrados, function($emp) use ($filtro_tipo) {
                return $emp['tipo_frecuente'] == $filtro_tipo;
            });
        }
        // Permisos globales para la vista
        $canAccessAllEmployees = RolePermissions::canAccessAllEmployees('horas_extras');
        $canApprove = RolePermissions::canAccess('horas_extras', 'approve');
        $pendingCount = method_exists('App\\Models\\RolePermissions', 'getPendingHoursCount') ? RolePermissions::getPendingHoursCount() : 0;
        $this->view('horas_extras/index', [
            'empleados' => $empleados_filtrados, 
            'roles' => $roles,
            'filtro_rol' => $filtro_rol,
            'tipos_disponibles' => $tipos_disponibles,
            'canAccessAllEmployees' => $canAccessAllEmployees,
            'canApprove' => $canApprove,
            'pendingCount' => $pendingCount
        ]);
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos de creación
        RolePermissions::redirectIfNoPermission('horas_extras', 'create');
        
        $empleadoModel = $this->model('Empleado');
        
        // Obtener empleados según el rol
        if (RolePermissions::canAccessAllEmployees('horas_extras')) {
            // Admin y RRHH pueden crear horas extras para cualquier empleado
            $empleados = $empleadoModel->getAllWithRoles();
        } else {
            // Empleados solo pueden crear horas extras para sí mismos
            $currentEmployeeId = RolePermissions::getCurrentEmployeeId();
            if ($currentEmployeeId) {
                $empleado = $empleadoModel->find($currentEmployeeId);
                $empleados = $empleado ? [$empleado] : [];
            } else {
                $empleados = [];
            }
        }
        
        // Cargar tipos de horas extras disponibles
        $tipoModel = $this->model('TipoHoraExtra');
        $tipos_disponibles = $tipoModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $empleado_id = intval($_POST['empleado_id']);
                
                // Validar que el empleado puede crear horas extras para el empleado seleccionado
                if (!RolePermissions::canAccessAllEmployees('horas_extras')) {
                    $currentEmployeeId = RolePermissions::getCurrentEmployeeId();
                    if ($empleado_id !== $currentEmployeeId) {
                        throw new \Exception('No tiene permisos para crear horas extras para este empleado.');
                    }
                }
                
                $data = [
                    'empleado_id' => $empleado_id,
                    'cantidad' => $_POST['cantidad'],
                    'tipo' => $_POST['tipo'],
                    'dia' => $_POST['dia'],
                    'mes' => $_POST['mes'],
                    'anio' => $_POST['anio']
                    // valor y porcentaje se calculan automáticamente en el modelo
                ];
                
                $horasExtrasModel = $this->model('HorasExtras');
                $resultado = $horasExtrasModel->create($data);
                
                if ($resultado) {
                    // Éxito - redirigir a la lista
                    header('Location: /ZIGMA/public/index.php?url=HorasExtras');
                    exit;
                } else {
                    // Error en la creación
                    $error = "Error al crear las horas extras. Verifique los datos e intente nuevamente.";
                }
            } catch (\Exception $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
        $this->view('horas_extras/create', [
            'empleados' => $empleados,
            'tipos_disponibles' => $tipos_disponibles,
            'error' => isset($error) ? $error : null
        ]);
    }

    public function edit($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        
        // Cargar tipos de horas extras disponibles
        $tipoModel = $this->model('TipoHoraExtra');
        $tipos_disponibles = $tipoModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'empleado_id' => $_POST['empleado_id'],
                'cantidad' => $_POST['cantidad'],
                'tipo' => $_POST['tipo'],
                'dia' => $_POST['dia'],
                'mes' => $_POST['mes'],
                'anio' => $_POST['anio']
                // valor y porcentaje se calculan automáticamente en el modelo
            ];
            $horasExtrasModel->update($id, $data);
            header('Location: /ZIGMA/public/index.php?url=HorasExtras');
            exit;
        }
        $hora = $horasExtrasModel->find($id);
        $this->view('horas_extras/edit', [
            'hora' => $hora, 
            'empleados' => $empleados,
            'tipos_disponibles' => $tipos_disponibles
        ]);
    }

    public function detalle($empleado_id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        $empleadoModel = $this->model('Empleado');
        $horasExtrasModel = $this->model('HorasExtras');
        
        $empleado = $empleadoModel->find($empleado_id);
        $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado_id);
        
        // Calcular totales
        $total_horas = 0;
        $total_valor = 0;
        foreach ($horasExtras as $he) {
            if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                $total_horas += floatval($he['cantidad']);
                $total_valor += floatval($he['valor']);
            }
        }
        
        $hasDeletePermission = false;
        if (isset($_SESSION['user']['rol'])) {
            $hasDeletePermission = \App\Models\RolePermissions::hasPermission($_SESSION['user']['rol'], 'horas_extras', 'delete');
        }
        $this->view('horas_extras/detalle', [
            'empleado' => $empleado,
            'horasExtras' => $horasExtras,
            'total_horas' => $total_horas,
            'total_valor' => $total_valor,
            'hasDeletePermission' => $hasDeletePermission
        ]);
    }

    public function delete($id = null) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        if ($id === null) {
            // Si no se recibe el ID, mostrar error y redirigir
            $_SESSION['error'] = 'No se recibió el ID de la hora extra a eliminar.';
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $horasExtrasModel->delete($id);
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras');
        exit;
    }
    
    /**
     * Ver horas extras pendientes de aprobación
     */
    public function pendientes() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos de aprobación
        RolePermissions::redirectIfNoPermission('horas_extras', 'approve');
        
        $horasExtrasModel = $this->model('HorasExtras');
        $pendientes = [];
        // Si el usuario es empleado, filtrar por su propio empleado_id
        if (RolePermissions::getCurrentUserRole() === 'empleado') {
            $empleadoId = RolePermissions::getCurrentEmployeeId();
            if ($empleadoId) {
                $pendientes = $horasExtrasModel->getPendientes($empleadoId);
            }
        } else {
            $pendientes = $horasExtrasModel->getPendientes();
        }
        $this->view('horas_extras/pendientes', [
            'pendientes' => $pendientes,
            'title' => 'Horas Extras Pendientes de Aprobación'
        ]);
    }
    
    /**
     * Aprobar horas extras
     */
    public function aprobar() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos de aprobación
        RolePermissions::redirectIfNoPermission('horas_extras', 'approve');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $comentario = $_POST['comentario'] ?? null;
            $aprobado_por = $_SESSION['user']['id_doc'] ?? null;
            if ($aprobado_por) {
                $horasExtrasModel = $this->model('HorasExtras');
                $resultado = $horasExtrasModel->aprobar($id, $aprobado_por, $comentario);
                // Registrar notificación para el empleado
                $he = $horasExtrasModel->find($id);
                if ($he && isset($he['empleado_id'])) {
                    $horasExtrasModel->registrarNotificacionHorasExtras($he['empleado_id'], 'aprobada');
                }
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&success=aprobada');
                } else {
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&error=1');
                }
            } else {
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&error=usuario');
            }
        } else {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes');
        }
        exit;
    }
    
    /**
     * Rechazar horas extras
     */
    public function rechazar() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos de rechazo
        RolePermissions::redirectIfNoPermission('horas_extras', 'reject');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $comentario = $_POST['comentario'] ?? null;
            $aprobado_por = $_SESSION['user']['id_doc'] ?? null;
            if ($aprobado_por) {
                $horasExtrasModel = $this->model('HorasExtras');
                $resultado = $horasExtrasModel->rechazar($id, $aprobado_por, $comentario);
                // Registrar notificación para el empleado
                $he = $horasExtrasModel->find($id);
                if ($he && isset($he['empleado_id'])) {
                    $horasExtrasModel->registrarNotificacionHorasExtras($he['empleado_id'], 'rechazada', $comentario);
                }
                
                if ($resultado) {
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&success=rechazada');
                } else {
                    header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&error=1');
                }
            } else {
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes&error=usuario');
            }
        } else {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras/pendientes');
        }
        exit;
    }
    
    /**
     * Ver historial de horas extras con información de aprobación
     */
    public function historial($empleado_id = null) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        // Verificar permisos
        if (!RolePermissions::canAccess('horas_extras', 'read') && !RolePermissions::canAccess('horas_extras', 'read_own')) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        
        $empleadoModel = $this->model('Empleado');
        $horasExtrasModel = $this->model('HorasExtras');
        
        // Si no se especifica empleado y es un empleado general, usar su propio ID
        if (!$empleado_id && RolePermissions::getCurrentUserRole() === 'empleado') {
            $empleado_id = RolePermissions::getCurrentEmployeeId();
        }
        
        // Verificar permisos de acceso al empleado específico
        if ($empleado_id && !RolePermissions::canAccessEmployee($empleado_id)) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=dashboard&error=no_permission');
            exit;
        }
        
        if ($empleado_id) {
            // Mostrar historial de un empleado específico
            $empleado = $empleadoModel->find($empleado_id);
            $historial = $horasExtrasModel->getByEmpleadoConAprobacion($empleado_id);
        } else {
            // Mostrar todos los empleados (solo para admin/RRHH)
            if (!RolePermissions::canAccessAllEmployees('horas_extras')) {
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=dashboard&error=no_permission');
                exit;
            }
            $empleado = null;
            $historial = $horasExtrasModel->getAllConAprobacion();
        }
        
        $this->view('horas_extras/historial', [
            'empleado' => $empleado,
            'historial' => $historial,
            'title' => $empleado ? 'Historial de Horas Extras - ' . $empleado['nombre'] . ' ' . $empleado['apellido'] : 'Historial de Todas las Horas Extras'
        ]);
    }
}
