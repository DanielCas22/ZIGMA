<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            .desprendible-container { 
                margin: 0 !important; 
                padding: 0 !important;
                box-shadow: none !important;
            }
            body { background: white !important; }
            .card { border: 2px solid #000 !important; box-shadow: none !important; }
        }
        
        .desprendible-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .desprendible-table {
            border: 2px solid #000;
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        
        .desprendible-table td, .desprendible-table th {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }
        
        .header-empresa {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        
        .label-field {
            background-color: #e9ecef;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .value-field {
            background-color: #fff;
        }
        
        .section-devengado {
            background-color: #e8f5e8;
        }
        
        .section-deducido {
            background-color: #fff3cd;
        }
        
        .section-neto {
            background-color: #d1ecf1;
            font-weight: bold;
            font-size: 14px;
        }
        
        .currency {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        
        .signature-section {
            border-top: 2px solid #000;
            margin-top: 20px;
            padding-top: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-3">
        <!-- Botones de Acción -->
        <div class="row no-print mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= URL_ROOT ?>=Desprendible" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimir
                        </button>
                        <button type="button" class="btn btn-success" onclick="descargarPDF()">
                            <i class="fas fa-file-pdf me-1"></i> Descargar PDF
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCorreo">
                            <i class="fas fa-envelope me-1"></i> Enviar por Correo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desprendible -->
        <div class="desprendible-container">
            <div class="card shadow">
                <div class="card-body p-0">
                    <table class="desprendible-table">
                        <!-- Header con información de la empresa -->
                        <tr>
                            <td rowspan="4" class="header-empresa" style="width: 150px;">
                                <div class="text-center">
                                    <i class="fas fa-building fa-3x mb-2 text-primary"></i><br>
                                    <strong>LOGO DE LA EMPRESA</strong>
                                </div>
                            </td>
                            <td class="label-field" style="width: 100px;"><strong>NOMBRE:</strong></td>
                            <td class="value-field"><?= strtoupper($data['desprendible']['empresa']['nombre']) ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>NIT:</strong></td>
                            <td class="value-field"><?= $data['desprendible']['empresa']['nit'] ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>DIRECCIÓN:</strong></td>
                            <td class="value-field"><?= strtoupper($data['desprendible']['empresa']['direccion']) ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>TELÉFONO:</strong></td>
                            <td class="value-field"><?= $data['desprendible']['empresa']['telefono'] ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>CIUDAD:</strong></td>
                            <td class="value-field" colspan="2"><?= strtoupper($data['desprendible']['empresa']['ciudad']) ?></td>
                        </tr>
                        
                        <!-- Información del empleado -->
                        <tr>
                            <td class="label-field"><strong>TRABAJADOR:</strong></td>
                            <td class="value-field" colspan="2"><?= strtoupper($data['desprendible']['empleado']['nombre']) ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>PERIODO DE PAGO:</strong></td>
                            <td class="value-field" colspan="2"><?= $data['desprendible']['empleado']['periodo'] ?></td>
                        </tr>
                        <tr>
                            <td class="label-field"><strong>DÍAS TRABAJADOS:</strong></td>
                            <td class="value-field" colspan="2"><?= $data['desprendible']['empleado']['dias_trabajados'] ?></td>
                        </tr>
                        
                        <!-- Devengado -->
                        <tr class="section-devengado">
                            <td class="label-field"><strong>SUELDO BÁSICO:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['devengado']['sueldo_basico'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-devengado">
                            <td class="label-field"><strong>TOTAL HORAS EXTRAS:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['devengado']['horas_extras'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-devengado">
                            <td class="label-field"><strong>COMISIONES:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['devengado']['comisiones'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-devengado">
                            <td class="label-field"><strong>AUXILIO DE TRANSPORTE:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['devengado']['auxilio_transporte'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-devengado">
                            <td class="label-field"><strong>OTROS:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['devengado']['otros'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-devengado">
                            <td class="label-field"><strong>TOTAL DEVENGADO:</strong></td>
                            <td class="currency"><strong>$</strong></td>
                            <td class="currency"><strong><?= number_format($data['desprendible']['devengado']['total_devengado'], 0, ',', '.') ?></strong></td>
                        </tr>
                        
                        <!-- Deducciones -->
                        <tr class="section-deducido">
                            <td class="label-field"><strong>APORTES SALUD:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['deducciones']['aportes_salud'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-deducido">
                            <td class="label-field"><strong>APORTES PENSIÓN:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['deducciones']['aportes_pension'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-deducido">
                            <td class="label-field"><strong>APORTE F.S.:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['deducciones']['aportes_fs'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-deducido">
                            <td class="label-field"><strong>RETENCIÓN:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['deducciones']['retencion'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-deducido">
                            <td class="label-field"><strong>OTROS:</strong></td>
                            <td class="currency">$</td>
                            <td class="currency"><?= number_format($data['desprendible']['deducciones']['otros_descuentos'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="section-deducido">
                            <td class="label-field"><strong>TOTAL DEDUCIDO:</strong></td>
                            <td class="currency"><strong>$</strong></td>
                            <td class="currency"><strong><?= number_format($data['desprendible']['deducciones']['total_deducido'], 0, ',', '.') ?></strong></td>
                        </tr>
                        
                        <!-- Neto a Pagar -->
                        <tr class="section-neto">
                            <td class="text-center" colspan="2"><strong>NETO PAGADO:</strong></td>
                            <td class="currency"><strong>$ <?= number_format($data['desprendible']['neto_pagado'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </table>
                    
                    <!-- Sección de Firmas -->
                    <div class="signature-section p-3">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Recibí a satisfacción y acepto en todas sus partes este pago.</small>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">Larause <?= $data['desprendible']['numero_desprendible'] ?></small><br>
                                <strong>Firma y C.C. EMPLEADO</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para envío por correo -->
    <div class="modal fade" id="modalCorreo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-envelope me-2"></i>
                        Enviar Desprendible por Correo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEnviarCorreo">
                        <input type="hidden" name="empleado_id" value="<?= $data['desprendible']['empleado']['cedula'] ?>">
                        <input type="hidden" name="mes" value="<?= date('m') ?>">
                        <input type="hidden" name="anio" value="<?= date('Y') ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="empleado@correo.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje (opcional)</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="3" 
                                      placeholder="Mensaje adicional para el empleado..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="enviarCorreo()">
                        <i class="fas fa-paper-plane me-1"></i> Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function descargarPDF() {
            // Simular descarga de PDF
            const empleadoId = '<?= $data['desprendible']['empleado']['cedula'] ?>';
            const mes = '<?= date('m') ?>';
            const anio = '<?= date('Y') ?>';
            
            window.location.href = `<?= URL_ROOT ?>=Desprendible/pdf/${empleadoId}/${mes}/${anio}`;
        }
        
        function enviarCorreo() {
            const form = document.getElementById('formEnviarCorreo');
            const formData = new FormData(form);
            
            fetch('<?= URL_ROOT ?>=Desprendible/enviarCorreo', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Desprendible enviado correctamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalCorreo')).hide();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el desprendible');
            });
        }
        
        // Auto-focus en el modal de correo
        document.getElementById('modalCorreo').addEventListener('shown.bs.modal', function () {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>