<?php
namespace App\Models;

use PDO;

class Empleado extends Model {
    protected $table = 'empleados';
    public $nombre;
    public $apellido;
    public $usuario;
    public $contrasena;
    public $sueldo_actual;

    // Verifica si una columna existe en la tabla empleados
    private function hasColumn($column) {
        try {
            $stmt = $this->db->prepare('SHOW COLUMNS FROM empleados LIKE ?');
            $stmt->execute([$column]);
            return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAll() {
        $sql = 'SELECT * FROM empleados ORDER BY nombre';
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
        if (!$this->hasColumn('es_usuario_sistema')) {
            return [];
        }
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
                        LEFT JOIN rol_has_user rhu2 ON u2.id_doc = rhu2.user_id 
                        LEFT JOIN rol r2 ON rhu2.rol_id = r2.id_rol 
                        WHERE u2.empleado_id = e.id_empleados 
                        AND r2.nombre IN ("admin", "rrhh", "empleado") 
                        ORDER BY FIELD(r2.nombre, "admin", "rrhh", "empleado") 
                        LIMIT 1) as rol_principal
                FROM empleados e 
                LEFT JOIN user u ON e.id_empleados = u.empleado_id 
                LEFT JOIN rol_has_user rhu ON u.id_doc = rhu.user_id 
                LEFT JOIN rol r ON rhu.rol_id = r.id_rol 
                WHERE e.id_empleados = ? 
                GROUP BY e.id_empleados';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $hasFlag = $this->hasColumn('es_usuario_sistema');
        $db = $this->db;
        try {
            $db->beginTransaction();
            if ($hasFlag) {
                $es_usuario = isset($data['es_usuario_sistema']) ? (int)$data['es_usuario_sistema'] : 1; // Por defecto 1 (usuario del sistema)
                $sql = 'INSERT INTO empleados (nombre, apellido, sueldo_actual, es_usuario_sistema) VALUES (?, ?, ?, ?)';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $data['nombre'],
                    $data['apellido'],
                    isset($data['sueldo_actual']) ? floatval($data['sueldo_actual']) : 0.00,
                    $es_usuario
                ]);
            } else {
                $sql = 'INSERT INTO empleados (nombre, apellido, sueldo_actual) VALUES (?, ?, ?)';
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $data['nombre'],
                    $data['apellido'],
                    isset($data['sueldo_actual']) ? floatval($data['sueldo_actual']) : 0.00
                ]);
            }
            $empleado_id = $db->lastInsertId();

            // Si se reciben usuario y contraseña, crear el usuario asociado
            if (!empty($data['usuario']) && !empty($data['contrasena'])) {
                $usuario = $data['usuario'];
                
                // Verificar si el nombre de usuario ya existe
                $sqlCheckUser = 'SELECT COUNT(*) FROM user WHERE username = ?';
                $stmtCheckUser = $db->prepare($sqlCheckUser);
                $stmtCheckUser->execute([$usuario]);
                $userExists = $stmtCheckUser->fetchColumn() > 0;
                
                if ($userExists) {
                    $db->rollBack();
                    throw new \Exception("El nombre de usuario '$usuario' ya está en uso. Por favor, elija otro nombre de usuario.");
                }
                
                $contrasena = password_hash($data['contrasena'], PASSWORD_DEFAULT);
                $sqlUser = 'INSERT INTO user (username, password, empleado_id) VALUES (?, ?, ?)';
                $stmtUser = $db->prepare($sqlUser);
                $stmtUser->execute([$usuario, $contrasena, $empleado_id]);
                $user_id = $db->lastInsertId();
                
                // Asignar automáticamente el rol seleccionado
                $rol_nombre = isset($data['rol']) ? $data['rol'] : 'empleado';
                $sqlRol = 'SELECT id_rol FROM rol WHERE nombre = ?';
                $stmtRol = $db->prepare($sqlRol);
                $stmtRol->execute([$rol_nombre]);
                $rol = $stmtRol->fetch(PDO::FETCH_ASSOC);
                if ($rol) {
                    $sqlRolUser = 'INSERT INTO rol_has_user (user_id, rol_id) VALUES (?, ?)';
                    $stmtRolUser = $db->prepare($sqlRolUser);
                    $stmtRolUser->execute([$user_id, $rol['id_rol']]);
                }
            }
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function update($id, $data) {
        $sql = 'UPDATE empleados SET nombre=?, apellido=?, sueldo_actual=? WHERE id_empleados=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            isset($data['sueldo_actual']) ? floatval($data['sueldo_actual']) : 0.00,
            $id
        ]);
    }

    public function updateSalario($id, $sueldo_actual) {
        $sql = 'UPDATE empleados SET sueldo_actual=? WHERE id_empleados=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            floatval($sueldo_actual),
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
            
            // Eliminar todos los registros de nómina asociados al empleado
            $sqlNomina = 'DELETE FROM nomina WHERE empleado_id = :id';
            $stmtNomina = $this->db->prepare($sqlNomina);
            $stmtNomina->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtNomina->execute();
            
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
            // Eliminar filtro por es_usuario_sistema para mostrar todos los empleados reales
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
                LEFT JOIN rol r ON rhu.rol_id = r.id_rol';
            // No se agrega condición por es_usuario_sistema
            $sql .= ' GROUP BY e.id_empleados, e.nombre, e.apellido ORDER BY e.nombre';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Procesar los resultados para establecer el rol principal y todos los roles
            foreach ($result as &$empleado) {
                $empleado['rol_nombre'] = $empleado['rol_principal'] ?? 'Sin rol';
                $empleado['todos_los_roles'] = $empleado['roles_concatenados'] ?? '';
            }
            return $result ? $result : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    /**
     * Calcula el auxilio de transporte según el sueldo actual y el menor salario base por rol (SMLV lógico)
     * Si el sueldo es menor o igual a dos veces ese valor, retorna el auxilio vigente; si es mayor, retorna 0.
     */
    public function getAuxilioTransporte($sueldo_actual) {
        require_once __DIR__ . '/SalarioPorRol.php';
        $salarioPorRol = new SalarioPorRol();
        $smlv = $salarioPorRol->getMenorSalarioBase();
        require_once __DIR__ . '/ParametrosModel.php';
        $paramModel = new ParametrosModel();
        $parametros = $paramModel->getParametrosVigentes();
        $auxilio_transporte = isset($parametros['auxilio_transporte']) ? floatval($parametros['auxilio_transporte']) : 200000;
        if ($sueldo_actual <= 2 * $smlv) {
            return $auxilio_transporte;
        }
        return 0;
    }

    /**
     * Devuelve el auxilio de transporte del empleado según su campo propio,
     * si cumple la condición de salario, o 0 si no aplica.
     */
    public function getAuxilioTransporteEmpleado($empleado) {
        require_once __DIR__ . '/SalarioPorRol.php';
        $salarioPorRol = new SalarioPorRol();
        $smlv = $salarioPorRol->getMenorSalarioBase();
        $sueldo_actual = isset($empleado['sueldo_actual']) ? floatval($empleado['sueldo_actual']) : 0;
        $auxilio_transporte = isset($empleado['auxilio_transporte']) ? floatval($empleado['auxilio_transporte']) : 0;
        if ($sueldo_actual <= 2 * $smlv) {
            return $auxilio_transporte;
        }
        return 0;
    }

    /**
     * Obtener empleados válidos (excluye solo los placeholders de rol exactos)
     */
    public function getValidEmployees() {
        $sql = "SELECT * FROM empleados WHERE NOT (
            (nombre = 'Administrador' AND apellido = 'del Sistema') OR
            (nombre = 'Coordinador' AND apellido = 'RRHH') OR
            (nombre = 'Empleado' AND apellido = 'General')
        ) ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarSalarioTodos($nuevoSMLV) {
        $sql = "UPDATE empleados SET sueldo_actual = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nuevoSMLV]);
    }

    public function actualizarAuxilioTransporteTodos($nuevoAuxilio) {
        $sql = "UPDATE empleados SET auxilio_transporte = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nuevoAuxilio]);
    }
}
