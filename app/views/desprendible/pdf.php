<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desprendible de Nómina - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .desprendible-table {
            border: 2px solid #000;
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
        }
        
        .desprendible-table td, .desprendible-table th {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
        }
        
        .header-empresa {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .label-field {
            background-color: #e0e0e0;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .value-field {
            background-color: #fff;
        }
        
        .section-devengado {
            background-color: #f0f8f0;
        }
        
        .section-deducido {
            background-color: #fff8e0;
        }
        
        .section-neto {
            background-color: #e0f0f8;
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
            overflow: hidden;
        }
        
        .signature-left {
            float: left;
            width: 60%;
        }
        
        .signature-right {
            float: right;
            width: 35%;
            text-align: right;
        }

        @media (max-width: 576px) {
            body { padding: 5px !important; font-size: 11px !important; }
            .desprendible-table { font-size: 10px !important; }
            .desprendible-table th, .desprendible-table td { padding: 4px !important; }
            .header-empresa { font-size: 12px !important; }
        }
    </style>
</head>
<body>
    <table class="desprendible-table">
        <!-- Header con información de la empresa -->
        <tr>
            <td rowspan="4" class="header-empresa" style="width: 150px;">
                <div style="text-align: center;">
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
            <td style="text-align: center;" colspan="2"><strong>NETO PAGADO:</strong></td>
            <td class="currency"><strong>$ <?= number_format($data['desprendible']['neto_pagado'], 0, ',', '.') ?></strong></td>
        </tr>
    </table>
    
    <!-- Sección de Firmas -->
    <div class="signature-section">
        <div class="signature-left">
            <small>Recibí a satisfacción y acepto en todas sus partes este pago.</small>
        </div>
        <div class="signature-right">
            <small>Larause <?= $data['desprendible']['numero_desprendible'] ?></small><br>
            <strong>Firma y C.C. EMPLEADO</strong>
        </div>
    </div>
</body>
</html>