<?php
class ParafiscalesModel extends Model {
    public function setConnection(PDO $pdo) { $this->db = $pdo; }

    public function calcularAportesParafiscales($totalDevengado, $auxilioTransporte) {
        $totalDevengado = (float)$totalDevengado;
        $auxilioTransporte = max(0, (float)$auxilioTransporte);
        if ($totalDevengado <= 0) {
            throw new InvalidArgumentException('total_devengado debe ser mayor a 0');
        }
        if ($auxilioTransporte > $totalDevengado) {
            $auxilioTransporte = $totalDevengado; // cap para evitar base negativa
        }
        $base = $totalDevengado - $auxilioTransporte;
        $sena = round($base * 0.02);
        $icbf = round($base * 0.03);
        $comp = round($base * 0.04);
        $total = $sena + $icbf + $comp;
        return [
            'base_calculo' => (int)$base,
            'sena' => (int)$sena,
            'icbf' => (int)$icbf,
            'compensacion' => (int)$comp,
            'total_parafiscales' => (int)$total,
        ];
    }

    public function guardarAportesParafiscales($totalDevengadoId, array $aportes) {
        $ownTx = !$this->db->inTransaction();
        if ($ownTx) { $this->db->beginTransaction(); }
        try {
            $stmt = $this->db->prepare('INSERT INTO parafiscales (valor_total, total_devengado_id) VALUES (?, ?)');
            $stmt->execute([$aportes['total_parafiscales'], $totalDevengadoId]);
            $parafiscalesId = (int)$this->db->lastInsertId();

            // SENA
            $stmtS = $this->db->prepare('INSERT INTO sena (nombre, valor, parafiscales_id) VALUES (?,?,?)');
            $stmtS->execute(['Aporte SENA', $aportes['sena'], $parafiscalesId]);

            // ICBF (requiere fecha)
            $stmtI = $this->db->prepare('INSERT INTO icbf (nombre, mes, dia, anio, valor, parafiscales_id) VALUES (?,?,?,?,?,?)');
            $stmtI->execute(['Aporte ICBF', date('m'), date('d'), date('Y'), $aportes['icbf'], $parafiscalesId]);

            // Caja de Compensación
            $stmtC = $this->db->prepare('INSERT INTO compensacion (valor_total, parafiscales_id) VALUES (?, ?)');
            $stmtC->execute([$aportes['compensacion'], $parafiscalesId]);

            if ($ownTx) { $this->db->commit(); }
            return $parafiscalesId;
        } catch (Throwable $e) {
            if ($ownTx && $this->db->inTransaction()) { $this->db->rollBack(); }
            throw $e;
        }
    }

    public function obtenerAportesPorTotalDevengado($totalDevengadoId) {
        $sql = 'SELECT p.id_parafiscales, p.valor_total,
                       COALESCE(s.valor,0) AS sena,
                       COALESCE(i.valor,0) AS icbf,
                       COALESCE(c.valor_total,0) AS compensacion
                FROM parafiscales p
                LEFT JOIN sena s ON s.parafiscales_id = p.id_parafiscales
                LEFT JOIN icbf i ON i.parafiscales_id = p.id_parafiscales
                LEFT JOIN compensacion c ON c.parafiscales_id = p.id_parafiscales
                WHERE p.total_devengado_id = ?
                ORDER BY p.id_parafiscales DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$totalDevengadoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerAuxilioTransportePorTD($totalDevengadoId) {
        $stmt = $this->db->prepare('SELECT valor FROM auxilio_transporte WHERE total_devengado_id = ? ORDER BY id_transporte DESC LIMIT 1');
        $stmt->execute([$totalDevengadoId]);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    public function obtenerTotalDevengado($totalDevengadoId) {
        $stmt = $this->db->prepare('SELECT * FROM total_devengado WHERE id_total_devengado = ?');
        $stmt->execute([$totalDevengadoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
