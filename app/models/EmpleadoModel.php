<?php
require_once 'Model_nuevo.php';

class EmpleadoModel extends Model {
    private $table = 'empleados';

    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id_empleados DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_empleados = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Hash de contraseña si viene
        $passwordHash = null;
        if (!empty($data['contrasena'])) {
            $passwordHash = password_hash($data['contrasena'], PASSWORD_BCRYPT);
        }

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombre, apellido, usuario, contrasena, sueldo_actual) VALUES (:nombre, :apellido, :usuario, :contrasena, :sueldo_actual)");
        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'] ?? null,
            'usuario' => $data['usuario'] ?? null,
            'contrasena' => $passwordHash,
            'sueldo_actual' => $data['sueldo_actual'] ?? null,
        ]);

        $empleadoId = (int)$this->db->lastInsertId();

        // Integración con tabla user para documentos (opcional)
        if (!empty($data['tipo_doc']) && !empty($data['num_doc'])) {
            $stmtU = $this->db->prepare("INSERT INTO user (tipo_doc, num_doc, empleado_id) VALUES (:tipo_doc, :num_doc, :empleado_id)");
            $stmtU->execute([
                'tipo_doc' => $data['tipo_doc'],
                'num_doc' => $data['num_doc'],
                'empleado_id' => $empleadoId,
            ]);
        }

        return $empleadoId;
    }

    public function update($id, $data) {
        $fields = ['nombre = :nombre', 'apellido = :apellido', 'usuario = :usuario', 'sueldo_actual = :sueldo_actual'];
        $params = [
            'id' => $id,
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'] ?? null,
            'usuario' => $data['usuario'] ?? null,
            'sueldo_actual' => $data['sueldo_actual'] ?? null,
        ];

        if (!empty($data['contrasena'])) {
            $fields[] = 'contrasena = :contrasena';
            $params['contrasena'] = password_hash($data['contrasena'], PASSWORD_BCRYPT);
        }

        $sql = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id_empleados = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        // Actualizar/crear documentos en tabla user (opcional)
        if (isset($data['tipo_doc']) && isset($data['num_doc'])) {
            // Verificar si existe user
            $stmtCheck = $this->db->prepare('SELECT id_doc FROM user WHERE empleado_id = :id');
            $stmtCheck->execute(['id' => $id]);
            $user = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $stmtU = $this->db->prepare('UPDATE user SET tipo_doc = :tipo_doc, num_doc = :num_doc WHERE empleado_id = :empleado_id');
                $stmtU->execute([
                    'tipo_doc' => $data['tipo_doc'],
                    'num_doc' => $data['num_doc'],
                    'empleado_id' => $id,
                ]);
            } else {
                $stmtU = $this->db->prepare('INSERT INTO user (tipo_doc, num_doc, empleado_id) VALUES (:tipo_doc, :num_doc, :empleado_id)');
                $stmtU->execute([
                    'tipo_doc' => $data['tipo_doc'],
                    'num_doc' => $data['num_doc'],
                    'empleado_id' => $id,
                ]);
            }
        }

        return true;
    }

    public function delete($id) {
        // Eliminar vínculo en user si existe
        $this->db->prepare('DELETE FROM user WHERE empleado_id = :id')->execute(['id' => $id]);

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_empleados = :id");
        return $stmt->execute(['id' => $id]);
    }
}
