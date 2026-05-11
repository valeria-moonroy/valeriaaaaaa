<?php
require_once 'auth_helper.php';

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
  <title>Registro</title>

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

    .register-card {
      backdrop-filter: blur(15px);
      background: rgba(255, 255, 255, 0.85);
      border-radius: 20px;
      border: 1px solid rgba(255,255,255,0.3);
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
      transition: transform 0.3s ease;
    }

    .register-card:hover {
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
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">

        <div class="register-card p-5">

          <div class="text-center mb-4">
            <h1 class="fw-bold">Crear Cuenta</h1>
            <p class="text-muted">Únete y comienza hoy ✨</p>
          </div>

          <form id="registerForm" novalidate>

            <div id="registroError" class="alert alert-danger d-none"></div>

            <!-- Nombre -->
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre completo</label>
              <input 
                class="form-control" 
                id="nombre" 
                type="text" 
                required 
                autocomplete="name">
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input 
                class="form-control" 
                id="email" 
                type="email" 
                required 
                autocomplete="email">
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="pwd" class="form-label">Contraseña</label>
              <input 
                class="form-control" 
                type="password" 
                id="pwd" 
                required 
                autocomplete="new-password">
            </div>

            <!-- Botón -->
            <button 
              type="button" 
              class="btn btn-primary-custom w-100 mt-3"
              onclick="registroUsuarios();">
              Registrarse
            </button>

            <!-- Login -->
            <div class="text-center mt-4">
              <span class="small text-muted">¿Ya tienes cuenta?</span>
              <a href="index.php">Iniciar sesión</a>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>

</body>
</html>