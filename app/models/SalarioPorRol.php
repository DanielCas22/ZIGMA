<?php

class SalarioPorRol extends Model {
    
    /**
     * Obtener todos los salarios por rol
     */
    public function getAll() {
        $sql = "SELECT * FROM salarios_por_rol ORDER BY salario_base DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener salario base por rol
     */
    public function getSalarioByRol($rol_nombre) {
        $sql = "SELECT salario_base FROM salarios_por_rol WHERE rol_nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rol_nombre]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? floatval($resultado['salario_base']) : null;
    }
    
    /**
     * Obtener información completa de un rol
     */
    public function getRolInfo($rol_nombre) {
        $sql = "SELECT * FROM salarios_por_rol WHERE rol_nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$rol_nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Actualizar salario base de un rol
     */
    public function updateSalarioRol($rol_nombre, $nuevo_salario) {
        $sql = "UPDATE salarios_por_rol SET salario_base = ? WHERE rol_nombre = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([floatval($nuevo_salario), $rol_nombre]);
    }
    
    /**
     * Determinar el salario correcto según la jerarquía de roles
     * Si tiene múltiples roles, usa el de mayor jerarquía
     */
    public function getSalarioSegunJerarquia($roles_empleado) {
        // Orden de jerarquía (mayor a menor)
        $jerarquia = ['admin', 'rrhh', 'empleado'];
        
        // Si viene como string separado por comas, convertir a array
        if (is_string($roles_empleado)) {
            $roles_empleado = array_map('trim', explode(',', $roles_empleado));
        }
        
        // Buscar el rol de mayor jerarquía que tenga el empleado
        foreach ($jerarquia as $rol_jerarquico) {
            if (in_array($rol_jerarquico, $roles_empleado)) {
                return $this->getSalarioByRol($rol_jerarquico);
            }
        }
        
        // Si no tiene ningún rol reconocido, devolver salario de empleado base
        return $this->getSalarioByRol('empleado');
    }
    
    /**
     * Crear nuevo salario por rol
     */
    public function create($rol_nombre, $salario_base, $descripcion = '') {
        $sql = "INSERT INTO salarios_por_rol (rol_nombre, salario_base, descripcion) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$rol_nombre, floatval($salario_base), $descripcion]);
    }
}