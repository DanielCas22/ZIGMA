<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Model.php';
require_once __DIR__ . '/app/models/Empleado.php';
require_once __DIR__ . '/app/models/HorasExtras.php';
require_once __DIR__ . '/app/models/DevengadoModel.php';
require_once __DIR__ . '/app/models/TotalDeducidoModel.php';
require_once __DIR__ . '/app/models/ConceptosAdicionalesDeduciblesModel.php';
require_once __DIR__ . '/app/models/ConceptosAdicionalesModel.php';
require_once __DIR__ . '/app/models/RetencionFuenteModel.php';
require_once __DIR__ . '/app/models/ParametrosModel.php';

try {
    // Obtener primer empleado de prueba
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id_empleados FROM empleados LIMIT 1");
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$empleado) {
        echo "❌ No hay empleados en la base de datos\n";
        exit(1);
    }
    
    $idEmpleado = $empleado['id_empleados'];
    echo "Calculando para empleado ID: $idEmpleado\n";
    echo "================================================\n\n";
    
    // Crear instancia del modelo
    $deducidoModel = new \App\Models\TotalDeducidoModel();
    
    // Intentar calcular
    $resultado = $deducidoModel->calcularTotalDeducidoCompleto($idEmpleado);
    
    echo "✓ Cálculo exitoso!\n";
    echo "\nEmpleado: {$resultado['empleado']['nombre']} {$resultado['empleado']['apellido']}\n";
    echo "Total Deducciones: $" . number_format($resultado['resumen']['total_deducciones'], 2) . "\n";
    echo "\nDesglose:\n";
    foreach ($resultado['resumen']['desglose'] as $concepto => $valor) {
        echo "  • " . ucfirst(str_replace('_', ' ', $concepto)) . ": $" . number_format($valor, 2) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}
?>
