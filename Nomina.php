<?php
// models/Nomina.php
require_once __DIR__ . '/../config.php';
class Nomina {
    public $id;
    public $anio;
    public $mes;
    public $dia;
    public $valor_pagar;
    public function __construct($id, $anio, $mes, $dia, $valor_pagar) {
        $this->id = $id;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->dia = $dia;
        $this->valor_pagar = $valor_pagar;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM nomina');
        return $stmt->fetchAll();
    }
}
?>