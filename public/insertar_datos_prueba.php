<?php
echo "<h1>Insertar Datos de Prueba en Horas Extras</h1>";

require_once '../app/models/Model.php';
require_once '../app/models/HorasExtras.php';

try {
    $horasExtrasModel = new HorasExtras();
    
    // Datos de prueba
    $datos_prueba = [
        [
            'empleado_id' => 1,
            'cantidad' => 2,
            'tipo' => 'Extra nocturna',
            'dia' => 22,
            'mes' => 9,
            'año' => 2025
        ],
        [
            'empleado_id' => 1, 
            'cantidad' => 4,
            'tipo' => 'Extra diurna',
            'dia' => 21,
            'mes' => 9,
            'año' => 2025
        ]
    ];
    
    echo "<h2>Insertando datos de prueba...</h2>";
    
    foreach ($datos_prueba as $index => $datos) {
        echo "<h3>Registro " . ($index + 1) . ":</h3>";
        echo "<ul>";
        foreach ($datos as $campo => $valor) {
            echo "<li><strong>$campo:</strong> $valor</li>";
        }
        echo "</ul>";
        
        $resultado = $horasExtrasModel->create($datos);
        
        if ($resultado) {
            echo "<p style='color: green;'>✓ Registro insertado exitosamente</p>";
        } else {
            echo "<p style='color: red;'>✗ Error al insertar registro</p>";
        }
        echo "<hr>";
    }
    
    echo "<h2>Verificar datos insertados:</h2>";
    
    // Verificar con una consulta directa
    $pdo = include '../config/database.php';
    $stmt = $pdo->query("SELECT * FROM horas_extras");
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Total de registros en horas_extras: " . count($registros) . "</p>";
    
    if (!empty($registros)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 8px;'>ID</th>";
        echo "<th style='padding: 8px;'>Empleado ID</th>";
        echo "<th style='padding: 8px;'>Cantidad</th>";
        echo "<th style='padding: 8px;'>Tipo</th>";
        echo "<th style='padding: 8px;'>Valor</th>";
        echo "<th style='padding: 8px;'>Fecha</th>";
        echo "</tr>";
        
        foreach ($registros as $reg) {
            echo "<tr>";
            echo "<td style='padding: 8px;'>" . $reg['id_extras'] . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['empleado_id'] . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['cantidad'] . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['tipo'] . "</td>";
            echo "<td style='padding: 8px;'>$" . number_format($reg['valor'], 0, ',', '.') . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['dia'] . "/" . $reg['mes'] . "/" . $reg['año'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
}

echo "<hr>";
echo "<p><a href='/ZIGMA/public/index.php?url=HorasExtras' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ver Horas Extras</a></p>";
?>