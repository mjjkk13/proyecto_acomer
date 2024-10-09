<?php
// controllers/SessionController.php

class SessionController {
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /Proyecto/app/views/iniciarSesion.php"); // Redirige a la página de inicio de sesión
        exit();
    }
}
?>
