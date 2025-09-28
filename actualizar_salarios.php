<?php
// Script para actualizar salarios por rol con los valores correctos
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "=== ACTUALIZANDO SALARIOS POR ROL ===\n\n";
    
    // Valores correctos de salarios
    $salarios_correctos = [
        'empleado' => 1423000,
        'rrhh' => 2000000,
        'admin' => 4000000
    ];
    
    // Actualizar cada rol
    foreach ($salarios_correctos as $rol => $salario) {
        // Primero verificar si existe el registro
        $stmt = $db->prepare("SELECT * FROM salarios_por_rol WHERE rol = ?");
        $stmt->execute([$rol]);
        $existe = $stmt->fetch();
        
        if ($existe) {
            // Actualizar registro existente
            $stmt = $db->prepare("UPDATE salarios_por_rol SET salario = ? WHERE rol = ?");
            $resultado = $stmt->execute([$salario, $rol]);
            if ($resultado) {
                echo "✅ Actualizado: $rol = $" . number_format($salario, 0) . "\n";
            } else {
                echo "❌ Error actualizando: $rol\n";
            }
        } else {
            // Crear nuevo registro
            $stmt = $db->prepare("INSERT INTO salarios_por_rol (rol, salario) VALUES (?, ?)");
            $resultado = $stmt->execute([$rol, $salario]);
            if ($resultado) {
                echo "✅ Creado: $rol = $" . number_format($salario, 0) . "\n";
            } else {
                echo "❌ Error creando: $rol\n";
            }
        }
    }
    
    echo "\n=== VERIFICACIÓN ===\n";
    $stmt = $db->query("SELECT * FROM salarios_por_rol ORDER BY salario DESC");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resultados as $row) {
        echo "- {$row['rol']}: $" . number_format($row['salario'], 0) . "\n";
    }
    
    echo "\n✅ Salarios actualizados correctamente\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>