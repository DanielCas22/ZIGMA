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
            <a class="navbar-brand" href="#">ZIGMA - Sistema de Nómina</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Bienvenido, <?php echo htmlspecialchars($data['user']['empleado_nombre']); ?> 
                    (<?php echo htmlspecialchars($data['user']['rol_nombre']); ?>)
                </span>
                <a href="/ZIGMA/public_nuevo/index.php?url=login/logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-light vh-100 p-3">
                <h5>Menú Principal</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Empleados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Nómina</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Reportes</a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-10 p-5">
                <h2>Panel de Control</h2>
                <p class="text-muted">Bienvenido al sistema de gestión de nómina ZIGMA</p>
                
                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <i class="bi bi-people-fill" style="font-size: 2rem; color: #0d6efd;"></i>
                                <h5 class="card-title mt-2">Gestión de Empleados</h5>
                                <p class="card-text">Administrar información de empleados</p>
                                <a href="#" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <i class="bi bi-calculator-fill" style="font-size: 2rem; color: #198754;"></i>
                                <h5 class="card-title mt-2">Cálculo de Nómina</h5>
                                <p class="card-text">Procesar y calcular nóminas</p>
                                <a href="#" class="btn btn-success">Acceder</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem; color: #fd7e14;"></i>
                                <h5 class="card-title mt-2">Reportes</h5>
                                <p class="card-text">Generar reportes y estadísticas</p>
                                <a href="#" class="btn btn-warning">Acceder</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6>Información del Usuario:</h6>
                    <ul class="mb-0">
                        <li><strong>Nombre:</strong> <?php echo htmlspecialchars($data['user']['empleado_nombre']); ?></li>
                        <li><strong>Rol:</strong> <?php echo htmlspecialchars($data['user']['rol_nombre']); ?></li>
                        <li><strong>Usuario:</strong> <?php echo htmlspecialchars($data['user']['username']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
