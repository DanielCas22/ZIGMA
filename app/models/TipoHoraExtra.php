<?php

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
}