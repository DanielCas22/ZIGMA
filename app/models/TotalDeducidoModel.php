<?php
namespace App\Models;

use PDO;
use InvalidArgumentException;

class TotalDeducidoModel extends Model {
    
    // Constantes para cálculos 2025
    const SALARIO_MINIMO = 1423000;
    const PORC_SALUD_EMPLEADO = 4.0;      // 4% (Fallback)
    const PORC_PENSION_EMPLEADO = 4.0;    // 4% (Fallback)
    
    /**
     * Obtener parámetros de aportes desde la base de datos
     * SIEMPRE obtiene de la BD sin caché para reflejar cambios en tiempo real
     */
    private function getParametrosAportes() {
        $stmt = $this->db->prepare("SELECT * FROM parametros_aportes ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $parametros = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$parametros) {
            $parametros = [
                'salud_empleado' => self::PORC_SALUD_EMPLEADO,
                'pension_empleado' => self::PORC_PENSION_EMPLEADO
            ];
        }
        return $parametros;
    }
    
    // Tabla Fondo de Solidaridad según attachment
    const FONDO_SOLIDARIDAD_RANGOS = [
        ['desde_smlv' => 1, 'hasta_smlv' => 4, 'porcentaje' => 0.0],
        ['desde_smlv' => 4, 'hasta_smlv' => 16, 'porcentaje' => 1.0],
        ['desde_smlv' => 16, 'hasta_smlv' => 17, 'porcentaje' => 1.2],
        ['desde_smlv' => 17, 'hasta_smlv' => 18, 'porcentaje' => 1.4],
        ['desde_smlv' => 18, 'hasta_smlv' => 19, 'porcentaje' => 1.6],
        ['desde_smlv' => 19, 'hasta_smlv' => 20, 'porcentaje' => 1.8],
        ['desde_smlv' => 20, 'hasta_smlv' => 999, 'porcentaje' => 2.0] // Más de 20 SMLV
    ];
    
    /**
     * Obtener salario mínimo vigente desde la base de datos
     */
    private function getSalarioMinimoVigente() {
        $paramModel = new ParametrosModel();
        $parametros = $paramModel->getParametrosVigentes();
        return isset($parametros['smlv']) ? $parametros['smlv'] : self::SALARIO_MINIMO;
    }

    /**
     * Calcular el fondo de solidaridad según la tabla de rangos desde la base de datos
     */
    public function calcularFondoSolidaridad($salario) {
        $salarioMinimo = $this->getSalarioMinimoVigente();
        $salarioEnSMLV = $salario / $salarioMinimo;
        
        // Consultar rangos desde la base de datos
        $stmt = $this->db->prepare("SELECT * FROM rangos_fondo_solidaridad WHERE ? > desde_smlv AND ? <= hasta_smlv ORDER BY desde_smlv");
        $stmt->execute([$salarioEnSMLV, $salarioEnSMLV]);
        $rango = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($rango) {
            $valor = ($salario * $rango['porcentaje']) / 100;
            return [
                'salario' => $salario,
                'smlv_equivalente' => $salarioEnSMLV,
                'rango' => $rango['desde_smlv'] . ' - ' . $rango['hasta_smlv'] . ' SMLV',
                'porcentaje' => $rango['porcentaje'],
                'valor' => $valor,
                'aplica' => $valor > 0,
                'formula' => 'Salario × ' . $rango['porcentaje'] . '%'
            ];
        }
        
        // Por defecto, si no está en rangos
        return [
            'salario' => $salario,
            'smlv_equivalente' => $salarioEnSMLV,
            'rango' => 'Fuera de rango',
            'porcentaje' => 0.0,
            'valor' => 0,
            'aplica' => false,
            'formula' => 'No aplica'
        ];
    }
    
    /**
     * Obtener conceptos adicionales deducibles (otros)
     */
    public function obtenerOtrosConceptosDeducibles($idEmpleado) {
        try {
            $conceptosModel = new ConceptosAdicionalesDeduciblesModel();
            
            // Verificar y crear tabla si no existe
            $conceptosModel->crearTablaSeNoExiste();
            
            $resumen = $conceptosModel->obtenerResumenConceptos($idEmpleado);
            
            return [
                'valor_total' => $resumen['total'],
                'conceptos' => $resumen['conceptos'],
                'cantidad' => $resumen['cantidad']
            ];
            
        } catch (Exception $e) {
            error_log("Error obteniendo otros conceptos deducibles para empleado $idEmpleado: " . $e->getMessage());
            return [
                'valor_total' => 0,
                'conceptos' => [],
                'cantidad' => 0
            ];
        }
    }
    
    /**
     * Calcular el total deducido completo de un empleado
     */
    public function calcularTotalDeducidoCompleto($idEmpleado) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        // Obtener el cálculo de devengado (necesario para la base de cálculo)
        $devengadoModel = new DevengadoModel();
        $devengado = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
        
        $salarioBasico = floatval($empleado['sueldo_actual'] ?? 0);
        $auxilioTransporte = $devengado['conceptos']['auxilio_transporte']['valor'];
        $horasExtras = $devengado['conceptos']['horas_extras']['valor'];
        
        // Obtener otros conceptos deducibles
        $otrosDeducibles = $this->obtenerOtrosConceptosDeducibles($idEmpleado);
        
        // FÓRMULAS SIMPLIFICADAS SEGÚN REQUERIMIENTOS:
        
        // 1. SALUD: ((total devengado - auxilio de transporte) + horas extras + otros) * 4%
        $baseCalculoSalud = ($devengado['resumen']['total_devengado'] - $auxilioTransporte) + $horasExtras + $otrosDeducibles['valor_total'];
        $saludEmpleado = $baseCalculoSalud * (self::PORC_SALUD_EMPLEADO / 100);
        
        // 2. PENSIÓN: El mismo resultado que en salud 
        $pensionEmpleado = $baseCalculoSalud * (self::PORC_PENSION_EMPLEADO / 100);
        
        // 3. FONDO DE SOLIDARIDAD: Depende de la cantidad de salarios que gane un empleado
        $fondoSolidaridad = $this->calcularFondoSolidaridad($salarioBasico);
        
        // 4. RETENCIÓN EN LA FUENTE: Basada en lo que ya tienes incorporado
        $retencionModel = new RetencionFuenteModel();
        $retencionFuente = null;
        try {
            $retencionFuente = $retencionModel->calcularProcedimiento1($baseCalculoSalud, [
                'pension' => $pensionEmpleado,
                'fondo_solidaridad' => $fondoSolidaridad['valor']
            ]);
        } catch (Exception $e) {
            error_log("Error calculando retención para empleado $idEmpleado: " . $e->getMessage());
            $retencionFuente = null;
        }
        
        // Calcular total de deducciones
        $totalDeducciones = $saludEmpleado + 
                          $pensionEmpleado + 
                          $fondoSolidaridad['valor'] + 
                          ($retencionFuente['retencion_art833'] ?? 0) +
                          $otrosDeducibles['valor_total'];
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'documento' => $empleado['documento'] ?? '',
                'cargo' => $empleado['cargo'] ?? 'No especificado',
                'salario_basico' => $salarioBasico
            ],
            'base_calculo' => [
                'total_devengado' => $devengado['resumen']['total_devengado'],
                'auxilio_transporte' => $auxilioTransporte,
                'horas_extras' => $horasExtras,
                'otros_agregados' => $otrosDeducibles['valor_total'],
                'base_final' => $baseCalculoSalud,
                'formula' => '(Total Devengado - Auxilio) + Horas Extras + Otros'
            ],
            'deducciones' => [
                'salud_empleado' => [
                    'base' => $baseCalculoSalud,
                    'porcentaje' => self::PORC_SALUD_EMPLEADO,
                    'valor' => $saludEmpleado,
                    'descripcion' => 'Salud empleado 4%',
                    'formula' => '((Total Devengado - Auxilio) + Horas Extras + Otros) × 4%'
                ],
                'pension_empleado' => [
                    'base' => $baseCalculoSalud,
                    'porcentaje' => self::PORC_PENSION_EMPLEADO,
                    'valor' => $pensionEmpleado,
                    'descripcion' => 'Pensión empleado 4%',
                    'formula' => '((Total Devengado - Auxilio) + Horas Extras + Otros) × 4%'
                ],
                'fondo_solidaridad' => $fondoSolidaridad,
                'retencion_fuente' => $retencionFuente ? [
                    'valor' => $retencionFuente['retencion_art833'] ?? 0,
                    'base_retencion' => $retencionFuente['base_retencion'] ?? 0,
                    'descripcion' => 'Retención en la fuente',
                    'detalle' => $retencionFuente
                ] : [
                    'valor' => 0,
                    'descripcion' => 'Sin retención aplicable',
                    'detalle' => null
                ],
                'otros_deducibles' => [
                    'valor' => $otrosDeducibles['valor_total'],
                    'descripcion' => $otrosDeducibles['cantidad'] > 0 ? 
                        "{$otrosDeducibles['cantidad']} concepto(s) deducible(s)" : 
                        'Sin otros descuentos',
                    'detalle' => $otrosDeducibles['conceptos']
                ]
            ],
            'resumen' => [
                'total_deducciones' => $totalDeducciones,
                'desglose' => [
                    'salud' => $saludEmpleado,
                    'pension' => $pensionEmpleado,
                    'fondo_solidaridad' => $fondoSolidaridad['valor'],
                    'retencion_fuente' => $retencionFuente['retencion_art833'] ?? 0,
                    'otros' => $otrosDeducibles['valor_total']
                ],
                'porcentajes' => [
                    'salud' => $totalDeducciones > 0 ? ($saludEmpleado / $totalDeducciones) * 100 : 0,
                    'pension' => $totalDeducciones > 0 ? ($pensionEmpleado / $totalDeducciones) * 100 : 0,
                    'fondo_solidaridad' => $totalDeducciones > 0 ? ($fondoSolidaridad['valor'] / $totalDeducciones) * 100 : 0,
                    'retencion_fuente' => $totalDeducciones > 0 ? (($retencionFuente['retencion_art833'] ?? 0) / $totalDeducciones) * 100 : 0,
                    'otros' => $totalDeducciones > 0 ? ($otrosDeducibles['valor_total'] / $totalDeducciones) * 100 : 0
                ]
            ],
            'parametros' => [
                'salario_minimo' => self::SALARIO_MINIMO,
                'porcentaje_salud' => self::PORC_SALUD_EMPLEADO,
                'porcentaje_pension' => self::PORC_PENSION_EMPLEADO,
                'fecha_calculo' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    /**
     * Calcular total deducido para todos los empleados
     */
    public function calcularTotalDeducidoTodosEmpleados() {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        // Filtrar empleados del sistema (IDs 1, 2, 3 son empleados del sistema)
        $empleados = array_filter($empleados, function($emp) {
            return !in_array($emp['id_empleados'], [1, 2, 3]);
        });
        
        $resultados = [];
        $totales = [
            'salud' => 0,
            'pension' => 0,
            'fondo_solidaridad' => 0,
            'retencion_fuente' => 0,
            'otros' => 0,
            'total_general' => 0
        ];
        
        $estadisticas = [
            'empleados_con_fondo_solidaridad' => 0,
            'empleados_con_retencion' => 0,
            'empleados_con_otros_descuentos' => 0
        ];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularTotalDeducidoCompleto($empleado['id_empleados']);
                $resultados[] = $calculo;
                
                // Sumar totales
                $totales['salud'] += $calculo['deducciones']['salud_empleado']['valor'];
                $totales['pension'] += $calculo['deducciones']['pension_empleado']['valor'];
                $totales['fondo_solidaridad'] += $calculo['deducciones']['fondo_solidaridad']['valor'];
                $totales['retencion_fuente'] += $calculo['deducciones']['retencion_fuente']['valor'];
                $totales['otros'] += $calculo['deducciones']['otros_deducibles']['valor'];
                $totales['total_general'] += $calculo['resumen']['total_deducciones'];
                
                // Estadísticas
                if ($calculo['deducciones']['fondo_solidaridad']['valor'] > 0) {
                    $estadisticas['empleados_con_fondo_solidaridad']++;
                }
                if ($calculo['deducciones']['retencion_fuente']['valor'] > 0) {
                    $estadisticas['empleados_con_retencion']++;
                }
                if ($calculo['deducciones']['otros_deducibles']['valor'] > 0) {
                    $estadisticas['empleados_con_otros_descuentos']++;
                }
                
            } catch (Exception $e) {
                // Si ocurre un error, igual agregar el empleado con mensaje de error
                $resultados[] = [
                    'empleado' => $empleado,
                    'error' => 'No se pudo calcular deducciones: ' . $e->getMessage(),
                    'deducciones' => [
                        'salud_empleado' => ['valor' => 0],
                        'pension_empleado' => ['valor' => 0],
                        'fondo_solidaridad' => ['valor' => 0, 'aplica' => false, 'rango' => '', 'porcentaje' => 0],
                        'retencion_fuente' => ['valor' => 0, 'base_retencion' => 0],
                        'otros_deducibles' => ['valor' => 0, 'detalle' => [], 'descripcion' => '']
                    ],
                    'resumen' => [
                        'total_deducciones' => 0
                    ]
                ];
            }
        }
        
        $totalEmpleados = count($resultados);
        
        return [
            'empleados' => $resultados,
            'totales_empresa' => $totales,
            'total_empleados' => $totalEmpleados,
            'estadisticas' => $estadisticas,
            'promedios' => [
                'salud' => $totalEmpleados > 0 ? $totales['salud'] / $totalEmpleados : 0,
                'pension' => $totalEmpleados > 0 ? $totales['pension'] / $totalEmpleados : 0,
                'fondo_solidaridad' => $totalEmpleados > 0 ? $totales['fondo_solidaridad'] / $totalEmpleados : 0,
                'retencion_fuente' => $totalEmpleados > 0 ? $totales['retencion_fuente'] / $totalEmpleados : 0,
                'otros' => $totalEmpleados > 0 ? $totales['otros'] / $totalEmpleados : 0,
                'total_general' => $totalEmpleados > 0 ? $totales['total_general'] / $totalEmpleados : 0
            ],
            'porcentajes_empresa' => [
                'salud' => $totales['total_general'] > 0 ? ($totales['salud'] / $totales['total_general']) * 100 : 0,
                'pension' => $totales['total_general'] > 0 ? ($totales['pension'] / $totales['total_general']) * 100 : 0,
                'fondo_solidaridad' => $totales['total_general'] > 0 ? ($totales['fondo_solidaridad'] / $totales['total_general']) * 100 : 0,
                'retencion_fuente' => $totales['total_general'] > 0 ? ($totales['retencion_fuente'] / $totales['total_general']) * 100 : 0,
                'otros' => $totales['total_general'] > 0 ? ($totales['otros'] / $totales['total_general']) * 100 : 0
            ]
        ];
    }
    
    /**
     * Guardar cálculo de total deducido en base de datos
     */
    public function guardarTotalDeducido($calculoDeducido) {
        try {
            $this->db->beginTransaction();
            
            // Insertar en total_deducido
            $sql = "INSERT INTO total_deducido (salario, valor, otros, total) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $calculoDeducido['empleado']['salario_basico'],
                $calculoDeducido['resumen']['total_deducciones'],
                json_encode($calculoDeducido['deducciones']['otros_deducibles']['detalle']),
                $calculoDeducido['resumen']['total_deducciones']
            ]);
            
            $deducidoId = $this->db->lastInsertId();
            
            $this->db->commit();
            return $deducidoId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Retorna el total deducido por un empleado
     * Calcula basado en conceptos adicionales deducibles
     */
    public function getTotalByEmpleado($empleado_id) {
        try {
            // Obtener conceptos adicionales (deducciones) del empleado
            $conceptosModel = new ConceptosAdicionalesDeduciblesModel();
            $conceptos = $conceptosModel->obtenerConceptosPorEmpleado($empleado_id);
            
            $total = 0;
            if (is_array($conceptos)) {
                foreach ($conceptos as $concepto) {
                    $total += floatval($concepto['valor'] ?? 0);
                }
            }
            
            return $total;
        } catch (\Exception $e) {
            error_log("Error calculando total deducido para empleado $empleado_id: " . $e->getMessage());
            return 0;
        }
    }
}