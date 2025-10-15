<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Horas Extras - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<?php $pageTitle = "Agregar Horas Extras"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 fade-in-up">
            <div class="card-zigma shadow-lg">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa-solid fa-clock fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-primary fw-bold">Agregar Horas Extras</h2>
                        <p class="text-muted">Registra las horas extras de un empleado</p>
                    </div>
                    <?php if (isset($error)): ?>
                        <div class="alert-zigma-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Empleado</label>
                            <select name="empleado_id" class="form-select" required>
                                <option value="">Seleccione un empleado</option>
                                <?php 
                                $selectedEmpleado = isset($_GET['empleado_id']) ? $_GET['empleado_id'] : (isset($_POST['empleado_id']) ? $_POST['empleado_id'] : '');
                                foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleados'] ?>" <?= ($selectedEmpleado == $emp['id_empleados']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de Horas</label>
                            <input type="number" name="cantidad" class="form-control" step="0.1" min="0.1" max="24" required placeholder="Ej: 8.5">
                            <div class="form-text">
                                <i class="fas fa-clock text-info"></i>
                                Ingrese la cantidad de horas extras (máximo 24 horas por día)
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Horas Extras</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccione el tipo de horas extras</option>
                                <?php if (isset($tipos_disponibles) && !empty($tipos_disponibles)): ?>
                                    <?php foreach ($tipos_disponibles as $tipo): ?>
                                        <option value="<?= htmlspecialchars($tipo['nombre']) ?>">
                                            <?= htmlspecialchars($tipo['nombre']) ?> (+<?= $tipo['porcentaje'] ?>%)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="Extra diurna">Extra diurna (+25%)</option>
                                    <option value="Extra nocturna">Extra nocturna (+75%)</option>
                                    <option value="Extra diurna dominical/festiva">Extra diurna dominical/festiva (+105%)</option>
                                    <option value="Extra nocturna dominical/festiva">Extra nocturna dominical/festiva (+155%)</option>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-primary"></i>
                                El valor se calculará automáticamente según las tarifas de Colombia 2025
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Día</label>
                                <input type="number" name="dia" class="form-control" min="1" max="31" required value="<?= date('d') ?>">
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
                                    $mes_actual = date('n');
                                    foreach ($meses as $num => $nombre): ?>
                                        <option value="<?= $num ?>" <?= ($num == $mes_actual) ? 'selected' : '' ?>><?= $nombre ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Año</label>
                                <input type="number" name="anio" class="form-control" min="2020" max="2030" required value="<?= date('Y') ?>">
                            </div>
                        </div>
                        <div class="alert-zigma-info">
                            <i class="fas fa-calculator me-2"></i>
                            <strong>Cálculo automático:</strong> El valor de las horas extras se calculará automáticamente usando las tarifas oficiales de Colombia 2025.
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn-zigma-success px-4">
                                <i class="fas fa-save me-2"></i>Guardar
                            </button>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn-zigma-secondary px-4">
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
