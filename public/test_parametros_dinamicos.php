<?php
/**
 * Script de prueba FINAL para comprobar que los cambios en la BD afectan los cálculos
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\TotalDeducidoModel;
use App\Models\RetencionFuenteModel;
use App\Models\ParametrosModel;

$db = require __DIR__ . '/../config/database.php';

echo "<h1>Prueba de Cambios Dinámicos en Parámetros</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    table { border-collapse: collapse; margin: 20px 0; width: 100%; background: white; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    .section { margin: 30px 0; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .valor { font-weight: bold; color: #2196F3; }
    .original { background-color: #e3f2fd; }
    .modificado { background-color: #fff3e0; }
    .exito { color: #4CAF50; font-weight: bold; }
    .diferencia { background-color: #ffeb3b; font-weight: bold; }
    h2 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
    h3 { color: #555; }
</style>";

// ========== PARTE 1: FONDO DE SOLIDARIDAD ==========
echo "<div class='section'>";
echo "<h2>🔍 PRUEBA 1: Fondo de Solidaridad</h2>";

$salario_prueba = 6000000;
echo "<p>Salario de prueba: <span class='valor'>$" . number_format($salario_prueba, 0, ',', '.') . "</span></p>";

// Calcular ANTES del cambio
$deducidoModel = new TotalDeducidoModel();
$fondo_antes = $deducidoModel->calcularFondoSolidaridad($salario_prueba);

echo "<h3>📊 Valores ANTES del cambio</h3>";
echo "<table class='original'>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td>SMLV Equivalente</td><td>" . number_format($fondo_antes['smlv_equivalente'], 2) . "</td></tr>";
echo "<tr><td>Rango aplicable</td><td>{$fondo_antes['rango']}</td></tr>";
echo "<tr><td>Porcentaje</td><td>{$fondo_antes['porcentaje']}%</td></tr>";
echo "<tr><td>Valor a pagar</td><td class='valor'>$" . number_format($fondo_antes['valor'], 0, ',', '.') . "</td></tr>";
echo "</table>";

// MODIFICAR temporalmente el porcentaje
$stmt = $db->prepare("SELECT porcentaje FROM rangos_fondo_solidaridad WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute();
$original = $stmt->fetch(PDO::FETCH_ASSOC);
$porcentaje_original = $original['porcentaje'];

$nuevo_porcentaje = 3.0; // Cambiar de 1.0% a 3.0%
$stmt = $db->prepare("UPDATE rangos_fondo_solidaridad SET porcentaje = ? WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute([$nuevo_porcentaje]);

echo "<p><strong>🔧 Modificación temporal aplicada:</strong> Porcentaje cambiado de <span class='valor'>{$porcentaje_original}%</span> a <span class='valor'>{$nuevo_porcentaje}%</span></p>";

// Crear nueva instancia para que consulte de nuevo
$deducidoModel2 = new TotalDeducidoModel();
$fondo_despues = $deducidoModel2->calcularFondoSolidaridad($salario_prueba);

echo "<h3>📊 Valores DESPUÉS del cambio</h3>";
echo "<table class='modificado'>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td>SMLV Equivalente</td><td>" . number_format($fondo_despues['smlv_equivalente'], 2) . "</td></tr>";
echo "<tr><td>Rango aplicable</td><td>{$fondo_despues['rango']}</td></tr>";
echo "<tr><td>Porcentaje</td><td>{$fondo_despues['porcentaje']}%</td></tr>";
echo "<tr><td>Valor a pagar</td><td class='valor'>$" . number_format($fondo_despues['valor'], 0, ',', '.') . "</td></tr>";
echo "</table>";

$diferencia_fondo = $fondo_despues['valor'] - $fondo_antes['valor'];
echo "<h3>📈 Comparación</h3>";
echo "<table>";
echo "<tr><th>Concepto</th><th>Antes</th><th>Después</th><th>Diferencia</th></tr>";
echo "<tr>";
echo "<td>Porcentaje</td>";
echo "<td>{$fondo_antes['porcentaje']}%</td>";
echo "<td>{$fondo_despues['porcentaje']}%</td>";
echo "<td class='diferencia'>+" . ($fondo_despues['porcentaje'] - $fondo_antes['porcentaje']) . "%</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Valor a pagar</td>";
echo "<td>$" . number_format($fondo_antes['valor'], 0, ',', '.') . "</td>";
echo "<td>$" . number_format($fondo_despues['valor'], 0, ',', '.') . "</td>";
echo "<td class='diferencia'>+$" . number_format($diferencia_fondo, 0, ',', '.') . "</td>";
echo "</tr>";
echo "</table>";

// Restaurar valor original
$stmt = $db->prepare("UPDATE rangos_fondo_solidaridad SET porcentaje = ? WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute([$porcentaje_original]);

echo "<p class='exito'>✓ Valor original restaurado: {$porcentaje_original}%</p>";

if ($diferencia_fondo > 0) {
    echo "<p class='exito'>✅ ¡ÉXITO! El cambio en la base de datos SÍ afecta el cálculo del fondo de solidaridad.</p>";
} else {
    echo "<p style='color: red;'>❌ ERROR: El cambio NO afectó el cálculo.</p>";
}

echo "</div>";

// ========== PARTE 2: RETENCIÓN EN LA FUENTE ==========
echo "<div class='section'>";
echo "<h2>🔍 PRUEBA 2: Retención en la Fuente</h2>";

$salario_alto = 10000000; // 10 millones
echo "<p>Salario de prueba: <span class='valor'>$" . number_format($salario_alto, 0, ',', '.') . "</span></p>";

$retencionModel = new RetencionFuenteModel();

// Calcular ANTES del cambio
$datos = [
    'pension' => $salario_alto * 0.04,
    'fondo_solidaridad' => $salario_alto * 0.01
];
$retencion_antes = $retencionModel->calcularProcedimiento1($salario_alto, $datos);

echo "<h3>📊 Valores ANTES del cambio</h3>";
echo "<table class='original'>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td>Base retención (UVT)</td><td>" . number_format($retencion_antes['base_retencion_uvt'], 2) . "</td></tr>";
echo "<tr><td>Retención Art. 383</td><td class='valor'>$" . number_format($retencion_antes['retencion_art383'], 0, ',', '.') . "</td></tr>";
echo "</table>";

// MODIFICAR temporalmente un porcentaje de retención
$stmt = $db->prepare("SELECT porcentaje FROM tabla_retencion_fuente WHERE desde_uvt = 150 AND hasta_uvt = 360");
$stmt->execute();
$original_ret = $stmt->fetch(PDO::FETCH_ASSOC);
$porc_ret_original = $original_ret['porcentaje'];

$nuevo_porc_ret = 35; // Cambiar de 28% a 35%
$stmt = $db->prepare("UPDATE tabla_retencion_fuente SET porcentaje = ? WHERE desde_uvt = 150 AND hasta_uvt = 360");
$stmt->execute([$nuevo_porc_ret]);

echo "<p><strong>🔧 Modificación temporal aplicada:</strong> Porcentaje retención (150-360 UVT) cambiado de <span class='valor'>{$porc_ret_original}%</span> a <span class='valor'>{$nuevo_porc_ret}%</span></p>";

// Nueva instancia y cálculo
$retencionModel2 = new RetencionFuenteModel();
$retencion_despues = $retencionModel2->calcularProcedimiento1($salario_alto, $datos);

echo "<h3>📊 Valores DESPUÉS del cambio</h3>";
echo "<table class='modificado'>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td>Base retención (UVT)</td><td>" . number_format($retencion_despues['base_retencion_uvt'], 2) . "</td></tr>";
echo "<tr><td>Retención Art. 383</td><td class='valor'>$" . number_format($retencion_despues['retencion_art383'], 0, ',', '.') . "</td></tr>";
echo "</table>";

$diferencia_ret = $retencion_despues['retencion_art383'] - $retencion_antes['retencion_art383'];
echo "<h3>📈 Comparación</h3>";
echo "<table>";
echo "<tr><th>Concepto</th><th>Antes</th><th>Después</th><th>Diferencia</th></tr>";
echo "<tr>";
echo "<td>Retención en fuente</td>";
echo "<td>$" . number_format($retencion_antes['retencion_art383'], 0, ',', '.') . "</td>";
echo "<td>$" . number_format($retencion_despues['retencion_art383'], 0, ',', '.') . "</td>";
echo "<td class='diferencia'>+$" . number_format($diferencia_ret, 0, ',', '.') . "</td>";
echo "</tr>";
echo "</table>";

// Restaurar
$stmt = $db->prepare("UPDATE tabla_retencion_fuente SET porcentaje = ? WHERE desde_uvt = 150 AND hasta_uvt = 360");
$stmt->execute([$porc_ret_original]);

echo "<p class='exito'>✓ Valor original restaurado: {$porc_ret_original}%</p>";

if (abs($diferencia_ret) > 0) {
    echo "<p class='exito'>✅ ¡ÉXITO! El cambio en la base de datos SÍ afecta el cálculo de la retención en la fuente.</p>";
} else {
    echo "<p style='color: red;'>❌ ERROR: El cambio NO afectó el cálculo.</p>";
}

echo "</div>";

// ========== CONCLUSIÓN FINAL ==========
echo "<div class='section'>";
echo "<h2>🎯 Conclusión Final</h2>";

if ($diferencia_fondo > 0 && abs($diferencia_ret) > 0) {
    echo "<p class='exito' style='font-size: 18px;'>✅ ¡PRUEBA EXITOSA! Ambos cálculos ahora responden dinámicamente a los cambios en la base de datos.</p>";
    echo "<ul>";
    echo "<li>✓ Fondo de Solidaridad: Consulta <code>rangos_fondo_solidaridad</code></li>";
    echo "<li>✓ Retención en la Fuente: Consulta <code>tabla_retencion_fuente</code></li>";
    echo "</ul>";
    echo "<p><strong>Ahora puedes modificar los valores desde el panel de administración y los cálculos se actualizarán automáticamente.</strong></p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ Algunos cálculos no se actualizaron correctamente. Revisa los modelos.</p>";
}

echo "</div>";
?>
