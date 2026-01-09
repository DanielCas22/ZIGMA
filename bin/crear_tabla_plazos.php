<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    
    $sql = file_get_contents(__DIR__ . '/../scripts/data/agregar_plazos_conceptos.sql');
    
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $db->exec($stmt);
        }
    }
    
    echo "✅ Tabla de plazos creada exitosamente\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
