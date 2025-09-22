<?php
require_once __DIR__ . '/SalarioPorRol.php';

class Empleado extends Model {
    protected $table = 'empleados';
    public $nombre;
    public $apellido;
    public $usuario;
    public $contrasena;
    public $sueldo_actual;

    public function getAll() {
        $sql = 'SELECT * FROM empleados WHERE es_usuario_sistema = FALSE ORDER BY nombre';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todos los empleados incluyendo usuarios del sistema
     */
    public function getAllIncludingSystem() {
        $sql = 'SELECT * FROM empleados ORDER BY nombre';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener solo usuarios del sistema
     */
    public function getSystemUsers() {
        $sql = 'SELECT * FROM empleados WHERE es_usuario_sistema = TRUE ORDER BY nombre';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = 'SELECT e.*, u.id_doc, u.username 
                FROM empleados e 
                LEFT JOIN user u ON e.id_empleados = u.empleado_id 
                WHERE e.id_empleados = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Alias para compatibilidad
    public function getById($id) {
        return $this->find($id);
    }

    public function getByIdWithRoles($id) {
        $sql = 'SELECT e.*, 
                       GROUP_CONCAT(r.nombre SEPARATOR ", ") as todos_los_roles,
                       (SELECT r2.nombre FROM user u2 
                        LEFT JOIN rol_has_user rhu2 ON u2.id_usuario = rhu2.usuario_id_usuario 
                        LEFT JOIN roles r2 ON rhu2.rol_id_rol = r2.id_rol 
                        WHERE u2.empleado_id = e.id_empleados 
                        AND r2.nombre IN ("admin", "rrhh", "empleado") 
                        ORDER BY FIELD(r2.nombre, "admin", "rrhh", "empleado") 
                        LIMIT 1) as rol_principal
                FROM empleados e 
                LEFT JOIN user u ON e.id_empleados = u.empleado_id 
                LEFT JOIN rol_has_user rhu ON u.id_usuario = rhu.usuario_id_usuario 
                LEFT JOIN roles r ON rhu.rol_id_rol = r.id_rol 
                WHERE e.id_empleados = ? 
                GROUP BY e.id_empleados';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = 'INSERT INTO empleados (nombre, apellidos, salario, es_usuario_sistema) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            isset($data['salario']) ? floatval($data['salario']) : 0.00,
            isset($data['es_usuario_sistema']) ? (bool)$data['es_usuario_sistema'] : false
        ]);
    }

    public function update($id, $data) {
        $sql = 'UPDATE empleados SET nombre=?, apellidos=?, salario=? WHERE id_empleados=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellidos'],
            isset($data['salario']) ? floatval($data['salario']) : 0.00,
            $id
        ]);
    }

    public function updateSalario($id, $salario) {
        $sql = 'UPDATE empleados SET salario=? WHERE id_empleados=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            floatval($salario),
            $id
        ]);
    }

    /**
     * Sincronizar salario de un empleado según su rol principal
     */
    public function sincronizarSalarioConRol($empleado_id) {
        // Obtener datos del empleado con roles
        $empleadoConRoles = $this->getAllWithRoles();
        $empleado = null;
        
        foreach ($empleadoConRoles as $emp) {
            if ($emp['id_empleados'] == $empleado_id) {
                $empleado = $emp;
                break;
            }
        }
        
        if (!$empleado) {
            return false;
        }
        
        // Obtener salario según rol principal
        $salarioModel = new SalarioPorRol();
        $rol_principal = $empleado['rol_principal'] ?? 'empleado';
        $salario_correcto = $salarioModel->getSalarioByRol($rol_principal);
        
        if ($salario_correcto) {
            return $this->updateSalario($empleado_id, $salario_correcto);
        }
        
        return false;
    }

    /**
     * Sincronizar salarios de todos los empleados con sus roles
     */
    public function sincronizarTodosSalariosConRoles() {
        $empleados = $this->getAllWithRoles();
        $salarioModel = new SalarioPorRol();
        $actualizados = 0;
        
        foreach ($empleados as $empleado) {
            $rol_principal = $empleado['rol_principal'] ?? 'empleado';
            $salario_correcto = $salarioModel->getSalarioByRol($rol_principal);
            
            if ($salario_correcto) {
                $resultado = $this->updateSalario($empleado['id_empleados'], $salario_correcto);
                if ($resultado) {
                    $actualizados++;
                }
            }
        }
        
        return $actualizados;
    }

    public function delete($id) {
        try {
            // Iniciar transacción
            $this->db->beginTransaction();
            
            // Primero eliminar las horas extras del empleado
            $sqlHorasExtras = 'DELETE FROM horas_extras WHERE empleado_id = :id';
            $stmtHorasExtras = $this->db->prepare($sqlHorasExtras);
            $stmtHorasExtras->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtHorasExtras->execute();
            
            // Buscar todos los usuarios asociados a este empleado
            $sqlUsers = 'SELECT id_doc FROM user WHERE empleado_id = :id';
            $stmtUsers = $this->db->prepare($sqlUsers);
            $stmtUsers->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUsers->execute();
            $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($users as $user) {
                $user_id = $user['id_doc'];
                // Eliminar roles asociados al usuario
                $sqlDelRoles = 'DELETE FROM rol_has_user WHERE user_id = :user_id';
                $stmtDelRoles = $this->db->prepare($sqlDelRoles);
                $stmtDelRoles->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmtDelRoles->execute();
            }
            
            // Eliminar todos los usuarios asociados al empleado
            $sqlDelUsers = 'DELETE FROM user WHERE empleado_id = :id';
            $stmtDelUsers = $this->db->prepare($sqlDelUsers);
            $stmtDelUsers->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtDelUsers->execute();
            
            // Finalmente eliminar el empleado
            $sql = 'DELETE FROM empleados WHERE id_empleados = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            
            // Confirmar transacción
            $this->db->commit();
            return $result;
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getAllWithRoles() {
        try {
            $sql = 'SELECT e.*, 
                           GROUP_CONCAT(DISTINCT r.nombre ORDER BY 
                               CASE r.nombre 
                                   WHEN "admin" THEN 1 
                                   WHEN "rrhh" THEN 2 
                                   WHEN "empleado" THEN 3 
                                   ELSE 4 
                               END) as roles_concatenados,
                           CASE 
                               WHEN GROUP_CONCAT(DISTINCT r.nombre) LIKE "%admin%" THEN "admin"
                               WHEN GROUP_CONCAT(DISTINCT r.nombre) LIKE "%rrhh%" THEN "rrhh"
                               ELSE "empleado"
                           END as rol_principal
                    FROM empleados e
                    LEFT JOIN user u ON e.id_empleados = u.empleado_id
                    LEFT JOIN rol_has_user rhu ON u.id_doc = rhu.user_id 
                    LEFT JOIN rol r ON rhu.rol_id = r.id_rol
                    WHERE e.es_usuario_sistema = FALSE
                    GROUP BY e.id_empleados, e.nombre, e.apellidos
                    ORDER BY e.nombre';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar los resultados para establecer el rol principal y todos los roles
            foreach ($result as &$empleado) {
                $empleado['rol_nombre'] = $empleado['rol_principal'] ?? 'Sin rol';
                $empleado['todos_los_roles'] = $empleado['roles_concatenados'] ?? '';
            }
            
            return $result ? $result : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
