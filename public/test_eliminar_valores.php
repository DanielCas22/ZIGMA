<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ['id' => 1, 'username' => 'admin'];
}

echo "<h1>Test Completo: Eliminar y Valores</h1>";

require_once '../app/models/Model.php';
require_once '../app/models/Empleado.php';
require_once '../app/models/HorasExtras.php';

try {
    echo "<h2>1. Estado actual de horas extras:</h2>";
    
    $pdo = include '../config/database.php';
    $stmt = $pdo->query("SELECT he.*, e.nombre, e.apellidos FROM horas_extras he LEFT JOIN empleados e ON he.empleado_id = e.id_empleados ORDER BY he.id_extras");
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Total registros: " . count($registros) . "</p>";
    
    if (!empty($registros)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 8px;'>ID</th>";
        echo "<th style='padding: 8px;'>Empleado</th>";
        echo "<th style='padding: 8px;'>Fecha</th>";
        echo "<th style='padding: 8px;'>Tipo</th>";
        echo "<th style='padding: 8px;'>Cantidad</th>";
        echo "<th style='padding: 8px;'>Valor</th>";
        echo "<th style='padding: 8px;'>Acciones</th>";
        echo "</tr>";
        
        $total_general_horas = 0;
        $total_general_valor = 0;
        
        foreach ($registros as $reg) {
            $total_general_horas += floatval($reg['cantidad']);
            $total_general_valor += floatval($reg['valor']);
            
            echo "<tr>";
            echo "<td style='padding: 8px;'>" . $reg['id_extras'] . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars(($reg['nombre'] ?? '') . ' ' . ($reg['apellidos'] ?? '')) . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['dia'] . "/" . $reg['mes'] . "/" . $reg['año'] . "</td>";
            echo "<td style='padding: 8px;'>" . htmlspecialchars($reg['tipo']) . "</td>";
            echo "<td style='padding: 8px;'>" . $reg['cantidad'] . " hrs</td>";
            echo "<td style='padding: 8px;'>$" . number_format($reg['valor'], 0, ',', '.') . "</td>";
            echo "<td style='padding: 8px;'>";
            echo "<a href='/ZIGMA/public/index.php?url=HorasExtras/delete/" . $reg['id_extras'] . "' ";
            echo "onclick=\"return confirm('¿Eliminar este registro?')\" ";
            echo "style='background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px;'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "<tr style='background: #d4edda; font-weight: bold;'>";
        echo "<td colspan='4' style='padding: 8px;'>TOTALES GENERALES</td>";
        echo "<td style='padding: 8px;'>" . number_format($total_general_horas, 1) . " hrs</td>";
        echo "<td style='padding: 8px;'>$" . number_format($total_general_valor, 0, ',', '.') . "</td>";
        echo "<td style='padding: 8px;'>-</td>";
        echo "</tr>";
        echo "</table>";
        
        echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Resumen de Valores:</h3>";
        echo "<p><strong>Total Horas Extras:</strong> " . number_format($total_general_horas, 1) . " horas</p>";
        echo "<p><strong>Valor Total General:</strong> $" . number_format($total_general_valor, 0, ',', '.') . "</p>";
        echo "</div>";
        
    } else {
        echo "<p style='color: #666; font-style: italic;'>No hay registros de horas extras.</p>";
        echo "<p><a href='/ZIGMA/public/insertar_datos_prueba.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Insertar Datos de Prueba</a></p>";
    }
    
    echo "<h2>2. Verificar redirección después de eliminar:</h2>";
    echo "<p>Los enlaces de eliminar deben redirigir a: <code>/ZIGMA/public/index.php?url=HorasExtras</code></p>";
    echo "<p style='background: #fff3cd; padding: 10px; border-radius: 5px;'>";
    echo "<strong>Instrucciones:</strong><br>";
    echo "1. Haz clic en un botón 'Eliminar' de la tabla anterior<br>";
    echo "2. Confirma la eliminación<br>";
    echo "3. Verifica que te redirija a la gestión de horas extras (no al login)<br>";
    echo "4. Verifica que los totales se actualicen correctamente";
    echo "</p>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;'>";
    echo "<h3>Error</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<div style='display: flex; gap: 10px; margin: 20px 0;'>";
echo "<a href='/ZIGMA/public/index.php?url=HorasExtras' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir a Horas Extras</a>";
echo "<a href='/ZIGMA/public/index.php?url=HorasExtras/detalle/1' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ver Detalle Empleado</a>";
echo "<a href='/ZIGMA/public/debug_calculos.php' style='background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Debug Cálculos</a>";
echo "</div>";
?>