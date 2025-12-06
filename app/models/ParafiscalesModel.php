<?php
namespace App\Models;

use PDO;

class ParafiscalesModel extends Model {
    
    // Constantes para cálculos de parafiscales 2025
    const PORC_SENA = 2.0;           // 2%
    const PORC_ICBF = 3.0;           // 3%
    const PORC_CAJA_COMP = 4.0;      // 4%
    const PORC_TOTAL = 9.0;          // 9% total
    
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
    
    /**
     * Calcular parafiscales completos para un empleado
     */
    public function calcularParafiscalesCompleto($idEmpleado) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        // Obtener el cálculo de devengado (base para parafiscales)
        $devengadoModel = new DevengadoModel();
        $devengado = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
        
        $totalDevengado = $devengado['resumen']['total_devengado'];
        
        // Calcular cada componente parafiscal
        $sena = $totalDevengado * (self::PORC_SENA / 100);
        $icbf = $totalDevengado * (self::PORC_ICBF / 100);
        $cajaCompensacion = $totalDevengado * (self::PORC_CAJA_COMP / 100);
        $totalParafiscales = $sena + $icbf + $cajaCompensacion;
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'documento' => $empleado['documento'] ?? '',
                'cargo' => $empleado['cargo'] ?? 'No especificado',
                'salario_basico' => floatval($empleado['sueldo_actual'] ?? 0)
            ],
            'base_calculo' => [
                'total_devengado' => $totalDevengado,
                'descripcion' => 'Base de cálculo = Total Devengado',
                'formula' => 'Salario + Auxilio + Horas Extras + Otros Conceptos'
            ],
            'parafiscales' => [
                'sena' => [
                    'base' => $totalDevengado,
                    'porcentaje' => self::PORC_SENA,
                    'valor' => $sena,
                    'descripcion' => 'SENA - Servicio Nacional de Aprendizaje',
                    'formula' => 'Total Devengado × 2%',
                    'entidad' => 'SENA'
                ],
                'icbf' => [
                    'base' => $totalDevengado,
                    'porcentaje' => self::PORC_ICBF,
                    'valor' => $icbf,
                    'descripcion' => 'ICBF - Instituto Colombiano de Bienestar Familiar',
                    'formula' => 'Total Devengado × 3%',
                    'entidad' => 'ICBF'
                ],
                'caja_compensacion' => [
                    'base' => $totalDevengado,
                    'porcentaje' => self::PORC_CAJA_COMP,
                    'valor' => $cajaCompensacion,
                    'descripcion' => 'Caja de Compensación Familiar',
                    'formula' => 'Total Devengado × 4%',
                    'entidad' => 'CAJA COMPENSACIÓN'
                ]
            ],
            'resumen' => [
                'total_parafiscales' => $totalParafiscales,
                'porcentaje_total' => self::PORC_TOTAL,
                'desglose' => [
                    'sena' => $sena,
                    'icbf' => $icbf,
                    'caja_compensacion' => $cajaCompensacion
                ],
                'porcentajes_individuales' => [
                    'sena' => $totalParafiscales > 0 ? ($sena / $totalParafiscales) * 100 : 0,
                    'icbf' => $totalParafiscales > 0 ? ($icbf / $totalParafiscales) * 100 : 0,
                    'caja_compensacion' => $totalParafiscales > 0 ? ($cajaCompensacion / $totalParafiscales) * 100 : 0
                ]
            ],
            'informacion_legal' => [
                'obligatorio_empleador' => true,
                'descuenta_empleado' => false,
                'normativa' => 'Ley 21 de 1982, Ley 89 de 1988',
                'vigencia' => '2025',
                'observaciones' => 'Los parafiscales son de obligatorio cumplimiento y solo los paga el empleador'
            ],
            'parametros' => [
                'porcentaje_sena' => self::PORC_SENA,
                'porcentaje_icbf' => self::PORC_ICBF,
                'porcentaje_caja' => self::PORC_CAJA_COMP,
                'porcentaje_total' => self::PORC_TOTAL,
                'fecha_calculo' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    /**
     * Calcular parafiscales para todos los empleados
     */
    public function calcularParafiscalesGeneral() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $calculosEmpleados = [];
        $totalEmpresa = [
            'sena' => 0,
            'icbf' => 0,
            'caja_compensacion' => 0,
            'total' => 0,
            'base_total' => 0
        ];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularParafiscalesCompleto($empleado['id_empleados']);
                $calculosEmpleados[] = $calculo;
                
                // Acumular totales de la empresa
                $totalEmpresa['sena'] += $calculo['parafiscales']['sena']['valor'];
                $totalEmpresa['icbf'] += $calculo['parafiscales']['icbf']['valor'];
                $totalEmpresa['caja_compensacion'] += $calculo['parafiscales']['caja_compensacion']['valor'];
                $totalEmpresa['total'] += $calculo['resumen']['total_parafiscales'];
                $totalEmpresa['base_total'] += $calculo['base_calculo']['total_devengado'];
                
            } catch (Exception $e) {
                error_log("Error calculando parafiscales para empleado {$empleado['id_empleados']}: " . $e->getMessage());
                continue;
            }
        }
        
        // Calcular promedios y estadísticas
        $cantidadEmpleados = count($calculosEmpleados);
        $promedios = [
            'sena' => $cantidadEmpleados > 0 ? $totalEmpresa['sena'] / $cantidadEmpleados : 0,
            'icbf' => $cantidadEmpleados > 0 ? $totalEmpresa['icbf'] / $cantidadEmpleados : 0,
            'caja_compensacion' => $cantidadEmpleados > 0 ? $totalEmpresa['caja_compensacion'] / $cantidadEmpleados : 0,
            'total_por_empleado' => $cantidadEmpleados > 0 ? $totalEmpresa['total'] / $cantidadEmpleados : 0,
            'base_devengado' => $cantidadEmpleados > 0 ? $totalEmpresa['base_total'] / $cantidadEmpleados : 0
        ];
        
        $estadisticas = [
            'empleados_procesados' => $cantidadEmpleados,
            'costo_total_empresa' => $totalEmpresa['total'],
            'porcentaje_sobre_nomina' => $totalEmpresa['base_total'] > 0 ? ($totalEmpresa['total'] / $totalEmpresa['base_total']) * 100 : 0,
            'distribucion_porcentual' => [
                'sena' => $totalEmpresa['total'] > 0 ? ($totalEmpresa['sena'] / $totalEmpresa['total']) * 100 : 0,
                'icbf' => $totalEmpresa['total'] > 0 ? ($totalEmpresa['icbf'] / $totalEmpresa['total']) * 100 : 0,
                'caja_compensacion' => $totalEmpresa['total'] > 0 ? ($totalEmpresa['caja_compensacion'] / $totalEmpresa['total']) * 100 : 0
            ]
        ];
        
        return [
            'calculos_empleados' => $calculosEmpleados,
            'totales_empresa' => $totalEmpresa,
            'promedios' => $promedios,
            'estadisticas' => $estadisticas,
            'total_empleados' => $cantidadEmpleados
        ];
    }
    
    /**
     * Guardar cálculo de parafiscales en la base de datos
     */
    public function guardarParafiscales($calculoParafiscales) {
        try {
            $this->db->beginTransaction();
            
            // Insertar en tabla parafiscales
            $sqlParafiscales = "INSERT INTO parafiscales (valor_total, total_devengado_id) VALUES (?, ?)";
            $stmtParafiscales = $this->db->prepare($sqlParafiscales);
            $stmtParafiscales->execute([
                $calculoParafiscales['resumen']['total_parafiscales'],
                null // Por ahora null, se puede asociar después
            ]);
            
            $parafiscalesId = $this->db->lastInsertId();
            
            // Insertar SENA
            $sqlSena = "INSERT INTO sena (nombre, valor, parafiscales_id) VALUES (?, ?, ?)";
            $stmtSena = $this->db->prepare($sqlSena);
            $stmtSena->execute([
                'SENA - ' . $calculoParafiscales['empleado']['nombre'],
                $calculoParafiscales['parafiscales']['sena']['valor'],
                $parafiscalesId
            ]);
            
            // Insertar Caja de Compensación
            $sqlCompensacion = "INSERT INTO compensacion (valor_total, parafiscales_id) VALUES (?, ?)";
            $stmtCompensacion = $this->db->prepare($sqlCompensacion);
            $stmtCompensacion->execute([
                $calculoParafiscales['parafiscales']['caja_compensacion']['valor'],
                $parafiscalesId
            ]);
            
            $this->db->commit();
            return $parafiscalesId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Error al guardar parafiscales: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener histórico de parafiscales
     */
    public function obtenerHistorico($limite = 50) {
        $sql = "SELECT p.*, s.nombre as nombre_sena, s.valor as valor_sena, 
                       c.valor_total as valor_compensacion
                FROM parafiscales p
                LEFT JOIN sena s ON p.id_parafiscales = s.parafiscales_id
                LEFT JOIN compensacion c ON p.id_parafiscales = c.parafiscales_id
                ORDER BY p.id_parafiscales DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener los porcentajes de SENA, ICBF y Caja desde la base de datos de parámetros
     */
    private function getPorcentajesParafiscales() {
        require_once __DIR__ . '/ParametrosModel.php';
        $paramModel = new ParametrosModel();
        $aportes = $paramModel->getAportes();
        return [
            'sena' => isset($aportes['sena']) ? floatval($aportes['sena']) : 0,
            'icbf' => isset($aportes['icbf']) ? floatval($aportes['icbf']) : 0,
            'caja' => isset($aportes['parafiscales']) ? floatval($aportes['parafiscales']) : 0
        ];
    }
}
?>
