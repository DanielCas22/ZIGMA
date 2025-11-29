<?php
namespace App\Models;

use PDO;

class ConceptosAdicionalesModel extends Model {
    
    /**
     * Obtener todos los conceptos adicionales de un empleado
     * @param int $empleado_id ID del empleado
     * @return array
     */
    public function obtenerConceptosPorEmpleado($empleado_id) {
        try {
            $query = "SELECT * FROM conceptos_adicionales_prestaciones 
                      WHERE empleado_id = ? AND activo = TRUE 
                      ORDER BY fecha_creacion DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$empleado_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (\Exception $e) {
            error_log("Error obteniendo conceptos adicionales: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener el total de conceptos adicionales de un empleado
     * @param int $empleado_id ID del empleado
     * @return float
     */
    public function obtenerTotalConceptosPorEmpleado($empleado_id) {
        try {
            $query = "SELECT COALESCE(SUM(valor), 0) as total 
                      FROM conceptos_adicionales_prestaciones 
                      WHERE empleado_id = ? AND activo = TRUE";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$empleado_id]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return floatval($resultado['total']);
            
        } catch (\Exception $e) {
            error_log("Error obteniendo total conceptos adicionales: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Agregar un nuevo concepto adicional
     * @param int $empleado_id ID del empleado
     * @param string $concepto Nombre del concepto
     * @param string $descripcion Descripción detallada
     * @param float $valor Valor monetario
     * @param string $creado_por Usuario que crea el concepto
     * @return bool|int ID del concepto creado o false si falla
     */
    public function agregarConcepto($empleado_id, $concepto, $descripcion, $valor, $creado_por) {
        try {
            $query = "INSERT INTO conceptos_adicionales_prestaciones 
                      (empleado_id, concepto, descripcion, valor, creado_por) 
                      VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([$empleado_id, $concepto, $descripcion, $valor, $creado_por]);
            
            if ($resultado) {
                return $this->db->lastInsertId();
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error agregando concepto adicional: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar un concepto adicional
     * @param int $id ID del concepto
     * @param string $concepto Nombre del concepto
     * @param string $descripcion Descripción
     * @param float $valor Valor monetario
     * @return bool
     */
    public function actualizarConcepto($id, $concepto, $descripcion, $valor) {
        try {
            $query = "UPDATE conceptos_adicionales_prestaciones 
                      SET concepto = ?, descripcion = ?, valor = ?, 
                          fecha_actualizacion = CURRENT_TIMESTAMP 
                      WHERE id = ? AND activo = TRUE";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$concepto, $descripcion, $valor, $id]);
            
        } catch (Exception $e) {
            error_log("Error actualizando concepto adicional: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar (desactivar) un concepto adicional
     * @param int $id ID del concepto
     * @return bool
     */
    public function eliminarConcepto($id) {
        try {
            $query = "UPDATE conceptos_adicionales_prestaciones 
                      SET activo = FALSE, fecha_actualizacion = CURRENT_TIMESTAMP 
                      WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$id]);
            
        } catch (Exception $e) {
            error_log("Error eliminando concepto adicional: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener resumen de conceptos adicionales por empleado (para tooltips)
     * @param int $empleado_id ID del empleado
     * @return array
     */
    public function obtenerResumenConceptos($empleado_id) {
        try {
            $conceptos = $this->obtenerConceptosPorEmpleado($empleado_id);
            $total = $this->obtenerTotalConceptosPorEmpleado($empleado_id);
            
            return [
                'conceptos' => $conceptos,
                'total' => $total,
                'cantidad' => count($conceptos)
            ];
            
        } catch (Exception $e) {
            error_log("Error obteniendo resumen conceptos: " . $e->getMessage());
            return [
                'conceptos' => [],
                'total' => 0,
                'cantidad' => 0
            ];
        }
    }
    
    /**
     * Obtener todos los conceptos con información del empleado
     * @return array
     */
    public function obtenerTodosLosConceptos() {
        try {
            $query = "SELECT 
                        cap.*, 
                        e.nombre, 
                        e.apellido 
                      FROM conceptos_adicionales_prestaciones cap
                      JOIN empleados e ON cap.empleado_id = e.id_empleados
                      WHERE cap.activo = TRUE
                      ORDER BY cap.fecha_creacion DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error obteniendo todos los conceptos: " . $e->getMessage());
            return [];
        }
    }
}

?>