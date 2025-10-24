<?php
require_once 'Model_nuevo.php';

class ReporteModel extends Model {
    private $table = 'gestion_reportes';

    public function listar() {
        $sql = 'SELECT gr.* FROM ' . $this->table . ' gr ORDER BY gr.id_reportes DESC';
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $anio, $mes, $nominaId = null) {
        $stmt = $this->db->prepare('INSERT INTO ' . $this->table . ' (nombre, dia, mes, anio, nomina_id) VALUES (:nombre, :dia, :mes, :anio, :nomina_id)');
        return $stmt->execute([
            'nombre' => $nombre,
            'dia' => date('d'),
            'mes' => $mes,
            'anio' => $anio,
            'nomina_id' => $nominaId,
        ]);
    }
}
