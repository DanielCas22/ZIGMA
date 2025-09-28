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
        
        $empleadoModel = $this->model('Empleado');
        $horasExtrasModel = $this->model('HorasExtras');
        
        $empleados = $empleadoModel->getAllWithRoles();
        
        // Roles específicos para filtrar
        $roles = [
            ['nombre' => 'admin'],
            ['nombre' => 'empleado'],
            ['nombre' => 'rrhh']
        ];
        
        // Validar que tengamos arrays válidos
        if (!is_array($empleados)) {
            $empleados = [];
        }
        
        // Tipos de horas extras predefinidos
        $tipos_disponibles = [
            'Extra diurna',
            'Extra nocturna', 
            'Extra diurna dominical/festiva',
            'Extra nocturna dominical/festiva'
        ];
        
        // Para cada empleado, obtener sus horas extras
        foreach ($empleados as &$empleado) {
            $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado['id_empleados']);
            // Obtener la suma total de horas, valor total y el tipo más frecuente
            $empleado['total_horas'] = 0;
            $empleado['total_valor'] = 0;
            $tipos = [];
            foreach ($horasExtras as $he) {
                $empleado['total_horas'] += floatval($he['cantidad']);
                $empleado['total_valor'] += floatval($he['valor']);
                $tipos[] = $he['tipo'];
            }
            $empleado['tipo_frecuente'] = !empty($tipos) ? array_count_values($tipos) : [];
            $empleado['tipo_frecuente'] = !empty($empleado['tipo_frecuente']) ? array_keys($empleado['tipo_frecuente'], max($empleado['tipo_frecuente']))[0] : 'N/A';
            
            // Asignar rol (ya viene del query)
            $empleado['rol'] = $empleado['rol_nombre'];
        }
        
        // Aplicar filtros
        $filtro_rol = isset($_GET['filtro_rol']) ? $_GET['filtro_rol'] : '';
        $filtro_horas = isset($_GET['filtro_horas']) ? $_GET['filtro_horas'] : '';
        $filtro_tipo = isset($_GET['filtro_tipo']) ? $_GET['filtro_tipo'] : '';
        $buscar_empleado = isset($_GET['buscar_empleado']) ? trim($_GET['buscar_empleado']) : '';
        
        // Filtrar empleados según los criterios
        $empleados_filtrados = $empleados;
        
        // Filtrar por nombre de empleado (búsqueda)
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
                    // Si filtra por empleado, mostrar todos (ya que todos tienen rol empleado)
                    return true;
                } else {
                    // Para admin o rrhh, verificar tanto el rol principal como todos los roles
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
        
        $this->view('horas_extras/index', [
            'empleados' => $empleados_filtrados, 
            'roles' => $roles,
            'filtro_rol' => $filtro_rol,
            'tipos_disponibles' => $tipos_disponibles
        ]);
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        
        // Cargar tipos de horas extras disponibles
        $tipoModel = $this->model('TipoHoraExtra');
        $tipos_disponibles = $tipoModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'empleado_id' => $_POST['empleado_id'],
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
            } catch (Exception $e) {
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
            $total_horas += floatval($he['cantidad']);
            $total_valor += floatval($he['valor']);
        }
        
        $this->view('horas_extras/detalle', [
            'empleado' => $empleado,
            'horasExtras' => $horasExtras,
            'total_horas' => $total_horas,
            'total_valor' => $total_valor
        ]);
    }

    public function delete($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $horasExtrasModel = $this->model('HorasExtras');
        $horasExtrasModel->delete($id);
        header('Location: ' . $this->baseUrl() . '/public/index.php?url=HorasExtras');
        exit;
    }
}
