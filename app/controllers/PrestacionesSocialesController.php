<?php

class PrestacionesSocialesController extends Controller {
    
    private function baseUrl() {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $base = exploded('/public', $scriptName)[0];
        return $base;
    }
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }

        try {
            $prestacionesModel = $this->model('PrestacionesSocialesModel');
            $diasTrabajados = isset($_GET['dias_trabajados']) ? intval($_GET['dias_trabajados']) : 360;

            // Filtrar por empleado si el usuario es 'empleado'
            $user = $_SESSION['user'];
            $rol = isset($user['rol']) ? strtolower($user['rol']) : '';
            $idEmpleado = isset($user['empleado_id']) ? $user['empleado_id'] : null;
            if ($rol === 'empleado' && $idEmpleado) {
                // Solo mostrar el cálculo para el empleado actual
                $calculo = $prestacionesModel->calcularPrestacionesCompletas($idEmpleado);
                // Construir totales_empresa con las mismas claves que el caso múltiple
                $totales_empresa = [
                    'cesantias' => $calculo['prestaciones']['cesantias']['valor_cesantias'],
                    'intereses' => $calculo['prestaciones']['intereses_cesantias']['valor_intereses'],
                    'prima' => $calculo['prestaciones']['prima_servicios']['valor_prima'],
                    'vacaciones' => $calculo['prestaciones']['vacaciones']['valor_vacaciones'],
                    'total_general' => $calculo['resumen']['total_prestaciones'],
                    'dias_trabajados' => $diasTrabajados
                ];
                $this->view('prestaciones_sociales/index', [
                    'title' => 'Prestaciones Sociales',
                    'calculos_empleados' => [$calculo],
                    'totales_empresa' => $totales_empresa,
                    'promedios' => [],
                    'total_empleados' => 1,
                    'dias_trabajados' => $diasTrabajados
                ]);
                return;
            }

            // Calcular prestaciones para todos los empleados (admin/rrhh)
            $calculoCompleto = $prestacionesModel->calcularPrestacionesTodosEmpleados($diasTrabajados);

            $this->view('prestaciones_sociales/index', [
                'title' => 'Prestaciones Sociales',
                'calculos_empleados' => $calculoCompleto['empleados'],
                'totales_empresa' => $calculoCompleto['totales_empresa'],
                'promedios' => $calculoCompleto['promedios'],
                'total_empleados' => $calculoCompleto['total_empleados'],
                'dias_trabajados' => $diasTrabajados
            ]);

        } catch (Exception $e) {
            $this->view('prestaciones_sociales/index', [
                'title' => 'Prestaciones Sociales',
                'error' => 'Error al calcular prestaciones sociales: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'totales_empresa' => [],
                'promedios' => [],
                'total_empleados' => 0,
                'dias_trabajados' => 360
            ]);
        }
    }
    
    public function detalle($idEmpleado) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $prestacionesModel = $this->model('PrestacionesSocialesModel');
            
            $calculo = $prestacionesModel->calcularPrestacionesCompletas($idEmpleado);
            
            $this->view('prestaciones_sociales/detalle', [
                'title' => 'Detalle de Prestaciones Sociales',
                'calculo' => $calculo,
                'success' => 'Cálculo realizado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('prestaciones_sociales/detalle', [
                'title' => 'Detalle de Prestaciones Sociales',
                'error' => 'Error al calcular prestaciones sociales: ' . $e->getMessage(),
                'calculo' => null
            ]);
        }
    }
    
    public function calcular() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $prestacionesModel = $this->model('PrestacionesSocialesModel');
                $idEmpleado = intval($_POST['empleado_id'] ?? 0);
                
                if ($idEmpleado <= 0) {
                    throw new InvalidArgumentException('Debe seleccionar un empleado válido');
                }
                
                $calculo = $prestacionesModel->calcularPrestacionesCompletas($idEmpleado);
                
                // Guardar el cálculo si se solicita
                if (isset($_POST['guardar']) && $_POST['guardar'] === '1') {
                    $prestacionesId = $prestacionesModel->guardarPrestaciones($calculo);
                    $mensaje = 'Prestaciones calculadas y guardadas correctamente (ID: ' . $prestacionesId . ')';
                } else {
                    $mensaje = 'Prestaciones calculadas correctamente';
                }
                
                $this->view('prestaciones_sociales/resultado', [
                    'title' => 'Resultado de Prestaciones Sociales',
                    'calculo' => $calculo,
                    'success' => $mensaje
                ]);
                
            } catch (Exception $e) {
                $empleadoModel = $this->model('Empleado');
                $empleados = $empleadoModel->getAll();
                
                $this->view('prestaciones_sociales/calcular', [
                    'title' => 'Calcular Prestaciones Sociales',
                    'empleados' => $empleados,
                    'error' => 'Error: ' . $e->getMessage()
                ]);
            }
        } else {
            // Mostrar formulario
            $empleadoModel = $this->model('Empleado');
            $empleados = $empleadoModel->getAll();
            
            $this->view('prestaciones_sociales/calcular', [
                'title' => 'Calcular Prestaciones Sociales',
                'empleados' => $empleados
            ]);
        }
    }
    
    public function reporteAnual() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $prestacionesModel = $this->model('PrestacionesSocialesModel');
            $anio = isset($_GET['anio']) ? intval($_GET['anio']) : date('Y');
            
            // Cálculo mensual para todos los empleados
            $calculoAnual = $prestacionesModel->calcularPrestacionesTodosEmpleados();
            
            $this->view('prestaciones_sociales/reporte_anual', [
                'title' => 'Reporte Anual de Prestaciones Sociales ' . $anio,
                'anio' => $anio,
                'calculo_anual' => $calculoAnual,
                'fecha_reporte' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            $this->view('prestaciones_sociales/reporte_anual', [
                'title' => 'Reporte Anual de Prestaciones Sociales',
                'error' => 'Error al generar el reporte: ' . $e->getMessage(),
                'anio' => date('Y'),
                'calculo_anual' => null,
                'fecha_reporte' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function configuracion() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        $this->view('prestaciones_sociales/configuracion', [
            'title' => 'Configuración de Prestaciones Sociales',
            'parametros' => [
                'salario_minimo' => PrestacionesSocialesModel::SALARIO_MINIMO,
                'dias_laborales_anio' => PrestacionesSocialesModel::DIAS_LABORALES_ANIO,
                'interes_cesantias' => PrestacionesSocialesModel::INTERES_CESANTIAS * 100
            ],
            'formulas' => [
                'cesantias' => '(Salario + Auxilio Transporte) × Días Trabajados ÷ 360',
                'intereses' => 'Cesantías × 12% × (Días Trabajados ÷ 360)',
                'prima' => '(Salario + Auxilio Transporte) × Días Trabajados ÷ 360',
                'vacaciones' => 'Salario × Días Trabajados ÷ 720 (sin auxilio de transporte)'
            ]
        ]);
    }
}