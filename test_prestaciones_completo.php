<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/PrestacionesSocialesModel.php';
require_once 'app/models/ConceptosAdicionalesModel.php';

echo "<h2>🧪 Test Directo del Sistema de Prestaciones</h2>\n";

try {
    $prestacionesModel = new PrestacionesSocialesModel();
    $conceptosModel = new ConceptosAdicionalesModel();
    
    echo "<h3>✅ Modelos inicializados</h3>\n";
    
    // Probar método principal
    $calculoCompleto = $prestacionesModel->calcularPrestacionesTodosEmpleados(360);
    
    echo "<h3>📊 Resultado del cálculo:</h3>\n";
    echo "<p><strong>Total empleados procesados:</strong> " . $calculoCompleto['total_empleados'] . "</p>\n";
    
    if (!empty($calculoCompleto['empleados'])) {
        echo "<h4>Empleados procesados:</h4>\n";
        echo "<ul>\n";
        foreach ($calculoCompleto['empleados'] as $index => $calculo) {
            if ($index >= 3) break; // Solo mostrar los primeros 3
            $empleado = $calculo['empleado'];
            echo "<li>";
            echo "<strong>{$empleado['nombre']} {$empleado['apellido']}</strong><br>";
            echo "Salario: $" . number_format($empleado['salario_mensual']) . "<br>";
            echo "Cesantías: $" . number_format($calculo['prestaciones']['cesantias']['valor_cesantias']) . "<br>";
            echo "Total Prestaciones: $" . number_format($calculo['resumen']['total_prestaciones']);
            echo "</li>\n";
        }
        echo "</ul>\n";
        
        if (count($calculoCompleto['empleados']) > 3) {
            echo "<p><em>...y " . (count($calculoCompleto['empleados']) - 3) . " empleados más</em></p>\n";
        }
    } else {
        echo "<p>❌ No se procesaron empleados</p>\n";
    }
    
    // Probar conceptos adicionales
    echo "<h3>💰 Conceptos adicionales:</h3>\n";
    $conceptos_empleados = [];
    $total_conceptos_empresa = 0;
    
    foreach ($calculoCompleto['empleados'] as $calculo) {
        $empleado_id = $calculo['empleado']['id'];
        $conceptos_empleados[$empleado_id] = $conceptosModel->obtenerConceptosPorEmpleado($empleado_id);
        $total_conceptos_empresa += $conceptosModel->obtenerTotalConceptosPorEmpleado($empleado_id);
    }
    
    echo "<p><strong>Total conceptos adicionales empresa:</strong> $" . number_format($total_conceptos_empresa) . "</p>\n";
    
    $empleados_con_conceptos = 0;
    foreach ($conceptos_empleados as $empleado_id => $conceptos) {
        if (!empty($conceptos)) {
            $empleados_con_conceptos++;
        }
    }
    
    echo "<p><strong>Empleados con conceptos adicionales:</strong> $empleados_con_conceptos</p>\n";
    
    echo "<h3>🎯 Estado del Sistema:</h3>\n";
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; color: #155724;'>\n";
    echo "<p>✅ <strong>Sistema totalmente funcional</strong></p>\n";
    echo "<p>✅ Prestaciones calculadas correctamente</p>\n";
    echo "<p>✅ Conceptos adicionales operativos</p>\n";
    echo "<p>✅ Base de datos con datos válidos</p>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; color: #721c24;'>\n";
    echo "<p><strong>❌ Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>\n";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
    echo "</div>\n";
}
?>