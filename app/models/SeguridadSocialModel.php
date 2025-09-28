<?php

require_once __DIR__ . '/ARLModel.php';

/**
 * Modelo para cálculos de Seguridad Social
 * Basado en el PROM proporcionado para cálculo de aportes de salud y pensión
 */
class SeguridadSocialModel extends Model {
    
    // CONSTANTES PORCENTAJES SEGURIDAD SOCIAL
    const PORC_SALUD_EMPLEADO = 4.0;
    const PORC_PENSION_EMPLEADO = 4.0;
    const PORC_SALUD_EMPLEADOR = 8.5;
    const PORC_PENSION_EMPLEADOR = 12.0;
    
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

        // CÁLCULO DEDUCCIONES EMPLEADO (Seguridad Social)
        $saludEmpleado = $totalDevengado * (self::PORC_SALUD_EMPLEADO / 100);
        $pensionEmpleado = $totalDevengado * (self::PORC_PENSION_EMPLEADO / 100);
        $totalDeduccionesEmpleado = $saludEmpleado + $pensionEmpleado;

        // CÁLCULO APORTES EMPLEADOR (Seguridad Social)
        $saludEmpleador = $totalDevengado * (self::PORC_SALUD_EMPLEADOR / 100);
        $pensionEmpleador = $totalDevengado * (self::PORC_PENSION_EMPLEADOR / 100);
        $totalAportesEmpleador = $saludEmpleador + $pensionEmpleador;

        return [
            'total_devengado' => $totalDevengado,
            'deducciones_empleado' => [
                'salud' => [
                    'porcentaje' => self::PORC_SALUD_EMPLEADO,
                    'valor' => $saludEmpleado
                ],
                'pension' => [
                    'porcentaje' => self::PORC_PENSION_EMPLEADO,
                    'valor' => $pensionEmpleado
                ],
                'total' => $totalDeduccionesEmpleado
            ],
            'aportes_empleador' => [
                'salud' => [
                    'porcentaje' => self::PORC_SALUD_EMPLEADOR,
                    'valor' => $saludEmpleador
                ],
                'pension' => [
                    'porcentaje' => self::PORC_PENSION_EMPLEADOR,
                    'valor' => $pensionEmpleador
                ],
                'total' => $totalAportesEmpleador
            ]
        ];
    }
    
    /**
     * Calcular seguridad social básica (solo empleado) basado en el segundo PROM
     * @param float $salarioBase Salario base del empleado
     * @param int $diasTrabajados Días trabajados en el período
     * @return array Array con los cálculos básicos de seguridad social
     */
    public function calcularSeguridadSocialBasica($salarioBase, $diasTrabajados) {
        // Validación
        if ($salarioBase <= 0 || $diasTrabajados <= 0) {
            throw new InvalidArgumentException('Error: Datos inválidos - salario base y días trabajados deben ser mayores a 0');
        }

        // CÁLCULO SALARIO PROPORCIONAL
        $salarioProporcional = ($salarioBase * $diasTrabajados) / 30;

        // CÁLCULO APORTES SEGURIDAD SOCIAL
        $salud = $salarioProporcional * (self::PORC_SALUD_EMPLEADO / 100);
        $pension = $salarioProporcional * (self::PORC_PENSION_EMPLEADO / 100);
        $totalSeguridadSocial = $salud + $pension;

        return [
            'salario_base' => $salarioBase,
            'dias_trabajados' => $diasTrabajados,
            'salario_proporcional' => $salarioProporcional,
            'aportes_empleado' => [
                'salud' => [
                    'porcentaje' => self::PORC_SALUD_EMPLEADO,
                    'valor' => $salud
                ],
                'pension' => [
                    'porcentaje' => self::PORC_PENSION_EMPLEADO,
                    'valor' => $pension
                ],
                'total' => $totalSeguridadSocial
            ]
        ];
    }
    
    /**
     * Calcular seguridad social por empleado según su salario individual
     * @param int $idEmpleado ID del empleado
     * @param int $diasTrabajados Días trabajados (opcional, por defecto 30)
     * @return array Array con los cálculos de seguridad social del empleado
     */
    public function calcularSeguridadSocialPorEmpleado($idEmpleado, $diasTrabajados = 30) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        // Usar directamente el sueldo_actual del empleado
        $salarioBase = 0;
        
        if (isset($empleado['sueldo_actual']) && $empleado['sueldo_actual'] > 0) {
            $salarioBase = floatval($empleado['sueldo_actual']);
        } else {
            throw new InvalidArgumentException("El empleado {$empleado['nombre']} {$empleado['apellido']} no tiene un salario asignado");
        }
        
        // Calcular seguridad social básica
        $calculoBasico = $this->calcularSeguridadSocialBasica($salarioBase, $diasTrabajados);
        
        // Obtener información adicional del empleado (roles si existen - solo informativo)
        $roles = $this->obtenerRolesEmpleado($idEmpleado);
        
        // Calcular retención en la fuente si aplica
        require_once __DIR__ . '/RetencionFuenteModel.php';
        $retencionModel = new RetencionFuenteModel();
        $retencion = null;
        $uvt = 49799; // UVT 2025
        $umbral_uvt = 95;
        $umbral_cop = $uvt * $umbral_uvt;
        if ($salarioBase > $umbral_cop) {
            try {
                $retencion = $retencionModel->calcularProcedimiento1($salarioBase);
            } catch (Exception $e) {
                // Si falla la retención, continuar sin ella
                error_log("Error calculando retención para empleado {$idEmpleado}: " . $e->getMessage());
            }
        }
        
        // Agregar información del empleado y retención
        $calculoBasico['empleado'] = [
            'id' => $empleado['id_empleados'],
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'roles' => $roles,
            'salario_asignado' => $salarioBase,
            'tiene_salario_individual' => true
        ];
        $calculoBasico['retencion_fuente'] = $retencion;
        
        return $calculoBasico;
    }
    
    /**
     * Obtener roles de un empleado (informativo, no afecta cálculos)
     * @param int $idEmpleado ID del empleado
     * @return array Array con los roles del empleado
     */
    private function obtenerRolesEmpleado($idEmpleado) {
        try {
            $userModel = new User();
            $usuario = $userModel->getByEmpleadoId($idEmpleado);
            
            if ($usuario) {
                $rolHasUserModel = new RolHasUser();
                return $rolHasUserModel->getRolesByUserId($usuario['id_doc']);
            }
            
            return ['empleado']; // Rol por defecto para información
        } catch (Exception $e) {
            return ['empleado'];
        }
        
        return $calculoBasico;
    }
    
    /**
     * Calcular seguridad social para todos los empleados
     * @param int $diasTrabajados Días trabajados (opcional, por defecto 30)
     * @return array Array con los cálculos de todos los empleados
     */
    public function calcularSeguridadSocialTodosEmpleados($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAll();
        
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
    public function calcularSeguridadSocialConARL($idEmpleado, $diasTrabajados = 30) {
        // Calcular seguridad social básica
        $calculoSeguridad = $this->calcularSeguridadSocialPorEmpleado($idEmpleado, $diasTrabajados);
        
        // Calcular ARL
        $arlModel = new ARLModel();
        $calculoARL = $arlModel->calcularARLEmpleado($idEmpleado, $diasTrabajados);
        
        // Combinar ambos cálculos
        return [
            'empleado' => $calculoSeguridad['empleado'],
            'salario_base' => $calculoSeguridad['salario_base'],
            'dias_trabajados' => $diasTrabajados,
            'salario_proporcional' => $calculoSeguridad['salario_proporcional'],
            'seguridad_social' => $calculoSeguridad['aportes_empleado'],
            'arl' => [
                'codigo_riesgo' => $calculoARL['codigo_riesgo'],
                'nivel_riesgo' => $calculoARL['nivel_riesgo'],
                'porcentaje' => $calculoARL['porcentaje_arl'],
                'valor' => $calculoARL['aporte_arl']
            ],
            'totales' => [
                'seguridad_social' => $calculoSeguridad['aportes_empleado']['total'],
                'arl' => $calculoARL['aporte_arl'],
                'total_deducciones' => $calculoSeguridad['aportes_empleado']['total'] + $calculoARL['aporte_arl']
            ]
        ];
    }
    
    /**
     * Calcular seguridad social + ARL para todos los empleados
     * @param int $diasTrabajados Días trabajados (opcional, por defecto 30)
     * @return array Array con todos los cálculos incluyendo ARL
     */
    public function calcularSeguridadSocialConARLTodos($diasTrabajados = 30) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAll();
        
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
            $totalSalud += $calculo['seguridad_social']['salud']['valor'];
            $totalPension += $calculo['seguridad_social']['pension']['valor'];
            $totalARL += $calculo['arl']['valor'];
            $totalGeneral += $calculo['totales']['total_deducciones'];
            
            // Contar por nivel de riesgo
            $codigoRiesgo = $calculo['arl']['codigo_riesgo'];
            if (!isset($riesgoPorNivel[$codigoRiesgo])) {
                $riesgoPorNivel[$codigoRiesgo] = [
                    'cantidad' => 0,
                    'nivel_info' => $calculo['arl']['nivel_riesgo'],
                    'total_arl' => 0
                ];
            }
            $riesgoPorNivel[$codigoRiesgo]['cantidad']++;
            $riesgoPorNivel[$codigoRiesgo]['total_arl'] += $calculo['arl']['valor'];
        }
        
        return [
            'total_empleados' => $totalEmpleados,
            'totales' => [
                'salud' => $totalSalud,
                'pension' => $totalPension,
                'arl' => $totalARL,
                'seguridad_social' => $totalSalud + $totalPension,
                'total_general' => $totalGeneral
            ],
            'promedios' => [
                'salud' => $totalEmpleados > 0 ? $totalSalud / $totalEmpleados : 0,
                'pension' => $totalEmpleados > 0 ? $totalPension / $totalEmpleados : 0,
                'arl' => $totalEmpleados > 0 ? $totalARL / $totalEmpleados : 0,
                'total_general' => $totalEmpleados > 0 ? $totalGeneral / $totalEmpleados : 0
            ],
            'distribucion_riesgo' => $riesgoPorNivel
        ];
    }
}