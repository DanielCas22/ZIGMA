<html>
<head>
    <meta charset="UTF-8">
    <title>Desprendible de Nómina</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .titulo { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .seccion { margin-bottom: 15px; }
        .tabla { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .tabla th, .tabla td { border: 1px solid #333; padding: 4px 8px; }
        .tabla th { background: #eee; }
        .resaltado { font-weight: bold; background: #e0ffe0; }
    </style>
</head>
<body>
    <div class="titulo">Desprendible de Nómina</div>
    <div class="seccion">
        <strong>Empleado:</strong> <?php echo htmlspecialchars(($nomina['nombre'] ?? '').' '.($nomina['apellido'] ?? '')); ?><br>
        <strong>Periodo:</strong> <?php echo htmlspecialchars(($nomina['mes'] ?? '').'/'.($nomina['anio'] ?? '')); ?><br>
        <strong>Fecha:</strong> <?php echo htmlspecialchars(($nomina['dia'] ?? '')); ?>/<?php echo htmlspecialchars(($nomina['mes'] ?? '')); ?>/<?php echo htmlspecialchars(($nomina['anio'] ?? '')); ?><br>
        <strong>Nómina #</strong> <?php echo htmlspecialchars(($nomina['id_nomina'] ?? '')); ?><br>
    </div>
    <table class="tabla">
        <tr><th colspan="2">Devengados</th></tr>
        <tr><td>Salario</td><td>$<?php echo number_format($dev['salario'] ?? 0, 0, ',', '.'); ?></td></tr>
        <tr><td>Días</td><td><?php echo htmlspecialchars($dev['dias'] ?? 0); ?></td></tr>
        <tr class="resaltado"><td>Total Devengado</td><td>$<?php echo number_format($dev['total'] ?? 0, 0, ',', '.'); ?></td></tr>
    </table>
    <table class="tabla">
        <tr><th colspan="2">Deducciones</th></tr>
        <tr><td>Salud + Pensión</td><td>$<?php echo number_format($ded['valor'] ?? 0, 0, ',', '.'); ?></td></tr>
        <tr><td>Otros</td><td><?php echo htmlspecialchars($ded['otros'] ?? ''); ?></td></tr>
        <tr class="resaltado"><td>Total Deducido</td><td>$<?php echo number_format($ded['total'] ?? 0, 0, ',', '.'); ?></td></tr>
    </table>
    <div class="seccion resaltado">
        Neto a Pagar: $<?php echo number_format(($nomina['valor_pagar'] ?? 0), 0, ',', '.'); ?>
    </div>
</body>
</html>