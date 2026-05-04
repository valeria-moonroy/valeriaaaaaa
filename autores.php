<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Autores - Biblioteca</title>
    <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">
    <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
    <script src="./wwwroot/js/autores.js"></script>
  </head>
  <body>
    <header>
      <div class="px-3 py-2 text-bg-primary border-bottom">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="dashboard.php" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
              <i class="bi bi-book-half fw-bold fs-5 pe-2"></i> Biblioteca
            </a>
            <nav class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
              <a href="dashboard.php" class="nav-link text-white">
                <i class="bi bi-house-door pe-1"></i> Dashboard
              </a>
              <a href="autores.php" class="nav-link text-white active">
                <i class="bi bi-people-fill pe-1"></i> Autores
              </a>
              <a href="libros.php" class="nav-link text-white">
                <i class="bi bi-book-fill pe-1"></i> Libros
              </a>
              <a href="prestamos.php" class="nav-link text-white">
                <i class="bi bi-journal-bookmark-fill pe-1"></i> Préstamos
              </a>
              <a href="logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right pe-1"></i> Salir
              </a>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <main class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h2">Gestión de Autores</h1>
          <p class="text-muted mb-0">Usuario: <strong id="userName">-</strong></p>
        </div>
      </div>

      <div id="dashboardMessage" class="alert d-none" role="alert"></div>

      <div class="row gy-4">
        <div class="col-lg-5">
          <div class="card shadow-sm">
            <div class="card-header">
              <strong>Agregar Autor</strong>
            </div>
            <div class="card-body">
              <div id="authorsError" class="alert alert-danger d-none" role="alert"></div>
              <div class="mb-3">
                <label for="authorName" class="form-label">Nombre del autor</label>
                <input type="text" id="authorName" class="form-control" placeholder="Nombre completo" autocomplete="off">
              </div>
              <button type="button" class="btn btn-primary" onclick="createAuthor()">Guardar autor</button>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <strong>Lista de Autores</strong>
              <button class="btn btn-sm btn-outline-secondary" type="button" onclick="loadAuthors()">Actualizar</button>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0" id="authorsTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td colspan="2" class="text-center text-muted">Cargando autores...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
      $(document).ready(function() {
        loadAuthors();
      });
    </script>
    <script src="./wwwroot/js/bootstrap.bundle.min.js"></script>
  </body>
</html>