<?php
// models/Comisiones.php
require_once __DIR__ . '/../config.php';
class Comisiones {
    public $id;
    public $valor;
    public $mes;
    public $año;
    public $id_total_devengado;
    public function __construct($id, $valor, $mes, $año, $id_total_devengado) {
        $this->id = $id;
        $this->valor = $valor;
        $this->mes = $mes;
        $this->año = $año;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM comisiones');
        return $stmt->fetchAll();
    }
}
?>