<?php
require_once 'auth_helper.php';

// Si está autenticado, redirigir al dashboard
if(isAuthenticated()) {
    header("Location: dashboard.php");
    exit();
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <!-- Bootstrap -->
  <link href="./wwwroot/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./wwwroot/css/bootstrap-icons.min.css">

  <!-- JS -->
  <script src="./wwwroot/js/jquery-4.0.0.min.js"></script>
  <script src="./wwwroot/js/script.js"></script>

  <!-- Estilos modernos -->
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #f9cbe5, #e0e7ff);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', sans-serif;
    }

    .login-card {
      backdrop-filter: blur(15px);
      background: rgba(255, 255, 255, 0.85);
      border-radius: 20px;
      border: 1px solid rgba(255,255,255,0.3);
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid #ddd;
      padding: 12px;
      transition: all 0.25s ease;
    }

    .form-control:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
    }

    .form-label {
      font-weight: 500;
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, #6366f1, #7c3aed);
      border: none;
      border-radius: 12px;
      padding: 12px;
      color: white;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .btn-primary-custom:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(99,102,241,0.3);
    }

    a {
      color: #6366f1;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    input:invalid {
      border-color: #ef4444;
    }

    input:valid {
      border-color: #22c55e;
    }

    .input-group-text {
      border-radius: 12px 0 0 12px;
      background: #f1f1f1;
      border: 1px solid #ddd;
    }

    .input-group .form-control {
      border-radius: 0 12px 12px 0;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">

        <div class="login-card p-5">

          <div class="text-center mb-4">
            <h1 class="fw-bold">Iniciar Sesión</h1>
            <p class="text-muted">Bienvenida de nuevo 👋</p>
          </div>

          <form id="loginForm" novalidate>

            <div id="loginError" class="alert alert-danger d-none"></div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
          
                <input 
                  class="form-control" 
                  id="email" 
                  type="email" 
                  required 
                  autocomplete="username">
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              
              <input 
                  class="form-control" 
                  type="password" 
                  id="pwd" 
                  required 
                  autocomplete="current-password">
            </div>

            <!-- Botón -->
            <button 
              type="button" 
              class="btn btn-primary-custom w-100 mt-3"
              onclick="login();"
              id="loginBtn">
              Iniciar sesión
            </button>

            <!-- Registro -->
            <div class="text-center mt-4">
              <span class="small text-muted">¿No tienes cuenta?</span>
              <a href="registro.php">Crear cuenta</a>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>
</body>
</html>