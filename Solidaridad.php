<?php
// models/Solidaridad.php
require_once __DIR__ . '/../config.php';
class Solidaridad {
    public $id;
    public $sueldo;
    public $valor;
    public $id_total_deducido;
    public function __construct($id, $sueldo, $valor, $id_total_deducido) {
        $this->id = $id;
        $this->sueldo = $sueldo;
        $this->valor = $valor;
        $this->id_total_deducido = $id_total_deducido;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM solidaridad');
        return $stmt->fetchAll();
    }
}
?>