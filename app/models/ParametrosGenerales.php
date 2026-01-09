<?php
namespace App\Models;

class ParametrosGenerales {
    private $db;

    public function __construct() {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    public function getAll() {
        $sql = "SELECT * FROM parametros_generales LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: $this->getDefaults();
    }

    public function update($data) {
        $user_id = $_SESSION['user']['id_doc'] ?? null;
        
        $sql = "UPDATE parametros_generales SET 
                uvt = :uvt,
                smlv = :smlv,
                periodo_pago = :periodo_pago,
                formato_divisa = :formato_divisa,
                formato_decimales = :formato_decimales,
                formato_miles = :formato_miles,
                ano_vigencia = :ano_vigencia,
                actualizado_por = :actualizado_por,
                fecha_actualizacion = NOW()
                WHERE id = 1";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':uvt' => $data['uvt'] ?? 0,
            ':smlv' => $data['smlv'] ?? 0,
            ':periodo_pago' => $data['periodo_pago'] ?? 'mensual',
            ':formato_divisa' => $data['formato_divisa'] ?? '$',
            ':formato_decimales' => $data['formato_decimales'] ?? 2,
            ':formato_miles' => $data['formato_miles'] ?? '.',
            ':ano_vigencia' => $data['ano_vigencia'] ?? date('Y'),
            ':actualizado_por' => $user_id
        ]);
    }

    private function getDefaults() {
        return [
            'id' => 1,
            'uvt' => 45286.00,
            'smlv' => 1300000,
            'periodo_pago' => 'mensual',
            'formato_divisa' => '$',
            'formato_decimales' => 2,
            'formato_miles' => '.',
            'ano_vigencia' => date('Y')
        ];
    }
}
?>
