<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Resultado Nómina</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <div class="container py-4">
    <h3>Resultado Nómina</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>
    <div class="card p-3 shadow-sm">
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Empleado:</strong> <?php echo htmlspecialchars(($data['empleado']['nombre'] ?? '').' '.($data['empleado']['apellido'] ?? '')); ?></li>
        <li class="list-group-item"><strong>Periodo:</strong> <?php echo htmlspecialchars(($data['mes'] ?? '').'/'.($data['anio'] ?? '')); ?></li>
        <li class="list-group-item"><strong>Días:</strong> <?php echo htmlspecialchars($data['base']['dias'] ?? ''); ?></li>
        <li class="list-group-item"><strong>Salario proporcional:</strong> $<?php echo number_format($data['base']['salario_proporcional'] ?? 0, 0, ',', '.'); ?></li>
        <li class="list-group-item"><strong>Total Horas Extras:</strong> $<?php echo number_format($data['total_he'] ?? 0, 0, ',', '.'); ?></li>
        <li class="list-group-item"><strong>Auxilio Transporte:</strong> $<?php echo number_format($data['aux_transporte'] ?? 0, 0, ',', '.'); ?></li>
        <li class="list-group-item"><strong>Deducción Salud (4%):</strong> $<?php echo number_format($data['salud'] ?? 0, 0, ',', '.'); ?></li>
        <li class="list-group-item"><strong>Deducción Pensión (4%):</strong> $<?php echo number_format($data['pension'] ?? 0, 0, ',', '.'); ?></li>
        <li class="list-group-item"><strong>Neto a Pagar:</strong> $<?php echo number_format($data['valor_pagar'] ?? 0, 0, ',', '.'); ?></li>
      </ul>
    </div>
  </div>
</body>
</html>
