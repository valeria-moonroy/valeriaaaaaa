<?php
require_once 'auth_helper.php';
if(!isAuthenticated()) {
    header("Location: index.php");
    exit();
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca Digital 2026</title>
    <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">
    <style>
      /* Estilos desde tailwindcss.com con un toque personalizado */
      .navbar-brand {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
      }
      .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      }
      .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
      }
      .stats-card .card-body {
        padding: 2rem;
      }
      .sidebar {
        background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
      }
      .nav-link {
        border-radius: 8px;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
      }
      .nav-link:hover {
        background-color: rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
      }
      .btn-modern {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
      }
      .btn-modern:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
      }
      @media (max-width: 767.98px) {
        .sidebar {
          position: fixed;
          top: 0;
          left: -100%;
          width: 280px;
          z-index: 1050;
          transition: left 0.3s ease;
        }
        .sidebar.show {
          left: 0;
        }
        .sidebar-backdrop {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0,0,0,0.5);
          z-index: 1040;
          display: none;
        }
        .sidebar-backdrop.show {
          display: block;
        }
      }
    </style>
    <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
    <script src="./wwwroot/js/script.js"></script>
    <script src="./wwwroot/js/dashboard.js"></script>
  </head>
  <body class="bg-light">
    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="container-fluid">
        <button class="btn btn-outline-light d-lg-none me-2" type="button" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>
        <a class="navbar-brand fs-3" href="#">
          <i class="bi bi-book-half me-2"></i>Biblioteca Digital 2026
        </a>
        <div class="d-flex align-items-center">
          <span class="text-white me-3">
            <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
          </span>
          <a href="logout.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Salir
          </a>
        </div>
      </div>
    </nav>

    <!-- Sidebar Backdrop para móviles -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="sidebar d-flex flex-column p-3" id="sidebar">
      <h5 class="fw-bold text-primary mb-4">
        <i class="bi bi-grid-3x3-gap me-2"></i>Menú Principal
      </h5>
      <nav class="nav nav-pills flex-column flex-grow-1">
        <a class="nav-link active mb-2" href="dashboard.php">
          <i class="bi bi-house-door-fill me-2"></i>Dashboard
        </a>
        <a class="nav-link mb-2" href="autores.php">
          <i class="bi bi-people-fill me-2"></i>Autores
        </a>
        <a class="nav-link mb-2" href="libros.php">
          <i class="bi bi-book-fill me-2"></i>Libros
        </a>
        <a class="nav-link mb-2" href="prestamos.php">
          <i class="bi bi-journal-bookmark-fill me-2"></i>Préstamos
        </a>
      </nav>
      <div class="mt-auto">
        <div class="text-center text-muted small">
          <i class="bi bi-shield-check me-1"></i>Sistema Seguro
        </div>
      </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-grow-1 p-4" id="mainContent">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h2 fw-bold text-primary mb-1">Panel de Control</h1>
            <p class="text-muted mb-0">Gestiona tu biblioteca digital con facilidad</p>
          </div>
          <div class="d-none d-md-block">
            <small class="text-muted"><?php echo date('d/m/Y H:i'); ?></small>
          </div>
        </div>

        <div id="dashboardMessage" class="alert d-none" role="alert"></div>

        <!-- Cards de Módulos -->
        <div class="row g-4 mb-5">
          <div class="col-md-6 col-lg-4">
            <div class="card card-hover h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                  <i class="bi bi-people-fill text-white fs-4"></i>
                </div>
                <h5 class="card-title fw-bold">Gestión de Autores</h5>
                <p class="card-text text-muted">Administra el catálogo de autores con herramientas avanzadas.</p>
                <a href="autores.php" class="btn btn-modern">Acceder</a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card card-hover h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                  <i class="bi bi-book-fill text-white fs-4"></i>
                </div>
                <h5 class="card-title fw-bold">Catálogo de Libros</h5>
                <p class="card-text text-muted">Gestiona tu colección de libros y su disponibilidad.</p>
                <a href="libros.php" class="btn btn-modern">Acceder</a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card card-hover h-100 border-0 shadow-sm">
              <div class="card-body text-center p-4">
                <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                  <i class="bi bi-journal-bookmark-fill text-white fs-4"></i>
                </div>
                <h5 class="card-title fw-bold">Sistema de Préstamos</h5>
                <p class="card-text text-muted">Controla préstamos y devoluciones de manera eficiente.</p>
                <a href="prestamos.php" class="btn btn-modern">Acceder</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Estadísticas -->
        <div class="row g-4">
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold text-primary">
                  <i class="bi bi-bar-chart-line me-2"></i>Estadísticas del Sistema
                </h5>
              </div>
              <div class="card-body">
                <div class="row text-center g-4">
                  <div class="col-md-4">
                    <div class="stats-card rounded-3">
                      <div class="card-body">
                        <i class="bi bi-people-fill fs-1 mb-3 opacity-75"></i>
                        <h2 class="fw-bold mb-1" id="totalAuthors">-</h2>
                        <p class="mb-0 opacity-75">Total Autores</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="stats-card rounded-3">
                      <div class="card-body">
                        <i class="bi bi-book-fill fs-1 mb-3 opacity-75"></i>
                        <h2 class="fw-bold mb-1" id="totalBooks">-</h2>
                        <p class="mb-0 opacity-75">Total Libros</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="stats-card rounded-3">
                      <div class="card-body">
                        <i class="bi bi-journal-bookmark-fill fs-1 mb-3 opacity-75"></i>
                        <h2 class="fw-bold mb-1" id="activeLoans">-</h2>
                        <p class="mb-0 opacity-75">Préstamos Activos</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
      // Toggle sidebar en móviles
      document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarBackdrop').classList.toggle('show');
      });

      document.getElementById('sidebarBackdrop').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('show');
        this.classList.remove('show');
      });

      // Ajustar layout para desktop
      if (window.innerWidth >= 992) {
        document.getElementById('sidebar').classList.add('position-fixed');
        document.getElementById('mainContent').style.marginLeft = '280px';
      }
    </script>
    <script src="./wwwroot/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
