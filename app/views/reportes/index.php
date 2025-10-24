<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Reportes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Gestión de Reportes</h3>
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning">Volver al Dashboard</a>
    </div>

    <?php if (!empty($_SESSION['flash']['success'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['flash']['success']); unset($_SESSION['flash']['success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash']['error'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['flash']['error']); unset($_SESSION['flash']['error']); ?></div>
    <?php endif; ?>

    <div class="card p-3 shadow-sm mb-4">
      <h5 class="mb-3">Crear nuevo reporte</h5>
      <form action="/ZIGMA/public_nuevo/index.php?url=reportes/store" method="POST" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre del reporte</label>
          <input class="form-control" name="nombre" placeholder="Ej. Nómina Septiembre 2025" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">Año</label>
          <input type="number" class="form-control" name="anio" value="<?php echo (int)date('Y'); ?>" min="2000" max="2100" required />
        </div>
        <div class="col-md-3">
          <label class="form-label">Mes</label>
          <select class="form-select" name="mes" required>
            <?php for ($m=1; $m<=12; $m++): $sel = ($m==(int)date('n')) ? 'selected' : ''; ?>
              <option value="<?php echo sprintf('%02d', $m); ?>" <?php echo $sel; ?>><?php echo sprintf('%02d', $m); ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Crear</button>
        </div>
      </form>
    </div>

    <div class="card shadow-sm">
      <div class="card-header">Reportes creados</div>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Fecha</th>
              <th>Periodo</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (($data['reportes'] ?? []) as $r): ?>
              <tr>
                <td><?php echo (int)$r['id_reportes']; ?></td>
                <td><?php echo htmlspecialchars($r['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars(($r['dia'] ?? '').'/'.($r['mes'] ?? '').'/'.($r['anio'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars(($r['mes'] ?? '').'/'.($r['anio'] ?? '')); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>