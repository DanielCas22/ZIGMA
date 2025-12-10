<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --zigma-navy: #1e3a8a;
            --zigma-blue: #2563eb;
            --zigma-pink: #ec4899;
            --zigma-magenta: #d946ef;
            --zigma-cyan: #06b6d4;
            --zigma-cyan-light: #22d3ee;
            --zigma-dark: #1f2937;
        }

        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #ec4899 50%, #06b6d4 100%);
            min-height: 100vh;
            position: relative;
            margin: 0;
            padding: 0;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .card {
            border-radius: 1.5rem;
            border: none;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.2), 
                        0 0 20px rgba(6, 182, 212, 0.15);
        }

        .card h2 {
            color: #1e3a8a;
            font-weight: 700;
        }

        .form-label {
            color: #1f2937;
            font-weight: 500;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #d946ef 0%, #ec4899 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .alert-danger {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 0.5rem;
        }

        .logo-container {
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4" style="min-width:400px; max-width:450px;">
            <h2 class="mb-4 text-center">Iniciar Sesión</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
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
