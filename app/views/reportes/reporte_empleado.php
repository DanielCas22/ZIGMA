<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte por Empleado - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Reporte por Empleado</h2>
    <form method="get" action="/ZIGMA/public/index.php">
        <input type="hidden" name="url" value="Reportes/reporteEmpleado">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="empleado_id" class="form-label">Seleccione un empleado</label>
                <select name="empleado_id" id="empleado_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($empleados as $emp): ?>
                        <option value="<?= $emp['id_empleados'] ?>" <?= isset($_GET['empleado_id']) && $_GET['empleado_id'] == $emp['id_empleados'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
    <?php if ($resumen): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-3">Resumen de <?= htmlspecialchars($resumen['empleado']['nombre'] . ' ' . $resumen['empleado']['apellido']) ?></h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Total Devengado:</strong> $<?= number_format($resumen['total_devengado'], 0, ',', '.') ?></li>
                    <li class="list-group-item"><strong>Total Deducido:</strong> $<?= number_format($resumen['total_deducido'], 0, ',', '.') ?></li>
                    <li class="list-group-item"><strong>Total Horas Extras:</strong> <?= number_format($resumen['total_horas_extras'], 1) ?> horas ($<?= number_format($resumen['total_valor_extras'], 0, ',', '.') ?>)</li>
                </ul>
                <h5>Detalle de Horas Extras</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Valor</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resumen['horas_extras'] as $he): ?>
                            <tr>
                                <td><?= htmlspecialchars($he['dia'] . '/' . $he['mes'] . '/' . $he['anio']) ?></td>
                                <td><?= htmlspecialchars($he['tipo']) ?></td>
                                <td><?= htmlspecialchars($he['cantidad']) ?></td>
                                <td>$<?= number_format($he['valor'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($he['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <a href="/ZIGMA/public/index.php?url=Reportes/descargarEmpleado&empleado_id=<?= $resumen['empleado']['id_empleados'] ?>&formato=pdf" class="btn btn-danger me-2"><i class="fas fa-file-pdf me-2"></i>Descargar PDF</a>
                    <a href="/ZIGMA/public/index.php?url=Reportes/descargarEmpleado&empleado_id=<?= $resumen['empleado']['id_empleados'] ?>&formato=excel" class="btn btn-success"><i class="fas fa-file-excel me-2"></i>Descargar Excel</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <a href="/ZIGMA/public/index.php?url=Reportes" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left me-2"></i>Volver a Reportes</a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
