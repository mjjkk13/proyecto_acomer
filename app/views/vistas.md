# AQUI SE COLOCAN LOS ARCHIVOS DE LA CARPETA php

Es recomendable cambiar el .html por php para la mejor adaptabilidad

EJ Con el index.html
```
<!DOCTYPE html>
<html lang="es">
<head>
  <title>A Comer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/index.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="<?php echo BASE_URL; ?>/css/img/logo.png" alt="Logo" width="40" height="40">
                <span class="navbar-text">A Comer</span>
            </a>
        </div>
    </nav>

    <section class="hero">
        <div class="container text-center">
            <h1 class="display-4">¡Bienvenido a nuestra plataforma educativa!</h1>
            <p class="lead">¿Qué deseas hacer?</p>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-box" id="estudianteBox">
                        <h3>Estudiante</h3>
                        <p>Accede al lector Qr y otras funciones de nuestro sistema.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-box" id="docenteBox">
                        <h3>Docente</h3>
                        <p>Ingresa a las listas de estudiantes, genera tu Qr y observa el menú del día.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-box" id="adminBox">
                        <h3>Administrador</h3>
                        <p>Gestiona usuarios, contenido y más desde nuestra plataforma.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        document.getElementById("estudianteBox").addEventListener("click", function() {
            window.location.href = "<?php echo BASE_URL; ?>/iniciarSesion";
        });

        document.getElementById("docenteBox").addEventListener("click", function() {
            window.location.href = "<?php echo BASE_URL; ?>/iniciarSesion";
        });

        document.getElementById("adminBox").addEventListener("click", function() {
            window.location.href = "<?php echo BASE_URL; ?>/iniciarSesion";
        });
    </script>

    <footer class="footer mt-5">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Plataforma Educativa. Todos los derechos reservados.</p>
        </div>
    </footer>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```


