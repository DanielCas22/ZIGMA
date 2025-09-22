<?php
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/HorasExtras.php';

// Conectar a la base de datos
$db = require_once 'config/database.php';

echo "=== VERIFICACIÓN DE FILTRADO DE USUARIOS DEL SISTEMA ===\n\n";

$empleadoModel = new Empleado();
$horasExtrasModel = new HorasExtras();

echo "1. TODOS LOS REGISTROS EN LA TABLA (INCLUYENDO SISTEMA):\n";
$todos_empleados = $empleadoModel->getAllIncludingSystem();
foreach ($todos_empleados as $emp) {
    $tipo = $emp['es_usuario_sistema'] ? '🔧 USUARIO SISTEMA' : '👤 EMPLEADO REAL';
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']}) - {$tipo}\n";
}

echo "\n2. SOLO EMPLEADOS REALES (COMO APARECERÁN EN LAS LISTAS):\n";
$empleados_reales = $empleadoModel->getAll();
foreach ($empleados_reales as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']}) - Salario: $" . number_format($emp['salario'], 0) . "\n";
}

echo "\n3. SOLO USUARIOS DEL SISTEMA:\n";
$usuarios_sistema = $empleadoModel->getSystemUsers();
foreach ($usuarios_sistema as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']}) - Rol de sistema\n";
}

echo "\n4. EMPLEADOS CON ROLES (FILTRADOS):\n";
$empleados_con_roles = $empleadoModel->getAllWithRoles();
foreach ($empleados_con_roles as $emp) {
    $rol = $emp['rol_principal'] ?? 'Sin rol';
    echo "- {$emp['nombre']} {$emp['apellidos']} - Rol: {$rol}\n";
}

echo "\n5. EMPLEADOS PARA HORAS EXTRAS:\n";
$empleados_horas = $horasExtrasModel->getAllWithEmpleado();
foreach ($empleados_horas as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']})\n";
}

echo "\n=== RESUMEN ===\n";
echo "Total registros en tabla: " . count($todos_empleados) . "\n";
echo "Usuarios del sistema: " . count($usuarios_sistema) . "\n";
echo "Empleados reales mostrados: " . count($empleados_reales) . "\n";
echo "Empleados disponibles para horas extras: " . count($empleados_horas) . "\n";

// Calcular nómina solo de empleados reales
$nomina_real = array_sum(array_column($empleados_reales, 'salario'));
$nomina_sistema = array_sum(array_column($usuarios_sistema, 'salario'));

echo "\nNómina empleados reales: $" . number_format($nomina_real, 0) . "\n";
echo "Nómina usuarios sistema: $" . number_format($nomina_sistema, 0) . " (oculta en reportes)\n";
echo "Nómina total: $" . number_format($nomina_real + $nomina_sistema, 0) . "\n";
?>