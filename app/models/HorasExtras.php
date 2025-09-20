<?php
class HorasExtras extends Model {
    public function getByEmpleado($empleado_id) {
        $sql = 'SELECT he.*, e.nombre as empleado_nombre FROM horas_extras he 
                JOIN empleados e ON he.empleado_id = e.id_empleados
                WHERE he.empleado_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // id_extras, valor, cantidad, tipo, porcentaje, dia, mes, año
    protected $table = 'horas_extras';

    public function getAllWithEmpleado() {
        $sql = 'SELECT he.*, e.id_empleados, e.nombre, e.apellidos FROM empleados e 
                LEFT JOIN horas_extras he ON he.empleado_id = e.id_empleados';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = 'INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, año) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['año']
        ]);
    }

    public function find($id) {
        $sql = 'SELECT * FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = 'UPDATE horas_extras SET empleado_id=?, valor=?, cantidad=?, tipo=?, porcentaje=?, dia=?, mes=?, año=? WHERE id_extras=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['año'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = 'DELETE FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
