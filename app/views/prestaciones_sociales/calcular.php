<?php
// Vista básica para calcular prestaciones sociales
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcular Prestaciones Sociales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4>Calcular Prestaciones Sociales</h4>
        </div>
        <div class="card-body">
            
            <!-- Mensajes -->
            <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger">';
                echo '<strong>Error:</strong> ' . htmlspecialchars($error);
                echo '</div>';
            }
            
            if (isset($success)) {
                echo '<div class="alert alert-success">';
                echo '<strong>Éxito:</strong> ' . htmlspecialchars($success);
                echo '</div>';
            }
            ?>

            <!-- Formulario -->
            <form method="POST" action="?url=PrestacionesSociales/calcular">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="empleado_id" class="form-label">Empleado</label>
                            <select name="empleado_id" id="empleado_id" class="form-select" required>
                                <option value="">Seleccione un empleado...</option>
                                <?php
                                if (isset($empleados) && is_array($empleados)) {
                                    foreach ($empleados as $empleado) {
                                        echo '<option value="' . $empleado['id'] . '">';
                                        echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']);
                                        if (isset($empleado['cargo'])) {
                                            echo ' - ' . htmlspecialchars($empleado['cargo']);
                                        }
                                        echo '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <select name="anio" id="anio" class="form-select">
                                <?php
                                $anioActual = date('Y');
                                for ($i = $anioActual; $i >= ($anioActual - 5); $i--) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        Calcular Prestaciones
                    </button>
                </div>
            </form>

            <!-- Resultados -->
            <?php
            if (isset($calculo) && $calculo) {
                echo '<hr>';
                echo '<h5>Resultados del Cálculo</h5>';
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered">';
                echo '<thead class="table-primary">';
                echo '<tr><th>Concepto</th><th>Valor</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                
                // Cesantías
                if (isset($calculo['prestaciones']['cesantias']['valor_cesantias'])) {
                    echo '<tr>';
                    echo '<td>Cesantías (8.33%)</td>';
                    echo '<td class="text-end">$' . number_format($calculo['prestaciones']['cesantias']['valor_cesantias']) . '</td>';
                    echo '</tr>';
                }
                
                // Intereses
                if (isset($calculo['prestaciones']['intereses_cesantias']['valor_intereses'])) {
                    echo '<tr>';
                    echo '<td>Intereses Cesantías (1%)</td>';
                    echo '<td class="text-end">$' . number_format($calculo['prestaciones']['intereses_cesantias']['valor_intereses']) . '</td>';
                    echo '</tr>';
                }
                
                // Prima
                if (isset($calculo['prestaciones']['prima_servicios']['valor_prima'])) {
                    echo '<tr>';
                    echo '<td>Prima de Servicios (8.33%)</td>';
                    echo '<td class="text-end">$' . number_format($calculo['prestaciones']['prima_servicios']['valor_prima']) . '</td>';
                    echo '</tr>';
                }
                
                // Vacaciones
                if (isset($calculo['prestaciones']['vacaciones']['valor_vacaciones'])) {
                    echo '<tr>';
                    echo '<td>Vacaciones (4.17%)</td>';
                    echo '<td class="text-end">$' . number_format($calculo['prestaciones']['vacaciones']['valor_vacaciones']) . '</td>';
                    echo '</tr>';
                }
                
                // Total
                if (isset($calculo['resumen']['total_prestaciones'])) {
                    echo '<tr class="table-success">';
                    echo '<td><strong>TOTAL PRESTACIONES</strong></td>';
                    echo '<td class="text-end"><strong>$' . number_format($calculo['resumen']['total_prestaciones']) . '</strong></td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
            ?>

        </div>
    </div>

    <div class="text-center mt-3">
        <a href="?url=Dashboard" class="btn btn-primary">
            Volver al Inicio
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>