<?php

/**
 * Modelo para manejar conceptos adicionales DEDUCIBLES
 * Separado del modelo de conceptos de devengado
 */
class ConceptosAdicionalesDeduciblesModel extends Model {
    
    protected $table = 'conceptos_adicionales_deducibles';
    
    /**
     * Agregar nuevo concepto deducible (descuento)
     */
    public function agregarConcepto($empleadoId, $concepto, $descripcion, $valor, $creadoPor = 'Sistema') {
        try {
            $sql = "INSERT INTO {$this->table} (empleado_id, concepto, descripcion, valor, creado_por) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $resultado = $stmt->execute([
                $empleadoId,
                $concepto,
                $descripcion,
                $valor,
                $creadoPor
            ]);
            
            return $resultado ? $this->db->lastInsertId() : false;
            
        } catch (PDOException $e) {
            error_log("Error agregando concepto deducible: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener conceptos deducibles por empleado
     */
    public function obtenerConceptosPorEmpleado($empleadoId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE empleado_id = ? AND activo = 1 
                    ORDER BY fecha_creacion DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$empleadoId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error obteniendo conceptos deducibles: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener total de conceptos deducibles por empleado
     */
    public function obtenerTotalConceptosPorEmpleado($empleadoId) {
        try {
            $sql = "SELECT COALESCE(SUM(valor), 0) as total 
                    FROM {$this->table} 
                    WHERE empleado_id = ? AND activo = 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$empleadoId]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($resultado['total']);
            
        } catch (PDOException $e) {
            error_log("Error obteniendo total conceptos deducibles: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener resumen completo de conceptos deducibles por empleado
     */
    public function obtenerResumenConceptos($empleadoId) {
        $conceptos = $this->obtenerConceptosPorEmpleado($empleadoId);
        $total = $this->obtenerTotalConceptosPorEmpleado($empleadoId);
        
        return [
            'conceptos' => $conceptos,
            'total' => $total,
            'cantidad' => count($conceptos)
        ];
    }
    
    /**
     * Eliminar concepto deducible
     */
    public function eliminarConcepto($id) {
        try {
            // Soft delete - marcar como inactivo
            $sql = "UPDATE {$this->table} SET activo = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
            
        } catch (PDOException $e) {
            error_log("Error eliminando concepto deducible: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar concepto deducible
     */
    public function actualizarConcepto($id, $concepto, $descripcion, $valor) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET concepto = ?, descripcion = ?, valor = ?, fecha_actualizacion = CURRENT_TIMESTAMP 
                    WHERE id = ? AND activo = 1";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$concepto, $descripcion, $valor, $id]);
            
        } catch (PDOException $e) {
            error_log("Error actualizando concepto deducible: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener estadísticas generales de conceptos deducibles
     */
    public function obtenerEstadisticasGenerales() {
        try {
            $sql = "SELECT 
                        COUNT(DISTINCT empleado_id) as empleados_con_deducciones,
                        COUNT(*) as total_conceptos,
                        SUM(valor) as total_valor,
                        AVG(valor) as promedio_valor
                    FROM {$this->table} 
                    WHERE activo = 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'empleados_con_deducciones' => intval($resultado['empleados_con_deducciones']),
                'total_conceptos' => intval($resultado['total_conceptos']),
                'total_valor' => floatval($resultado['total_valor']),
                'promedio_valor' => floatval($resultado['promedio_valor'])
            ];
            
        } catch (PDOException $e) {
            error_log("Error obteniendo estadísticas generales: " . $e->getMessage());
            return [
                'empleados_con_deducciones' => 0,
                'total_conceptos' => 0,
                'total_valor' => 0,
                'promedio_valor' => 0
            ];
        }
    }
    
    /**
     * Obtener conceptos deducibles por tipo/categoría (si se implementa en el futuro)
     */
    public function obtenerConceptosPorTipo($tipo = null) {
        try {
            $sql = "SELECT cd.*, e.nombre, e.apellido 
                    FROM {$this->table} cd
                    INNER JOIN empleados e ON cd.empleado_id = e.id_empleados
                    WHERE cd.activo = 1";
            
            if ($tipo) {
                $sql .= " AND cd.concepto LIKE ?";
                $parametros = ["%{$tipo}%"];
            } else {
                $parametros = [];
            }
            
            $sql .= " ORDER BY cd.fecha_creacion DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($parametros);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error obteniendo conceptos por tipo: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verificar si existe la tabla (para migrations automáticas)
     */
    public function verificarTabla() {
        try {
            $sql = "SHOW TABLES LIKE '{$this->table}'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Crear tabla si no existe (migración automática)
     */
    public function crearTablaSeNoExiste() {
        if ($this->verificarTabla()) {
            return true;
        }
        
        try {
            $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
                id INT PRIMARY KEY AUTO_INCREMENT,
                empleado_id INT NOT NULL,
                concepto VARCHAR(255) NOT NULL,
                descripcion TEXT,
                valor DECIMAL(15,2) NOT NULL DEFAULT 0,
                creado_por VARCHAR(100),
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                activo TINYINT(1) DEFAULT 1,
                KEY idx_empleado_activo (empleado_id, activo),
                KEY idx_fecha_creacion (fecha_creacion)
            ) COMMENT='Conceptos adicionales para TOTAL DEDUCIDO (descuentos adicionales)'";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error creando tabla conceptos_adicionales_deducibles: " . $e->getMessage());
            return false;
        }
    }
}