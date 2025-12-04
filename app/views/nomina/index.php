<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nómina - Sistema de Pago de Salarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        .table-nomina {
            font-size: 11px;
        }
        .table-nomina th {
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 10px;
            padding: 8px 4px;
        }
        .table-nomina td {
            text-align: center;
            vertical-align: middle;
            padding: 6px 4px;
        }
        .table-nomina .empleado-nombre {
            text-align: left;
            font-weight: bold;
            background-color: #ecf0f1;
            max-width: 120px;
        }
        .table-nomina .valor-monetario {
            text-align: right;
            font-family: monospace;
            font-weight: bold;
        }
        .seccion-devengado {
            background-color: #d5e8d4 !important;
        }
        .seccion-seguridad {
            background-color: #dae8fc !important;
        }
        .seccion-parafiscales {
            background-color: #fff2cc !important;
        }
        .seccion-prestaciones {
            background-color: #f8cecc !important;
        }
        .seccion-deducciones {
            background-color: #ffe6cc !important;
        }
        .total-row {
            background-color: #2c3e50 !important;
            color: white !important;
            font-weight: bold;
        }
        .neto-pagar {
            background-color: #27ae60 !important;
            color: white !important;
            font-weight: bold;
            font-size: 12px;
        }
        .card-estadisticas {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            margin-bottom: 20px;
        }
        .btn-print {
            background: linear-gradient(45deg, #2c3e50, #3498db);
            border: none;
            color: white;
        }
        .btn-print:hover {
            background: linear-gradient(45deg, #34495e, #5dade2);
            color: white;
        }
        @media print {
            .no-print { display: none !important; }
            .table-nomina { font-size: 9px; }
            .nomina-header { background: #2c3e50 !important; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Nómina Completa"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header-zigma text-center no-print mb-4">
        <h1><i class="fas fa-file-invoice-dollar me-3"></i>NÓMINA PARA PAGO DE SALARIOS</h1>
        <p class="mb-1">Período: <?= strtoupper(date('F Y', strtotime($periodo . '-01'))) ?></p>
        <p class="mb-0">Generado: <?= date('d/m/Y H:i', strtotime($fecha_generacion)) ?></p>
    </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4 no-print">
            <div class="col-md-3">
                <div class="card-zigma">
                    <div class="card-body text-center">
                        <h5 class="text-zigma-primary"><i class="fas fa-users me-2"></i>Total Empleados</h5>
                        <h3 class="text-zigma-navy"><?= $total_empleados ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-zigma">
                    <div class="card-body text-center">
                        <h5 class="text-zigma-secondary"><i class="fas fa-dollar-sign me-2"></i>Total Devengado</h5>
                        <h3 class="text-zigma-navy">$<?= number_format($totales_empresa['total_devengado'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-zigma">
                    <div class="card-body text-center">
                        <h5 class="text-zigma-primary"><i class="fas fa-minus-circle me-2"></i>Total Deducciones</h5>
                        <h3 class="text-zigma-navy">$<?= number_format($totales_empresa['total_deducciones'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-zigma">
                    <div class="card-body text-center">
                        <h5 class="text-zigma-secondary"><i class="fas fa-money-bill-wave me-2"></i>Neto a Pagar</h5>
                        <h3 class="text-zigma-navy">$<?= number_format($totales_empresa['total_neto_pagar'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Nómina -->
        <div class="table-responsive">
            <table class="table table-nomina table-zigma table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 40px;">No.</th>
                        <th rowspan="2" style="width: 150px;">Nombres y Apellidos</th>
                        <th colspan="3" style="background-color: #27ae60;">SUELDO</th>
                        <th colspan="5" style="background-color: #2ecc71;" class="seccion-devengado">DEVENGADO</th>
                        <th style="background-color: #27ae60;">TOTAL DEVENGADO</th>
                        <th colspan="5" style="background-color: #e74c3c;" class="seccion-deducciones">DEDUCCIONES</th>
                        <th style="background-color: #e74c3c;">TOTAL DEDUCCIÓN</th>
                        <th style="background-color: #27ae60;">NETO PAGADO</th>
                    </tr>
                    <tr>
                        <th>BÁSICO</th>
                        <th>DÍAS</th>
                        <th>HORAS</th>
                        <th>SUELDO BÁSICO</th>
                        <th>HORAS EXTRAS</th>
                        <th>COMISIÓN</th>
                        <th>AUXILIO TRANS.</th>
                        <th>OTROS</th>
                        <th>Salud</th>
                        <th>Pensión</th>
                        <th>Fondo Solidaridad</th>
                        <th>Retención</th>
                        <th>Otros</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Primera sección: Datos básicos de empleados -->
                    <?php if (!empty($nomina_empleados)): ?>
                        <?php foreach ($nomina_empleados as $index => $nomina): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td class="empleado-nombre"><?= htmlspecialchars($nomina['empleado']['nombre'] . ' ' . $nomina['empleado']['apellido']) ?></td>
                            <td class="valor-monetario"><?= number_format($nomina['empleado']['salario_basico'], 0, ',', '.') ?></td>
                            <td>30</td>
                            <td>240</td>
                            <td class="valor-monetario">$<?= number_format($nomina['devengado']['salario_basico'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['devengado']['horas_extras'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['devengado']['comisiones'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['devengado']['auxilio_transporte'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['devengado']['otros_devengados'], 0, ',', '.') ?></td>
                            <td class="valor-monetario total-row">$<?= number_format($nomina['devengado']['total_devengado'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['deducciones']['salud'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['deducciones']['pension'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['deducciones']['fondo_solidaridad'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['deducciones']['retencion_fuente'], 0, ',', '.') ?></td>
                            <td class="valor-monetario">$<?= number_format($nomina['deducciones']['otros_deducidos'], 0, ',', '.') ?></td>
                            <td class="valor-monetario total-row">$<?= number_format($nomina['deducciones']['total_deducciones'], 0, ',', '.') ?></td>
                            <td class="valor-monetario neto-pagar">$<?= number_format($nomina['resumen']['neto_pagar'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Fila de totales -->
                        <tr class="total-row">
                            <td colspan="2"><strong>TOTALES</strong></td>
                            <td><strong>-</strong></td>
                            <td><strong>-</strong></td>
                            <td><strong>-</strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_column($nomina_empleados, 'devengado'))['salario_basico'] ?? 0, 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['devengado']['horas_extras'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['devengado']['comisiones'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['devengado']['auxilio_transporte'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['devengado']['otros_devengados'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_devengado'], 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['deducciones']['salud'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['deducciones']['pension'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['deducciones']['fondo_solidaridad'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['deducciones']['retencion_fuente'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['deducciones']['otros_deducidos'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_deducciones'], 0, ',', '.') ?></strong></td>
                            <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_neto_pagar'], 0, ',', '.') ?></strong></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="18" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay empleados para procesar en la nómina
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Segunda tabla: Información adicional para empleadores -->
        <div class="row mt-5">
            <div class="col-12">
                <h4>Información Adicional para el Empleador</h4>
                <div class="table-responsive">
                    <table class="table table-nomina table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nombres y Apellidos</th>
                                <th colspan="4" class="seccion-seguridad">SEGURIDAD SOCIAL EMPLEADOR</th>
                                <th colspan="3" class="seccion-parafiscales">APORTES PARAFISCALES</th>
                                <th colspan="4" class="seccion-prestaciones">PRESTACIONES SOCIALES</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="seccion-seguridad">SALUD</th>
                                <th class="seccion-seguridad">PENSIÓN</th>
                                <th class="seccion-seguridad">ARL</th>
                                <th class="seccion-seguridad">TOTAL</th>
                                <th class="seccion-parafiscales">SENA</th>
                                <th class="seccion-parafiscales">ICBF</th>
                                <th class="seccion-parafiscales">CAJA COMPENSACIÓN</th>
                                <th class="seccion-prestaciones">CESANTÍAS</th>
                                <th class="seccion-prestaciones">INTERESES</th>
                                <th class="seccion-prestaciones">PRIMA</th>
                                <th class="seccion-prestaciones">VACACIONES</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($nomina_empleados)): ?>
                                <?php foreach ($nomina_empleados as $index => $nomina): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td class="empleado-nombre"><?= htmlspecialchars($nomina['empleado']['nombre'] . ' ' . $nomina['empleado']['apellido']) ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['seguridad_social_empleado']['salud'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['seguridad_social_empleado']['pension'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$0</td>
                                    <td class="valor-monetario">$<?= number_format($nomina['seguridad_social_empleado']['total_ss_empleado'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['aportes_parafiscales']['sena'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['aportes_parafiscales']['icbf'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['aportes_parafiscales']['caja_compensacion'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['prestaciones_sociales']['cesantias'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['prestaciones_sociales']['intereses_cesantias'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['prestaciones_sociales']['prima_servicios'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario">$<?= number_format($nomina['prestaciones_sociales']['vacaciones'], 0, ',', '.') ?></td>
                                    <td class="valor-monetario total-row">$<?= number_format($nomina['resumen']['total_costo_empresa'] - $nomina['resumen']['total_devengado'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <!-- Totales -->
                                <tr class="total-row">
                                    <td colspan="2"><strong>TOTALES</strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['seguridad_social_empleado']['salud'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['seguridad_social_empleado']['pension'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$0</strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format(array_sum(array_map(fn($n) => $n['seguridad_social_empleado']['total_ss_empleado'], $nomina_empleados)), 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_parafiscales'] * 0.22, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_parafiscales'] * 0.33, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_parafiscales'] * 0.44, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_prestaciones'] * 0.25, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_prestaciones'] * 0.25, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_prestaciones'] * 0.25, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_prestaciones'] * 0.25, 0, ',', '.') ?></strong></td>
                                    <td class="valor-monetario"><strong>$<?= number_format($totales_empresa['total_costo_empresa'] - $totales_empresa['total_devengado'], 0, ',', '.') ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Firmas -->
        <div class="row mt-5 no-print">
            <div class="col-md-3 text-center">
                <div style="height: 80px; border-bottom: 2px solid #2c3e50; margin-bottom: 10px;"></div>
                <strong>PAGADOR</strong>
            </div>
            <div class="col-md-3 text-center">
                <div style="height: 80px; border-bottom: 2px solid #2c3e50; margin-bottom: 10px;"></div>
                <strong>PREPARADO POR</strong>
            </div>
            <div class="col-md-3 text-center">
                <div style="height: 80px; border-bottom: 2px solid #2c3e50; margin-bottom: 10px;"></div>
                <strong>REVISADO POR</strong>
            </div>
            <div class="col-md-3 text-center">
                <div style="height: 80px; border-bottom: 2px solid #2c3e50; margin-bottom: 10px;"></div>
                <strong>APROBADO POR</strong>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función de impresión optimizada
        window.addEventListener('beforeprint', function() {
            document.body.style.fontSize = '10px';
        });
        
        window.addEventListener('afterprint', function() {
            document.body.style.fontSize = '';
        });
    </script>
</body>
</html>