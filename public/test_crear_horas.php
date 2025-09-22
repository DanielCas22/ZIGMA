<?php
session_start();

// Simular sesión de usuario para la prueba
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ['id' => 1, 'username' => 'admin'];
}

echo "<h1>Prueba de Creación de Horas Extras</h1>";
echo "<p>Fecha actual: " . date('Y-m-d H:i:s') . "</p>";

// Incluir modelos
require_once '../app/models/Model.php';
require_once '../app/models/HorasExtras.php';
require_once '../app/models/TarifaHora.php';
require_once '../app/models/TipoHoraExtra.php';

try {
    echo "<h2>1. Creando registro de prueba</h2>";
    
    $horasExtrasModel = new HorasExtras();
    
    // Datos de prueba
    $datos_prueba = [
        'empleado_id' => 1, // Asumiendo que existe un empleado con ID 1
        'cantidad' => 2,
        'tipo' => 'Extra nocturna',
        'dia' => 22,
        'mes' => 9,
        'año' => 2025
    ];
    
    echo "<h3>Datos a insertar:</h3>";
    echo "<ul>";
    foreach ($datos_prueba as $campo => $valor) {
        echo "<li><strong>$campo:</strong> $valor</li>";
    }
    echo "</ul>";
    
    // Intentar crear el registro
    $resultado = $horasExtrasModel->create($datos_prueba);
    
    if ($resultado) {
        echo "<div style='color: green; background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
        echo "✓ <strong>¡Éxito!</strong> El registro de horas extras se creó correctamente.";
        echo "</div>";
        
        echo "<h3>El sistema calculó automáticamente:</h3>";
        echo "<p>- El valor monetario según las tarifas de Colombia 2025</p>";
        echo "<p>- El porcentaje correspondiente al tipo de hora extra (75% para nocturna)</p>";
        
    } else {
        echo "<div style='color: red; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "✗ <strong>Error:</strong> No se pudo crear el registro.";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "✗ <strong>Error:</strong> " . $e->getMessage();
    echo "<br><strong>Archivo:</strong> " . $e->getFile();
    echo "<br><strong>Línea:</strong> " . $e->getLine();
    echo "</div>";
}

echo "<hr>";
echo "<a href='/ZIGMA/public/index.php?url=HorasExtras' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ver Horas Extras</a>";
echo " ";
echo "<a href='/ZIGMA/public/index.php?url=HorasExtras/create' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Crear Nueva</a>";
?>