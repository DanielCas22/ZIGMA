<?php
// Script para crear las tablas de parámetros faltantes

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Creando tablas de parámetros...</h2>";
    
    // Crear tabla parametros_legales
    echo "<p>✓ Creando tabla 'parametros_legales'...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS parametros_legales (
      id INT PRIMARY KEY AUTO_INCREMENT,
      smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
      auxilio_transporte DECIMAL(10, 2) NOT NULL DEFAULT 0,
      año_vigencia INT NOT NULL UNIQUE,
      actualizado_por INT,
      fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      FOREIGN KEY (actualizado_por) REFERENCES user(id_doc)
    )");
    
    // Crear tabla rangos_fondo_solidaridad
    echo "<p>✓ Creando tabla 'rangos_fondo_solidaridad'...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS rangos_fondo_solidaridad (
      id INT PRIMARY KEY AUTO_INCREMENT,
      desde_smlv DECIMAL(10, 2) NOT NULL,
      hasta_smlv DECIMAL(10, 2) NOT NULL,
      porcentaje DECIMAL(5, 2) NOT NULL
    )");
    
    // Insertar datos en rangos_fondo_solidaridad
    echo "<p>✓ Poblando tabla 'rangos_fondo_solidaridad'...</p>";
    $pdo->exec("INSERT IGNORE INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
      (1, 4, 0.0),
      (4, 16, 1.0),
      (16, 17, 1.2),
      (17, 18, 1.4),
      (18, 19, 1.6),
      (19, 20, 1.8),
      (20, 999, 2.0)
    ");
    
    // Crear tabla tabla_retencion_fuente
    echo "<p>✓ Creando tabla 'tabla_retencion_fuente'...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS tabla_retencion_fuente (
      id INT PRIMARY KEY AUTO_INCREMENT,
      desde_uvt DECIMAL(10, 2) NOT NULL,
      hasta_uvt DECIMAL(10, 2) NOT NULL,
      porcentaje DECIMAL(5, 2) NOT NULL
    )");
    
    // Crear tabla parametros_aportes si no existe
    echo "<p>✓ Creando tabla 'parametros_aportes'...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS parametros_aportes (
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
    
    // Insertar valores por defecto en parametros_aportes si está vacía
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM parametros_aportes");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    if ($count == 0) {
        echo "<p>✓ Poblando tabla 'parametros_aportes' con valores por defecto...</p>";
        $pdo->exec("INSERT INTO parametros_aportes (salud_empleado, salud_empleador, pension_empleado, pension_empleador, arl, icbf, sena, caja_compensacion, fondo_solidaridad_retencion, fecha_vigencia) 
        VALUES (4.0, 8.5, 4.0, 12.0, 0.522, 3.0, 2.0, 4.0, 1.0, '2025-01-01')");
    }
    
    // Insertar parámetros legales por defecto si está vacía
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM parametros_legales");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    if ($count == 0) {
        echo "<p>✓ Poblando tabla 'parametros_legales' con valores por defecto...</p>";
        $pdo->exec("INSERT INTO parametros_legales (smlv, auxilio_transporte, año_vigencia) 
        VALUES (1423000, 163000, 2025)");
    }
    
    echo "<h3>✓ Tablas de parámetros creadas y pobladas correctamente</h3>";
    echo "<p><a href='/ZIGMA/public/index.php'>Volver a la aplicación</a></p>";
    
} catch (PDOException $e) {
    echo "<h3>Error al crear las tablas:</h3>";
    echo "<p><strong>" . $e->getMessage() . "</strong></p>";
    echo "<p>Asegúrate de que:</p>";
    echo "<ul>";
    echo "<li>El servidor MySQL está ejecutándose en XAMPP</li>";
    echo "<li>La base de datos 'zigmaog' existe</li>";
    echo "<li>El usuario 'root' tiene acceso sin contraseña</li>";
    echo "</ul>";
}
?>
