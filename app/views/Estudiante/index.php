<?php
require_once "C:/xampp/htdocs/Proyecto/app/helpers/auth.php";
checkUserRole('Estudiante SS');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>A Comer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/estudiante.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="../../../assets/plugins/qrCode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <div class="so">
        <a class="navbar-brand" href="index.php">
          <img id="logo" src="../css/img/logo.png" alt="Logo" width="40" height="40"> A Comer
        </a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php" >
              <i class="fas fa-qrcode"></i> Escanear QR
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="codigosRegistrados.html" > Codigos Registrados </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="consultarMenu.html" > Consultar menú </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="AgregarEstudiante.html"> Agregar Estudiante </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="datosPersonales.html"> Datos Personales </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../models/loggout.php"> Cerrar Sesión </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container qr-scan-area">
    <h3>Escanear QR para acceder al comedor escolar</h3>
    <p>Por favor, escanea el código QR para acceder al comedor escolar.</p>
    <a id="btn-scan-qr" href="#">
      <img src="../css/img/qr2.png" alt="Código QR Comedor Escolar" width="200" id="estudianteBox">
    </a>
    <canvas hidden="" id="qr-canvas" class="img-fluid"></canvas>
  </div>

  <script>
    document.getElementById("estudianteBox").addEventListener("click", function() {
      // Mostrar un diálogo emergente utilizando SweetAlert2
      Swal.fire({
        title: 'Escanea El Codigo QR',
        text: 'Dale permiso a el navegador para usar tú camara',
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Activar camara',
        cancelButtonText: 'Cancelar',
        showDenyButton: true,
        denyButtonText: 'Desactivar cámara',
        imageUrl: '../css/img/qr.png', // Ruta de la imagen de escaneo QR
        imageWidth: 100, // Ancho de la imagen (en píxeles)
        imageHeight: 100, // Alto de la imagen (en píxeles)
        imageAlt: 'Imagen de escaneo QR', // Texto alternativo para la imagen
      }).then((result) => {
        if (result.isDenied) {
            cerrarCamara(); // Si el usuario cancela, apagar la cámara
        } else if (result.isConfirmed) {
            encenderCamara(); // Si el usuario confirma, encender la cámara
        }
      });
    });
  </script>

  <audio id="audioScaner" src="../../../assets/sonido.mp3"></audio>
  <!-- Indica que las funciones de encender, apagar y leer se dejan a un archivo JS externo -->
  <script src="../../../assets/js/index.js"></script>
  <footer class="footer mt-5">
    <div class="container text-center">
      <p>&copy; 2024 Plataforma Educativa. Todos los derechos reservados.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
