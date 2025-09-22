<?php
class Rol extends Model {
    protected $table = 'rol';

    public function getByName($nombre) {
        $sql = 'SELECT * FROM rol WHERE nombre = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        try {
            $sql = 'SELECT * FROM rol ORDER BY nombre';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }
}
