<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Libros - Biblioteca</title>
    <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">
    <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
    <script src="./wwwroot/js/libros.js"></script>
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
              <a href="autores.php" class="nav-link text-white">
                <i class="bi bi-people-fill pe-1"></i> Autores
              </a>
              <a href="libros.php" class="nav-link text-white active">
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
          <h1 class="h2">Gestión de Libros</h1>
          <p class="text-muted mb-0">Usuario: <strong id="userName">-</strong></p>
        </div>
      </div>

      <div id="dashboardMessage" class="alert d-none" role="alert"></div>

      <div class="row gy-4">
        <div class="col-lg-5">
          <div class="card shadow-sm">
            <div class="card-header">
              <strong>Agregar Libro</strong>
            </div>
            <div class="card-body">
              <div id="booksError" class="alert alert-danger d-none" role="alert"></div>
              <div class="mb-3">
                <label for="bookName" class="form-label">Título del libro</label>
                <input type="text" id="bookName" class="form-control" placeholder="Nombre del libro" autocomplete="off">
              </div>
              <div class="mb-3">
                <label for="bookAuthor" class="form-label">Autor</label>
                <select id="bookAuthor" class="form-select">
                  <option value="">Selecciona un autor</option>
                </select>
              </div>
              <button type="button" class="btn btn-primary" onclick="createBook()">Guardar libro</button>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <strong>Lista de Libros</strong>
              <button class="btn btn-sm btn-outline-secondary" type="button" onclick="loadBooks()">Actualizar</button>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover mb-0" id="booksTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Libro</th>
                    <th>Autor</th>
                    <th>Disponible</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td colspan="4" class="text-center text-muted">Cargando libros...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <script>
      $(document).ready(function() {
        loadBooks();
      });
    </script>
    <script src="./wwwroot/js/bootstrap.bundle.min.js"></script>
  </body>
</html>