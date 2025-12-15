<?php
/**
 * VERIFICADOR Y INICIALIZADOR AUTOMÁTICO DE BASE DE DATOS
 * Archivo: config/db_init.php
 * 
 * Este archivo verifica que la base de datos tenga todas las tablas necesarias
 * y las crea automáticamente si faltan. Se ejecuta en cada inicio de la aplicación.
 */

class DatabaseInitializer {
    private $pdo;
    private $tables_created = [];
    private $errors = [];
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Inicia el proceso de verificación e inicialización
     */
    public function initialize() {
        try {
            // Paso 1: Verificar que la base de datos existe
            $this->verifyDatabase();
            
            // Paso 2: Verificar y crear tablas
            $this->createAllTables();
            
            // Paso 3: Insertar datos iniciales
            $this->insertInitialData();
            
            return [
                'success' => true,
                'message' => 'Base de datos inicializada correctamente',
                'tables_created' => $this->tables_created,
                'errors' => $this->errors
            ];
        } catch (Exception $e) {
            error_log("Error en inicialización de BD: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error en inicialización: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ];
        }
    }
    
    /**
     * Verifica que la base de datos exista
     */
    private function verifyDatabase() {
        try {
            $this->pdo->query("SELECT 1");
        } catch (Exception $e) {
            throw new Exception("No se pudo conectar a la base de datos: " . $e->getMessage());
        }
    }
    
    /**
     * Verifica si una tabla existe
     */
    private function tableExists($table) {
        try {
            $result = $this->pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table}'");
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Verifica si una columna existe en una tabla
     */
    private function columnExists($table, $column) {
        try {
            $result = $this->pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table}' AND COLUMN_NAME = '{$column}'");
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Crea todas las tablas necesarias
     */
    private function createAllTables() {
        // Roles
        if (!$this->tableExists('rol')) {
            $this->pdo->exec("CREATE TABLE rol (
                id_rol INT PRIMARY KEY AUTO_INCREMENT,
                nombre VARCHAR(45) NOT NULL UNIQUE
            )");
            $this->tables_created[] = 'rol';
        }
        
        // Empleados
        if (!$this->tableExists('empleados')) {
            $this->pdo->exec("CREATE TABLE empleados (
                id_empleados INT PRIMARY KEY AUTO_INCREMENT,
                nombre VARCHAR(45) NOT NULL,
                apellido VARCHAR(45) NOT NULL,
                usuario VARCHAR(45),
                contrasena VARCHAR(255),
                sueldo_actual DECIMAL(15,2),
                auxilio_transporte DECIMAL(10, 2) DEFAULT 0,
                cedula VARCHAR(45),
                email VARCHAR(100),
                telefono VARCHAR(20),
                direccion VARCHAR(255),
                fecha_ingreso DATE,
                estado VARCHAR(45) DEFAULT 'activo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_estado (estado),
                INDEX idx_cedula (cedula)
            )");
            $this->tables_created[] = 'empleados';
        }
        
        // Users
        if (!$this->tableExists('user')) {
            $this->pdo->exec("CREATE TABLE user (
                id_doc INT PRIMARY KEY AUTO_INCREMENT,
                tipo_doc VARCHAR(45),
                num_doc VARCHAR(45),
                username VARCHAR(45) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                empleado_id INT,
                estado VARCHAR(45) DEFAULT 'activo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE SET NULL,
                INDEX idx_username (username),
                INDEX idx_estado (estado)
            )");
            $this->tables_created[] = 'user';
        }
        
        // Rol_has_user
        if (!$this->tableExists('rol_has_user')) {
            $this->pdo->exec("CREATE TABLE rol_has_user (
                user_id INT NOT NULL,
                rol_id INT NOT NULL,
                PRIMARY KEY (user_id, rol_id),
                FOREIGN KEY (user_id) REFERENCES user(id_doc) ON DELETE CASCADE,
                FOREIGN KEY (rol_id) REFERENCES rol(id_rol) ON DELETE CASCADE
            )");
            $this->tables_created[] = 'rol_has_user';
        }
        
        // Parámetros Generales
        if (!$this->tableExists('parametros_generales')) {
            $this->pdo->exec("CREATE TABLE parametros_generales (
                id INT PRIMARY KEY AUTO_INCREMENT,
                uvt DECIMAL(10, 2) NOT NULL DEFAULT 0,
                smlv DECIMAL(10, 2) NOT NULL DEFAULT 0,
                periodo_pago VARCHAR(45) DEFAULT 'mensual',
                formato_divisa VARCHAR(10) DEFAULT '$',
                formato_decimales INT DEFAULT 2,
                formato_miles VARCHAR(1) DEFAULT '.',
                ano_vigencia INT DEFAULT YEAR(CURDATE()),
                actualizado_por INT,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (actualizado_por) REFERENCES user(id_doc) ON DELETE SET NULL,
                INDEX idx_ano_vigencia (ano_vigencia)
            )");
            $this->tables_created[] = 'parametros_generales';
        }
        
        // Parámetros Legales
        if (!$this->tableExists('parametros_legales')) {
            $this->pdo->exec("CREATE TABLE parametros_legales (
                id INT PRIMARY KEY AUTO_INCREMENT,
                smlv DECIMAL(10, 2) NOT NULL,
                auxilio_transporte DECIMAL(10, 2) NOT NULL,
                año_vigencia INT NOT NULL,
                actualizado_por INT,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (actualizado_por) REFERENCES user(id_doc) ON DELETE SET NULL,
                INDEX idx_año (año_vigencia)
            )");
            $this->tables_created[] = 'parametros_legales';
        }
        
        // Parámetros Aportes
        if (!$this->tableExists('parametros_aportes')) {
            $this->pdo->exec("CREATE TABLE parametros_aportes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                salud_empleador DECIMAL(5,2) NOT NULL,
                salud_empleado DECIMAL(5,2) NOT NULL,
                pension_empleador DECIMAL(5,2) NOT NULL,
                pension_empleado DECIMAL(5,2) NOT NULL,
                parafiscales DECIMAL(5,2) NOT NULL,
                prestaciones DECIMAL(5,2) NOT NULL,
                sena DECIMAL(5,2) DEFAULT 0,
                icbf DECIMAL(5,2) DEFAULT 0,
                actualizado_por INT,
                fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (actualizado_por) REFERENCES user(id_doc) ON DELETE SET NULL
            )");
            $this->tables_created[] = 'parametros_aportes';
        }
        
        // Salarios por rol
        if (!$this->tableExists('salarios_por_rol')) {
            $this->pdo->exec("CREATE TABLE salarios_por_rol (
                id INT PRIMARY KEY AUTO_INCREMENT,
                rol VARCHAR(50) NOT NULL UNIQUE,
                salario DECIMAL(12,2) NOT NULL,
                descripcion VARCHAR(255) DEFAULT NULL,
                INDEX idx_rol (rol)
            )");
            $this->tables_created[] = 'salarios_por_rol';
        }
        
        // Niveles Riesgo ARL
        if (!$this->tableExists('niveles_riesgo_arl')) {
            $this->pdo->exec("CREATE TABLE niveles_riesgo_arl (
                codigo INT PRIMARY KEY,
                clase VARCHAR(10) NOT NULL,
                descripcion VARCHAR(100) NOT NULL,
                valor_minimo DECIMAL(5,3) NOT NULL,
                valor_inicial DECIMAL(5,3) NOT NULL,
                valor_maximo DECIMAL(5,3) NOT NULL,
                porcentaje DECIMAL(5,3) NOT NULL,
                activo BOOLEAN DEFAULT TRUE,
                INDEX idx_clase (clase)
            )");
            $this->tables_created[] = 'niveles_riesgo_arl';
        }
        
        // Empleados Riesgo ARL
        if (!$this->tableExists('empleados_riesgo_arl')) {
            $this->pdo->exec("CREATE TABLE empleados_riesgo_arl (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_empleado INT NOT NULL,
                codigo_riesgo INT NOT NULL DEFAULT 2,
                observaciones TEXT NULL,
                fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                FOREIGN KEY (codigo_riesgo) REFERENCES niveles_riesgo_arl(codigo),
                UNIQUE KEY unique_empleado_riesgo (id_empleado),
                INDEX idx_codigo_riesgo (codigo_riesgo)
            )");
            $this->tables_created[] = 'empleados_riesgo_arl';
        }
        
        // Rangos Fondo Solidaridad
        if (!$this->tableExists('rangos_fondo_solidaridad')) {
            $this->pdo->exec("CREATE TABLE rangos_fondo_solidaridad (
                id INT PRIMARY KEY AUTO_INCREMENT,
                desde_smlv DECIMAL(10, 2) NOT NULL,
                hasta_smlv DECIMAL(10, 2) NOT NULL,
                porcentaje DECIMAL(5, 2) NOT NULL,
                INDEX idx_rango (desde_smlv, hasta_smlv)
            )");
            $this->tables_created[] = 'rangos_fondo_solidaridad';
        }
        
        // Tabla Retención Fuente
        if (!$this->tableExists('tabla_retencion_fuente')) {
            $this->pdo->exec("CREATE TABLE tabla_retencion_fuente (
                id INT PRIMARY KEY AUTO_INCREMENT,
                desde_uvt DECIMAL(10, 2) NOT NULL,
                hasta_uvt DECIMAL(10, 2) NOT NULL,
                porcentaje DECIMAL(5, 2) NOT NULL,
                INDEX idx_rango (desde_uvt, hasta_uvt)
            )");
            $this->tables_created[] = 'tabla_retencion_fuente';
        }
        
        // Total Devengado
        if (!$this->tableExists('total_devengado')) {
            $this->pdo->exec("CREATE TABLE total_devengado (
                id_total_devengado INT PRIMARY KEY AUTO_INCREMENT,
                empleado_id INT,
                salario DECIMAL(15,2),
                dias INT,
                horas_extra DECIMAL(15,2) DEFAULT 0,
                bonificaciones DECIMAL(15,2) DEFAULT 0,
                otros DECIMAL(15,2) DEFAULT 0,
                total DECIMAL(15,2),
                mes INT,
                anio INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE SET NULL,
                INDEX idx_empleado_mes_anio (empleado_id, mes, anio),
                INDEX idx_fecha (anio, mes)
            )");
            $this->tables_created[] = 'total_devengado';
        }
        
        // Total Deducido
        if (!$this->tableExists('total_deducido')) {
            $this->pdo->exec("CREATE TABLE total_deducido (
                id_total_deducido INT PRIMARY KEY AUTO_INCREMENT,
                empleado_id INT,
                salario DECIMAL(15,2),
                salud DECIMAL(15,2) DEFAULT 0,
                pension DECIMAL(15,2) DEFAULT 0,
                arl DECIMAL(15,2) DEFAULT 0,
                fondo_solidaridad DECIMAL(15,2) DEFAULT 0,
                retencion_fuente DECIMAL(15,2) DEFAULT 0,
                otros_descuentos DECIMAL(15,2) DEFAULT 0,
                total DECIMAL(15,2),
                mes INT,
                anio INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE SET NULL,
                INDEX idx_empleado_mes_anio (empleado_id, mes, anio),
                INDEX idx_fecha (anio, mes)
            )");
            $this->tables_created[] = 'total_deducido';
        }
        
        // Nómina
        if (!$this->tableExists('nomina')) {
            $this->pdo->exec("CREATE TABLE nomina (
                id_nomina INT PRIMARY KEY AUTO_INCREMENT,
                anio INT,
                mes INT,
                dia INT,
                empleado_id INT,
                valor_pagar DECIMAL(15,2),
                total_devengado_id INT,
                total_deducido_id INT,
                estado VARCHAR(45) DEFAULT 'pendiente',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado) ON DELETE SET NULL,
                FOREIGN KEY (total_deducido_id) REFERENCES total_deducido(id_total_deducido) ON DELETE SET NULL,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                INDEX idx_empleado_mes_anio (empleado_id, mes, anio),
                INDEX idx_estado (estado),
                INDEX idx_fecha (anio, mes)
            )");
            $this->tables_created[] = 'nomina';
        }
        
        // Prestaciones Sociales
        if (!$this->tableExists('prestaciones_sociales')) {
            $this->pdo->exec("CREATE TABLE prestaciones_sociales (
                id_prestaciones INT PRIMARY KEY AUTO_INCREMENT,
                empleado_id INT,
                valor DECIMAL(15,2),
                concepto VARCHAR(100),
                total_devengado_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado) ON DELETE SET NULL,
                INDEX idx_empleado (empleado_id)
            )");
            $this->tables_created[] = 'prestaciones_sociales';
        }
        
        // Tarifas Horas
        if (!$this->tableExists('tarifas_horas')) {
            $this->pdo->exec("CREATE TABLE tarifas_horas (
                id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
                fecha_inicio DATE NOT NULL,
                fecha_fin DATE NOT NULL,
                valor_hora DECIMAL(10,2) NOT NULL,
                descripcion VARCHAR(255) NULL,
                nombre_periodo VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_fecha (fecha_inicio, fecha_fin)
            )");
            $this->tables_created[] = 'tarifas_horas';
        }
        
        // Tipos Horas Extras
        if (!$this->tableExists('tipos_horas_extras')) {
            $this->pdo->exec("CREATE TABLE tipos_horas_extras (
                id_tipo INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                porcentaje DECIMAL(5,2) NOT NULL,
                descripcion TEXT NULL,
                activo BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_nombre (nombre)
            )");
            $this->tables_created[] = 'tipos_horas_extras';
        }
        
        // Horas Extras
        if (!$this->tableExists('horas_extras')) {
            $this->pdo->exec("CREATE TABLE horas_extras (
                id INT AUTO_INCREMENT PRIMARY KEY,
                empleado_id INT NOT NULL,
                valor DECIMAL(15,2) NOT NULL,
                cantidad DECIMAL(8,2) NOT NULL,
                tipo VARCHAR(100),
                porcentaje DECIMAL(5,2),
                dia VARCHAR(2),
                mes VARCHAR(2),
                anio VARCHAR(4),
                estado VARCHAR(45) DEFAULT 'pendiente',
                fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                fecha_aprobacion DATETIME NULL,
                aprobado_por INT NULL,
                comentario_aprobacion TEXT NULL,
                total_devengado_id INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                FOREIGN KEY (aprobado_por) REFERENCES user(id_doc) ON DELETE SET NULL,
                FOREIGN KEY (total_devengado_id) REFERENCES total_devengado(id_total_devengado) ON DELETE SET NULL,
                INDEX idx_empleado_mes_anio (empleado_id, mes, anio),
                INDEX idx_estado (estado),
                INDEX idx_fecha_creacion (fecha_creacion),
                INDEX idx_aprobado_por (aprobado_por)
            )");
            $this->tables_created[] = 'horas_extras';
        }
        
        // Conceptos Adicionales Prestaciones
        if (!$this->tableExists('conceptos_adicionales_prestaciones')) {
            $this->pdo->exec("CREATE TABLE conceptos_adicionales_prestaciones (
                id INT AUTO_INCREMENT PRIMARY KEY,
                empleado_id INT NOT NULL,
                concepto VARCHAR(255) NOT NULL,
                descripcion TEXT,
                valor DECIMAL(15,2) NOT NULL,
                total_plazos INT DEFAULT 1,
                tipo_plazo ENUM('quincena', 'mes') DEFAULT 'quincena',
                tiene_plazo BOOLEAN DEFAULT FALSE,
                activo BOOLEAN DEFAULT TRUE,
                creado_por VARCHAR(100),
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                INDEX idx_empleado_id (empleado_id),
                INDEX idx_activo (activo),
                INDEX idx_fecha_creacion (fecha_creacion)
            )");
            $this->tables_created[] = 'conceptos_adicionales_prestaciones';
        }
        
        // Conceptos Adicionales Plazos
        if (!$this->tableExists('conceptos_adicionales_plazos')) {
            $this->pdo->exec("CREATE TABLE conceptos_adicionales_plazos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                concepto_id INT NOT NULL,
                periodo_numero INT NOT NULL,
                valor_periodo DECIMAL(15,2) NOT NULL,
                tipo_periodo ENUM('quincena', 'mes') NOT NULL DEFAULT 'quincena',
                fecha_vencimiento DATE,
                estado ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente',
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (concepto_id) REFERENCES conceptos_adicionales_prestaciones(id) ON DELETE CASCADE,
                INDEX idx_concepto_id (concepto_id),
                INDEX idx_estado (estado),
                INDEX idx_fecha_vencimiento (fecha_vencimiento)
            )");
            $this->tables_created[] = 'conceptos_adicionales_plazos';
        }
        
        // Conceptos Adicionales Deducibles
        if (!$this->tableExists('conceptos_adicionales_deducibles')) {
            $this->pdo->exec("CREATE TABLE conceptos_adicionales_deducibles (
                id INT PRIMARY KEY AUTO_INCREMENT,
                empleado_id INT NOT NULL,
                concepto VARCHAR(255) NOT NULL,
                descripcion TEXT,
                valor DECIMAL(15,2) NOT NULL DEFAULT 0,
                activo TINYINT(1) DEFAULT 1,
                creado_por VARCHAR(100),
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (empleado_id) REFERENCES empleados(id_empleados) ON DELETE CASCADE,
                INDEX idx_empleado_activo (empleado_id, activo),
                INDEX idx_fecha_creacion (fecha_creacion)
            )");
            $this->tables_created[] = 'conceptos_adicionales_deducibles';
        }
        
        // Notificaciones
        if (!$this->tableExists('notificaciones')) {
            $this->pdo->exec("CREATE TABLE notificaciones (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NOT NULL,
                tipo VARCHAR(50) NOT NULL,
                mensaje TEXT NOT NULL,
                url VARCHAR(255),
                leida TINYINT(1) DEFAULT 0,
                fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES user(id_doc) ON DELETE CASCADE,
                INDEX idx_notificaciones_usuario_leida (usuario_id, leida),
                INDEX idx_fecha (fecha_creacion)
            )");
            $this->tables_created[] = 'notificaciones';
        }
    }
    
    /**
     * Inserta datos iniciales si no existen
     */
    private function insertInitialData() {
        // Verificar si ya hay roles
        $result = $this->pdo->query("SELECT COUNT(*) as count FROM rol");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        if ($row['count'] == 0) {
            // Insertar roles
            $this->pdo->exec("INSERT INTO rol (id_rol, nombre) VALUES 
                (1, 'admin'), 
                (2, 'rrhh'), 
                (3, 'empleado')");
            
            // Insertar empleados del sistema
            $this->pdo->exec("INSERT INTO empleados (id_empleados, nombre, apellido, sueldo_actual) VALUES 
                (1, 'Sistema', 'Administrador', 1300000),
                (2, 'Sistema', 'RRHH', 1300000),
                (3, 'Sistema', 'Empleado', 1300000)");
            
            // Insertar usuarios
            $this->pdo->exec("INSERT INTO user (id_doc, username, password, empleado_id) VALUES
                (1, 'admin', '\$2y\$10\$1uIHpk3HVxppsInwqpofbexCSv18ou7J3VVh6Aq03Wpxpt9CXiO6a', 1),
                (2, 'rrhh', '\$2y\$10\$u4InYXHoARGT6FU/tjcaM.LOGrOnTbv0bTmc16TVMSrKOTHm1SHFy', 2), 
                (3, 'empleado', '\$2y\$10\$k2Z/bmGFazE8lPYK8LpNxe8bbh4zCVsu5qi2L5QT8dJYAzoIOhfqS', 3)");
            
            // Asignar roles
            $this->pdo->exec("INSERT INTO rol_has_user (user_id, rol_id) VALUES
                (1, 1),
                (2, 2),
                (3, 3)");
            
            // Insertar salarios por rol
            $this->pdo->exec("INSERT INTO salarios_por_rol (rol, salario, descripcion) VALUES
                ('empleado', 1300000, 'Salario base empleado'),
                ('rrhh', 1800000, 'Salario base RRHH'),
                ('admin', 2500000, 'Salario base Administrador')");
            
            // Insertar parámetros generales
            $this->pdo->exec("INSERT INTO parametros_generales (uvt, smlv, periodo_pago, formato_divisa, formato_decimales, formato_miles, ano_vigencia) 
                VALUES (45286.00, 1300000, 'mensual', '$', 0, '.', YEAR(CURDATE()))");
            
            // Insertar parámetros de aportes
            $this->pdo->exec("INSERT INTO parametros_aportes (salud_empleador, salud_empleado, pension_empleador, pension_empleado, parafiscales, prestaciones, sena, icbf)
                VALUES (8.50, 4.00, 12.00, 4.00, 9.00, 8.33, 2.00, 3.00)");
            
            // Insertar niveles de riesgo ARL
            $this->pdo->exec("INSERT INTO niveles_riesgo_arl (codigo, clase, descripcion, valor_minimo, valor_inicial, valor_maximo, porcentaje) VALUES
                (1, 'I', 'Mínimo', 0.348, 0.522, 0.696, 0.522),
                (2, 'II', 'Bajo', 0.435, 1.044, 1.653, 1.044),
                (3, 'III', 'Medio', 0.783, 2.436, 4.089, 2.436),
                (4, 'IV', 'Alto', 1.740, 4.350, 6.960, 4.350),
                (5, 'V', 'Máximo', 3.219, 6.960, 8.700, 6.960)");
            
            // Asignar riesgo ARL a empleados
            $this->pdo->exec("INSERT INTO empleados_riesgo_arl (id_empleado, codigo_riesgo) VALUES (1, 2), (2, 2), (3, 2)");
            
            // Insertar rangos fondo solidaridad
            $this->pdo->exec("INSERT INTO rangos_fondo_solidaridad (desde_smlv, hasta_smlv, porcentaje) VALUES
                (1, 1.5, 1.0),
                (1.5, 2, 1.2),
                (2, 2.5, 1.4),
                (2.5, 3, 1.6),
                (3, 4, 2.0),
                (4, 5, 2.5),
                (5, 10, 2.75),
                (10, 20, 3.0)");
            
            // Insertar retención en la fuente
            $this->pdo->exec("INSERT INTO tabla_retencion_fuente (desde_uvt, hasta_uvt, porcentaje) VALUES
                (0, 95, 0),
                (95, 150, 5),
                (150, 360, 8),
                (360, 645, 11),
                (645, 999999, 15)");
            
            // Insertar tarifas de horas
            $this->pdo->exec("INSERT INTO tarifas_horas (fecha_inicio, fecha_fin, valor_hora, descripcion, nombre_periodo) VALUES
                ('2025-01-01', '2025-07-14', 6189.00, 'Tarifa base primer período 2025', 'Primer período 2025'),
                ('2025-07-15', '2025-12-31', 6470.00, 'Tarifa base segundo período 2025', 'Segundo período 2025')");
            
            // Insertar tipos de horas extras
            $this->pdo->exec("INSERT INTO tipos_horas_extras (nombre, porcentaje, descripcion) VALUES
                ('Extra diurna', 25.00, 'Horas extras en horario diurno (6:00 AM - 10:00 PM)'),
                ('Extra nocturna', 75.00, 'Horas extras en horario nocturno (10:00 PM - 6:00 AM)'),
                ('Extra diurna dominical/festiva', 105.00, 'Horas extras diurnas en domingos o festivos'),
                ('Extra nocturna dominical/festiva', 155.00, 'Horas extras nocturnas en domingos o festivos')");
        }
    }
}
