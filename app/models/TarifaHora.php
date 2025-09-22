<?php

class TarifaHora extends Model {
    
    /**
     * Obtener la tarifa vigente para una fecha específica
     */
    public function getTarifaVigente($fecha = null) {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }
        
        $sql = "SELECT * FROM tarifas_horas 
                WHERE fecha_inicio <= ? AND fecha_fin >= ? 
                ORDER BY fecha_inicio DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha, $fecha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las tarifas
     */
    public function getAll() {
        $sql = "SELECT * FROM tarifas_horas ORDER BY fecha_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Calcular valor de horas extras
     * @param float $valor_hora - Valor base por hora
     * @param float $cantidad - Cantidad de horas trabajadas
     * @param int $porcentaje - Porcentaje de recargo (25, 75, 105, 155)
     * @return float - Valor total calculado
     */
    public function calcularValorHorasExtras($valor_hora, $cantidad, $porcentaje) {
        // Calcular factor de recargo (1 + porcentaje/100)
        $factor_recargo = 1 + ($porcentaje / 100);
        
        // Calcular valor total
        $valor_total = floatval($valor_hora) * floatval($cantidad) * $factor_recargo;
        
        return $valor_total;
    }
    
    /**
     * Crear nueva tarifa
     */
    public function create($datos) {
        $sql = "INSERT INTO tarifas_horas (fecha_inicio, fecha_fin, valor_hora, descripcion) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['fecha_inicio'], 
            $datos['fecha_fin'],
            $datos['valor_hora'],
            $datos['descripcion']
        ]);
    }
}