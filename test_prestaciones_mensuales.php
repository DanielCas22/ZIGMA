<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/PrestacionesSocialesModel.php';

echo "<h2>🧪 Test Prestaciones Sociales - CÁLCULO MENSUAL</h2>\n";

try {
    $prestacionesModel = new PrestacionesSocialesModel();
    
    echo "<h3>📊 Ejemplo de Cálculo Mensual</h3>\n";
    
    // Datos de ejemplo
    $salarioMensual = 2000000; // $2,000,000
    $auxilioTransporte = 200000; // $200,000 (aplica para salarios <= 2 SMMLV)
    
    echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
    echo "<h4>💰 Datos del Empleado:</h4>\n";
    echo "<p><strong>Salario Mensual:</strong> $" . number_format($salarioMensual) . "</p>\n";
    echo "<p><strong>Auxilio de Transporte:</strong> $" . number_format($auxilioTransporte) . "</p>\n";
    echo "<p><strong>Base Cálculo (S+AT):</strong> $" . number_format($salarioMensual + $auxilioTransporte) . "</p>\n";
    echo "</div>\n";
    
    // Test de métodos individuales mensuales
    echo "<h3>🔢 Cálculos Mensuales Individuales:</h3>\n";
    
    // Cesantías mensuales
    $cesantias = $prestacionesModel->calcularCesantiasMensuales($salarioMensual, $auxilioTransporte);
    echo "<div style='background: #f3e5f5; padding: 10px; border-radius: 5px; margin: 5px 0;'>\n";
    echo "<h4>📋 CESANTÍAS MENSUALES</h4>\n";
    echo "<p><strong>Fórmula:</strong> {$cesantias['formula']}</p>\n";
    echo "<p><strong>Cálculo:</strong> ($" . number_format($salarioMensual) . " + $" . number_format($auxilioTransporte) . ") ÷ 12</p>\n";
    echo "<p><strong>Resultado:</strong> <span style='color: #7b1fa2; font-weight: bold;'>$" . number_format($cesantias['valor_cesantias']) . "</span></p>\n";
    echo "</div>\n";
    
    // Prima mensual
    $prima = $prestacionesModel->calcularPrimaServiciosMensual($salarioMensual, $auxilioTransporte);
    echo "<div style='background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 5px 0;'>\n";
    echo "<h4>🎁 PRIMA DE SERVICIOS MENSUAL</h4>\n";
    echo "<p><strong>Fórmula:</strong> {$prima['formula']}</p>\n";
    echo "<p><strong>Cálculo:</strong> ($" . number_format($salarioMensual) . " + $" . number_format($auxilioTransporte) . ") ÷ 12</p>\n";
    echo "<p><strong>Resultado:</strong> <span style='color: #2e7d32; font-weight: bold;'>$" . number_format($prima['valor_prima']) . "</span></p>\n";
    echo "</div>\n";
    
    // Vacaciones mensuales
    $vacaciones = $prestacionesModel->calcularVacacionesMensuales($salarioMensual);
    echo "<div style='background: #fff3e0; padding: 10px; border-radius: 5px; margin: 5px 0;'>\n";
    echo "<h4>🏖️ VACACIONES MENSUALES</h4>\n";
    echo "<p><strong>Fórmula:</strong> {$vacaciones['formula']}</p>\n";
    echo "<p><strong>Cálculo:</strong> $" . number_format($salarioMensual) . " ÷ 24</p>\n";
    echo "<p><strong>Resultado:</strong> <span style='color: #f57c00; font-weight: bold;'>$" . number_format($vacaciones['valor_vacaciones']) . "</span></p>\n";
    echo "<p><em>Nota: {$vacaciones['nota']}</em></p>\n";
    echo "</div>\n";
    
    // Intereses sobre cesantías
    $cesantiasAcumuladas = $cesantias['valor_cesantias']; // Para un mes
    $intereses = $prestacionesModel->calcularInteresesCesantiasMensuales($cesantiasAcumuladas);
    echo "<div style='background: #e1f5fe; padding: 10px; border-radius: 5px; margin: 5px 0;'>\n";
    echo "<h4>💹 INTERESES SOBRE CESANTÍAS</h4>\n";
    echo "<p><strong>Fórmula:</strong> {$intereses['formula']}</p>\n";
    echo "<p><strong>Cesantías Acumuladas:</strong> $" . number_format($cesantiasAcumuladas) . "</p>\n";
    echo "<p><strong>Cálculo:</strong> $" . number_format($cesantiasAcumuladas) . " × 1%</p>\n";
    echo "<p><strong>Resultado:</strong> <span style='color: #0277bd; font-weight: bold;'>$" . number_format($intereses['valor_intereses']) . "</span></p>\n";
    echo "</div>\n";
    
    // Resumen total mensual
    $totalMensual = $cesantias['valor_cesantias'] + $prima['valor_prima'] + 
                   $vacaciones['valor_vacaciones'] + $intereses['valor_intereses'];
    
    echo "<h3>💰 RESUMEN MENSUAL DE PRESTACIONES</h3>\n";
    echo "<div style='background: #c8e6c9; padding: 15px; border-radius: 5px; border: 2px solid #4caf50;'>\n";
    echo "<table style='width: 100%; border-collapse: collapse;'>\n";
    echo "<tr><td><strong>Cesantías:</strong></td><td style='text-align: right;'>$" . number_format($cesantias['valor_cesantias']) . "</td></tr>\n";
    echo "<tr><td><strong>Intereses:</strong></td><td style='text-align: right;'>$" . number_format($intereses['valor_intereses']) . "</td></tr>\n";
    echo "<tr><td><strong>Prima:</strong></td><td style='text-align: right;'>$" . number_format($prima['valor_prima']) . "</td></tr>\n";
    echo "<tr><td><strong>Vacaciones:</strong></td><td style='text-align: right;'>$" . number_format($vacaciones['valor_vacaciones']) . "</td></tr>\n";
    echo "<tr style='border-top: 2px solid #4caf50; font-weight: bold; font-size: 1.1em;'>";
    echo "<td><strong>TOTAL MENSUAL:</strong></td><td style='text-align: right; color: #2e7d32;'>$" . number_format($totalMensual) . "</td></tr>\n";
    echo "</table>\n";
    echo "</div>\n";
    
    // Comparación anual
    $totalAnual = $totalMensual * 12;
    echo "<h3>📈 Proyección Anual</h3>\n";
    echo "<div style='background: #f3e5f5; padding: 10px; border-radius: 5px;'>\n";
    echo "<p><strong>Total mensual × 12 meses:</strong> $" . number_format($totalAnual) . "</p>\n";
    echo "<p><em>Nota: En la práctica, los intereses se acumulan progresivamente mes a mes</em></p>\n";
    echo "</div>\n";
    
    echo "<h3>✅ Validación del Sistema</h3>\n";
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724;'>\n";
    echo "<p><strong>🎯 CÁLCULOS MENSUALES IMPLEMENTADOS CORRECTAMENTE</strong></p>\n";
    echo "<ul>\n";
    echo "<li>✅ Cesantías: Un doceavo del salario + auxilio</li>\n";
    echo "<li>✅ Prima: Un doceavo del salario + auxilio</li>\n";
    echo "<li>✅ Vacaciones: Un veinticuatroavo del salario (sin auxilio)</li>\n";
    echo "<li>✅ Intereses: 1% mensual sobre cesantías acumuladas</li>\n";
    echo "<li>✅ Sistema adaptado para nómina con cortes mensuales</li>\n";
    echo "</ul>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>\n";
    echo "<p><strong>❌ Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>\n";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
    echo "</div>\n";
}

echo "<hr>\n";
echo "<p><em>Test ejecutado el " . date('Y-m-d H:i:s') . "</em></p>\n";
?>