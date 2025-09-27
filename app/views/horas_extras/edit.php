<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Horas Extras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-warning border-2">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-warning text-dark rounded-circle p-3 mb-2">
                            <i class="fa-solid fa-edit fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-warning">Editar Horas Extras</h2>
                        <p class="text-muted">Modifica los datos de las horas extras registradas</p>
                    </div>
                    <div class="mb-3">
                        <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left"></i> Volver a Horas Extras
                        </a>
                    </div>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Empleado</label>
                            <select name="empleado_id" class="form-select" required>
                                <option value="">Seleccione un empleado</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleados'] ?>" <?= $emp['id_empleados'] == $hora['empleado_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['nombre'] . ' ' . (isset($emp['apellidos']) ? $emp['apellidos'] : '')) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de Horas</label>
                            <input type="number" name="cantidad" class="form-control" step="0.1" min="0.1" max="24" 
                                   value="<?= htmlspecialchars($hora['cantidad']) ?>" required placeholder="Ej: 8.5">
                            <div class="form-text">
                                <i class="fas fa-clock text-info"></i>
                                Cantidad de horas extras trabajadas (el valor se calculará automáticamente)
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Horas Extras</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccione el tipo de horas extras</option>
                                <?php if (isset($tipos_disponibles) && !empty($tipos_disponibles)): ?>
                                    <?php foreach ($tipos_disponibles as $tipo): ?>
                                        <option value="<?= htmlspecialchars($tipo['nombre']) ?>" 
                                                <?= ($tipo['nombre'] == $hora['tipo']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tipo['nombre']) ?> (+<?= $tipo['porcentaje'] ?>%)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="Extra diurna" <?= ($hora['tipo'] == 'Extra diurna') ? 'selected' : '' ?>>Extra diurna (+25%)</option>
                                    <option value="Extra nocturna" <?= ($hora['tipo'] == 'Extra nocturna') ? 'selected' : '' ?>>Extra nocturna (+75%)</option>
                                    <option value="Extra diurna dominical/festiva" <?= ($hora['tipo'] == 'Extra diurna dominical/festiva') ? 'selected' : '' ?>>Extra diurna dominical/festiva (+105%)</option>
                                    <option value="Extra nocturna dominical/festiva" <?= ($hora['tipo'] == 'Extra nocturna dominical/festiva') ? 'selected' : '' ?>>Extra nocturna dominical/festiva (+155%)</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Día</label>
                                <input type="number" name="dia" class="form-control" min="1" max="31" 
                                       value="<?= htmlspecialchars($hora['dia']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mes</label>
                                <select name="mes" class="form-select" required>
                                    <?php 
                                    $meses = [
                                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                    ];
                                    foreach ($meses as $num => $nombre): ?>
                                        <option value="<?= $num ?>" <?= ($num == $hora['mes']) ? 'selected' : '' ?>><?= $nombre ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Año</label>
                                <input type="number" name="anio" class="form-control" min="2020" max="2030" 
                                       value="<?= htmlspecialchars($hora['anio']) ?>" required>
                            </div>
                        </div>
                        
                        <!-- Mostrar valores actuales calculados -->
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-percentage me-2"></i>Porcentaje actual:</strong> 
                                    <?= htmlspecialchars($hora['porcentaje']) ?>%
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-dollar-sign me-2"></i>Valor actual:</strong> 
                                    $<?= number_format($hora['valor'], 0, ',', '.') ?>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Al guardar, estos valores se recalcularán automáticamente según las tarifas de Colombia 2025.
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-2"></i>Actualizar
                            </button>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
