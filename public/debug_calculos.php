<?php
echo "<h1>Debug: Verificar Cálculos de Horas Extras</h1>";

require_once '../app/models/Model.php';
require_once '../app/models/Empleado.php';
require_once '../app/models/HorasExtras.php';

try {
    $empleadoModel = new Empleado();
    $horasExtrasModel = new HorasExtras();
    
    echo "<h2>1. Verificar empleados disponibles:</h2>";
    $empleados = $empleadoModel->getAllWithRoles();
    echo "<p>Total empleados: " . count($empleados) . "</p>";
    
    echo "<h2>2. Verificar horas extras por empleado:</h2>";
    
    foreach ($empleados as $empleado) {
        echo "<h3>Empleado: " . htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']) . " (ID: {$empleado['id_empleados']})</h3>";
        
        $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado['id_empleados']);
        echo "<p>Registros de horas extras: " . count($horasExtras) . "</p>";
        
        if (!empty($horasExtras)) {
            $total_horas = 0;
            $total_valor = 0;
            
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
            echo "<tr style='background: #f0f0f0;'>";
            echo "<th style='padding: 5px;'>Fecha</th>";
            echo "<th style='padding: 5px;'>Tipo</th>";
            echo "<th style='padding: 5px;'>Cantidad</th>";
            echo "<th style='padding: 5px;'>Valor</th>";
            echo "</tr>";
            
            foreach ($horasExtras as $he) {
                $total_horas += floatval($he['cantidad']);
                $total_valor += floatval($he['valor']);
                
                echo "<tr>";
                echo "<td style='padding: 5px;'>" . $he['dia'] . "/" . $he['mes'] . "/" . $he['año'] . "</td>";
                echo "<td style='padding: 5px;'>" . htmlspecialchars($he['tipo']) . "</td>";
                echo "<td style='padding: 5px;'>" . $he['cantidad'] . " hrs</td>";
                echo "<td style='padding: 5px;'>$" . number_format($he['valor'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            
            echo "<tr style='background: #d4edda; font-weight: bold;'>";
            echo "<td colspan='2' style='padding: 5px;'>TOTALES</td>";
            echo "<td style='padding: 5px;'>" . number_format($total_horas, 1) . " hrs</td>";
            echo "<td style='padding: 5px;'>$" . number_format($total_valor, 0, ',', '.') . "</td>";
            echo "</tr>";
            echo "</table>";
            
        } else {
            echo "<p style='color: #666; font-style: italic;'>Sin horas extras registradas</p>";
        }
        
        echo "<hr>";
    }
    
    echo "<h2>3. Verificar cálculo global:</h2>";
    
    // Simular el cálculo que hace el controlador
    $total_horas_global = 0;
    $total_valor_global = 0;
    
    foreach ($empleados as &$empleado) {
        $horasExtras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado['id_empleados']);
        $empleado['total_horas'] = 0;
        $empleado['total_valor'] = 0;
        
        foreach ($horasExtras as $he) {
            $empleado['total_horas'] += floatval($he['cantidad']);
            $empleado['total_valor'] += floatval($he['valor']);
        }
        
        $total_horas_global += $empleado['total_horas'];
        $total_valor_global += $empleado['total_valor'];
    }
    
    echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 5px;'>";
    echo "<h3>Totales Globales:</h3>";
    echo "<p><strong>Total Horas:</strong> " . number_format($total_horas_global, 1) . " horas</p>";
    echo "<p><strong>Total Valor:</strong> $" . number_format($total_valor_global, 0, ',', '.') . "</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;'>";
    echo "<h3>Error</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='/ZIGMA/public/index.php?url=HorasExtras' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir a Horas Extras</a></p>";
?>