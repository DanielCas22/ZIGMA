<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); }
        .login-card { max-width: 400px; margin: 40px auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(30,58,138,0.08); }
        @media (max-width: 576px) {
            .login-card { margin: 10px; padding: 0.5rem !important; }
            .form-label, .form-control, .btn { font-size: 13px !important; }
            h3, p { font-size: clamp(1rem, 4vw, 1.1rem) !important; }
        }
    </style>
</head>
<body>
    <div class="login-card bg-white p-4">
        <div class="text-center mb-3">
            <h3 class="mb-0 text-zigma-primary fw-bold">ZIGMA</h3>
            <p class="text-muted">Acceso al sistema</p>
        </div>
        <form method="post" action="/ZIGMA/public/index.php?url=Usuario/login">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control" required autofocus autocomplete="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Ingresar
            </button>
        </form>
        <div class="text-center mt-3">
            <small class="text-muted">&copy; <?= date('Y') ?> ZIGMA CORPORATION S.A.S</small>
        </div>
    </div>
</body>
</html>
