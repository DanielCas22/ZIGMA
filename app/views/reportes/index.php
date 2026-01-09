<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reporte-card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            border: none;
            transition: box-shadow 0.2s;
        }
        .reporte-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.13);
            transform: translateY(-2px) scale(1.02);
        }
        .reporte-card .card-title {
            font-weight: bold;
            color: #2a3f54;
        }
        .reporte-card .btn-primary {
            background: linear-gradient(90deg, #4f8cff 0%, #6fd6ff 100%);
            border: none;
        }
        .reporte-card .btn-success {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            border: none;
        }
        .reporte-card .btn-warning {
            background: linear-gradient(90deg, #ffc107 0%, #ffeb3b 100%);
            border: none;
        }
        .reporte-card .btn {
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
        }
        .reporte-card .btn:active {
            transform: scale(0.98);
        }
        .reporte-card .btn + .btn {
            margin-left: 8px;
        }
        .reporte-card .card-text {
            color: #6c757d;
        }
        .alert-restricted {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Módulo de Reportes</h2>
    
    <?php
    $userRole = isset($_SESSION['user']['rol']) ? $_SESSION['user']['rol'] : 'empleado';
    $isEmpleado = $userRole === 'empleado';
    $isAdmin = $userRole === 'admin';
    ?>
    
    <div class="alert alert-info">
        <?php if ($isEmpleado): ?>
            Aquí puede generar y descargar sus reportes individuales.
        <?php else: ?>
            Seleccione el tipo de reporte que desea generar.
        <?php endif; ?>
    </div>
    
    <div class="row justify-content-center">
        <!-- Reporte General - Solo para administradores -->
        <?php if (!$isEmpleado): ?>
        <div class="col-md-6 col-lg-5">
            <div class="card mb-4 reporte-card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2"><i class="fas fa-file-alt me-2 text-primary"></i>Reporte General</h5>
                    <p class="card-text mb-4">Descargue un resumen general de la nómina y empleados.</p>
                    <a href="/ZIGMA/public/index.php?url=Reportes/descargarGeneral&formato=pdf" class="btn btn-danger mb-2 w-100"><i class="fas fa-file-pdf me-2"></i>Descargar PDF</a>
                    <a href="/ZIGMA/public/index.php?url=Reportes/descargarGeneral&formato=excel" class="btn btn-success w-100"><i class="fas fa-file-excel me-2"></i>Descargar Excel</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Reporte por Empleado - Para todos -->
        <div class="col-md-6 col-lg-5">
            <div class="card mb-4 reporte-card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2"><i class="fas fa-user me-2 text-success"></i>Reporte por Empleado</h5>
                    <p class="card-text mb-4">
                        <?php if ($isEmpleado): ?>
                            Consulte y descargue su resumen individual, incluyendo devengado, deducido y horas extras.
                        <?php else: ?>
                            Consulte y descargue el resumen individual de cada empleado, incluyendo devengado, deducido y horas extras.
                        <?php endif; ?>
                    </p>
                    <a href="/ZIGMA/public/index.php?url=Reportes/reporteEmpleado" class="btn btn-primary w-100"><i class="fas fa-user me-2"></i>Ver Reporte</a>
                </div>
            </div>
        </div>
        
        <!-- Reporte de Nómina - Solo para administradores y coordinadores RRHH -->
        <?php if (!$isEmpleado): ?>
        <div class="col-md-6 col-lg-5">
            <div class="card mb-4 reporte-card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2"><i class="fas fa-coins me-2 text-warning"></i>Reporte de Nómina</h5>
                    <p class="card-text mb-4">Visualice el reporte general de nómina con valores, gastos detallados y estadísticas.</p>
                    <a href="/ZIGMA/public/index.php?url=Reportes/reporteNomina" class="btn btn-warning w-100"><i class="fas fa-coins me-2"></i>Ver Reporte</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Mensaje de restricción para empleados -->
        <?php if ($isEmpleado): ?>
        <div class="col-md-6 col-lg-5 mt-3">
            <div class="alert alert-restricted" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Acceso Restringido:</strong> Como empleado, solo puede generar y ver su reporte individual. Los reportes generales y de nómina están disponibles solo para administradores.
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <a href="/ZIGMA/public/index.php?url=Dashboard" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left me-2"></i>Volver al Dashboard</a>
</div>
<!-- FontAwesome para íconos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
