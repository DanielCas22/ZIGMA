<?php
namespace App\Models;

use PDO;

class DevengadoModel extends Model {
    
    // Constantes para auxilios y parámetros 2025
    const AUXILIO_TRANSPORTE_LIMITE = 2645000; // 2 SMMLV
    const SALARIO_MINIMO = 1423000;
    
    /**
     * Obtener parámetros vigentes desde la base de datos
     */
    private function getParametrosVigentes() {
        $paramModel = new ParametrosModel();
        return $paramModel->getParametrosVigentes();
    }

    /**
     * Calcular el auxilio de transporte para un empleado usando el campo propio
     */
    public function calcularAuxilioTransporte($empleado) {
        $empleadoModel = new Empleado();
        return $empleadoModel->getAuxilioTransporteEmpleado($empleado);
    }
    
    /**
     * Obtener horas extras de un empleado para el período actual
     */
    public function obtenerHorasExtras($idEmpleado) {
        try {
            $horasExtrasModel = new HorasExtras();
            $horasExtras = $horasExtrasModel->getByEmpleado($idEmpleado);
            
            if (!$horasExtras || !is_array($horasExtras)) {
                return [
                    'valor_total' => 0,
                    'detalle' => [],
                    'total_horas' => 0
                ];
            }
            
            $valorTotal = 0;
            $totalHoras = 0;
            $detalle = [];
            
            foreach ($horasExtras as $hora) {
                $valor = floatval($hora['valor'] ?? 0); // Cambié 'valor_total' por 'valor'
                $horas = floatval($hora['cantidad'] ?? 0); // Cambié 'cantidad_horas' por 'cantidad'
                $tipo = $hora['tipo'] ?? 'Normal'; // Cambié 'tipo_hora' por 'tipo'
                
                $valorTotal += $valor;
                $totalHoras += $horas;
                
                $detalle[] = [
                    'tipo' => $tipo,
                    'horas' => $horas,
                    'valor' => $valor,
                    'fecha' => ($hora['anio'] ?? date('Y')) . '-' . 
                              str_pad($hora['mes'] ?? date('m'), 2, '0', STR_PAD_LEFT) . '-' . 
                              str_pad($hora['dia'] ?? date('d'), 2, '0', STR_PAD_LEFT)
                ];
            }
            
            return [
                'valor_total' => $valorTotal,
                'detalle' => $detalle,
                'total_horas' => $totalHoras
            ];
            
        } catch (\Exception $e) {
            error_log("Error obteniendo horas extras para empleado $idEmpleado: " . $e->getMessage());
            return [
                'valor_total' => 0,
                'detalle' => [],
                'total_horas' => 0
            ];
        }
    }
    
    /**
     * Calcular comisiones de un empleado (por implementar según reglas de negocio)
     */
    public function calcularComisiones($idEmpleado) {
        // Por ahora retornar 0, se puede implementar según reglas específicas
        return [
            'valor_total' => 0,
            'detalle' => 'Sin comisiones configuradas',
            'porcentaje' => 0
        ];
    }
    
    /**
     * Calcular otros conceptos (bonos, incentivos, etc.)
     */
    public function calcularOtrosConceptos($idEmpleado) {
        try {
            $conceptosModel = new ConceptosAdicionalesModel();
            $resumen = $conceptosModel->obtenerResumenConceptos($idEmpleado);
            
            return [
                'valor_total' => $resumen['total'],
                'conceptos' => $resumen['conceptos'],
                'cantidad' => $resumen['cantidad']
            ];
            
        } catch (Exception $e) {
            error_log("Error obteniendo otros conceptos para empleado $idEmpleado: " . $e->getMessage());
            return [
                'valor_total' => 0,
                'conceptos' => [],
                'cantidad' => 0
            ];
        }
    }
    
    /**
     * Calcular el devengado completo de un empleado
     */
    public function calcularDevengadoCompleto($idEmpleado) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        $salarioBasico = floatval($empleado['sueldo_actual'] ?? 0);
        if ($salarioBasico <= 0) {
            throw new InvalidArgumentException("El empleado {$empleado['nombre']} {$empleado['apellido']} no tiene un salario asignado");
        }
        
        // Calcular componentes del devengado
        $auxilioTransporte = $this->calcularAuxilioTransporte($empleado);
        $horasExtras = $this->obtenerHorasExtras($idEmpleado);
        $comisiones = $this->calcularComisiones($idEmpleado);
        $otros = $this->calcularOtrosConceptos($idEmpleado);
        
        // Calcular total devengado
        $totalDevengado = $salarioBasico + 
                         $horasExtras['valor_total'] + 
                         $comisiones['valor_total'] + 
                         $auxilioTransporte + 
                         $otros['valor_total'];
        
        $parametros = $this->getParametrosVigentes();
        $salarioMinimo = isset($parametros['smlv']) ? $parametros['smlv'] : self::SALARIO_MINIMO;
        $auxilioTransporteVigente = isset($parametros['auxilio_transporte']) ? $parametros['auxilio_transporte'] : 0;
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'documento' => $empleado['documento'] ?? '',
                'cargo' => $empleado['cargo'] ?? 'No especificado'
            ],
            'conceptos' => [
                'sueldo_basico' => [
                    'valor' => $salarioBasico,
                    'descripcion' => 'Salario básico mensual'
                ],
                'horas_extras' => [
                    'valor' => $horasExtras['valor_total'],
                    'descripcion' => 'Horas extras trabajadas',
                    'detalle' => $horasExtras['detalle'],
                    'total_horas' => $horasExtras['total_horas']
                ],
                'comisiones' => [
                    'valor' => $comisiones['valor_total'],
                    'descripcion' => 'Comisiones por ventas/metas',
                    'detalle' => $comisiones['detalle']
                ],
                'auxilio_transporte' => [
                    'valor' => $auxilioTransporte,
                    'descripcion' => $auxilioTransporte > 0 ? 
                        'Auxilio de transporte (salario <= $2,645,000)' : 
                        'Sin auxilio (salario > $2,645,000)',
                    'aplica' => $auxilioTransporte > 0
                ],
                'otros' => [
                    'valor' => $otros['valor_total'],
                    'descripcion' => $otros['cantidad'] > 0 ? 
                        "{$otros['cantidad']} concepto(s) adicional(es)" : 
                        'Sin conceptos adicionales',
                    'detalle' => $otros['conceptos']
                ]
            ],
            'resumen' => [
                'total_devengado' => $totalDevengado,
                'desglose' => [
                    'sueldo_basico' => $salarioBasico,
                    'horas_extras' => $horasExtras['valor_total'],
                    'comisiones' => $comisiones['valor_total'],
                    'auxilio_transporte' => $auxilioTransporte,
                    'otros' => $otros['valor_total']
                ],
                'porcentajes' => [
                    'sueldo_basico' => $totalDevengado > 0 ? ($salarioBasico / $totalDevengado) * 100 : 0,
                    'horas_extras' => $totalDevengado > 0 ? ($horasExtras['valor_total'] / $totalDevengado) * 100 : 0,
                    'comisiones' => $totalDevengado > 0 ? ($comisiones['valor_total'] / $totalDevengado) * 100 : 0,
                    'auxilio_transporte' => $totalDevengado > 0 ? ($auxilioTransporte / $totalDevengado) * 100 : 0,
                    'otros' => $totalDevengado > 0 ? ($otros['valor_total'] / $totalDevengado) * 100 : 0
                ]
            ],
            'parametros' => [
                'salario_minimo' => $salarioMinimo,
                'auxilio_transporte_limite' => isset($parametros['smlv']) ? $parametros['smlv'] * 2 : self::AUXILIO_TRANSPORTE_LIMITE,
                'auxilio_transporte_valor' => $auxilioTransporteVigente,
                'fecha_calculo' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    /**
     * Calcular devengado para todos los empleados
     */
    public function calcularDevengadoTodosEmpleados() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $resultados = [];
        $totales = [
            'sueldo_basico' => 0,
            'horas_extras' => 0,
            'comisiones' => 0,
            'auxilio_transporte' => 0,
            'otros' => 0,
            'total_general' => 0
        ];
        
        $contadores = [
            'empleados_con_auxilio' => 0,
            'empleados_con_horas_extras' => 0,
            'empleados_con_comisiones' => 0
        ];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularDevengadoCompleto($empleado['id_empleados']);
                $resultados[] = $calculo;
                
                // Sumar totales
                $totales['sueldo_basico'] += $calculo['conceptos']['sueldo_basico']['valor'];
                $totales['horas_extras'] += $calculo['conceptos']['horas_extras']['valor'];
                $totales['comisiones'] += $calculo['conceptos']['comisiones']['valor'];
                $totales['auxilio_transporte'] += $calculo['conceptos']['auxilio_transporte']['valor'];
                $totales['otros'] += $calculo['conceptos']['otros']['valor'];
                $totales['total_general'] += $calculo['resumen']['total_devengado'];
                
                // Contadores estadísticos
                if ($calculo['conceptos']['auxilio_transporte']['valor'] > 0) {
                    $contadores['empleados_con_auxilio']++;
                }
                if ($calculo['conceptos']['horas_extras']['valor'] > 0) {
                    $contadores['empleados_con_horas_extras']++;
                }
                if ($calculo['conceptos']['comisiones']['valor'] > 0) {
                    $contadores['empleados_con_comisiones']++;
                }
                
            } catch (Exception $e) {
                error_log("Error calculando devengado para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        $totalEmpleados = count($resultados);
        
        return [
            'empleados' => $resultados,
            'totales_empresa' => $totales,
            'total_empleados' => $totalEmpleados,
            'estadisticas' => $contadores,
            'promedios' => [
                'sueldo_basico' => $totalEmpleados > 0 ? $totales['sueldo_basico'] / $totalEmpleados : 0,
                'horas_extras' => $totalEmpleados > 0 ? $totales['horas_extras'] / $totalEmpleados : 0,
                'comisiones' => $totalEmpleados > 0 ? $totales['comisiones'] / $totalEmpleados : 0,
                'auxilio_transporte' => $totalEmpleados > 0 ? $totales['auxilio_transporte'] / $totalEmpleados : 0,
                'otros' => $totalEmpleados > 0 ? $totales['otros'] / $totalEmpleados : 0,
                'total_general' => $totalEmpleados > 0 ? $totales['total_general'] / $totalEmpleados : 0
            ],
            'porcentajes_empresa' => [
                'sueldo_basico' => $totales['total_general'] > 0 ? ($totales['sueldo_basico'] / $totales['total_general']) * 100 : 0,
                'horas_extras' => $totales['total_general'] > 0 ? ($totales['horas_extras'] / $totales['total_general']) * 100 : 0,
                'comisiones' => $totales['total_general'] > 0 ? ($totales['comisiones'] / $totales['total_general']) * 100 : 0,
                'auxilio_transporte' => $totales['total_general'] > 0 ? ($totales['auxilio_transporte'] / $totales['total_general']) * 100 : 0,
                'otros' => $totales['total_general'] > 0 ? ($totales['otros'] / $totales['total_general']) * 100 : 0
            ]
        ];
    }
    
    /**
     * Guardar cálculo de devengado en base de datos
     */
    public function guardarDevengado($calculoDevengado) {
        try {
            $this->db->beginTransaction();
            
            // Insertar en total_devengado
            $sql = "INSERT INTO total_devengado (salario, dias, total) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $calculoDevengado['conceptos']['sueldo_basico']['valor'],
                30, // días del mes
                $calculoDevengado['resumen']['total_devengado']
            ]);
            
            $devengadoId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $devengadoId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Obtener historial de devengados por empleado
     */
    public function obtenerHistorial($idEmpleado, $limite = 12) {
        $sql = "SELECT td.*, DATE_FORMAT(td.created_at, '%Y-%m') as periodo 
                FROM total_devengado td 
                WHERE td.empleado_id = ? 
                ORDER BY td.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idEmpleado, $limite]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Filtrar empleados especiales (placeholders) de la lista de empleados
     */
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