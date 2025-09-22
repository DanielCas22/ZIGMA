<?php
// models/HorasExtras.php
require_once __DIR__ . '/../config.php';
class HorasExtras {
    public $id;
    public $valor;
    public $cantidad;
    public $tipo;
    public $porcentaje;
    public $dia;
    public $mes;
    public $año;
    public $id_total_devengado;
    public function __construct($id, $valor, $cantidad, $tipo, $porcentaje, $dia, $mes, $año, $id_total_devengado) {
        $this->id = $id;
        $this->valor = $valor;
        $this->cantidad = $cantidad;
        $this->tipo = $tipo;
        $this->porcentaje = $porcentaje;
        $this->dia = $dia;
        $this->mes = $mes;
        $this->año = $año;
        $this->id_total_devengado = $id_total_devengado;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM horas_extras');
        return $stmt->fetchAll();
    }
}
?>