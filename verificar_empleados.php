<?php
// Script para verificar empleados en la base de datos
try {
    $db = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Verificación de Empleados en la Base de Datos</h2>";
    
    // Verificar si existe la tabla empleados
    $stmt = $db->query("SHOW TABLES LIKE 'empleados'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ La tabla 'empleados' existe</p>";
        
        // Contar empleados
        $stmt = $db->query("SELECT COUNT(*) as total FROM empleados");
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "<p>Total de empleados: <strong>$total</strong></p>";
        
        if ($total > 0) {
            // Mostrar empleados
            $stmt = $db->query("SELECT * FROM empleados ORDER BY id_empleados");
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Lista de Empleados:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th></tr>";
            
            foreach ($empleados as $emp) {
                echo "<tr>";
                echo "<td>" . $emp['id_empleados'] . "</td>";
                echo "<td>" . $emp['nombre'] . "</td>";
                echo "<td>" . $emp['apellidos'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ No hay empleados registrados en la tabla</p>";
            echo "<p>Insertando empleados de ejemplo...</p>";
            
            // Insertar empleados de ejemplo
            $stmt = $db->prepare("INSERT INTO empleados (nombre, apellidos) VALUES (?, ?)");
            $empleados_ejemplo = [
                ['Juan', 'Pérez'],
                ['María', 'González'],
                ['Carlos', 'López'],
                ['Ana', 'Martínez'],
                ['Luis', 'Rodríguez']
            ];
            
            foreach ($empleados_ejemplo as $emp) {
                $stmt->execute($emp);
            }
            
            echo "<p style='color: green;'>✓ Se insertaron 5 empleados de ejemplo</p>";
            echo "<p><a href='verificar_empleados.php'>Actualizar página</a></p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ La tabla 'empleados' no existe</p>";
        echo "<p>Creando tabla empleados...</p>";
        
        $sql = "CREATE TABLE empleados (
            id_empleados INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            apellidos VARCHAR(100) NOT NULL
        )";
        
        $db->exec($sql);
        echo "<p style='color: green;'>✓ Tabla 'empleados' creada</p>";
        echo "<p><a href='verificar_empleados.php'>Actualizar página</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error de conexión: " . $e->getMessage() . "</p>";
}
?>