<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/ZIGMA/public_nuevo/img/logo.png" alt="ZIGMA" height="36" class="me-2"/>
                <span>ZIGMA - Sistema de Nómina</span>
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">
                    Bienvenido, <?php echo htmlspecialchars($data['user']['empleado_nombre']); ?> 
                    (<?php echo htmlspecialchars($data['user']['rol_nombre']); ?>)
                </span>
                <div class="form-check form-switch me-3">
                  <input class="form-check-input" type="checkbox" id="themeSwitch">
                  <label class="form-check-label text-light" for="themeSwitch" id="themeSwitchLabel">Oscuro</label>
                </div>
                <a href="/ZIGMA/public_nuevo/index.php?url=login/logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 vh-100 p-3 bg-dark text-white">
                <h5 class="text-white">Menú Principal</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link link-light" href="#">Dashboard</a>
                    </li>
                    <?php $rol = strtolower($data['user']['rol_nombre'] ?? ''); ?>
                    <?php if (in_array($rol, ['admin','rrhh'])): ?>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=empleado">Empleados</a></li>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=usuario">Usuarios</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=nomina">Nómina</a></li>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=horaextra/registro">Horas Extras</a></li>
                    <?php if (in_array($rol, ['admin','rrhh'])): ?>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=horaextra/aprobacion">Aprobar Horas</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=desprendible">Desprendible</a></li>
                    <?php if (in_array($rol, ['admin','rrhh'])): ?>
                    <li class="nav-item"><a class="nav-link link-light" href="/ZIGMA/public_nuevo/index.php?url=reportes">Reportes</a></li>
                    <?php endif; ?>
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
                                <a href="/ZIGMA/public_nuevo/index.php?url=empleado" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <i class="bi bi-calculator-fill" style="font-size: 2rem; color: #198754;"></i>
                                <h5 class="card-title mt-2">Cálculo de Nómina</h5>
                                <p class="card-text">Procesar y calcular nóminas</p>
                                <a href="/ZIGMA/public_nuevo/index.php?url=nomina" class="btn btn-success">Acceder</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-center shadow">
                            <div class="card-body">
                                <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem; color: #fd7e14;"></i>
                                <h5 class="card-title mt-2">Reportes</h5>
                                <p class="card-text">Generar reportes y estadísticas</p>
                                <a href="/ZIGMA/public_nuevo/index.php?url=reportes" class="btn btn-warning">Acceder</a>
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
        <script>
            // Theme switch logic
            const themeSwitch = document.getElementById('themeSwitch');
            const themeLabel = document.getElementById('themeSwitchLabel');
            const htmlTag = document.documentElement;
            // Load preference
            const savedTheme = localStorage.getItem('bsTheme');
            if (savedTheme === 'light') {
                htmlTag.setAttribute('data-bs-theme', 'light');
                themeSwitch.checked = true;
                themeLabel.textContent = 'Claro';
            } else {
                htmlTag.setAttribute('data-bs-theme', 'dark');
                themeSwitch.checked = false;
                themeLabel.textContent = 'Oscuro';
            }
            themeSwitch.addEventListener('change', function() {
                if (this.checked) {
                    htmlTag.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('bsTheme', 'light');
                    themeLabel.textContent = 'Claro';
                } else {
                    htmlTag.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('bsTheme', 'dark');
                    themeLabel.textContent = 'Oscuro';
                }
            });
        </script>
</body>
</html>
