<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión exitosa a la base de datos\n";
    
    // Función para verificar si una columna existe
    function columnExists($pdo, $table, $column) {
        try {
            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME=? AND COLUMN_NAME=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$table, $column]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Función para verificar si una tabla existe
    function tableExists($pdo, $table) {
        try {
            $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$table]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // 1. Crear/reparar tabla rangos_fondo_solidaridad
    echo "✓ Verificando tabla rangos_fondo_solidaridad...\n";
    if (!tableExists($pdo, 'rangos_fondo_solidaridad')) {
        $pdo->exec('CREATE TABLE rangos_fondo_solidaridad (
          id INT PRIMARY KEY AUTO_INCREMENT,
          desde_smlv DECIMAL(10, 2) NOT NULL,
          hasta_smlv DECIMAL(10, 2) NOT NULL,
          porcentaje DECIMAL(5, 2) NOT NULL
        )');
        echo "  → Tabla creada\n";
    } else {
        echo "  → Tabla ya existe\n";
    }
    
    // Insertar datos en rangos_fondo_solidaridad
    $pdo->exec('TRUNCATE TABLE rangos_fondo_solidaridad');
    $pdo->exec('INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
      (1, 4, 0.0),
      (4, 16, 1.0),
      (16, 17, 1.2),
      (17, 18, 1.4),
      (18, 19, 1.6),
      (19, 20, 1.8),
      (20, 999, 2.0)
    ');
    echo "  → Datos poblados\n";
    
    // 2. Crear/reparar tabla parametros_aportes
    echo "✓ Verificando tabla parametros_aportes...\n";
    if (!tableExists($pdo, 'parametros_aportes')) {
        $pdo->exec('CREATE TABLE parametros_aportes (
          id INT PRIMARY KEY AUTO_INCREMENT,
          salud_empleado DECIMAL(5, 2) NOT NULL DEFAULT 4.0,
          salud_empleador DECIMAL(5, 2) NOT NULL DEFAULT 8.5,
          pension_empleado DECIMAL(5, 2) NOT NULL DEFAULT 4.0,
          pension_empleador DECIMAL(5, 2) NOT NULL DEFAULT 12.0,
          arl DECIMAL(5, 3) NOT NULL DEFAULT 0.522,
          icbf DECIMAL(5, 2) NOT NULL DEFAULT 3.0,
          sena DECIMAL(5, 2) NOT NULL DEFAULT 2.0,
          caja_compensacion DECIMAL(5, 2) NOT NULL DEFAULT 4.0,
          fondo_solidaridad_retencion DECIMAL(5, 2) NOT NULL DEFAULT 1.0,
          fecha_vigencia DATE NOT NULL,
          creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
        echo "  → Tabla creada\n";
    } else {
        echo "  → Tabla ya existe\n";
        
        // Agregar columnas faltantes
        if (!columnExists($pdo, 'parametros_aportes', 'fecha_vigencia')) {
            $pdo->exec('ALTER TABLE parametros_aportes ADD COLUMN fecha_vigencia DATE NOT NULL DEFAULT "2025-01-01"');
            echo "  → Columna fecha_vigencia agregada\n";
        }
    }
    
    // Insertar valores por defecto
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM parametros_aportes');
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    if ($count == 0) {
        $pdo->exec("INSERT INTO parametros_aportes (salud_empleado, salud_empleador, pension_empleado, pension_empleador, arl, icbf, sena, caja_compensacion, fondo_solidaridad_retencion, fecha_vigencia) VALUES (4.0, 8.5, 4.0, 12.0, 0.522, 3.0, 2.0, 4.0, 1.0, '2025-01-01')");
        echo "  → Datos poblados\n";
    }
    
    // 3. Crear/reparar tabla parametros_legales
    echo "✓ Verificando tabla parametros_legales...\n";
    if (!tableExists($pdo, 'parametros_legales')) {
        $pdo->exec('CREATE TABLE parametros_legales (
          id INT PRIMARY KEY AUTO_INCREMENT,
          smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
          auxilio_transporte DECIMAL(10, 2) NOT NULL DEFAULT 0,
          año_vigencia INT NOT NULL UNIQUE,
          actualizado_por INT,
          fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )');
        echo "  → Tabla creada\n";
    } else {
        echo "  → Tabla ya existe\n";
        
        // Agregar columnas faltantes
        if (!columnExists($pdo, 'parametros_legales', 'smlv')) {
            $pdo->exec('ALTER TABLE parametros_legales ADD COLUMN smlv DECIMAL(10, 2) NOT NULL DEFAULT 1423000');
            echo "  → Columna smlv agregada\n";
        }
        if (!columnExists($pdo, 'parametros_legales', 'auxilio_transporte')) {
            $pdo->exec('ALTER TABLE parametros_legales ADD COLUMN auxilio_transporte DECIMAL(10, 2) NOT NULL DEFAULT 163000');
            echo "  → Columna auxilio_transporte agregada\n";
        }
    }
    
    // Insertar valores por defecto
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM parametros_legales');
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    if ($count == 0) {
        $pdo->exec("INSERT INTO parametros_legales (smlv, auxilio_transporte, año_vigencia) VALUES (1423000, 163000, 2025)");
        echo "  → Datos poblados\n";
    }
    
    // 4. Agregar columnas a tablas existentes
    echo "✓ Verificando columnas en otras tablas...\n";
    
    $columns_to_add = [
        'nomina' => [
            'empleado_id' => 'INT',
            'estado' => "VARCHAR(50) DEFAULT 'pendiente'"
        ],
        'horas_extras' => [
            'empleado_id' => 'INT',
            'estado' => "VARCHAR(50) DEFAULT 'pendiente'"
        ],
        'user' => [
            'empleado_id' => 'INT'
        ],
        'total_devengado' => [
            'empleado_id' => 'INT'
        ],
        'total_deducido' => [
            'empleado_id' => 'INT'
        ],
        'conceptos_adicionales_prestaciones' => [
            'empleado_id' => 'INT',
            'activo' => "TINYINT(1) DEFAULT 1",
            'fecha_creacion' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            'creado_por' => "VARCHAR(100)",
            'total_plazos' => "INT DEFAULT 1",
            'tipo_plazo' => "VARCHAR(50) DEFAULT 'quincena'",
            'tiene_plazo' => "TINYINT(1) DEFAULT 0"
        ]
    ];
    
    foreach ($columns_to_add as $table => $columns) {
        if (tableExists($pdo, $table)) {
            foreach ($columns as $column => $type) {
                if (!columnExists($pdo, $table, $column)) {
                    try {
                        $pdo->exec("ALTER TABLE $table ADD COLUMN $column $type");
                        echo "  → Columna '$column' agregada a tabla '$table'\n";
                    } catch (Exception $e) {
                        echo "  ⚠ No se pudo agregar '$column' a '$table': " . $e->getMessage() . "\n";
                    }
                }
            }
        }
    }
    
    echo "\n✓✓✓ Base de datos reparada y configurada exitosamente ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Archivo: " . $e->getFile() . "\n";
    echo "   Línea: " . $e->getLine() . "\n";
    exit(1);
}
?>
