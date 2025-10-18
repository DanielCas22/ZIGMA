<?php
require_once 'Controller.php';

class ParafiscalesController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $parafiscalesModel = $this->model('ParafiscalesModel');
            $calculoCompleto = $parafiscalesModel->calcularParafiscalesGeneral();
            
            $this->view('parafiscales/index', [
                'title' => 'Parafiscales - Nómina',
                'calculos_empleados' => $calculoCompleto['calculos_empleados'],
                'totales_empresa' => $calculoCompleto['totales_empresa'],
                'promedios' => $calculoCompleto['promedios'],
                'estadisticas' => $calculoCompleto['estadisticas'],
                'total_empleados' => $calculoCompleto['total_empleados'],
                'success' => 'Cálculo de parafiscales realizado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('parafiscales/index', [
                'title' => 'Parafiscales - Nómina',
                'error' => 'Error al calcular parafiscales: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'totales_empresa' => [],
                'promedios' => [],
                'estadisticas' => [],
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
            $parafiscalesModel = $this->model('ParafiscalesModel');
            $calculo = $parafiscalesModel->calcularParafiscalesCompleto($idEmpleado);
            
            // Restructurar datos para la vista
            $empleado = $calculo['empleado'];
            $datos = [
                'base_calculo' => $calculo['base_calculo']['total_devengado'],
                'sena' => $calculo['parafiscales']['sena']['valor'],
                'icbf' => $calculo['parafiscales']['icbf']['valor'],
                'caja_compensacion' => $calculo['parafiscales']['caja_compensacion']['valor'],
                'total_parafiscales' => $calculo['resumen']['total_parafiscales'],
                'porcentajes' => [
                    'sena' => $calculo['parafiscales']['sena']['porcentaje'],
                    'icbf' => $calculo['parafiscales']['icbf']['porcentaje'],
                    'caja_compensacion' => $calculo['parafiscales']['caja_compensacion']['porcentaje'],
                    'total' => $calculo['resumen']['porcentaje_total']
                ],
                'detalles' => $calculo['parafiscales']
            ];
            
            $this->view('parafiscales/detalle', [
                'title' => 'Detalle Parafiscales - ' . $empleado['nombre'] . ' ' . $empleado['apellido'],
                'empleado' => $empleado,
                'datos' => $datos,
                'informacion_legal' => $calculo['informacion_legal'],
                'success' => 'Cálculo realizado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('parafiscales/detalle', [
                'title' => 'Detalle Parafiscales',
                'error' => 'Error al calcular parafiscales: ' . $e->getMessage(),
                'empleado' => [
                    'nombre' => 'Error', 
                    'apellido' => '',
                    'documento' => 'N/A', 
                    'cargo' => 'N/A', 
                    'id' => 0
                ],
                'datos' => [
                    'base_calculo' => 0,
                    'sena' => 0,
                    'icbf' => 0,
                    'caja_compensacion' => 0,
                    'total_parafiscales' => 0,
                    'porcentajes' => ['sena' => 0, 'icbf' => 0, 'caja_compensacion' => 0, 'total' => 0],
                    'detalles' => []
                ],
                'informacion_legal' => []
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
                $parafiscalesModel = $this->model('ParafiscalesModel');
                $idEmpleado = intval($_POST['empleado_id'] ?? 0);
                
                if ($idEmpleado <= 0) {
                    throw new InvalidArgumentException('Debe seleccionar un empleado válido');
                }
                
                $calculo = $parafiscalesModel->calcularParafiscalesCompleto($idEmpleado);
                
                // Guardar el cálculo si se solicita
                if (isset($_POST['guardar']) && $_POST['guardar'] === '1') {
                    $parafiscalesId = $parafiscalesModel->guardarParafiscales($calculo);
                    $mensaje = 'Parafiscales calculados y guardados correctamente (ID: ' . $parafiscalesId . ')';
                } else {
                    $mensaje = 'Parafiscales calculados correctamente';
                }
                
                $this->view('parafiscales/resultado', [
                    'title' => 'Resultado Parafiscales',
                    'calculo' => $calculo,
                    'success' => $mensaje
                ]);
                
            } catch (Exception $e) {
                $empleadoModel = $this->model('Empleado');
                // Obtener empleados válidos (excluye roles)
                $empleados = $empleadoModel->getValidEmployees();
                
                $this->view('parafiscales/generar', [
                    'title' => 'Generar Parafiscales',
                    'empleados' => $empleados,
                    'error' => 'Error al calcular parafiscales: ' . $e->getMessage()
                ]);
            }
            
        } else {
            // Mostrar formulario
            $empleadoModel = $this->model('Empleado');
            // Obtener empleados válidos (excluye roles)
            $empleados = $empleadoModel->getValidEmployees();
            
            $this->view('parafiscales/generar', [
                'title' => 'Generar Parafiscales',
                'empleados' => $empleados
            ]);
        }
    }
    
    public function historico() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $parafiscalesModel = $this->model('ParafiscalesModel');
            $historico = $parafiscalesModel->obtenerHistorico();
            
            $this->view('parafiscales/historico', [
                'title' => 'Histórico Parafiscales',
                'historico' => $historico,
                'success' => 'Histórico cargado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('parafiscales/historico', [
                'title' => 'Histórico Parafiscales',
                'error' => 'Error al cargar histórico: ' . $e->getMessage(),
                'historico' => []
            ]);
        }
    }
}
?>