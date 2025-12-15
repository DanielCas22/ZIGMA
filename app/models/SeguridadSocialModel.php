<?php
namespace App\Models;

use InvalidArgumentException;

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/ARLModel.php';
require_once __DIR__ . '/DevengadoModel.php';

/**
 * Modelo para cálculos de Seguridad Social
 * Basado en total devengado según normatividad colombiana 2025
 */
class SeguridadSocialModel extends Model {
    
    // CONSTANTES PORCENTAJES SEGURIDAD SOCIAL 2025 (Fallback si no hay datos en BD)
    const PORC_SALUD_EMPLEADO = 4.0;
    const PORC_PENSION_EMPLEADO = 4.0;
    const PORC_SALUD_EMPLEADOR = 8.5;
    const PORC_PENSION_EMPLEADOR = 12.0;
    
    /**
     * Obtener parámetros de aportes desde la base de datos
     * SIEMPRE obtiene de la BD sin caché para reflejar cambios en tiempo real
     */
    private function getParametrosAportes() {
        $stmt = $this->db->prepare("SELECT * FROM parametros_aportes ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $parametros = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$parametros) {
            // Fallback a constantes
            $parametros = [
                'salud_empleado' => self::PORC_SALUD_EMPLEADO,
                'salud_empleador' => self::PORC_SALUD_EMPLEADOR,
                'pension_empleado' => self::PORC_PENSION_EMPLEADO,
                'pension_empleador' => self::PORC_PENSION_EMPLEADOR
            ];
        }
        return $parametros;
    }
    
    private function obtenerRolesEmpleado($idEmpleado) {
        try {
            $empleadoModel = new Empleado();
            $empleadoConRoles = $empleadoModel->getByIdWithRoles($idEmpleado);
            
            if ($empleadoConRoles && isset($empleadoConRoles['todos_los_roles'])) {
                // Dividir los roles y limpiar espacios
                $rolesString = $empleadoConRoles['todos_los_roles'];
                $rolesArray = array_map('trim', explode(',', $rolesString));
                return $rolesArray;
            }
            
            return ['empleado']; // Rol por defecto para información
        } catch (Exception $e) {
            return ['empleado'];
        }
    }
    
    /**
     * Calcular seguridad social completa (empleado + empleador) basado en el primer PROM
     * @param float $totalDevengado Total devengado del empleado
     * @return array Array con todos los cálculos de seguridad social
     */
    public function calcularSeguridadSocialCompleta($totalDevengado) {
        // Validación
        if ($totalDevengado <= 0) {
            throw new InvalidArgumentException('Error: Total devengado debe ser mayor a 0');
        }
        
        // Obtener parámetros desde BD
        $params = $this->getParametrosAportes();

        // CÁLCULO DEDUCCIONES EMPLEADO (Seguridad Social)
        $saludEmpleado = $totalDevengado * ($params['salud_empleado'] / 100);
        $pensionEmpleado = $totalDevengado * ($params['pension_empleado'] / 100);
        $totalDeduccionesEmpleado = $saludEmpleado + $pensionEmpleado;

        // CÁLCULO APORTES EMPLEADOR (Seguridad Social)
        $saludEmpleador = $totalDevengado * ($params['salud_empleador'] / 100);
        $pensionEmpleador = $totalDevengado * ($params['pension_empleador'] / 100);
        $totalAportesEmpleador = $saludEmpleador + $pensionEmpleador;

        return [
            'total_devengado' => $totalDevengado,
            'deducciones_empleado' => [
                'salud' => [
                    'porcentaje' => $params['salud_empleado'],
                    'valor' => $saludEmpleado
                ],
                'pension' => [
                    'porcentaje' => $params['pension_empleado'],
                    'valor' => $pensionEmpleado
                ],
                'total' => $totalDeduccionesEmpleado
            ],
            'aportes_empleador' => [
                'salud' => [
                    'porcentaje' => $params['salud_empleador'],
                    'valor' => $saludEmpleador
                ],
                'pension' => [
                    'porcentaje' => $params['pension_empleador'],
                    'valor' => $pensionEmpleador
                ],
                'total' => $totalAportesEmpleador
            ]
        ];
    }
    
    /**
     * Calcular seguridad social basado en total devengado
     * Fórmulas actualizadas 2025:
     * - Salud: (total devengado - auxilio transporte) * 8.5%
     * - Pensión: (total devengado - auxilio transporte) * 12%
     */
    public function calcularSeguridadSocialBasica($totalDevengado, $auxilioTransporte = 0) {
        // Validación
        if ($totalDevengado <= 0) {
            throw new InvalidArgumentException('Error: Total devengado debe ser mayor a 0');
        }

        // Base de cálculo: Total devengado menos auxilio de transporte
        $baseCalculo = $totalDevengado - $auxilioTransporte;
        
        // Asegurar que la base no sea negativa
        if ($baseCalculo < 0) {
            $baseCalculo = 0;
        }

        // CÁLCULO APORTES SEGURIDAD SOCIAL EMPLEADOR
        $salud = $baseCalculo * (self::PORC_SALUD_EMPLEADOR / 100);
        $pension = $baseCalculo * (self::PORC_PENSION_EMPLEADOR / 100);
        $totalSeguridadSocial = $salud + $pension;

        // CÁLCULO APORTES EMPLEADO (informativo)
        $saludEmpleado = $baseCalculo * (self::PORC_SALUD_EMPLEADO / 100);
        $pensionEmpleado = $baseCalculo * (self::PORC_PENSION_EMPLEADO / 100);
        $totalEmpleado = $saludEmpleado + $pensionEmpleado;

        return [
            'total_devengado' => $totalDevengado,
            'auxilio_transporte' => $auxilioTransporte,
            'base_calculo' => $baseCalculo,
            'aportes_empleador' => [
                'salud' => [
                    'porcentaje' => self::PORC_SALUD_EMPLEADOR,
                    'valor' => $salud,
                    'formula' => '(Total Devengado - Auxilio) × 8.5%'
                ],
                'pension' => [
                    'porcentaje' => self::PORC_PENSION_EMPLEADOR,
                    'valor' => $pension,
                    'formula' => '(Total Devengado - Auxilio) × 12%'
                ],
                'total' => $totalSeguridadSocial
            ],
            'aportes_empleado' => [
                'salud' => [
                    'porcentaje' => self::PORC_SALUD_EMPLEADO,
                    'valor' => $saludEmpleado
                ],
                'pension' => [
                    'porcentaje' => self::PORC_PENSION_EMPLEADO,
                    'valor' => $pensionEmpleado
                ],
                'total' => $totalEmpleado
            ]
        ];
    }
    
    /**
     * Calcular seguridad social por empleado basado en total devengado
     */
    public function calcularSeguridadSocialPorEmpleado($idEmpleado, $diasTrabajados = 30) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }

        // Obtener el total devengado usando DevengadoModel
        $devengadoModel = new DevengadoModel();
        $devengadoData = $devengadoModel->calcularDevengadoCompleto($idEmpleado);
        
        if (!$devengadoData) {
            throw new InvalidArgumentException("No se pudo calcular el devengado para el empleado {$empleado['nombre']} {$empleado['apellido']}");
        }

        $totalDevengado = $devengadoData['resumen']['total_devengado'];
        $auxilioTransporte = $devengadoData['conceptos']['auxilio_transporte']['valor'] ?? 0;

        // Calcular seguridad social basada en total devengado
        $calculoBasico = $this->calcularSeguridadSocialBasica($totalDevengado, $auxilioTransporte);
        
        // Obtener información adicional del empleado (roles si existen - solo informativo)
        $roles = $this->obtenerRolesEmpleado($idEmpleado);
        
        // Calcular retención en la fuente si aplica
        require_once __DIR__ . '/RetencionFuenteModel.php';
        $retencionModel = new RetencionFuenteModel();
        $retencion = null;
        $uvt = 49799; // UVT 2025
        $umbral_uvt = 95;
        $umbral_cop = $uvt * $umbral_uvt;
        
        // Usar la base de cálculo para retención
        $baseCalculo = $calculoBasico['base_calculo'];
        if ($baseCalculo > $umbral_cop) {
            try {
                $retencion = $retencionModel->calcularProcedimiento1($baseCalculo);
            } catch (Exception $e) {
                // Si falla la retención, continuar sin ella
                error_log("Error calculando retención para empleado {$idEmpleado}: " . $e->getMessage());
            }
        }
        
        // Agregar información del empleado y retención
        $resultado = $calculoBasico;
        $resultado['empleado'] = [
            'id' => $empleado['id_empleados'],
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'cargo' => $empleado['cargo'] ?? 'No especificado',
            'total_devengado' => $totalDevengado,
            'auxilio_transporte' => $auxilioTransporte,
            'roles' => $roles
        ];
        
        if ($retencion) {
            $resultado['retencion_fuente'] = $retencion;
        }
        
        return $resultado;
    }
    
    /**
     * Calcular seguridad social para todos los empleados
     */
    public function calcularSeguridadSocialTodosEmpleados($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $resultados = [];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularSeguridadSocialPorEmpleado($empleado['id_empleados'], $diasTrabajados);
                $resultados[] = $calculo;
            } catch (Exception $e) {
                // Log del error pero continuar con los demás empleados
                error_log("Error calculando seguridad social para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        return $resultados;
    }
    
    /**
     * Obtener resumen total de seguridad social
     * @param array $calculosEmpleados Array de cálculos de empleados
     * @return array Resumen total
     */
    public function obtenerResumenTotal($calculosEmpleados) {
        $totalSalud = 0;
        $totalPension = 0;
        $totalGeneral = 0;
        $totalEmpleados = count($calculosEmpleados);
        
        foreach ($calculosEmpleados as $calculo) {
            $totalSalud += $calculo['aportes_empleado']['salud']['valor'];
            $totalPension += $calculo['aportes_empleado']['pension']['valor'];
            $totalGeneral += $calculo['aportes_empleado']['total'];
        }
        
        return [
            'total_empleados' => $totalEmpleados,
            'totales' => [
                'salud' => $totalSalud,
                'pension' => $totalPension,
                'total_seguridad_social' => $totalGeneral
            ],
            'promedios' => [
                'salud' => $totalEmpleados > 0 ? $totalSalud / $totalEmpleados : 0,
                'pension' => $totalEmpleados > 0 ? $totalPension / $totalEmpleados : 0,
                'total_seguridad_social' => $totalEmpleados > 0 ? $totalGeneral / $totalEmpleados : 0
            ]
        ];
    }
    
    /**
     * Calcular seguridad social completa incluyendo ARL
     * @param int $idEmpleado ID del empleado
     * @param int $diasTrabajados Días trabajados (opcional, por defecto 30)
     * @return array Array con cálculos de seguridad social + ARL
     */
    /**
     * Calcular seguridad social completa incluyendo ARL
     * Basado en total devengado
     */
    public function calcularSeguridadSocialConARL($idEmpleado, $diasTrabajados = 30) {
        // Calcular seguridad social básica
        $calculoSeguridad = $this->calcularSeguridadSocialPorEmpleado($idEmpleado, $diasTrabajados);
        
        // Calcular ARL usando el total devengado
        $arlModel = new ARLModel();
        $totalDevengado = $calculoSeguridad['total_devengado'];
        $auxilioTransporte = $calculoSeguridad['auxilio_transporte'];
        
        // El ARL también debe calcularse sobre la base (total devengado - auxilio)
        $calculoARL = $arlModel->calcularARLPorDevengado($idEmpleado, $totalDevengado, $auxilioTransporte);
        
        // Combinar ambos cálculos
        return [
            'empleado' => $calculoSeguridad['empleado'],
            'total_devengado' => $calculoSeguridad['total_devengado'],
            'auxilio_transporte' => $calculoSeguridad['auxilio_transporte'],
            'base_calculo' => $calculoSeguridad['base_calculo'],
            'seguridad_social' => [
                'empleador' => $calculoSeguridad['aportes_empleador'],
                'empleado' => $calculoSeguridad['aportes_empleado']
            ],
            'arl' => $calculoARL,
            'totales' => [
                'seguridad_social_empleador' => $calculoSeguridad['aportes_empleador']['total'],
                'seguridad_social_empleado' => $calculoSeguridad['aportes_empleado']['total'],
                'arl' => $calculoARL['valor_arl'] ?? 0,
                'total_empleador' => $calculoSeguridad['aportes_empleador']['total'] + ($calculoARL['valor_arl'] ?? 0),
                'total_empleado' => $calculoSeguridad['aportes_empleado']['total']
            ],
            'retencion_fuente' => $calculoSeguridad['retencion_fuente'] ?? null
        ];
    }
    
    /**
     * Calcular seguridad social + ARL para todos los empleados
     */
    public function calcularSeguridadSocialConARLTodos($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $resultados = [];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularSeguridadSocialConARL($empleado['id_empleados'], $diasTrabajados);
                $resultados[] = $calculo;
            } catch (Exception $e) {
                // Log del error pero continuar con los demás empleados
                error_log("Error calculando seguridad social + ARL para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        return $resultados;
    }
    
    /**
     * Obtener resumen total incluyendo ARL
     * @param array $calculosEmpleados Array de cálculos con ARL
     * @return array Resumen total incluyendo ARL
     */
    public function obtenerResumenTotalConARL($calculosEmpleados) {
        $totalSalud = 0;
        $totalPension = 0;
        $totalARL = 0;
        $totalGeneral = 0;
        $totalEmpleados = count($calculosEmpleados);
        
        // Contadores por nivel de riesgo
        $riesgoPorNivel = [];
        
        foreach ($calculosEmpleados as $calculo) {
            $totalSalud += $calculo['seguridad_social']['empleado']['salud']['valor'] ?? 0;
            $totalPension += $calculo['seguridad_social']['empleado']['pension']['valor'] ?? 0;
            $totalARL += $calculo['arl']['valor_arl'] ?? 0;
            $totalGeneral += $calculo['totales']['total_empleado'] ?? 0;
            
            // Contar por nivel de riesgo
            $codigoRiesgo = $calculo['arl']['codigo_riesgo'] ?? 2;
            if (!isset($riesgoPorNivel[$codigoRiesgo])) {
                $riesgoPorNivel[$codigoRiesgo] = [
                    'cantidad' => 0,
                    'nivel_info' => $calculo['arl']['nivel_riesgo']['descripcion'] ?? 'Riesgo ' . $codigoRiesgo,
                    'total_arl' => 0
                ];
            }
            $riesgoPorNivel[$codigoRiesgo]['cantidad']++;
            $riesgoPorNivel[$codigoRiesgo]['total_arl'] += $calculo['arl']['valor_arl'] ?? 0;
        }
        
        return [
            'total_empleados' => $totalEmpleados,
            'totales' => [
                'salud' => $totalSalud,
                'pension' => $totalPension,
                'arl' => $totalARL,
                'total_seguridad_social' => $totalGeneral
            ],
            'promedios' => [
                'salud' => $totalEmpleados > 0 ? $totalSalud / $totalEmpleados : 0,
                'pension' => $totalEmpleados > 0 ? $totalPension / $totalEmpleados : 0,
                'arl' => $totalEmpleados > 0 ? $totalARL / $totalEmpleados : 0,
                'total_seguridad_social' => $totalEmpleados > 0 ? $totalGeneral / $totalEmpleados : 0
            ],
            'distribucion_riesgo' => $riesgoPorNivel
        ];
    }

    /**
     * Calcular seguridad social CON ARL para todos los empleados
     * @param int $diasTrabajados Días trabajados en el mes
     * @return array Array con los cálculos de todos los empleados incluyendo ARL
     */
    public function calcularSeguridadSocialConARLTodosEmpleados($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $resultados = [];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularSeguridadSocialConARL($empleado['id_empleados'], $diasTrabajados);
                $resultados[] = $calculo;
            } catch (Exception $e) {
                // Log del error pero continuar con los demás empleados
                error_log("Error calculando seguridad social CON ARL para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        return $resultados;
    }
    
    /**
     * Calcular seguridad social CON ARL para todos los empleados - VERSIÓN TEMPORAL SIN ARL
     * @param int $diasTrabajados Días trabajados en el mes
     * @return array Array con los cálculos de todos los empleados
     */
    public function calcularSeguridadSocialConARLTodosEmpleadosTemporal($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
        $resultados = [];
        
        foreach ($empleados as $empleado) {
            try {
                // Solo calcular seguridad social básica (sin ARL)
                $calculo = $this->calcularSeguridadSocialPorEmpleado($empleado['id_empleados'], $diasTrabajados);
                
                // Agregar formato compatible con la vista
                $resultado = [
                    'empleado' => $calculo['empleado'],
                    'total_devengado' => $calculo['total_devengado'],
                    'auxilio_transporte' => $calculo['auxilio_transporte'],
                    'base_calculo' => $calculo['base_calculo'],
                    'seguridad_social' => [
                        'empleador' => $calculo['aportes_empleador'],
                        'empleado' => $calculo['aportes_empleado']
                    ],
                    'arl' => [
                        'valor_arl' => 0,
                        'codigo_riesgo' => 2,
                        'nivel_riesgo' => ['descripcion' => 'Clase II - Bajo']
                    ],
                    'totales' => [
                        'seguridad_social_empleador' => $calculo['aportes_empleador']['total'],
                        'seguridad_social_empleado' => $calculo['aportes_empleado']['total'],
                        'arl' => 0,
                        'total_empleador' => $calculo['aportes_empleador']['total'],
                        'total_empleado' => $calculo['aportes_empleado']['total']
                    ],
                    'retencion_fuente' => $calculo['retencion_fuente'] ?? null
                ];
                
                $resultados[] = $resultado;
            } catch (Exception $e) {
                // Log del error pero continuar con los demás empleados
                error_log("Error calculando seguridad social para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        return $resultados;
    }
    
    private function filtrarEmpleadosEspeciales($empleados) {
        return array_filter($empleados, function($emp) {
            // Filtrar empleados del sistema por ID (IDs 1, 2, 3)
            return !in_array($emp['id_empleados'], [1, 2, 3]);
        });
    }
}