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
     */
    public function getTotalByEmpleado($empleado_id) {
        $sql = 'SELECT SUM(td.total) as total FROM total_devengado td
                INNER JOIN nomina n ON n.total_devengado_id = td.id_total_devengado
                WHERE n.empleado_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && isset($row['total']) ? floatval($row['total']) : 0;
    }
}
