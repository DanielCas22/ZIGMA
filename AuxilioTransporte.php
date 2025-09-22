<?php
// models/AuxilioTransporte.php
require_once __DIR__ . '/../config.php';
class AuxilioTransporte {
    public $id;
    public $valor;
    public $id_total_devengado;
    public function __construct($id, $valor, $id_total_devengado) {
        $this->id = $id;
        $this->valor = $valor;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM auxilio_de_transporte');
        return $stmt->fetchAll();
    }
}
?>