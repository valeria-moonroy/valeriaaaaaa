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
    <title>Gestión de Autores - Biblioteca Digital 2026</title>
    <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">
    <style>
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
      .nav-link {
        border-radius: 8px;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
      }
      .nav-link:hover {
        background-color: rgba(102, 126, 234, 0.1);
        transform: translateX(5px);
      }
      @media (max-width: 767.98px) {
        .navbar-nav {
          position: fixed;
          top: 56px;
          left: -100%;
          width: 280px;
          height: calc(100vh - 56px);
          background: white;
          z-index: 1050;
          transition: left 0.3s ease;
          padding: 1rem;
        }
        .navbar-nav.show {
          left: 0;
        }
        .navbar-backdrop {
          position: fixed;
          top: 56px;
          left: 0;
          width: 100%;
          height: calc(100vh - 56px);
          background: rgba(0,0,0,0.5);
          z-index: 1040;
          display: none;
        }
        .navbar-backdrop.show {
          display: block;
        }
      }
    </style>
    <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
    <script src="./wwwroot/js/autores.js"></script>
  </head>
  <body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient shadow-sm">
      <div class="container-fluid">
        <button class="navbar-toggler d-lg-none me-2" type="button" id="navbarToggle">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand fs-4" href="dashboard.php">
          <i class="bi bi-book-half me-2"></i>Biblioteca Digital 2026
        </a>
        <div class="d-flex align-items-center d-lg-none">
          <a href="logout.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Salir
          </a>
        </div>
        <div class="collapse navbar-collapse d-none d-lg-block">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">
                <i class="bi bi-house-door me-1"></i>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="autores.php">
                <i class="bi bi-people-fill me-1"></i>Autores
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="libros.php">
                <i class="bi bi-book-fill me-1"></i>Libros
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="prestamos.php">
                <i class="bi bi-journal-bookmark-fill me-1"></i>Préstamos
              </a>
            </li>
            <li class="nav-item ms-3">
              <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right me-1"></i>Salir
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Navbar Backdrop para móviles -->
    <div class="navbar-backdrop" id="navbarBackdrop"></div>

    <!-- Navbar Móvil -->
    <nav class="navbar-nav d-lg-none" id="mobileNav">
      <a class="nav-link mb-2" href="dashboard.php">
        <i class="bi bi-house-door me-2"></i>Dashboard
      </a>
      <a class="nav-link active mb-2" href="autores.php">
        <i class="bi bi-people-fill me-2"></i>Autores
      </a>
      <a class="nav-link mb-2" href="libros.php">
        <i class="bi bi-book-fill me-2"></i>Libros
      </a>
      <a class="nav-link mb-2" href="prestamos.php">
        <i class="bi bi-journal-bookmark-fill me-2"></i>Préstamos
      </a>
      <hr>
      <a class="nav-link text-danger" href="logout.php">
        <i class="bi bi-box-arrow-right me-2"></i>Salir
      </a>
    </nav>

    <!-- Contenido Principal -->
    <main class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h1 class="h2 fw-bold text-primary mb-1">
                <i class="bi bi-people-fill me-2"></i>Gestión de Autores
              </h1>
              <p class="text-muted mb-0">Administra el catálogo de autores de tu biblioteca</p>
            </div>
            <div class="d-none d-md-block">
              <small class="text-muted"><?php echo date('d/m/Y H:i'); ?></small>
            </div>
          </div>

          <div id="dashboardMessage" class="alert d-none" role="alert"></div>

          <div class="row g-4">
            <!-- Formulario Agregar Autor -->
            <div class="col-lg-5">
              <div class="card card-hover border-0 shadow-sm">
                <div class="card-header text-white" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                  <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Agregar Nuevo Autor
                  </h5>
                </div>
                <div class="card-body">
                  <div id="authorsError" class="alert alert-danger d-none" role="alert"></div>
                  <div class="mb-3">
                    <label for="authorName" class="form-label fw-semibold">Nombre del Autor</label>
                    <input type="text" id="authorName" class="form-control form-control-lg" placeholder="Ingrese el nombre completo" autocomplete="off">
                  </div>
                  <button type="button" class="btn btn-modern w-100" onclick="createAuthor()">
                    <i class="bi bi-check-circle me-2"></i>Guardar Autor
                  </button>
                </div>
              </div>
            </div>

            <!-- Lista de Autores -->
            <div class="col-lg-7">
              <div class="card card-hover border-0 shadow-sm">
                <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Lista de Autores
                  </h5>
                  <button class="btn btn-outline-light btn-sm" type="button" onclick="loadAuthors()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0" id="authorsTable">
                      <thead class="table-light">
                        <tr>
                          <th class="border-0 fw-semibold">ID</th>
                          <th class="border-0 fw-semibold">Nombre</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="2" class="text-center text-muted py-4">
                            <i class="bi bi-hourglass-split me-2"></i>Cargando autores...
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
      // Toggle navbar móvil
      document.getElementById('navbarToggle').addEventListener('click', function() {
        document.getElementById('mobileNav').classList.toggle('show');
        document.getElementById('navbarBackdrop').classList.toggle('show');
      });

      document.getElementById('navbarBackdrop').addEventListener('click', function() {
        document.getElementById('mobileNav').classList.remove('show');
        this.classList.remove('show');
      });
    </script>
    <script src="./wwwroot/js/bootstrap.bundle.min.js"></script>
  </body>
</html>