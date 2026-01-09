<?php
namespace App\Models;

use PDO;

class TipoHoraExtra extends Model {
    
    /**
     * Obtener todos los tipos de horas extras
     */
    public function getAll() {
        $sql = "SELECT * FROM tipos_horas_extras ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener tipo por nombre
     */
    public function getByNombre($nombre) {
        $sql = "SELECT * FROM tipos_horas_extras WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener tipo con porcentaje por nombre
     */
    public function getTipoPorcentaje($tipo) {
        return $this->getByNombre($tipo);
    }
    
    /**
     * Obtener porcentaje por tipo
     */
    public function getPorcentaje($tipo) {
        $tipoData = $this->getByNombre($tipo);
        return $tipoData ? intval($tipoData['porcentaje']) : 0;
    }

    /**
     * Guardar o actualizar un tipo de hora extra
     */
    public function guardarTipo($nombre, $porcentaje) {
        $existe = $this->getByNombre($nombre);
        
        if ($existe) {
            // Actualizar
            $sql = "UPDATE tipos_horas_extras SET porcentaje = ? WHERE nombre = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([floatval($porcentaje), $nombre]);
        } else {
            // Crear
            $sql = "INSERT INTO tipos_horas_extras (nombre, porcentaje, descripcion) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, floatval($porcentaje), 'Tipo de hora extra creado por admin']);
        }
    }

    /**
     * Verificar si un tipo tiene horas extras asociadas
     */
    public function tieneHorasAsociadas($nombre) {
        $sql = "SELECT COUNT(*) as total FROM horas_extras WHERE tipo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Eliminar un tipo de hora extra
     * Solo permite eliminación si no hay horas extras asociadas
     */
    public function eliminarTipo($nombre) {
        // Verificar si tiene horas asociadas
        if ($this->tieneHorasAsociadas($nombre)) {
            throw new \Exception("No se puede eliminar el tipo '{$nombre}' porque tiene horas extras asociadas. Debe eliminar primero las horas extras o cambiarles el tipo.");
        }
        
        $sql = "DELETE FROM tipos_horas_extras WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre]);
    }
    
    /**
     * Desactivar un tipo de hora extra en lugar de eliminarlo
     * (alternativa segura cuando hay horas asociadas)
     */
    public function desactivarTipo($nombre) {
        $sql = "UPDATE tipos_horas_extras SET activo = 0 WHERE nombre = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre]);
    }
    
    /**
     * Obtener solo tipos activos
     */
    public function getAllActivos() {
        $sql = "SELECT * FROM tipos_horas_extras WHERE activo = 1 ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}