<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <title>Desprendible - Filtro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
</head>
<body class="bg-body-tertiary">
  <div class="container py-4">
    <h3>Desprendible de Nómina</h3>
    <div class="mb-3">
      <a href="/ZIGMA/public_nuevo/index.php?url=dashboard" class="btn btn-outline-warning" onclick="return confirm('¿Volver al dashboard?');">Volver al Dashboard</a>
    </div>

    <div class="card p-3 shadow-sm">
      <form method="GET" action="/ZIGMA/public_nuevo/index.php">
        <input type="hidden" name="url" value="desprendible/ver" />
        <?php $rol = $data['rol'] ?? ''; ?>
        <?php if (in_array(strtolower($rol), ['admin','rrhh'])): ?>
          <div class="mb-3">
            <label class="form-label">Empleado</label>
            <select name="empleado_id" class="form-select" required>
              <option value="" disabled selected>Seleccione empleado</option>
              <?php foreach (($data['empleados'] ?? []) as $e): ?>
                <option value="<?php echo (int)$e['id_empleados']; ?>"><?php echo htmlspecialchars(($e['nombre'] ?? '').' '.($e['apellido'] ?? '')); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php else: ?>
          <input type="hidden" name="empleado_id" value="<?php echo (int)($data['self_empleado_id'] ?? 0); ?>" />
          <div class="alert alert-info">Se mostrará tu desprendible.</div>
        <?php endif; ?>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Año</label>
            <input type="number" name="anio" class="form-control" value="<?php echo (int)date('Y'); ?>" min="2000" max="2100" required/>
          </div>
          <div class="col-md-4">
            <label class="form-label">Mes</label>
            <select name="mes" class="form-select" required>
              <?php for ($m=1; $m<=12; $m++): $sel = ($m==(int)date('n')) ? 'selected' : ''; ?>
                <option value="<?php echo sprintf('%02d', $m); ?>" <?php echo $sel; ?>><?php echo sprintf('%02d', $m); ?></option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Ver Desprendible</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>