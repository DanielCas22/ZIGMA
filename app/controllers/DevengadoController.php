<?php
namespace App\Controllers;

use App\Controllers\Controller;

require_once '../app/models/Empleado.php';
require_once '../app/models/HorasExtras.php';
require_once '../app/models/TotalDevengado.php';

class DevengadoController extends Controller {
    
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
            $devengadoModel = $this->model('DevengadoModel');
            // Iniciar sesión si no está iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $user = $_SESSION['user'] ?? null;
            $rol = $user['rol'] ?? null;
            $empleado_id = $user['empleado_id'] ?? null;
            if ($rol === 'empleado' && $empleado_id) {
                // Solo mostrar datos del empleado logueado
                $calculo = $devengadoModel->calcularDevengadoCompleto($empleado_id);
                $calculosEmpleados = [$calculo];
                $totalesEmpresa = [
                    'sueldo_basico' => $calculo['conceptos']['sueldo_basico']['valor'],
                    'horas_extras' => $calculo['conceptos']['horas_extras']['valor'],
                    'comisiones' => $calculo['conceptos']['comisiones']['valor'],
                    'auxilio_transporte' => $calculo['conceptos']['auxilio_transporte']['valor'],
                    'otros' => $calculo['conceptos']['otros']['valor'],
                    'total_general' => $calculo['resumen']['total_devengado']
                ];
                $promedios = [
                    'sueldo_basico' => $calculo['conceptos']['sueldo_basico']['valor'],
                    'horas_extras' => $calculo['conceptos']['horas_extras']['valor'],
                    'comisiones' => $calculo['conceptos']['comisiones']['valor'],
                    'auxilio_transporte' => $calculo['conceptos']['auxilio_transporte']['valor'],
                    'otros' => $calculo['conceptos']['otros']['valor']
                ];
                $total_empleados = 1;
            } else {
                $calculoCompleto = $devengadoModel->calcularDevengadoTodosEmpleados();
                $calculosEmpleados = $calculoCompleto['empleados'];
                $totalesEmpresa = $calculoCompleto['totales_empresa'];
                $promedios = $calculoCompleto['promedios'];
                $total_empleados = $calculoCompleto['total_empleados'];
            }
            $this->view('devengado/index', [
                'title' => 'Total Devengado - Nómina',
                'calculos_empleados' => $calculosEmpleados,
                'totales_empresa' => $totalesEmpresa,
                'promedios' => $promedios,
                'total_empleados' => $total_empleados,
                'currentRole' => $rol
            ]);
        } catch (\Exception $e) {
            $this->view('devengado/index', [
                'title' => 'Total Devengado - Nómina',
                'error' => 'Error al calcular el devengado: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'totales_empresa' => [],
                'promedios' => [],
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
            $devengadoModel = $this->model('DevengadoModel');
            $calculo = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
            
            $this->view('devengado/detalle', [
                'title' => 'Detalle Devengado - ' . $calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido'],
                'calculo' => $calculo,
                'success' => 'Cálculo realizado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('devengado/detalle', [
                'title' => 'Detalle Devengado',
                'error' => 'Error al calcular el devengado: ' . $e->getMessage(),
                'calculo' => null
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
                $devengadoModel = $this->model('DevengadoModel');
                $idEmpleado = intval($_POST['empleado_id'] ?? 0);
                
                if ($idEmpleado <= 0) {
                    throw new InvalidArgumentException('Debe seleccionar un empleado válido');
                }
                
                $calculo = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
                
                // Guardar el cálculo si se solicita
                if (isset($_POST['guardar']) && $_POST['guardar'] === '1') {
                    $devengadoId = $devengadoModel->guardarDevengado($calculo);
                    $mensaje = 'Devengado calculado y guardado correctamente (ID: ' . $devengadoId . ')';
                } else {
                    $mensaje = 'Devengado calculado correctamente';
                }
                
                $this->view('devengado/resultado', [
                    'title' => 'Resultado Devengado',
                    'calculo' => $calculo,
                    'success' => $mensaje
                ]);
                
            } catch (Exception $e) {
                $empleadoModel = $this->model('Empleado');
                // Obtener todos los empleados registrados, sin filtros
                $empleados = $empleadoModel->getAllWithRoles();
                
                $this->view('devengado/generar', [
                    'title' => 'Generar Devengado',
                    'empleados' => $empleados,
                    'error' => 'Error: ' . $e->getMessage()
                ]);
            }
        } else {
            // Mostrar formulario
            $empleadoModel = $this->model('Empleado');
            // Obtener todos los empleados registrados, sin filtros
            $empleados = $empleadoModel->getAllWithRoles();
            
            $this->view('devengado/generar', [
                'title' => 'Generar Devengado',
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
            $devengadoModel = $this->model('DevengadoModel');
            $calculoCompleto = $devengadoModel->calcularDevengadoTodosEmpleados();
            
            $this->view('devengado/resumen', [
                'title' => 'Resumen General Devengado',
                'totales_empresa' => $calculoCompleto['totales_empresa'],
                'promedios' => $calculoCompleto['promedios'],
                'total_empleados' => $calculoCompleto['total_empleados'],
                'detalle_conceptos' => $calculoCompleto['detalle_conceptos'] ?? []
            ]);
            
        } catch (Exception $e) {
            $this->view('devengado/resumen', [
                'title' => 'Resumen General Devengado',
                'error' => 'Error al generar el resumen: ' . $e->getMessage(),
                'totales_empresa' => [],
                'promedios' => [],
                'total_empleados' => 0,
                'detalle_conceptos' => []
            ]);
        }
    }
    
    /**
     * Agregar concepto adicional via AJAX
     */
    public function agregarConcepto() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        $user = $_SESSION['user'];
        if (($user['rol'] ?? null) === 'empleado') {
            echo json_encode(['success' => false, 'message' => 'No tiene permiso para agregar conceptos.']);
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
            
            $conceptosModel = $this->model('ConceptosAdicionalesModel');
            $resultado = $conceptosModel->agregarConcepto(
                $empleado_id,
                $concepto,
                $descripcion,
                floatval($valor),
                $_SESSION['user']['nombre'] ?? 'Sistema'
            );
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Concepto agregado exitosamente', 'id' => $resultado]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el concepto']);
            }
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Obtener conceptos de un empleado via AJAX
     */
    public function obtenerConceptos($empleado_id = null) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || !$empleado_id) {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        
        try {
            $conceptosModel = $this->model('ConceptosAdicionalesModel');
            $resumen = $conceptosModel->obtenerResumenConceptos($empleado_id);
            
            echo json_encode([
                'success' => true, 
                'conceptos' => $resumen['conceptos'],
                'total' => $resumen['total'],
                'cantidad' => $resumen['cantidad']
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Eliminar concepto adicional via AJAX
     */
    public function eliminarConcepto($id = null) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'DELETE' || !$id) {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }
        
        try {
            $conceptosModel = $this->model('ConceptosAdicionalesModel');
            $resultado = $conceptosModel->eliminarConcepto($id);
            
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Concepto eliminado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el concepto']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
        
        exit;
    }
}