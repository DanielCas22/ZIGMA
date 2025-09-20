<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">ZIGMA</a>
    <div class="d-flex">
      <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($data['user']['rol']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 bg-light vh-100 p-3">
      <h5>Menú</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link active fw-bold text-primary d-flex align-items-center" href="/ZIGMA/public/index.php?url=HorasExtras">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-history me-2" viewBox="0 0 16 16">
              <path d="M8.515 3.879a.5.5 0 0 0-1 0v4.25a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 1 0 .496-.868l-3.248-1.856V3.88z"/>
              <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zm0-1A7 7 0 1 0 8 1a7 7 0 0 0 0 14z"/>
            </svg>
            Horas Extras
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold text-success d-flex align-items-center" href="/ZIGMA/public/index.php?url=Empleado/index">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
              <path d="M13 7a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
              <path fill-rule="evenodd" d="M13 9c1.105 0 2 .672 2 1.5V13h-5v-2.5c0-.828.895-1.5 2-1.5zM6 9c1.105 0 2 .672 2 1.5V13H1v-2.5C1 9.672 1.895 9 3 9z"/>
            </svg>
            Empleados
          </a>
        </li>
        <li class="nav-item mb-2"><a class="nav-link" href="#">Gestión 3</a></li>
      </ul>
    </div>
    <div class="col-md-10 p-5">
      <h2>Accesos rápidos</h2>
      <div class="row g-4 mt-2">
        <div class="col-md-4">
          <div class="card text-center shadow border-primary border-2">
            <div class="card-body">
              <h5 class="card-title text-primary d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-clock-history me-2" viewBox="0 0 16 16">
                  <path d="M8.515 3.879a.5.5 0 0 0-1 0v4.25a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 1 0 .496-.868l-3.248-1.856V3.88z"/>
                  <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zm0-1A7 7 0 1 0 8 1a7 7 0 0 0 0 14z"/>
                </svg>
                Horas Extras
              </h5>
              <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-outline-primary fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-success border-2">
            <div class="card-body">
              <h5 class="card-title text-success d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
                  <path d="M13 7a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                  <path fill-rule="evenodd" d="M13 9c1.105 0 2 .672 2 1.5V13h-5v-2.5c0-.828.895-1.5 2-1.5zM6 9c1.105 0 2 .672 2 1.5V13H1v-2.5C1 9.672 1.895 9 3 9z"/>
                </svg>
                Empleados
              </h5>
              <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-outline-success fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow">
            <div class="card-body">
              <h5 class="card-title">Gestión 2</h5>
              <a href="#" class="btn btn-primary">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow">
            <div class="card-body">
              <h5 class="card-title">Gestión 3</h5>
              <a href="#" class="btn btn-primary">Ir</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow border-info border-2">
        <div class="card-body">
          <h3 class="mb-4 text-info"><i class="fa fa-users me-2"></i>Empleados registrados</h3>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-info">
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Apellidos</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($empleados)) : foreach ($empleados as $i => $emp) : ?>
                  <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($emp['nombre']) ?></td>
                    <td><?= htmlspecialchars($emp['apellidos']) ?></td>
                  </tr>
                <?php endforeach; else: ?>
                  <tr><td colspan="3" class="text-center text-muted">No hay empleados registrados</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
