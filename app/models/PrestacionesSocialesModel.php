<?php
require_once 'Empleado.php';
require_once 'DevengadoModel.php';

class PrestacionesSocialesModel extends Model {
    
    // Constantes para cálculos mensuales de prestaciones sociales 2025
    const SALARIO_MINIMO = 1423000;
    
    // Porcentajes mensuales para prestaciones sociales
    const PORC_CESANTIAS_MENSUAL = 8.33; // 8.33% anual ÷ 12 meses = 0.694% mensual  
    const PORC_INTERESES_MENSUAL = 12.0; // 12% anual sobre cesantías acumuladas
    const PORC_PRIMA_MENSUAL = 8.33; // 8.33% anual ÷ 12 meses = 0.694% mensual
    const PORC_VACACIONES_MENSUAL = 4.17; // 4.17% anual ÷ 12 meses = 0.347% mensual
    
    /**
     * Calcular cesantías mensuales para un empleado
     * Fórmula mensual: (Salario + Aux. Transporte) ÷ 12
     */
    public function calcularCesantiasMensuales($salarioMensual, $auxilioTransporte) {
        $baseCalculo = $salarioMensual + $auxilioTransporte;
        $cesantiasMensuales = $baseCalculo / 12; // Un doceavo del salario mensual
        
        return [
            'salario_mensual' => $salarioMensual,
            'auxilio_transporte' => $auxilioTransporte,
            'base_calculo' => $baseCalculo,
            'valor_cesantias' => $cesantiasMensuales,
            'formula' => "(Salario + Aux. Trans.) ÷ 12",
            'tipo_calculo' => 'mensual'
        ];
    }
    
    /**
     * Calcular intereses sobre cesantías mensuales
     * Fórmula: Cesantías acumuladas × 12% ÷ 12 meses = 1% mensual
     */
    public function calcularInteresesCesantiasMensuales($cesantiasAcumuladas) {
        $interesesMensuales = $cesantiasAcumuladas * 0.01; // 1% mensual
        
        return [
            'cesantias_acumuladas' => $cesantiasAcumuladas,
            'valor_intereses' => $interesesMensuales,
            'formula' => "Cesantías Acumuladas × 1% mensual",
            'porcentaje_aplicado' => 1.0
        ];
    }
    
    /**
     * Calcular prima de servicios mensual
     * Fórmula: (Salario + Aux. Transporte) ÷ 12
     */
    public function calcularPrimaServiciosMensual($salarioMensual, $auxilioTransporte) {
        $baseCalculo = $salarioMensual + $auxilioTransporte;
        $primaMensual = $baseCalculo / 12; // Un doceavo del salario mensual
        
        return [
            'salario_mensual' => $salarioMensual,
            'auxilio_transporte' => $auxilioTransporte,
            'base_calculo' => $baseCalculo,
            'valor_prima' => $primaMensual,
            'formula' => "(Salario + Aux. Trans.) ÷ 12",
            'tipo_calculo' => 'mensual'
        ];
    }
    
    /**
     * Calcular vacaciones mensuales
     * Fórmula: Salario ÷ 24 (sin incluir auxilio de transporte)
     */
    public function calcularVacacionesMensuales($salarioMensual) {
        $vacacionesMensuales = $salarioMensual / 24; // Salario ÷ 24 meses (2 años)
        
        return [
            'salario_mensual' => $salarioMensual,
            'valor_vacaciones' => $vacacionesMensuales,
            'formula' => "Salario ÷ 24",
            'tipo_calculo' => 'mensual',
            'nota' => 'Vacaciones sin auxilio de transporte',
            'incluye_auxilio_transporte' => false
        ];
    }

    /**
     * Calcular todas las prestaciones sociales mensuales para un empleado
     */
    public function calcularPrestacionesCompletas($idEmpleado) {
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        $salarioMensual = floatval($empleado['sueldo_actual'] ?? 0);
        if ($salarioMensual <= 0) {
            throw new InvalidArgumentException("El empleado {$empleado['nombre']} {$empleado['apellido']} no tiene un salario asignado");
        }
        
        $auxilioTransporte = $empleadoModel->getAuxilioTransporte($salarioMensual);
        
        // Calcular cesantías acumuladas (para el cálculo de intereses)
        // En un sistema real, esto vendría de la base de datos
        // Por ahora usamos el cálculo mensual × meses trabajados (asumimos 1 mes)
        $cesantiasMensuales = $this->calcularCesantiasMensuales($salarioMensual, $auxilioTransporte);
        $cesantiasAcumuladas = $cesantiasMensuales['valor_cesantias']; // Para un mes
        
        // Calcular cada prestación mensual
        $cesantias = $cesantiasMensuales;
        $intereses = $this->calcularInteresesCesantiasMensuales($cesantiasAcumuladas);
        $prima = $this->calcularPrimaServiciosMensual($salarioMensual, $auxilioTransporte);
        $vacaciones = $this->calcularVacacionesMensuales($salarioMensual);
        
        // Calcular totales
        $totalPrestaciones = $cesantias['valor_cesantias'] + $intereses['valor_intereses'] + 
                           $prima['valor_prima'] + $vacaciones['valor_vacaciones'];
        
        // Calcular total devengado básico (salario + auxilio para este contexto)
        $totalDevengadoBasico = $salarioMensual + $auxilioTransporte;
        
        return [
            'empleado' => [
                'id' => $empleado['id_empleados'],
                'nombre' => $empleado['nombre'],
                'apellido' => $empleado['apellido'],
                'salario_mensual' => $salarioMensual,
                'auxilio_transporte' => $auxilioTransporte,
                'total_devengado' => $totalDevengadoBasico
            ],
            'parametros' => [
                'periodo' => 'Mensual',
                'salario_minimo' => self::SALARIO_MINIMO,
                'fecha_calculo' => date('Y-m-d H:i:s')
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
        $empleados = $empleadoModel->getAllWithRoles();
        $empleados = $this->filtrarEmpleadosEspeciales($empleados);
        
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
                $calculo = $this->calcularPrestacionesCompletas($empleado['id_empleados']);
                
                // Agregar información para compatibilidad con la vista
                $calculo['parametros']['dias_trabajados'] = 'Mensual'; // Ya no usamos días
                
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
    
    /**
     * Filtrar empleados especiales (placeholders)
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