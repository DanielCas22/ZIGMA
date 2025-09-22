<?php
require_once 'Model_nuevo.php';

class ParametroModel extends Model {
    private $table = 'parametros';

    public function get($clave, $default = null) {
        try {
            $stmt = $this->db->prepare("SELECT valor FROM {$this->table} WHERE clave = :c");
            $stmt->execute(['c' => $clave]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) return (int)$row['valor'];
        } catch (Exception $e) {
            // Tabla puede no existir aún
        }
        return $default;
    }

    public function set($clave, $valor) {
        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (clave, valor) VALUES (:c, :v) ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
            return $stmt->execute(['c' => $clave, 'v' => $valor]);
        } catch (Exception $e) {
            return false;
        }
    }
}
