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
        <li class="nav-item"><a class="nav-link" href="#">Gestión 1</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Gestión 2</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Gestión 3</a></li>
      </ul>
    </div>
    <div class="col-md-10 p-5">
      <h2>Accesos rápidos</h2>
      <div class="row g-4 mt-2">
        <div class="col-md-4">
          <div class="card text-center shadow">
            <div class="card-body">
              <h5 class="card-title">Gestión 1</h5>
              <a href="#" class="btn btn-primary">Ir</a>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
