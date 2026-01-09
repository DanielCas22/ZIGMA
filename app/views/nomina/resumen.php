<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Retención en la Fuente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4">Resumen Retención en la Fuente</h2>
    <div class="mb-3">
        <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-outline-primary">Volver a Empleados</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Empleado</h5>
            <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars(($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? '')) ?></p>
            <p class="mb-0"><strong>Salario:</strong> $<?= number_format($salario ?? 0, 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light">Procedimiento 1 (Art. 383)</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Límite 30% salario:</strong> $<?= number_format($proc1['limite_30_salario'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Subtotal 1:</strong> $<?= number_format($proc1['subtotal_1'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Dependientes (máx 32 UVT):</strong> $<?= number_format($proc1['dependientes_uvt_32'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Salud prepagada (máx 16 UVT):</strong> $<?= number_format($proc1['salud_prepagada_16_uvt'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Intereses vivienda (máx 100 UVT):</strong> $<?= number_format($proc1['intereses_vivienda_100_uvt'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Subtotal 2:</strong> $<?= number_format($proc1['subtotal_2'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Renta exenta:</strong> $<?= number_format($proc1['renta_exenta'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Base retención:</strong> $<?= number_format($proc1['base_retencion'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Base UVT:</strong> <?= number_format($proc1['base_retencion_uvt'] ?? 0, 2, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Retención (COP):</strong> $<?= number_format($proc1['retencion_art383'] ?? 0, 0, ',', '.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-light">Procedimiento 2 (Art. 384 - Mínima)</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Base gravable:</strong> $<?= number_format($proc2['base_gravable'] ?? 0, 0, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Base UVT:</strong> <?= number_format($proc2['base_uvt'] ?? 0, 2, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Retención mínima (UVT):</strong> <?= number_format($proc2['retencion_minima_uvt'] ?? 0, 2, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Retención mínima (COP):</strong> $<?= number_format($proc2['retencion_minima_cop'] ?? 0, 0, ',', '.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Resultado</h5>
            <p class="mb-1"><strong>Procedimiento aplicado:</strong> <?= htmlspecialchars($procedimiento_aplicado ?? '') ?></p>
            <p class="mb-0"><strong>Retención final:</strong> $<?= number_format($valor_final ?? 0, 0, ',', '.') ?></p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>