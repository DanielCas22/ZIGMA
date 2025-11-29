<?php
use App\Models\RolePermissions;
?>
<?php require_once __DIR__ . '/../../models/RolePermissions.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ZIGMA</title>
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
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }
        
        .navbar {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        }
        
        .sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar h5 {
            color: #ec4899;
            font-weight: 700;
            border-bottom: 2px solid #ec4899;
            padding-bottom: 0.5rem;
        }
        
        .nav-link {
            color: #d1d5db !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }
        
        .nav-link:hover {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            color: white !important;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            color: white !important;
        }
        
        .nav-link svg {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover svg {
            transform: scale(1.1);
        }
        
        .btn-outline-light:hover {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            border-color: #ec4899;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
        }
        
        .card-title {
            color: #1e3a8a;
            font-weight: 700;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #d946ef 0%, #ec4899 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
            border: none;
            color: white;
        }
        
        .btn-info:hover {
            background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%);
        }
        
        .btn-outline-primary {
            color: #ec4899;
            border-color: #ec4899;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
            border-color: #ec4899;
            color: white;
        }
        
        .btn-outline-success {
            color: #06b6d4;
            border-color: #06b6d4;
        }
        
        .btn-outline-success:hover {
            background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
            border-color: #06b6d4;
            color: white;
        }
        
        .btn-outline-warning,
        .btn-outline-info,
        .btn-outline-danger {
            color: #d946ef;
            border-color: #d946ef;
        }
        
        .btn-outline-warning:hover,
        .btn-outline-info:hover,
        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #d946ef 0%, #ec4899 100%);
            border-color: #d946ef;
            color: white;
        }
        
        .border-primary {
            border-color: #ec4899 !important;
        }
        
        .border-success {
            border-color: #06b6d4 !important;
        }
        
        .border-warning,
        .border-info,
        .border-danger {
            border-color: #d946ef !important;
        }
        
        .text-primary {
            color: #ec4899 !important;
        }
        
        .text-success {
            color: #06b6d4 !important;
        }
        
        .text-warning,
        .text-info,
        .text-danger {
            color: #d946ef !important;
        }

        @media (max-width: 576px) {
            .sidebar { display: none !important; }
            .container-fluid, .row, .col-12, .card, .card-body { padding: 0.5rem !important; }
            .navbar { font-size: 13px !important; }
            h2, h5 { font-size: 1.1rem !important; }
            .btn, .btn-zigma-success, .btn-zigma-secondary {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
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
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <span>ZIGMA</span>
    </a>
    <div class="d-flex">
      <span class="navbar-text me-3">Bienvenido, <?php echo htmlspecialchars($data['user']['rol']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 sidebar vh-100 p-3">
      <h5>Menú</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link active fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=HorasExtras">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-history me-2" viewBox="0 0 16 16">
              <path d="M8.515 3.879a.5.5 0 0 0-1 0v4.25a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 1 0 .496-.868l-3.248-1.856V3.88z"/>
              <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zm0-1A7 7 0 1 0 8 1a7 7 0 0 0 0 14z"/>
            </svg>
            Horas Extras
          </a>
        </li>
        <?php if (RolePermissions::canAccess('horas_extras', 'approve')): ?>
        <li class="nav-item mb-2 ms-3">
          <a class="nav-link fw-bold d-flex align-items-center position-relative" href="/ZIGMA/public/index.php?url=HorasExtras/pendientes">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell me-2" viewBox="0 0 16 16">
              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
            </svg>
            Pendientes
            <?php if (isset($pendingHoursCount) && $pendingHoursCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $pendingHoursCount; ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
        <?php endif; ?>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Empleado/index">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
              <path d="M13 7a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
              <path fill-rule="evenodd" d="M13 9c1.105 0 2 .672 2 1.5V13h-5v-2.5c0-.828.895-1.5 2-1.5zM6 9c1.105 0 2 .672 2 1.5V13H1v-2.5C1 9.672 1.895 9 3 9z"/>
            </svg>
            Empleados
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=PrestacionesSociales">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gift me-2" viewBox="0 0 16 16">
              <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
            </svg>
            Prestaciones Sociales
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=SeguridadSocial">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-check me-2" viewBox="0 0 16 16">
              <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
              <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
            </svg>
            Seguridad Social
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Devengado">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cash-stack me-2" viewBox="0 0 16 16">
              <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zM7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
              <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
            </svg>
            Total Devengado
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=TotalDeducido">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-dash-circle me-2" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
            </svg>
            Total Deducido
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Parafiscales">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-building me-2" viewBox="0 0 16 16">
              <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z"/>
              <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z"/>
            </svg>
            Parafiscales
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Nomina">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-spreadsheet me-2" viewBox="0 0 16 16">
              <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>
            </svg>
            Nómina
          </a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Desprendible">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-invoice me-2" viewBox="0 0 16 16">
              <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
              <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
            </svg>
            Desprendibles
          </a>
        </li>
        <?php if (isset($data['user']['rol']) && $data['user']['rol'] === 'admin'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link fw-bold d-flex align-items-center" href="/ZIGMA/public/index.php?url=Admin/parametros">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear me-2" viewBox="0 0 16 16">
              <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
              <path d="M9.796 1.343c-.527-1.013-2.065-1.013-2.592 0l-.094.188a1.007 1.007 0 0 1-1.255.465l-.211-.087c-1.07-.428-2.255.323-2.255 1.466v.172a1.007 1.007 0 0 1-.465 1.255l-.188.094c-1.013.527-1.013 2.065 0 2.592l.188.094a1.007 1.007 0 0 1 .465 1.255l-.087.211c-.428 1.07.323 2.255 1.466 2.255h.172a1.007 1.007 0 0 1 1.255.465l.094.188c.527 1.013 2.065 1.013 2.592 0l.094-.188a1.007 1.007 0 0 1 1.255-.465h.172c1.07.428 2.255-.323 2.255-1.466v-.172a1.007 1.007 0 0 1 .465-1.255l.188-.094c1.013-.527 1.013-2.065 0-2.592l-.188-.094a1.007 1.007 0 0 1-.465-1.255l.087-.211c.428-1.07-.323-2.255-1.466-2.255h-.172a1.007 1.007 0 0 1-1.255-.465l-.094-.188zm-2.633.283c.246-.475.96-.475 1.206 0l.094.188a2.007 2.007 0 0 0 2.51.928l.211-.087c.475-.19 1.012.174 1.012.684v.172a2.007 2.007 0 0 0 .928 2.51l.188.094c.475.246.475.96 0 1.206l-.188.094a2.007 2.007 0 0 0-.928 2.51l.087.211c.19.475-.174 1.012-.684 1.012h-.172a2.007 2.007 0 0 0-2.51.928l-.094.188c-.246.475-.96.475-1.206 0l-.094-.188a2.007 2.007 0 0 0-2.51-.928l-.211.087c-.475.19-1.012-.174-1.012-.684v-.172a2.007 2.007 0 0 0-.928-2.51l-.188-.094c-.475-.246-.475-.96 0-1.206l.188-.094a2.007 2.007 0 0 0 .928-2.51l-.087-.211c-.19-.475.174-1.012.684-1.012h.172a2.007 2.007 0 0 0 2.51-.928l.094-.188z"/>
            </svg>
            Parámetros Administrativos
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="col-md-10 p-5">
      <?php if (isset($_GET['error']) && $_GET['error'] === 'no_permission'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Acceso Denegado:</strong> No tienes permisos para acceder a esa funcionalidad.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <!-- Notificaciones de Horas Extras Pendientes -->
      <?php if (isset($pendingHoursCount) && $pendingHoursCount > 0): ?>
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
          <div class="d-flex align-items-center">
            <i class="fas fa-bell text-warning me-3" style="font-size: 1.5rem;"></i>
            <div class="flex-grow-1">
              <h6 class="alert-heading mb-1">
                <strong>¡Tienes <?php echo $pendingHoursCount; ?> solicitud<?php echo $pendingHoursCount > 1 ? 'es' : ''; ?> de horas extras pendiente<?php echo $pendingHoursCount > 1 ? 's' : ''; ?>!</strong>
              </h6>
              <p class="mb-2">Los siguientes empleados necesitan aprobación de sus horas extras:</p>
              <div class="mb-3">
                <?php foreach ($pendingHours as $hora): ?>
                  <span class="badge bg-warning text-dark me-2 mb-1">
                    <i class="fas fa-user me-1"></i>
                    <?php echo htmlspecialchars($hora['nombre'] . ' ' . $hora['apellido']); ?>
                    (<?php echo number_format($hora['cantidad'], 2); ?> hrs)
                  </span>
                <?php endforeach; ?>
                <?php if ($pendingHoursCount > count($pendingHours)): ?>
                  <span class="badge bg-secondary">
                    +<?php echo ($pendingHoursCount - count($pendingHours)); ?> más...
                  </span>
                <?php endif; ?>
              </div>
              <a href="/ZIGMA/public/index.php?url=HorasExtras/pendientes" class="btn btn-warning btn-sm">
                <i class="fas fa-clock me-1"></i>
                Ver todas las solicitudes pendientes
              </a>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <h2>Accesos rápidos</h2>
      <div class="row g-4 mt-2">
        <div class="col-md-4">
          <div class="card text-center shadow border-primary border-2">
            <div class="card-body">
              <h5 class="card-title text-primary d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-clock-history me-2" viewBox="0 0 16 16">
                  <path d="M8.515 3.879a.5.5 0 0 0-1 0v4.25a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 1 0 .496-.868l-3.248-1.856V3.88z"/>
                  <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zm0-1A7 7 0 1 0 8 1a7 7 0 0 0 0 14z"/>
                </svg>
                Horas Extras
              </h5>
              <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-outline-primary fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-success border-2">
            <div class="card-body">
              <h5 class="card-title text-success d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
                  <path d="M13 7a2 2 0 1 0-4 0 2 2 0 0 0 4 0zM6 7a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                  <path fill-rule="evenodd" d="M13 9c1.105 0 2 .672 2 1.5V13h-5v-2.5c0-.828.895-1.5 2-1.5zM6 9c1.105 0 2 .672 2 1.5V13H1v-2.5C1 9.672 1.895 9 3 9z"/>
                </svg>
                Empleados
              </h5>
              <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-outline-success fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-warning border-2">
            <div class="card-body">
              <h5 class="card-title text-warning d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-gift me-2" viewBox="0 0 16 16">
                  <path d="M3 2.5a2.5 2.5 0 0 1 5 0 2.5 2.5 0 0 1 5 0v.006c0 .07 0 .27-.038.494H15a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 14.5V7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h2.038A2.968 2.968 0 0 1 3 2.5zm1.068.5H7v-.5a1.5 1.5 0 1 0-3 0c0 .085.002.274.045.43a.522.522 0 0 0 .023.07zM9 3h2.932a.56.56 0 0 0 .023-.07c.043-.156.045-.345.045-.43a1.5 1.5 0 0 0-3 0V3zM1 4v2h6V4H1zm8 0v2h6V4H9zm5 3H9v8h4.5a.5.5 0 0 0 .5-.5V7zm-7 8V7H2v7.5a.5.5 0 0 0 .5.5H7z"/>
                </svg>
                Prestaciones Sociales
              </h5>
              <a href="/ZIGMA/public/index.php?url=PrestacionesSociales" class="btn btn-outline-warning fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-info border-2">
            <div class="card-body">
              <h5 class="card-title text-info d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-check me-2" viewBox="0 0 16 16">
                  <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                  <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                </svg>
                Seguridad Social
              </h5>
              <p class="card-text small">Salud, Pensión y ARL</p>
              <a href="/ZIGMA/public/index.php?url=SeguridadSocial" class="btn btn-outline-info fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-danger border-2">
            <div class="card-body">
              <h5 class="card-title text-danger d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-cash-stack me-2" viewBox="0 0 16 16">
                  <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zM7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                  <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                </svg>
                Total Devengado
              </h5>
              <p class="card-text small">Sueldos, Horas Extras, Comisiones</p>
              <a href="/ZIGMA/public/index.php?url=Devengado" class="btn btn-outline-danger fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-danger border-2">
            <div class="card-body">
              <h5 class="card-title text-danger d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-dash-circle me-2" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                </svg>
                Total Deducido
              </h5>
              <p class="card-text small">Salud, Pensión, Fondo, Retención</p>
              <a href="/ZIGMA/public/index.php?url=TotalDeducido" class="btn btn-outline-danger fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-success border-2">
            <div class="card-body">
              <h5 class="card-title text-success d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-building me-2" viewBox="0 0 16 16">
                  <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1ZM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1ZM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1Zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1Z"/>
                  <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V1Zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3V1Z"/>
                </svg>
                Parafiscales
              </h5>
              <p class="card-text small">SENA, ICBF, Caja de Compensación</p>
              <a href="/ZIGMA/public/index.php?url=Parafiscales" class="btn btn-outline-success fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-primary border-2">
            <div class="card-body">
              <h5 class="card-title text-primary d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-earmark-spreadsheet me-2" viewBox="0 0 16 16">
                  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm-7 0v-2h3v2H6z"/>
                </svg>
                Nómina
              </h5>
              <p class="card-text small">Pago de Salarios y Liquidación</p>
              <a href="/ZIGMA/public/index.php?url=Nomina" class="btn btn-outline-primary fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center shadow border-warning border-2">
            <div class="card-body">
              <h5 class="card-title text-warning d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-invoice me-2" viewBox="0 0 16 16">
                  <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                  <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                </svg>
                Desprendibles
              </h5>
              <p class="card-text small">Generar y consultar desprendibles</p>
              <a href="/ZIGMA/public/index.php?url=Desprendible" class="btn btn-outline-warning fw-bold">Ir</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
            <?php include __DIR__ . '/reportes_card.php'; ?>
        </div>
        <div class="col-md-4">
            <?php include __DIR__ . '/parametros_card.php'; ?>
        </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Eliminada la sección de empleados registrados del dashboard principal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
