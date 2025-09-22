<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Editar Empleado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <div class="container py-4">
    <h3>Editar Empleado</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>
    <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
    <?php if (!empty($_SESSION['flash']['error'])): ?><div class="alert alert-danger"><?php echo nl2br(htmlspecialchars($_SESSION['flash']['error'])); unset($_SESSION['flash']['error']); ?></div><?php endif; ?>
    <?php if (!empty($_SESSION['flash']['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div><?php endif; ?>
    <form method="POST" action="/ZIGMA/public_nuevo/index.php?url=empleado/update/<?php echo (int)($data['empleado']['id_empleados'] ?? 0); ?>" class="card p-3 shadow-sm">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['nombre'] ?? ($data['empleado']['nombre'] ?? '')); ?>" required />
        </div>
        <div class="col-md-6">
          <label class="form-label">Apellido</label>
      <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['apellido'] ?? ($data['empleado']['apellido'] ?? '')); ?>" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Usuario</label>
      <input type="text" name="usuario" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['usuario'] ?? ($data['empleado']['usuario'] ?? '')); ?>" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
          <input type="password" name="contrasena" class="form-control" />
        </div>
        <div class="col-md-6">
          <label class="form-label">Sueldo Actual</label>
      <input type="number" name="sueldo_actual" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['sueldo_actual'] ?? ($data['empleado']['sueldo_actual'] ?? '')); ?>" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Tipo Documento</label>
      <input type="text" name="tipo_doc" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['tipo_doc'] ?? ($data['empleado']['tipo_doc'] ?? '')); ?>" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Número Documento</label>
      <input type="number" name="num_doc" class="form-control" value="<?php echo htmlspecialchars($_SESSION['old']['num_doc'] ?? ($data['empleado']['num_doc'] ?? '')); ?>" />
        </div>
      </div>
      <div class="mt-3">
        <a href="/ZIGMA/public_nuevo/index.php?url=empleado" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Actualizar</button>
      </div>
    </form>
    <?php unset($_SESSION['old']); ?>
  </div>
</body>
</html>
