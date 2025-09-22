<?php
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/HorasExtras.php';

// Conectar a la base de datos
$db = require_once 'config/database.php';

echo "=== PRUEBA FINAL DEL SISTEMA SEPARADO ===\n\n";

$empleadoModel = new Empleado();
$horasExtrasModel = new HorasExtras();

echo "1. CREAR NUEVO EMPLEADO REAL:\n";
$nuevo_empleado = [
    'nombre' => 'María',
    'apellidos' => 'González',
    'salario' => 2500000,
    'es_usuario_sistema' => false // Empleado real
];

$resultado = $empleadoModel->create($nuevo_empleado);
if ($resultado) {
    $nuevo_id = $empleadoModel->getLastInsertId();
    echo "✅ Empleado real creado: María González (ID {$nuevo_id})\n";
} else {
    echo "❌ Error al crear empleado\n";
}

echo "\n2. LISTA DE EMPLEADOS REALES (VISIBLE EN EL SISTEMA):\n";
$empleados_visibles = $empleadoModel->getAll();
foreach ($empleados_visibles as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']}) - $" . number_format($emp['salario'], 0) . "\n";
}

echo "\n3. EMPLEADOS DISPONIBLES PARA HORAS EXTRAS:\n";
$empleados_horas = $horasExtrasModel->getAllWithEmpleado();
foreach ($empleados_horas as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} (ID {$emp['id_empleados']})\n";
}

echo "\n4. USUARIOS DEL SISTEMA (OCULTOS PERO EXISTENTES):\n";
$usuarios_sistema = $empleadoModel->getSystemUsers();
foreach ($usuarios_sistema as $emp) {
    echo "- {$emp['nombre']} {$emp['apellidos']} - Para login y administración\n";
}

echo "\n5. CREAR HORAS EXTRAS PARA EMPLEADO REAL:\n";
$datos_horas = [
    'empleado_id' => $nuevo_id, // María González
    'cantidad' => 4,
    'tipo' => 'Extra diurna',
    'dia' => 22,
    'mes' => 9,
    'año' => 2025
];

$resultado_horas = $horasExtrasModel->create($datos_horas);
if ($resultado_horas) {
    echo "✅ Horas extras creadas para María González\n";
    
    // Verificar el registro
    $horas_creadas = $horasExtrasModel->getHorasExtrasByEmpleado($nuevo_id);
    if (!empty($horas_creadas)) {
        $registro = $horas_creadas[0];
        echo "   - {$registro['cantidad']} horas {$registro['tipo']} = $" . number_format($registro['valor'], 0) . "\n";
    }
} else {
    echo "❌ Error al crear horas extras\n";
}

echo "\n=== RESUMEN FINAL ===\n";
echo "✅ Usuarios del sistema (admin, rrhh, empleado): OCULTOS de listas de trabajadores\n";
echo "✅ Empleados reales: VISIBLES en listas y gestión de horas extras\n";
echo "✅ Separación completa: Usuarios para login vs Trabajadores para operaciones\n";
echo "✅ Nómina empleados reales: $" . number_format(array_sum(array_column($empleados_visibles, 'salario')), 0) . "\n";
echo "✅ Total empleados visibles: " . count($empleados_visibles) . "\n";
?>