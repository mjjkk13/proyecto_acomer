<!DOCTYPE html>
<html lang="es">
<head>
  <title>Iniciar Sesión</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../views/css/formulario.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/Proyecto/public/index.php">
          <img src="../views/css/img/logo.png" alt="Logo" width="40" height="40">
          <span class="navbar-text">A Comer</span> 
        </a>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center mb-4">Iniciar Sesión</h5>
            <form id="loginForm" action="../controllers/AuthController.php?action=login" method="POST">
              <div class="mb-3 form-floating">
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                <label for="usuario">Usuario</label>
                <div class="invalid-feedback">Por favor ingresa un usuario válido.</div>
              </div>
              <div class="mb-3 form-floating position-relative">
                <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Contraseña" required>
                <label for="inputPassword">Contraseña</label>
                <div class="invalid-feedback">Por favor ingresa tu contraseña.</div>
                <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer; z-index: 10; width: 20px; height: 20px;"></i>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Recordarme</label>
              </div>
              <div class="mb-3">
                <a href="index.html" class="regresarpapu">Regresar</a>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer mt-5">
    <div class="container text-center">
      <p>&copy; 2024 Plataforma Educativa. Todos los derechos reservados.</p>
    </div>
  </footer>
  
  <script src="../views/javascript/formulario.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>