<?php
// models/Parafiscales.php
require_once __DIR__ . '/../config.php';
class Parafiscales {
    public $id;
    public $valor_total;
    public $id_total_devengado;
    public function __construct($id, $valor_total, $id_total_devengado) {
        $this->id = $id;
        $this->valor_total = $valor_total;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM parafiscales');
        return $stmt->fetchAll();
    }
}
?>