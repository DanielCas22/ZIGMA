<?php
require_once 'Empleado.php';
require_once 'DevengadoModel.php';
require_once 'TotalDeducidoModel.php';
require_once 'ParafiscalesModel.php';
require_once 'PrestacionesSocialesModel.php';

class NominaModel extends Model {
    
    /**
     * Calcular nómina completa para un empleado
     */
    public function calcularNominaCompleta($idEmpleado) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        // Obtener cálculos de cada módulo
        $devengadoModel = new DevengadoModel();
        $deducidoModel = new TotalDeducidoModel();
        $parafiscalesModel = new ParafiscalesModel();
        $prestacionesModel = new PrestacionesSocialesModel();
        
        $devengado = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
        $deducido = $deducidoModel->calcularTotalDeducidoCompleto($idEmpleado);
        $parafiscales = $parafiscalesModel->calcularParafiscalesCompleto($idEmpleado);
        $prestaciones = $prestacionesModel->calcularPrestacionesCompletas($idEmpleado);
        
        // Calcular neto a pagar
        $totalDevengado = $devengado['resumen']['total_devengado'];
        $totalDeducido = $deducido['resumen']['total_deducciones'] ?? 0;
        $netoPagar = $totalDevengado - $totalDeducido;
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'documento' => $empleado['documento'] ?? '',
                'cargo' => $empleado['cargo'] ?? 'No especificado',
                'salario_basico' => floatval($empleado['sueldo_actual'] ?? 0)
            ],
            'devengado' => [
                'salario_basico' => $devengado['conceptos']['sueldo_basico']['valor'] ?? 0,
                'auxilio_transporte' => $devengado['conceptos']['auxilio_transporte']['valor'] ?? 0,
                'horas_extras' => $devengado['conceptos']['horas_extras']['valor'] ?? 0,
                'comisiones' => $devengado['conceptos']['comisiones']['valor'] ?? 0,
                'otros_devengados' => $devengado['otros_conceptos']['valor_total'] ?? 0,
                'total_devengado' => $totalDevengado
            ],
            'seguridad_social_empleado' => [
                'salud' => $deducido['deducciones']['salud_empleado']['valor'] ?? 0,
                'pension' => $deducido['deducciones']['pension_empleado']['valor'] ?? 0,
                'fondo_solidaridad' => $deducido['deducciones']['fondo_solidaridad']['valor'] ?? 0,
                'total_ss_empleado' => ($deducido['deducciones']['salud_empleado']['valor'] ?? 0) + 
                                     ($deducido['deducciones']['pension_empleado']['valor'] ?? 0) + 
                                     ($deducido['deducciones']['fondo_solidaridad']['valor'] ?? 0)
            ],
            'aportes_parafiscales' => [
                'sena' => $parafiscales['parafiscales']['sena']['valor'] ?? 0,
                'icbf' => $parafiscales['parafiscales']['icbf']['valor'] ?? 0,
                'caja_compensacion' => $parafiscales['parafiscales']['caja_compensacion']['valor'] ?? 0,
                'total_parafiscales' => $parafiscales['resumen']['total_parafiscales'] ?? 0
            ],
            'prestaciones_sociales' => [
                'cesantias' => $prestaciones['conceptos']['cesantias']['valor'] ?? 0,
                'intereses_cesantias' => $prestaciones['conceptos']['intereses_cesantias']['valor'] ?? 0,
                'prima_servicios' => $prestaciones['conceptos']['prima_servicios']['valor'] ?? 0,
                'vacaciones' => $prestaciones['conceptos']['vacaciones']['valor'] ?? 0,
                'total_prestaciones' => $prestaciones['resumen']['total_prestaciones'] ?? 0
            ],
            'deducciones' => [
                'salud' => $deducido['deducciones']['salud_empleado']['valor'],
                'pension' => $deducido['deducciones']['pension_empleado']['valor'],
                'fondo_solidaridad' => $deducido['deducciones']['fondo_solidaridad']['valor'],
                'retencion_fuente' => $deducido['deducciones']['retencion_fuente']['valor'] ?? 0,
                'otros_deducidos' => $deducido['deducciones']['otros_deducibles']['valor'] ?? 0,
                'total_deducciones' => $totalDeducido
            ],
            'resumen' => [
                'total_devengado' => $totalDevengado,
                'total_deducciones' => $totalDeducido,
                'neto_pagar' => $netoPagar,
                'total_costo_empresa' => $totalDevengado + $parafiscales['resumen']['total_parafiscales'] + $prestaciones['resumen']['total_prestaciones'],
                'formula_neto' => 'Total Devengado - Total Deducciones'
            ],
            'parametros' => [
                'periodo' => date('Y-m'),
                'fecha_calculo' => date('Y-m-d H:i:s'),
                'tipo_calculo' => 'Nómina Completa'
            ]
        ];
    }
    
    /**
     * Calcular nómina para todos los empleados
     */

    public function calcularNominaGeneral() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $nominaEmpleados = [];
        $totalesEmpresa = [
            'total_devengado' => 0,
            'total_deducciones' => 0,
            'total_neto_pagar' => 0,
            'total_costo_empresa' => 0,
            'total_parafiscales' => 0,
            'total_prestaciones' => 0
        ];
        
        foreach ($empleados as $empleado) {
            try {
                $nominaEmpleado = $this->calcularNominaCompleta($empleado['id_empleados']);
                $nominaEmpleados[] = $nominaEmpleado;
                
                // Acumular totales
                $totalesEmpresa['total_devengado'] += $nominaEmpleado['resumen']['total_devengado'];
                $totalesEmpresa['total_deducciones'] += $nominaEmpleado['resumen']['total_deducciones'];
                $totalesEmpresa['total_neto_pagar'] += $nominaEmpleado['resumen']['neto_pagar'];
                $totalesEmpresa['total_costo_empresa'] += $nominaEmpleado['resumen']['total_costo_empresa'];
                $totalesEmpresa['total_parafiscales'] += $nominaEmpleado['aportes_parafiscales']['total_parafiscales'];
                $totalesEmpresa['total_prestaciones'] += $nominaEmpleado['prestaciones_sociales']['total_prestaciones'];
                
            } catch (Exception $e) {
                error_log("Error calculando nómina para empleado {$empleado['id_empleados']}: " . $e->getMessage());
                continue;
            }
        }
        
        // Calcular estadísticas
        $cantidadEmpleados = count($nominaEmpleados);
        $estadisticas = [
            'empleados_procesados' => $cantidadEmpleados,
            'promedio_devengado' => $cantidadEmpleados > 0 ? $totalesEmpresa['total_devengado'] / $cantidadEmpleados : 0,
            'promedio_deducciones' => $cantidadEmpleados > 0 ? $totalesEmpresa['total_deducciones'] / $cantidadEmpleados : 0,
            'promedio_neto_pagar' => $cantidadEmpleados > 0 ? $totalesEmpresa['total_neto_pagar'] / $cantidadEmpleados : 0,
            'porcentaje_deducciones' => $totalesEmpresa['total_devengado'] > 0 ? ($totalesEmpresa['total_deducciones'] / $totalesEmpresa['total_devengado']) * 100 : 0,
            'costo_total_empresa' => $totalesEmpresa['total_costo_empresa']
        ];
        
        return [
            'nomina_empleados' => $nominaEmpleados,
            'totales_empresa' => $totalesEmpresa,
            'estadisticas' => $estadisticas,
            'total_empleados' => $cantidadEmpleados,
            'periodo' => date('Y-m'),
            'fecha_generacion' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Generar reporte de nómina para impresión
     */
    public function generarReporteNomina($periodo = null) {
        if (!$periodo) {
            $periodo = date('Y-m');
        }
        
        $nominaGeneral = $this->calcularNominaGeneral();
        
        return [
            'titulo' => 'NÓMINA PARA PAGO DE SALARIOS',
            'periodo' => $periodo,
            'fecha_generacion' => date('d/m/Y H:i:s'),
            'datos' => $nominaGeneral,
            'formato' => 'reporte_nomina',
            'version' => '2025'
        ];
    }
    
    /**
     * Obtener resumen ejecutivo de nómina
     */
    public function obtenerResumenEjecutivo() {
        $nominaGeneral = $this->calcularNominaGeneral();
        
        return [
            'resumen' => [
                'total_empleados' => $nominaGeneral['total_empleados'],
                'costo_total_nomina' => $nominaGeneral['totales_empresa']['total_neto_pagar'],
                'costo_total_empresa' => $nominaGeneral['totales_empresa']['total_costo_empresa'],
                'promedio_salario' => $nominaGeneral['estadisticas']['promedio_neto_pagar'],
                'total_deducciones' => $nominaGeneral['totales_empresa']['total_deducciones'],
                'porcentaje_deducciones' => $nominaGeneral['estadisticas']['porcentaje_deducciones']
            ],
            'distribucion' => [
                'devengado' => $nominaGeneral['totales_empresa']['total_devengado'],
                'deducciones' => $nominaGeneral['totales_empresa']['total_deducciones'],
                'parafiscales' => $nominaGeneral['totales_empresa']['total_parafiscales'],
                'prestaciones' => $nominaGeneral['totales_empresa']['total_prestaciones']
            ]
        ];
    }
    
    private function filtrarEmpleadosEspeciales($empleados) {
        return array_filter($empleados, function($emp) {
            $nombre = trim(mb_strtolower($emp['nombre']));
            $apellido = trim(mb_strtolower($emp['apellido']));
            if (($nombre === 'administrador' && $apellido === 'del sistema') ||
                ($nombre === 'coordinador' && $apellido === 'rrhh') ||
                ($nombre === 'empleado' && $apellido === 'general')) {
                return false;
            }
            return true;
        });
    }
}
?>