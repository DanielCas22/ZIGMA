<?php
// Comprueba esquema mínimo para horas_extras
// Uso: php bin\check_schema.php

chdir(__DIR__ . '/../');

$dbFile = __DIR__ . '/../config/database.php';
if (!file_exists($dbFile)) {
    fwrite(STDERR, "Falta config/database.php\n");
    exit(2);
}

$pdo = require $dbFile;
if (!($pdo instanceof PDO)) {
    fwrite(STDERR, "config/database.php no devolvió PDO\n");
    exit(2);
}

$schema = [];
$schema['table'] = 'horas_extras';
$requiredColumns = [
    'fecha_creacion',
    'estado',
    'aprobado_por',
    'fecha_aprobacion',
    'comentario_aprobacion'
];

echo "Comprobando esquema para tabla: {$schema['table']}\n";

// Check table exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
$stmt->execute([$schema['table']]);
$tableExists = $stmt->fetchColumn() > 0;
if (!$tableExists) {
    fwrite(STDERR, "ERROR: La tabla '{$schema['table']}' no existe.\n");
    exit(3);
}
echo " - Tabla existe.\n";

$allOk = true;
// Check columns
foreach ($requiredColumns as $col) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
    $stmt->execute([$schema['table'], $col]);
    $exists = $stmt->fetchColumn() > 0;
    if ($exists) {
        echo " - Columna '$col': OK\n";
    } else {
        echo " - Columna '$col': MISSING\n";
        $allOk = false;
    }
}

// Check FK aprobado_por -> user(id_doc)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE k JOIN information_schema.REFERENTIAL_CONSTRAINTS r ON k.CONSTRAINT_NAME = r.CONSTRAINT_NAME AND k.CONSTRAINT_SCHEMA = r.CONSTRAINT_SCHEMA WHERE k.CONSTRAINT_SCHEMA = DATABASE() AND k.TABLE_NAME = 'horas_extras' AND k.COLUMN_NAME = 'aprobado_por' AND r.REFERENCED_TABLE_NAME = 'user' AND k.REFERENCED_COLUMN_NAME = 'id_doc'");
$stmt->execute();
$fkExists = $stmt->fetchColumn() > 0;
if ($fkExists) {
    echo " - FK aprobado_por -> user(id_doc): OK\n";
} else {
    echo " - FK aprobado_por -> user(id_doc): MISSING\n";
    $allOk = false;
}

// Summary
if ($allOk) {
    echo "\nEsquema OK.\n";
    exit(0);
} else {
    echo "\nFaltan elementos del esquema. Ejecuta bin/migrate.php y revisa logs/migrations.log si hay fallos.\n";
    exit(4);
}

?>
