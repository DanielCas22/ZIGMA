<?php
require_once 'Controller.php';

class NominaController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        
        try {
            $nominaModel = $this->model('NominaModel');
            $nominaCompleta = $nominaModel->calcularNominaGeneral();
            
            $this->view('nomina/index', [
                'title' => 'Nómina - Sistema de Pago de Salarios',
                'nomina_empleados' => $nominaCompleta['nomina_empleados'],
                'totales_empresa' => $nominaCompleta['totales_empresa'],
                'estadisticas' => $nominaCompleta['estadisticas'],
                'total_empleados' => $nominaCompleta['total_empleados'],
                'periodo' => $nominaCompleta['periodo'],
                'fecha_generacion' => $nominaCompleta['fecha_generacion'],
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
