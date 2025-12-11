<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión exitosa a la base de datos\n\n";
    
    echo "Creando tabla 'parametros_generales'...\n";
    
    // Crear tabla parametros_generales
    $pdo->exec("CREATE TABLE IF NOT EXISTS parametros_generales (
        id INT PRIMARY KEY AUTO_INCREMENT,
        uvt DECIMAL(10, 2) NOT NULL DEFAULT 45360,
        smlv DECIMAL(10, 2) NOT NULL DEFAULT 1423000,
        periodo_pago VARCHAR(50) DEFAULT 'mensual',
        formato_divisa VARCHAR(50) DEFAULT 'COP',
        formato_decimales INT DEFAULT 2,
        formato_miles VARCHAR(10) DEFAULT '.',
        ano_vigencia INT DEFAULT 2025,
        actualizado_por INT,
        fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
    )");
    
    echo "  ✓ Tabla 'parametros_generales' creada\n";
    
    // Insertar valores por defecto
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM parametros_generales");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        echo "  ✓ Insertando valores por defecto...\n";
        $pdo->exec("INSERT INTO parametros_generales (uvt, smlv, periodo_pago, formato_divisa, formato_decimales, formato_miles, ano_vigencia) 
                   VALUES (45360, 1423000, 'mensual', 'COP', 2, '.', 2025)");
        echo "    → Valores por defecto insertados\n";
    } else {
        echo "  ✓ Tabla ya contiene datos\n";
    }
    
    echo "\n✓✓✓ Tabla creada y configurada exitosamente ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
