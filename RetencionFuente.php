<?php
// models/RetencionFuente.php
require_once __DIR__ . '/../config.php';
class RetencionFuente {
    public $id;
    public $sueldo;
    public $id_total_deducido;
    public function __construct($id, $sueldo, $id_total_deducido) {
        $this->id = $id;
        $this->sueldo = $sueldo;
        $this->id_total_deducido = $id_total_deducido;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM retencion_fuente');
        return $stmt->fetchAll();
    }
}
?>