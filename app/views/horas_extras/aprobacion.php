<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Aprobación de Horas Extras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-4">
    <h3>Horas Extras Pendientes</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>
    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Empleado</th>
              <th>Tipo</th>
              <th>Cantidad</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($data['pendientes'] ?? []) as $h): ?>
              <tr>
                <td><?php echo (int)$h['id_extras']; ?></td>
                <td><?php echo htmlspecialchars(($h['nombre'] ?? '').' '.($h['apellido'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars($h['tipo'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($h['cantidad'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars(($h['dia'] ?? '').'/'.($h['mes'] ?? '').'/'.($h['anio'] ?? '')); ?></td>
                <td>
                  <a class="btn btn-sm btn-success" href="/ZIGMA/public_nuevo/index.php?url=horaextra/aprobar/<?php echo (int)$h['id_extras']; ?>">Aprobar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
