<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            background-attachment: fixed;
        }
        .card-zigma {
            border-radius: 1.5rem;
            border: 1.5px solid #e0e7ff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
            animation: fadeInCard 1s cubic-bezier(.4,0,.2,1);
        }
        @keyframes fadeInCard {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInCard 1s cubic-bezier(.4,0,.2,1); }
        .form-label {
            font-weight: 500;
            color: #3b3b3b;
        }
        .form-control, .form-select {
            border-radius: 0.7rem;
            border: 1.5px solid #cbd5e1;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 2px #6366f133;
        }
        .input-group-text {
            background: #f1f5f9;
            border-radius: 0.7rem 0 0 0.7rem;
            border: 1.5px solid #cbd5e1;
            border-right: 0;
        }
        .btn-zigma-success, .btn-zigma-secondary {
            border-radius: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .btn-zigma-success:hover, .btn-zigma-secondary:hover {
            box-shadow: 0 4px 16px 0 rgba(99,102,241,0.13);
            filter: brightness(1.08);
        }
        .form-section {
            border-bottom: 1px dashed #e0e7ff;
            margin-bottom: 1.2rem;
            padding-bottom: 1.2rem;
        }
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6366f1;
            opacity: 0.7;
        }
        .input-with-icon {
            position: relative;
        }
        .input-with-icon input {
            padding-left: 2.2rem;
        }
        .underline-title {
            display: inline-block;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 2px;
            margin-bottom: 6px;
        }
        .pulse {
            animation: pulseGlow 1.5s infinite alternate;
        }
        @keyframes pulseGlow {
            from { box-shadow: 0 0 0 0 #6366f144; }
            to { box-shadow: 0 0 16px 8px #6366f122; }
        }
        @media (max-width: 576px) {
            .btn, .btn-zigma-success, .btn-zigma-secondary {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
            .form-label, .form-control, .form-select { font-size: clamp(12px, 3vw, 13px) !important; }
            h2 { font-size: clamp(1rem, 4vw, 1.2rem) !important; }
        }
        @media (min-width: 577px) {
            .btn, .btn-zigma-success, .btn-zigma-secondary {
                font-size: 15px !important;
                padding: 8px 18px !important;
                min-width: 100px;
                max-width: 180px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<?php $pageTitle = "Registrar Empleado"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 fade-in-up">
            <div class="card-zigma shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa fa-user-plus fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-secondary fw-bold underline-title">Registrar Empleado</h2>
                        <p class="text-muted">Complete los datos para crear un nuevo empleado y usuario asociado</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="post" action="/ZIGMA/public/index.php?url=Empleado/store">
                        <div class="mb-3 form-section input-with-icon">
                            <i class="fa fa-user form-icon" title="Nombres"></i>
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan Carlos" required title="Ingrese los nombres del empleado">
                        </div>
                        <div class="mb-3 form-section input-with-icon">
                            <i class="fa fa-user-tag form-icon" title="Apellido"></i>
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Pérez" required title="Ingrese el apellido del empleado">
                        </div>
                        <div class="mb-3 form-section input-with-icon">
                            <i class="fa fa-user-circle form-icon" title="Usuario"></i>
                            <label class="form-label">Usuario <span class="text-danger">*</span></label>
                            <input type="text" name="usuario" class="form-control" placeholder="Nombre de usuario único" required minlength="4" maxlength="32" autocomplete="username" title="Nombre de usuario para el acceso">
                        </div>
                        <div class="mb-3 form-section input-with-icon">
                            <i class="fa fa-lock form-icon" title="Contraseña"></i>
                            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña segura" required minlength="6" maxlength="64" autocomplete="new-password" title="Contraseña para el acceso">
                        </div>
                        <div class="mb-3 form-section">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required id="rolSelect" title="Seleccione el rol o cargo del empleado">
                                <option value="">Seleccione un rol</option>
                                <?php foreach ((new \App\Models\Rol())->getAll() as $rol): ?>
                                    <?php if (strtolower($rol['nombre']) !== 'admin'): ?>
                                        <option value="<?= htmlspecialchars($rol['nombre']) ?>"><?= htmlspecialchars(ucfirst($rol['nombre'])) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 form-section">
                            <label class="form-label">Sueldo Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="sueldo_actual" id="sueldoInput" class="form-control" placeholder="Ingrese un sueldo" min="1" max="100000000" step="1" value="<?= isset($salario_minimo) && $salario_minimo ? htmlspecialchars($salario_minimo) : '' ?>" title="Sueldo inicial del empleado" readonly>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Salario mínimo actual: <strong>$<?= isset($salario_minimo) && $salario_minimo ? number_format($salario_minimo, 0) : '0' ?></strong>
                            </div>
                        </div>
                        <div class="mb-3 form-section">
                            <label class="form-label">🛡️ Nivel de Riesgo ARL</label>
                            <select name="riesgo_arl" class="form-select" required id="riesgoSelect" title="Seleccione el nivel de riesgo ARL">
                                <option value="">Seleccione el nivel de riesgo</option>
                                <option value="1">Clase I - Mínimo (0.522%)</option>
                                <option value="2" selected>Clase II - Bajo (1.044%) - Por defecto</option>
                                <option value="3">Clase III - Medio (2.436%)</option>
                                <option value="4">Clase IV - Alto (4.350%)</option>
                                <option value="5">Clase V - Máximo (6.960%)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn-zigma-success px-4" title="Registrar empleado">
                                <i class="fas fa-user-plus me-2"></i>Registrar
                            </button>
                            <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn-zigma-secondary px-4" title="Cancelar y volver">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// El sueldo mínimo es el que se configura en parámetros, igual para todos los roles
var salarioMinimoSMLV = <?php echo isset($salario_minimo) ? $salario_minimo : 0; ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Al cargar, mostrar el SMLV en el campo de sueldo
    var sueldoInput = document.getElementById('sueldoInput');
    sueldoInput.value = salarioMinimoSMLV;
});
</script>
</body>
</html>


