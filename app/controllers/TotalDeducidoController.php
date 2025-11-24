<?php
namespace App\Controllers;
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/TotalDeducidoModel.php';
require_once __DIR__ . '/../models/ConceptosAdicionalesDeduciblesModel.php';
use App\Controllers\Controller;

class TotalDeducidoController extends Controller {
    
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
        
        try {
            $deducidoModel = $this->model('TotalDeducidoModel');
            
            // Calcular total deducido para todos los empleados
            $calculoCompleto = $deducidoModel->calcularTotalDeducidoTodosEmpleados();
            
            $this->view('total_deducido/index', [
                'title' => 'Total Deducido - Nómina',
                'calculos_empleados' => $calculoCompleto['empleados'],
                'totales_empresa' => $calculoCompleto['totales_empresa'],
                'promedios' => $calculoCompleto['promedios'],
                'estadisticas' => $calculoCompleto['estadisticas'],
                'porcentajes_empresa' => $calculoCompleto['porcentajes_empresa'],
                'total_empleados' => $calculoCompleto['total_empleados']
            ]);
            
        } catch (\Exception $e) {
            $this->view('total_deducido/index', [
                'title' => 'Total Deducido - Nómina',
                'error' => 'Error al calcular el total deducido: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'totales_empresa' => [],
                'promedios' => [],
                'estadisticas' => [],
                'porcentajes_empresa' => [],
                'total_empleados' => 0
            ]);
        }
    }
    
    public function detalle($idEmpleado) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $deducidoModel = $this->model('TotalDeducidoModel');
            $calculo = $deducidoModel->calcularTotalDeducidoCompleto($idEmpleado);
            
            // Restructurar datos para la vista
            $empleado = $calculo['empleado'];
            $datos = [
                'total_devengado' => $calculo['base_calculo']['total_devengado'],
                'total_deducido' => $calculo['resumen']['total_deducciones'],
                'base_calculo' => $calculo['base_calculo']['base_final'],
                'salud' => $calculo['deducciones']['salud_empleado']['valor'],
                'pension' => $calculo['deducciones']['pension_empleado']['valor'],
                'fondo_solidaridad' => $calculo['deducciones']['fondo_solidaridad']['valor'],
                'porcentaje_fondo' => $calculo['deducciones']['fondo_solidaridad']['porcentaje'],
                'retencion_fuente' => $calculo['deducciones']['retencion_fuente']['valor'] ?? 0,
                'conceptos_adicionales' => [
                    'total' => $calculo['deducciones']['otros_deducibles']['valor'],
                    'cantidad' => count($calculo['deducciones']['otros_deducibles']['detalle']),
                    'conceptos' => $calculo['deducciones']['otros_deducibles']['detalle']
                ]
            ];
            
            $this->view('total_deducido/detalle', [
                'title' => 'Detalle Deducido - ' . $empleado['nombre'] . ' ' . $empleado['apellido'],
                'empleado' => $empleado,
                'datos' => $datos,
                'success' => 'Cálculo realizado correctamente'
            ]);
            
        } catch (\Exception $e) {
            $this->view('total_deducido/detalle', [
                'title' => 'Detalle Total Deducido',
                'error' => 'Error al calcular el total deducido: ' . $e->getMessage(),
                'empleado' => [
                    'nombre' => 'Error', 
                    'apellido' => '',
                    'documento' => 'N/A', 
                    'cargo' => 'N/A', 
                    'id' => 0
                ],
                'datos' => [
                    'total_devengado' => 0,
                    'total_deducido' => 0,
                    'base_calculo' => 0,
                    'salud' => 0,
                    'pension' => 0,
                    'fondo_solidaridad' => 0,
                    'porcentaje_fondo' => 0,
                    'retencion_fuente' => 0,
                    'conceptos_adicionales' => ['total' => 0, 'cantidad' => 0, 'conceptos' => []]
                ]
            ]);
        }
    }
    
    public function generar() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $deducidoModel = $this->model('TotalDeducidoModel');
                $idEmpleado = intval($_POST['empleado_id'] ?? 0);
                
                if ($idEmpleado <= 0) {
                    throw new \InvalidArgumentException('Debe seleccionar un empleado válido');
                }
                
                $calculo = $deducidoModel->calcularTotalDeducidoCompleto($idEmpleado);
                
                // Guardar el cálculo si se solicita
                if (isset($_POST['guardar']) && $_POST['guardar'] === '1') {
                    $deducidoId = $deducidoModel->guardarTotalDeducido($calculo);
                    $mensaje = 'Total deducido calculado y guardado correctamente (ID: ' . $deducidoId . ')';
                } else {
                    $mensaje = 'Total deducido calculado correctamente';
                }
                
                $this->view('total_deducido/resultado', [
                    'title' => 'Resultado Total Deducido',
                    'calculo' => $calculo,
                    'success' => $mensaje
                ]);
                
            } catch (\Exception $e) {
                $empleadoModel = $this->model('Empleado');
                $empleados = $empleadoModel->getAllWithRoles();
                
                $this->view('total_deducido/generar', [
                    'title' => 'Generar Total Deducido',
                    'empleados' => $empleados,
                    'error' => 'Error: ' . $e->getMessage()
                ]);
            }
        } else {
            // Mostrar formulario
            $empleadoModel = $this->model('Empleado');
            // Obtener empleados válidos (excluye roles)
            $empleados = $empleadoModel->getAllWithRoles();
            
            $this->view('total_deducido/generar', [
                'title' => 'Generar Total Deducido',
                'empleados' => $empleados
            ]);
        }
    }
    
    public function resumen() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $deducidoModel = $this->model('TotalDeducidoModel');
            $calculoCompleto = $deducidoModel->calcularTotalDeducidoTodosEmpleados();
            
            $this->view('total_deducido/resumen', [
                'title' => 'Resumen General Total Deducido',
                'totales_empresa' => $calculoCompleto['totales_empresa'],
                'promedios' => $calculoCompleto['promedios'],
                'estadisticas' => $calculoCompleto['estadisticas'],
                'porcentajes_empresa' => $calculoCompleto['porcentajes_empresa'],
                'total_empleados' => $calculoCompleto['total_empleados']
            ]);
            
        } catch (\Exception $e) {
            $this->view('total_deducido/resumen', [
                'title' => 'Resumen General Total Deducido',
                'error' => 'Error al generar el resumen: ' . $e->getMessage(),
                'totales_empresa' => [],
                'promedios' => [],
                'estadisticas' => [],
                'porcentajes_empresa' => [],
                'total_empleados' => 0
            ]);
        }
    }
    
    /**
     * Agregar concepto deducible via AJAX (Otros)
     */
    public function agregarConcepto() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        
        try {
            $empleado_id = $_POST['empleado_id'] ?? null;
            $concepto = $_POST['concepto'] ?? null;
            $descripcion = $_POST['descripcion'] ?? '';
            $valor = $_POST['valor'] ?? null;
            
            if (!$empleado_id || !$concepto || !$valor) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
                exit;
            }
            
            $conceptosModel = $this->model('ConceptosAdicionalesDeduciblesModel');
            $resultado = $conceptosModel->agregarConcepto(
                $empleado_id,
                $concepto,
                $descripcion,
                floatval($valor),
                $_SESSION['user']['nombre'] ?? 'Sistema'
            );
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Concepto deducible agregado exitosamente', 'id' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el concepto deducible']);
            }
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Obtener conceptos deducibles de un empleado via AJAX
     */
    public function obtenerConceptos($empleado_id = null) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || !$empleado_id) {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        
        try {
            $conceptosModel = $this->model('ConceptosAdicionalesDeduciblesModel');
            $resumen = $conceptosModel->obtenerResumenConceptos($empleado_id);
            
            echo json_encode([
                'success' => true, 
                'conceptos' => $resumen['conceptos'],
                'total' => $resumen['total'],
                'cantidad' => $resumen['cantidad']
            ]);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Eliminar concepto deducible via AJAX
     */
    public function eliminarConcepto($id = null) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'DELETE' || !$id) {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        
        try {
            $conceptosModel = $this->model('ConceptosAdicionalesDeduciblesModel');
            $resultado = $conceptosModel->eliminarConcepto($id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Concepto deducible eliminado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el concepto deducible']);
            }
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
}