<?php
namespace App\Models;

use PDO;

class DesprendibleModel {
    private $db;
    
    public function __construct() {
        $this->db = require __DIR__ . '/../../config/database.php';
    }
    
    /**
     * Crear un nuevo desprendible/nómina para un empleado
     */
    public function crearDesprendible($empleadoId, $mes = null, $anio = null, $dia = null) {
        try {
            if (!$mes) $mes = date('n');
            if (!$anio) $anio = date('Y');
            if (!$dia) $dia = date('j');
            
            // Verificar si ya existe
            $sql = 'SELECT id_nomina FROM nomina WHERE empleado_id = ? AND mes = ? AND anio = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$empleadoId, $mes, $anio]);
            
            if ($stmt->rowCount() > 0) {
                return true; // Ya existe, no crear duplicado
            }
            
            // Obtener datos de nómina
            $nominaModel = new NominaModel();
            $calculo = $nominaModel->calcularNominaCompleta($empleadoId);
            
            // Insertar nuevo registro
            $sql = 'INSERT INTO nomina (anio, mes, dia, empleado_id, valor_pagar, estado) 
                    VALUES (?, ?, ?, ?, ?, "pendiente")';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $anio,
                $mes,
                $dia,
                $empleadoId,
                $calculo['resumen']['neto_pagar'] ?? 0
            ]);
            
        } catch (\Exception $e) {
            error_log("Error creando desprendible: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener datos completos del desprendible para un empleado
     */
    public function obtenerDesprendible($empleadoId, $mes = null, $anio = null) {
        // Si no se especifica mes/año, usar el actual
        if (!$mes) $mes = date('m');
        if (!$anio) $anio = date('Y');
        
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($empleadoId);
        
        if (!$empleado) {
            return null;
        }
        
        // Obtener cálculos de nómina
        $nominaModel = new NominaModel();
        $nominaEmpleado = $nominaModel->calcularNominaCompleta($empleadoId);
        
        // Obtener información de la empresa (esto debería venir de configuración)
        $infoEmpresa = $this->obtenerInfoEmpresa();
        
        // Formatear período
        $meses = [
            '01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL',
            '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'
        ];
        
        $periodo = "LIQUIDACION DE NOMINA DEL 01 AL 30 DEL MES DE " . $meses[$mes] . " DEL AÑO " . $anio;
        
        return [
            'empresa' => $infoEmpresa,
            'empleado' => [
                'nombre' => ($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? ''),
                'cedula' => $empleado['id_doc'] ?? $empleado['id_empleados'] ?? 'N/A',
                'periodo' => $periodo,
                'dias_trabajados' => 30 // Esto debería calcularse según el período real
            ],
            'devengado' => [
                'sueldo_basico' => $nominaEmpleado['devengado']['salario_basico'] ?? 0,
                'horas_extras' => $nominaEmpleado['devengado']['horas_extras'] ?? 0,
                'comisiones' => $nominaEmpleado['devengado']['comisiones'] ?? 0,
                'auxilio_transporte' => $nominaEmpleado['devengado']['auxilio_transporte'] ?? 0,
                'otros' => $nominaEmpleado['devengado']['otros_devengados'] ?? 0,
                'total_devengado' => $nominaEmpleado['resumen']['total_devengado'] ?? 0
            ],
            'deducciones' => [
                'aportes_salud' => $nominaEmpleado['deducciones']['salud'] ?? 0,
                'aportes_pension' => $nominaEmpleado['deducciones']['pension'] ?? 0,
                'aportes_fs' => $nominaEmpleado['deducciones']['fondo_solidaridad'] ?? 0,
                'retencion' => $nominaEmpleado['deducciones']['retencion_fuente'] ?? 0,
                'otros_descuentos' => $nominaEmpleado['deducciones']['otros_deducidos'] ?? 0,
                'total_deducido' => $nominaEmpleado['deducciones']['total_deducciones'] ?? 0
            ],
            'neto_pagado' => $nominaEmpleado['resumen']['neto_pagar'] ?? 0,
            'fecha_generacion' => date('Y-m-d'),
            'numero_desprendible' => $this->generarNumeroDesprendible($empleadoId, $mes, $anio)
        ];
    }
    
    /**
     * Obtener información de la empresa
     */
    private function obtenerInfoEmpresa() {
        // Esto debería venir de una tabla de configuración de la empresa
        return [
            'nombre' => 'ZIGMA CORPORATION S.A.S',
            'nit' => '900.123.456-7',
            'direccion' => 'Calle 123 #45-67',
            'telefono' => '(601) 234-5678',
            'ciudad' => 'Bogotá D.C.',
            'logo' => 'assets/img/logo-empresa.png' // Ruta del logo
        ];
    }
    
    /**
     * Generar número único del desprendible
     */
    private function generarNumeroDesprendible($empleadoId, $mes, $anio) {
        return str_pad($empleadoId, 3, '0', STR_PAD_LEFT) . 
               str_pad($mes, 2, '0', STR_PAD_LEFT) . 
               $anio . 
               rand(100, 999);
    }
    
    /**
     * Obtener lista de empleados para selección
     */
    public function obtenerEmpleadosParaDesprendible() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        return $empleados;
    }
    
    private function filtrarEmpleadosEspeciales($empleados) {
        return array_filter($empleados, function($emp) {
            return !in_array($emp['id_empleados'], [1, 2, 3]);
        });
    }
    
    /**
     * Validar si existe información de nómina para un empleado en un período
     */
    public function validarPeriodo($empleadoId, $mes, $anio) {
        // Aquí se validaría si existe información de nómina para ese período
        // Por ahora retornamos true
        return true;
    }
    
    /**
     * Registrar notificación de desprendible generado
     */
    public function registrarNotificacionDesprendible($empleadoId, $mes, $anio) {
        require_once __DIR__ . '/NotificacionModel.php';
        $noti = new NotificacionModel();
        $mensaje = "Se ha generado un desprendible de nómina para el periodo $mes/$anio.";
        $url = "/ZIGMA/public/index.php?url=Desprendible/mostrar/$empleadoId/$mes/$anio";
        $noti->registrar($empleadoId, 'desprendible', $mensaje, $url);
    }
    
    /**
     * Eliminar desprendible de la base de datos
     */
    public function eliminarDesprendible($empleadoId) {
        try {
            // Eliminar desprendible de la tabla nomina
            $sql = 'DELETE FROM nomina WHERE empleado_id = ?';
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([$empleadoId]);
            
            // Si se eliminó exitosamente, generar uno nuevo automáticamente
            if ($resultado) {
                $mes = date('n'); // Mes actual (1-12)
                $anio = date('Y'); // Año actual
                $dia = date('j'); // Día actual (1-31)
                
                // Crear nuevo desprendible para el mes actual
                $this->crearDesprendible($empleadoId, $mes, $anio, $dia);
            }
            
            return $resultado;
        } catch (\Exception $e) {
            error_log("Error eliminando desprendible: " . $e->getMessage());
            return false;
        }
    }
}