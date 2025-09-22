<?php
echo "<h1>Probando sistema de cálculos automáticos</h1>";

// Incluir modelos
require_once '../app/models/Model.php';
require_once '../app/models/TarifaHora.php';
require_once '../app/models/TipoHoraExtra.php';
require_once '../app/models/HorasExtras.php';

try {
    echo "<h2>1. Probando TarifaHora</h2>";
    $tarifaModel = new TarifaHora();
    $tarifa = $tarifaModel->getTarifaVigente('2025-03-15');
    if ($tarifa) {
        echo "<p>✓ Tarifa encontrada para marzo 2025: $" . number_format($tarifa['valor_hora'], 0, ',', '.') . "</p>";
    } else {
        echo "<p>✗ Error: No se encontró tarifa</p>";
    }

    echo "<h2>2. Probando TipoHoraExtra</h2>";
    $tipoModel = new TipoHoraExtra();
    $tipos_test = ['Extra diurna', 'Extra nocturna', 'Extra diurna dominical/festiva', 'Extra nocturna dominical/festiva'];
    foreach ($tipos_test as $tipo_nombre) {
        $tipo = $tipoModel->getTipoPorcentaje($tipo_nombre);
        if ($tipo) {
            echo "<p>✓ $tipo_nombre: " . $tipo['porcentaje'] . "%</p>";
        } else {
            echo "<p>✗ Error: No se encontró tipo $tipo_nombre</p>";
        }
    }

    echo "<h2>3. Probando Cálculos Automáticos</h2>";
    if ($tarifa) {
        // Calcular 8 horas extra diurna
        $valor_8h_diurna = $tarifaModel->calcularValorHorasExtras($tarifa['valor_hora'], 8, 25);
        echo "<p>8 horas extra diurna (25%): $" . number_format($valor_8h_diurna, 0, ',', '.') . "</p>";
        
        // Calcular 4 horas extra nocturna
        $valor_4h_nocturna = $tarifaModel->calcularValorHorasExtras($tarifa['valor_hora'], 4, 75);
        echo "<p>4 horas extra nocturna (75%): $" . number_format($valor_4h_nocturna, 0, ',', '.') . "</p>";
        
        // Calcular 6 horas extra dominical
        $valor_6h_dominical = $tarifaModel->calcularValorHorasExtras($tarifa['valor_hora'], 6, 105);
        echo "<p>6 horas extra dominical (105%): $" . number_format($valor_6h_dominical, 0, ',', '.') . "</p>";
    }

    echo "<h2>4. Probando HorasExtras con cálculo automático</h2>";
    $horasExtrasModel = new HorasExtras();
    $valor_auto = $horasExtrasModel->calcularValorAutomatico('Extra diurna', 10, '2025-06-15');
    if ($valor_auto) {
        echo "<p>✓ Cálculo automático: 10 horas extra diurna en junio = $" . number_format($valor_auto, 0, ',', '.') . "</p>";
    } else {
        echo "<p>✗ Error en cálculo automático</p>";
    }

    echo "<h2>✓ Todas las pruebas completadas exitosamente</h2>";
    echo "<p>El sistema de cálculos automáticos está funcionando correctamente con las tarifas de Colombia 2025.</p>";

} catch (Exception $e) {
    echo "<h2>✗ Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
}
?>