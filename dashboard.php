<?php
// views/dashboard.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../index.php');
    exit;
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard ZIGMA</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="d-flex">
    <nav class="bg-dark text-white p-3 vh-100" style="width:220px;">
        <h4 class="mb-4">Menú</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="#">Gestión de Nómina</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Prestaciones Sociales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Parafiscales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Seguridad Social</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Reportes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../index.php">Cerrar sesión</a></li>
        </ul>
    </nav>
    <main class="flex-grow-1 p-4">
        <h2>Bienvenido, <?php echo ucfirst($role); ?>!</h2>
        <p>Seleccione una gestión en el menú lateral.</p>
    </main>
</div>
</body>
</html>