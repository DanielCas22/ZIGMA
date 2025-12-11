<?php
/**
 * Script para limpiar horas extras de prueba
 * Elimina las horas extras creadas el 11/12/2025 y el tipo "Extra especial nocturna"
 */

// Conexión a la base de datos
try {
    $db = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limpieza de Horas Extras de Prueba</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        h1 {
            color: #2d3748;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .exito {
            background: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .warning {
            background: #fffaf0;
            border-left: 4px solid #ed8936;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .error {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .info {
            background: #ebf8ff;
            border-left: 4px solid #4299e1;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-danger {
            background: #f56565;
        }
        .btn-danger:hover {
            background: #e53e3e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧹 Limpieza de Horas Extras de Prueba</h1>
        <p class="subtitle">Eliminar datos de prueba del sistema</p>

<?php
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

if ($accion === '') {
    // Mostrar lo que se va a eliminar
    echo "<div class='warning'>";
    echo "<strong>⚠️ ADVERTENCIA:</strong> Esta acción eliminará los siguientes registros de prueba:";
    echo "</div>";
    
    // Horas extras del 11/12/2025
    $sql = "SELECT id_extras, empleado_id, tipo, cantidad, valor, estado FROM horas_extras WHERE dia = 11 AND mes = 12 AND anio = 2025";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $horas_prueba = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Horas extras creadas el 11/12/2025:</h3>";
    if (count($horas_prueba) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Empleado ID</th><th>Tipo</th><th>Cantidad</th><th>Valor</th><th>Estado</th></tr>";
        foreach ($horas_prueba as $hora) {
            echo "<tr>";
            echo "<td>{$hora['id_extras']}</td>";
            echo "<td>{$hora['empleado_id']}</td>";
            echo "<td>{$hora['tipo']}</td>";
            echo "<td>{$hora['cantidad']}</td>";
            echo "<td>\$" . number_format($hora['valor'], 0, ',', '.') . "</td>";
            echo "<td>{$hora['estado']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='info'>No hay horas extras del 11/12/2025 para eliminar.</div>";
    }
    
    // Tipo "Extra especial nocturna"
    $sql = "SELECT * FROM tipos_horas_extras WHERE nombre = 'Extra especial nocturna'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $tipo_especial = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Tipo de hora extra 'Extra especial nocturna':</h3>";
    if ($tipo_especial) {
        echo "<div class='warning'>";
        echo "<strong>ID:</strong> {$tipo_especial['id_tipo']}<br>";
        echo "<strong>Nombre:</strong> {$tipo_especial['nombre']}<br>";
        echo "<strong>Porcentaje:</strong> {$tipo_especial['porcentaje']}%<br>";
        echo "<strong>Activo:</strong> " . ($tipo_especial['activo'] ? 'Sí' : 'No');
        echo "</div>";
        
        // Contar horas con este tipo
        $sql = "SELECT COUNT(*) as total FROM horas_extras WHERE tipo = 'Extra especial nocturna'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($count['total'] > 0) {
            echo "<div class='error'>";
            echo "<strong>⚠️ IMPORTANTE:</strong> Hay {$count['total']} hora(s) extra(s) que usan este tipo. ";
            echo "Se eliminarán primero las horas extras antes de eliminar el tipo.";
            echo "</div>";
        }
    } else {
        echo "<div class='info'>El tipo 'Extra especial nocturna' ya no existe en la base de datos.</div>";
    }
    
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='?accion=confirmar' class='btn btn-danger'>❌ Confirmar Eliminación</a>";
    echo "<a href='/ZIGMA/public/index.php?url=HorasExtras' class='btn'>↩️ Cancelar</a>";
    echo "</div>";
    
} elseif ($accion === 'confirmar') {
    echo "<h3>Ejecutando limpieza...</h3>";
    
    $errores = [];
    $exitos = [];
    
    try {
        $db->beginTransaction();
        
        // 1. Eliminar horas extras del 11/12/2025
        $sql = "DELETE FROM horas_extras WHERE dia = 11 AND mes = 12 AND anio = 2025";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $deleted_horas = $stmt->rowCount();
        $exitos[] = "Eliminadas {$deleted_horas} hora(s) extra(s) del 11/12/2025";
        
        // 2. Verificar si quedan horas con el tipo "Extra especial nocturna"
        $sql = "SELECT COUNT(*) as total FROM horas_extras WHERE tipo = 'Extra especial nocturna'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($count['total'] == 0) {
            // 3. Eliminar el tipo si no hay horas asociadas
            $sql = "DELETE FROM tipos_horas_extras WHERE nombre = 'Extra especial nocturna'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $exitos[] = "Eliminado el tipo 'Extra especial nocturna'";
            } else {
                $exitos[] = "El tipo 'Extra especial nocturna' ya no existía";
            }
        } else {
            $errores[] = "Aún quedan {$count['total']} hora(s) extra(s) con el tipo 'Extra especial nocturna' (de otras fechas)";
        }
        
        $db->commit();
        
        echo "<div class='exito'>";
        echo "<strong>✅ Limpieza completada exitosamente</strong><br><br>";
        foreach ($exitos as $msg) {
            echo "• {$msg}<br>";
        }
        echo "</div>";
        
        if (count($errores) > 0) {
            echo "<div class='warning'>";
            echo "<strong>⚠️ Advertencias:</strong><br>";
            foreach ($errores as $msg) {
                echo "• {$msg}<br>";
            }
            echo "</div>";
        }
        
    } catch (Exception $e) {
        $db->rollBack();
        echo "<div class='error'>";
        echo "<strong>❌ Error durante la limpieza:</strong><br>";
        echo htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='/ZIGMA/public/index.php?url=HorasExtras' class='btn'>↩️ Volver a Horas Extras</a>";
    echo "</div>";
}

?>

        <div style="text-align: center; margin-top: 40px; padding: 20px; background: #f7fafc; border-radius: 10px;">
            <p style="color: #718096; font-size: 14px;">
                <strong>Script de limpieza - <?= date('d/m/Y H:i:s') ?></strong><br>
                Sistema ZIGMA - Gestión de Nómina
            </p>
        </div>
    </div>
</body>
</html>
