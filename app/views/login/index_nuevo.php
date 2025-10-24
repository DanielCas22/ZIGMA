<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="bg-body-tertiary">
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4" style="min-width:350px;">
            <div class="text-center mb-3">
                <img src="/ZIGMA/public_nuevo/img/logo.png" alt="ZIGMA" height="72" class="logo-glow"/>
            </div>
            <h2 class="mb-4 text-center">ZIGMA - Iniciar Sesión</h2>
            
            <?php if (!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
