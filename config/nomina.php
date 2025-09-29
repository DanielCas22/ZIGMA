<?php
/**
 * Configuración de Nómina y Aportes Colombia
 * Porcentajes según normativa colombiana vigente
 */

// APORTES DE SALUD
define('PORC_SALUD_EMPLEADOR', 8.5);   // 8.5% empleador
define('PORC_SALUD_EMPLEADO', 4.0);    // 4% trabajador
define('PORC_SALUD_TOTAL', 12.5);      // 12.5% total

// APORTES DE PENSIÓN  
define('PORC_PENSION_EMPLEADOR', 12.0); // 12% empleador
define('PORC_PENSION_EMPLEADO', 4.0);   // 4% trabajador
define('PORC_PENSION_TOTAL', 16.0);     // 16% total

// RIESGOS LABORALES (ARL) - Variable según clase de riesgo
define('ARL_RIESGO_I', 0.522);    // Riesgo I - Mínimo
define('ARL_RIESGO_II', 1.044);   // Riesgo II - Bajo
define('ARL_RIESGO_III', 2.436);  // Riesgo III - Medio
define('ARL_RIESGO_IV', 4.350);   // Riesgo IV - Alto
define('ARL_RIESGO_V', 6.960);    // Riesgo V - Máximo

// SALARIO MÍNIMO LEGAL VIGENTE (año 2025)
define('SALARIO_MINIMO', 1423000);

// AUXILIO DE TRANSPORTE
define('AUXILIO_TRANSPORTE', 200000);

// LÍMITE PARA AUXILIO DE TRANSPORTE (2 SMLV)
define('LIMITE_AUXILIO_TRANSPORTE', SALARIO_MINIMO * 2);

/**
 * Obtener información de riesgo ARL
 */
function getInfoRiesgoARL($nivel) {
    $riesgos = [
        1 => ['codigo' => 'I', 'porcentaje' => ARL_RIESGO_I, 'descripcion' => 'Riesgo Mínimo'],
        2 => ['codigo' => 'II', 'porcentaje' => ARL_RIESGO_II, 'descripcion' => 'Riesgo Bajo'],
        3 => ['codigo' => 'III', 'porcentaje' => ARL_RIESGO_III, 'descripcion' => 'Riesgo Medio'],
        4 => ['codigo' => 'IV', 'porcentaje' => ARL_RIESGO_IV, 'descripcion' => 'Riesgo Alto'],
        5 => ['codigo' => 'V', 'porcentaje' => ARL_RIESGO_V, 'descripcion' => 'Riesgo Máximo']
    ];
    
    return $riesgos[$nivel] ?? null;
}

/**
 * Validar que los porcentajes sumen correctamente
 */
function validarPorcentajes() {
    $salud_total = PORC_SALUD_EMPLEADOR + PORC_SALUD_EMPLEADO;
    $pension_total = PORC_PENSION_EMPLEADOR + PORC_PENSION_EMPLEADO;
    
    $errores = [];
    
    if ($salud_total != PORC_SALUD_TOTAL) {
        $errores[] = "Error en porcentajes de salud: {$salud_total}% != " . PORC_SALUD_TOTAL . "%";
    }
    
    if ($pension_total != PORC_PENSION_TOTAL) {
        $errores[] = "Error en porcentajes de pensión: {$pension_total}% != " . PORC_PENSION_TOTAL . "%";
    }
    
    return $errores;
}

return [
    'salud' => [
        'empleador' => PORC_SALUD_EMPLEADOR,
        'empleado' => PORC_SALUD_EMPLEADO,
        'total' => PORC_SALUD_TOTAL
    ],
    'pension' => [
        'empleador' => PORC_PENSION_EMPLEADOR,
        'empleado' => PORC_PENSION_EMPLEADO,
        'total' => PORC_PENSION_TOTAL
    ],
    'arl' => [
        'riesgo_1' => ARL_RIESGO_I,
        'riesgo_2' => ARL_RIESGO_II,
        'riesgo_3' => ARL_RIESGO_III,
        'riesgo_4' => ARL_RIESGO_IV,
        'riesgo_5' => ARL_RIESGO_V
    ],
    'salarios' => [
        'minimo' => SALARIO_MINIMO,
        'auxilio_transporte' => AUXILIO_TRANSPORTE,
        'limite_auxilio' => LIMITE_AUXILIO_TRANSPORTE
    ]
];
?>
