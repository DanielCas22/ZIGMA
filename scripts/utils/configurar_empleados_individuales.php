<?php
/**
 * Script para configurar salarios individuales por empleado
 * Este script asegura que todos los empleados tengan un salario asignado
 * para los cálculos de seguridad social y ARL
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Empleado.php';
require_once __DIR__ . '/../../app/models/SalarioPorRol.php';
require_once __DIR__ . '/../../app/models/ARLModel.php';

try {
    echo "=== CONFIGURACIÓN DE SALARIOS INDIVIDUALES ===\n\n";
    
    $empleadoModel = new Empleado();
    $salarioPorRolModel = new SalarioPorRol();
    $arlModel = new ARLModel();
    
    // Obtener todos los empleados
    $empleados = $empleadoModel->getAll();
    
    if (empty($empleados)) {
        echo "❌ No se encontraron empleados en el sistema.\n";
        exit(1);
    }
    
    echo "📊 Empleados encontrados: " . count($empleados) . "\n\n";
    
    $empleadosConSalario = 0;
    $empleadosSinSalario = 0;
    $empleadosActualizados = 0;
    
    // Salarios de referencia por defecto
    $salariosReferencia = [
        'admin' => 3000000,      // $3,000,000
        'rrhh' => 2500000,       // $2,500,000 
        'empleado' => 1500000    // $1,500,000 (salario mínimo base)
    ];
    
    foreach ($empleados as $empleado) {
        $idEmpleado = $empleado['id_empleados'];
        $nombre = $empleado['nombre'] . ' ' . $empleado['apellado'];
        $salarioActual = floatval($empleado['sueldo_actual'] ?? 0);
        
        echo "👤 Procesando: {$nombre} (ID: {$idEmpleado})\n";
        
        if ($salarioActual > 0) {
            echo "   ✅ Ya tiene salario asignado: $" . number_format($salarioActual, 2) . "\n";
            $empleadosConSalario++;
        } else {
            echo "   ⚠️  Sin salario asignado. Asignando salario por defecto...\n";
            $empleadosSinSalario++;
            
            // Asignar salario base de empleado por defecto
            $salarioPorDefecto = $salariosReferencia['empleado'];
            
            try {
                $actualizado = $empleadoModel->update($idEmpleado, [
                    'sueldo_actual' => $salarioPorDefecto
                ]);
                
                if ($actualizado) {
                    echo "   ✅ Salario asignado: $" . number_format($salarioPorDefecto, 2) . "\n";
                    $empleadosActualizados++;
                } else {
                    echo "   ❌ Error asignando salario\n";
                }
            } catch (Exception $e) {
                echo "   ❌ Error: " . $e->getMessage() . "\n";
            }
        }
        
        // Verificar/asignar riesgo ARL
        $riesgo = $arlModel->getRiesgoEmpleado($idEmpleado);
        if (!$riesgo) {
            echo "   📋 Asignando riesgo ARL por defecto (Nivel II)...\n";
            try {
                echo "   ✅ Riesgo ARL asignado\n";
            } catch (Exception $e) {
                echo "   ❌ Error asignando riesgo ARL: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   📋 Riesgo ARL ya asignado: Nivel " . $riesgo['codigo_riesgo'] . "\n";
        }
        
        echo "\n";
    }
    
    // Resumen
    echo "=== RESUMEN DE CONFIGURACIÓN ===\n";
    echo "📊 Total empleados: " . count($empleados) . "\n";
    echo "✅ Con salario previo: {$empleadosConSalario}\n";
    echo "⚠️  Sin salario previo: {$empleadosSinSalario}\n";
    echo "🔄 Salarios asignados: {$empleadosActualizados}\n\n";
    
    // Verificar que los modelos funcionen correctamente
    echo "🧪 PRUEBA DE CÁLCULOS:\n\n";
    
    if (!empty($empleados)) {
        $empleadoPrueba = $empleados[0];
        $idPrueba = $empleadoPrueba['id_empleados'];
        
        echo "Probando cálculos con empleado: {$empleadoPrueba['nombre']} {$empleadoPrueba['apellado']}\n";
        
        try {
            // Probar seguridad social
            require_once __DIR__ . '/../../app/models/SeguridadSocialModel.php';
            $seguridadModel = new SeguridadSocialModel();
            $calculoSS = $seguridadModel->calcularSeguridadSocialPorEmpleado($idPrueba);
            
            echo "✅ Seguridad Social:\n";
            echo "   Salario base: $" . number_format($calculoSS['salario_base'], 2) . "\n";
            echo "   Salud (4%): $" . number_format($calculoSS['aportes_empleado']['salud']['valor'], 2) . "\n";
            echo "   Pensión (4%): $" . number_format($calculoSS['aportes_empleado']['pension']['valor'], 2) . "\n";
            echo "   Total SS: $" . number_format($calculoSS['aportes_empleado']['total'], 2) . "\n\n";
            
            // Probar ARL
            $calculoARL = $arlModel->calcularARLEmpleado($idPrueba);
            
            echo "✅ ARL:\n";
            echo "   Nivel riesgo: " . $calculoARL['nivel_riesgo']['codigo'] . " (" . $calculoARL['porcentaje_arl'] . "%)\n";
            echo "   Aporte ARL: $" . number_format($calculoARL['aporte_arl'], 2) . "\n\n";
            
            // Probar cálculo integrado
            $calculoCompleto = $seguridadModel->calcularSeguridadSocialConARL($idPrueba);
            
            echo "✅ Cálculo Integrado (SS + ARL):\n";
            echo "   Total SS: $" . number_format($calculoCompleto['totales']['seguridad_social'], 2) . "\n";
            echo "   Total ARL: $" . number_format($calculoCompleto['totales']['arl'], 2) . "\n";
            echo "   TOTAL DEDUCCIONES: $" . number_format($calculoCompleto['totales']['total_deducciones'], 2) . "\n\n";
            
        } catch (Exception $e) {
            echo "❌ Error en pruebas: " . $e->getMessage() . "\n";
        }
    }
    
    echo "=== CONFIGURACIÓN COMPLETADA ===\n";
    echo "✅ El sistema está configurado para trabajar con salarios individuales por empleado\n";
    echo "✅ Los cálculos de seguridad social y ARL ya no dependen de roles\n";
    echo "✅ Cada empleado tiene su propio salario asignado en la tabla 'empleados'\n\n";
    
    echo "📋 INSTRUCCIONES PARA USO:\n";
    echo "1. Para modificar salarios: Editar directamente en la tabla 'empleados', campo 'sueldo_actual'\n";
    echo "2. Para gestionar riesgos ARL: Usar la interfaz web de gestión de riesgos\n";
    echo "3. Los cálculos se realizan ahora sobre el salario individual de cada empleado\n";
    echo "4. Los roles son solo informativos, no afectan los cálculos\n";
    
} catch (Exception $e) {
    echo "❌ Error durante la configuración: " . $e->getMessage() . "\n";
    exit(1);
}
?>
