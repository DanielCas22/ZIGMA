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
     * Agregar un nuevo concepto adicional con opción de plazos
     * @param int $empleado_id ID del empleado
     * @param string $concepto Nombre del concepto
     * @param string $descripcion Descripción detallada
     * @param float $valor Valor monetario
     * @param string $creado_por Usuario que crea el concepto
     * @param int $total_plazos Cantidad de plazos (1 si es de pago único)
     * @param string $tipo_plazo Tipo de plazo: 'quincena' o 'mes'
     * @return bool|int ID del concepto creado o false si falla
     */
    public function agregarConcepto($empleado_id, $concepto, $descripcion, $valor, $creado_por, $total_plazos = 1, $tipo_plazo = 'quincena') {
        try {
            $tiene_plazo = $total_plazos > 1;
            
            $query = "INSERT INTO conceptos_adicionales_prestaciones 
                      (empleado_id, concepto, descripcion, valor, creado_por, total_plazos, tipo_plazo, tiene_plazo) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([$empleado_id, $concepto, $descripcion, $valor, $creado_por, $total_plazos, $tipo_plazo, $tiene_plazo]);
            
            if ($resultado) {
                $concepto_id = $this->db->lastInsertId();
                
                // Si tiene plazos, crear los registros de plazos
                if ($tiene_plazo) {
                    $this->crearPlazos($concepto_id, $valor, $total_plazos, $tipo_plazo);
                }
                
                return $concepto_id;
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
    
    /**
     * Crear los plazos para un concepto adicional
     * @param int $concepto_id ID del concepto
     * @param float $valor_total Valor total a distribuir
     * @param int $total_plazos Cantidad de plazos
     * @param string $tipo_plazo Tipo de plazo
     * @return bool
     */
    public function crearPlazos($concepto_id, $valor_total, $total_plazos, $tipo_plazo = 'quincena') {
        try {
            $valor_por_plazo = $valor_total / $total_plazos;
            
            for ($i = 1; $i <= $total_plazos; $i++) {
                $query = "INSERT INTO conceptos_adicionales_plazos 
                         (concepto_id, periodo_numero, valor_periodo, tipo_periodo, estado) 
                         VALUES (?, ?, ?, ?, 'pendiente')";
                
                $stmt = $this->db->prepare($query);
                $stmt->execute([$concepto_id, $i, $valor_por_plazo, $tipo_plazo]);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Error creando plazos: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener plazos de un concepto
     * @param int $concepto_id ID del concepto
     * @return array
     */
    public function obtenerPlazos($concepto_id) {
        try {
            $query = "SELECT * FROM conceptos_adicionales_plazos 
                     WHERE concepto_id = ? 
                     ORDER BY periodo_numero ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$concepto_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error obteniendo plazos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener detalles completos de un concepto con sus plazos
     * @param int $concepto_id ID del concepto
     * @return array
     */
    public function obtenerConceptoConPlazos($concepto_id) {
        try {
            $query = "SELECT * FROM conceptos_adicionales_prestaciones 
                     WHERE id = ? AND activo = TRUE";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$concepto_id]);
            
            $concepto = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($concepto) {
                $concepto['plazos'] = $this->obtenerPlazos($concepto_id);
            }
            
            return $concepto;
            
        } catch (Exception $e) {
            error_log("Error obteniendo concepto con plazos: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Marcar un plazo como pagado
     * @param int $plazo_id ID del plazo
     * @return bool
     */
    public function marcarPlazoPagado($plazo_id) {
        try {
            $query = "UPDATE conceptos_adicionales_plazos 
                     SET estado = 'pagado', fecha_actualizacion = CURRENT_TIMESTAMP 
                     WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$plazo_id]);
            
        } catch (Exception $e) {
            error_log("Error marcando plazo como pagado: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener el valor del próximo plazo a pagar de un concepto
     * @param int $concepto_id ID del concepto
     * @return array|null
     */
    public function obtenerProximoPlazo($concepto_id) {
        try {
            $query = "SELECT * FROM conceptos_adicionales_plazos 
                     WHERE concepto_id = ? AND estado = 'pendiente' 
                     ORDER BY periodo_numero ASC 
                     LIMIT 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$concepto_id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error obteniendo próximo plazo: " . $e->getMessage());
            return null;
        }
    }
}

?>