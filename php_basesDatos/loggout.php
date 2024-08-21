<?php
session_start();
session_unset();
session_destroy();
header("Location: ../Php/iniciarSesion.html"); // Redirige a la página de inicio de sesión
exit();
?>
