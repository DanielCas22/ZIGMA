<?php
namespace App\Controllers;
require_once __DIR__ . '/../../config/session_config.php';
require_once __DIR__ . '/Controller.php';
use App\Controllers\Controller;

class NominaController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $nominaModel = $this->model('NominaModel');
            $user = $_SESSION['user'] ?? null;
            $rol = $user['rol'] ?? null;
            $empleado_id = $user['empleado_id'] ?? null;
            if ($rol === 'empleado' && $empleado_id) {
                // Solo mostrar datos del empleado logueado
                $nominaEmpleado = $nominaModel->calcularNominaCompleta($empleado_id);
                $nominaEmpleados = [$nominaEmpleado];
                $totalesEmpresa = [
                    'total_devengado' => $nominaEmpleado['resumen']['total_devengado'],
                    'total_deducciones' => $nominaEmpleado['resumen']['total_deducciones'],
                    'total_neto_pagar' => $nominaEmpleado['resumen']['neto_pagar'],
                    'total_costo_empresa' => $nominaEmpleado['resumen']['total_costo_empresa'],
                    'total_parafiscales' => $nominaEmpleado['aportes_parafiscales']['total_parafiscales'],
                    'total_prestaciones' => $nominaEmpleado['prestaciones_sociales']['total_prestaciones']
                ];
                $estadisticas = [
                    'empleados_procesados' => 1,
                    'promedio_devengado' => $nominaEmpleado['resumen']['total_devengado'],
                    'promedio_deducciones' => $nominaEmpleado['resumen']['total_deducciones'],
                    'promedio_neto_pagar' => $nominaEmpleado['resumen']['neto_pagar'],
                    'porcentaje_deducciones' => $nominaEmpleado['resumen']['total_devengado'] > 0 ? ($nominaEmpleado['resumen']['total_deducciones'] / $nominaEmpleado['resumen']['total_devengado']) * 100 : 0,
                    'costo_total_empresa' => $nominaEmpleado['resumen']['total_costo_empresa']
                ];
                $total_empleados = 1;
                $periodo = date('Y-m');
                $fecha_generacion = date('Y-m-d H:i:s');
            } else {
                $nominaCompleta = $nominaModel->calcularNominaGeneral();
                $nominaEmpleados = $nominaCompleta['nomina_empleados'];
                $totalesEmpresa = $nominaCompleta['totales_empresa'];
                $estadisticas = $nominaCompleta['estadisticas'];
                $total_empleados = $nominaCompleta['total_empleados'];
                $periodo = $nominaCompleta['periodo'];
                $fecha_generacion = $nominaCompleta['fecha_generacion'];
            }
            $this->view('nomina/index', [
                'title' => 'Nómina - Sistema de Pago de Salarios',
                'nomina_empleados' => $nominaEmpleados,
                'totales_empresa' => $totalesEmpresa,
                'estadisticas' => $estadisticas,
                'total_empleados' => $total_empleados,
                'periodo' => $periodo,
                'fecha_generacion' => $fecha_generacion,
                'success' => 'Nómina calculada correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('nomina/index', [
                'title' => 'Nómina - Sistema de Pago de Salarios',
                'error' => 'Error al calcular nómina: ' . $e->getMessage(),
                'nomina_empleados' => [],
                'totales_empresa' => [],
                'estadisticas' => [],
                'total_empleados' => 0,
                'periodo' => date('Y-m'),
                'fecha_generacion' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function detalle($idEmpleado) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $nominaModel = $this->model('NominaModel');
            $nominaEmpleado = $nominaModel->calcularNominaCompleta($idEmpleado);
            
            $this->view('nomina/detalle', [
                'title' => 'Detalle Nómina - ' . $nominaEmpleado['empleado']['nombre'] . ' ' . $nominaEmpleado['empleado']['apellido'],
                'nomina' => $nominaEmpleado,
                'success' => 'Detalle de nómina generado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('nomina/detalle', [
                'title' => 'Detalle Nómina',
                'error' => 'Error al generar detalle de nómina: ' . $e->getMessage(),
                'nomina' => null
            ]);
        }
    }
    
    public function reporte() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $nominaModel = $this->model('NominaModel');
            $periodo = $_GET['periodo'] ?? date('Y-m');
            $reporte = $nominaModel->generarReporteNomina($periodo);
            
            $this->view('nomina/reporte', [
                'title' => 'Reporte de Nómina',
                'reporte' => $reporte,
                'success' => 'Reporte generado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('nomina/reporte', [
                'title' => 'Reporte de Nómina',
                'error' => 'Error al generar reporte: ' . $e->getMessage(),
                'reporte' => null
            ]);
        }
    }
    
    public function resumen() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $nominaModel = $this->model('NominaModel');
            $resumen = $nominaModel->obtenerResumenEjecutivo();
            
            $this->view('nomina/resumen', [
                'title' => 'Resumen Ejecutivo - Nómina',
                'resumen' => $resumen,
                'success' => 'Resumen ejecutivo generado correctamente'
            ]);
            
        } catch (Exception $e) {
            $this->view('nomina/resumen', [
                'title' => 'Resumen Ejecutivo - Nómina',
                'error' => 'Error al generar resumen: ' . $e->getMessage(),
                'resumen' => null
            ]);
        }
    }
}
