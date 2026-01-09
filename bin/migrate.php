<?php
// Migrator simple para ejecutar archivos .sql en scripts/data
// Uso: php bin\migrate.php

$cwd = __DIR__ . '/../';
chdir($cwd);

// Cargar configuración de base de datos (devuelve PDO)
$dbFile = __DIR__ . '/../config/database.php';
if (!file_exists($dbFile)) {
    echo "No se encontró config/database.php\n";
    exit(1);
}

$pdo = require $dbFile; // debe retornar PDO
if (!($pdo instanceof PDO)) {
    echo "config/database.php no devolvió una instancia de PDO\n";
    exit(1);
}

$migrationsDir = __DIR__ . '/../scripts/data';
$argv = $_SERVER['argv'];
$dryRun = in_array('--dry-run', $argv) || in_array('-n', $argv);
$single = null;
foreach ($argv as $i => $a) {
    if ($a === '--single' && isset($argv[$i+1])) {
        $single = $argv[$i+1];
    }
}
if (!is_dir($migrationsDir)) {
    echo "No existe el directorio de migraciones: $migrationsDir\n";
    exit(1);
}

// Obtener archivos .sql ordenados
$files = glob($migrationsDir . '/*.sql');
sort($files, SORT_NATURAL);
if ($single) {
    $path = $migrationsDir . '/' . $single;
    if (file_exists($path)) {
        $files = [$path];
    } else {
        echo "No se encontró la migración especificada: $single\n";
        exit(2);
    }
}
sort($files, SORT_NATURAL);

if (empty($files)) {
    echo "No se encontraron archivos .sql en scripts/data\n";
    exit(0);
}

echo "Se ejecutarán " . count($files) . " migraciones desde scripts/data\n";

foreach ($files as $file) {
    $name = basename($file);
    echo "\n--> Ejecutando: $name ... ";
    $sql = file_get_contents($file);
    if ($sql === false) {
        echo "[ERROR lectura]\n";
        continue;
    }
    if ($dryRun) {
        echo "[DRY-RUN]\n";
        echo $sql . "\n";
        continue;
    }
    try {
        $pdo->beginTransaction();
        // Intentamos ejecutar todo el contenido; algunos archivos contienen múltiples instrucciones
        $pdo->exec($sql);
        $pdo->commit();
        echo "OK\n";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "FALLÓ - " . $e->getMessage() . "\n";
        // Registrar y continuar con la siguiente migración
        file_put_contents(__DIR__ . '/../logs/migrations.log', date('c') . " - $name - " . $e->getMessage() . "\n", FILE_APPEND);
        continue;
    }
}

echo "\nMigraciones finalizadas. Revisar logs/migrations.log en caso de errores.\n";

?>
