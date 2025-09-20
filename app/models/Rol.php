<?php
class Rol extends Model {
    protected $table = 'rol';

    public function getAll() {
        $sql = 'SELECT * FROM rol';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
