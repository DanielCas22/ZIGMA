<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Horas Extras - <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .valor-total {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .badge-tipo {
            font-size: 0.8em;
            padding: 6px 12px;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/ZIGMA/public/index.php?url=dashboard">
                <i class="fas fa-building me-2"></i>ZIGMA - Sistema de Nómina
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/ZIGMA/public/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Botón Volver -->
        <div class="mb-3">
            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-back">
                <i class="fas fa-arrow-left me-2"></i>Volver a Horas Extras
            </a>
        </div>

        <!-- Información del Empleado -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Detalle de Horas Extras - <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']) ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Empleado:</strong> <?= htmlspecialchars($empleado['id_empleados']) ?></p>
                        <p><strong>Nombre Completo:</strong> <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Documento:</strong> 
                            <?= isset($empleado['id_doc']) ? htmlspecialchars($empleado['id_doc']) : '<span class="text-muted">No disponible</span>' ?>
                        </p>
                        <p><strong>Sistema:</strong> <span class="badge bg-primary">ZIGMA Nómina 2025</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Totales -->
        <div class="valor-total text-center">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        <?= number_format($total_horas, 1) ?> horas
                    </h3>
                    <small>Total Horas Extras</small>
                </div>
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>
                        $<?= number_format($total_valor, 0, ',', '.') ?>
                    </h3>
                    <small>Valor Total</small>
                </div>
            </div>
        </div>

        <!-- Tabla de Horas Extras -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Registro de Horas Extras (<?= count($horasExtras) ?> registros)
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($horasExtras)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay horas extras registradas</h5>
                        <p class="text-muted">Este empleado no tiene horas extras registradas en el sistema.</p>
                        <a href="/ZIGMA/public/index.php?url=HorasExtras/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Registrar Horas Extras
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo de Hora Extra</th>
                                    <th>Cantidad (horas)</th>
                                    <th>Porcentaje</th>
                                    <th>Valor Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horasExtras as $he): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-calendar me-2"></i>
                                            <?= str_pad($he['dia'], 2, '0', STR_PAD_LEFT) ?>/<?= str_pad($he['mes'], 2, '0', STR_PAD_LEFT) ?>/<?= $he['anio'] ?>
                                        </td>
                                        <td>
                                            <?php
                                            $badge_class = '';
                                            switch ($he['tipo']) {
                                                case 'Extra diurna':
                                                    $badge_class = 'bg-primary';
                                                    break;
                                                case 'Extra nocturna':
                                                    $badge_class = 'bg-dark';
                                                    break;
                                                case 'Extra diurna dominical/festiva':
                                                    $badge_class = 'bg-warning text-dark';
                                                    break;
                                                case 'Extra nocturna dominical/festiva':
                                                    $badge_class = 'bg-danger';
                                                    break;
                                                default:
                                                    $badge_class = 'bg-secondary';
                                            }
                                            ?>
                                            <span class="badge <?= $badge_class ?> badge-tipo">
                                                <?= htmlspecialchars($he['tipo']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= number_format($he['cantidad'], 1) ?></strong> hrs
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= $he['porcentaje'] ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                $<?= number_format($he['valor'], 0, ',', '.') ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="/ZIGMA/public/index.php?url=HorasExtras/edit/<?= $he['id_extras'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="/ZIGMA/public/index.php?url=HorasExtras/delete/<?= $he['id_extras'] ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   onclick="return confirm('¿Está seguro de eliminar este registro?')"
                                                   title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>