<?php
// models/TotalDevengado.php
require_once __DIR__ . '/../config.php';
class TotalDevengado {
    public $id;
    public $salario;
    public $dias;
    public $total;
    public function __construct($id, $salario, $dias, $total) {
        $this->id = $id;
        $this->salario = $salario;
        $this->dias = $dias;
        $this->total = $total;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM total_devengado');
        return $stmt->fetchAll();
    }
    // Métodos para obtener relaciones: prestaciones sociales, comisiones, horas extras, auxilio de transporte, parafiscales, seguridad social
}
?>