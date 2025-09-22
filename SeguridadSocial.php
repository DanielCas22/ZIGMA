<?php
// models/SeguridadSocial.php
require_once __DIR__ . '/../config.php';
class SeguridadSocial {
    public $id;
    public $total;
    public $id_total_devengado;
    public function __construct($id, $total, $id_total_devengado) {
        $this->id = $id;
        $this->total = $total;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM seguridad_social');
        return $stmt->fetchAll();
    }
}
?>