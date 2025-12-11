<?php
// Script de diagnóstico y reparación de base de datos

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Diagnóstico y Reparación de Base de Datos</h2>";
    echo "<hr>";
    
    // Función auxiliar para verificar si una columna existe
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
    
    // Función auxiliar para verificar si una tabla existe
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
    
    // ===== CREAR TABLAS DE PARÁMETROS =====
    echo "<h3>📋 Tablas de Parámetros</h3>";
    
    if (!tableExists($pdo, 'rangos_fondo_solidaridad')) {
        echo "<p>✓ Creando tabla 'rangos_fondo_solidaridad'...</p>";
        $pdo->exec("CREATE TABLE rangos_fondo_solidaridad (
          id INT PRIMARY KEY AUTO_INCREMENT,
          desde_smlv DECIMAL(10, 2) NOT NULL,
          hasta_smlv DECIMAL(10, 2) NOT NULL,
          porcentaje DECIMAL(5, 2) NOT NULL
        )");
        
        $pdo->exec("INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
          (1, 4, 0.0),
          (4, 16, 1.0),
          (16, 17, 1.2),
          (17, 18, 1.4),
          (18, 19, 1.6),
          (19, 20, 1.8),
          (20, 999, 2.0)
        ");
    } else {
        echo "<p>✓ Tabla 'rangos_fondo_solidaridad' ya existe</p>";
    }
    
    if (!tableExists($pdo, 'parametros_aportes')) {
        echo "<p>✓ Creando tabla 'parametros_aportes'...</p>";
        $pdo->exec("CREATE TABLE parametros_aportes (
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
        )");
        
        $pdo->exec("INSERT INTO parametros_aportes (salud_empleado, salud_empleador, pension_empleado, pension_empleador, arl, icbf, sena, caja_compensacion, fondo_solidaridad_retencion, fecha_vigencia) 
        VALUES (4.0, 8.5, 4.0, 12.0, 0.522, 3.0, 2.0, 4.0, 1.0, '2025-01-01')");
    } else {
        echo "<p>✓ Tabla 'parametros_aportes' ya existe</p>";
    }
    
    if (!tableExists($pdo, 'parametros_legales')) {
        echo "<p>✓ Creando tabla 'parametros_legales'...</p>";
        $pdo->exec("CREATE TABLE parametros_legales (
          id INT PRIMARY KEY AUTO_INCREMENT,
          smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
          auxilio_transporte DECIMAL(10, 2) NOT NULL DEFAULT 0,
          año_vigencia INT NOT NULL UNIQUE,
          actualizado_por INT,
          fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
        )");
        
        $pdo->exec("INSERT INTO parametros_legales (smlv, auxilio_transporte, año_vigencia) 
        VALUES (1423000, 163000, 2025)");
    } else {
        echo "<p>✓ Tabla 'parametros_legales' ya existe</p>";
    }
    
    if (!tableExists($pdo, 'tabla_retencion_fuente')) {
        echo "<p>✓ Creando tabla 'tabla_retencion_fuente'...</p>";
        $pdo->exec("CREATE TABLE tabla_retencion_fuente (
          id INT PRIMARY KEY AUTO_INCREMENT,
          desde_uvt DECIMAL(10, 2) NOT NULL,
          hasta_uvt DECIMAL(10, 2) NOT NULL,
          porcentaje DECIMAL(5, 2) NOT NULL
        )");
    } else {
        echo "<p>✓ Tabla 'tabla_retencion_fuente' ya existe</p>";
    }
    
    // ===== AGREGAR COLUMNAS FALTANTES =====
    echo "<h3>🔍 Verificando columnas en tablas existentes</h3>";
    
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
        ]
    ];
    
    foreach ($columns_to_add as $table => $columns) {
        // Verificar si la tabla existe primero
        if (!tableExists($pdo, $table)) {
            echo "<p>⚠ Tabla '$table' no existe aún. Se creará cuando sea necesaria.</p>";
            continue;
        }
        
        foreach ($columns as $column => $type) {
            if (!columnExists($pdo, $table, $column)) {
                echo "<p>✓ Agregando columna '$column' a tabla '$table'...</p>";
                try {
                    $pdo->exec("ALTER TABLE $table ADD COLUMN $column $type");
                } catch (Exception $e) {
                    echo "<p>⚠ No se pudo agregar '$column' a '$table': " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p>✓ Columna '$column' ya existe en tabla '$table'</p>";
            }
        }
    }
    
    echo "<h3>✓ Diagnóstico completado</h3>";
    echo "<p style='background-color: #e8f5e9; padding: 10px; border-radius: 4px;'>
        La base de datos ha sido reparada. Intenta acceder a la aplicación nuevamente.
    </p>";
    echo "<p><a href='/ZIGMA/public/index.php' style='display: inline-block; padding: 10px 20px; background-color: #1976d2; color: white; text-decoration: none; border-radius: 4px;'>
        Ir a la aplicación
    </a></p>";
    
} catch (PDOException $e) {
    echo "<h3>❌ Error en la base de datos:</h3>";
    echo "<p><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
    echo "<p><strong>Código de error:</strong> " . $e->getCode() . "</p>";
    echo "<hr>";
    echo "<h4>Soluciones posibles:</h4>";
    echo "<ul>";
    echo "<li>✓ Verifica que MySQL está ejecutándose en XAMPP</li>";
    echo "<li>✓ Verifica que la base de datos 'zigmaog' existe</li>";
    echo "<li>✓ Verifica que el usuario 'root' existe sin contraseña</li>";
    echo "<li>✓ Intenta crear la base de datos ejecutando este comando en MySQL:
        <br><code>CREATE DATABASE IF NOT EXISTS zigmaog;</code></li>";
    echo "</ul>";
}
?>
