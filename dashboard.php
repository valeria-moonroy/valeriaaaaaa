<?php
session_start();

// ¿Existe la sesión? Si no, fuera de aquí.
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit();
}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca</title>
    <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">
    <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
    <script src="./wwwroot/js/script.js"></script>
    <script src="./wwwroot/js/dashboard.js"></script>
  </head>
  <body>
    <header>
      <div class="px-3 py-2 text-bg-primary border-bottom">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
              <i class="bi bi-book-half fw-bold fs-5 pe-2"></i> Biblioteca
            </a>
          </div>
        </div>
      </div>
    </header>
    <div class="container-fluid">
      <div class="row">
        <aside class="col-12 col-md-4 col-lg-3 col-xl-2 bg-light vh-100 position-fixed pt-4 border-end dashboard-sidebar">
          <div class="px-3">
            <h5 class="fw-semibold">Menú biblioteca</h5>
            <nav>
              <ul class="nav nav-pills flex-column mb-4 dashboard-nav">
                <li class="nav-item">
                  <a class="nav-link active" href="dashboard.php">
                    <i class="bi bi-house-door pe-2"></i>Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="autores.php">
                    <i class="bi bi-people-fill pe-2"></i>Autores
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="libros.php">
                    <i class="bi bi-book-fill pe-2"></i>Libros
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="prestamos.php">
                    <i class="bi bi-journal-bookmark-fill pe-2"></i>Préstamos
                  </a>
                </li>
                <li class="nav-item mt-3">
                  <a class="nav-link text-danger" href="logout.php">
                    <i class="bi bi-box-arrow-right pe-2"></i>Salir
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </aside>

        <main class="col-12 offset-md-4 offset-lg-3 offset-xl-2 pt-4 px-4" id="dashboardContent">
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
              <h1 class="h2">Administración de Biblioteca</h1>
              <p class="text-muted mb-0">Usuario: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            </div>
          </div>

          <div id="dashboardMessage" class="alert d-none" role="alert"></div>

          <div class="row gy-4">
            <div class="col-md-4">
              <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                  <i class="bi bi-people-fill text-primary fs-1 mb-3"></i>
                  <h5 class="card-title">Gestión de Autores</h5>
                  <p class="card-text text-muted">Agregar, editar y gestionar autores de la biblioteca.</p>
                  <a href="autores.php" class="btn btn-primary">Ir a Autores</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                  <i class="bi bi-book-fill text-success fs-1 mb-3"></i>
                  <h5 class="card-title">Gestión de Libros</h5>
                  <p class="card-text text-muted">Administrar el catálogo de libros y su disponibilidad.</p>
                  <a href="libros.php" class="btn btn-success">Ir a Libros</a>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                  <i class="bi bi-journal-bookmark-fill text-info fs-1 mb-3"></i>
                  <h5 class="card-title">Gestión de Préstamos</h5>
                  <p class="card-text text-muted">Controlar préstamos y devoluciones de libros.</p>
                  <a href="prestamos.php" class="btn btn-info">Ir a Préstamos</a>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12">
              <div class="card shadow-sm">
                <div class="card-header">
                  <h5 class="mb-0">Estadísticas Generales</h5>
                </div>
                <div class="card-body">
                  <div class="row text-center">
                    <div class="col-md-4">
                      <div class="p-3">
                        <h3 class="text-primary" id="totalAuthors">-</h3>
                        <p class="text-muted mb-0">Total Autores</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="p-3">
                        <h3 class="text-success" id="totalBooks">-</h3>
                        <p class="text-muted mb-0">Total Libros</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="p-3">
                        <h3 class="text-info" id="activeLoans">-</h3>
                        <p class="text-muted mb-0">Préstamos Activos</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    <script src="./wwwroot/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
