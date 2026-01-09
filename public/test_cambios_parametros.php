<?php
/**
 * Script de prueba para verificar que los cambios en los parámetros
 * de retención y fondo de solidaridad afecten correctamente los cálculos
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\TotalDeducidoModel;
use App\Models\RetencionFuenteModel;
use App\Models\ParametrosModel;

$db = require __DIR__ . '/../config/database.php';

echo "<h1>Prueba de Cambios en Parámetros</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #4CAF50; color: white; }
    .section { margin: 30px 0; padding: 20px; background: #f9f9f9; }
    .valor { font-weight: bold; color: #2196F3; }
</style>";

// 1. Mostrar valores actuales en las tablas
echo "<div class='section'>";
echo "<h2>1. Valores Actuales en la Base de Datos</h2>";

echo "<h3>Rangos de Fondo de Solidaridad</h3>";
$stmt = $db->query("SELECT * FROM rangos_fondo_solidaridad ORDER BY desde_smlv");
$rangos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table>";
echo "<tr><th>Desde SMLV</th><th>Hasta SMLV</th><th>Porcentaje</th></tr>";
foreach ($rangos as $rango) {
    echo "<tr><td>{$rango['desde_smlv']}</td><td>{$rango['hasta_smlv']}</td><td>{$rango['porcentaje']}%</td></tr>";
}
echo "</table>";

echo "<h3>Tabla de Retención en la Fuente</h3>";
$stmt = $db->query("SELECT * FROM tabla_retencion_fuente ORDER BY desde_uvt");
$tabla_retencion = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<table>";
echo "<tr><th>Desde UVT</th><th>Hasta UVT</th><th>Porcentaje</th></tr>";
foreach ($tabla_retencion as $row) {
    echo "<tr><td>{$row['desde_uvt']}</td><td>{$row['hasta_uvt']}</td><td>{$row['porcentaje']}%</td></tr>";
}
echo "</table>";
echo "</div>";

// 2. Calcular con valores actuales
echo "<div class='section'>";
echo "<h2>2. Cálculos con Valores Actuales</h2>";

$salario_prueba = 6000000; // 6 millones de pesos
echo "<p>Salario de prueba: <span class='valor'>$" . number_format($salario_prueba, 0, ',', '.') . "</span></p>";

$deducidoModel = new TotalDeducidoModel();
$fondo_actual = $deducidoModel->calcularFondoSolidaridad($salario_prueba);

echo "<h3>Fondo de Solidaridad</h3>";
echo "<table>";
echo "<tr><th>Campo</th><th>Valor</th></tr>";
echo "<tr><td>SMLV Equivalente</td><td>{$fondo_actual['smlv_equivalente']}</td></tr>";
echo "<tr><td>Rango</td><td>{$fondo_actual['rango']}</td></tr>";
echo "<tr><td>Porcentaje</td><td>{$fondo_actual['porcentaje']}%</td></tr>";
echo "<tr><td>Valor a pagar</td><td class='valor'>$" . number_format($fondo_actual['valor'], 0, ',', '.') . "</td></tr>";
echo "</table>";

echo "</div>";

// 3. Modificar temporalmente los valores y recalcular
echo "<div class='section'>";
echo "<h2>3. Prueba: Modificar Valores y Recalcular</h2>";
echo "<p><strong>Vamos a cambiar temporalmente un rango de fondo de solidaridad</strong></p>";

// Guardar valor original
$stmt = $db->prepare("SELECT porcentaje FROM rangos_fondo_solidaridad WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute();
$original = $stmt->fetch(PDO::FETCH_ASSOC);
$porcentaje_original = $original['porcentaje'];

echo "<p>Porcentaje original (4-16 SMLV): <span class='valor'>{$porcentaje_original}%</span></p>";

// Cambiar temporalmente
$nuevo_porcentaje = 2.5;
$stmt = $db->prepare("UPDATE rangos_fondo_solidaridad SET porcentaje = ? WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute([$nuevo_porcentaje]);

echo "<p>Nuevo porcentaje temporal: <span class='valor'>{$nuevo_porcentaje}%</span></p>";

// Recalcular (nota: el modelo actual usa constantes, por eso vamos a calcular manualmente)
$parametrosModel = new ParametrosModel();
$params = $parametrosModel->getParametrosVigentes();
$smlv = $params['smlv'];
$salario_en_smlv = $salario_prueba / $smlv;

// Obtener el nuevo valor de la base de datos
$stmt = $db->prepare("SELECT * FROM rangos_fondo_solidaridad WHERE ? > desde_smlv AND ? <= hasta_smlv");
$stmt->execute([$salario_en_smlv, $salario_en_smlv]);
$nuevo_rango = $stmt->fetch(PDO::FETCH_ASSOC);

if ($nuevo_rango) {
    $nuevo_valor_fondo = ($salario_prueba * $nuevo_rango['porcentaje']) / 100;
    
    echo "<h3>Resultado con nuevo porcentaje (consultando BD)</h3>";
    echo "<table>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    echo "<tr><td>Porcentaje</td><td>{$nuevo_rango['porcentaje']}%</td></tr>";
    echo "<tr><td>Valor a pagar</td><td class='valor'>$" . number_format($nuevo_valor_fondo, 0, ',', '.') . "</td></tr>";
    echo "<tr><td>Diferencia con anterior</td><td class='valor'>$" . number_format($nuevo_valor_fondo - $fondo_actual['valor'], 0, ',', '.') . "</td></tr>";
    echo "</table>";
}

// Restaurar valor original
$stmt = $db->prepare("UPDATE rangos_fondo_solidaridad SET porcentaje = ? WHERE desde_smlv = 4 AND hasta_smlv = 16");
$stmt->execute([$porcentaje_original]);

echo "<p style='color: green;'>✓ Valor original restaurado</p>";
echo "</div>";

// 4. Conclusión
echo "<div class='section'>";
echo "<h2>4. Conclusión</h2>";
echo "<p><strong>PROBLEMA DETECTADO:</strong></p>";
echo "<ul>";
echo "<li>El modelo <code>TotalDeducidoModel</code> usa constantes hardcodeadas (FONDO_SOLIDARIDAD_RANGOS) en lugar de consultar la base de datos.</li>";
echo "<li>El modelo <code>RetencionFuenteModel</code> usa valores del archivo config/uvt.php en lugar de la tabla tabla_retencion_fuente.</li>";
echo "<li>Por lo tanto, aunque cambies los valores en la base de datos, <strong>NO afectarán los cálculos actuales</strong>.</li>";
echo "</ul>";
echo "<p><strong>SOLUCIÓN:</strong></p>";
echo "<ul>";
echo "<li>Modificar TotalDeducidoModel para que consulte rangos_fondo_solidaridad en lugar de usar constantes.</li>";
echo "<li>Modificar RetencionFuenteModel para que consulte tabla_retencion_fuente en lugar de usar config/uvt.php.</li>";
echo "</ul>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>5. Siguiente Paso</h2>";
echo "<p>¿Quieres que actualice los modelos para que consulten la base de datos?</p>";
echo "</div>";
?>
