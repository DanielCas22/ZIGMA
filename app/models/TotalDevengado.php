<?php
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

    public function create($salario, $dias, $total) {
        $stmt = $this->db->prepare('INSERT INTO total_devengado (salario, dias, total) VALUES (?,?,?)');
        $stmt->execute([(int)$salario, (string)$dias, (int)$total]);
        return (int)$this->db->lastInsertId();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM total_devengado WHERE id_total_devengado = ?');
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
