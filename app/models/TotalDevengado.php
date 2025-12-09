<?php
namespace App\Models;

use PDO;

class TotalDevengado extends Model {
    protected $table = 'total_devengado';

    public function getByHorasExtras($id_extras) {
        $sql = 'SELECT td.* FROM total_devengado td 
                INNER JOIN horas_extras he ON he.id_extras = td.id_total_devengado
                WHERE he.id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_extras]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna el total devengado por un empleado
     * Calcula basado en conceptos adicionales no deducibles
     */
    public function getTotalByEmpleado($empleado_id) {
        try {
            // Obtener conceptos adicionales (devengos) del empleado
            $conceptosModel = new ConceptosAdicionalesModel();
            $conceptos = $conceptosModel->obtenerConceptosPorEmpleado($empleado_id);
            
            $total = 0;
            if (is_array($conceptos)) {
                foreach ($conceptos as $concepto) {
                    $total += floatval($concepto['valor'] ?? 0);
                }
            }
            
            return $total;
        } catch (\Exception $e) {
            error_log("Error calculando total devengado para empleado $empleado_id: " . $e->getMessage());
            return 0;
        }
    }
}
