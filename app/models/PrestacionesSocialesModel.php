<?php
class PrestacionesSocialesModel extends Model {
    
    // Constantes para cálculos de prestaciones sociales 2025
    const SALARIO_MINIMO = 1423000;
    const DIAS_LABORALES_ANIO = 360;
    const INTERES_CESANTIAS = 0.12; // 12% anual
    
    /**
     * Calcular cesantías para un empleado
     * Fórmula: (Salario mensual × Días trabajados) ÷ 360
     */
    public function calcularCesantias($salarioMensual, $diasTrabajados = 360, $auxilioTransporte = 0) {
        $salarioBase = $salarioMensual + $auxilioTransporte;
        $cesantias = ($salarioBase * $diasTrabajados) / self::DIAS_LABORALES_ANIO;
        
        return [
            'salario_base' => $salarioBase,
            'dias_trabajados' => $diasTrabajados,
            'valor_cesantias' => $cesantias,
            'formula' => "($salarioBase × $diasTrabajados) ÷ 360",
            'incluye_auxilio_transporte' => $auxilioTransporte > 0
        ];
    }
    
    /**
     * Calcular intereses sobre cesantías
     * Fórmula: Cesantías × 12% × (Días trabajados ÷ 360)
     */
    public function calcularInteresesCesantias($valorCesantias, $diasTrabajados = 360) {
        $factorTiempo = $diasTrabajados / self::DIAS_LABORALES_ANIO;
        $intereses = $valorCesantias * self::INTERES_CESANTIAS * $factorTiempo;
        
        return [
            'valor_cesantias' => $valorCesantias,
            'porcentaje_interes' => self::INTERES_CESANTIAS * 100,
            'factor_tiempo' => $factorTiempo,
            'valor_intereses' => $intereses,
            'formula' => "$valorCesantias × 12% × ($diasTrabajados ÷ 360)"
        ];
    }
    
    /**
     * Calcular prima de servicios
     * Fórmula: (Salario mensual × Días trabajados) ÷ 360
     */
    public function calcularPrimaServicios($salarioMensual, $diasTrabajados = 360, $auxilioTransporte = 0) {
        $salarioBase = $salarioMensual + $auxilioTransporte;
        $prima = ($salarioBase * $diasTrabajados) / self::DIAS_LABORALES_ANIO;
        
        return [
            'salario_base' => $salarioBase,
            'dias_trabajados' => $diasTrabajados,
            'valor_prima' => $prima,
            'formula' => "($salarioBase × $diasTrabajados) ÷ 360",
            'incluye_auxilio_transporte' => $auxilioTransporte > 0
        ];
    }
    
    /**
     * Calcular vacaciones
     * Fórmula: (Salario mensual × Días trabajados) ÷ 720 (solo salario básico, sin auxilio)
     */
    public function calcularVacaciones($salarioMensual, $diasTrabajados = 360) {
        // Las vacaciones NO incluyen auxilio de transporte
        $vacaciones = ($salarioMensual * $diasTrabajados) / 720; // 720 días = 2 años de factor
        
        return [
            'salario_base' => $salarioMensual,
            'dias_trabajados' => $diasTrabajados,
            'valor_vacaciones' => $vacaciones,
            'formula' => "($salarioMensual × $diasTrabajados) ÷ 720",
            'incluye_auxilio_transporte' => false,
            'nota' => 'Las vacaciones NO incluyen auxilio de transporte'
        ];
    }
    
    /**
     * Calcular todas las prestaciones sociales para un empleado
     */
    public function calcularPrestacionesCompletas($idEmpleado, $diasTrabajados = 360) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        $salarioMensual = floatval($empleado['sueldo_actual'] ?? 0);
        if ($salarioMensual <= 0) {
            throw new InvalidArgumentException("El empleado {$empleado['nombre']} {$empleado['apellido']} no tiene un salario asignado");
        }
        
        // Calcular auxilio de transporte
        $auxilioTransporte = $empleadoModel->getAuxilioTransporte($salarioMensual);
        
        // Calcular cada prestación
        $cesantias = $this->calcularCesantias($salarioMensual, $diasTrabajados, $auxilioTransporte);
        $intereses = $this->calcularInteresesCesantias($cesantias['valor_cesantias'], $diasTrabajados);
        $prima = $this->calcularPrimaServicios($salarioMensual, $diasTrabajados, $auxilioTransporte);
        $vacaciones = $this->calcularVacaciones($salarioMensual, $diasTrabajados);
        
        // Calcular totales
        $totalPrestaciones = $cesantias['valor_cesantias'] + $intereses['valor_intereses'] + 
                           $prima['valor_prima'] + $vacaciones['valor_vacaciones'];
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'salario_mensual' => $salarioMensual,
                'auxilio_transporte' => $auxilioTransporte
            ],
            'parametros' => [
                'dias_trabajados' => $diasTrabajados,
                'salario_minimo' => self::SALARIO_MINIMO,
                'interes_cesantias' => self::INTERES_CESANTIAS * 100 . '%'
            ],
            'prestaciones' => [
                'cesantias' => $cesantias,
                'intereses_cesantias' => $intereses,
                'prima_servicios' => $prima,
                'vacaciones' => $vacaciones
            ],
            'resumen' => [
                'total_prestaciones' => $totalPrestaciones,
                'desglose' => [
                    'cesantias' => $cesantias['valor_cesantias'],
                    'intereses' => $intereses['valor_intereses'],
                    'prima' => $prima['valor_prima'],
                    'vacaciones' => $vacaciones['valor_vacaciones']
                ]
            ]
        ];
    }
    
    /**
     * Calcular prestaciones sociales para todos los empleados
     */
    public function calcularPrestacionesTodosEmpleados($diasTrabajados = 360) {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAll();
        
        $resultados = [];
        $totales = [
            'cesantias' => 0,
            'intereses' => 0,
            'prima' => 0,
            'vacaciones' => 0,
            'total_general' => 0
        ];
        
        foreach ($empleados as $empleado) {
            try {
                $calculo = $this->calcularPrestacionesCompletas($empleado['id_empleados'], $diasTrabajados);
                $resultados[] = $calculo;
                
                // Sumar totales
                $totales['cesantias'] += $calculo['prestaciones']['cesantias']['valor_cesantias'];
                $totales['intereses'] += $calculo['prestaciones']['intereses_cesantias']['valor_intereses'];
                $totales['prima'] += $calculo['prestaciones']['prima_servicios']['valor_prima'];
                $totales['vacaciones'] += $calculo['prestaciones']['vacaciones']['valor_vacaciones'];
                $totales['total_general'] += $calculo['resumen']['total_prestaciones'];
                
            } catch (Exception $e) {
                error_log("Error calculando prestaciones para empleado {$empleado['id_empleados']}: " . $e->getMessage());
            }
        }
        
        return [
            'empleados' => $resultados,
            'totales_empresa' => $totales,
            'total_empleados' => count($resultados),
            'promedios' => [
                'cesantias' => count($resultados) > 0 ? $totales['cesantias'] / count($resultados) : 0,
                'intereses' => count($resultados) > 0 ? $totales['intereses'] / count($resultados) : 0,
                'prima' => count($resultados) > 0 ? $totales['prima'] / count($resultados) : 0,
                'vacaciones' => count($resultados) > 0 ? $totales['vacaciones'] / count($resultados) : 0,
                'total_general' => count($resultados) > 0 ? $totales['total_general'] / count($resultados) : 0
            ]
        ];
    }
    
    /**
     * Guardar cálculo de prestaciones en base de datos
     */
    public function guardarPrestaciones($calculoPrestaciones) {
        try {
            $this->db->beginTransaction();
            
            // Insertar en prestaciones_sociales
            $sql = "INSERT INTO prestaciones_sociales (valor, total, total_devengado_id) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $calculoPrestaciones['resumen']['total_prestaciones'],
                $calculoPrestaciones['resumen']['total_prestaciones'],
                null // TODO: vincular con total_devengado si es necesario
            ]);
            
            $prestacionesId = $this->db->lastInsertId();
            
            // Insertar cesantías
            $sqlCesantias = "INSERT INTO cesantias (tipo, prestaciones_sociales_id) VALUES (?, ?)";
            $stmtCesantias = $this->db->prepare($sqlCesantias);
            $stmtCesantias->execute([
                'Cesantías anuales',
                $prestacionesId
            ]);
            
            // Insertar intereses
            $sqlIntereses = "INSERT INTO intereses (total, prestaciones_sociales_id) VALUES (?, ?)";
            $stmtIntereses = $this->db->prepare($sqlIntereses);
            $stmtIntereses->execute([
                $calculoPrestaciones['prestaciones']['intereses_cesantias']['valor_intereses'],
                $prestacionesId
            ]);
            
            // Insertar prima
            $sqlPrima = "INSERT INTO prima (valor_total, dia, mes, anio, prestaciones_sociales_id) VALUES (?, ?, ?, ?, ?)";
            $stmtPrima = $this->db->prepare($sqlPrima);
            $stmtPrima->execute([
                $calculoPrestaciones['prestaciones']['prima_servicios']['valor_prima'],
                date('d'),
                date('m'),
                date('Y'),
                $prestacionesId
            ]);
            
            // Insertar vacaciones
            $sqlVacaciones = "INSERT INTO vacaciones (valor_total, dia, mes, anio, prestaciones_sociales_id) VALUES (?, ?, ?, ?, ?)";
            $stmtVacaciones = $this->db->prepare($sqlVacaciones);
            $stmtVacaciones->execute([
                $calculoPrestaciones['prestaciones']['vacaciones']['valor_vacaciones'],
                date('d'),
                date('m'),
                date('Y'),
                $prestacionesId
            ]);
            
            $this->db->commit();
            return $prestacionesId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}