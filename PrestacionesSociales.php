<?php
// models/PrestacionesSociales.php
require_once __DIR__ . '/../config.php';
class PrestacionesSociales {
    public $id;
    public $valor;
    public $total;
    public $id_total_devengado;
    public function __construct($id, $valor, $total, $id_total_devengado) {
        $this->id = $id;
        $this->valor = $valor;
        $this->total = $total;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM prestaciones_sociales');
        return $stmt->fetchAll();
    }
}
?>