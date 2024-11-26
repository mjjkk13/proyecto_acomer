<?php
session_start();
session_unset();
session_destroy();
header("Location: C:/xampp/htdocs/proyecto_acomer/client/src/components/Landing/Landing.jsx"); // Redirige a la página de inicio de sesión
exit();
?>
