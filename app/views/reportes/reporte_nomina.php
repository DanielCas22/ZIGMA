<?php
// Vista: Reporte de Nómina General
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Nómina - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Reporte General de Nómina</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title mb-3">Estadísticas Generales</h4>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Total Nómina Pagada:</strong> $<?= number_format($estadisticas['total_nomina'], 0, ',', '.') ?></li>
                <li class="list-group-item"><strong>Total Devengado:</strong> $<?= number_format($estadisticas['total_devengado'], 0, ',', '.') ?></li>
                <li class="list-group-item"><strong>Total Deducido:</strong> $<?= number_format($estadisticas['total_deducido'], 0, ',', '.') ?></li>
                <li class="list-group-item"><strong>Promedio por Empleado:</strong> $<?= number_format($estadisticas['promedio_nomina'], 0, ',', '.') ?></li>
            </ul>
        </div>
    </div>
    <h5>Detalle de Nómina por Empleado</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Devengado</th>
                    <th>Deducido</th>
                    <th>Total Horas Extras</th>
                    <th>Valor Horas Extras</th>
                    <th>Valor a Pagar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nomina as $row): ?>
                <?php 
                    $devengado_total = $row['devengado'] + $row['total_valor_horas']; 
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                    <td>$<?= number_format($devengado_total, 0, ',', '.') ?></td>
                    <td>$<?= number_format($row['deducido'], 0, ',', '.') ?></td>
                    <td><?= number_format($row['total_horas'], 1) ?></td>
                    <td>$<?= number_format($row['total_valor_horas'], 0, ',', '.') ?></td>
                    <td>$<?= number_format($row['valor_pagar'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="/ZIGMA/public/index.php?url=Dashboard/index" class="btn btn-zigma-fluor w-100 mb-2">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
        <div class="col-md-4">
            <a href="/ZIGMA/public/index.php?url=Reportes/descargarNomina&formato=pdf" class="btn btn-danger w-100 mb-2">
                <i class="fas fa-file-pdf me-2"></i>Descargar PDF
            </a>
        </div>
        <div class="col-md-4">
            <a href="/ZIGMA/public/index.php?url=Reportes/descargarNomina&formato=excel" class="btn btn-success w-100">
                <i class="fas fa-file-excel me-2"></i>Descargar Excel
            </a>
        </div>
    </div>
</div>
</body>
</html>
