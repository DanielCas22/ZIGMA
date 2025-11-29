<?php
namespace App\Models;

use PDO;

/**
 * Modelo para cálculos de ARL (Administradora de Riesgos Laborales)
 * Basado en el PROM proporcionado para cá    /**
     * Obtener estadísticas de riesgos por empleadosL según nivel de riesgo
 */
class ARLModel extends Model {
    
    // CONSTANTES DE PORCENTAJES DE RIESGO ARL (según tabla oficial)
    const RIESGO_I = 0.522;    // Clase I - Mínimo
    const RIESGO_II = 1.044;   // Clase II - Bajo  
    const RIESGO_III = 2.436;  // Clase III - Medio
    const RIESGO_IV = 4.350;   // Clase IV - Alto
    const RIESGO_V = 6.960;    // Clase V - Máximo
    
    // Mapeo de niveles de riesgo
    const NIVELES_RIESGO = [
        1 => ['codigo' => 'I', 'porcentaje' => self::RIESGO_I, 'descripcion' => 'Clase I - Mínimo'],
        2 => ['codigo' => 'II', 'porcentaje' => self::RIESGO_II, 'descripcion' => 'Clase II - Bajo'],
        3 => ['codigo' => 'III', 'porcentaje' => self::RIESGO_III, 'descripcion' => 'Clase III - Medio'],
        4 => ['codigo' => 'IV', 'porcentaje' => self::RIESGO_IV, 'descripcion' => 'Clase IV - Alto'],
        5 => ['codigo' => 'V', 'porcentaje' => self::RIESGO_V, 'descripcion' => 'Clase V - Máximo']
    ];
    
    protected $table = 'empleados_riesgo_arl';
    
    /**
     * Calcular ARL basado en total devengado
     * ARL se calcula sobre la base: (total devengado - auxilio transporte)
     */
    public function calcularARLPorDevengado($idEmpleado, $totalDevengado, $auxilioTransporte = 0) {
        // Obtener información completa del riesgo desde la BD
        $riesgoInfo = $this->obtenerRiesgoCompletoEmpleado($idEmpleado);
        
        // Validaciones
        if ($totalDevengado <= 0) {
            throw new InvalidArgumentException('Error: El total devengado debe ser mayor a 0');
        }
        
        // Base de cálculo: Total devengado menos auxilio de transporte
        $baseCalculo = $totalDevengado - $auxilioTransporte;
        
        // Asegurar que la base no sea negativa
        if ($baseCalculo < 0) {
            $baseCalculo = 0;
        }
        
        // Calcular ARL usando el porcentaje de la BD
        $valorARL = $baseCalculo * ($riesgoInfo['porcentaje'] / 100);
        
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        return [
            'empleado_id' => $idEmpleado,
            'empleado_nombre' => $empleado ? $empleado['nombre'] . ' ' . $empleado['apellido'] : 'Desconocido',
            'total_devengado' => $totalDevengado,
            'auxilio_transporte' => $auxilioTransporte,
            'base_calculo' => $baseCalculo,
            'codigo_riesgo' => $riesgoInfo['codigo_riesgo'],
            'clase_riesgo' => $riesgoInfo['clase_riesgo'],
            'nivel_riesgo' => [
                'descripcion' => $riesgoInfo['descripcion'],
                'porcentaje' => $riesgoInfo['porcentaje']
            ],
            'valor_arl' => $valorARL,
            'formula' => "($" . number_format($baseCalculo) . " × {$riesgoInfo['porcentaje']}%) = $" . number_format($valorARL),
            'fecha_calculo' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Calcular ARL básico según el PROM
     * Mantiene compatibilidad con el sistema anterior
     * @param float $salarioBase Salario base del empleado
     * @param int $diasTrabajados Días trabajados en el período
     * @param int $codigoRiesgo Código de riesgo (1-5)
     * @return array Array con los cálculos de ARL
     */
    public function calcularARL($salarioBase, $diasTrabajados, $codigoRiesgo) {
        // Validaciones según PROM
        if ($salarioBase <= 0 || $diasTrabajados <= 0) {
            throw new InvalidArgumentException('Error: El salario base y los días trabajados deben ser mayores a 0');
        }
        
        if ($codigoRiesgo < 1 || $codigoRiesgo > 5) {
            throw new InvalidArgumentException('Error: Código de riesgo inválido. Debe ser entre 1 y 5');
        }
        
        // Obtener porcentaje según riesgo
        $nivelRiesgo = self::NIVELES_RIESGO[$codigoRiesgo];
        $porcentajeARL = $nivelRiesgo['porcentaje'];
        
        // CÁLCULO DEL SALARIO PROPORCIONAL (según PROM)
        $salarioProporcional = ($salarioBase * $diasTrabajados) / 30;
        
        // CÁLCULO DEL APORTE A LA ARL
        $aporteARL = $salarioProporcional * ($porcentajeARL / 100);
        
        return [
            'salario_base' => $salarioBase,
            'dias_trabajados' => $diasTrabajados,
            'salario_proporcional' => $salarioProporcional,
            'codigo_riesgo' => $codigoRiesgo,
            'nivel_riesgo' => $nivelRiesgo,
            'porcentaje_arl' => $porcentajeARL,
            'aporte_arl' => $aporteARL
        ];
    }
    
    /**
     * Obtener información de un nivel de riesgo
     * @param int $codigoRiesgo Código de riesgo (1-5)
     * @return array|null Información del nivel de riesgo
     */
    public function getNivelRiesgo($codigoRiesgo) {
        return isset(self::NIVELES_RIESGO[$codigoRiesgo]) ? self::NIVELES_RIESGO[$codigoRiesgo] : null;
    }
    
    /**
     * Obtener todos los niveles de riesgo disponibles
     * @return array Array con todos los niveles de riesgo
     */
    public function getTodosNivelesRiesgo() {
        return self::NIVELES_RIESGO;
    }
    
    /**
     * Asignar nivel de riesgo a un empleado
     * @param int $idEmpleado ID del empleado
     * @param int $codigoRiesgo Código de riesgo (1-5)
     * @return bool True si se asignó correctamente
     */
    public function asignarRiesgoEmpleado($idEmpleado, $codigoRiesgo) {
        if ($codigoRiesgo < 1 || $codigoRiesgo > 5) {
            throw new InvalidArgumentException('Código de riesgo inválido');
        }
        // Validar que el empleado exista antes de asignar riesgo
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        if (!$empleado) {
            throw new \Exception('El empleado no existe. No se puede asignar riesgo ARL.');
        }
        // Verificar si ya existe un registro
        $existente = $this->getRiesgoEmpleado($idEmpleado);
        
        if ($existente) {
            // Actualizar
            $sql = "UPDATE empleados_riesgo_arl SET codigo_riesgo = ?, fecha_actualizacion = NOW() WHERE id_empleado = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$codigoRiesgo, $idEmpleado]);
        } else {
            // Insertar nuevo
            $sql = "INSERT INTO empleados_riesgo_arl (id_empleado, codigo_riesgo, fecha_asignacion) VALUES (?, ?, NOW())";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$idEmpleado, $codigoRiesgo]);
        }
    }
    
    /**
     * Obtener el código de riesgo de un empleado
     */
    public function obtenerCodigoRiesgoEmpleado($idEmpleado) {
        try {
            $sql = "SELECT era.codigo_riesgo, nra.valor_inicial as porcentaje
                    FROM empleados_riesgo_arl era
                    JOIN niveles_riesgo_arl nra ON era.codigo_riesgo = nra.codigo
                    WHERE era.id_empleado = ? AND era.activo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idEmpleado]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                return $resultado['codigo_riesgo'];
            }
            
            // Si no tiene riesgo asignado, asignar Clase II por defecto
            $this->asignarRiesgoEmpleado($idEmpleado, 2);
            return 2;
            
        } catch (Exception $e) {
            error_log("Error obteniendo riesgo para empleado $idEmpleado: " . $e->getMessage());
            return 2; // Riesgo por defecto
        }
    }

    /**
     * Obtener información completa del riesgo con porcentaje desde BD
     */
    public function obtenerRiesgoCompletoEmpleado($idEmpleado) {
        try {
            $sql = "SELECT era.codigo_riesgo, nra.descripcion, nra.porcentaje
                    FROM empleados_riesgo_arl era
                    JOIN niveles_riesgo_arl nra ON era.codigo_riesgo = nra.codigo
                    WHERE era.id_empleado = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idEmpleado]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                return [
                    'codigo_riesgo' => $resultado['codigo_riesgo'],
                    'clase_riesgo' => 'Clase ' . self::NIVELES_RIESGO[$resultado['codigo_riesgo']]['codigo'],
                    'descripcion' => $resultado['descripcion'],
                    'porcentaje' => floatval($resultado['porcentaje'])
                ];
            }
            
            // Si no tiene riesgo asignado, usar Clase II por defecto
            return [
                'codigo_riesgo' => 2,
                'clase_riesgo' => 'Clase II',
                'descripcion' => 'Clase II - Bajo',
                'porcentaje' => 1.044
            ];
            
        } catch (\Exception $e) {
            error_log("Error obteniendo riesgo para empleado $idEmpleado: " . $e->getMessage());
            return [
                'codigo_riesgo' => 2,
                'clase_riesgo' => 'Clase II', 
                'descripcion' => 'Clase II - Bajo',
                'porcentaje' => 1.044
            ];
        }
    }

    /**
     * Obtener el nivel de riesgo de un empleado
     * @param int $idEmpleado ID del empleado
     * @return array|null Información del riesgo del empleado
     */
    public function getRiesgoEmpleado($idEmpleado) {
        $sql = "SELECT * FROM empleados_riesgo_arl WHERE id_empleado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idEmpleado]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $resultado['nivel_info'] = self::NIVELES_RIESGO[$resultado['codigo_riesgo']];
        }
        
        return $resultado;
    }
    
    /**
     * Obtener nivel de riesgo por defecto según el rol
     * @param array $roles Array de roles del empleado
     * @return int Código de riesgo por defecto
     */
    public function getRiesgoPorDefecto($roles) {
        // Lógica para asignar riesgo por defecto según roles
        // Esto puede ajustarse según las necesidades específicas
        
        if (in_array('admin', $roles) || in_array('rrhh', $roles)) {
            return 1; // Riesgo I - Administrativo
        }
        
        // Para empleados generales, podemos usar Riesgo II como default
        return 2; // Riesgo II - Bajo
    }
    
    /**
     * Calcular ARL para un empleado específico usando su salario individual
     * @param int $idEmpleado ID del empleado
     * @param int $diasTrabajados Días trabajados
     * @param float $salarioBase Salario base (opcional, se obtiene del empleado)
     * @return array Cálculo completo de ARL para el empleado
     */
    public function calcularARLEmpleado($idEmpleado, $diasTrabajados = 30, $salarioBase = null) {
        // Obtener datos del empleado
        $empleadoModel = new Empleado();
        $empleado = $empleadoModel->find($idEmpleado);
        
        if (!$empleado) {
            throw new InvalidArgumentException('Empleado no encontrado');
        }
        
        // Obtener salario base del empleado individual
        if ($salarioBase === null) {
            if (isset($empleado['sueldo_actual']) && $empleado['sueldo_actual'] > 0) {
                $salarioBase = floatval($empleado['sueldo_actual']);
            } else {
                throw new InvalidArgumentException("El empleado {$empleado['nombre']} {$empleado['apellido']} no tiene un salario asignado");
            }
        }
        
        // Obtener nivel de riesgo del empleado
        $riesgoEmpleado = $this->getRiesgoEmpleado($idEmpleado);
        
        // Si no tiene riesgo asignado, usar riesgo por defecto
        if (!$riesgoEmpleado) {
            // Asignar riesgo por defecto (Riesgo II - Bajo) automáticamente
            $codigoRiesgoPorDefecto = 2;
            $this->asignarRiesgoEmpleado($idEmpleado, $codigoRiesgoPorDefecto);
            $codigoRiesgo = $codigoRiesgoPorDefecto;
        } else {
            $codigoRiesgo = $riesgoEmpleado['codigo_riesgo'];
        }
        
        // Calcular ARL
        $calculoARL = $this->calcularARL($salarioBase, $diasTrabajados, $codigoRiesgo);
        
        // Obtener roles del empleado (solo informativo)
        $roles = $this->obtenerRolesEmpleado($idEmpleado);
        
        // Agregar información del empleado
        $calculoARL['empleado'] = [
            'id' => $empleado['id_empleados'],
            'nombre' => $empleado['nombre'],
            'apellido' => $empleado['apellido'],
            'roles' => $roles,
            'salario_asignado' => $salarioBase,
            'riesgo_asignado' => $riesgoEmpleado ? true : false,
            'riesgo_auto_asignado' => !$riesgoEmpleado,
            'fecha_asignacion' => $riesgoEmpleado['fecha_asignacion'] ?? date('Y-m-d H:i:s')
        ];
        
        return $calculoARL;
    }
    
    /**
     * Obtener roles de un empleado (informativo, no afecta cálculos ARL)
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
    }
    
    /**
     * Obtener estadísticas de riesgos por empleados
     * @return array Estadísticas de distribución de riesgos
     */
    public function getEstadisticasRiesgo() {
        $sql = "SELECT 
                    codigo_riesgo, 
                    COUNT(*) as cantidad_empleados,
                    AVG(codigo_riesgo) as riesgo_promedio
                FROM empleados_riesgo_arl 
                GROUP BY codigo_riesgo 
                ORDER BY codigo_riesgo";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $estadisticas = [];
        $totalEmpleados = 0;
        
        foreach ($resultados as $resultado) {
            $codigoRiesgo = $resultado['codigo_riesgo'];
            $cantidad = $resultado['cantidad_empleados'];
            $totalEmpleados += $cantidad;
            
            $estadisticas[$codigoRiesgo] = [
                'nivel_info' => self::NIVELES_RIESGO[$codigoRiesgo],
                'cantidad_empleados' => $cantidad,
                'porcentaje_total' => 0 // Se calculará después
            ];
        }
        
        // Calcular porcentajes
        foreach ($estadisticas as $codigo => &$stats) {
            $stats['porcentaje_total'] = $totalEmpleados > 0 ? 
                round(($stats['cantidad_empleados'] / $totalEmpleados) * 100, 2) : 0;
        }
        
        return [
            'por_nivel' => $estadisticas,
            'total_empleados' => $totalEmpleados,
            'riesgo_promedio' => $totalEmpleados > 0 ? 
                array_sum(array_column($resultados, 'riesgo_promedio')) / count($resultados) : 0
        ];
    }
    
    /**
     * Crear la tabla si no existe
     * @return bool True si se creó correctamente
     */
    public function crearTablaRiesgo() {
        $sql = "CREATE TABLE IF NOT EXISTS empleados_riesgo_arl (
            id INT PRIMARY KEY AUTO_INCREMENT,
            id_empleado INT NOT NULL,
            codigo_riesgo TINYINT NOT NULL CHECK (codigo_riesgo BETWEEN 1 AND 5),
            fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
            fecha_actualizacion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            observaciones TEXT DEFAULT NULL,
            FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
            UNIQUE KEY unique_empleado_riesgo (id_empleado)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creando tabla ARL: " . $e->getMessage());
            return false;
        }
    }
}