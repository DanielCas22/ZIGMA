<?php
namespace App\Models;

use PDO;

class ParametrosModel extends Model {
    public function getParametrosLegales() {
        $sql = "SELECT * FROM parametros_legales ORDER BY año_vigencia DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getRangosFondoSolidaridad() {
        $sql = "SELECT * FROM rangos_fondo_solidaridad ORDER BY desde_smlv";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTablaRetencionFuente() {
        $sql = "SELECT * FROM tabla_retencion_fuente ORDER BY desde_uvt";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getHistorialCambios() {
        $sql = "SELECT * FROM historial_parametros ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function actualizarParametrosLegales($smlv, $auxilio, $anio, $usuario_id) {
        // Validación básica
        if ($smlv <= 0 || $auxilio < 0 || $anio < 2000) return false;
        // Si ya existe un registro para ese año, actualiza. Si no, inserta uno nuevo.
        $sql = "INSERT INTO parametros_legales (smlv, auxilio_transporte, año_vigencia, actualizado_por)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE smlv=VALUES(smlv), auxilio_transporte=VALUES(auxilio_transporte), actualizado_por=VALUES(actualizado_por), fecha_actualizacion=NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$smlv, $auxilio, $anio, $usuario_id]);
        $this->registrarHistorial('Actualizar parámetros legales', $usuario_id, json_encode(['smlv'=>$smlv,'auxilio'=>$auxilio,'anio'=>$anio]));
        return true;
    }

    public function actualizarRangosSolidaridad($rangos, $usuario_id) {
        // Validación de rangos
        foreach ($rangos as $r) {
            if ($r['desde_smlv'] < 0 || $r['hasta_smlv'] < $r['desde_smlv'] || $r['porcentaje'] < 0 || $r['porcentaje'] > 100) return false;
        }
        // Eliminar y volver a insertar (simplificado)
        $this->db->exec('DELETE FROM rangos_fondo_solidaridad');
        $sql = "INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        foreach ($rangos as $r) {
            $stmt->execute([$r['desde_smlv'], $r['hasta_smlv'], $r['porcentaje']]);
        }
        $this->registrarHistorial('Actualizar rangos fondo solidaridad', $usuario_id, json_encode($rangos));
        return true;
    }

    public function actualizarTablaRetencion($tabla, $usuario_id) {
        foreach ($tabla as $t) {
            if ($t['desde_uvt'] < 0 || $t['hasta_uvt'] < $t['desde_uvt'] || $t['porcentaje'] < 0 || $t['porcentaje'] > 100) return false;
        }
        $this->db->exec('DELETE FROM tabla_retencion_fuente');
        $sql = "INSERT INTO tabla_retencion_fuente (desde_uvt, hasta_uvt, porcentaje) VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        foreach ($tabla as $t) {
            $stmt->execute([$t['desde_uvt'], $t['hasta_uvt'], $t['porcentaje']]);
        }
        $this->registrarHistorial('Actualizar tabla retención fuente', $usuario_id, json_encode($tabla));
        return true;
    }

    public function registrarHistorial($accion, $usuario_id, $detalle) {
        $sql = "INSERT INTO historial_parametros (fecha, actualizado_por, accion, detalle) VALUES (NOW(), ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $accion, $detalle]);
    }

    public function getExportData() {
        return [
            'parametros_legales' => $this->getParametrosLegales(),
            'rangos_fondo_solidaridad' => $this->getRangosFondoSolidaridad(),
            'tabla_retencion_fuente' => $this->getTablaRetencionFuente(),
            'historial' => $this->getHistorialCambios()
        ];
    }
    public function getParametrosVigentes() {
        $sql = "SELECT * FROM parametros_legales ORDER BY año_vigencia DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Métodos para actualizar, validar y exportar se agregan después

    // Actualiza solo el auxilio de transporte y su año de vigencia
    public function actualizarAuxilioTransporte($auxilio, $anio, $usuario_id) {
        if ($auxilio < 0 || $anio < 2000) return false;
        $sql = "UPDATE parametros_legales SET auxilio_transporte = ?, actualizado_por = ?, fecha_actualizacion = NOW() WHERE año_vigencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$auxilio, $usuario_id, $anio]);
        $this->registrarHistorial('Actualizar auxilio transporte', $usuario_id, json_encode(['auxilio'=>$auxilio,'anio'=>$anio]));
        return true;
    }
    // Actualiza solo el salario mínimo legal vigente para el año dado
    public function actualizarSalarioMinimo($smlv, $anio, $usuario_id) {
        if ($smlv <= 0 || $anio < 2000) return false;
        $sql = "UPDATE parametros_legales SET smlv = ?, actualizado_por = ?, fecha_actualizacion = NOW() WHERE año_vigencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$smlv, $usuario_id, $anio]);
        $this->registrarHistorial('Actualizar SMLV', $usuario_id, json_encode(['smlv'=>$smlv,'anio'=>$anio]));
        return true;
    }
}
