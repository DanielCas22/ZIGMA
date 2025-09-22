<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Desprendible de Nómina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-4">
    <h3>Desprendible de Nómina</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>
    <div class="card p-3 shadow-sm">
      <div class="row">
        <div class="col-md-6">
          <p><strong>Empleado:</strong> <?php echo htmlspecialchars(($data['nomina']['nombre'] ?? '').' '.($data['nomina']['apellido'] ?? '')); ?></p>
          <p><strong>Periodo:</strong> <?php echo htmlspecialchars(($data['nomina']['mes'] ?? '').'/'.($data['nomina']['anio'] ?? '')); ?></p>
        </div>
        <div class="col-md-6 text-end">
          <p><strong>Fecha:</strong> <?php echo htmlspecialchars(($data['nomina']['dia'] ?? '')); ?>/<?php echo htmlspecialchars(($data['nomina']['mes'] ?? '')); ?>/<?php echo htmlspecialchars(($data['nomina']['anio'] ?? '')); ?></p>
          <p><strong>Nómina #</strong> <?php echo htmlspecialchars(($data['nomina']['id_nomina'] ?? '')); ?></p>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6">
          <h5>Devengados</h5>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>Salario</span> <strong>$<?php echo number_format($data['dev']['salario'] ?? 0, 0, ',', '.'); ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Días</span> <strong><?php echo htmlspecialchars($data['dev']['dias'] ?? 0); ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Total Devengado</span> <strong>$<?php echo number_format($data['dev']['total'] ?? 0, 0, ',', '.'); ?></strong>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <h5>Deducciones</h5>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>Salud + Pensión</span> <strong>$<?php echo number_format($data['ded']['valor'] ?? 0, 0, ',', '.'); ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Otros</span> <strong><?php echo htmlspecialchars($data['ded']['otros'] ?? ''); ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Total Deducido</span> <strong>$<?php echo number_format($data['ded']['total'] ?? 0, 0, ',', '.'); ?></strong>
            </li>
          </ul>
        </div>
      </div>

      <div class="alert alert-success mt-3">
        <h5 class="mb-0">Neto a Pagar: $<?php echo number_format(($data['nomina']['valor_pagar'] ?? 0), 0, ',', '.'); ?></h5>
      </div>
    </div>
    <div class="mt-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=nomina/calcular" class="btn btn-secondary">Volver</a>
      <button class="btn btn-outline-primary" onclick="window.print()">Imprimir/PDF</button>
    </div>
  </div>
</body>
</html>
