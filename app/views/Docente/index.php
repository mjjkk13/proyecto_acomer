<?php
require_once "C:/xampp/htdocs/Proyecto/app/helpers/auth.php";
checkUserRole('Docente');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>A Comer - Listados</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/estudiante.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="../../assets/plugins/qrCode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg ">
    <div class="container" style="padding: 0px;">
      <a class="navbar-brand" href="index.php">
        <img id="logo" src="../css/img/logo.png" alt="Logo" width="40" height="40"> A Comer
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.html"> Consulta Listados </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="codigosRegistrados.html"> Códigos Registrados </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="consultarMenu.html"> Consultar Menú </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="datosPersonales.html"> Datos Personales </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../models/loggout.php">Cerrar Sesión</a> 
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1 style="text-align: center; margin-top: 20px;">Listado de Estudiantes</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Curso</th>
      
          <th>Asistencia</th>
        </tr>
      </thead>
      <tbody id="tablaEstudiantes">
      </tbody>
    </table>
    <div class="text-center">
      <button class="btn mx-auto" id="btnGenerarQR">Generar Código QR</button>
    </div>
  </div>

  <footer class="footer">
    <div class="container text-center">
      <p>&copy; 2024 Plataforma Educativa. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="../javascript/generadorQR.js"></script>
</body>
</html>
