<?php
/**
 * Script para crear tabla de riesgos ARL y datos iniciales
 * Ejecutar este archivo para configurar la estructura de datos de ARL
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/ARLModel.php';

try {
    echo "=== CONFIGURACIÓN DE TABLA ARL ===\n\n";
    
    // Crear instancia del modelo
    $arlModel = new ARLModel();
    
    // Crear tabla
    echo "1. Creando tabla empleados_riesgo_arl...\n";
    if ($arlModel->crearTablaRiesgo()) {
        echo "   ✓ Tabla creada correctamente\n\n";
    } else {
        echo "   ✗ Error al crear la tabla\n\n";
        exit(1);
    }
    
    // Mostrar información de niveles de riesgo
    echo "2. Niveles de riesgo disponibles:\n";
    $niveles = $arlModel->getTodosNivelesRiesgo();
    foreach ($niveles as $codigo => $info) {
        echo "   Código {$codigo}: Riesgo {$info['codigo']} ({$info['porcentaje']}%) - {$info['descripcion']}\n";
    }
    echo "\n";
    
    // Asignar riesgos por defecto a empleados existentes
    echo "3. Asignando riesgos por defecto a empleados existentes...\n";
    
    require_once __DIR__ . '/app/models/Empleado.php';
    $empleadoModel = new Empleado();
    $empleados = $empleadoModel->getAll();
    
    $asignados = 0;
    foreach ($empleados as $empleado) {
        $idEmpleado = $empleado['id_empleados'];
        
        // Verificar si ya tiene riesgo asignado
        $riesgoExistente = $arlModel->getRiesgoEmpleado($idEmpleado);
        
        if (!$riesgoExistente) {
            // Asignar riesgo por defecto (Riesgo II para empleados generales)
            $codigoRiesgoPorDefecto = 2; // Riesgo Bajo
            
            if ($arlModel->asignarRiesgoEmpleado($idEmpleado, $codigoRiesgoPorDefecto)) {
                echo "   ✓ Empleado {$empleado['nombre']} {$empleado['apellido']} - Riesgo II asignado\n";
                $asignados++;
            }
        } else {
            echo "   - Empleado {$empleado['nombre']} {$empleado['apellido']} - Ya tiene riesgo asignado (Nivel {$riesgoExistente['codigo_riesgo']})\n";
        }
    }
    
    echo "\n   Total empleados con riesgo asignado: {$asignados}\n\n";
    
    // Mostrar estadísticas
    echo "4. Estadísticas de riesgos:\n";
    $estadisticas = $arlModel->getEstadisticasRiesgo();
    
    foreach ($estadisticas['por_nivel'] as $codigo => $stats) {
        $info = $stats['nivel_info'];
        echo "   Riesgo {$info['codigo']} ({$info['porcentaje']}%): {$stats['cantidad_empleados']} empleados ({$stats['porcentaje_total']}%)\n";
    }
    
    echo "\n   Total empleados: {$estadisticas['total_empleados']}\n";
    echo "   Riesgo promedio: " . round($estadisticas['riesgo_promedio'], 2) . "\n\n";
    
    echo "=== CONFIGURACIÓN COMPLETADA ===\n";
    echo "La tabla ARL ha sido configurada correctamente.\n";
    echo "Puedes ahora utilizar el sistema de cálculo de ARL integrado con seguridad social.\n\n";
    
    // Ejemplo de cálculo
    if (!empty($empleados)) {
        $empleadoEjemplo = $empleados[0];
        echo "Ejemplo de cálculo para {$empleadoEjemplo['nombre']} {$empleadoEjemplo['apellido']}:\n";
        
        try {
            $calculoEjemplo = $arlModel->calcularARLEmpleado($empleadoEjemplo['id_empleados']);
            echo "- Salario proporcional: $" . number_format($calculoEjemplo['salario_proporcional'], 2) . "\n";
            echo "- Nivel de riesgo: {$calculoEjemplo['nivel_riesgo']['codigo']} ({$calculoEjemplo['porcentaje_arl']}%)\n";
            echo "- Aporte ARL: $" . number_format($calculoEjemplo['aporte_arl'], 2) . "\n";
        } catch (Exception $e) {
            echo "Error en ejemplo: " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error durante la configuración: " . $e->getMessage() . "\n";
    exit(1);
}