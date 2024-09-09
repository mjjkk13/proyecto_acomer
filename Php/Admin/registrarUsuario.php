<!DOCTYPE html>
<html lang="es">
<head>
  <title>A Comer - Registrar Usuario</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/registrarUsuario.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <div class="so">
        <a class="navbar-brand" href="index.html">
          <img id="logo" src="../../css/img/logo.png" alt="Logo" width="40" height="40"> A Comer
        </a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.html">Consultar Estadísticas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="codigosRegistrados.html">Códigos Registrados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="consultarMenu.html">Consultar Menú</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="datosPersonales.html">Datos Personales</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Administrador
            </a>
            <ul class="dropdown-menu" aria-labelledby="adminDropdown">
              <li><a class="dropdown-item" href="../Admin/consultarCursos.html">Cursos Disponibles</a></li>
              <li><a class="dropdown-item" href="../Admin/registrarUsuario.php">Registrar Nuevo Usuario</a></li>
              <li><a class="dropdown-item" href="../Admin/usuarios.html">Usuarios</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../php_basesDatos/loggout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="card mt-5 mx-auto">
      <h2 class="text-center mb-4">Registrar Usuario</h2>
      <form method="POST" action="../../php_basesDatos/registrarUsuario.php" id="registroForm">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombres:</label>
              <input type="text" class="form-control" name="nombre" placeholder="Ingrese sus nombres">
            </div>
            <div class="mb-3">
              <label for="apellido" class="form-label">Apellidos:</label>
              <input type="text" class="form-control" name="apellido" placeholder="Ingrese sus apellidos">
            </div>
            <div class="mb-3">
              <label for="correo" class="form-label">Correo:</label>
              <input type="email" class="form-control" name="correo" placeholder="Ingrese su correo electrónico">
            </div>
            <div class="mb-3">
              <label for="contrasena" class="form-label">Contraseña:</label>
              <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Ingrese una contraseña">
              <input type="checkbox" id="verContrasena"> Mostrar Contraseña
            </div>
            <div class="mb-3">
              <label for="celular" class="form-label">Celular:</label>
              <input type="number" class="form-control" name="celular" placeholder="Ingrese su número de celular">
            </div>
            <div class="mb-3">
              <label for="user" class="form-label">Usuario:</label>
              <input type="text" class="form-control" name="user" placeholder="Ingrese su usuario">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección:</label>
              <input type="text" class="form-control" name="direccion" placeholder="Ingrese su dirección">
            </div>
            <div class="mb-3">
              <label for="documento" class="form-label">Documento de Identidad:</label>
              <input type="number" class="form-control" name="documento" placeholder="Ingrese su número de documento">
            </div>
            <div class="mb-3">
              <label for="tipoDocumento" class="form-label">Tipo de Documento:</label>
              <select class="form-select" name="tipoDocumento">
              <option value="" disabled selected>Seleccionar</option>
                <option value="TI">Tarjeta de Identidad</option>
                <option value="PA">Pasaporte</option>
                <option value="PR">Permiso Especial de Permanencia</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="CE">Cédula de Extranjería</option>
                <option value="RC">Registro Civil</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="rol" class="form-label">Rol:</label>
              <select class="form-select" id="rol" name="rol">
                <option value="" disabled selected>Seleccionar</option>
                <option value="Estudiante SS">Estudiante Servicio Social</option>
                <option value="Docente">Docente</option>
                <option value="Administrador">Administrador</option>
              </select>
            </div>
            <div class="mb-3" id="cursosDisponibles" style="display: none;">
              <label for="cursos" class="form-label">Cursos Disponibles:</label>
              <select class="form-select" id="cursos" name="cursos">
                <?php
                // Include database connection
                include("../../php_basesDatos/conexion.php");

                // Query to get courses
                $sql = "SELECT idcursos, nombrecurso FROM cursos";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Generate dropdown options
                foreach ($cursos as $curso) {
                  echo '<option value="' . htmlspecialchars($curso['idcursos']) . '"> ' . htmlspecialchars($curso['nombrecurso']) . '</option>';
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100" name="submit" id="submit">Enviar</button>
      </form>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <p>&copy; 2024 Plataforma Educativa. Todos los derechos reservados.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../javascript/registroUsuarios.js"></script>
</body>
</html>
