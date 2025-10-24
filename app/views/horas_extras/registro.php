<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Registro de Horas Extras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <div class="container py-4">
    <h3>Registrar Horas Extras</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard? Se perderán los cambios no guardados.');">Volver al Dashboard</a>
    </div>
  <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
  <?php if (!empty($_SESSION['flash']['error'])): ?><div class="alert alert-danger"><?php echo nl2br(htmlspecialchars($_SESSION['flash']['error'])); unset($_SESSION['flash']['error']); ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['flash']['success'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div><?php endif; ?>
    <form method="POST" action="/ZIGMA/public_nuevo/index.php?url=horaextra/guardar" class="card p-3 shadow-sm">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Tipo</label>
          <select name="tipo" class="form-select">
            <option value="HED">HED - Extra Diurna (25%)</option>
            <option value="HEN">HEN - Extra Nocturna (75%)</option>
            <option value="HEFD">HEFD - Extra Festiva Diurna (100%)</option>
            <option value="HEFN">HEFN - Extra Festiva Nocturna (150%)</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cantidad de horas</label>
          <input type="number" name="cantidad" class="form-control" min="1" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">Día</label>
          <input type="text" name="dia" class="form-control" value="<?php echo date('d'); ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Mes</label>
          <input type="text" name="mes" class="form-control" value="<?php echo date('m'); ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Año</label>
          <input type="text" name="anio" class="form-control" value="<?php echo date('Y'); ?>" />
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Enviar</button>
      </div>
    </form>
  </div>
</body>
</html>
