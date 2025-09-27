<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Resumen Nómina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-4">
    <h3 class="mb-3">Resumen de Nómina</h3>

    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1"><strong>Empleado:</strong> <?php echo htmlspecialchars(($empleado['nombre'] ?? '').' '.($empleado['apellido'] ?? '')); ?></p>
            <p class="mb-1"><strong>Periodo:</strong> <?php echo htmlspecialchars(($mes ?? '').'/'.($anio ?? '')); ?></p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="mb-1"><strong>Total Devengado:</strong> $<?php echo number_format(($total_devengado ?? 0), 0, ',', '.'); ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header">Devengados</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span>Salario</span><strong>$<?php echo number_format(($salario ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Horas Extras</span><strong>$<?php echo number_format(($total_horas_extras ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Auxilio de Transporte</span><strong>$<?php echo number_format(($auxilio_transporte ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Total Devengado</span><strong>$<?php echo number_format(($total_devengado ?? 0), 0, ',', '.'); ?></strong></li>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header">Aportes Parafiscales (Empresa)</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span>Base de Cálculo</span><strong>$<?php echo number_format(($parafiscales['base_calculo'] ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>SENA (2%)</span><strong>$<?php echo number_format(($parafiscales['sena'] ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>ICBF (3%)</span><strong>$<?php echo number_format(($parafiscales['icbf'] ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Caja Compensación (4%)</span><strong>$<?php echo number_format(($parafiscales['compensacion'] ?? 0), 0, ',', '.'); ?></strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Total Parafiscales</span><strong>$<?php echo number_format(($parafiscales['total_parafiscales'] ?? 0), 0, ',', '.'); ?></strong></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <a class="btn btn-secondary" href="/ZIGMA/public/index.php?url=Dashboard">Volver</a>
    </div>
  </div>
</body>
</html>