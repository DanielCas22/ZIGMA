<?php
class Empleado extends Model {
    protected $table = 'empleados';
    public $nombre;
    public $apellido;
    public $usuario;
    public $contrasena;
    public $sueldo_actual;

    public function getAll() {
        $sql = 'SELECT * FROM empleados';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
