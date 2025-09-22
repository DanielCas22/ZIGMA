<?php
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/SalarioPorRol.php';

// Conectar a la base de datos
$db = require_once 'config/database.php';

echo "=== SINCRONIZACIÓN DE SALARIOS CON ROLES ===\n\n";

$empleadoModel = new Empleado();

echo "1. ESTADO ACTUAL DE EMPLEADOS Y ROLES:\n";
$empleados = $empleadoModel->getAllWithRoles();

foreach ($empleados as $emp) {
    $rol_principal = $emp['rol_principal'] ?? 'Sin rol';
    $salario_actual = isset($emp['salario']) ? floatval($emp['salario']) : 0;
    
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']})\n";
    echo "  Rol principal: {$rol_principal}\n";
    echo "  Salario actual: $" . number_format($salario_actual, 0) . "\n";
    
    if (isset($emp['todos_los_roles']) && $emp['todos_los_roles']) {
        echo "  Todos los roles: {$emp['todos_los_roles']}\n";
    }
    echo "\n";
}

echo "2. SALARIOS ESTÁNDAR POR ROL:\n";
$salarioModel = new SalarioPorRol();
$salarios_rol = $salarioModel->getAll();

foreach ($salarios_rol as $rol) {
    echo "- {$rol['rol_nombre']}: $" . number_format($rol['salario_base'], 0) . " - {$rol['descripcion']}\n";
}

echo "\n3. SINCRONIZANDO SALARIOS...\n";
$empleados_actualizados = $empleadoModel->sincronizarTodosSalariosConRoles();

echo "✅ Empleados actualizados: {$empleados_actualizados}\n\n";

echo "4. ESTADO DESPUÉS DE LA SINCRONIZACIÓN:\n";
$empleados_finales = $empleadoModel->getAllWithRoles();

$total_nomina = 0;
foreach ($empleados_finales as $emp) {
    $rol_principal = $emp['rol_principal'] ?? 'Sin rol';
    $salario_final = isset($emp['salario']) ? floatval($emp['salario']) : 0;
    $total_nomina += $salario_final;
    
    echo "- {$emp['nombre']} {$emp['apellidos']} ({$rol_principal}): $" . number_format($salario_final, 0) . "\n";
}

echo "\n=== RESUMEN FINAL ===\n";
echo "Total empleados: " . count($empleados_finales) . "\n";
echo "Nómina total: $" . number_format($total_nomina, 0) . "\n";
echo "Promedio salarial: $" . number_format($total_nomina / count($empleados_finales), 0) . "\n";

// Verificar coherencia por roles
echo "\nVERIFICACIÓN POR ROLES:\n";
$roles_conteo = [];
$roles_salarios = [];

foreach ($empleados_finales as $emp) {
    $rol = $emp['rol_principal'] ?? 'Sin rol';
    $salario = isset($emp['salario']) ? floatval($emp['salario']) : 0;
    
    if (!isset($roles_conteo[$rol])) {
        $roles_conteo[$rol] = 0;
        $roles_salarios[$rol] = [];
    }
    
    $roles_conteo[$rol]++;
    $roles_salarios[$rol][] = $salario;
}

foreach ($roles_conteo as $rol => $cantidad) {
    $salarios_del_rol = $roles_salarios[$rol];
    $salario_promedio = array_sum($salarios_del_rol) / count($salarios_del_rol);
    $todos_iguales = count(array_unique($salarios_del_rol)) === 1;
    
    echo "- {$rol}: {$cantidad} empleados, salario promedio: $" . number_format($salario_promedio, 0);
    echo $todos_iguales ? " ✅ (Todos tienen el mismo salario)" : " ⚠️ (Salarios diferentes)";
    echo "\n";
}
?>