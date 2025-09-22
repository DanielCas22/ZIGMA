<?php
// models/Rol.php
require_once __DIR__ . '/../config.php';
class Rol {
    public $id_rol;
    public $nombre;
    public $apellido;
    public function __construct($id_rol, $nombre, $apellido) {
        $this->id_rol = $id_rol;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM rol');
        return $stmt->fetchAll();
    }
}
?>