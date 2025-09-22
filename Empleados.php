<?php
// models/Empleados.php
require_once __DIR__ . '/../config.php';
class Empleados {
    public $id;
    public $nombre;
    public $apellido;
    public $usuario;
    public $contraseña;
    public $sueldo_actual;
    public function __construct($id, $nombre, $apellido, $usuario, $contraseña, $sueldo_actual) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->usuario = $usuario;
        $this->contraseña = $contraseña;
        $this->sueldo_actual = $sueldo_actual;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM empleados');
        return $stmt->fetchAll();
    }
}
?>