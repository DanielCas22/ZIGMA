<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Sistema Prestaciones</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin: 10px 0; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Sistema ZIGMA - Prestaciones Sociales</h1>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div class='info'><strong>🕐 Fecha del diagnóstico:</strong> " . date('Y-m-d H:i:s') . "</div>";

// Test 1: Verificar archivos del sistema
echo "<h2>📁 Test 1: Verificación de Archivos</h2>";

$archivos_requeridos = [
    'app/models/ConceptosAdicionalesModel.php' => 'Modelo de conceptos adicionales',
    'app/controllers/PrestacionesSocialesController.php' => 'Controlador de prestaciones',
    'app/views/prestaciones_sociales/index.php' => 'Vista principal'
];

foreach ($archivos_requeridos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        echo "<div class='success'>✅ <strong>$descripcion:</strong> <code>$archivo</code> existe</div>";
    } else {
        echo "<div class='error'>❌ <strong>$descripcion:</strong> <code>$archivo</code> NO ENCONTRADO</div>";
    }
}

// Test 2: Conexión a base de datos
echo "<h2>🗄️ Test 2: Base de Datos</h2>";

try {
    $db = include 'config/database.php';
    echo "<div class='success'>✅ Conexión a base de datos exitosa</div>";
    
    // Verificar tabla conceptos
    $stmt = $db->prepare("SHOW TABLES LIKE 'conceptos_adicionales_prestaciones'");
    $stmt->execute();
    if ($stmt->fetch()) {
        echo "<div class='success'>✅ Tabla <code>conceptos_adicionales_prestaciones</code> existe</div>";
    } else {
        echo "<div class='error'>❌ Tabla <code>conceptos_adicionales_prestaciones</code> no encontrada</div>";
    }
    
    // Contar empleados
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM empleados WHERE sueldo_actual > 0");
    $stmt->execute();
    $result = $stmt->fetch();
    echo "<div class='info'>📊 Empleados con salario asignado: <strong>{$result['total']}</strong></div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error de base de datos: " . $e->getMessage() . "</div>";
}

// Test 3: Modelos
echo "<h2>🧩 Test 3: Modelos</h2>";

try {
    require_once 'config/database.php';
    require_once 'app/models/Model.php';
    require_once 'app/models/ConceptosAdicionalesModel.php';
    
    $conceptosModel = new ConceptosAdicionalesModel();
    echo "<div class='success'>✅ Modelo ConceptosAdicionalesModel creado correctamente</div>";
    
    $conceptos_test = $conceptosModel->obtenerConceptosPorEmpleado(1);
    echo "<div class='info'>📊 Conceptos para empleado ID 1: <strong>" . count($conceptos_test) . "</strong></div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error en modelos: " . $e->getMessage() . "</div>";
}

// Test 4: URL y parámetros
echo "<h2>🌐 Test 4: URLs y Parámetros</h2>";

echo "<div class='info'><strong>URL actual:</strong> " . $_SERVER['REQUEST_URI'] . "</div>";
echo "<div class='info'><strong>Método HTTP:</strong> " . $_SERVER['REQUEST_METHOD'] . "</div>";
echo "<div class='info'><strong>Parámetros GET:</strong> " . json_encode($_GET) . "</div>";

// Test 5: Enlaces importantes
echo "<h2>🔗 Test 5: Enlaces del Sistema</h2>";

$base_url = dirname($_SERVER['SCRIPT_NAME']);
$links = [
    'Dashboard' => $base_url . '/index.php',
    'Prestaciones Sociales' => $base_url . '/index.php?url=PrestacionesSociales',
    'Test Manual Prestaciones' => $_SERVER['SCRIPT_NAME'] . '?test=prestaciones'
];

foreach ($links as $nombre => $url) {
    echo "<div class='info'><strong>$nombre:</strong> <a href='$url' target='_blank'>$url</a></div>";
}

// Test manual si se solicita
if (isset($_GET['test']) && $_GET['test'] === 'prestaciones') {
    echo "<h2>🧪 Test Manual - Prestaciones Sociales</h2>";
    
    try {
        require_once 'app/models/PrestacionesSocialesModel.php';
        require_once 'app/models/Empleado.php';
        
        $prestacionesModel = new PrestacionesSocialesModel();
        $calculoCompleto = $prestacionesModel->calcularPrestacionesTodosEmpleados(360);
        
        echo "<div class='success'>✅ Cálculo de prestaciones ejecutado exitosamente</div>";
        echo "<div class='info'>📊 Total empleados procesados: <strong>{$calculoCompleto['total_empleados']}</strong></div>";
        
        if (!empty($calculoCompleto['empleados'])) {
            echo "<h3>👥 Primeros 3 empleados:</h3>";
            foreach (array_slice($calculoCompleto['empleados'], 0, 3) as $calculo) {
                $emp = $calculo['empleado'];
                echo "<div class='info'>";
                echo "<strong>{$emp['nombre']} {$emp['apellido']}</strong><br>";
                echo "Salario: $" . number_format($emp['salario_mensual']) . "<br>";
                echo "Total prestaciones: $" . number_format($calculo['resumen']['total_prestaciones']);
                echo "</div>";
            }
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error en test manual: " . $e->getMessage() . "</div>";
    }
}

?>

<h2>🎯 Instrucciones</h2>
<div class='info'>
    <p><strong>Si todos los tests son exitosos:</strong></p>
    <ul>
        <li>El sistema debería funcionar correctamente</li>
        <li>Intente limpiar la caché del navegador (Ctrl+F5)</li>
        <li>Verifique que no hay errores en la consola del navegador (F12)</li>
        <li>Acceda a: <code>/ZIGMA/public/index.php?url=PrestacionesSociales</code></li>
    </ul>
</div>

</body>
</html>