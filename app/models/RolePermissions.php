<?php

class RolePermissions {
    
    /**
     * Definir permisos por rol
     */
    private static $permissions = [
        'admin' => [
            'empleados' => ['create', 'read', 'update', 'delete'],
            'horas_extras' => ['create', 'read', 'update', 'delete', 'all_employees', 'approve', 'reject'],
            'nomina' => ['read', 'generate'],
            'devengado' => ['read'],
            'deducido' => ['read'],
            'parafiscales' => ['read'],
            'prestaciones' => ['read'],
            'seguridad_social' => ['read'],
            'desprendible' => ['read']
        ],
        'rrhh' => [
            'empleados' => ['create', 'read', 'update'],
            'horas_extras' => ['create', 'read', 'update', 'all_employees', 'approve', 'reject'],
            'nomina' => ['read', 'generate'],
            'devengado' => ['read'],
            'deducido' => ['read'],
            'parafiscales' => ['read'],
            'prestaciones' => ['read'],
            'seguridad_social' => ['read'],
            'desprendible' => ['read']
        ],
        'empleado' => [
            'horas_extras' => ['create', 'read_own'],
            'desprendible' => ['read_own']
        ]
    ];
    
    /**
     * Verificar si un rol tiene permiso para una acción específica
     */
    public static function hasPermission($role, $module, $action) {
        if (!isset(self::$permissions[$role])) {
            return false;
        }
        
        if (!isset(self::$permissions[$role][$module])) {
            return false;
        }
        
        return in_array($action, self::$permissions[$role][$module]);
    }
    
    /**
     * Obtener el rol principal del usuario actual
     */
    public static function getCurrentUserRole() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['rol'])) {
            return null;
        }
        return $_SESSION['user']['rol'];
    }
    
    /**
     * Verificar si el usuario actual puede acceder a un módulo con una acción específica
     */
    public static function canAccess($module, $action) {
        $role = self::getCurrentUserRole();
        if (!$role) {
            return false;
        }
        return self::hasPermission($role, $module, $action);
    }
    
    /**
     * Verificar si el usuario actual puede acceder a registros de otros empleados
     */
    public static function canAccessAllEmployees($module) {
        $role = self::getCurrentUserRole();
        if (!$role) {
            return false;
        }
        return self::hasPermission($role, $module, 'all_employees');
    }
    
    /**
     * Obtener el ID del empleado del usuario actual
     */
    public static function getCurrentEmployeeId() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['empleado_id'])) {
            return null;
        }
        return $_SESSION['user']['empleado_id'];
    }
    
    /**
     * Verificar si el usuario puede acceder a un registro específico de empleado
     */
    public static function canAccessEmployee($employeeId) {
        $role = self::getCurrentUserRole();
        
        // Admin y RRHH pueden acceder a todos los empleados
        if (in_array($role, ['admin', 'rrhh'])) {
            return true;
        }
        
        // Empleados solo pueden acceder a sus propios registros
        if ($role === 'empleado') {
            return $employeeId == self::getCurrentEmployeeId();
        }
        
        return false;
    }
    
    /**
     * Redireccionar si no tiene permisos
     */
    public static function redirectIfNoPermission($module, $action, $redirectUrl = null) {
        if (!self::canAccess($module, $action)) {
            $baseUrl = self::getBaseUrl();
            $url = $redirectUrl ?: $baseUrl . '/public/index.php?url=dashboard&error=no_permission';
            header("Location: $url");
            exit;
        }
    }
    
    /**
     * Obtener URL base
     */
    private static function getBaseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . '/ZIGMA';
    }
    
    /**
     * Obtener cantidad de horas extras pendientes de aprobación
     */
    public static function getPendingHoursCount() {
        $role = self::getCurrentUserRole();
        
        // Solo admin y RRHH pueden ver horas extras pendientes
        if (!in_array($role, ['admin', 'rrhh'])) {
            return 0;
        }
        
        try {
            $db = require __DIR__ . '/../../config/database.php';
            
            $sql = "SELECT COUNT(*) FROM horas_extras WHERE estado = 'pendiente'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Obtener horas extras pendientes de aprobación
     */
    public static function getPendingHours($limit = 5) {
        $role = self::getCurrentUserRole();
        
        // Solo admin y RRHH pueden ver horas extras pendientes
        if (!in_array($role, ['admin', 'rrhh'])) {
            return [];
        }
        
        try {
            $db = require __DIR__ . '/../../config/database.php';
            
            $sql = "SELECT he.*, e.nombre, e.apellido 
                    FROM horas_extras he 
                    INNER JOIN empleados e ON he.empleado_id = e.id_empleados 
                    WHERE he.estado = 'pendiente' 
                    ORDER BY he.fecha_creacion DESC 
                    LIMIT ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
