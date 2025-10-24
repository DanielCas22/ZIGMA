<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Usuarios - ZIGMA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Gestión de Usuarios</h3>
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard?');">Volver al Dashboard</a>
    </div>

    <?php if (!empty($_SESSION['flash']['success'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash']['error'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>User ID</th>
              <th>Documento</th>
              <th>Empleado</th>
              <th>Usuario</th>
              <th>Rol actual</th>
              <th>Asignar nuevo rol</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($data['usuarios'] ?? []) as $u): ?>
              <tr>
                <td><?php echo (int)($u['user_id'] ?? 0); ?></td>
                <td><?php echo htmlspecialchars(($u['tipo_doc'] ?? '').' '.($u['num_doc'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars(($u['nombre'] ?? '').' '.($u['apellido'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars($u['usuario'] ?? ''); ?></td>
                <td><span class="badge text-bg-info"><?php echo htmlspecialchars($u['rol_nombre'] ?? 'sin rol'); ?></span></td>
                <td>
                  <form action="/ZIGMA/public_nuevo/index.php?url=usuario/asignar_rol" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="user_id" value="<?php echo (int)($u['user_id'] ?? 0); ?>" />
                    <select name="rol_id" class="form-select form-select-sm" required>
                      <option value="" disabled selected>Seleccione rol</option>
                      <?php foreach (($data['roles'] ?? []) as $r): ?>
                        <option value="<?php echo (int)$r['id_rol']; ?>" <?php echo (isset($u['rol_id']) && (int)$u['rol_id'] === (int)$r['id_rol']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($r['nombre']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                  </form>
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