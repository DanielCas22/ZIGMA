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
}
