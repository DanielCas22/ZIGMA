<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Empleados - ZIGMA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body class="bg-body-tertiary">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Empleados</h3>
      <a href="/ZIGMA/public_nuevo/index.php?url=empleado/create" class="btn btn-primary">Nuevo</a>
    </div>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>

    <div class="card shadow-sm">
      <div class="table-responsive">
  <table class="table table-striped table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Usuario</th>
              <th>Sueldo Actual</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($data['empleados'] ?? []) as $e): ?>
              <tr>
                <td><?php echo (int)$e['id_empleados']; ?></td>
                <td><?php echo htmlspecialchars($e['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($e['apellido'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($e['usuario'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($e['sueldo_actual'] ?? ''); ?></td>
                <td>
                  <a class="btn btn-sm btn-warning" href="/ZIGMA/public_nuevo/index.php?url=empleado/edit/<?php echo (int)$e['id_empleados']; ?>">Editar</a>
                  <a class="btn btn-sm btn-danger" href="/ZIGMA/public_nuevo/index.php?url=empleado/delete/<?php echo (int)$e['id_empleados']; ?>" onclick="return confirm('¿Eliminar empleado?');">Eliminar</a>
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
