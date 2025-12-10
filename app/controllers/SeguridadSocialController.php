<?php
namespace App\Controllers;
require_once __DIR__ . '/../../config/session_config.php';
require_once __DIR__ . '/Controller.php';
use App\Controllers\Controller;
use App\Models\SeguridadSocialModel;
use App\Models\ARLModel;
use App\Models\Empleado;
use App\Models\SalarioPorRol;
use App\Models\RolHasUser;
use App\Models\User;

/**
 * Controlador para manejo de cálculos de Seguridad Social
 */
class SeguridadSocialController extends Controller {
    
    private $seguridadSocialModel;
    private $arlModel;
    
    public function __construct() {
        $this->seguridadSocialModel = new \App\Models\SeguridadSocialModel();
        $this->arlModel = new \App\Models\ARLModel();
    }
    
    /**
     * Mostrar la vista principal de seguridad social
     */
    public function index() {
        try {
            $diasTrabajados = 30; // Valor por defecto
            $user = $_SESSION['user'] ?? null;
            $rol = $user['rol'] ?? null;
            $empleado_id = $user['empleado_id'] ?? null;
            if ($rol === 'empleado' && $empleado_id) {
                // Solo mostrar datos del empleado logueado
                $calculo = $this->seguridadSocialModel->calcularSeguridadSocialPorEmpleado($empleado_id, $diasTrabajados);
                $calculosEmpleados = [$calculo];
                $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotalConARL($calculosEmpleados);
            } else {
                // Mostrar todos los empleados (admin, rrhh)
                $calculosEmpleados = $this->seguridadSocialModel->calcularSeguridadSocialConARLTodosEmpleados($diasTrabajados);
                $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotalConARL($calculosEmpleados);
            }
            
            $data = [
                'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                'calculos_empleados' => $calculosEmpleados,
                'resumen_total' => $resumenTotal,
                'dias_trabajados' => $diasTrabajados,
                'success' => 'Cálculos de seguridad social + ARL generados correctamente'
            ];
            
            $this->view('seguridad_social/index', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                'error' => 'Error al calcular seguridad social: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'resumen_total' => [],
                'dias_trabajados' => 30
            ];
            
            $this->view('seguridad_social/index', $data);
        }
    }
    
    /**
     * Calcular seguridad social para un empleado específico
     */
    public function calcular($idEmpleado) {
        try {
            $diasTrabajados = isset($_POST['dias_trabajados']) ? intval($_POST['dias_trabajados']) : 30;
            
            $calculo = $this->seguridadSocialModel->calcularSeguridadSocialPorEmpleado($idEmpleado, $diasTrabajados);
            
            $data = [
                'title' => 'Detalle de Seguridad Social',
                'calculo' => $calculo,
                'success' => 'Cálculo realizado correctamente'
            ];
            
            $this->view('seguridad_social/detalle', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Detalle de Seguridad Social',
                'error' => 'Error al calcular seguridad social: ' . $e->getMessage(),
                'calculo' => null
            ];
            
            $this->view('seguridad_social/detalle', $data);
        }
    }
    
    /**
     * Calcular seguridad social completa (empleado + empleador)
     */
    public function calculoCompleto() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $totalDevengado = floatval($_POST['total_devengado'] ?? 0);
                
                if ($totalDevengado <= 0) {
                    throw new \InvalidArgumentException('El total devengado debe ser mayor a 0');
                }
                
                $calculoCompleto = $this->seguridadSocialModel->calcularSeguridadSocialCompleta($totalDevengado);
                
                $data = [
                    'title' => 'Cálculo Completo de Seguridad Social',
                    'calculo_completo' => $calculoCompleto,
                    'success' => 'Cálculo completo realizado correctamente'
                ];
                
            } else {
                $data = [
                    'title' => 'Cálculo Completo de Seguridad Social',
                    'calculo_completo' => null
                ];
            }
            
            $this->view('seguridad_social/completo', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo Completo de Seguridad Social',
                'error' => 'Error en el cálculo: ' . $e->getMessage(),
                'calculo_completo' => null
            ];
            
            $this->view('seguridad_social/completo', $data);
        }
    }
    
    /**
     * Generar reporte de seguridad social en formato JSON
     */
    public function reporteJson() {
        try {
            $calculosEmpleados = $this->seguridadSocialModel->calcularSeguridadSocialTodosEmpleados();
            $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotal($calculosEmpleados);
            
            $reporte = [
                'fecha_generacion' => date('Y-m-d H:i:s'),
                'calculos_empleados' => $calculosEmpleados,
                'resumen_total' => $resumenTotal
            ];
            
            header('Content-Type: application/json');
            echo json_encode($reporte, JSON_PRETTY_PRINT);
            exit;
            
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    
    /**
     * Actualizar días trabajados y recalcular
     */
    public function actualizarDias() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $diasTrabajados = intval($_POST['dias_trabajados'] ?? 30);
                
                if ($diasTrabajados <= 0 || $diasTrabajados > 31) {
                    throw new \InvalidArgumentException('Los días trabajados deben estar entre 1 y 31');
                }
                
                // Usar cálculo CON ARL para la vista principal
                $calculosEmpleados = $this->seguridadSocialModel->calcularSeguridadSocialConARLTodosEmpleados($diasTrabajados);
                $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotalConARL($calculosEmpleados);
                
                $data = [
                    'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                    'calculos_empleados' => $calculosEmpleados,
                    'resumen_total' => $resumenTotal,
                    'dias_trabajados' => $diasTrabajados,
                    'success' => "Cálculos actualizados para {$diasTrabajados} días trabajados (incluyendo ARL)"
                ];
                
                $this->view('seguridad_social/index', $data);
                
            } else {
                // Redirigir usando header
                header('Location: /seguridad_social');
                exit;
            }
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo de Seguridad Social por Empleado',
                'error' => 'Error al actualizar cálculos: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'resumen_total' => []
            ];
            
            $this->view('seguridad_social/index', $data);
        }
    }
    
    /**
     * Vista de configuración de porcentajes (solo lectura por ahora)
     */
    public function configuracion() {
        $data = [
            'title' => 'Configuración de Seguridad Social',
            'porcentajes' => [
                'salud_empleado' => \SeguridadSocialModel::PORC_SALUD_EMPLEADO,
                'pension_empleado' => \SeguridadSocialModel::PORC_PENSION_EMPLEADO,
                'salud_empleador' => \SeguridadSocialModel::PORC_SALUD_EMPLEADOR,
                'pension_empleador' => \SeguridadSocialModel::PORC_PENSION_EMPLEADOR
            ]
        ];
        
        $this->view('seguridad_social/configuracion', $data);
    }
    
    /**
     * Vista principal con cálculos incluyendo ARL
     */
    public function indexConARL() {
        try {
            // Obtener cálculos de todos los empleados incluyendo ARL
            $calculosEmpleados = $this->seguridadSocialModel->calcularSeguridadSocialConARLTodos();
            $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotalConARL($calculosEmpleados);
            
            $data = [
                'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                'calculos_empleados' => $calculosEmpleados,
                'resumen_total' => $resumenTotal,
                'incluye_arl' => true,
                'success' => 'Cálculos de seguridad social y ARL generados correctamente'
            ];
            
            $this->view('seguridad_social/index_con_arl', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                'error' => 'Error al calcular seguridad social + ARL: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'resumen_total' => [],
                'incluye_arl' => true
            ];
            
            $this->view('seguridad_social/index_con_arl', $data);
        }
    }
    
    /**
     * Calcular ARL para un empleado específico
     */
    public function calcularARL($idEmpleado) {
        try {
            $diasTrabajados = isset($_POST['dias_trabajados']) ? intval($_POST['dias_trabajados']) : 30;
            
            $calculoCompleto = $this->seguridadSocialModel->calcularSeguridadSocialConARL($idEmpleado, $diasTrabajados);
            
            $data = [
                'title' => 'Detalle de Seguridad Social + ARL',
                'calculo' => $calculoCompleto,
                'incluye_arl' => true,
                'success' => 'Cálculo realizado correctamente'
            ];
            
            $this->view('seguridad_social/detalle_con_arl', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Detalle de Seguridad Social + ARL',
                'error' => 'Error al calcular: ' . $e->getMessage(),
                'calculo' => null,
                'incluye_arl' => true
            ];
            
            $this->view('seguridad_social/detalle_con_arl', $data);
        }
    }
    
    /**
     * Vista de gestión de riesgos ARL
     */
    public function gestionRiesgos() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idEmpleado = intval($_POST['id_empleado']);
                $codigoRiesgo = intval($_POST['codigo_riesgo']);
                
                if ($this->arlModel->asignarRiesgoEmpleado($idEmpleado, $codigoRiesgo)) {
                    $success = 'Nivel de riesgo asignado correctamente';
                } else {
                    $error = 'Error al asignar el nivel de riesgo';
                }
            }
            
            // Obtener empleados con sus riesgos actuales
            $empleadoModel = new \Empleado();
            // Obtener empleados válidos (excluye roles)
            $empleados = $empleadoModel->getAllWithRoles();
            
            $empleadosConRiesgo = [];
            foreach ($empleados as $empleado) {
                $riesgo = $this->arlModel->getRiesgoEmpleado($empleado['id_empleados']);
                $empleado['riesgo_actual'] = $riesgo;
                $empleadosConRiesgo[] = $empleado;
            }
            
            $data = [
                'title' => 'Gestión de Riesgos ARL',
                'empleados' => $empleadosConRiesgo,
                'niveles_riesgo' => $this->arlModel->getTodosNivelesRiesgo(),
                'estadisticas' => $this->arlModel->getEstadisticasRiesgo(),
                'success' => $success ?? null,
                'error' => $error ?? null
            ];
            
            $this->view('seguridad_social/gestion_riesgos', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Gestión de Riesgos ARL',
                'error' => 'Error: ' . $e->getMessage(),
                'empleados' => [],
                'niveles_riesgo' => [],
                'estadisticas' => []
            ];
            
            $this->view('seguridad_social/gestion_riesgos', $data);
        }
    }
    
    /**
     * Cálculo de ARL puro (según PROM original)
     */
    public function calculoARLPuro() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $salarioBase = floatval($_POST['salario_base'] ?? 0);
                $diasTrabajados = intval($_POST['dias_trabajados'] ?? 30);
                $codigoRiesgo = intval($_POST['codigo_riesgo'] ?? 1);
                
                $calculoARL = $this->arlModel->calcularARL($salarioBase, $diasTrabajados, $codigoRiesgo);
                
                $data = [
                    'title' => 'Cálculo ARL (PROM Original)',
                    'calculo_arl' => $calculoARL,
                    'niveles_riesgo' => $this->arlModel->getTodosNivelesRiesgo(),
                    'success' => 'Cálculo ARL realizado correctamente'
                ];
                
            } else {
                $data = [
                    'title' => 'Cálculo ARL (PROM Original)',
                    'calculo_arl' => null,
                    'niveles_riesgo' => $this->arlModel->getTodosNivelesRiesgo()
                ];
            }
            
            $this->view('seguridad_social/calculo_arl_puro', $data);
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo ARL (PROM Original)',
                'error' => 'Error en el cálculo: ' . $e->getMessage(),
                'calculo_arl' => null,
                'niveles_riesgo' => $this->arlModel->getTodosNivelesRiesgo()
            ];
            
            $this->view('seguridad_social/calculo_arl_puro', $data);
        }
    }
    
    /**
     * Actualizar días trabajados para cálculos con ARL
     */
    public function actualizarDiasConARL() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $diasTrabajados = intval($_POST['dias_trabajados'] ?? 30);
                
                if ($diasTrabajados <= 0 || $diasTrabajados > 31) {
                    throw new \InvalidArgumentException('Los días trabajados deben estar entre 1 y 31');
                }
                
                $calculosEmpleados = $this->seguridadSocialModel->calcularSeguridadSocialConARLTodos($diasTrabajados);
                $resumenTotal = $this->seguridadSocialModel->obtenerResumenTotalConARL($calculosEmpleados);
                
                $data = [
                    'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                    'calculos_empleados' => $calculosEmpleados,
                    'resumen_total' => $resumenTotal,
                    'dias_trabajados' => $diasTrabajados,
                    'incluye_arl' => true,
                    'success' => "Cálculos actualizados para {$diasTrabajados} días trabajados (incluyendo ARL)"
                ];
                
                $this->view('seguridad_social/index_con_arl', $data);
                
            } else {
                // Redirigir usando header
                header('Location: /seguridad_social/indexConARL');
                exit;
            }
            
        } catch (\Exception $e) {
            $data = [
                'title' => 'Cálculo de Seguridad Social + ARL por Empleado',
                'error' => 'Error al actualizar cálculos: ' . $e->getMessage(),
                'calculos_empleados' => [],
                'resumen_total' => [],
                'incluye_arl' => true
            ];
            
            $this->view('seguridad_social/index_con_arl', $data);
        }
    }
}